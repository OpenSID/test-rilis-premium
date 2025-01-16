@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1> Inventaris Klasifikasi
        <small>{{ empty($data) ? 'Tambah' : 'Ubah' }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('klasifikasi') }}">Inventaris Klasifikasi </a></li>
    <li class="active">{{ empty($data) ? 'Tambah' : 'Ubah' }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open($form_action, 'id="validasi" class="form-horizontal"') !!}
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('inventaris_klasifikasi') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Klasifikasi">
                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Inventaris Klasifikasi
            </a>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="kode">Kode Inventaris</label>
                <div class="col-sm-6">
                    <input name="kode" class="form-control input-sm required" type="text" placeholder="Kode" value="{{ old('kode', $data->kode) }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Nama</label>
                <div class="col-sm-6">
                    <input name="nama" class="form-control input-sm required" type="text" placeholder="Nama" value="{{ old('nama', $data->nama) }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="tipe_inventaris">Tipe </label>
                <div class="col-sm-6">
                    <select class="form-control input-sm required" name="tipe_inventaris">
                        <option value="" selected disabled>Pilih Tipe</option>
                        @foreach ($tipes as $key => $label)
                            <option value="{{ $key }}" {{ old('tipe_inventaris', $data->tipe_inventaris ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="deskripsi">Keterangan</label>
                <div class="col-sm-6">
                    <textarea name="deskripsi" class="form-control input-sm" placeholder="Keterangan">{{ $data['uraian'] }}</textarea>
                </div>
            </div>
        </div>
        <div class='box-footer'>
            <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button>
            <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i>
                Simpan</button>
        </div>
    </div>
    </form>
@endsection
