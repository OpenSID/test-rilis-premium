@php
  $title = (!empty($judul_kategori)) ? $judul_kategori : 'Artikel Terkini';
  $slug = 'terkini';
  if (is_array($title)) {
    $slug = $title['slug'];
    $title = $title['kategori'];
  }
@endphp

<div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
  <main class="lg:w-2/3 w-full overflow-hidden space-y-5">
    <!-- Tampilkan slider hanya di halaman awal. Tidak tampil pada daftar artikel di halaman kategori atau halaman selanjutnya serta halaman hasil pencarian -->
    @if (empty($cari) && count($slider_gambar ?? []) > 0 && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
      @include('layouts.partials.slider')
    @endif

    <!-- Judul Kategori / Artikel Terkini -->
    <div class="flex justify-between items-center w-full">
      <h3 class="text-h4 text-primary-200">{{ $title }}</h3>
      <a href="{{ site_url('arsip') }}" class="text-sm hover:text-primary-100">Indeks <i class="fas fa-chevron-right ml-1"></i></a>
    </div>

    @if (empty($cari) && count($slider_gambar ?? []) > 0 && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
      @include('layouts.partials.headline')
    @endif

    @if ($artikel)
      @foreach ($artikel as $post)
        @include('layouts.partials.article_list', ['post' => $post])
      @endforeach
      <div class="pagination space-y-1 flex-wrap w-full">
        @include('layouts.commons.paging', ['paging_page' => $paging_page])
      </div>
    @else
      @include('layouts.partials.empty_article', ['title' => $title])
    @endif
  </main>

  <!-- Bagian sidebar / widget -->
  <div class="lg:w-1/3 w-full">
    @include('layouts.partials.sidebar')
  </div>
</div>
