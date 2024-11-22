@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="single_page_area">
	<h2 class="post_titile">{{ $heading }}</h2>
	<div class="box-body">
		<div class="table-responsive">
			@if(count($daftar_dusun ?? []) > 0)
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>No</th>
						<th colspan="8">Wilayah / Ketua</th>
						<th class="center">KK</th>
						<th class="center">L+P</th>
						<th class="center">L</th>
						<th class="center">P</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($daftar_dusun as $key_dusun => $data_dusun)
					<tr>
						<td align="center">{{ $key_dusun + 1 }}</td>
						<td align="left" colspan="8">
							{{ ucwords(setting('sebutan_dusun') . ' ' . $data_dusun['dusun']) }}
							@if ($data_dusun['nama_kadus'])
							, {{ ucwords(setting('sebutan_kepala_dusun')) . ' ' . $data_dusun['nama_kadus'] }}
							@endif
						</td>
						<td align="right">{{ $data_dusun['jumlah_kk'] }}</td>
						<td align="right">{{ $data_dusun['jumlah_warga'] }}</td>
						<td align="right">{{ $data_dusun['jumlah_warga_l'] }}</td>
						<td align="right">{{ $data_dusun['jumlah_warga_p'] }}</td>
					</tr>

					@php $no_rw = 1; @endphp
					@foreach ($data_dusun['daftar_rw'] as $data_rw)
					@if ($data_rw['rw'] != '-')
					<tr>
						<td></td>
						<td align="center">{{ $no_rw++ }}</td>
						<td align="left" colspan="7">
							RW {{ $data_rw['rw'] }}
							@if ($data_rw['nama_ketua'])
							, Ketua {{ $data_rw['nama_ketua'] }}
							@endif
						</td>
						<td align="right">{{ $data_rw['jumlah_kk'] }}</td>
						<td align="right">{{ $data_rw['jumlah_warga'] }}</td>
						<td align="right">{{ $data_rw['jumlah_warga_l'] }}</td>
						<td align="right">{{ $data_rw['jumlah_warga_p'] }}</td>
					</tr>
					@endif

					@php $no_rt = 1; @endphp
					@foreach ($data_rw['daftar_rt'] as $data_rt)
					@if ($data_rt['rt'] != '-')
					<tr>
						<td></td>
						<td></td>
						<td align="center">{{ $no_rt++ }}</td>
						<td align="left" colspan="6">
							RT {{ $data_rt['rt'] }}
							@if ($data_rt['nama_ketua'])
							, Ketua {{ $data_rt['nama_ketua'] }}
							@endif
						</td>
						<td align="right">{{ $data_rt['jumlah_kk'] }}</td>
						<td align="right">{{ $data_rt['jumlah_warga'] }}</td>
						<td align="right">{{ $data_rt['jumlah_warga_l'] }}</td>
						<td align="right">{{ $data_rt['jumlah_warga_p'] }}</td>
					</tr>
					@endif
					@endforeach
					@endforeach
					@endforeach
				</tbody>
				<tfoot>
					<tr style="background-color:{{ theme_config('warna_dasar', '#e64946') }};font-weight:bold;">
						<td colspan="9" align="left"><label>TOTAL</label></td>
						<td align="right">{{ $total['total_kk'] }}</td>
						<td align="right">{{ $total['total_warga'] }}</td>
						<td align="right">{{ $total['total_warga_l'] }}</td>
						<td align="right">{{ $total['total_warga_p'] }}</td>
					</tr>
				</tfoot>
			</table>
			@else
			Belum ada data...
			@endif
		</div>
	</div>
</div>