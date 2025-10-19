<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gajipokok extends Model
{
    use HasFactory;
    protected $table = "karyawan_gaji_pokok";
    protected $primaryKey = "kode_gaji";
    protected $guarded = [];
    public $incrementing = false;
}
