<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kehadiran</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	 
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

		<!-- jQuery 3 -->
		<script src="<?php echo e(asset('bootstrap/js/jquery.min.js')); ?>"></script>
		<!-- Bootstrap 3.3.7 -->
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

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
     
</head>

<body class="hold-transition login-page">
    <div class="" style="margin: 0%; padding-right: 15px; padding-left: 15px;">
        
			 
					<?php echo $__env->yieldContent('content'); ?>
			 

        

    </div>


    
		<script src="<?php echo e(asset('bootstrap/js/bootstrap.min.js')); ?>"></script>
		<!-- Select2 -->
		<script src="<?php echo e(asset('bootstrap/js/select2.full.min.js')); ?>"></script>
		<!-- Slimscroll -->
		<script src="<?php echo e(asset('bootstrap/js/jquery.slimscroll.min.js')); ?>"></script>
		<!-- FastClick -->
		<script src="<?php echo e(asset('bootstrap/js/fastclick.js')); ?>"></script>
		<!-- datatables -->
		<script src="<?php echo e(asset('bootstrap/js/jquery.dataTables.min.js')); ?>"></script>
		<script src="<?php echo e(asset('bootstrap/js/dataTables.bootstrap.min.js')); ?>">
		<!-- AdminLTE -->
		<script src="<?php echo e(asset('js/adminlte.min.js')); ?>"></script>
		<script src="<?php echo e(asset('js/validasi.js')); ?>"></script>
		<script src="<?php echo e(asset('js/jquery.validate.min.js')); ?>"></script>
		<script src="<?php echo e(asset('js/localization/messages_id.js')); ?>"></script>
		<!-- Script -->
		<script src="<?php echo e(asset('js/script.js')); ?>"></script>
		<!-- Admin -->
		<script src="<?php echo e(asset('js/admin.js')); ?>"></script>
		<?php echo $__env->yieldPushContent('scripts'); ?>
    
</body>

</html>
<?php /**PATH D:\kerjoan\web\opendesa\premium\resources\views/kehadiran/layouts/index.blade.php ENDPATH**/ ?>