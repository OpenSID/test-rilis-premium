@push('styles')
    <style>
        .pagination {
            margin-top: 10px;
        }
    </style>
@endpush

<hr style="margin: 10px -20px">
<div class="pagination_area text-center" id="pagination-container" style="display: none;">
    <div id="pagination-info">Halaman 1 dari 2</div>
    <ul class="pagination" id="pagination-list">
    </ul>
</div>

@push('scripts')
    <script src="{{ theme_asset('js/pagination.js') }}"></script>
@endpush