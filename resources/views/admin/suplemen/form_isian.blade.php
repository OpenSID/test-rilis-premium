@if ($suplemen->form_isian)
    <h5><b>Form Isian</b></h5>
    <div class="col-sm-12">
        @foreach ($formData as $field)
            @php
                $class = $field['atribut'] ? buat_class($field['atribut'], '', $field['required']) : '';
                $widthClass = $field['kolom'] ? 'col-sm-' . $field['kolom'] : 'col-sm-12';
            @endphp

            <div class="form-group {{ $widthClass }}">
                @if ($field['tipe'] == 'date')
                    <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                    <input type="date" class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}" value="{{ old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}">
                @elseif($field['tipe'] == 'text')
                    <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                    <input type="text" class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}"
                        value="{{ old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}"
                    >
                @elseif($field['tipe'] == 'textarea')
                    <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                    <textarea class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}" placeholder="{{ $field['deskripsi_kode'] }}">{{ old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') }}</textarea>
                @elseif($field['tipe'] == 'select-manual')
                    <label for="{{ $field['nama_kode'] }}">{{ $field['label_kode'] }}</label>
                    <div class="{{ $widthClass }}">
                        <select class="form-control {{ $class }}" name="input_data[{{ $field['nama_kode'] }}]" id="{{ $field['nama_kode'] }}">
                            <option value="">-- {{ $field['deskripsi_kode'] }} --</option>
                            @foreach ($field['pilihan_kode'] as $pilih)
                                <option value="{{ $pilih }}" @selected(old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') == $pilih)>
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
                                <option value="{{ $pilih }}" @selected(old('input_data[' . $field['nama_kode'] . ']', isset($existingData[$field['nama_kode']]) ? $existingData[$field['nama_kode']] : '') == $pilih)>
                                    {{ $pilih }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
