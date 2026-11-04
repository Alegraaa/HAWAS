<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantQris extends Model
{
    protected $table = 'merchant_qris';

    protected $fillable = [
        'nama_merchant',
        'kota',
        'kategori',
        'status_keaslian',
        'catatan',
    ];
}

