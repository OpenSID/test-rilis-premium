@extends('main')

@section('content')
        @if ($tampil)
            @if ($layout)
                @include("layouts.$layout")
            @else
                @include('layouts.right-sidebar')
            @endif
        @else
            @include('commons.not_found')
        @endif
@endsection