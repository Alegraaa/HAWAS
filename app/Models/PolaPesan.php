<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolaPesan extends Model
{
    protected $table = 'pola_pesan';

    protected $fillable = [
        'nama_pola',
        'bobot',
        'regex_pattern',
        'alasan_risiko',
        'solusi_aman',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'bobot' => 'integer',
    ];
}

