@extends('main')
@include('commons.asset_highcharts')
@section('content')
<div class="container mx-auto lg:px-5 px-3 flex flex-col-reverse lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
    <div class="lg:w-1/3 w-full">
      @include('partials.statistik.sidenav')
    </div>
    <main class="lg:w-3/4 w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
      @php              
          switch ($tipe) {
            case null:
            case '0':
              $page = 'partials.statistik.default';
              break;
            case '3':
              $page = 'partials.statistik.regions';
              break;
            case '4':
              $page = 'partials.statistik.voters';
              break;
            default:
              $page = 'commons.404';
              break;
          }
        @endphp
        @include($page)
      <script>
        const enable3d = {{ setting('statistik_chart_3d') ? 1 : 0 }};
      </script>
    </main>
</div>
@endsection