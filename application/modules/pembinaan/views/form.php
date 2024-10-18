<div class="modal fade" id="modal-form-pembinaan" data-backdrop="static" data-keyboard="false">
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
                <form id="form-pembinaan" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" readonly />
                    <input type="hidden" name="ref" value="<?= $key ?>" readonly />

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Kategori</label>
                                <div class="select">
                                    <select name="kategori" class="form-control select2-partial pembinaan-kategori" data-placeholder="Pilih &#8595;" required>
                                        <?= $kategori_pembinaan_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>No. Pembinaan</label>
                                <input type="text" name="no_pembinaan" class="form-control pembinaan-no_pembinaan" placeholder="No. Pembinaan" required value="<?= @$pembinaan->no_pembinaan ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Pegawai</label>
                                <div class="select">
                                    <select name="pegawai_id" class="form-control select2-partial pembinaan-pegawai_id" required></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Perihal</label>
                                <input type="text" name="perihal" class="form-control pembinaan-perihal" placeholder="Perihal" required value="<?= @$pembinaan->perihal ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Unit</label>
                                <div class="select">
                                    <select name="unit_id" class="form-control select2-partial pembinaan-unit_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $unit_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Jabatan</label>
                                <div class="select">
                                    <select name="jabatan_id" class="form-control select2-partial pembinaan-jabatan_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $jabatan_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Tanggal Mulai</label>
                                <input type="text" name="start_date" class="form-control flatpickr-partial-date bg-white pembinaan-start_date" placeholder="Tanggal Mulai" readonly required value="<?= @$pembinaan->start_date ?>" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Tanggal Selesai</label>
                                <input type="text" name="end_date" class="form-control flatpickr-partial-date bg-white pembinaan-end_date" placeholder="Tanggal Selesai" readonly required value="<?= @$pembinaan->end_date ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 wrap-pembinaan-pelanggaran" style="display: <?= (@$pembinaan->kategori === 'Scorsing') ? 'none' : 'block' ?>">
                            <div class="form-group">
                                <label required>Pelanggaran</label>
                                <textarea name="pelanggaran" class="form-control tinymce-init-partial pembinaan-pelanggaran" data-height="300" placeholder="Pelanggaran" required><?= @$pembinaan->pelanggaran ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 wrap-pembinaan-sanksi">
                            <div class="form-group">
                                <label required>Sanksi</label>
                                <textarea name="sanksi" class="form-control tinymce-init-partial pembinaan-sanksi" data-height="300" placeholder="Sanksi" required><?= @$pembinaan->sanksi ?></textarea>
                            </div>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <div style="flex: auto;">
                    <a href="<?php echo base_url('pasal/content') ?>" class="btn btn--raised btn-dark btn--icon-text x-load-swal-content">
                        <i class="zmdi zmdi-file-text"></i> Lihat Pasal
                    </a>
                </div>
                <button type="button" class="btn btn-success btn--icon-text pembinaan-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text pembinaan-action-cancel" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>