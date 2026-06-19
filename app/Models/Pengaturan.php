<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturans';

    protected $fillable = [
        'nama_aplikasi',
        'ascii_header',
        'versi',
        'analisis_tambahan_arsenal',
        'catatan_pengembang_kontak',
        'waktu_respons_kontak',
    ];
}
