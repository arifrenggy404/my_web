<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    protected $table = 'kontaks';

    protected $fillable = [
        'nama',
        'username_label',
        'tautan_url',
        'tipe_ikon',
        'warna_hover',
        'urutan',
    ];
}
