<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setjamkerjabydate extends Model
{
    use HasFactory;
    protected $table = 'presensi_jamkerja_bydate';
    protected $guarded = [];
}
