<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ddc extends Model
{
    protected $table = 'tbl_ddc';
    protected $primaryKey = 'id_ddc';
    
    protected $fillable = [
        'id_rak',
        'kode_ddc',
        'ddc',
        'keterangan'
    ];

    // Relasi dengan Rak
    public function rak()
    {
        return $this->belongsTo(Rak::class, 'id_rak', 'id_rak');
    }
} 