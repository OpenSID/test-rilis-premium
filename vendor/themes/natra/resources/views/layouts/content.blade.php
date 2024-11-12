@if(isset($halaman))
    @include("partials.{$halaman}")
@else
    @include('commons.404')
@endif