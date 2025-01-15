<div class="modal fade" id="modal-form-jadwal-config" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left">
        <i class="zmdi zmdi-settings"></i> Pengaturan Range Batas Jadwal
        </h5>
      </div>
      <div class="spinner">
        <div class="lds-hourglass"></div>
      </div>
      <div class="modal-body">
        <form id="form-jadwal-config" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
          <input type="hidden" name="id" class="form-control jadwal-id" value="<?= (@$data_config->id !== null) ? @$data_config->id : null ?>" />
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Masuk Cepat (menit)</label>
                <input type="number" name="masuk_cepat" class="form-control jadwal-masuk_cepat" maxlength="2" min="0" max="60" placeholder="Menit" value="<?= (@$data_config->masuk_cepat !== null) ? @$data_config->masuk_cepat : '' ?>" />
                <i class="form-group__bar"></i>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Pulang Cepat (menit)</label>
                <input type="number" name="pulang_cepat" class="form-control jadwal-pulang_cepat" maxlength="2" min="0" max="60" placeholder="Menit" value="<?= (@$data_config->pulang_cepat !== null) ? @$data_config->pulang_cepat : '' ?>" />
                <i class="form-group__bar"></i>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Masuk Terlambat (menit)</label>
                <input type="number" name="masuk_terlambat" class="form-control jadwal-masuk_terlambat" maxlength="2" min="0" max="60" placeholder="Menit" value="<?= (@$data_config->masuk_terlambat !== null) ? @$data_config->masuk_terlambat : '' ?>" />
                <i class="form-group__bar"></i>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Pulang Terlambat (menit)</label>
                <input type="number" name="pulang_terlambat" class="form-control jadwal-pulang_terlambat" maxlength="2" min="0" max="60" placeholder="Menit" value="<?= (@$data_config->pulang_terlambat !== null) ? @$data_config->pulang_terlambat : '' ?>" />
                <i class="form-group__bar"></i>
              </div>
            </div>
          </div>
        </form>
        <small class="form-text text-muted">
          <label required></label> <?= !empty($data_config->updated_date) ? 'Diubah terakhir : '.$data_config->updated_date : '' ?>
        </small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn--icon-text jadwal-action-save-config">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text jadwal-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>