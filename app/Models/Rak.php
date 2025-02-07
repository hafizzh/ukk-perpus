<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    protected $table = 'tbl_rak';
    protected $primaryKey = 'id_rak';
    
    protected $fillable = [
        'kode_rak',
        'rak',
        'keterangan'
    ];

    // Relasi dengan DDC
    public function ddcs()
    {
        return $this->hasMany(Ddc::class, 'id_rak', 'id_rak');
    }
} 