<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bpjstenagakerja extends Model
{
    use HasFactory;
    protected $table = "karyawan_bpjstenagakerja";
    protected $primaryKey = "kode_bpjs_tk";
    public $incrementing = false;
    protected $guarded = [];
}
