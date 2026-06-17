<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;

#[Table('proyek')]
#[Fillable([
    'nama_proyek',
    'tautan_slug',
    'deskripsi',
    'teknologi_utama',
    'tautan_langsung',
    'tautan_github',
    'jalur_gambar'
])]
#[Casts([
    'teknologi_utama' => 'array'
])]
class Proyek extends Model
{
    // Model dikonfigurasi sepenuhnya via Attributes bawaan Laravel 13
}
