@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="content_left" style="margin-bottom:10px;">
    <div class="archive_style_1">
        <div style="margin-top:10px;">
            @if (!empty($teks_berjalan))
            <marquee onmouseover="this.stop()" onmouseout="this.start()">
                @include("layouts.teks_berjalan")
            </marquee>
            @endif
        </div>
        @include("layouts.slider")
        @if (setting('covid_data')) @include("layouts.partials.corona-widget") @endif
        @if (setting('covid_desa')) @include("layouts.partials.corona-local") @endif
        @if ($headline)
        @php $abstrak_headline = potong_teks($headline['isi'], 550) @endphp
        <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span
                    class="title_text">Berita Utama</span> </h2>
        </div>
        <div id="headline" class="single_category wow fadeInDown">
            <div class="archive_style_1">
                <div class="business_category_left wow fadeInDown">
                    <ul class="fashion_catgnav">
                        <li>
                            <div class="catgimg2_container2">
                                <h5 class="catg_titile">
                                    <a href="{{ $headline->url_slug }}"> {{ $headline['judul']
                                        }}</a>
                                </h5>
                                <a href="{{ $headline->url_slug }}">
                                    @if ($headline['gambar'] != '')
                                    @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $headline['gambar']))
                                    <img data-src="{{ AmbilFotoArtikel($headline['gambar'], 'sedang') }}"
                                        src="{{ asset('images/img-loader.gif') }}" width="300"
                                        class="yall_lazy img-fluid img-thumbnail hidden-sm hidden-xs"
                                        style="float:left; margin:0 8px 4px 0;" />
                                    <img data-src="{{ AmbilFotoArtikel($headline['gambar'], 'sedang') }}"
                                        src="{{ asset('images/img-loader.gif') }}" width="100%"
                                        class="yall_lazy img-fluid img-thumbnail hidden-lg hidden-md"
                                        style="float:left; margin:0 8px 4px 0;" />
                                    @else
                                    <img src="{{ theme_asset('images/noimage.png') }}" width="300px"
                                        class="img-fluid img-thumbnail hidden-sm hidden-xs"
                                        style="float:left; margin:0 8px 4px 0;" />
                                    <img src="{{ theme_asset('images/noimage.png') }}" width="100%"
                                        class="img-fluid img-thumbnail hidden-lg hidden-md"
                                        style="float:left; margin:0 8px 4px 0;" />
                                    @endif
                                    @endif
                                </a>
                                <div style="text-align: justify;" class="hidden-sm hidden-xs">
                                    {!! $abstrak_headline !!} ...
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
    @php $title = (!empty($judul_kategori)) ? $judul_kategori : 'Artikel Terkini' @endphp
    @if (is_array($title))
    @foreach ($title as $item)
    @php $title = $item @endphp
    @endforeach
    @endif
    <div class="single_category wow fadeInDown">
        <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">{{
                $title }}</span> </h2>
    </div>
    @if ($artikel)
    <div class="single_category wow fadeInDown">
        <div class="archive_style_1">
            @foreach ($artikel as $data)
            @php $abstrak = potong_teks($data['isi'], 550) @endphp
            <div class="business_category_left wow fadeInDown">
                <ul class="fashion_catgnav">
                    <li>
                        <div class="catgimg2_container2">
                            <h5 class="catg_titile">
                                <a href="{{ $data->url_slug }}" title="Baca Selengkapnya">{{
                                    $data['judul'] }}</a>
                            </h5>
                            <div class="post_commentbox">
                                <span class="meta_date">{{ tgl_indo($data['tgl_upload']) }}&nbsp;
                                    <i class="fa fa-user"></i>{{ $data['owner'] }}&nbsp;
                                    <i class="fa fa-eye"></i>{{ hit($data['hit']) }}&nbsp;
                                    <i class="fa fa-comments"></i>
                                    {{ $data->jumlah_komentar }}
                                    &nbsp;
                                </span>
                            </div>
                            <a href="{{ $data->url_slug }}" title="Baca Selengkapnya"
                                style="font-weight:bold">
                                @if (is_file(LOKASI_FOTO_ARTIKEL.'kecil_'.$data['gambar']))
                                <img data-src="{{ AmbilFotoArtikel($data['gambar'], 'sedang') }}"
                                    src="{{ asset('images/img-loader.gif') }}" width="300"
                                    class="yall_lazy img-fluid img-thumbnail hidden-sm hidden-xs"
                                    style="float:left; margin:0 8px 4px 0;" alt="{{ $data['judul'] }}" />
                                <img data-src="{{ AmbilFotoArtikel($data['gambar'], 'sedang') }}"
                                    src="{{ asset('images/img-loader.gif') }}" width="100%"
                                    class="yall_lazy img-fluid img-thumbnail hidden-lg hidden-md"
                                    style="float:left; margin:0 8px 4px 0;" alt="{{ $data['judul'] }}" />
                                @else
                                <img src="{{ theme_asset('images/noimage.png') }}" width="300px"
                                    class="img-fluid img-thumbnail hidden-sm hidden-xs"
                                    style="float:left; margin:0 8px 4px 0;" alt="{{ $data['judul'] }}" />
                                <img src="{{ theme_asset('images/noimage.png') }}" width="100%"
                                    class="img-fluid img-thumbnail hidden-lg hidden-md"
                                    style="float:left; margin:0 8px 4px 0;" alt="{{ $data['judul'] }}" />
                                @endif
                            </a>
                            <div style="text-align: justify;" class="hidden-sm hidden-xs">
                                {!! $abstrak !!} ...
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="business_category_left wow fadeInDown" id="artikel-blank">
        <div class="box box-warning box-solid">
            <div class="box-header">
                <h3 class="box-title">Maaf, belum ada data</h3>
            </div>
            <div class="box-body">
                <p>Belum ada artikel yang dituliskan dalam {{ $title }}</p>
                <p>Silakan kunjungi situs web kami dalam waktu dekat.</p>
            </div>
        </div>
    </div>
    @endif
</div>
@if(isset($links))
    {!! $links->links('admin.layouts.components.pagination_default') !!}
@else
@include("layouts.commons.page")
@endif