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
                    <!-- Tulisan Data Biaya -->
                    <div class="mb-4">
                        <h6 class="text-uppercase fw-bold"></h6>
                    </div>

                    <!-- Tombol tambah data dan pencarian -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <a href="{{ route($routePrefix . '.create') }}" class="btn btn-primary btn-sm">
                                Tambah Data
                            </a>
                        </div>
                        <div class="col-md-6">
                            {!! Form::open(['route' => $routePrefix . '.index', 'method' => 'GET']) !!}
                            <div class="input-group">
                                <input name="q" type="text" class="form-control" placeholder="Cari Data"
                                    aria-label="Cari Data" aria-describedby="button-addon2" value="{{ request('q') }}">
                                <button type="submit" class="btn btn-outline-primary" id="button-addon2">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <!-- Tabel data -->
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="bg-dark text-white text-center">
                                <tr>
                                    <th class="text-white">No</th>
                                    <th class="text-white">Nama Biaya</th>
                                    <th class="text-white">Jumlah (Rp)</th>
                                    <th class="text-white">Dibuat Oleh</th>
                                    <th class="text-white">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($models as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td class="text-end">{{ $item->formatRupiah('jumlah') }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td class="text-center">
                                            {!! Form::open([
                                                'route' => [$routePrefix . '.destroy', $item->id],
                                                'method' => 'DELETE',
                                                'onsubmit' => 'return confirm("Yakin Ingin Menghapus Data Ini?")',
                                            ]) !!}
                                            <a href="{{ route($routePrefix . '.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm mx-1">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route($routePrefix . '.show', $item->id) }}"
                                                class="btn btn-info btn-sm mx-1">
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
                                        <td colspan="5" class="text-center">Data Tidak Ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {!! $models->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
