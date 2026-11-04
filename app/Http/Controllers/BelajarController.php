<?php

namespace App\Http\Controllers;

use App\Models\MateriEdukasi;
use App\Models\SoalKuis;
use App\Models\RiwayatKuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class BelajarController extends Controller
{
    public function index()
    {
        try {
            $habits = MateriEdukasi::orderBy('nomor_urut', 'asc')->get();
            $questions = SoalKuis::orderBy('id', 'asc')->get();
        } catch (Throwable $e) {
            Log::error('Gagal mengambil materi belajar & kuis: ' . $e->getMessage(), ['exception' => $e]);
            $habits = collect();
            $questions = collect();
        }

        return view('belajar.index', [
            'title'      => 'Belajar 3 Kebiasaan Keamanan Transaksi',
            'activePage' => 'belajar',
            'habits'     => $habits,
            'questions'  => $questions
        ]);
    }

    public function submitQuiz(Request $request)
    {
        try {
            $answers = $request->input('answers', []);
            $questions = SoalKuis::orderBy('id', 'asc')->get();
            $total = $questions->count();
            $score = 0;
            $evaluations = [];

            foreach ($questions as $q) {
                $qid = $q->id;
                $ans = strtoupper($answers[$qid] ?? '');
                $isCorrect = ($ans === $q->kunci_jawaban);
                if ($isCorrect) {
                    $score += 25;
                }

                $evaluations[$qid] = [
                    'user_answer'    => $ans,
                    'correct_answer' => $q->kunci_jawaban,
                    'is_correct'     => $isCorrect,
                    'penjelasan'     => $q->penjelasan,
                ];
            }

            RiwayatKuis::create([
                'skor'       => $score,
                'total_soal' => $total,
            ]);

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'skor'       => $score,
                    'total_soal' => $total,
                    'evaluasi'   => $evaluations
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal memproses pengiriman kuis: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'status' => 'error',
                'error'  => 'Terjadi kesalahan sistem saat menilai kuis.'
            ], 500);
        }
    }
}
