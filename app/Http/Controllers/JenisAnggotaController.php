<?php

namespace App\Http\Controllers;

use App\Models\JenisAnggota;
use Illuminate\Http\Request;

class JenisAnggotaController extends Controller
{
    public function index()
    {
        $jenisAnggota = JenisAnggota::all();
        return view('admin.jenis-anggota.index', compact('jenisAnggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jenis_anggota' => 'required|unique:tbl_jenis_anggota',
            'jenis_anggota' => 'required',
            'max_pinjam' => 'required|numeric|min:1',
            'keterangan' => 'nullable'
        ]);

        JenisAnggota::create($request->all());
        return redirect()->route('admin.jenis-anggota.index')->with('success', 'Jenis Anggota berhasil ditambahkan');
    }

    public function update(Request $request, JenisAnggota $jenisAnggota)
    {
        $request->validate([
            'kode_jenis_anggota' => 'required|unique:tbl_jenis_anggota,kode_jenis_anggota,'.$jenisAnggota->id_jenis_anggota.',id_jenis_anggota',
            'jenis_anggota' => 'required',
            'max_pinjam' => 'required|numeric|min:1',
            'keterangan' => 'nullable'
        ]);

        $jenisAnggota->update($request->all());
        return redirect()->route('admin.jenis-anggota.index')->with('success', 'Jenis Anggota berhasil diupdate');
    }

    public function destroy(JenisAnggota $jenisAnggota)
    {
        $jenisAnggota->delete();
        return redirect()->route('admin.jenis-anggota.index')->with('success', 'Jenis Anggota berhasil dihapus');
    }
} 