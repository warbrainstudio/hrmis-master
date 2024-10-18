<div class="modal fade" id="modal-form-employeefamily" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Keluarga</h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-employeefamily" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" readonly />
                    <input type="hidden" name="ref" value="<?= $key ?>" readonly />
                    <input type="hidden" name="pegawai_id" value="<?= (isset($employee_family->pegawai_id)) ? $employee_family->pegawai_id : $pegawai_id ?>" readonly />

                    <div class="form-group">
                        <label required>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control employeefamily-nama_lengkap" placeholder="Nama Lengkap" value="<?= @$employee_family->nama_lengkap ?>" required />
                    </div>
                    <div class="form-group">
                        <label required>Hubungan</label>
                        <div class="select">
                            <select name="hubungan" class="form-control select2-partial employee-hubungan" data-placeholder="Pilih &#8595;" required>
                                <?= $hubungan_list ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="no_hp" class="form-control employeefamily-no_hp" placeholder="Np. HP" value="<?= @$employee_family->no_hp ?>" />
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <input type="text" name="alamat_lengkap" class="form-control employeefamily-alamat_lengkap" placeholder="Alamat Lengkap" value="<?= @$employee_family->alamat_lengkap ?>" />
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text employeefamily-action-save-<?= $unique_id ?>">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text skspkaction-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>