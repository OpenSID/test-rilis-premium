@extends('main')

@section('content')
    <section>
        <div class="content_bottom">
            <div class="row">
                <div class="col-lg-9 col-md-9">
                    @if ($tipe == 2)
                        @include("partials.statistik.statistik_sos")
                    @elseif ($tipe == 3)
                        @include("partials.statistik.wilayah")
                    @elseif ($tipe == 4)
                        @include("partials.statistik.dpt")
                    @else
                        @include("partials.statistik.default")
                    @endif
                </div>
                <div class="col-lg-3 col-md-3">
                    @include("partials.sidebar")
                </div>
            </div>
        </div>
    </section>
@endsection