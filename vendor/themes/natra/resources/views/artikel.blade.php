@extends('layouts.index')
@section('content')

	<section class="content">
		@if($single_artikel['tampilan'] == 1)
		<div class="col-lg-9 col-md-9">
			<div class="content_left">
				@include("layouts.partials.artikel")
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="content_right">
				@include("layouts.partials.bottom_content_right")
			</div>
		</div>
		@elseif ($single_artikel['tampilan'] == 2)
		<div class="col-lg-3 col-md-3">
			<div class="content_right">
				@include("layouts.partials.bottom_content_right")
			</div>
		</div>
		<div class="col-lg-9 col-md-9">
			<div class="content_left">
				@include("layouts.partials.artikel")
			</div>
		</div>
		@else
		<div class="col-lg-12 col-md-12">
			<div class="content_left">
				@include("layouts.partials.artikel")
			</div>
		</div>
		@endif
	</section>

@endsection
