<nav role="navigation" aria-label="navigation" class="breadcrumb">
    <ol>
        <li><a href="<?= site_url() ?>">Beranda</a></li>
        <li aria-current="page">Lapak</li>
    </ol>
</nav>
<h1 class="text-h2"><i class="fas fa-store mr-1"></i> Lapak</h1>
<form id="form-cari" method="get" class="w-full block py-4">
    <div class="flex gap-3 lg:w-7/12 flex-col lg:flex-row">
        <select class="form-input inline-block select2" id="id_kategori" name="id_kategori" style="min-width: 25%">
            <option selected value="">Semua Kategori</option>
        </select>
        <input type="text" id="search" name="search" maxlength="50" class="form-input" placeholder="Cari Produk"
            style="min-width: 35%">
        <button type="button" id="btn-cari" class="btn btn-primary flex-shrink-0 text-center">Cari</button>
        <button type="button" id="btn-semua" class="btn btn-info flex-shrink-0 text-center" style="display: none;">Tampil Semua</button>
    </div>
</form>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-1" id="produk-list">
</div>


{{-- @includ pagination --}}
@include('commons.pagination')

<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto show" id="modalLokasi" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
        <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
            <h5 class="text-h6">Lokasi Penjual</h5>
        </div>
        <div class="modal-body p-4">
            <div id="map" style="width: 100%; height: 350px; position: relative;"></div>
        </div>
    </div>
</div>

@push('scripts')
{{-- <script src="{{ theme_asset('js/pagination.js') }}"></script> --}}
<script src="{{ theme_asset('js/owl.carousel.min.js') }}"></script> <!-- Pastikan file Owl Carousel disertakan -->
<script type="text/javascript">
    $(document).ready(function () {
        // Mengisi kategori
        var apiKategori = '{{ route("api.lapak.kategori") }}';
        $.get(apiKategori, function (data) {
            var kategori = data.data;
            var select = $('#id_kategori');
            kategori.forEach(function (item) {
                select.append('<option value="' + item.id + '">' + item.attributes.kategori + '</option>');
            });
        });

        // Fungsi untuk memuat data produk
        function loadProduk(params = {}) {
            
            var apiProduk = '{{ route("api.lapak.produk") }}';

            $('#produk-list').html('<p class="text-center">Memuat...</p>');

            $.get(apiProduk, params, function (data) {
                var produk = data.data;
                var produkList = $('#produk-list');
                var paginationInfo = $('#pagination-info');
                var paginationList = $('#pagination-list');

                produkList.empty();
                paginationInfo.empty();
                paginationList.empty();

                if (!produk.length) {
                    produkList.html('<p class="py-2">Tidak ada produk yang tersedia</p>');
                    return;
                }

                // Menampilkan produk
                produk.forEach(function (item) {
                    var fotoHTML = '<div class="owl-carousel">';
                    var fotoList = item.attributes.foto;

                    fotoList.forEach(function (fotoItem) {
                        fotoHTML += `<div class="item"><img src="${fotoItem}" alt="Foto Produk" class="h-44 w-full object-cover object-center bg-gray-300"></div>`;
                    });

                    fotoHTML += '</div>';

                    var hargaDiskon = formatRupiah(item.attributes.harga_diskon, 'Rp ');
                    var hargaAwal = formatRupiah(item.attributes.harga, 'Rp ');
                    var viewDiskon = (hargaAwal === hargaDiskon) ? `` : `<s class="text-xs text-red-500">${hargaAwal}</s>`;

                    var produkHTML = `
                        <div class="flex flex-col justify-between space-y-4 this-product">
                            <div class="space-y-3">
                                ${fotoHTML}
                                <div class="space-y-1/2 text-sm flex flex-col detail">
                                    <span class="font-heading font-medium">${item.attributes.nama}</span>
                                    ${viewDiskon}
                                    <span class="text-lg font-bold">${hargaDiskon} <span class="text-xs font-thin">/ ${item.attributes.satuan}</span></span>
                                    <p class="text-xs pt-1">${item.attributes.deskripsi}</p>
                                    <span class="pt-2 text-xs font-bold text-gray-500 dark:text-gray-50">
                                        <i class="fas fa-award mr-1"></i> ${item.attributes.pelapak.penduduk.nama ?? 'Admin'} <i class="fas fa-check text-xs bg-green-500 h-4 w-4 inline-flex items-center justify-center rounded-full text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="group flex items-center space-x-1">
                                <a href="${item.attributes.pesan_wa}" 
                                    rel="noopener noreferrer" target="_blank" class="btn btn-primary text-xs text-center">
                                    <i class="fa fa-shopping-cart mr-1"></i> Beli Sekarang
                                </a>
                                <button id="tampil-modal" type="button" class="btn btn-secondary text-xs text-center rounded-0" 
                                    data-lat="${item.attributes.pelapak.lat}" data-lng="${item.attributes.pelapak.lng}" 
                                    data-zoom="${item.attributes.pelapak.zoom}" data-title="Lokasi ${item.attributes.pelapak.penduduk.nama}">
                                    <i class="fas fa-map-marker-alt mr-1"></i> Lokasi
                                </button>
                            </div>
                        </div>
                    `;

                    produkList.append(produkHTML);
                });

                // Menangani pagination
                var totalPages = data.meta.pagination.total_pages;
                var currentPage = data.meta.pagination.current_page;

                
                // Jika hanya ada satu halaman, tidak menampilkan pagination
                if (totalPages > 1) {
                    var paginationInfoHTML = `Halaman ${currentPage} dari ${totalPages}`;
                    var paginationListHTML = `<ul class="pagination flex gap-2 flex-wrap">`;

                    // Pagination First
                    paginationListHTML += `<li class="page-item">
                                                <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 btn-page" data-page="1">
                                                    <i class="fas fa-arrow-left"></i>
                                                </button>
                                            </li>`;
                            
                    // Previous page button
                    if (currentPage > 1) {
                        paginationListHTML += `<li class="page-item">
                                                    <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 btn-page" data-page="1">
                                                        <i class="fas fa-chevron-left inline-block"></i>
                                                    </button>
                                                </li>`;
                    }

                    // Page number buttons
                    for (var i = 1; i <= totalPages; i++) {
                        paginationListHTML += `<li class="page-item">
                                                    <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-${i === currentPage ? 'primary-100 text-white' : 'white hover:text-primary-200'} btn-page" data-page="${i}">
                                                        ${i}
                                                    </button>
                                                </li>`;
                    }

                    // Next page button
                    if (currentPage < totalPages) {
                        paginationListHTML += `<li class="page-item">
                                                    <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 btn-page" data-page="${currentPage + 1}">
                                                        <i class="fas fa-chevron-right inline-block"></i>
                                                    </button>
                                                </li>`;
                    }

                    // Pagination Last
                    paginationListHTML += `<li class="page-item">
                                                <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 btn-page" data-page="${totalPages}">
                                                    <i class="fas fa-arrow-right"></i>
                                                </button>
                                            </li>`;

                    paginationListHTML += `</ul>`;
                    paginationList.html(paginationListHTML);
                } else {
                    paginationList.empty(); // Tidak ada pagination jika hanya ada satu halaman
                }

                paginationInfo.html(paginationInfoHTML);

                // Inisialisasi Owl Carousel setelah produk dimuat
                $('.owl-carousel').owlCarousel({
                    items: 1,
                    loop: true,
                    margin: 10,
                    nav: false,
                    dots: true,
                    autoplay: true,
                    autoplayTimeout: 3000
                });
            });
        }



        $('#btn-cari').on('click', function () {
            var params = {};
            var kategori = $('#id_kategori').val();
            var search = $('#search').val();

            if (kategori) {
                params['filter[id_produk_kategori]'] = kategori;
            }

            if (search) {
                params['filter[search]'] = search;
            }
            
            loadProduk(params);

            $('#btn-semua').show();
        });

        $('.pagination').on('click', '.btn-page', function() {
            var params = {};
            var page = $(this).data('page');
            var kategori = $('#id_kategori').val();
            var search = $('#search').val();

            if (kategori) {
                params['filter[id_produk_kategori]'] = kategori;
            }

            if (search) {
                params['filter[search]'] = search;
            } 

            params['page[number]'] = page;

            loadProduk(params);
        });

        $('#btn-semua').on('click', function () {
            loadProduk();
            $('#btn-semua').hide();
            $('#search').val('');
            $('#id_kategori').val('');
        });

        $('#search').keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                $('#btn-cari').trigger('click');
            }
        });

        loadProduk();

        // saat clik tampil modal, maka tampilkan modal
        $(document).on('click', '#tampil-modal', function () {
            // saat di klik, modal baru tampil
            var lat = $(this).data('lat');
            var lng = $(this).data('lng');
            var zoom = $(this).data('zoom');
            var title = $(this).data('title');

            $('#modalLokasi').modal('show');

            var map = L.map('map').setView([lat, lng], zoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(map);

            // Menambahkan marker dan memastikan peta terpusat di marker
            var marker = L.marker([lat, lng]).addTo(map).bindPopup(title).openPopup();

            // Memastikan marker selalu berada di tengah peta
            map.setView([lat, lng], zoom);
        });

    });
</script>
@endpush