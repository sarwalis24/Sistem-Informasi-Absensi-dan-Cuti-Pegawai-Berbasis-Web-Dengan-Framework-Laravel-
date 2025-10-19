<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izindinas extends Model
{
    use HasFactory;
    protected $table = 'presensi_izindinas';
    protected $primaryKey = 'kode_izin_dinas';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';
}
