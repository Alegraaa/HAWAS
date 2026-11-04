<?php

namespace App\Http\Controllers;

use App\Models\SkenarioSimulasi;
use App\Models\PolaPesan;
use App\Models\MerchantQris;
use App\Models\LaporanInsiden;
use Illuminate\Support\Facades\Log;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $stats = [
                'total_skenario' => SkenarioSimulasi::count(),
                'total_pola'     => PolaPesan::count(),
                'total_merchant' => MerchantQris::count(),
                'total_laporan'  => LaporanInsiden::count(),
                'db_driver'      => strtoupper(config('database.default')),
            ];
        } catch (Throwable $e) {
            Log::error('Gagal mengambil statistik beranda: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            $stats = [
                'total_skenario' => 0,
                'total_pola'     => 0,
                'total_merchant' => 0,
                'total_laporan'  => 0,
                'db_driver'      => 'DATABASE ERROR',
            ];
        }

        return view('home.index', [
            'title'      => 'Beranda Literasi Keamanan Transaksi',
            'activePage' => 'home',
            'stats'      => $stats
        ]);
    }
}
