<nav role="navigation" aria-label="navigation" class="breadcrumb">
  <ol>
    <li><a href="{{ ci_route() }}">Beranda</a></li>
    @if($is_detail)
	<li><a href="{{ ci_route('galeri') }}">Galeri</a></li>
	<li aria-current="page">{{ $title_galeri }}</li>
	@else
	<li aria-current="page">Galeri</li>
	@endif
  </ol>
</nav>
<h1 class="text-h2">@if($is_detail) Album Galeri @else Album @endif {{  $title_galeri }}</h1>

<div>
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-5 main-content py-4" id="galeri-list"></div>
	<nav>
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
					galeriList.innerHTML = `@include('commons.loading')`
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
				galeriList.innerHTML = `<div class="alert text-primary-100">Maaf album galeri belum tersedia!</div>`
				return
			}			
			
			dataGaleri.data.forEach(item => {
				const card = document.createElement('div');								
				const image  = item.attributes.src_gambar ? `<img class="h-44 w-full object-cover object-center" src="${item.attributes.src_gambar}" title="${item.attributes.nama}" alt="${item.attributes.nama}"/>` : ``
				card.innerHTML = `
					<a href="${item.attributes.url_detail}" class="w-full bg-gray-100 block relative">
						${image}
						<p class="py-2 text-center block">${item.attributes.nama}</p>
					</a>					
				`;				
				galeriList.appendChild(card);
			});			
		}		

		loadGaleri(1);
	});	
</script>
@endpush
