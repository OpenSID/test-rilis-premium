<div class="single_category wow fadeInDown">
	<h2><span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">@if($is_detail) <a href="{{ ci_route('galeri') }}">Album Galeri</a> @else Album @endif {{  $title_galeri }}</span></h2>
</div>

<div style="content_left">
	<div class="col-md-12 col-lg-12" id="galeri-list"></div>
	<nav class="pagination_area text-center">
		<div class="pagination-info">Halaman 0 dari 0</div>
		<ul id="pagination" class="pagination">
			<!-- Pagination links will be dynamically generated here -->
		</ul>
	</nav>
</div>

@push('scripts')
<script src="{{ theme_asset('js/pagination.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		const pageSize = {{ $is_detail ? 10 : 6 }}		
		let status = ''			
		
		const loadGaleri = function (pageNumber) {			
			$.ajax({
				url: `{{ $url_api }}?sort=-tgl_upload&page[number]=${pageNumber}&page[size]=${pageSize}`,
				type: "GET",
				beforeSend: function(){
					const galeriList = document.getElementById('galeri-list');
					galeriList.innerHTML = `@include('commons.loading)`;
				},
				dataType: 'json',
				data: {
					
				},
				success: function (data) {
					displayGaleri(data);
					const pagination = new Pagination(document.getElementById('pagination'))
					pagination.generatePagination(data, loadGaleri)
				}
			});
		}

		const displayGaleri = function (dataGaleri) {
			const galeriList = document.getElementById('galeri-list');
			galeriList.innerHTML = '';
			if(!dataGaleri.data.length) {
				galeriList.innerHTML = `<div class="alert alert-info" role="alert">Data tidak tersedia</div>`
				return
			}
			const ulBlock = document.createElement('div');
			ulBlock.className = 'row';
			dataGaleri.data.forEach(item => {
				const card = document.createElement('div');								
				const image  = item.attributes.src_gambar ? `<img class="img-fluid img-thumbnail" src="${item.attributes.src_gambar}" alt="${item.attributes.nama}"/>` : ``
				card.innerHTML = `
					<a href="${item.attributes.url_detail}">
						<div class="col-sm-6">
							<div class="card">
                                ${image}
								<p align="center"><b>Album : ${item.attributes.nama}</b></p>
								<hr/>
							</div>
						</div>
					</a>
				`;
				card.onclick = function(){}				
				galeriList.appendChild(card);
			});		
		}
		loadGaleri(1);
	});	
</script>
@endpush

@push('styles')
<style>

	#galeri-list .card{
		height: 400px;
		padding:5px;		
		margin:5px
	}
	#galeri-list .card img{
		height: 360px;
		background-size: cover;
		margin: 0 auto;
		width: 100%;
	}

</style>
@endpush