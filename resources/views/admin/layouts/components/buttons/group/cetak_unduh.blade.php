@php
    $judul  = 'Cetak/Unduh';
    $icon   = 'fa fa-arrow-circle-down';
    $type   = 'bg-purple';
    $target = true;
    $list   = [
        ['url' => $url_cetak, 'judul' => $judul_cetak ?? 'Cetak', 'icon' => 'fa fa-print'],
        ['url' => $url_unduh, 'judul' => $judul_unduh ?? 'Unduh', 'icon' => 'fa fa-download']
    ];
@endphp

@include('admin.layouts.components.buttons.split', [
    'judul' => $judul,
    'icon' => $icon,
    'type' => $type,
    'list' => $list,
    'target' => $target
])