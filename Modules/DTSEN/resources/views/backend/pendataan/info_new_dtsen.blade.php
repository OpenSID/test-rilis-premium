Versi Formulir {{ setting('sebutan_dtks') }} saat ini : <b id="versi">
    {{ \Modules\DTSEN\App\Enums\DtsenEnum::VERSION_LIST[\Modules\DTSEN\App\Enums\DtsenEnum::VERSION_CODE] }}
</b>
<br>
<div id="info_versi_dtks">

</div>
<script>
    setTimeout(() => {
        $('#info_versi_dtks').load("<?= ci_route('dtsen/pendataan/loadRecentInfo') ?>");
    }, 500);
</script>
