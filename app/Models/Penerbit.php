<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerbit extends Model
{
    protected $table = 'tbl_penerbit';
    protected $primaryKey = 'id_penerbit';
    
    protected $fillable = [
        'kode_penerbit',
        'nama_penerbit',
        'alamat_penerbit',
        'no_telp',
        'email',
        'fax',
        'website',
        'kontak'
    ];
} 