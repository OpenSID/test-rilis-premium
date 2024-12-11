@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Terdata Suplemen
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('suplemen.rincian', $suplemen->id) }}">Daftar Terdata Suplemen</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('suplemen') }}" class="btn btn-social btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Suplemen</a>
            <a href="{{ ci_route('suplemen.rincian', $suplemen->id) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Terdata Suplemen</a>
        </div>
        @include('admin.suplemen.rincian')
        <div class="box-body">
            <h5><b>Tambahkan Warga Terdata</b></h5>
            <hr>
            @if ($action == 'Tambah')
                <form id="main" name="main" method="POST" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Warga Terdata</label>
                        <div class="col-sm-9">
                            <select
                                class="form-control required input-sm select2 required"
                                required
                                onchange="formAction('main')"
                                id="terdata"
                                name="id_terdata"
                                style="width:100%;"
                                data-suplemen="{{ $suplemen->id }}"
                                data-sasaran="{{ $suplemen->sasaran }}"
                            >
                                @if ($individu)
                                    @if ($suplemen->sasaran == 1)
                                        <option selected value="">{{ 'NIK : ' . $individu->nik . ' - ' . $individu->nama . ' RT-' . $individu->wilayah->rt . ', RW-' . $individu->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $individu->wilayah->dusun }}</option>
                                    @else
                                        <option selected value="">
                                            {{ 'No KK : ' . $individu->keluarga->no_kk . ' - ' . $individu->pendudukHubungan->nama . '- NIK : ' . $individu->nik . ' - ' . $individu->nama . ' RT-' . $individu->wilayah->rt . ', RW-' . $individu->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $individu->wilayah->dusun }}
                                        </option>
                                    @endif
                                @endif
                                <option value="">-- Cari {{ $judul_sasaran }} --</option>
                            </select>
                        </div>
                    </div>
                </form>
            @endif
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            @if ($individu)
                @include('admin.suplemen.konfirmasi_terdata')
            @endif
            <input type="hidden" name="id_suplemen" value="{{ $suplemen->id }}">
            <input type="hidden" name="sasaran" value="{{ $suplemen->sasaran }}">
            @if ($action == 'Ubah')
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nama">{{ $suplemen->sasaran == 1 ? 'NIK' : 'No. KK' }}</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm" type="text" disabled value="{{ $terdata->terdata_plus }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nama">{{ $suplemen->sasaran == 1 ? 'Nama Terdata' : 'Kepala Keluarga' }}</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm" type="text" disabled value="{{ $terdata->terdata_nama }}">
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                <div class="col-sm-9">
                    <textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3" style="resize:none;">{{ $terdata->keterangan }}</textarea>
                </div>
            </div>
            <h5><b>Form Isian</b></h5>
            <div class="col-sm-12">
            @if ($suplemen->form_isian)
            @foreach($formData as $field)
                @php
                    $class = ($field['atribut']) ? buat_class($field['atribut'], '', $field['required']) : '';
                    $widthClass = ($field['kolom']) ? 'col-sm-' . $field['kolom'] : 'col-sm-12';
                @endphp

                <div class="form-group {{ $widthClass }}">
                    @if($field['tipe'] == 'date')
                        <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                        <input type="date" class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}"
                            value="{{ old('input_data['.$field['nama_kode'].']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}">
                    @elseif($field['tipe'] == 'text')
                        <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                        <input type="text" class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}"
                            value="{{ old('input_data['.$field['nama_kode'].']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}">
                    @elseif($field['tipe'] == 'textarea')
                        <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                        <textarea class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}"
                            placeholder="{{ $field['deskripsi_kode'] }}">{{ old('input_data['.$field['nama_kode'].']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}</textarea>
                    @elseif($field['tipe'] == 'select-manual')
                        <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                        <div class="{{ $widthClass }}">
                            <select class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}">
                                <option value="">-- {{ $field['deskripsi_kode'] }} --</option>
                                @foreach ($field['pilihan_kode'] as $pilih)
                                    <option value="{{ $pilih }}" @selected(old('input_data['.$field['nama_kode'].']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') == $pilih)>
                                        {{ $pilih }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($field['tipe'] == 'select-otomatis')
                        <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                        <div class="{{ $widthClass }}">
                            <select class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}">
                                <option value="">-- {{ $field['deskripsi_kode'] }} --</option>
                                @foreach ($field['referensi_kode'] as $pilih)
                                    <option value="{{ $pilih }}" @selected(old('input_data['.$field['nama_kode'].']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') == $pilih)>
                                        {{ $pilih }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            @endforeach

            @endif
            </div>

            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
            </div>
            {!! form_close() !!}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#terdata').select2({
            ajax: {
                url: "{{ ci_route('suplemen.apipenduduksuplemen') }}",
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1,
                        suplemen: $(this).data('suplemen'),
                        sasaran: $(this).data('sasaran'),
                    };
                },
                cache: true
            },
            placeholder: function() {
                $(this).data('placeholder');
            },
            minimumInputLength: 0,
            allowClear: true,
            escapeMarkup: function(markup) {
                return markup;
            },
        });
    </script>
@endpush
