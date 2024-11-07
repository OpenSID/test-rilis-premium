@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

@if ($paging->num_rows > $paging->per_page)
    <div class="pagination_area text-center">
        <div>Halaman {{ $paging->page }} dari {{ $paging->end_link }}</div>
        <ul class="pagination">
            @if ($paging->start_link)
                <li><a href="{{ site_url("$paging_page/$paging->start_link" . $paging->suffix) }}" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
            @endif
            @if ($paging->prev)
                <li><a href="{{ site_url("$paging_page/$paging->prev" . $paging->suffix) }}" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
            @endif
            @for ($i = $paging->start_link; $i <= $paging->end_link; $i++)
                <li class="{{ $paging->page != $i ?: 'active' }}"><a href="{{ site_url("$paging_page/$i" . $paging->suffix) }}" title="{{ 'Halaman ' . $i }}">{{ $i }}</a></li>
            @endfor
            @if ($paging->next)
                <li><a href="{{ site_url("$paging_page/$paging->next" . $paging->suffix) }}" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
            @endif
            @if ($paging->end_link)
                <li><a href="{{ site_url("$paging_page/$paging->end_link" . $paging->suffix) }}" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
            @endif
        </ul>
    </div>
@endif
