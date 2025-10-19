<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bpjskesehatan extends Model
{
    use HasFactory;

    protected $table = "karyawan_bpjskesehatan";
    protected $primaryKey = "kode_bpjs_kesehatan";
    public $incrementing = false;
    protected $guarded = [];
}
