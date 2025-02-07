<?php

namespace App\Http\Controllers;

use App\Models\TglTransaksi;
use App\Models\Pustaka;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function index(): View
    {
        $transactions = TglTransaksi::with(['anggota.user', 'pustaka'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.transaksi.index', compact('transactions'));
    }

    public function approve($id): RedirectResponse
    {
        $transaction = TglTransaksi::findOrFail($id);
        
        // Cek status buku
        $book = Pustaka::findOrFail($transaction->id_pustaka);
        if ($book->fp != 1) {
            return back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }
        
        // Update status transaksi
        $transaction->fp = '1';
        $transaction->save();
        
        // Update status buku menjadi tidak tersedia
        $book->fp = '0';
        $book->save();
        
        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }
    
    public function reject($id): RedirectResponse
    {
        $transaction = TglTransaksi::findOrFail($id);
        $transaction->delete();
        
        return back()->with('success', 'Peminjaman ditolak.');
    }
    
    public function return($id): RedirectResponse
    {
        $transaction = TglTransaksi::findOrFail($id);
        
        // Hitung denda keterlambatan
        $lateFee = $transaction->calculateLateFee();
        
        // Update tanggal pengembalian
        $transaction->tgl_pengembalian = now();
        if ($lateFee > 0) {
            $transaction->keterangan = "Denda keterlambatan: Rp " . number_format($lateFee, 0, ',', '.');
        }
        $transaction->save();
        
        // Update status buku menjadi tersedia
        $book = Pustaka::findOrFail($transaction->id_pustaka);
        $book->fp = '1';
        $book->save();
        
        $message = 'Buku berhasil dikembalikan.';
        if ($lateFee > 0) {
            $message .= ' Denda keterlambatan: Rp ' . number_format($lateFee, 0, ',', '.');
        }
        
        return back()->with('success', $message);
    }
} 