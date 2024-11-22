@extends('template')

<section>
  <div class="content_bottom">
    <div class="row">
      <div class="col-lg-9 col-md-9">
          <div class="content_left">
            @include("layouts.content")
          </div>
      </div>
      <div class="col-lg-3 col-md-3">
          @yield('content')
      </div>
    </div>
  </div>
</section>
@endsection