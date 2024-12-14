@extends('layouts.app_sneat_wali')
@section('js')
    <script>
        $(function() {
            $("#checkboxtoggle").click(function() {
                if ($(this).is(":checked")) {
                    $("#pilihan_bank").fadeOut();
                    $("#form_bank_pengirim").fadeIn();
                } else {
                    $("#pilihan_bank").fadeIn();
                    $("#form_bank_pengirim").fadeOut();
                }
            });
        });
        $(document).ready(function() {
            @if (count($listWaliBank) >= 1)
                $("#form_bank_pengirim").hide();
            @else
                $("#form_bank_pengirim").show();
            @endif
            $("#pilih_bank").change(function(e) {
                var bankId = $(this).find(":selected").val();
                window.location.href = "{!! $url !!}&bank_sekolah_id=" + bankId;
            });
        });
    </script>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">KONFIRMASI PEMBAYARAN</h5>
                <div class="card-body">
                    {!! Form::model($model, ['route' => $route, 'method' => $method, 'files' => true]) !!}
                    {!! Form::hidden('tagihan_id', request('tagihan_id'), []) !!}
                    <div class="divider text">
                        <div class="divider-text"><i class="fa fa-info-circle"></i> INFORMASI REKENING PENGIRIM</div>
                    </div>
                    @if (count($listWaliBank) >= 1)
                        <div class="from-group" id="pilihan_bank">
                            <label for="wali_bank_id">Pilih Bank Pengirim</label>
                            {!! Form::select('wali_bank_id', $listWaliBank, null, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Pilih Nomor Rekening Pengirim',
                            ]) !!}
                            <span class="text-danger">{{ $errors->first('wali_bank_id') }}</span>
                        </div>
                        <div class="form-check mt-3">
                            {!! Form::checkbox('pilihan_bank', 1, false, ['class' => 'form-check-input', 'id' => 'checkboxtoggle']) !!}
                            <label class="form-check-label" for="checkboxtoggle">
                                Saya Punya Rekening Baru
                            </label>
                        </div>
                    @endif
                    <div class="informasi_pengirim" id="form_bank_pengirim">
                        <div class="alert alert-dark" role="alert">
                            Informasi Ini Dibutuhkan Agar Operator Sekolah Dapat Memverifikasi Pembayaran Yang Dilakukan
                            Oleh Wali Murid Melalui Bank.
                        </div>
                        <div class="from-group">
                            <label for="nama_bank_pengirim">Nama Bank Pengirim</label>
                            {!! Form::select('bank_id', $listBank, null, ['class' => 'form-control select2']) !!}
                            <span class="text-danger">{{ $errors->first('bank_id') }}</span>
                        </div>
                        <div class="from-group mt-3">
                            <label for="nama_rekening">Nama Pemilik Rekening</label>
                            {!! Form::text('nama_rekening', null, ['class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('nama_rekening') }}</span>
                        </div>
                        <div class="from-group mt-3">
                            <label for="nomor_rekening">Nomor Rekening</label>
                            {!! Form::text('nomor_rekening', null, ['class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('nomor_rekening') }}</span>
                        </div>
                        <div class="form-check mt-3">
                            {!! Form::checkbox('simpan_data_rekening', 1, false, ['class' => 'form-check-input', 'id' => 'defaultCheck3']) !!}
                            <label class="form-check-label" for="defaultCheck3">
                                Simpan Data Ini Untuk Memudahkan Pembayaran Selanjutnya.
                            </label>
                        </div>
                    </div>
                    <div class="divider text">
                        <div class="divider-text"><i class="fa fa-info-circle"></i> INFORMASI REKENING TUJUAN</div>
                    </div>
                    <div class="informasi_bank_tujuan">
                        <div class="from-group mt-0">
                            <label for="bank_sekolah_id">Bank Tujuan Pembayaran</label>
                            {!! Form::select('bank_sekolah_id', $listBankSekolah, request('bank_sekolah_id'), [
                                'class' => 'form-control',
                                'placeholder' => 'Pilih Bank Tujuan Transfer',
                                'id' => 'pilih_bank',
                            ]) !!}
                            <span class="text-danger">{{ $errors->first('bank_sekolah_id') }}</span>
                        </div>
                        @if (request('bank_sekolah_id') != '')
                            <div class="alert alert-dark mt-2 mb-2" role="alert">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td width="15%">Bank Tujuan</td>
                                            <td>: {{ $bankYangDipilih->nama_bank }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor Rekening</td>
                                            <td>: {{ $bankYangDipilih->nomor_rekening }}</td>
                                        </tr>
                                        <tr>
                                            <td>Atas Nama</td>
                                            <td>: {{ $bankYangDipilih->nama_rekening }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="divider text">
                        <div class="divider-text"><i class="fa fa-info-circle"></i> INFORMASI PEMBAYARAN</div>
                    </div>
                    <div class="informasi_pembayaran">
                        <div class="from-group mt-0">
                            <label for="tanggal_bayar">Tanggal Bayar</label>
                            {!! Form::date('tanggal_bayar', $model->tanggal_bayar ?? date('Y-m-d'), ['class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('tanggal_bayar') }}</span>
                        </div>
                        <div class="from-group mt-3">
                            <label for="jumlah_dibayar">Jumlah Yang Dibayarkan</label>
                            {!! Form::text('jumlah_dibayar', $tagihan->tagihanDetails->sum('jumlah_biaya'), [
                                'class' => 'form-control rupiah',
                            ]) !!}
                            <span class="text-danger">{{ $errors->first('jumlah_dibayar') }}</span>
                        </div>
                        <div class="from-group mt-3">
                            <label for="bukti_bayar">Bukti Pembayaran
                                <span class="text-danger">(File Harus jpg, jpeg, png. Ukuran File Maksimal
                                    5MB)</span>
                            </label>
                            {!! Form::file('bukti_bayar', ['class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('bukti_bayar') }}</span>
                        </div>
                    </div>
                    {!! Form::submit('SIMPAN', ['class' => 'btn btn-primary mt-3']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
