<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'bulan',
        'tahun',
        'kategori',
        'lokasi',
        'ruangan',
        'kondisi',
        'deskripsi',
        'foto_paths',
        'status',
        'tanggal_pengerjaan',
        'detail_pengerjaan',
        'nama_tukang',
        'estimasi',
    ];

    protected $casts = [
        'foto_paths' => 'array',
        'tanggal' => 'date',
        'tanggal_pengerjaan' => 'date',
    ];
}
