<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggota extends Model
{
    protected $table = 'tbl_anggota';
    protected $primaryKey = 'id_anggota';
    
    protected $fillable = [
        'id_jenis_anggota',
        'user_id',
        'kode_anggota',
        'nama_anggota',
        'tempat',
        'tgl_lahir',
        'alamat',
        'no_telp',
        'email',
        'tgl_daftar',
        'masa_aktif',
        'fa',
        'keterangan',
        'foto',
        'username',
        'password'
    ];

    public function jenisAnggota(): BelongsTo
    {
        return $this->belongsTo(JenisAnggota::class, 'id_jenis_anggota');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
} 