@php
  $themeVersion = 'v2409.0.0';  
@endphp
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
    @yield('content')
    @stack('scripts')

    <script src="{{ theme_asset("js/script.min.js?" . $themeVersion) }}"></script>
</body>

</html>