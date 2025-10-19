<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statuskawin extends Model
{
    use HasFactory;

    protected $table = "status_kawin";
    protected $primaryKey = "kode_status_kawin";
    public $incrementing = false;
    protected $guarded = [];
}
