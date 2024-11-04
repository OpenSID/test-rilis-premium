@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('lib/bootstrap/css/dataTables.bootstrap.min.css') }}">
@endpush

@push('scripts')
    <!-- DataTables JS-->
    <script src="{{ asset('lib/jquery/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap/js/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $.extend($.fn.dataTable.defaults, {
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"]
            ],
            pageLength: 10,
            language: {
                url: "{{ asset('lib/bootstrap/js/dataTables.indonesian.lang') }}",
            }
        });
    </script>
@endpush
