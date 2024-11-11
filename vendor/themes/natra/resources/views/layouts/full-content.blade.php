<style>
	.web .content-wrapper {
		margin-left: 0px !important;
	}
</style>
<section id="mainContent">
	<div class="content_bottom">
		<div class="col-lg-12 col-md-12">
			<div id="contentwrapper" class="web">
				@if ($tampil)
					@include("layouts.partials.{$halaman}")
				@else
					@include('layouts.partials.not_found')
				@endif
			</div>
		</div>
	</div>
</section>