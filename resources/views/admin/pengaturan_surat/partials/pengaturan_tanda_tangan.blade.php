<div class="tab-pane" id="tte">
    <div class="box-body">
        @if (!$kades)
            <div class="callout callout-danger">
                <p>Pengaturan modul TTE hanya bisa aktif jika user <strong>Kepala
                        {{ setting('sebutan_desa') }}</strong> sudah dibuat dan aktif.</p>
            </div>
        @endif
        <div class="form-group">
            <label>Aktifkan Modul TTE</label>
            <div class="input-group col-xs-12 col-sm-8">
                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="padding: 0px;">
                    <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('tte') == '1') @disabled(!$kades)">
                        <input
                            type="radio"
                            name="tte"
                            class="form-check-input"
                            value="1"
                            autocomplete="off"
                            @checked(setting('tte') == '1')
                            @disabled(!$kades)
                        >Ya</label>
                    <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('tte') == '0') @disabled(!$kades)">
                        <input
                            type="radio"
                            name="tte"
                            class="form-check-input"
                            value="0"
                            autocomplete="off"
                            @checked(setting('tte') == '0')
                            @disabled(!$kades)
                        >Tidak
                    </label>
                </div>
            </div>
        </div>
        <div id="modul-tte">
            <div class="form-group">
                <label>URL API Server TTE</label>
                <input type="text" name="tte_api" class="form-control input-sm" value="{{ setting('tte_api') }}" @disabled(!$kades)>
            </div>
            <div class="form-group">
                <label>Username Login TTE</label>
                <input type="text" name="tte_username" class="form-control input-sm" value="{{ setting('tte_username') }}" @disabled(!$kades)>
            </div>
            <div class="form-group">
                <label>Password Login TTE</label>
                <input type="password" name="tte_password" class="form-control input-sm" @disabled(!$kades)>
                @if (setting('tte_password'))
                    <p id="info-tte-password" class="help-block small text-red">Kosongkan jika tidak ingin mengubah
                        Password Login TTE.</p>
                @endif
            </div>
            <div class="form-group">
                <label>Visual TTE</label>
                <div class="input-group col-xs-12 col-sm-8">
                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="padding: 0px;">
                        <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('visual_tte') == '1') @disabled(!$kades)">
                            <input type="radio" name="visual_tte" class="form-check-input" value="1" autocomplete="off" @checked(setting('visual_tte') == '1')>
                            Ya</label>
                        <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('visual_tte') == '0') @disabled(!$kades)">
                            <input type="radio" name="visual_tte" class="form-check-input" value="0" autocomplete="off" @checked(setting('visual_tte') == '0')>
                            Tidak
                        </label>
                    </div>
                </div>
            </div>
            <div id="visual-tte-form">
                <div class="form-group">
                    <label>Visual</label>
                    <div class="input-group input-group-sm col-md-3 col-sm-12">
                        <img class="img-responsive" src="{{ setting('visual_tte_gambar') == null ? asset('assets/images/bsre.png?v', false) : base_url(setting('visual_tte_gambar')) }}" alt="TTE">
                    </div>
                    <div class="input-group input-group-sm col-md-3 col-sm-12">
                        <input type="text" class="form-control" id="file_path">
                        <input type="file" id="file" class="hidden" name="visual_tte_gambar" accept=".jpg,.jpeg,.png">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                            <span class="input-group-addon input-sm">Tinggi</span>
                            <input type="number" class="form-control input-sm required" name="visual_tte_height" style="text-align:right;" value="{{ setting('visual_tte_height') }}">
                            <span class="input-group-addon input-sm">px</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                            <span class="input-group-addon input-sm">Lebar</span>
                            <input type="number" class="form-control input-sm required" name="visual_tte_weight" style="text-align:right;" value="{{ setting('visual_tte_weight') }}">
                            <span class="input-group-addon input-sm">px</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="ttd-scan" @if(setting('tte') == '1') style="display: none;" @endif>
            <div class="form-group">
                <label>TTD Scan</label>
                <div class="input-group col-xs-12 col-sm-8">
                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="padding: 0px;">
                        <label
                            class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('ttd_scan') == '1') @disabled(!$kades)">
                            <input type="radio" name="ttd_scan" class="form-check-input" value="1" autocomplete="off"
                                @checked(setting('ttd_scan') == '1')>
                            Ya</label>
                        <label
                            class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('ttd_scan') == '0' || setting('ttd_scan') == null) @disabled(!$kades)">
                            <input type="radio" name="ttd_scan" class="form-check-input" value="0" autocomplete="off"
                                @checked(setting('ttd_scan') == '0' || setting('ttd_scan') == null)>
                            Tidak
                        </label>
                    </div>
                </div>
            </div>
            <div id="visual-ttd-form" @if(setting('ttd_scan') == '0' || setting('ttd_scan') == null) style="display: none;" @endif>
                <div class="form-group">
                    <label>Visual</label>
                    <div class="input-group input-group-sm col-md-3 col-sm-12">
                        <img class="img-responsive"
                            src="{{ setting('visual_ttd_scan') == null ? asset('assets/images/ttd_scan.png?v', false) : base_url(setting('visual_ttd_scan')) }}"
                            alt="TTD Scan">
                    </div>
                    <div class="input-group input-group-sm col-md-3 col-sm-12">
                        <input type="text" class="form-control" id="file_path1">
                        <input type="file" id="file1" class="hidden" name="visual_ttd_scan" accept=".jpg,.jpeg,.png">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat" id="file_browser1"><i
                                    class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                            <span class="input-group-addon input-sm">Tinggi</span>
                            <input type="number" class="form-control input-sm required" name="visual_ttd_height"
                                style="text-align:right;" value="{{ setting('visual_ttd_height') }}">
                            <span class="input-group-addon input-sm">px</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                            <span class="input-group-addon input-sm">Lebar</span>
                            <input type="number" class="form-control input-sm required" name="visual_ttd_width"
                                style="text-align:right;" value="{{ setting('visual_ttd_width') }}">
                            <span class="input-group-addon input-sm">px</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
