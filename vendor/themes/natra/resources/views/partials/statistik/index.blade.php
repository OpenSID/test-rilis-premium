@extends('template')
@include('commons.asset_highcharts')

@section('content')
    <section>
        <div class="content_bottom">
            <div class="row">
                <div class="col-lg-9 col-md-9">
                    @include("partials.statistik.default")
                </div>
                <div class="col-lg-3 col-md-3">
                    @include("partials.sidebar")
                </div>
            </div>
        </div>
    </section>
@endsection