<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatSimulasi extends Model
{
    protected $table = 'riwayat_simulasi';

    protected $fillable = [
        'skor',
        'saldo_akhir',
        'hasil_per_tahap',
    ];

    protected $casts = [
        'hasil_per_tahap' => 'array',
    ];
}

