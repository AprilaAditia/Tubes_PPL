<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaWaliController extends Controller
{
    public function index()
    {
        return view('wali.beranda_index');
    }

    public function indexApi()
    {
        $siswa = Siswa::with('tagihan')->where('wali_id', auth()->user()->id)
            ->orderBy('nama', 'asc')->first();
        return $this->successResponse("Data Sudah Disimpan");
    }
}
