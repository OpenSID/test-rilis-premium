<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @stack('styles')
</head>

<body>
    @includeWhen($layout, "layouts.$layout")

    @stack('scripts')
</body>

</html>