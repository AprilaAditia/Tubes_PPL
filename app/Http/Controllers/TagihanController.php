<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan as Model;
use App\Models\Biaya;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TagihanDetail;
use App\Models\Pembayaran;
use Carbon\Carbon;

class TagihanController extends Controller
{
    private $viewIndex = 'tagihan_index';
    private $viewCreate = 'tagihan_form';
    private $viewEdit = 'tagihan_form';
    private $viewShow = 'tagihan_show';
    private $routePrefix = 'tagihan';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $models = Model::with('user', 'siswa', 'tagihanDetails')
                ->latest()
                ->whereMonth('tanggal_tagihan', $request->bulan)
                ->whereYear('tanggal_tagihan', $request->tahun)
                ->paginate(50);
        } else {
            $models = Model::with('user', 'siswa')->latest()->paginate(50);
        }

        return view('operator.' . $this->viewIndex, [
            'models' => $models,
            'routePrefix' => $this->routePrefix,
            'title' => 'Data Tagihan',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswa = Siswa::all();
        $data = [
            'model' => new Model(),
            'method' => 'POST',
            'route' => $this->routePrefix . '.store',
            'button' => 'SIMPAN',
            'title' => 'FORM DATA TAGIHAN',
            'angkatan' => $siswa->pluck('angkatan', 'angkatan'),
            'kelas' => $siswa->pluck('kelas', 'kelas'),
            'biaya' => Biaya::get(),
        ];
        return view('operator.' . $this->viewCreate, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            "biaya_id" => "required|array",
            "angkatan" => 'required',
            "kelas" => 'required',
            "tanggal_tagihan" => 'required|date',
            "tanggal_jatuh_tempo" => "required|date",
            "keterangan" => "required",
        ]);

        $biayaIdArray = $validation['biaya_id'];
        $siswa = Siswa::query();

        if ($validation['kelas']) {
            $siswa->where('kelas', $validation['kelas']);
        }
        if ($validation['angkatan']) {
            $siswa->where('angkatan', $validation['angkatan']);
        }

        $siswa = $siswa->get();

        foreach ($siswa as $item) {
            $biaya = Biaya::whereIn('id', $biayaIdArray)->get();
            $dataTagihan = [
                'siswa_id' => $item->id,
                'angkatan' => $validation['angkatan'],
                'kelas' => $validation['kelas'],
                'tanggal_tagihan' => $validation['tanggal_tagihan'],
                'tanggal_jatuh_tempo' => $validation['tanggal_jatuh_tempo'],
                'keterangan' => $validation['keterangan'],
                'status' => 'baru',
            ];

            $bulanTagihan = Carbon::parse($validation['tanggal_tagihan'])->format('m');
            $tahunTagihan = Carbon::parse($validation['tanggal_tagihan'])->format('Y');

            $cekTagihan = Tagihan::where('siswa_id', $item->id)
                ->whereMonth('tanggal_tagihan', $bulanTagihan)
                ->whereYear('tanggal_tagihan', $tahunTagihan)
                ->first();

            if ($cekTagihan == null) {
                $tagihan = Model::create($dataTagihan);
                foreach ($biaya as $itemBiaya) {
                    TagihanDetail::create([
                        'tagihan_id' => $tagihan->id,
                        'nama_biaya' => $itemBiaya->nama,
                        'jumlah_biaya' => $itemBiaya->jumlah,
                    ]);
                }
            }
        }

        flash('Data Tagihan Berhasil Disimpan')->success();
        return back();
    }

    /**
     * Store pembayaran angsur.
     */
    public function storePembayaran(Request $request)
    {
        $validation = $request->validate([
            'tagihan_id' => 'required',
            'tanggal_bayar' => 'required|date',
            'jumlah_dibayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required',
        ]);

        $tagihan = Tagihan::with('tagihanDetails', 'pembayaran')->findOrFail($validation['tagihan_id']);
        $totalTagihan = $tagihan->tagihanDetails->sum('jumlah_biaya');
        $totalPembayaran = $tagihan->pembayaran->sum('jumlah_dibayar');

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'tanggal_bayar' => $validation['tanggal_bayar'],
            'jumlah_dibayar' => $validation['jumlah_dibayar'],
            'metode_pembayaran' => $validation['metode_pembayaran'],
            'status' => 'angsur',
        ]);

        $totalPembayaran += $validation['jumlah_dibayar'];

        if ($totalPembayaran >= $totalTagihan) {
            $tagihan->update(['status' => 'lunas']);
        } elseif ($totalPembayaran > 0) {
            $tagihan->update(['status' => 'proses']);
        }

        flash('Pembayaran berhasil disimpan. Status tagihan diperbarui.')->success();
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $tagihan = Model::with('siswa', 'tagihanDetails', 'user', 'pembayaran')->findOrFail($id);

        $data = [
            'tagihan' => $tagihan,
            'siswa' => $tagihan->siswa,
            'periode' => Carbon::parse($tagihan->tanggal_tagihan)->locale('id')->translatedFormat('F Y'),
            'model' => new Pembayaran(),
            'totalTagihan' => $tagihan->tagihanDetails->sum('jumlah_biaya'),
            'totalPembayaran' => $tagihan->pembayaran->sum('jumlah_dibayar'),
        ];

        return view('operator.' . $this->viewShow, $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Model::findOrFail($id);
        $model->delete();
        flash('Data Berhasil Dihapus')->success();
        return back();
    }

    public function buat(string $id)
    {

    }


    
}