<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatCek extends Model
{
    protected $table = 'riwayat_cek';

    protected $fillable = [
        'pesan_ringkas',
        'tingkat_bahaya',
        'bobot_skor',
        'jumlah_indikasi',
        'detail_temuan',
    ];

    protected $casts = [
        'detail_temuan' => 'array',
        'bobot_skor' => 'integer',
        'jumlah_indikasi' => 'integer',
    ];
}

