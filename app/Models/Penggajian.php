<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;

class Penggajian extends Model
{
    use HasFactory;
    protected $table = 'penggajian';
    protected $guarded = []; // Buka gembok agar bisa disimpan otomatis

    // Jembatan ke tabel Karyawan
    // Contoh di User.php, Absensi.php, atau Penggajian.php
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
