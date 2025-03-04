<div class="tab-pane" id="2fa">
    <div class="box-body">
        <div class="form-group">
            <div class="input-group col-xs-12 col-sm-4">
                @if ($userData->tfa_enabled)
                    {!! form_open(ci_route('pengguna.kirim_verifikasi_nonaktifkan_2fa')) !!}
                    <button type="submit" class="btn btn-sm btn-danger btn-block btn-mb-5"><i class="fa fa-share-square"></i>
                        Non Aktifkan 2FA Menggunakan Kode OTP yang Dikirim ke Email</button>
                    </form>
                @else
                    {!! form_open(ci_route('pengguna.kirim_verifikasi_aktifkan_2fa')) !!}
                    <button type="submit" class="btn btn-sm btn-success btn-block btn-mb-5"><i class="fa fa-share-square"></i>
                        Aktifkan 2FA Menggunakan Kode OTP yang Dikirim ke Email</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
