<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tunjangan extends Model
{
    use HasFactory;
    protected $table = "karyawan_tunjangan";
    protected $guarded = [];
    protected $primaryKey = "kode_tunjangan";
    public $incrementing = false;
}
