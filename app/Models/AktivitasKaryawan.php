<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasKaryawan extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_karyawan';

    protected $fillable = [
        'nik',
        'aktivitas',
        'foto',
        'lokasi'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the karyawan that owns the aktivitas.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
