@php $tte = setting('tte') ? 'hidden' : '' @endphp

<div class="form-group subtitle_head" {{ $tte }}>
    <label class="col-sm-12 control-label" for="label_penandatangan">PENANDA TANGAN</label>
</div>

<div class="form-group {{ $tte }}">
    <label class="col-sm-3 control-label">Tertanda</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control input-sm select2" id="atas_nama" name="pilih_atas_nama" onchange="ganti_ttd($(this).val());	">
            @foreach ($atas_nama as $key => $data)
                <option value="{{ $key }}">{{ $data }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group {{ $tte }}">
    <label class="col-sm-3 control-label">{{ 'Staf ' . ucwords(setting('sebutan_pemerintah_desa')) }}</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control required input-sm" id="pamong" name="pamong_id">
            <option value='' selected="selected">--
                {{ 'Pilih Staf ' . ucwords(setting('sebutan_pemerintah_desa')) }} --
            </option>
            @foreach ($pamong as $data)
                <option
                    value="{{ $data->pamong_id }}"
                    data-jenis="{{ $data->jenis }}"
                    data-jabatan="{{ trim($data->jabatan->nama) }}"
                    data-nip="{{ $data->pamong_nip }}"
                    data-niap="{{ $data->pamong_niap }}"
                    data-ttd="{{ $data->pamong_ttd }}"
                    data-ub="{{ $data->pamong_ub }}"
                >
                    {{ $data->pamong_nip ? 'NIP : ' . ($data->pamong_nip ?? '-') . ' | ' : setting('sebutan_nip_desa') . ' : ' . ($data->pamong_niap ?? '-') . ' | ' }}
                    {{ $data->pamong_nama . ' | ' . $data->jabatan->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group {{ $tte }}" id="form_ttd_scan">
    <label class="col-sm-3 control-label">TTD Scan</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control input-sm select2" id="ttd_scan" name="ttd_scan">
            <option value="1">Ya</option>
            <option value="0" selected>Tidak</option>
        </select>
    </div>
</div>

<div class="form-group hidden" id="form_ttd_alasan">
    <label class="col-sm-3 control-label">Alasan</label>
    <div class="col-sm-6 col-lg-4">
        <textarea class="form-control input-sm required" name="alasan" rows="3"></textarea>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#atas_nama').change();

            $('select[name="ttd_scan"]').change(function() {
                if ($('select[name="ttd_scan"]').val() == 1) {
                    Swal.fire({
                        title: 'Perhatian!',
                        html: 'Anda akan menggunakan TTD Scan.',
                        icon: 'warning',
                    })

                    $('#form_ttd_alasan').removeClass('hidden');
                } else {
                    $('#form_ttd_alasan').addClass('hidden');
                }
            })
        });

        function ganti_ttd(atas_nama) {
            if (atas_nama.includes('a.n')) {
                ub = $("#pamong option[data-ttd='1']").val();

                if (ub) {
                    $('#pamong').val(ub);
                } else {
                    $('#pamong').val('');
                }
                $('#pamong').attr('disabled', true);
                $('#form_ttd_scan').hide();
            } else if (atas_nama.includes('u.b')) {
                $('#pamong').val('');
                $("#pamong option[data-jenis='1']").hide();
                $("#pamong option[data-ttd='1']").hide();
                $('#pamong').attr('disabled', false);
                $('#form_ttd_scan').hide();
            } else {
                $('#pamong').val($("#pamong option[data-jenis='1']").val());
                $('#pamong').attr('disabled', true);
                $('#form_ttd_scan').show();
            }

            $('#pamong').change();
        }
    </script>
@endpush
