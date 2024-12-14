@extends('layouts.app_sneat')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <!-- Header Kartu -->
                <h5 class="card-header bg-primary text-white text-center">
                    {{ $title }}
                </h5>
                <div class="card-body">
                    <!-- Tombol Tambah Data -->
                    <div class="mb-4">
                        <hr>
                        <a href="{{ route($routePrefix . '.create') }}" class="btn btn-primary btn-sm">
                            Tambah Data
                        </a>
                    </div>

                    <!-- Tabel Data -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="bg-dark text-white text-center">
                                <tr>
                                    <th class="text-white">No</th>
                                    <th class="text-white">Nama Bank</th>
                                    <th class="text-white">Kode Transfer</th>
                                    <th class="text-white">Pemilik Rekening</th>
                                    <th class="text-white">Nomor Rekening</th>
                                    <th class="text-white">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($models as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_bank }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama_rekening }}</td>
                                        <td>{{ $item->nomor_rekening }}</td>
                                        <td class="text-center">
                                            {!! Form::open([
                                                'route' => [$routePrefix . '.destroy', $item->id],
                                                'method' => 'DELETE',
                                                'onsubmit' => 'return confirm("Yakin ingin menghapus data ini?")',
                                            ]) !!}
                                            <a href="{{ route($routePrefix . '.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm mx-1">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <button type="submit" class="btn btn-danger btn-sm mx-1">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Data Tidak Ada</td>
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
