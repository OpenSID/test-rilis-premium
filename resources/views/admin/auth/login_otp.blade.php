@extends('admin.auth.index')

@section('content')
    <form id="validasi" class="login-form" action="<?= site_url('siteman/kirim_otp') ?>" method="post"><br>
        <div class="form-group">
            <input name="token_email" id="token_email" type="password" placeholder="Masukan OTP Verifikasi" autocomplete="off" class="form-control required bilangan pin required kbvnumber">
        </div>
        <div class="form-group">
            <input @disabled($second) type="checkbox" id="checkbox" class="form-checkbox">
            <label for="checkbox" style="font-weight: unset">Tampilkan kata sandi</label>
            <a href="{{ site_url('siteman/logout') }}" class="btn" role="button" aria-pressed="true">Kembali ke halaman login?</a>
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Kirim</button>
        </div>
    </form>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var pass = $("#token_email");
            $('#checkbox').click(function() {
                if (pass.attr('type') === "password") {
                    pass.attr('type', 'text');
                } else {
                    pass.attr('type', 'password')
                }
            });
        });
    </script>
@endpush
