<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?php echo e(gambar_desa($desa['logo'])); ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <strong><?php echo e(ucwords($setting->sebutan_desa . ' ' . $desa['nama_desa'])); ?></strong>
        </br>
        
        <?php
          $seb_kec = $setting->sebutan_kecamatan;
          $nam_kec = $desa['nama_kecamatan'];
          $seb_kab = $setting->sebutan_kabupaten;
          $nam_kab = $desa['nama_kabupaten'];
        ?>

        <?php if(strlen($nam_kec) <= 12 && strlen($nam_kab) <= 12): ?>
          <?php echo e(ucwords($seb_kec . ' ' . $nam_kec)); ?>

          </br>
          <?php echo e(ucwords($seb_kab . ' ' . $nam_kab)); ?>

        <?php else: ?>
          <?php echo e(ucwords(substr($seb_kec, 0, 3) . '. ' . $nam_kec)); ?>

          </br>
          <?php echo e(ucwords(substr($seb_kab, 0, 3) . '. ' . $nam_kab)); ?>

        <?php endif; ?>
        
      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MENU UTAMA</li>

      <?php $__currentLoopData = $modul; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php if(count($mod['submodul']) == 0): ?>
        <li class="<?php echo e(jecho($modul_ini, $mod['id'], 'active')); ?>">
          <a href="<?php echo e(route($mod['url'])); ?>">
            <i class="fa <?php echo e($mod['ikon']); ?> <?php echo e(jecho($modul_ini, $mod['id'], 'text-aqua')); ?>"></i><span><?php echo e($mod['modul']); ?></span>
            <span class="pull-right-container"></span>
          </a>
        </li>
        <?php else: ?> 
        <li class="treeview <?php echo e(jecho($modul_ini, $mod['id'], 'active')); ?>">
          <a href="<?php echo e(route($mod['url'])); ?>">
            <i class="fa <?php echo e($mod['ikon']); ?> <?php echo e(jecho($modul_ini, $mod['id'], 'text-aqua')); ?>"></i><span><?php echo e($mod['modul']); ?></span>
            <span class="pull-right-container"><i class='fa fa-angle-left pull-right'></i></span>
          </a>
          <ul class="treeview-menu <?php echo e(jecho($modul_ini, $mod['id'], 'active')); ?>">

            <?php $__currentLoopData = $mod['submodul']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="<?php echo e(jecho($sub_modul_ini, $submod['id'], 'active')); ?>">
              <a href="<?php echo e(route($submod['url'])); ?>">
                <i class="fa <?php echo e(($submod['ikon'] != null) ? $submod['ikon'] : 'fa-circle-o'); ?> <?php echo e(jecho($sub_modul_ini, $submod['id'], 'text-red')); ?>"></i>
                <?php echo e($submod['modul']); ?>

              </a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          </ul>
        </li>
        <?php endif; ?>

      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </section>
</aside><?php /**PATH D:\kerjoan\web\opendesa\premium\resources\views/admin/layouts/partials/sidebar.blade.php ENDPATH**/ ?>