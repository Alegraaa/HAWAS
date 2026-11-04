<?php

namespace App\Http\Controllers;

use App\Models\MerchantQris;
use App\Models\LogCekQris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class QrisController extends Controller
{
    public function index()
    {
        try {
            $recentLogs = LogCekQris::orderBy('id', 'desc')->take(10)->get();
        } catch (Throwable $e) {
            Log::error('Gagal mengambil log cek QRIS: ' . $e->getMessage(), ['exception' => $e]);
            $recentLogs = collect();
        }

        return view('qris.index', [
            'title'      => 'Validasi & Periksa Nama Merchant QRIS',
            'activePage' => 'qris',
            'recentLogs' => $recentLogs
        ]);
    }

    public function validateQris(Request $request)
    {
        try {
            $namaQris = trim($request->input('nama_qris', ''));
            $namaToko = trim($request->input('nama_toko_tujuan', ''));
            $crcValid = (bool) $request->input('crc_valid', true);

            if (empty($namaQris)) {
                return response()->json(['error' => 'Nama merchant QRIS tidak boleh kosong'], 422);
            }

            $knownMerchant = MerchantQris::whereRaw('UPPER(nama_merchant) LIKE ?', ['%' . strtoupper($namaQris) . '%'])->first();

            $normQris = strtoupper(preg_replace('/[^A-Z0-9]/', '', $namaQris));
            $normToko = strtoupper(preg_replace('/[^A-Z0-9]/', '', $namaToko));

            $isMatch = ($normQris === $normToko);
            $isPartial = (strpos($normQris, $normToko) !== false || strpos($normToko, $normQris) !== false);

            $statusHasil = $isMatch ? 'cocok' : ($isPartial ? 'mirip' : 'berbeda');

            LogCekQris::create([
                'nama_di_qris'     => $namaQris,
                'nama_toko_tujuan' => $namaToko,
                'status_hasil'     => $statusHasil,
                'crc_valid'        => $crcValid,
            ]);

            return response()->json([
                'status'         => 'success',
                'status_hasil'   => $statusHasil,
                'is_match'       => $isMatch,
                'is_partial'     => $isPartial,
                'known_merchant' => $knownMerchant
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal memvalidasi QRIS: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'status' => 'error',
                'error'  => 'Terjadi kesalahan sistem saat memvalidasi data QRIS.'
            ], 500);
        }
    }
}
