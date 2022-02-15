<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo e($setting->admin_title . ' ' . ucwords($setting->sebutan_desa . ' ' . ($desa['nama_desa'] ?? '')) . get_dynamic_title_page_from_path()); ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php if(is_file(LOKASI_LOGO_DESA . 'favicon.ico')): ?>
  <link rel="shortcut icon" href="<?php echo e(base_url(LOKASI_LOGO_DESA . '/favicon.ico')); ?>"/>
  <?php else: ?>
  <link rel="shortcut icon" href="<?php echo e(base_url('favicon.ico')); ?>"/>
  <?php endif; ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo e(base_url('rss.xml')); ?>"/>
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/bootstrap.min.css')); ?>"/>
  <!-- Jquery UI -->
  <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/jquery-ui.min.css')); ?>"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/font-awesome.min.css')); ?>"/>
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/ionicons.min.css')); ?>"/>
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/select2.min.css')); ?>"/>
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('css/AdminLTE.min.css')); ?>"/>
  <!-- AdminLTE Skins. -->
  <link rel="stylesheet" href="<?php echo e(asset('css/skins/_all-skins.min.css')); ?>"/>
  <!-- AdminLTE Modifikasi -->
  <link rel="stylesheet" href="<?php echo e(asset('css/admin-style.css')); ?>"/>

  <?php if(is_file(DESAPATH . 'css/siteman.css')): ?>
  <!-- Untuk ubahan style desa -->
  <link rel="stylesheet" href="<?php echo e(base_url(DESAPATH . '/css/siteman.css')); ?>"/>
  <?php endif; ?>

  <!-- Diperlukan untuk global automatic base_url oleh external js file -->
  <script>
      var BASE_URL = "<?php echo e(base_url()); ?>";
      var SITE_URL = "<?php echo e(site_url()); ?>";
  </script>
  
  <?php if(config_item('csrf_protection')): ?>
  <!-- CSRF Token -->
  <script type="text/javascript">
    var csrfParam = "<?php echo e($token); ?>";
    var getCsrfToken = () => document.cookie.match(new RegExp(csrfParam +'=(\\w+)'))[1];
  </script>
  <script src="<?php echo e(asset('js/anti-csrf.js')); ?>"></script>
  <?php endif; ?>
  <?php echo $__env->yieldPushContent('css'); ?>
</head>
<body id="sidebar_collapse" class="<?php echo e($setting->warna_tema_admin); ?> fixed sidebar-mini">
  <div class="wrapper">

    <?php echo $__env->make('admin.layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('admin.layouts.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <div class="content-wrapper">
      <section class="content-header">
        <?php echo $__env->yieldContent('title'); ?>

        <?php echo $__env->make('admin.layouts.components.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </section>

      <section class="content">
        <?php echo $__env->yieldContent('content'); ?>
      </section>
    </div>

    <?php echo $__env->make('admin.layouts.components.pengaturan', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->make('admin.profil.pengaturan_pengguna', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('admin.layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  </div>

  <!-- jQuery 3 -->
  <script src="<?php echo e(asset('bootstrap/js/jquery.min.js')); ?>"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="<?php echo e(asset('bootstrap/js/bootstrap.min.js')); ?>"></script>
  <!-- Select2 -->
  <script src="<?php echo e(asset('bootstrap/js/select2.full.min.js')); ?>"></script>
  <!-- Slimscroll -->
  <script src="<?php echo e(asset('bootstrap/js/jquery.slimscroll.min.js')); ?>"></script>
  <!-- FastClick -->
  <script src="<?php echo e(asset('bootstrap/js/fastclick.js')); ?>"></script>
  <!-- AdminLTE -->
  <script src="<?php echo e(asset('js/adminlte.min.js')); ?>"></script>
  <script src="<?php echo e(asset('js/validasi.js')); ?>"></script>
  <script src="<?php echo e(asset('js/jquery.validate.min.js')); ?>"></script>
  <script src="<?php echo e(asset('js/localization/messages_id.js')); ?>"></script>
  <!-- Script -->
  <script src="<?php echo e(asset('js/script.js')); ?>"></script>
  <!-- Admin -->
  <script src="<?php echo e(asset('js/admin.js')); ?>"></script>
  <?php if(config_item('demo_mode')): ?>
  <!-- Website Demo -->
  <script src="<?php echo e(asset('js/demo.js')); ?>"></script>
  <?php endif; ?>
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\kerjoan\web\opendesa\premium\resources\views/admin/layouts/index.blade.php ENDPATH**/ ?>