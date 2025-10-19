<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approveizinsakit extends Model
{
    use HasFactory;
    protected $table = 'presensi_izinsakit_approve';
    protected $guarded = [];
}
