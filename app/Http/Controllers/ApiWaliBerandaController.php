<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiWaliBerandaController extends Controller
{
    /**
     * Display a listing of the students for the logged-in guardian.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        // Mengambil data siswa dan memuat relasi 'tagihan' berdasarkan wali_id pengguna yang sedang login
        $siswa = Siswa::with('tagihan')->where('wali_id', Auth::id())->get();
        $totalTagihanBelumBayar = 0;
        // dd(Auth::id());


        // Menghitung total tagihan belum lunas untuk setiap siswa
        foreach ($siswa as $siswa) {
            $totalTagihanBelumBayar += $siswa->tagihan->where('status', '<>', 'lunas')->count();
        }

        // Menyiapkan response
        $response = [
            'total_tagihan_belum_bayar' => $totalTagihanBelumBayar,
            'data_siswa' => $siswa->makeHidden(['tagihan', 'wali_id']),
        ];

        // dd($siswa);


        // Mengembalikan response dalam format JSON
        return $this->okResponse("Data Siswa", $response);
    }


    /**
     * Create a standard JSON response format.
     *
     * @param string $message The response message.
     * @param array|object $data The response data.
     * @param int $status The HTTP status code.
     * @return \Illuminate\Http\JsonResponse
     */
    private function okResponse($message, $data = [], $status = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    } 
}
