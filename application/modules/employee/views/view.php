<?php require_once(APPPATH . 'modules/_cssInject/main.css.php') ?>
<?php include_once(APPPATH . 'views/modal_embed.php') ?>

<style type="text/css">
    .upload-inline .upload-preview img {
        width: 100%;
        height: 195px;
        object-fit: contain;
        border: 1px solid #eee;
    }
</style>

<section id="employee" class="w-100">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <?php if (!is_null(@$pegawai->updated_date) && !empty(@$pegawai->updated_date)) : ?>
                <div class="alert alert-light border p-3 mt-3 mb-2">
                    <i class="zmdi zmdi-info"></i>
                    Terakhir diubah pada <?= @$pegawai->updated_date ?>
                </div>
            <?php endif ?>

            <div class="clear-card"></div>

            <div class="<?= (!$is_mobile) ? 'card mb-4' : 'mb-4' ?>">
                <div class="<?= (!$is_mobile) ? 'card-body pb-1' : '' ?>">
                    <div class="row no-gutters">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <div class="upload-inline">
                                    <div class="upload-preview p-0">
                                        <img src="<?= base_url((@$pegawai->foto) ? @$pegawai->foto : 'themes/_public/img/avatar/male-1.png') ?>" alt="Foto" style="height: 195px; object-fit: contain; border: 1px solid #eee;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-9">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group form-group-auto">
                                        <label>Nama Lengkap</label>
                                        <div class="form-control auto-filled-text-nama_lengkap"><?= @$pegawai->nama_lengkap ?>&nbsp;</div>
                                    </div>
                                    <div class="form-group form-group-auto">
                                        <label>NRP</label>
                                        <div class="form-control auto-filled-text-nrp"><?= @$pegawai->nrp ?>&nbsp;</div>
                                    </div>
                                    <div class="form-group form-group-auto">
                                        <label>Jabatan</label>
                                        <div class="form-control auto-filled-text-jabatan_id"><?= @$pegawai->nama_jabatan ?>&nbsp;</div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group form-group-auto">
                                        <label>Unit</label>
                                        <div class="form-control auto-filled-text-unit_id"><?= @$pegawai->nama_unit ?>&nbsp;</div>
                                    </div>
                                    <div class="form-group form-group-auto">
                                        <label>Sub Unit</label>
                                        <div class="form-control auto-filled-text-sub_unit_id"><?= @$pegawai->nama_sub_unit ?>&nbsp;</div>
                                    </div>
                                    <div class="form-group form-group-auto">
                                        <label>Tenaga Unit</label>
                                        <div class="form-control auto-filled-text-tenaga_unit_id"><?= @$pegawai->nama_tenaga_unit ?>&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-container">
                <ul class="nav nav-tabs nav-responsive" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab-basic_information" role="tab">Informasi Dasar</a>
                    </li>
                    <?php if (!is_null($pegawai)) : ?>
                        <?php if(!is_null(@$pegawai->absen_pegawai_id)) : ?>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-absensi" role="tab" id="nav-tab-absensi">Absensi</a>
                        </li>
                        <?php endif ?>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-family" role="tab" id="nav-tab-family">Keluarga</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-sk_perijinan" role="tab">SK / Perijinan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-kontrak_kerja" role="tab">Kontrak Kerja</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-diklat" role="tab">Diklat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-pembinaan" role="tab">Pembinaan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-mutasi" role="tab">Demosi / Mutasi</a>
                        </li>
                    <?php endif ?>
                </ul>
                <div class="tab-content clear-tab-content">
                    <div class="tab-pane active fade show" id="tab-basic_information" role="tabpanel">
                        <div class="pt-4">
                            <?php require_once(APPPATH . 'modules/employee/views/view_basic_information.php') ?>
                        </div>
                    </div>
                    <?php if (!is_null($pegawai)) : ?>
                        <?php if(!is_null(@$pegawai->absen_pegawai_id)) : ?>
                        <div class="tab-pane fade show" id="tab-absensi" role="tabpanel">
                            <?php require_once(APPPATH . 'modules/employee/views/histori_absensi.php') ?>
                        </div>
                        <?php endif ?>
                        <div class="tab-pane fade show" id="tab-family" role="tabpanel">
                            <!-- Content will be load from javascript -->
                            <iframe id="iframe-employee_family" src="#" frameborder="0" width="100%" height="100%" onload="resizeIframe(this)" />
                        </div>
                        <div class="tab-pane fade show" id="tab-sk_perijinan" role="tabpanel">
                            <?php require_once(APPPATH . 'modules/employee/views/histori_skspk.php') ?>
                        </div>
                        <div class="tab-pane fade show" id="tab-kontrak_kerja" role="tabpanel">
                            <?php require_once(APPPATH . 'modules/employee/views/histori_kontrak.php') ?>
                        </div>
                        <div class="tab-pane fade show" id="tab-diklat" role="tabpanel">
                            <?php require_once(APPPATH . 'modules/employee/views/histori_diklat.php') ?>
                        </div>
                        <div class="tab-pane fade show" id="tab-pembinaan" role="tabpanel">
                            <?php require_once(APPPATH . 'modules/employee/views/histori_pembinaan.php') ?>
                        </div>
                        <div class="tab-pane fade show" id="tab-mutasi" role="tabpanel">
                            <?php require_once(APPPATH . 'modules/employee/views/histori_demosimutasi.php') ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="buttons-container">
                        <div class="row">
                            <div class="col">
                                <a href="<?= base_url('employee') ?>" class="btn btn--raised btn-dark btn--icon-text btn-custom">
                                    <i class="zmdi zmdi-chevron-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col text-right">
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('employee/input?ref=' . @$pegawai->id) ?>" class="btn btn--raised btn-warning btn--icon-text x-load-partial">
                                        <i class="zmdi zmdi-edit"></i> Ubah
                                    </a>
                                    <a href="<?= base_url('idcard/generate?ref=' . @$pegawai->id) ?>" modal-id="modal-view-idcard" class="btn btn--raised btn-warning btn--icon-text x-load-modal-partial">
                                        <i class="zmdi zmdi-download"></i> ID Card
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>