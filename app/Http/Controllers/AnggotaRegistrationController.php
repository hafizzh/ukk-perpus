<?php

namespace App\Http\Controllers;

use App\Models\JenisAnggota;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnggotaRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $jenisAnggota = JenisAnggota::all();
        return view('users.anggota.register', compact('jenisAnggota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jenis_anggota' => 'required|exists:tbl_jenis_anggota,id_jenis_anggota',
            'nama_anggota' => 'required|string|max:50|unique:tbl_anggota',
            'tempat' => 'required|string|max:20',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string|max:50',
            'no_telp' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'username' => 'required|string|max:50|unique:tbl_anggota',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();
            
            // Generate kode anggota
            $lastAnggota = Anggota::orderBy('id_anggota', 'desc')->first();
            $lastNumber = $lastAnggota ? intval(substr($lastAnggota->kode_anggota, -4)) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $kodeAnggota = 'ANG-' . date('Y') . $newNumber;

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/anggota'), $fotoName);
                $validated['foto'] = 'uploads/anggota/' . $fotoName;
            }

            // Buat anggota baru dengan user_id yang benar
            $anggota = Anggota::create([
                'user_id' => $user->id,
                'id_jenis_anggota' => $validated['id_jenis_anggota'],
                'kode_anggota' => $kodeAnggota,
                'nama_anggota' => $validated['nama_anggota'],
                'tempat' => $validated['tempat'],
                'tgl_lahir' => $validated['tgl_lahir'],
                'alamat' => $validated['alamat'],
                'no_telp' => $validated['no_telp'],
                'email' => $user->email,  // Gunakan email dari user yang sudah login
                'tgl_daftar' => Carbon::now(),
                'masa_aktif' => Carbon::now()->addYear(),
                'fa' => 'Y',
                'foto' => $validated['foto'] ?? null,
                'username' => $validated['username'],
                'password' => Hash::make($validated['password'])
            ]);

            DB::commit();

            // Refresh session user untuk memuat relasi anggota yang baru
            auth()->user()->refresh();

            return redirect()->route('home')
                ->with('success', 'Pendaftaran berhasil! Anda sekarang dapat meminjam buku.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
} 