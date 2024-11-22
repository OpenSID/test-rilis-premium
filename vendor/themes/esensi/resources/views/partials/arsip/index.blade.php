@extends('layouts.right-sidebar')

@section('content')
<nav role="navigation" aria-label="navigation" class="breadcrumb">
	<ol>
		<li><a href="{{ ci_route('') }}">Beranda</a></li>
		<li aria-current="page">Arsip Artikel</li>
	</ol>
</nav>
<h1 class="text-h2">Arsip Situs Web</h1>
<div>		
<div id="artikel-list"></div>
<nav>
	<p class="pagination-info text-xs lg:text-sm py-3">Halaman 0 dari 0</p>
	<ul id="pagination" class="pagination flex gap-2 flex-wrap">
		<!-- Pagination links will be dynamically generated here -->
	</ul>
</nav>
</div>
@endsection

@push('scripts')
<script src="{{ theme_asset('js/pagination.js') }}"></script>
<script type="text/javascript">	
	$(document).ready(function() {
		const pageSize = 10
		let pageNumber = 1
		let status = ''				

		const loadArtikel = function (pageNumber) {			
			$.ajax({
				url: `{{ ci_route('internal_api.arsip') }}?sort=-tgl_upload&page[number]=${pageNumber}&page[size]=${pageSize}`,
				type: "GET",
				beforeSend: function(){
					const artikelList = document.getElementById('artikel-list');
					artikelList.innerHTML = `@include('commons.loading')`;
				},
				dataType: 'json',
				data: {
					
				},
				success: function (data) {
					displayArtikel(data);
					const pagination = new Pagination(document.getElementById('pagination'))
					pagination.generatePagination(data, loadArtikel)
				}
			});
		}

		const displayArtikel = function (dataArtikel) {
			const artikelList = document.getElementById('artikel-list');
			artikelList.innerHTML = '';
			if(!dataArtikel.data.length) {
				artikelList.innerHTML = `<div class="alert alert-info text-primary-100" role="alert">Data tidak tersedia</div>`
				return
			}
			const ulBlock = document.createElement('ol');
			ulBlock.className = 'divide-y mb-5';
			dataArtikel.data.forEach(item => {
				const card = document.createElement('li');				
				card.className = `py-5`;
				card.innerHTML = `										
                    <span class="fas fa-external-link-alt mr-2"></span>
                    <a class="text-h6 transition-all duration-200 hover:text-link" href="${item.attributes.url_slug}">${item.attributes.judul}</a>
                    <p class="text-xs lg:text-sm">Diterbitkan pada ${item.attributes.tgl_upload_local}</p>
                    <p class="text-xs lg:text-sm">Oleh: ${item.attributes.author.nama}</p>					
				`;
												
				artikelList.appendChild(card);
			});
		}		

		loadArtikel(pageNumber);
	});		
</script>
@endpush