@include('admin.layouts.components.asset_validasi')
@extends('admin.layouts.index')

@section('title')
    <h1>SINKRONISASI PBB</h1>
@endsection

@section('breadcrumb')
    <li class="active">Sinkronisasi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Pengaturan Sinkronisasi PBB</h3>
        </div>
        <div class="box-body">
            <form id="validasi" class="form-horizontal" action="{{ site_url('setting/update') }}" method="POST">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-12 col-md-3" for="nama">Sinkronisasi PBB</label>
                        <div class="col-sm-12 col-md-4">
                            <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons" style="padding: 0px;">
                                <label
                                    class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ setting('sinkronisasi_pbb') == 1 ? 'active' : '' }}">
                                    <input type="radio" name="sinkronisasi_pbb" class="form-check-input" value="1"
                                        autocomplete="off" @checked(setting('sinkronisasi_pbb'))>Ya</label>
                                <label
                                    class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ setting('sinkronisasi_pbb') == 0 ? 'active' : '' }}">
                                    <input type="radio" name="sinkronisasi_pbb" class="form-check-input" value="0"
                                        autocomplete="off" @checked(setting('sinkronisasi_pbb'))>Tidak
                                </label>
                            </div>
                        </div>
                        <label class="col-sm-12 col-md-5 pull-left" for="nama">Aktifkan Sinkronisasi PBB</code></label>
                    </div>
                    <div id="modul-sinkronisasi">
                        <div class="form-group">
                            <label class="col-sm-12 col-md-3" for="nama">Api PBB Key</label>
                            <div class="col-sm-12 col-md-4">
                                <textarea rows="5" readonly id="api_pbb_key" name="api_pbb_key" class="form-control input-sm"
                                    placeholder="Silakan Masukkan API Key PBB">{{ $list_setting->firstWhere('key', 'api_pbb_key')?->value }}</textarea>
                            </div>
                            <label class="col-sm-12 col-md-5 pull-left" for="nama">PBB API Key untuk Sinkronisasi
                                Data</label>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 col-md-4 col-md-offset-3">
                                <button id="generate-jwt" type="button" class="btn btn-success btn-sm"
                                    style="margin-top:8px;"
                                    {{ setting('sinkronisasi_pbb') == 1 ? '' : 'disabled' }}>Generate
                                    JWT Token</button>
                                <div id="jwt-result" style="margin-top:1em;"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                        Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right simpan"><i
                            class="fa fa-check"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (setting('sinkronisasi_pbb') == 1)
                $('#modul-sinkronisasi').show();
            @else
                $('#modul-sinkronisasi').hide();
            @endif
            $('input[name="sinkronisasi_pbb"]').on('change', function(e) {
                // Toggle required dan enable tombol generate-jwt
                if ($('input[name="sinkronisasi_pbb"]:checked').val() == 1) {
                    $('textarea[name="api_pbb_key"]').addClass("required");
                    $('#generate-jwt').prop('disabled', false);
                    $('#modul-sinkronisasi').show();
                } else {
                    $('textarea[name="api_pbb_key"]').removeClass("required");
                    $('#generate-jwt').prop('disabled', true);
                    $('#modul-sinkronisasi').hide();
                }
            });

            // Handler tombol generate-jwt
            $('#generate-jwt').on('click', function() {
                if ($(this).prop('disabled')) return;
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang membuat token, mohon tunggu.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.post('{{ route('internal.jwt-token') }}', {

                }, function(data) {
                    if (data.token) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Token Berhasil Dibuat',
                        });
                        // setelah klik OK
                        $('textarea[name=api_pbb_key]').val(data.token);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Membuat Token',
                            text: data.error || 'Gagal generate token'
                        });
                    }
                }, 'json').fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Membuat Token',
                        text: 'Terjadi kesalahan koneksi.'
                    });
                });
            });
        });
    </script>
@endpush
