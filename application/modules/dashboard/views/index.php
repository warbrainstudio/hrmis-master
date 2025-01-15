<style type="text/css">
    .img-home {
        margin-top: 1rem;
        width: <?= isset($app->dashboard_image_width) ? $app->dashboard_image_width . '%' : '100%' ?>;
        max-height: <?= isset($app->dashboard_image_max_height) ? $app->dashboard_image_max_height . 'px' : '450px' ?>;
        object-fit: <?= isset($app->dashboard_image_object_fit) ? $app->dashboard_image_object_fit : 'cover' ?>;
        object-position: <?= isset($app->dashboard_image_object_position) ? $app->dashboard_image_object_position : 'center' ?>;
        box-shadow: <?= (isset($app->dashboard_image_box_shadow) && $app->dashboard_image_box_shadow === '1') ? '0 1px 2px rgba(0, 0, 0, 0.1)' : 'none' ?>;
    }

    .text-small {
        font-size: 1rem;
        display: block;
        color: rgba(255, 255, 255, .8);
        font-weight: 600;
    }

    .text-xss {
        font-size: .735rem !important;
    }

    .text-left {
        text-align: left;
    }

    .flot-chart--xs {
        color: rgba(255, 255, 255, 0.6);
        font-size: 1.2rem;
        text-align: center;
        text-shadow: 0px 1px rgba(1, 1, 1, 0.1);
        font-weight: 500;
    }

    .stats__info h2 {
        font-size: 1.1rem;
        font-weight: 300;
    }

    .opacity-50 {
        opacity: 50%;
    }

    @media only screen and (max-width: 768px) {
        .img-home {
            margin-top: 1rem;
            width: 100%;
            max-height: <?= isset($app->dashboard_image_max_height) ? $app->dashboard_image_max_height . 'px' : '450px' ?>;
            object-fit: <?= isset($app->dashboard_image_object_fit) ? $app->dashboard_image_object_fit : 'cover' ?>;
            object-position: <?= isset($app->dashboard_image_object_position) ? $app->dashboard_image_object_position : 'center' ?>;
            box-shadow: <?= (isset($app->dashboard_image_box_shadow) && $app->dashboard_image_box_shadow === '1') ? '0 1px 2px rgba(0, 0, 0, 0.1)' : 'none' ?>;
        }
    }
</style>

<!-- DASHBOARD IMAGE -->
<div class="card mb-4" style="display: <?= (isset($app->dashboard_image_visibility)) ? $app->dashboard_image_visibility : 'none' ?>">
    <div class="card-body">
        <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
        <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>
        <?php if (isset($app->dashboard_image_source) && !empty($app->dashboard_image_source)) : ?>
            <div class="row">
                <div class="col">
                    <center>
                        <img src="<?php echo base_url($app->dashboard_image_source) ?>" class="img-home" />
                    </center>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- CARD COLORED -->
<div class="row">
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card border-primary text-dark h-100">
            <div id="count-total_pegawai_aktif-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-body bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-dark-75 small">Total Pegawai Aktif</div>
                        <div class="text-lg fw-bold" id="count-total_pegawai_aktif">-</div>
                    </div>
                    <i class="feather-xl text-dark-50 opacity-50" data-feather="users"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                Semua Unit
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card border-warning text-dark h-100">
            <div id="count-total_pegawai_habis_kontrak-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-body bg-warning text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-dark-75 small">Pegawai Akan Habis Kontrak</div>
                        <a href="<?php echo base_url("employeeexpired") ?>" style="color: white;"><div class="text-lg fw-bold" id="count-total_pegawai_habis_kontrak">-</div></a>
                    </div>
                    <i class="feather-xl text-dark-50 opacity-50" data-feather="archive"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                Semua Unit
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card border-danger text-dark h-100">
            <div id="count-total_demosi_mutasi-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-body bg-danger text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-dark-75 small">Total Demosi / Mutasi</div>
                        <div class="text-lg fw-bold" id="count-total_demosi_mutasi">-</div>
                    </div>
                    <i class="feather-xl text-dark-50 opacity-50" data-feather="git-pull-request"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                Semua Unit Tahun <?= date('Y') ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card border-success text-dark h-100">
            <div id="count-total_diklat-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-body bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-dark-75 small">Total Diklat</div>
                        <div class="text-lg fw-bold" id="count-total_diklat">-</div>
                    </div>
                    <i class="feather-xl text-dark-50 opacity-50" data-feather="award"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                Semua Unit Tahun <?= date('Y') ?>
            </div>
        </div>
    </div>
</div>

<!-- CARD STATISTIC -->
<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100 border-success">
            <div id="statistic-tingkat_pendidikan-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-header text-dark">
                Tingkat Pendidikan
                <div class="float-right">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check statistic-tingkat_pendidikan-type" name="statistic-tingkat_pendidikan-type" id="statistic-tingkat_pendidikan-all" autocomplete="off" data-type="all" checked>
                        <label class="btn btn-outline-dark btn-sm" for="statistic-tingkat_pendidikan-all">Semua</label>
                        <input type="radio" class="btn-check statistic-tingkat_pendidikan-type" name="statistic-tingkat_pendidikan-type" id="statistic-tingkat_pendidikan-hk" autocomplete="off" data-type="hk">
                        <label class="btn btn-outline-dark btn-sm" for="statistic-tingkat_pendidikan-hk">HK</label>
                        <input type="radio" class="btn-check statistic-tingkat_pendidikan-type" name="statistic-tingkat_pendidikan-type" id="statistic-tingkat_pendidikan-mk" autocomplete="off" data-type="mk">
                        <label class="btn btn-outline-dark btn-sm" for="statistic-tingkat_pendidikan-mk">MK</label>
                    </div>
                    <div class="btn-group" role="group">
                        <div class="dropdown">
                            <a class="btn btn-dark btn-sm dropdown-toggle" href="#" role="button" id="statistic-tingkat_pendidikan-download" data-bs-toggle="dropdown" aria-expanded="false"><i class="zmdi zmdi-download"></i></a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="statistic-tingkat_pendidikan-download">
                                <li><a class="dropdown-item under-development-msg" href="#">Unduh Excel</a></li>
                                <li><a class="dropdown-item under-development-msg" href="#">Cetak</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2" id="statistic-tingkat_pendidikan" style="min-height: 100px;">
                <!-- Content will be load from ajax request -->
            </div>
        </div>
    </div>
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100 border-info">
            <div id="statistic-kategori_pegawai-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-header text-dark">
                Kategori Pegawai
                <div class="float-right">
                    <div class="btn-group" role="group">
                        <div class="dropdown">
                            <a class="btn btn-dark btn-sm dropdown-toggle" href="#" role="button" id="statistic-tingkat_pendidikan-download" data-bs-toggle="dropdown" aria-expanded="false"><i class="zmdi zmdi-download"></i></a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="statistic-tingkat_pendidikan-download">
                                <li><a class="dropdown-item under-development-msg" href="#">Unduh Excel</a></li>
                                <li><a class="dropdown-item under-development-msg" href="#">Cetak</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2" id="statistic-kategori_pegawai" style="min-height: 100px;">
                <!-- Content will be load from ajax request -->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100 border-warning">
            <div id="statistic-usia_pegawai-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-header text-dark">
                Usia Pegawai
                <div class="float-right">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check statistic-usia_pegawai-type" name="statistic-usia_pegawai-type" id="statistic-usia_pegawai-all" autocomplete="off" data-type="all" checked>
                        <label class="btn btn-outline-dark btn-sm" for="statistic-usia_pegawai-all">Semua</label>
                        <input type="radio" class="btn-check statistic-usia_pegawai-type" name="statistic-usia_pegawai-type" id="statistic-usia_pegawai-hk" autocomplete="off" data-type="hk">
                        <label class="btn btn-outline-dark btn-sm" for="statistic-usia_pegawai-hk">HK</label>
                        <input type="radio" class="btn-check statistic-usia_pegawai-type" name="statistic-usia_pegawai-type" id="statistic-usia_pegawai-mk" autocomplete="off" data-type="mk">
                        <label class="btn btn-outline-dark btn-sm" for="statistic-usia_pegawai-mk">MK</label>
                    </div>
                    <div class="btn-group" role="group">
                        <div class="dropdown">
                            <a class="btn btn-dark btn-sm dropdown-toggle" href="#" role="button" id="statistic-tingkat_pendidikan-download" data-bs-toggle="dropdown" aria-expanded="false"><i class="zmdi zmdi-download"></i></a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="statistic-tingkat_pendidikan-download">
                                <li><a class="dropdown-item under-development-msg" href="#">Unduh Excel</a></li>
                                <li><a class="dropdown-item under-development-msg" href="#">Cetak</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2" id="statistic-usia_pegawai" style="min-height: 100px;">
                <!-- Content will be load from ajax request -->
            </div>
        </div>
    </div>
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100 border-danger">
            <div id="statistic-jenis_kelamin-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-header text-dark">
                Jenis Kelamin
                <div class="float-right">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check statistic-jenis_kelamin-type" name="statistic-jenis_kelamin-type" id="statistic-jenis_kelamin-all" autocomplete="off" data-type="all" checked>
                        <label class="btn btn-outline-dark btn-sm" for="statistic-jenis_kelamin-all">Semua</label>
                        <input type="radio" class="btn-check statistic-jenis_kelamin-type" name="statistic-jenis_kelamin-type" id="statistic-jenis_kelamin-hk" autocomplete="off" data-type="hk">
                        <label class="btn btn-outline-dark btn-sm" for="statistic-jenis_kelamin-hk">HK</label>
                        <input type="radio" class="btn-check statistic-jenis_kelamin-type" name="statistic-jenis_kelamin-type" id="statistic-jenis_kelamin-mk" autocomplete="off" data-type="mk">
                        <label class="btn btn-outline-dark btn-sm" for="statistic-jenis_kelamin-mk">MK</label>
                    </div>
                    <div class="btn-group" role="group">
                        <div class="dropdown">
                            <a class="btn btn-dark btn-sm dropdown-toggle" href="#" role="button" id="statistic-tingkat_pendidikan-download" data-bs-toggle="dropdown" aria-expanded="false"><i class="zmdi zmdi-download"></i></a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="statistic-tingkat_pendidikan-download">
                                <li><a class="dropdown-item under-development-msg" href="#">Unduh Excel</a></li>
                                <li><a class="dropdown-item under-development-msg" href="#">Cetak</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2" id="statistic-jenis_kelamin" style="min-height: 100px;">
                <!-- Content will be load from ajax request -->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card card-header-actions h-100 border-primary">
            <div id="statistic-hubungan_kerja-loader" class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="card-header text-dark">
                Hubungan Kerja
                <div class="float-right">
                    <div class="btn-group" role="group">
                        <div class="dropdown">
                            <a class="btn btn-dark btn-sm dropdown-toggle" href="#" role="button" id="statistic-tingkat_pendidikan-download" data-bs-toggle="dropdown" aria-expanded="false"><i class="zmdi zmdi-download"></i></a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="statistic-tingkat_pendidikan-download">
                                <li><a class="dropdown-item under-development-msg" href="#">Unduh Excel</a></li>
                                <li><a class="dropdown-item under-development-msg" href="#">Cetak</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2" id="statistic-hubungan_kerja" style="min-height: 100px;">
                <!-- Content will be load from ajax request -->
            </div>
        </div>
    </div>
</div>