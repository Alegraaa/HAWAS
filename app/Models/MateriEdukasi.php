<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriEdukasi extends Model
{
    protected $table = 'materi_edukasi';

    protected $fillable = [
        'nomor_urut',
        'judul',
        'deskripsi_risiko',
        'tindakan_aman',
        'link_skenario',
    ];
}

