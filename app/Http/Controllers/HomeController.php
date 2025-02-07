<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Pustaka;
use App\Models\TglTransaksi;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $latestBooks = Pustaka::with(['pengarang', 'penerbit'])
                             ->orderBy('created_at', 'desc')
                             ->take(4)
                             ->get();
        
        return view('users.home', compact('latestBooks'));
    } 
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome(): View
    {
        return view('admin.adminHome');
    }

    public function show($id)
    {
        $book = Pustaka::with(['pengarang', 'penerbit', 'ddc', 'format'])
                       ->findOrFail($id);
        
        $relatedBooks = Pustaka::with(['pengarang', 'penerbit'])
                              ->where('id_ddc', $book->id_ddc)
                              ->where('id_pustaka', '!=', $book->id_pustaka)
                              ->take(4)
                              ->get();
        
        return view('users.books.show', compact('book', 'relatedBooks'));
    }

    public function showBorrowForm($id)
    {
        if (!auth()->user()->anggota) {
            return redirect()->route('anggota.register')
                ->with('error', 'Anda harus terdaftar sebagai anggota untuk meminjam buku.');
        }

        $book = Pustaka::with(['pengarang', 'penerbit'])->findOrFail($id);
        
        if ($book->fp != 1) {
            return redirect()->route('books.show', $id)
                ->with('error', 'Maaf, buku tidak tersedia untuk dipinjam.');
        }

        return view('users.books.borrow', compact('book'));
    }

    public function borrowRequest($id, Request $request): RedirectResponse
    {
        if (!auth()->user()->anggota) {
            return back()->with('error', 'Anda harus terdaftar sebagai anggota untuk meminjam buku.');
        }

        $request->validate([
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'durasi' => 'required|in:7,14',
        ]);

        $book = Pustaka::findOrFail($id);
        
        if ($book->fp != 1) {
            return back()->with('error', 'Maaf, buku tidak tersedia untuk dipinjam.');
        }
        
        try {
            $tglPinjam = Carbon::parse($request->tgl_pinjam);
            $durasi = (int) $request->durasi;
            $tglKembali = $tglPinjam->copy()->addDays($durasi);

            $transaction = new TglTransaksi();
            $transaction->id_pustaka = $id;
            $transaction->id_anggota = auth()->user()->anggota->id_anggota;
            $transaction->tgl_pinjam = $tglPinjam;
            $transaction->tgl_kembali = $tglKembali;
            $transaction->fp = '0'; // menunggu persetujuan
            $transaction->save();
            
            return redirect()->route('books.show', $id)
                ->with('success', 'Pengajuan peminjaman buku berhasil! Silahkan menunggu persetujuan dari admin. Tanggal peminjaman: ' . 
                       $tglPinjam->format('d/m/Y') . ' - ' . $tglKembali->format('d/m/Y'));
        } catch (\Exception $e) {
            \Log::error('Error in borrowRequest: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengajukan peminjaman. Silahkan coba lagi.');
        }
    }

    public function borrowingHistory()
    {
        $borrowings = TglTransaksi::with(['pustaka'])
            ->where('id_anggota', auth()->user()->anggota->id_anggota)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.borrowing.history', compact('borrowings'));
    }

    public function cancelBorrowing($id)
    {
        $borrowing = TglTransaksi::where('id_transaksi', $id)
            ->where('id_anggota', auth()->user()->anggota->id_anggota)
            ->where('fp', '0')
            ->firstOrFail();

        try {
            $borrowing->delete();
            return redirect()->route('user.borrowing.history')
                ->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membatalkan peminjaman.');
        }
    }

    public function returnBorrowing($id): RedirectResponse
    {
        $borrowing = TglTransaksi::where('id_transaksi', $id)
            ->where('id_anggota', auth()->user()->anggota->id_anggota)
            ->where('fp', '1')
            ->whereNull('tgl_pengembalian')
            ->firstOrFail();

        try {
            // Update tanggal pengembalian
            $borrowing->tgl_pengembalian = now();
            $borrowing->save();
            
            // Update status buku menjadi tersedia
            $book = Pustaka::findOrFail($borrowing->id_pustaka);
            $book->fp = '1';
            $book->save();
            
            return redirect()->route('user.borrowing.history')
                ->with('success', 'Buku berhasil dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengembalikan buku.');
        }
    }
}
