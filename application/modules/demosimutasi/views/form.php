<div class="modal fade" id="modal-form-demosimutasi" data-backdrop="static" data-keyboard="false">
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
                <form id="form-demosimutasi" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" readonly />
                    <input type="hidden" name="ref" value="<?= $key ?>" readonly />
                    <input type="hidden" name="old_unit_id" class="demosimutasi-old_unit_id" value="<?= @$demosimutasi->old_unit_id ?>" readonly />
                    <input type="hidden" name="old_sub_unit_id" class="demosimutasi-old_sub_unit_id" value="<?= @$demosimutasi->old_sub_unit_id ?>" readonly />
                    <input type="hidden" name="old_jabatan_id" class="demosimutasi-old_jabatan_id" value="<?= @$demosimutasi->old_jabatan_id ?>" readonly />
                    <input type="hidden" name="old_tenaga_unit_id" class="demosimutasi-old_tenaga_unit_id" value="<?= @$demosimutasi->old_tenaga_unit_id ?>" readonly />
                    <input type="hidden" name="old_jenis_pegawai_id" class="demosimutasi-old_jenis_pegawai_id" value="<?= @$demosimutasi->old_jenis_pegawai_id ?>" readonly />

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Kategori</label>
                                <div class="select">
                                    <select name="kategori" class="form-control select2-partial demosimutasi-kategori" data-placeholder="Pilih &#8595;" required>
                                        <?= $kategori_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Pegawai</label>
                                <div class="select">
                                    <select name="pegawai_id" class="form-control select2-partial demosimutasi-pegawai_id" required></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>No. SK</label>
                                <input type="text" name="no_sk" class="form-control demosimutasi-no_sk" placeholder="No. SK" required value="<?= @$demosimutasi->no_sk ?>" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Tanggal SK</label>
                                <input type="text" name="tanggal_sk" class="form-control flatpickr-partial-date bg-white demosimutasi-tanggal_sk" placeholder="Tanggal SK" readonly required value="<?= @$demosimutasi->tanggal_sk ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>No. SKPPJ</label>
                                <input type="text" name="no_skppj" class="form-control demosimutasi-no_skppj" placeholder="No. SKPPJ" required value="<?= @$demosimutasi->no_skppj ?>" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>DOJ</label>
                                <input type="text" name="doj" class="form-control flatpickr-partial-date bg-white demosimutasi-doj" placeholder="DOJ" readonly required value="<?= @$demosimutasi->doj ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="x-section-group mb-3 mt-2">
                                <span class="x-section-group-title">Sebelumnya</span>
                                <div class="x-section-group-body">
                                    <div class="form-group form-group-auto">
                                        <label>Unit</label>
                                        <div class="form-control auto-filled-text-nama_unit"><?= @$demosimutasi->old_nama_unit ?>&nbsp;</div>
                                    </div>
                                    <div class="form-group form-group-auto">
                                        <label>Sub Unit</label>
                                        <div class="form-control auto-filled-text-nama_sub_unit"><?= @$demosimutasi->old_nama_sub_unit ?>&nbsp;</div>
                                    </div>
                                    <div class="form-group form-group-auto">
                                        <label>Jabatan</label>
                                        <div class="form-control auto-filled-text-nama_jabatan"><?= @$demosimutasi->old_nama_jabatan ?>&nbsp;</div>
                                    </div>
                                    <div class="form-group form-group-auto">
                                        <label>Tenaga Unit</label>
                                        <div class="form-control auto-filled-text-nama_tenaga_unit"><?= @$demosimutasi->old_nama_tenaga_unit ?>&nbsp;</div>
                                    </div>
                                    <div class="form-group form-group-auto mb-0">
                                        <label>Status Kerja</label>
                                        <div class="form-control auto-filled-text-nama_jenis_pegawai"><?= @$demosimutasi->old_nama_jenis_pegawai ?>&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="x-section-group mb-3 mt-2">
                                <span class="x-section-group-title">Baru</span>
                                <div class="x-section-group-body">
                                    <div class="form-group">
                                        <label required>Unit</label>
                                        <div class="select">
                                            <select name="new_unit_id" class="form-control select2-partial demosimutasi-new_unit_id" data-placeholder="Pilih &#8595;" required>
                                                <?= $new_unit_list ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label required>Sub Unit</label>
                                        <div class="select">
                                            <select name="new_sub_unit_id" class="form-control select2-partial demosimutasi-new_sub_unit_id" data-placeholder="Pilih &#8595;" required>
                                                <?= $old_sub_unit_list ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label required>Jabatan</label>
                                        <div class="select">
                                            <select name="new_jabatan_id" class="form-control select2-partial demosimutasi-new_jabatan_id" data-placehnewer="Pilih &#8595;" required>
                                                <?= $new_jabatan_list ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label required>Tenaga Unit</label>
                                        <div class="select">
                                            <select name="new_tenaga_unit_id" class="form-control select2-partial demosimutasi-new_tenaga_unit_id" data-placehnewer="Pilih &#8595;" required>
                                                <?= $new_tenaga_unit_list ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label required>Status Kerja</label>
                                        <div class="select">
                                            <select name="new_jenis_pegawai_id" class="form-control select2-partial demosimutasi-new_jenis_pegawai_id" data-placehnewer="Pilih &#8595;" required>
                                                <?= $new_jenis_pegawai_list ?>
                                            </select>
                                        </div>
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
                <button type="button" class="btn btn-success btn--icon-text demosimutasi-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text demosimutasi-action-cancel" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>