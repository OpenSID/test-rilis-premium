<header class="main-header">
  <a href="<?php echo e(route('/')); ?>" target="_blank" class="logo">
    <span class="logo-mini"><b>SID</b></span>
    <span class="logo-lg"><b>OpenSID</b></span>
  </a>

  <nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        <?php if($notif['langganan']): ?>
            <li>
                <a href="<?php echo e(route('pelanggan')); ?>">
                    <span><i class="fa <?php echo e($notif['langganan']['ikon']); ?> fa-lg" title="Status Langganan <?php echo e($notif['langganan']['masa']); ?> hari" style="color: <?php echo e($notif['langganan']['warna']); ?>"></i>&nbsp;</span>
                    <?php if($notif['langganan']['status'] > 2): ?>
                        <span class="badge" id="b_langganan"></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endif; ?>

        <?php if(can('b', 'permohonan_surat_admin')): ?>
          <li>
            <a href="<?php echo e(route('permohonan_surat_admin.clear')); ?>">
              <span><i class="fa fa-print fa-lg" title="Permohonan Surat"></i>&nbsp;</span>
              <?php if($notif['surat']): ?>
                <span class="badge" id="b_permohonan_surat"><?php echo e($notif['surat']); ?></span>
              <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>

        <?php if(can('b', 'komentar')): ?>
          <li>
            <a href="<?php echo e(route('komentar')); ?>">
              <span><i class="fa fa-commenting-o fa-lg" title="Komentar"></i>&nbsp;</span>
              <?php if($notif['komentar']): ?>
                <span class="badge" id="b_komentar"><?php echo e($notif['komentar']); ?></span>
              <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>

        <?php if(can('b', 'mailbox')): ?>
          <li>
            <a href="<?php echo e(route('mailbox')); ?>">
              <span><i class="fa fa-envelope-o fa-lg" title="Pesan Masuk"></i>&nbsp;</span>
              <?php if($notif['inbox']): ?>
                <span class="badge" id="b_inbox"><?php echo e($notif['inbox']); ?></span>
              <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>
        
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo e(AmbilFoto($auth->foto)); ?>" class="user-image" alt="User Image"/>
            <span class="hidden-xs"><?php echo e($auth->nama); ?></span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              <img src="<?php echo e(AmbilFoto($auth->foto)); ?>" class="img-circle" alt="User Image"/>
              <p>
                <small>Anda Masuk Sebagai</small>
                <?php echo e($auth->nama); ?>

              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="#"  class="btn bg-maroon btn-sm" data-remote="false" data-toggle="modal" data-target="#profil_pengguna">Profil</a>
              </div>
              <div class="pull-right">
                <a href="<?php echo e(route('siteman.logout')); ?>" class="btn bg-maroon btn-sm">Keluar</a>
              </div>
            </li>
          </ul>

          <?php if($controller == 'pelanggan' && can('u', $controller)): ?>
          <li>
            <a href="#" data-remote="false" data-toggle="modal" data-target="#pengaturan">
              <span><i class="fa fa-gear"></i>&nbsp;</span>
            </a>
          </li>
          <?php endif; ?>

        </li>
      </ul>
    </div>
  </nav>
</header><?php /**PATH D:\kerjoan\web\opendesa\premium\resources\views/admin/layouts/partials/header.blade.php ENDPATH**/ ?>