<?php

namespace App\Http\Controllers;

use Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Requests\UpdatePembayaranRequest;
use App\Models\Tagihan; // Ensure the Tagihan model is imported
use App\Notifications\PembayaranKonfirmasiNotification;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayarans = DB::table('pembayarans')
            ->join('users as wali', 'wali.id', '=', 'pembayarans.wali_id')
            ->join('tagihans', 'tagihans.id', '=', 'pembayarans.tagihan_id')
            ->join('siswas', 'siswas.id', '=', 'tagihans.siswa_id')
            ->select(
                'pembayarans.id',
                'pembayarans.bank_sekolah_id',
                'pembayarans.wali_bank_id',
                'pembayarans.tagihan_id',
                'pembayarans.wali_id',
                'pembayarans.tanggal_bayar',
                'pembayarans.tanggal_konfirmasi',
                'pembayarans.jumlah_dibayar',
                'pembayarans.bukti_bayar',
                'pembayarans.metode_pembayaran',
                'siswas.nama as nama_siswa',
                'siswas.nisn',
                'wali.name as nama_wali',
                'tagihans.status as status'
            )
            ->whereIn('tagihans.status', ['baru', 'angsur', 'lunas']) // Menampilkan semua status
            ->orderBy('tanggal_konfirmasi', 'desc')
            ->paginate(50);
    
        return view('operator.pembayaran_index', compact('pembayarans'));
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pembayarans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePembayaranRequest $request)
    {
        $requestData = $request->validated();
        // $requestData['status_konfirmasi'] = 'sudah';
        $requestData['tanggal_konfirmasi'] = now();
        $requestData['metode_pembayaran'] = 'manual';
        $tagihan = Tagihan::findOrFail($requestData['tagihan_id']);
        
        if ($requestData['jumlah_dibayar'] >= $tagihan->tagihanDetails->sum('jumlah_biaya')) {
            $tagihan->status = 'lunas';
        } else {
            $tagihan->status = 'angsur';
        }
        $tagihan->save();

        Pembayaran::create($requestData);

        flash('Pembayaran Berhasil Disimpan!')->success();
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        auth()->user()->unreadNotifications->where('id', request('id'))->first()?->markAsRead();
        return view('operator.pembayaran_show', [
            'model' => $pembayaran,
            'route' => ['pembayaran.update', $pembayaran->id]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        return view('pembayarans.edit', compact('pembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        // $pembayaran->status_konfirmasi = 'sudah';
        $wali = $pembayaran->wali;
        $wali->notify (new PembayaranKonfirmasiNotification($pembayaran));
        $pembayaran->tanggal_konfirmasi = now();
        $pembayaran->user_id = auth()->user()->id;
        $pembayaran->save();
        $pembayaran->tagihan->status = 'lunas';
        $pembayaran->tagihan->save();
        flash('Data Pembayaran Berhasil Dikonfirmasi')->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        session()->flash('success', 'Pembayaran Berhasil Dihapus');
        return back();
    }
}