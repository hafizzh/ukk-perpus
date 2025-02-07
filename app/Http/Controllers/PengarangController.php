<?php

namespace App\Http\Controllers;

use App\Models\Pengarang;
use Illuminate\Http\Request;

class PengarangController extends Controller
{
    public function index()
    {
        $pengarangs = Pengarang::all();
        return view('admin.pengarang.index', compact('pengarangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pengarang' => 'required|unique:tbl_pengarang',
            'nama_pengarang' => 'required|unique:tbl_pengarang',
            'gelar_depan' => 'nullable',
            'gelar_belakang' => 'nullable',
            'no_telp' => 'required',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'biografi' => 'nullable',
            'keterangan' => 'nullable'
        ]);

        Pengarang::create($request->all());
        return redirect()->route('admin.pengarang.index')->with('success', 'Pengarang berhasil ditambahkan');
    }

    public function update(Request $request, Pengarang $pengarang)
    {
        $request->validate([
            'kode_pengarang' => 'required|unique:tbl_pengarang,kode_pengarang,'.$pengarang->id_pengarang.',id_pengarang',
            'nama_pengarang' => 'required|unique:tbl_pengarang,nama_pengarang,'.$pengarang->id_pengarang.',id_pengarang',
            'gelar_depan' => 'nullable',
            'gelar_belakang' => 'nullable',
            'no_telp' => 'required',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'biografi' => 'nullable',
            'keterangan' => 'nullable'
        ]);

        $pengarang->update($request->all());
        return redirect()->route('admin.pengarang.index')->with('success', 'Pengarang berhasil diupdate');
    }

    public function destroy(Pengarang $pengarang)
    {
        $pengarang->delete();
        return redirect()->route('admin.pengarang.index')->with('success', 'Pengarang berhasil dihapus');
    }
} 