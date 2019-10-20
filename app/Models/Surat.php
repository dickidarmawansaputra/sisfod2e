<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = "surat";
    protected $fillable = [
        'no_surat',
        'perihal_surat',
        'tgl_surat',
        'pengirim',
        'jenis_surat',
        'deskripsi',
        'gambar'
    ];
}
