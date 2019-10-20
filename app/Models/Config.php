<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = "config";

    protected $fillable = [
        'nama_config',
        'host',
        'username',
        'password',
        'root_path',
    ];
}
