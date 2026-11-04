<?php

namespace App\Http\Controllers;

use App\Models\PolaPesan;
use App\Models\RiwayatCek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CekController extends Controller
{
    public function index()
    {
        try {
            $patterns = PolaPesan::where('aktif', true)->get();
        } catch (Throwable $e) {
            Log::error('Gagal mengambil data pola pesan: ' . $e->getMessage(), ['exception' => $e]);
            $patterns = collect();
        }

        return view('cek.index', [
            'title'      => 'Periksa Pesan & Tautan Mencurigakan',
            'activePage' => 'cek',
            'patterns'   => $patterns
        ]);
    }

    public function analyze(Request $request)
    {
        try {
            $text = trim($request->input('text', ''));

            if (empty($text)) {
                return response()->json(['error' => 'Teks pesan tidak boleh kosong'], 422);
            }

            $patterns = PolaPesan::where('aktif', true)->get();
            $findings = [];
            $totalWeight = 0;

            foreach ($patterns as $p) {
                $regex = '/' . str_replace('/', '\/', $p->regex_pattern) . '/i';
                if (@preg_match($regex, $text, $matches)) {
                    if ($p->bobot == 1 && strpos($p->nama_pola, 'Menyebut') === 0) {
                        $hasOtpReq = false;
                        foreach ($findings as $f) {
                            if (strpos($f['nama_pola'], 'Permintaan OTP') === 0) {
                                $hasOtpReq = true;
                                break;
                            }
                        }
                        if ($hasOtpReq) continue;
                    }

                    $findings[] = [
                        'nama_pola'     => $p->nama_pola,
                        'bobot'         => (int)$p->bobot,
                        'alasan_risiko' => $p->alasan_risiko,
                        'solusi_aman'   => $p->solusi_aman,
                        'snippet'       => mb_substr($matches[0], 0, 60),
                    ];
                    $totalWeight += (int)$p->bobot;
                }
            }

            // Analisis Tautan Web
            $urlRegex = '~\\b(?:https?://|www\\.)[^\\s<>"\'\']+|\\b(?:bit\\.ly|tinyurl\\.com|s\\.id|cutt\\.ly|t\\.ly|is\\.gd|shorturl\\.at|rb\\.gy)/\\S+~i';
            if (preg_match_all($urlRegex, $text, $urlMatches)) {
                $urls = $urlMatches[0];
                $findings[] = [
                    'nama_pola'     => 'Ditemukan tautan web (URL) di dalam pesan',
                    'bobot'         => 1,
                    'alasan_risiko' => 'Tautan bisa mengarahkan ke halaman tiruan yang sengaja dibuat menyerupai halaman login bank atau marketplace.',
                    'solusi_aman'   => 'Jangan klik tautan tersebut langsung dari chat. Buka browser dan ketik alamat resmi sendiri.',
                    'snippet'       => $urls[0],
                ];
                $totalWeight += 1;

                foreach ($urls as $u) {
                    $host = parse_url((strpos($u, 'http') === 0 ? '' : 'http://') . $u, PHP_URL_HOST);
                    if (!$host) continue;

                    if (preg_match('/^(?:bit\.ly|tinyurl\.com|s\.id|cutt\.ly|t\.ly|is\.gd|shorturl\.at|rb\.gy)$/i', $host)) {
                        $findings[] = [
                            'nama_pola'     => 'Tautan dipersingkat (Shortlink)',
                            'bobot'         => 2,
                            'alasan_risiko' => 'Tautan pendek menyembunyikan nama domain asli tujuan pengalihan.',
                            'solusi_aman'   => 'Hindari membukanya karena sering dipakai menyamarkan situs jebakan.',
                            'snippet'       => $host,
                        ];
                        $totalWeight += 2;
                    }

                    if (preg_match('/^\d{1,3}(?:\.\d{1,3}){3}$/', $host)) {
                        $findings[] = [
                            'nama_pola'     => 'Format domain tidak lazim (IP Murni)',
                            'bobot'         => 3,
                            'alasan_risiko' => 'Alamat berupa angka IP murni adalah indikasi kuat server phising ilegal.',
                            'solusi_aman'   => 'Jangan dibuka sama sekali.',
                            'snippet'       => $host,
                        ];
                        $totalWeight += 3;
                    }
                }
            }

            $highRiskItems = array_filter($findings, fn($x) => $x['bobot'] >= 3);
            $isHighRisk = $totalWeight >= 4 || !empty($highRiskItems);
            $tingkatBahaya = $isHighRisk ? 'BAHAYA TINGGI' : ($totalWeight >= 1 ? 'WASPADA' : 'AMAN');

            // Log ke tabel riwayat_cek
            RiwayatCek::create([
                'pesan_ringkas'   => mb_substr($text, 0, 120),
                'tingkat_bahaya'  => $tingkatBahaya,
                'bobot_skor'      => $totalWeight,
                'jumlah_indikasi' => count($findings),
                'detail_temuan'   => $findings,
            ]);

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'tingkat_bahaya'  => $tingkatBahaya,
                    'total_bobot'     => $totalWeight,
                    'is_high_risk'    => (bool) $isHighRisk,
                    'jumlah_temuan'   => count($findings),
                    'daftar_temuan'   => $findings,
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menganalisis pesan: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'status' => 'error',
                'error'  => 'Terjadi kesalahan sistem saat menganalisis pesan.'
            ], 500);
        }
    }

    public function riwayat()
    {
        try {
            $history = RiwayatCek::orderBy('id', 'desc')->take(20)->get();
        } catch (Throwable $e) {
            Log::error('Gagal mengambil riwayat cek: ' . $e->getMessage(), ['exception' => $e]);
            $history = collect();
        }

        return view('cek.riwayat', [
            'title'      => 'Riwayat Pemeriksaan Pesan',
            'activePage' => 'cek',
            'history'    => $history
        ]);
    }
}
