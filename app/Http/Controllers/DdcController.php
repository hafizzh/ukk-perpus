<?php

namespace App\Http\Controllers;

use App\Models\Ddc;
use App\Models\Rak;
use Illuminate\Http\Request;

class DdcController extends Controller
{
    public function index()
    {
        $ddcs = Ddc::with('rak')->get();
        $raks = Rak::all();
        return view('admin.ddc.index', compact('ddcs', 'raks'));
    }

    public function create()
    {
        $raks = Rak::all();
        return view('admin.ddc.create', compact('raks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rak' => 'required',
            'kode_ddc' => 'required|unique:tbl_ddc',
            'ddc' => 'required|unique:tbl_ddc',
            'keterangan' => 'nullable'
        ]);

        Ddc::create($request->all());
        return redirect()->route('admin.ddc.index')->with('success', 'DDC berhasil ditambahkan');
    }

    public function edit($id)
    {
        $ddc = Ddc::findOrFail($id);
        $raks = Rak::all();
        return view('admin.ddc.edit', compact('ddc', 'raks'));
    }

    public function update(Request $request, Ddc $ddc)
    {
        $request->validate([
            'id_rak' => 'required',
            'kode_ddc' => 'required|unique:tbl_ddc,kode_ddc,'.$ddc->id_ddc.',id_ddc',
            'ddc' => 'required|unique:tbl_ddc,ddc,'.$ddc->id_ddc.',id_ddc',
            'keterangan' => 'nullable'
        ]);

        $ddc->update($request->all());
        return redirect()->route('admin.ddc.index')->with('success', 'DDC berhasil diupdate');
    }

    public function destroy(Ddc $ddc)
    {
        $ddc->delete();
        return redirect()->route('admin.ddc.index')->with('success', 'DDC berhasil dihapus');
    }
} 