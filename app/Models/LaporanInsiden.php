<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanInsiden extends Model
{
    protected $table = 'laporan_insiden';

    protected $fillable = [
        'nama_pelapor',
        'bank_korban',
        'kontak_penipu',
        'nominal_rugi',
        'kronologi',
        'status',
    ];

    protected $casts = [
        'nominal_rugi' => 'integer',
    ];
}

