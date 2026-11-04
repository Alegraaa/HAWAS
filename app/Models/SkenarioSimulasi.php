<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkenarioSimulasi extends Model
{
    protected $table = 'skenario_simulasi';

    protected $fillable = [
        'judul',
        'konteks',
        'nominal_rugi',
        'opsi_berisiko',
        'opsi_aman',
        'tampilan_html',
        'penjelasan_risiko',
        'penjelasan_aman',
        'catatan_khusus',
        'urutan',
    ];
}

