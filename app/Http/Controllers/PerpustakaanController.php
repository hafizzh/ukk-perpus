<?php

namespace App\Http\Controllers;

use App\Models\Perpustakaan;
use Illuminate\Http\Request;

class PerpustakaanController extends Controller
{
    public function index()
    {
        $perpustakaan = Perpustakaan::first();
        return view('admin.perpustakaan.index', compact('perpustakaan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_perpustakaan' => 'required',
            'nama_pustakawan' => 'required',
            'alamat' => 'required',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'no_telp' => 'required',
            'keterangan' => 'nullable'
        ]);

        $perpustakaan = Perpustakaan::first();
        if ($perpustakaan) {
            $perpustakaan->update($request->all());
        } else {
            Perpustakaan::create($request->all());
        }

        return redirect()->route('admin.perpustakaan.index')->with('success', 'Profil Perpustakaan berhasil diupdate');
    }
} 