<?php if (ci_session('success')): ?>
<div id="notifikasi" class="callout callout-success">
    <h4><i class="icon fa fa-check"></i> Berhasil</h4>
    <p><?= ci_session('success') ?></p>
</div>
<?php endif; ?>

<?php if (ci_session('error')): ?>
<div id="notifikasi" class="callout callout-danger">
    <h4><i class="icon fa fa-alert"></i> Gagal</h4>
    <p><?= ci_session('error') ?></p>
</div>
<?php endif; ?>