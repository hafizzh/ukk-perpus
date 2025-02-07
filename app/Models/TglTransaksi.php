<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TglTransaksi extends Model
{
    protected $table = 'tgl_transaksi';
    protected $primaryKey = 'id_transaksi';
    
    protected $fillable = [
        'id_pustaka',
        'id_anggota',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_pengembalian',
        'fp',
        'keterangan'
    ];

    protected $dates = [
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_pengembalian',
        'created_at',
        'updated_at'
    ];

    // Mutator untuk mengkonversi string date ke Carbon instance
    public function setTglPinjamAttribute($value)
    {
        $this->attributes['tgl_pinjam'] = $value instanceof Carbon ? $value : Carbon::parse($value);
    }

    public function setTglKembaliAttribute($value)
    {
        $this->attributes['tgl_kembali'] = $value instanceof Carbon ? $value : Carbon::parse($value);
    }

    public function setTglPengembalianAttribute($value)
    {
        if ($value) {
            $this->attributes['tgl_pengembalian'] = $value instanceof Carbon ? $value : Carbon::parse($value);
        } else {
            $this->attributes['tgl_pengembalian'] = null;
        }
    }

    // Accessor untuk memastikan tanggal selalu dikembalikan sebagai Carbon instance
    public function getTglPinjamAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getTglKembaliAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getTglPengembalianAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function pustaka(): BelongsTo
    {
        return $this->belongsTo(Pustaka::class, 'id_pustaka', 'id_pustaka');
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function calculateLateFee()
    {
        $returnDate = $this->tgl_pengembalian ?? Carbon::now();
        
        // Debug log
        \Log::info('Tanggal Kembali: ' . $this->tgl_kembali);
        \Log::info('Tanggal Sekarang: ' . $returnDate);
        
        if ($returnDate > $this->tgl_kembali) {
            // Gunakan diffInDays dengan absolute = false untuk mendapatkan nilai positif
            $daysLate = $returnDate->startOfDay()->diffInDays($this->tgl_kembali, false);
            
            // Pastikan daysLate positif
            $daysLate = abs($daysLate);
            
            \Log::info('Hari Terlambat: ' . $daysLate);
            \Log::info('Denda per Hari: ' . $this->pustaka->denda_terlambat);
            
            if ($daysLate > 0) {
                return $daysLate * $this->pustaka->denda_terlambat;
            }
        }
        
        return 0;
    }
} 