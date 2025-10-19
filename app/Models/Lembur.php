<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;
    protected $table = 'lembur';
    protected $fillable = [
        'nik',
        'tanggal',
        'lembur_mulai',
        'lembur_selesai',
        'keterangan',
        'status',
        'lembur_in',
        'lembur_out',
        'foto_lembur_in',
        'foto_lembur_out',
        'lokasi_lembur_in',
        'lokasi_lembur_out',
    ];
}
