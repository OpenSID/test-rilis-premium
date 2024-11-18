@extends('main')

@section('content')
<div class="container mx-auto lg:px-5 px-3 flex flex-col-reverse lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
    <div class="lg:w-1/3 w-full">
        @include('partials.statistik.sidenav')
    </div>
    <main class="lg:w-3/4 w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
        <div class="breadcrumb">
            <ol>
            <li><a href="<?= site_url() ?>">Beranda</a></li>
            <li>Data Statistik</li>
            </ol>
        </div>
        <h1 class="text-h2">Data Statistik Wilayah</h1>

        <div class="table-responsive content py-3">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th colspan="8">Wilayah / Ketua</th>
                        <th class="text-center">KK</th>
                        <th class="text-center">L+P</th>
                        <th class="text-center">L</th>
                        <th class="text-center">P</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
        </div>
    </main>
</div>
@endsection