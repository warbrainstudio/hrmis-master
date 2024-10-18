<div class="modal fade" id="modal-form-skspk" data-backdrop="static" data-keyboard="false">
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
                <form id="form-skspk" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" readonly />
                    <input type="hidden" name="ref" value="<?= $key ?>" readonly />

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Kategori</label>
                                <div class="form-control" style="height: 44.22px;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input skspk-kategori-1" type="radio" name="kategori" id="kategori-1" value="SK" <?= (@$skspk->kategori == 'SK') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="kategori-1">SK</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input skspk-kategori-2" type="radio" name="kategori" id="kategori-2" value="SPK" <?= (@$skspk->kategori == 'SPK') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="kategori-2">SPK</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Keterangan</label>
                                <div class="select">
                                    <select name="sk_id" class="form-control select2-partial skspk-sk_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $skspk_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Nomor</label>
                                <input type="text" name="no_sk_spk" class="form-control skspk-no_sk_spk" placeholder="Nomor" required value="<?= @$skspk->no_sk_spk ?>" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Pegawai</label>
                                <div class="select">
                                    <select name="pegawai_id" class="form-control select2-partial skspk-pegawai_id" required></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Unit</label>
                                <div class="select">
                                    <select name="unit_id" class="form-control select2-partial skspk-unit_id" data-placeholder="Pilih &#8595;">
                                        <?= $unit_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Sub Unit</label>
                                <div class="select">
                                    <select name="sub_unit_id" class="form-control select2-partial skspk-sub_unit_id" data-placeholder="Pilih &#8595;">
                                        <?= $sub_unit_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <div class="select">
                                    <select name="jabatan_id" class="form-control select2-partial skspk-jabatan_id" data-placeholder="Pilih &#8595;">
                                        <?= $jabatan_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Ruangan</label>
                                <div class="select">
                                    <select name="ruangan_id" class="form-control select2-partial skspk-ruangan_id" data-placeholder="Pilih &#8595;">
                                        <?= $ruangan_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Tanggal Berlaku</label>
                                <input type="text" name="tanggal_berlaku" class="form-control flatpickr-partial-date bg-white skspk-tanggal_berlaku" placeholder="Tanggal Berlaku" readonly required value="<?= @$skspk->tanggal_berlaku ?>" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Status</label>
                                <div class="form-control" style="height: 44.22px;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input skspk-status_active-1" type="radio" name="status_active" id="skspk-status_active-1" value="1" <?= (@$skspk->status_active == 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="skspk-status_active-1">Aktif</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input skspk-status_active-0" type="radio" name="status_active" id="skspk-status_active-0" value="0" <?= (@$skspk->status_active == 0) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="skspk-status_active-0">Tidak Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text skspk-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text skspkaction-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>