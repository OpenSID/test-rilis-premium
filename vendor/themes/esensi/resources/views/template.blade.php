<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('layouts.commons.meta')
    @include('layouts.commons.source_css')
    @include('layouts.commons.source_js')
    <title>@yield('title')</title>
    @stack('styles')
</head>

<body class="font-primary bg-gray-100">
    @if(request()->segment(2) == 'kategori' && empty($judul_kategori))
        @include('layouts.commons.404')
    @else
        @include('layouts.commons.loading_screen')
        @include('layouts.commons.header')

        @if ($layout)
            @include("layouts.$layout")
        @else
            @include('layouts.right-sidebar')
        @endif

        @include('layouts.commons.footer')
    @endif
    @stack('scripts')

    <script src="{{ theme_asset("js/script.min.js?" . THEME_VERSION) }}"></script>
</body>

</html>