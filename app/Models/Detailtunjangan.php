<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailtunjangan extends Model
{
    use HasFactory;

    protected $table = "karyawan_tunjangan_detail";
    protected $guarded = [];
}
