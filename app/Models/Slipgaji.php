<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slipgaji extends Model
{
    use HasFactory;
    protected $table = 'slip_gaji';
    protected $guarded = [];
    protected $primaryKey = 'kode_slip_gaji';
    public $incrementing = false;
}
