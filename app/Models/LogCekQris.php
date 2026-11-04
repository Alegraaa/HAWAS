<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogCekQris extends Model
{
    protected $table = 'log_cek_qris';

    protected $fillable = [
        'nama_di_qris',
        'nama_toko_tujuan',
        'status_hasil',
        'crc_valid',
    ];

    protected $casts = [
        'crc_valid' => 'boolean',
    ];
}

