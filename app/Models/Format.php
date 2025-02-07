<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    protected $table = 'tbl_format';
    protected $primaryKey = 'id_format';
    
    protected $fillable = [
        'kode_format',
        'format',
        'keterangan'
    ];
} 