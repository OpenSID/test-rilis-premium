@if (ci_session('success'))
<div id="notifikasi" class="alert alert-success alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-check"></i> Berhasil</h4>
  <p>{!! ci_session('success') !!}</p>
</div>
@endif

@if (ci_session('error'))
<div id="notifikasi" class="alert alert-danger alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-ban"></i> Gagal</h4>
  <p>{!! ci_session('error') !!}</p>
</div>
@endif

@if(ci_session('warning'))
<div id="notifikasi" class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-warning"></i> Peringatan</h4>
  <p>{!! ci_session('warning') !!}</p>
</div>
@endif

@if(ci_session('information'))
<div id="notifikasi" class="alert alert-info alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h4><i class="icon fa fa-info"></i> Informasi</h4>
  <p>{!! ci_session('information') !!}</p>
</div>
@endif