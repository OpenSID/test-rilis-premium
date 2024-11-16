<div class="content py-1">
  <div class="box box-danger" style="padding-bottom: 2rem;">
    <div class="box-header with-border" style="margin-bottom: 15px;">
      <h3 class="box-title"><?= $heading ?></h3>
    </div>
    <div style="margin-right: 1rem; margin-left: 1rem;">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tabelData">
          <thead>
            <tr>
                <th>No</th>
                <th>Judul Informasi</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Tanggal Upload</th>
                <th>Aksi</th>
            </tr>
          </thead>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        var tabelData = $('#tabelData').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ordering: true,
            ajax: {
                url: `{{ route('api.informasi-publik') }}`,
                method: 'GET',
                data: row => ({
                    "page[size]": row.length,
                    "page[number]": (row.start / row.length) + 1,
                    "filter[search]": row.search.value,
                    "sort": `${row.order[0]?.dir === "asc" ? "" : "-"}${row.columns[row.order[0]?.column]?.name}`
                }),
                dataSrc: json => {
                    json.recordsTotal = json.meta.pagination.total;
                    json.recordsFiltered = json.meta.pagination.total;
                    return json.data;
                },
                error: function (xhr, error, thrown) {
                    console.error('AJAX Error:', xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data.');
                }
            },
            columnDefs: [
                {
                    targets: '_all',
                    className: 'text-nowrap',
                },
            ],
            columns: [{
                    data: null,
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'nama',
                    name: 'nama',
                    orderable: true,
                    searchable: true,
                    render: (data, type, row, meta) => {
                        return row.attributes.nama;
                    }
                },
                {
                    data: 'tahun',
                    name: 'tahun',
                    orderable: true,
                    searchable: true,
                    render: (data, type, row, meta) => {
                        return row.attributes.tahun;
                    }
                },
                {
                    data: 'kategori',
                    name: 'kategori',
                    orderable: true,
                    searchable: true,
                    render: (data, type, row, meta) => {
                        return row.attributes.kategori;
                    }
                },
                {
                    data: 'tgl_upload',
                    name: 'tgl_upload',
                    orderable: true,
                    // searchable: true,
                    render: (data, type, row, meta) => {
                        return row.attributes.tgl_upload;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: (data, type, row, meta) => {
                        return `<a href="${row.link}" target="_blank" class="btn btn-primary btn-xs">Lihat</a>`;
                    }
                }
            ],
            order: [
                [4, 'desc']
            ]
        });

        tabelData.on('order.dt search.dt', function() {
            tabelData.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>
@endpush