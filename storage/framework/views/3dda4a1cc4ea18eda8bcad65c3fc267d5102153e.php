<div class="modal fade" id="profil_pengguna" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"> Pengaturan Pengguna</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-3">
            <div class="box box-primary">
              <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="<?php echo e(AmbilFoto($auth->foto)); ?>" alt="Foto">
              </div>
            </div>
            <?php if($auth->email_verified_at === null): ?>
              <?php echo form_open(route('user_setting.kirim_verifikasi')); ?>

                <span class="input-group-btn">
                  <button type="submit" class="btn btn-sm btn-warning btn-block"><i class="fa fa-share-square"></i> Verifikasi Email</button>
                </span>
              </form>
            <?php endif; ?>
          </div>
          <div class="col-sm-9">
            <div class="box box-danger">
              <?php echo form_open_multipart(route('update_a'), 'id="validasi"'); ?>

                <div class="box-body">
                  <div class="form-group">
                    <label for="tgl_peristiwa">Username</label>
                    <input class="form-control input-sm" type="text" value="<?php echo e($auth->username); ?>" disabled></input>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control input-sm" type="text" value="<?php echo e($auth->email); ?>" readonly></input>
                  </div>
                  <div class="form-group">
                    <label for="catatan">Nama Lengkap</label>
                    <input class="form-control input-sm" type="text" name="nama" value="<?php echo e($auth->nama); ?>"/>
                  </div>
                  <div class="form-group">
                    <label for="catatan">Kata Sandi Lama</label>
                    <input class="form-control input-sm required" type="password" name="pass_lama"/>
                  </div>
                  <div class="form-group">
                    <label for="catatan">Kata Sandi Baru</label>
                    <input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru" name="pass_baru"/>
                  </div>
                  <div class="form-group">
                    <label for="catatan">Kata Sandi Baru (Ulangi)</label>
                    <input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru1" name="pass_baru1"/>
                  </div>
                  <div class="form-group">
                    <label for="catatan">Ganti Foto</label>
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control" id="file_path" name="foto">
                      <input type="file" class="hidden" id="file" name="foto">
                      <input type="hidden" name="old_foto" value="<?php echo e($auth->foto); ?>">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                  <button id="btnSubmit" type="submit" class="btn btn-social btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->startPush('css'); ?>
<script>
  $('document').ready(function() {
    setTimeout(function() {
      $('#pass_baru1').rules('add', {
        equalTo: '#pass_baru'
      })
    }, 500);

    $('#file_browser').click(function(e) {
      e.preventDefault();
      $('#file').click();
    });

    $('#file').change(function() {
      $('#file_path').val($(this).val());
    });

    $('#file_path').click(function() {
      $('#file_browser').click();
    });
  });
</script>
<?php $__env->stopPush(); ?><?php /**PATH D:\kerjoan\web\opendesa\premium\resources\views/admin/profil/pengaturan_pengguna.blade.php ENDPATH**/ ?>