<?php

namespace App\Http\Controllers;

use App\Models\KontakDarurat;
use App\Models\LaporanInsiden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class SosController extends Controller
{
    public function index()
    {
        try {
            $all = KontakDarurat::orderBy('id', 'asc')->get();
            $contacts = [
                'bank'      => $all->where('jenis', 'bank'),
                'regulator' => $all->where('jenis', 'regulator'),
                'aparat'    => $all->where('jenis', 'aparat'),
            ];
            $totalLaporan = LaporanInsiden::count();
        } catch (Throwable $e) {
            Log::error('Gagal mengambil kontak darurat SOS: ' . $e->getMessage(), ['exception' => $e]);
            $contacts = ['bank' => collect(), 'regulator' => collect(), 'aparat' => collect()];
            $totalLaporan = 0;
        }

        return view('sos.index', [
            'title'        => 'Bantuan Darurat & Panduan Korban Penipuan (SOS)',
            'activePage'   => 'sos',
            'contacts'     => $contacts,
            'totalLaporan' => $totalLaporan
        ]);
    }

    public function lapor(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_pelapor'  => 'required|string|max:255',
                'bank_korban'   => 'required|string|max:255',
                'kontak_penipu' => 'required|string|max:255',
                'nominal_rugi'  => 'required',
                'kronologi'     => 'required|string',
            ]);

            $nominal = (int) preg_replace('/[^\d]/', '', $validated['nominal_rugi']);

            $laporan = LaporanInsiden::create([
                'nama_pelapor'  => $validated['nama_pelapor'],
                'bank_korban'   => $validated['bank_korban'],
                'kontak_penipu' => $validated['kontak_penipu'],
                'nominal_rugi'  => $nominal,
                'kronologi'     => $validated['kronologi'],
                'status'        => 'tercatat',
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Laporan insiden berhasil dicatat ke database HAWAS.',
                    'id'      => $laporan->id
                ]);
            }

            return redirect()->route('sos.daftar-laporan', ['sukses' => 1]);
        } catch (ValidationException $ve) {
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validasi gagal.',
                    'errors'  => $ve->errors()
                ], 422);
            }
            return back()->withErrors($ve->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan laporan insiden SOS: ' . $e->getMessage(), ['exception' => $e]);

            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Terjadi kesalahan sistem saat menyimpan laporan insiden.'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan sistem saat mencatat laporan. Silakan coba beberapa saat lagi.');
        }
    }

    public function daftarLaporan()
    {
        try {
            $reports = LaporanInsiden::orderBy('id', 'desc')->take(50)->get();
        } catch (Throwable $e) {
            Log::error('Gagal mengambil daftar laporan insiden: ' . $e->getMessage(), ['exception' => $e]);
            $reports = collect();
        }

        return view('sos.laporan', [
            'title'      => 'Daftar Laporan Insiden Tersimpan',
            'activePage' => 'sos',
            'reports'    => $reports
        ]);
    }

    public function hapusLaporan(Request $request)
    {
        try {
            $id = $request->input('id');
            if ($id) {
                LaporanInsiden::destroy($id);
            }
            return redirect()->route('sos.daftar-laporan', ['dihapus' => 1]);
        } catch (Throwable $e) {
            Log::error('Gagal menghapus laporan insiden: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('sos.daftar-laporan', ['error' => 'Gagal menghapus laporan.']);
        }
    }
}
