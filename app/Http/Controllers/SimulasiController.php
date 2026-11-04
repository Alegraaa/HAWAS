<?php

namespace App\Http\Controllers;

use App\Models\SkenarioSimulasi;
use App\Models\RiwayatSimulasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class SimulasiController extends Controller
{
    public function index()
    {
        try {
            $skenarios = SkenarioSimulasi::orderBy('urutan', 'asc')->get()->map(function($s) {
                return [
                    't'    => $s->judul,
                    'ctx'  => $s->konteks,
                    'loss' => (int) $s->nominal_rugi,
                    'risk' => $s->opsi_berisiko,
                    'safe' => $s->opsi_aman,
                    'ph'   => $s->tampilan_html,
                    'r'    => $s->penjelasan_risiko,
                    's'    => $s->penjelasan_aman,
                    'n'    => $s->catatan_khusus
                ];
            });
        } catch (Throwable $e) {
            Log::error('Gagal memuat skenario simulasi: ' . $e->getMessage(), ['exception' => $e]);
            $skenarios = collect();
        }

        return view('simulasi.index', [
            'title'      => 'Simulasi Transaksi Berisiko',
            'activePage' => 'simulasi',
            'skenarios'  => $skenarios
        ]);
    }

    public function save(Request $request)
    {
        try {
            $validated = $request->validate([
                'skor'            => 'required|integer',
                'saldo_akhir'     => 'required|integer',
                'hasil_per_tahap' => 'nullable|array',
            ]);

            $riwayat = RiwayatSimulasi::create([
                'skor'            => $validated['skor'],
                'saldo_akhir'     => $validated['saldo_akhir'],
                'hasil_per_tahap' => $validated['hasil_per_tahap'] ?? [],
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Hasil simulasi berhasil dicatat ke database.',
                'id'      => $riwayat->id
            ]);
        } catch (ValidationException $ve) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi data simulasi gagal.',
                'errors'  => $ve->errors()
            ], 422);
        } catch (Throwable $e) {
            Log::error('Gagal mencatat hasil simulasi: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem saat menyimpan hasil simulasi.'
            ], 500);
        }
    }

    public function riwayat()
    {
        try {
            $history = RiwayatSimulasi::orderBy('id', 'desc')->take(20)->get();
        } catch (Throwable $e) {
            Log::error('Gagal memuat riwayat simulasi: ' . $e->getMessage(), ['exception' => $e]);
            $history = collect();
        }

        return view('simulasi.history', [
            'title'      => 'Riwayat Simulasi Transaksi',
            'activePage' => 'simulasi',
            'history'    => $history
        ]);
    }
}
