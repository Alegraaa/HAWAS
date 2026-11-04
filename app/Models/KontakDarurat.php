<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakDarurat extends Model
{
    protected $table = 'kontak_darurat';

    protected $fillable = [
        'nama_institusi',
        'nomor_telepon',
        'jenis',
        'url_resmi',
        'deskripsi',
    ];
}

