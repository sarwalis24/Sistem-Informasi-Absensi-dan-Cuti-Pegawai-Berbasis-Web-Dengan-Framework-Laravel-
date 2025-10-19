<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamkerja extends Model
{
    use HasFactory;
    protected $table = 'presensi_jamkerja';
    protected $primaryKey = 'kode_jam_kerja';
    protected $guarded = [];
    public $incrementing = false;
}
