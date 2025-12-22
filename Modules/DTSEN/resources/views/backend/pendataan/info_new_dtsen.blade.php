Versi Formulir DTSEN saat ini : <b id="versi">
    {{ \Modules\DTSEN\App\Enums\DtsenEnum::VERSION_LIST[\Modules\DTSEN\App\Enums\DtsenEnum::VERSION_CODE] }}
</b>
<br>
<div id="info_versi_dtsen">

</div>
<script>
    setTimeout(() => {
        $('#info_versi_dtsen').load("<?= ci_route('dtsen/pendataan/loadRecentInfo') ?>");
    }, 500);
</script>
