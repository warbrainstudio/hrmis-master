<div class="modal fade" id="modal-form-diklat" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Input' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <div class="tab-container">
                    <ul class="nav nav-tabs nav-responsive" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-input" role="tab">Input</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-parameter_sertifikat" role="tab" id="nav-tab-parameter_sertifikat">Parameter Template Sertifikat</a>
                        </li>
                    </ul>
                    <div class="tab-content clear-tab-content">
                        <div class="tab-pane active fade show" id="tab-input" role="tabpanel">
                            <div class="pt-3">
                                <form id="form-diklat" enctype="multipart/form-data" autocomplete="off">
                                    <!-- CSRF -->
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" readonly />
                                    <input type="hidden" name="ref" value="<?= $key ?>" readonly />

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label required>Kategori</label>
                                                <div class="form-control" style="height: 44.22px;">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input diklat-tipe-1" type="radio" name="tipe" id="tipe-1" value="Internal" <?= (@$diklat->tipe == 'Internal') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="tipe-1">Internal</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input diklat-tipe-2" type="radio" name="tipe" id="tipe-2" value="Eksternal" <?= (@$diklat->tipe == 'Eksternal') ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="tipe-2">Eksternal</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label required>Nama Pelatihan</label>
                                                <input type="text" name="nama_pelatihan" class="form-control diklat-nama_pelatihan" placeholder="Nama Pelatihan" value="<?= @$diklat->nama_pelatihan ?>" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label required>Tanggal Mulai</label>
                                                <input type="text" name="tanggal_mulai" class="form-control flatpickr-partial-date bg-white diklat-tanggal_mulai" placeholder="Tanggal Mulai" value="<?= @$diklat->tanggal_mulai ?>" readonly required />
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label required>Tanggal Selesai</label>
                                                <input type="text" name="tanggal_selesai" class="form-control flatpickr-partial-date bg-white diklat-tanggal_selesai" placeholder="Tanggal Selesai" value="<?= @$diklat->tanggal_selesai ?>" readonly required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 <?= (@$diklat->tipe === 'Internal') ? 'col-sm-6' : 'col-sm-12' ?> diklat-tempat_pelatihan-container">
                                            <div class="form-group">
                                                <label>Tempat Pelatihan</label>
                                                <input type="text" name="tempat_pelatihan" class="form-control diklat-tempat_pelatihan" placeholder="Tempat Pelatihan" value="<?= @$diklat->tempat_pelatihan ?>" />
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 diklat-template_sertifikat-container" style="display: <?= (@$diklat->tipe === 'Internal') ? 'block' : 'none' ?>">
                                            <div class="form-group">
                                                <label required>
                                                    Template Sertifikat
                                                    <!-- <a href="#">(Lihat Parameter)</a> -->
                                                </label>
                                                <div class="form-control" style="height: 44.22px; padding-top: 12px;">
                                                    <input type="hidden" name="template_sertifikat_temp" value="<?= @$diklat->template_sertifikat ?>" readonly />
                                                    <!-- Upload -->
                                                    <input type="file" name="template_sertifikat" class="upload-simple diklat-template_sertifikat" data-text="Pilih Template (docx)" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document" style="width: 163px;" required />
                                                    <span class="upload-simple-preview upload-simple-preview-template_sertifikat">&nbsp;</span>
                                                    <!-- Existing -->
                                                    <?php if (!is_null(@$diklat->template_sertifikat) && !empty(@$diklat->template_sertifikat)) : ?>
                                                        <a href="<?= base_url(@$diklat->template_sertifikat) ?>" class="diklat-template_sertifikat-existing" target="_blank" title="Unduh Template Sertifikat">
                                                            <i class="zmdi zmdi-download"></i> Unduh
                                                        </a>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Keterangan</label>
                                                <textarea name="keterangan" class="form-control diklat-keterangan" rows="3" placeholder="Keterangan"><?= @$diklat->keterangan ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="x-section-group mb-2">
                                        <span class="x-section-group-title">
                                            <label required>Peserta</label>
                                        </span>
                                        <div class="x-section-group-body">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="select">
                                                        <select class="form-control select2-partial diklat-pegawai_search" style="width: 430px;"></select>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <a href="javascript:;" class="diklat-participant-add">
                                                            <span class="input-group-text text-primary" style="border-radius: 0.35rem !important; margin-left: 10px;">Tambah</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="participant-collection mb-2" style="max-height: 315px; overflow-y: auto; overflow-x: hidden;">
                                                <!-- Content generated by javascript : load_participantItem() -->
                                            </div>
                                            <div class="alert alert-warning p-2 m-0">
                                                <small>
                                                    <i class="zmdi zmdi-alert-triangle"></i> <b>Petunjuk</b>
                                                    <ul class="m-0" style="padding-left: 15px;">
                                                        <li>Cari pegawai dengan NRP / Nama Lengkap kemudian klik tombol Tambah untuk memasukan kedalam daftar peserta</li>
                                                        <li>Peserta yang sudah dipilih tidak dapat diubah, silahkan hapus jika tidak diikutsertakan</li>
                                                        <li>Tidak ada batasan jumlah peserta pada setiap pelatihan, semakin banyak peserta mungkin akan terasa lambat saat diakses</li>
                                                        <li>Sertifikat hanya bisa di upload jika kategori "Eksternal" dan tanggal selesai >= hari ini, dengan format file PDF</li>
                                                    </ul>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <small class="form-text text-muted">
                                        Fields with red stars (<label required></label>) are required.
                                    </small>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="tab-parameter_sertifikat" role="tabpanel">
                            <?php include_once('parameter_sertifikat.php') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text diklat-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text mappinggajiaction-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>