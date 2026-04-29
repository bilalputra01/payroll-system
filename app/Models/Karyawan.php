<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    // 3 Baris Sakti untuk NIK
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    // Pastikan 'nik' masuk ke fillable
    protected $fillable = ['nik', 'jabatan_id', 'nama_karyawan', 'email', 'nomor_rekening', 'status', 'nama_bank'];

    // Tambahkan jembatan relasi ini:
    public function jabatan()
    {
        // Artinya: Setiap 1 Karyawan "dimiliki oleh" (belongs to) 1 Jabatan
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function bank() {}

    public function user()
    {
        return $this->hasOne(User::class, 'nik');
    }
}
