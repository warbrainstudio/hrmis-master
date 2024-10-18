<div class="modal fade" id="modal-form-contract" data-backdrop="static" data-keyboard="false">
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
                <form id="form-contract" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" readonly />
                    <input type="hidden" name="ref" value="<?= $key ?>" readonly />

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>No. Kontrak</label>
                                <input type="text" name="no_kontrak" class="form-control contract-no_kontrak" placeholder="No. Kontrak" required value="<?= @$contract->no_kontrak ?>" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Pegawai</label>
                                <div class="select">
                                    <select name="pegawai_id" class="form-control select2-partial contract-pegawai_id" required></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Kategori Pegawai</label>
                                <div class="select">
                                    <select name="kategori_pegawai_id" class="form-control select2-partial contract-kategori_pegawai_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $kategori_pegawai_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Status Pegawai</label>
                                <div class="select">
                                    <select name="jenis_pegawai_id" class="form-control select2-partial contract-jenis_pegawai_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $jenis_pegawai_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Status Kontrak</label>
                                <div class="select">
                                    <select name="status_kontrak_id" class="form-control select2-partial contract-status_kontrak_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $status_kontrak_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Jabatan</label>
                                <div class="select">
                                    <select name="jabatan_id" class="form-control select2-partial contract-jabatan_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $jabatan_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Unit</label>
                                <div class="select">
                                    <select name="unit_id" class="form-control select2-partial contract-unit_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $unit_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>Sub Unit</label>
                                <div class="select">
                                    <select name="sub_unit_id" class="form-control select2-partial contract-sub_unit_id" data-placeholder="Pilih &#8595;" required>
                                        <?= $sub_unit_list ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>SOC</label>
                                <input type="text" name="soc" class="form-control flatpickr-partial-date bg-white contract-soc" placeholder="SOC" readonly required value="<?= @$contract->soc ?>" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label required>EOC</label>
                                <input type="text" name="eoc" class="form-control flatpickr-partial-date bg-white contract-eoc" placeholder="EOC" readonly required value="<?= @$contract->eoc ?>" />
                            </div>
                        </div>
                    </div>
                    <!-- <div class="x-section-group mt-2 mb-3 section-contract_pembayaran" style="<?= (in_array(@$contract->jenis_pegawai_id, $contract_type->mitra) !== false) ? 'display: block;' : 'display: none;' ?>">
                        <span class="x-section-group-title">Pembayaran</span>
                        <div class="x-section-group-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label required>Gaji Pokok</label>
                                        <input type="text" name="gaji_pokok" class="form-control mask-money contract-gaji_pokok" placeholder="Gaji Pokok" required value="<?= @$payroll->gaji_pokok ?>" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label required>Maksimal Hari Kerja / Bulan</label>
                                        <input type="number" name="max_hari_kerja" class="form-control contract-max_hari_kerja" placeholder="Maksimal Hari Kerja / Bulan" required value="<?= @$payroll->max_hari_kerja ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label required>Status</label>
                        <div class="form-control" style="height: 44.22px;">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input contract-status_active-1" type="radio" name="status_active" id="status_active-1" value="1" <?= (@$contract->status_active == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_active-1">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input contract-status_active-0" type="radio" name="status_active" id="status_active-0" value="0" <?= (@$contract->status_active == 0) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_active-0">Tidak Aktif</label>
                            </div>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text contract-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text contract-action-cancel" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>