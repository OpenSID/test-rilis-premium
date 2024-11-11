<nav role="navigation" aria-label="navigation" class="breadcrumb">
    <ol>
        <li><a href="{{ site_url('/') }}">Beranda</a></li>
        <li aria-current="page">SDGs {{ ucwords(setting('sebutan_desa')) }}</li>
    </ol>
</nav>

<h1 class="text-h2">SDGs {{ ucwords(setting('sebutan_desa')) }}</h1>

<div id="errorMsg" style="display: none;">
    <div class="alert alert-danger">
        <p class="py-3" id="errorText"></p>
    </div>
</div>

<div class="space-y-12 text-center" id="sdgs_desa" style="display: none;">
    <span class="text-h2" id="average"></span>
    </br>
    <span class="text-h6">Skor SDGs {{ ucwords(setting('sebutan_desa')) }}</span>
</div>

<div id="sdgsData" class="grid grid-cols-2 lg:grid-cols-4 gap-5 py-5">
</div>

@push('scripts')    
<script type="text/javascript">
$(document).ready(function() {
    $.ajax({
        url: "{{ site_url('api/v1/sdgs') }}",
        method: "GET",
        loading: true,
        success: function(data) {
            if(data['error_msg']) {
                $('#errorMsg').show();
                $('#sdgs_desa').hide();
                $('#errorText').html(data['error_msg']);
                return;
            }
            
            $('#sdgs_desa').show();
            var data_sdgs = data['data'];
            var total_desa = data['total_desa'];
            var average = data['average'];
            var path = BASE_URL + 'assets/images/sdgs/';

            $('#average').text(average);

            for (let i = 0; i < data_sdgs.length; i++) {
                var image = path + '/' + data_sdgs[i].image;
                
                $('#sdgsData').append(`
                    <div class="space-y-3">
                        <img class="w-full object-cover object-center" src="${image}" alt="${data_sdgs[i].image}" />

                        <div class="space-y-1 text-sm text-center z-10">
                            <span class="text-h6">NILAI</span>
                            <span class="block">${data_sdgs[i].score}</span>
                        </div>
                    </div>
                `)
            }
        }
    });
});
</script>
@endpush
