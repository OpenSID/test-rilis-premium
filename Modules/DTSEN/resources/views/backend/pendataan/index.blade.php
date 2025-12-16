@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        {{ setting('sebutan_dtks') }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">DTSEN</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <x-btn-button judul="Kelola Keluarga" icon="fa fa-reply" type="btn-default" modal="true" :url="'keluarga'" />
            @if (can('u'))
            <x-btn-button judul="Tambah Data Baru" icon="fa fa-plus" modal='true' modalTarget="modal-survey" type="btn-success" :url="'dtsen/pendataan#'" />
            @endif
            <x-btn-button 
                judul="Cetak Prelist Terpilih" 
                icon="fa fa-print" 
                type="bg-purple" 
                url="#" 
                formAction="true"
                :disabled="true"
                attribut='id="cetak_terpilih"'
            />
            <x-btn-button judul="Ekspor ke excel" icon="fa fa-file" type="bg-navy" :url="'dtsen/pendataan/ekspor?versi=' . \Modules\DTSEN\App\Enums\DtsenEnum::VERSION_CODE" />
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover nowrap" id="tabeldata">
                    <thead class="bg-gray disabled color-palette">
                        <tr>
                            <th rowspan="2"><input type="checkbox" id="checkall" /></th>
                            <th rowspan="2">No</th>
                            <th rowspan="2" class="padat">Aksi</th>
                            <th colspan="2" class="padat" kolom="3,4">Status Data</th>
                            <th colspan="6" class="padat" kolom="5,6,7,8,9,10">Kepala Keluarga</th>
                            <th rowspan="2">Petugas</th>
                            <th rowspan="2">Terakhir diubah</th>
                        </tr>
                        <tr>
                            <th>Pengisian</th>
                            <th>Kelompok Desil</th>
                            <th>NIK</th>
                            <th nowrap>Nama</th>
                            <th>Jumlah Anggota</th>
                            <th kolom="5">{{ ucwords(setting('sebutan_dusun')) }}</th>
                            <th>RW</th>
                            <th>RT</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>

        </div>
    </div>
    <div class="modal fade" id="modal-survey" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Data Baru</h4>
                </div>
                <form data-action="{{ ci_route('dtsen.pendataan.new') }}" id="form-new-dtks" method="POST">
                    <div class="modal-body">
                        <div class="col-sm-12">
                            <div class="box" style="border-top:none">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="id_keluarga">NIK / Nama Kepala Keluarga</label>
                                        <select class="form-control input-sm select2 required" id="id_keluarga" name="id_keluarga" style="width:100%;">
                                            <option value="">-- Silakan Cari NIK / Nama Kepala Kepala Keluarga--</option>
                                            @foreach ($keluarga as $data)
                                                <option value="{{ $data->id }}">NIK :{{ $data->kepalaKeluarga->nik . ' - ' . $data->kepalaKeluarga->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
                                </div>
                                <div>
                                    @include('dtsen::backend.pendataan.info_new_dtsen')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="modal-confirm-delete-dtks"
        style="overflow: scroll;"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                {!! form_open('', 'class="" id="form-delete-dtks"') !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i> Konfirmasi</h4>
                </div>
                <div class="modal-body btn-info">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                    <button type="submit" class="btn btn-social btn-danger btn-sm" id="okdelete"><i class="fa fa-trash-o"></i> Hapus</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="modal-cetak-multi-dtks"
        style="overflow: scroll;"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Cetak</h4>
                </div>
                {!! form_open(ci_route('dtsen/pendataan/cetak2'), 'method="POST"') !!}
                <div class="modal-body">
                    <p class="alert alert-info">
                        Proses cetak dapat memakan waktu cukup lama dan memerlukan halaman ini untuk tetap terbuka
                    </p>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td>NIK</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Hanya cetak file yang sudah siap</button>
                    <button type="button" id="batal_cetak" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="modal-ekspor"
        style="overflow: scroll;"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Cetak</h4>
                </div>
                {!! form_open(ci_route('dtsen/pendataan/ekspor'), 'method="GET"') !!}
                <div class="modal-body">
                    <select name="versi" class="form-control">
                        @foreach (Modules\DTSEN\App\Enums\DtsenEnum::VERSION_LIST as $key => $value)
                            <option value="{{ $key }}" {{ $key == 1 ? 'disabled' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Ekspor</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="modal-impor"
        style="overflow: scroll;"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Impor</h4>
                </div>
                {!! form_open(ci_route('dtsen/pendataan/impor'), 'method="GET"') !!}
                <div class="modal-body">
                    <select name="versi" class="form-control">
                        @foreach (Modules\DTSEN\App\Enums\DtsenEnum::VERSION_LIST as $key => $value)
                            <option value="{{ $key }}" {{ $key == 1 ? 'disabled' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    <div id="impor_info"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Impor</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.layouts.components.ajax_dtks')
    <script>
        $(document).ready(function() {
            let batal_cetak = false;

            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            // Select2 dengan fitur pencarian karena tidak ngeload /js/custom.select2.js
            $('.select2').select2({
                width: '100%',
                dropdownAutoWidth: true
            });

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('dtsen/pendataan/datatables') }}",
                columns: [
                    { data: 'ceklist', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'aksi', orderable: false, searchable: false },

                    { data: 'status_pengisian', name: 'dtsen.status_pengisian' },
                    { data: 'kelompok_desil', name: 'dtsen.kelompok_desil' },

                    { data: 'nik_kk', name: 'kk.nik' },
                    { data: 'nama_kk', name: 'kk.nama' },

                    { data: 'jumlah_anggota', orderable: false, searchable: false },

                    { data: 'dusun', name: 'wil_kk.dusun' },
                    { data: 'rw', name: 'wil_kk.rw' },
                    { data: 'rt', name: 'wil_kk.rt' },

                    { data: 'petugas', name: 'dtsen.nama_petugas_pencacahan' },
                    { data: 'updated_at', name: 'dtsen.updated_at' }
                ],
                order: [
                    [3, 'asc']
                ],
                language: {
                    'url': "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}"
                }
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
            $('#form-new-dtks').one('submit', function(ev) {
                ev.preventDefault();
                let id_keluarga = $('#id_keluarga').val();
                $('#form-new-dtks').attr('action', $('#form-new-dtks').data('action') + '/' + id_keluarga);
                $(this).submit();
            });

            let dtks_id = null;
            $(document).on('click', '.btn-hapus', function() {
                dtks_id = $(this).data('id');
            });

            $('#form-delete-dtks').on('submit', function(ev) {
                ev.preventDefault();

                let form = $('#form-delete-dtks').serializeArray();
                $.ajax({
                        url: "{{ ci_route('dtsen/pendataan/delete') }}" + "/" + dtks_id,
                        method: "POST",
                        data: form
                    })
                    .done(function(data) {
                        $('#modal-confirm-delete-dtks').modal('hide');
                        showMessageDtsen('success', data.message);
                        TableData.draw();
                    })
                    .fail(function(xhr) {
                        showMessageDtsen('error', xhr.statusText + ": " + xhr.responseText);
                    });
            });


            $('#batal_cetak').on('click', function() {
                batal_cetak = true;
            });
            $(document).on('click', 'input[type=checkbox]', function() {
                let checked = [];
                $('input[type=checkbox]:checked').each(function(index, el) {
                    if (el.value != 'on') {
                        checked.push(el.value);

                        let nik = $(el).parentsUntil('tr').parent().find('td:eq(3)').text();
                        $('#modal-cetak-multi-dtks tbody').append('<tr><td>' + nik + '</td><td id="status_' + el.value + '">Menunggu</td></tr>')
                    }
                });

                $('#cetak_terpilih').prop('disabled', checked.length == 0);
                $('#cetak_terpilih').attr('disabled', checked.length == 0);
            });

            $('#cetak_terpilih').on('click', function(ev_cetak_terpilih) {
                let checked = [];
                $('#modal-cetak-multi-dtks tbody').empty();

                // Collect selected checkboxes
                $('input[type=checkbox]:checked').each(function(index, el) {
                    if (el.value != 'on') {
                        checked.push(el.value);
                        let nik = $(el).parentsUntil('tr').parent().find('td:eq(3)').text();
                        $('#modal-cetak-multi-dtks tbody').append('<tr><td>' + nik + '</td><td id="status_' + el.value + '">Menunggu</td></tr>')
                    }
                });

                // If no checkboxes are selected, exit early
                if (checked.length == 0) {
                    return;
                }

                $('#modal-cetak-multi-dtks').modal();

                function ubah_status_file(list) {
                    list.forEach(function(element) {
                        if (element.status_file == 0) {
                            $('#status_' + element.id).text('Menunggu');
                        } else {
                            $('#status_' + element.id).html('<input type="hidden" name="id[]" value="' + element.id + '">Selesai');
                        }
                    });
                }

                let callback_fail = function(xhr) {
                    console.error("AJAX Failed", xhr);
                };

                let callback_success = function(data) {
                    if (data.message === 'Mengunduh 1 data') {
                        window.open(data.href, '_blank');
                        $('#modal-cetak-multi-dtks').modal('hide');
                    } else if (data.message === 'Proses Data' && !batal_cetak) {
                        ubah_status_file(data.list);
                        // Continue processing if there's still work to be done
                        process_cetak_terpilih(checked);
                    } else if (!batal_cetak) {
                        ubah_status_file(data.list);
                    }
                };

                // This function ensures that `ajax_save_dtks` is called recursively only when needed
                function process_cetak_terpilih(checked) {
                    ajax_save_dtks("{{ ci_route('dtsen/pendataan/cetak2') }}", {
                        id: checked
                    }, callback_success, callback_fail);
                }

                // Start the first process
                batal_cetak = false;
                process_cetak_terpilih(checked);
            });

            $('#modal-impor').on('show.bs.modal', function() {
                $('#impor_info').empty();
                $('#impor_info').load("<?= ci_route('dtsen/pendataan/loadRecentImpor') ?>");
            });
        });
    </script>
@endpush
