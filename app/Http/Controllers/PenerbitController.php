<?php

namespace App\Http\Controllers;

use App\Models\Penerbit;
use Illuminate\Http\Request;

class PenerbitController extends Controller
{
    public function index()
    {
        $penerbits = Penerbit::all();
        return view('admin.penerbit.index', compact('penerbits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_penerbit' => 'required|unique:tbl_penerbit',
            'nama_penerbit' => 'required|unique:tbl_penerbit',
            'alamat_penerbit' => 'required',
            'no_telp' => 'required',
            'email' => 'nullable|email',
            'fax' => 'nullable',
            'website' => 'nullable|url',
            'kontak' => 'nullable'
        ]);

        Penerbit::create($request->all());
        return redirect()->route('admin.penerbit.index')->with('success', 'Penerbit berhasil ditambahkan');
    }

    public function update(Request $request, Penerbit $penerbit)
    {
        $request->validate([
            'kode_penerbit' => 'required|unique:tbl_penerbit,kode_penerbit,'.$penerbit->id_penerbit.',id_penerbit',
            'nama_penerbit' => 'required|unique:tbl_penerbit,nama_penerbit,'.$penerbit->id_penerbit.',id_penerbit',
            'alamat_penerbit' => 'required',
            'no_telp' => 'required',
            'email' => 'nullable|email',
            'fax' => 'nullable',
            'website' => 'nullable|url',
            'kontak' => 'nullable'
        ]);

        $penerbit->update($request->all());
        return redirect()->route('admin.penerbit.index')->with('success', 'Penerbit berhasil diupdate');
    }

    public function destroy(Penerbit $penerbit)
    {
        $penerbit->delete();
        return redirect()->route('admin.penerbit.index')->with('success', 'Penerbit berhasil dihapus');
    }
} 