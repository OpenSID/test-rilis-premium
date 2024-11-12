@extends('main')

@section('content')
    @if(request()->segment(2) == 'kategori' && empty($judul_kategori))
        @include('commons.404')
    @else
        @if ($tampil)
            @if ($layout)
                @include("layouts.$layout")
            @else
                @include('layouts.right-sidebar')
            @endif
        @else
            @include('commons.404')
        @endif
    @endif
@endsection