<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisAnggota extends Model
{
    protected $table = 'tbl_jenis_anggota';
    protected $primaryKey = 'id_jenis_anggota';
    
    protected $fillable = [
        'kode_jenis_anggota',
        'jenis_anggota',
        'max_pinjam',
        'keterangan'
    ];
} 