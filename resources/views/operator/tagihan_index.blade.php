@extends('layouts.app_sneat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <!-- Header kartu -->
            <h5 class="card-header bg-primary text-white text-center">
                {{ $title }}
            </h5>
            <div class="card-body">
                <!-- Tulisan Data Tagihan -->
                <div class="mb-4">
                    <h6 class="text-uppercase fw-bold"></h6>
                </div>

                <!-- Tombol tambah data dan filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <a href="{{ route($routePrefix . '.create') }}" class="btn btn-primary btn-sm">
                            Tambah Data
                        </a>
                    </div>
                    <div class="col-md-6">
                        {!! Form::open(['route' => $routePrefix . '.index', 'method' => 'GET']) !!}
                        <div class="row g-2">
                            <div class="col-md-4 col-sm-12">
                                {!! Form::selectMonth('bulan', request('bulan'), ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4 col-sm-12">
                                {!! Form::selectRange('tahun', 2015, date('Y') + 1, request('tahun'), ['class' => 'form-control']) !!}
                            </div>
                            <div class="col">
                                <button class="btn btn-primary" type="submit">
                                    Tampil
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <!-- Tabel data -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="bg-dark text-white text-center">
                            <tr>
                                <th class="text-white">No</th>
                                <th class="text-white">NISN (Nomor Induk Siswa Nasional)</th>
                                <th class="text-white">Nama Siswa</th>
                                <th class="text-white">Tanggal Tagihan</th>
                                <th class="text-white">Status Pembayaran</th>
                                <th class="text-white">Total Tagihan (Rp)</th>
                                <th class="text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($models as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->siswa ? $item->siswa->nisn : 'N/A' }}</td>
                                <td>{{ $item->siswa ? $item->siswa->nama : 'N/A' }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_tagihan)->locale('id')->translatedFormat('d-F-Y') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $item->status == 'lunas' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    {{ number_format($item->tagihanDetails->sum('jumlah_biaya'), 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    {!! Form::open([
                                        'route' => [$routePrefix . '.destroy', $item->id],
                                        'method' => 'DELETE',
                                        'onsubmit' => 'return confirm("Yakin Ingin Menghapus Data Ini?")',
                                    ]) !!}
                                    <a href="{{ route($routePrefix . '.show', [
                                        $item->id,
                                        'siswa_id' => $item->siswa_id,
                                        'bulan' => \Carbon\Carbon::parse($item->tanggal_tagihan)->format('m'),
                                        'tahun' => \Carbon\Carbon::parse($item->tanggal_tagihan)->format('Y'),
                                    ]) }}" class="btn btn-info btn-sm mx-1">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                                    <button type="submit" class="btn btn-danger btn-sm mx-1">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Data Tidak Ada</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {!! $models->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
