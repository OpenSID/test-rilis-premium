@php
  $themeVersion = 'v2409.0.0';  
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('commons.meta')
    @include('commons.source_css')
    @include('commons.source_js')
    <title>@yield('title')</title>
    @stack('styles')
</head>

<body class="font-primary bg-gray-100">
    @include('commons.loading_screen')
    @include('commons.header')

    @yield('content')

    @include('commons.footer')
    
    <script src="{{ theme_asset('js/script.min.js') }}?{{$themeVersion}}"></script>
    @stack('scripts')
</body>

</html>