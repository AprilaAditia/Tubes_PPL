@extends('layouts.app_sneat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <!-- Tambahkan margin bawah pada header -->
            <h5 class="card-header bg-primary text-white text-center mb-4">
                DATA PEMBAYARAN
            </h5>
            <div class="card-body">
                <!-- Form filter bulan dan tahun -->
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::open(['route' => 'pembayaran.index', 'method' => 'GET']) !!}
                            <div class="row g-2">
                                <div class="col-md-4 col-sm-12">
                                    {!! Form::selectMonth('bulan', request('bulan'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    {!! Form::selectRange('tahun', 2015, date('Y') + 1, request('tahun'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i> Tampilkan
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

                <!-- Tabel dengan teks yang lebih terang dan margin atas -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="bg-dark text-white text-center">
                            <tr>
                                <th class="text-white">No</th>
                                <th class="text-white">NISN</th>
                                <th class="text-white">Nama Siswa</th>
                                <th class="text-white">Nama Wali</th>
                                <th class="text-white">Metode Pembayaran</th>
                                <th class="text-white">Status Konfirmasi</th>
                                <th class="text-white">Tanggal Konfirmasi</th>
                                <th class="text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembayarans as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->nisn }}</td>
                                <td>{{ $item->nama_siswa }}</td>
                                <td>{{ $item->nama_wali }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success text-white">
                                        {{ $item->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $item->status == 'lunas' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_konfirmasi)->format('d-m-Y') }}
                                </td>
                                <td class="text-center">
                                    {!! Form::open([
                                        'route' => ['pembayaran.destroy', $item->id],
                                        'method' => 'DELETE',
                                        'onsubmit' => 'return confirm("Yakin Ingin Menghapus Data Ini?")',
                                    ]) !!}
                                    <a href="{{ route('pembayaran.show', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Data Tidak Ada</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $pembayarans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
