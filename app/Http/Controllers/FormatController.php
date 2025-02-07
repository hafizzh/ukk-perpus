<?php

namespace App\Http\Controllers;

use App\Models\Format;
use Illuminate\Http\Request;

class FormatController extends Controller
{
    public function index()
    {
        $formats = Format::all();
        return view('admin.format.index', compact('formats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_format' => 'required|unique:tbl_format',
            'format' => 'required|unique:tbl_format',
            'keterangan' => 'nullable'
        ]);

        Format::create($request->all());
        return redirect()->route('admin.format.index')->with('success', 'Format berhasil ditambahkan');
    }

    public function update(Request $request, Format $format)
    {
        $request->validate([
            'kode_format' => 'required|unique:tbl_format,kode_format,'.$format->id_format.',id_format',
            'format' => 'required|unique:tbl_format,format,'.$format->id_format.',id_format',
            'keterangan' => 'nullable'
        ]);

        $format->update($request->all());
        return redirect()->route('admin.format.index')->with('success', 'Format berhasil diupdate');
    }

    public function destroy(Format $format)
    {
        $format->delete();
        return redirect()->route('admin.format.index')->with('success', 'Format berhasil dihapus');
    }
} 