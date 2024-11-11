<style type="text/css">
    .info-box {
        border: 1px solid;
        border-radius: 10px;
        background-color: #fff;
        border-color: #d8dbe0;
    }

    .info-box-icon {
        border-radius: 10px;
        width: 120px;
        height: 120px;
    }

    .info-box-content {
        padding: 5px 10px;
        margin-left: 130px;
        height: 120px;
    }

    .info-box-icon {
        padding-top: 0;
        background: white;
    }

    .info-box-text {
        text-transform: capitalize;
    }
    
    .sdgs-logo {
        border-radius: 10px;
        width: 120px;
        height: 120px;
    }

    .total-bumds {
        font-size: 32px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: left;
        color: #232b39;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .desc-bumds {
        margin-top: 8px;
        font-size: 21px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: left;
        color: #5a677d;
    }
</style>
<div class="single_page_area">
    <h2 class="post_titile">SDGs {{ ucwords(setting('sebutan_desa')) }}</h2>
    <div class="box-body">
        <div id="errorMsg" style="display: none;">
            <div class="alert alert-danger">
                <p class="py-3" id="errorText"></p>
            </div>
        </div>
        <div class="row" id="sdgs_desa" style="display: none;">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="info-box" style="display: flex;justify-content: center;">
                    <span class="info-box-number total-bumds" style="text-align: center;" id="average">
                        <span class="info-box-text desc-bumds" style="text-align: center;">Skor SDGs {{ ucwords(setting('sebutan_desa')) }}</span>
                    </span>
                </div>
            </div>

            <div id="sdgsData">

            </div>
        </div>
    </div>
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
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon">
                                <img class="sdgs-logo" src="${image}" alt="${data_sdgs[i].image}">
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-number total-bumds">${data_sdgs[i].score}
                                    <span class="info-box-text desc-bumds">Nilai</span>
                                </span>
                            </div>
                        </div>
                    </div>
                `)
            }
        }
    });
});
</script>
@endpush