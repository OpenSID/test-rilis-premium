

<?php $__env->startSection('content'); ?>
    <div class="row" style="background-color: #ffffff">
        <div class="col-sm-7 hidden-xs " style="padding: 0px">
            <div class="form-left vertical-align">
                <div class="row text-center">
                    <div class="col-xm-12 col-sm-12">
                        <img src="<?php echo e(gambar_desa($header['logo'])); ?>" alt="Lambang Desa" class="img-responsive center-block" />
                    </div>
                    <div class="col-xm-12 col-sm-12">
                        <div class="col-sm-1"></div>
                        <div class="text-ceter col-sm-10">
                            <h1>Aplikasi Rekam Kehadiran Perangkat Desa</h1>
                        </div>
                    </div>
                    <div class="col-xm-12 col-sm-2"></div>
                </div>
                <div class="callout ">
                    <h4> <?php echo e(ucwords($setting->sebutan_desa).' '.$header['nama_desa']); ?>   </h4>
                    <p> <?php echo e(ucwords($setting->sebutan_kecamatan).' '.$header['nama_kecamatan']); ?> </p>
                </div>
            </div>
        </div>
        <div class="col-sm-5 col-xm-5">
            <div class="login-box">
                <div class="login-box-body">
                    <p class="login-box-msg">Masuk Ke Aplikasi</p>
                    <?php echo form_open_multipart($form_action, 'class="form-horizontal" id="validasi"'); ?>

                        <div class="form-group has-feedback">
                            <input type="text" name="username" class="form-control" placeholder="Username / NIK">
                            <span class="glyphicon glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-success btn-block btn-flat">Masuk</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .login-box,
        .register-box {
            width: auto;
            min-width: 200px;
            margin: 10% auto;
        }

        h1 {
            padding: 10px 0;
            font-weight: bold;
            letter-spacing: 1px;
            color: #fff;
            -webkit-text-stroke: 0;
            text-shadow: 2px 0px 10px rgb(27 27 27 / 10%);
            font-weight: bold;
            font-size: calc(18px + 6 * ((100vw - 320px) / 680));
        }

        .form-left {
            height: 100vh;
            background-image: url('../../assets/images/kehadiran/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .vertical-align {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 30%;
        }

        .callout {
            position: absolute;
            width: 100%;
            bottom: 5%;
            border-radius: 0px;
            border-left: 5px solid #ffe000;
            margin-left: 20px;
						color: #fff;
						font-weight: bold;
						padding: 5px 20px 5px 15px;
        }

    </style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('kehadiran.layouts.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\kerjoan\web\opendesa\premium\resources\views/kehadiran/masuk/index.blade.php ENDPATH**/ ?>