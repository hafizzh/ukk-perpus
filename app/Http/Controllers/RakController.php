<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use Illuminate\Http\Request;

class RakController extends Controller
{
    public function index()
    {
        $raks = Rak::all();
        return view('admin.rak.index', compact('raks'));
    }

    public function create()
    {
        return view('admin.rak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rak' => 'required|unique:tbl_rak,kode_rak',
            'rak' => 'required|unique:tbl_rak,rak',
            'keterangan' => 'nullable'
        ]);

        Rak::create([
            'kode_rak' => $request->kode_rak,
            'rak' => $request->rak,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.rak.index')
            ->with('success', 'Rak berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $rak = Rak::findOrFail($id);
        return view('admin.rak.edit', compact('rak'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_rak' => 'required|unique:tbl_rak,kode_rak,'.$id.',id_rak',
            'rak' => 'required|unique:tbl_rak,rak,'.$id.',id_rak',
            'keterangan' => 'nullable'
        ]);

        $rak = Rak::findOrFail($id);
        $rak->update($request->all());

        return redirect()->route('admin.rak.index')
            ->with('success', 'Rak berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $rak = Rak::findOrFail($id);
        $rak->delete();

        return redirect()->route('admin.rak.index')
            ->with('success', 'Rak berhasil dihapus!');
    }
}
