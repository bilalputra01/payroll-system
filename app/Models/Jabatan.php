<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'jabatan'; // Tambahkan baris ini
    protected $guarded = [];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'jabatan_id');
    }
}
