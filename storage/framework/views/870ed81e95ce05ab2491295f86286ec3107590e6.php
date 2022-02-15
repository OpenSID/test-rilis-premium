<?php if($controller == 'pelanggan' && can('u', $controller)): ?>
<div class="modal fade" id="pengaturan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"> Pengaturan <?php echo e(ucwords($controller)); ?></h4>
      </div>

      <?php echo $__env->make('admin.layouts.components.form_pengaturan', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>
  </div>
</div>
<?php endif; ?><?php /**PATH D:\kerjoan\web\opendesa\premium\resources\views/admin/layouts/components/pengaturan.blade.php ENDPATH**/ ?>