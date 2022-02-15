@extends('kehadiran.layouts.index')

@section('content')
<div class="row " style="background-color: #ffffff; height: 100vh; ">
    <div class="col-xm-12 vertical-align" style="padding: 0px;height: 100vh;">
        <div class="row ">
            <div class="box-body box-line">
                <img class="img-circle " src="{{ AmbilFoto($session['foto'], '', $session['sex']) }} " alt="Foto" width="150px">
            </div>
            <div class="col-xm-12">
                <h1 class="text-center">{{ $session['nama'] }}</h1>
                 <h4 class="text-center">{{ $session['jabatan'] }}</h4>

                 <!-- Rounded switch -->
            <label class="switch">
              <input type="checkbox">
              <span class="slider round"></span>
            </label>
                        </div>

        </div>
       
    </div>
</div>

<script type="text/javascript">
    $(function() {
        function showTime(){
            var date = new Date();
            var h = date.getHours(); // 0 - 23
            var m = date.getMinutes(); // 0 - 59
            var s = date.getSeconds(); // 0 - 59
            var session = "AM";
            
            if(h == 0){
                h = 12;
            }
            
            if(h > 12){
                h = h - 12;
                session = "PM";
            }
            
            h = (h < 10) ? "0" + h : h;
            m = (m < 10) ? "0" + m : m;
            s = (s < 10) ? "0" + s : s;
            
            var time = h + ":" + m + ":" + s + " " + session;
            document.getElementById("MyClockDisplay").innerText = time;
            document.getElementById("MyClockDisplay").textContent = time;
            
            setTimeout(showTime, 1000);
            
        }
        showTime();
    });
</script>

<style type="text/css">
    .vertical-align {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 30%;
    }

    /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
@endsection