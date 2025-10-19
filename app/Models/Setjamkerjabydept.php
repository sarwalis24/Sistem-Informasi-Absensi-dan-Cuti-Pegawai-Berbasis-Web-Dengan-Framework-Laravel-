<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setjamkerjabydept extends Model
{
    use HasFactory;
    protected $table = 'presensi_jamkerja_bydept';
    protected $guarded = [];
    protected $primaryKey = 'kode_jk_dept';
    public $incrementing = false;
}
