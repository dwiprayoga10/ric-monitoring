<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Swdkllj extends Model
{
    protected $table = 'swdklljs';

    protected $fillable = [
    'no',
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
    'metode_pembayaran', // tambah ini
    'nominal_bayar',
    'source_file',
];

    protected $casts = [
        'masa_berlaku' => 'date',
        'nominal_bayar' => 'decimal:2',
    ];
}