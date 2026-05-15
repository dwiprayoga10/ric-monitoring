<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Swdkllj extends Model
{
    protected $table = 'swdklljs';

    protected $fillable = [
    'id_petugas',
    'nama_ric',
    'nopol',
    'nama_wp',
    'alamat',
    'masa_berlaku',
    'gol',
    'no_hp',
    'status_penyerahan',
    'status_kepemilikan',
    'status_bayar',
    'source_file',
];
}