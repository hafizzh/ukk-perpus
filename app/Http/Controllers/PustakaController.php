<?php

namespace App\Http\Controllers;

use App\Models\Pustaka;
use App\Models\Ddc;
use App\Models\Format;
use App\Models\Penerbit;
use App\Models\Pengarang;
use Illuminate\Http\Request;

class PustakaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pustaka = Pustaka::with(['ddc', 'format', 'penerbit', 'pengarang'])->get();
        return view('admin.pustaka.index', compact('pustaka'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ddcs = Ddc::all();
        $formats = Format::all();
        $penerbits = Penerbit::all();
        $pengarangs = Pengarang::all();
        return view('admin.pustaka.create', compact('ddcs', 'formats', 'penerbits', 'pengarangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tambahkan dd($request->all()); untuk debug
        $validated = $request->validate([
            'id_ddc' => 'required|exists:tbl_ddc,id_ddc',
            'id_format' => 'required|exists:tbl_format,id_format',
            'id_penerbit' => 'required|exists:tbl_penerbit,id_penerbit',
            'id_pengarang' => 'required|exists:tbl_pengarang,id_pengarang',
            'isbn' => 'nullable|string|max:20',
            'judul_pustaka' => 'required|string|max:100',
            'tahun_terbit' => 'required|string|max:5',
            'keyword' => 'nullable|string|max:50',
            'abstraksi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_buku' => 'required|integer',
            'kondisi_buku' => 'required|string|max:15',
            'fp' => 'required|in:0,1',
            'jml_pinjam' => 'required|integer',
            'denda_terlambat' => 'required|integer',
            'denda_hilang' => 'required|integer',
        ]);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = time() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('uploads/pustaka'), $nama_gambar);
            $validated['gambar'] = 'uploads/pustaka/' . $nama_gambar;
        }

        try {
            Pustaka::create($validated);
            return redirect()->route('admin.pustaka.index')
                ->with('success', 'Pustaka berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pustaka = Pustaka::findOrFail($id);
        $ddcs = Ddc::all();
        $formats = Format::all();
        $penerbits = Penerbit::all();
        $pengarangs = Pengarang::all();
        
        return view('admin.pustaka.edit', compact('pustaka', 'ddcs', 'formats', 'penerbits', 'pengarangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pustaka = Pustaka::findOrFail($id);
        
        $validated = $request->validate([
            'id_ddc' => 'required|exists:tbl_ddc,id_ddc',
            'id_format' => 'required|exists:tbl_format,id_format',
            'id_penerbit' => 'required|exists:tbl_penerbit,id_penerbit',
            'id_pengarang' => 'required|exists:tbl_pengarang,id_pengarang',
            'isbn' => 'nullable|string|max:20',
            'judul_pustaka' => 'required|string|max:100',
            'tahun_terbit' => 'required|string|max:5',
            'keyword' => 'nullable|string|max:50',
            'abstraksi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_buku' => 'required|integer',
            'kondisi_buku' => 'required|string|max:15',
            'fp' => 'required|in:0,1',
            'jml_pinjam' => 'required|integer',
            'denda_terlambat' => 'required|integer',
            'denda_hilang' => 'required|integer',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($pustaka->gambar && file_exists(public_path($pustaka->gambar))) {
                unlink(public_path($pustaka->gambar));
            }
            
            $gambar = $request->file('gambar');
            $nama_gambar = time() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('uploads/pustaka'), $nama_gambar);
            $validated['gambar'] = 'uploads/pustaka/' . $nama_gambar;
        }

        try {
            $pustaka->update($validated);
            return redirect()->route('admin.pustaka.index')
                ->with('success', 'Pustaka berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pustaka = Pustaka::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($pustaka->gambar && file_exists(public_path($pustaka->gambar))) {
                unlink(public_path($pustaka->gambar));
            }
            
            $pustaka->delete();
            return redirect()->route('admin.pustaka.index')
                ->with('success', 'Pustaka berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
