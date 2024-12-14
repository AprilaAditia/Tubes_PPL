@extends('layouts.app_sneat_wali')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">DATA TAGIHAN SPP</h5>
                <div class="card-body">
                    @if ($tagihan->isEmpty())
                        <div class="alert alert-info text-center">
                            <strong>Data tagihan tidak tersedia.</strong>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Tingkatan</th>
                                        <th>Kelas</th>
                                        <th>Tanggal Tagihan</th>
                                        <th>Status Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tagihan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->siswa->nama }}</td>
                                            <td>{{ $item->siswa->tingkatan }}</td>
                                            <td>{{ $item->siswa->kelas }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_tagihan)->translatedFormat('d F Y') }}</td>
                                            <td>
                                                @if ($item->status == 'lunas')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif ($item->status == 'angsur')
                                                    <span class="badge bg-warning text-dark">Angsur</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Dibayar</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->status == 'lunas')
                                                    <button class="btn btn-success btn-sm" disabled>
                                                        Pembayaran Sudah Lunas
                                                    </button>
                                                @else
                                                    <a href="{{ route('wali.tagihan.show', $item->id) }}" class="btn btn-primary btn-sm">
                                                        Lakukan Pembayaran
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
