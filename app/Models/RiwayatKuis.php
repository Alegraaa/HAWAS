<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKuis extends Model
{
    protected $table = 'riwayat_kuis';

    protected $fillable = [
        'skor',
        'total_soal',
    ];
}

