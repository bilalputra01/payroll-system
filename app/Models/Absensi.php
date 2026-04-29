<?php

namespace App\Models;

use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $table = 'absensi';

    // Baris ini mengizinkan semua kolom diisi data
    protected $guarded = [];

    // Contoh di User.php, Absensi.php, atau Penggajian.php
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
