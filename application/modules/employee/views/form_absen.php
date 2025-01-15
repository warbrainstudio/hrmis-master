<div class="modal fade" id="modal-form-absen_employee" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-x1">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left">
        <span class="badge badge-info">Edit</span> Absensi
        </h5>
      </div>
      <div class="spinner">
        <div class="lds-hourglass"></div>
      </div>
      <div class="modal-body">
        <form id="form-absen_employee" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label required>Jam Masuk</label>
                <input type="datetime-local" name="masuk" class="form-control employee-masuk" value="<?= @$absen->masuk ?>"/>
                <i class="form-group__bar"></i>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label required>Jam Pulang</label>
                <input type="datetime-local" name="pulang" class="form-control employee-pulang" value="<?= @$absen->pulang ?>"/>
                <i class="form-group__bar"></i>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group employee-row_verifikasi_masuk">
                <label required>Verifikasi Masuk</label>
                <div class="select">
                  <select name="verifikasi_masuk" class="form-control select2 employee-verifikasi_masuk" data-placeholder="Pilih &#8595;" required>
                    <?= $list_verifikasi ?>
                  </select>
                  <i class="form-group__bar"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group employee-row_verifikasi_pulang">
                <label required>Verifikasi Pulang</label>
                <div class="select">
                  <select name="verifikasi_pulang" class="form-control select2 employee-verifikasi_pulang" data-placeholder="Pilih &#8595;" required>
                    <?= $list_verifikasi ?>
                  </select>
                  <i class="form-group__bar"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group employee-row_mesin_masuk">
                <label required>Mesin Masuk</label>
                <div class="select">
                  <select name="mesin_masuk" class="form-control select2 employee-mesin_masuk" data-placeholder="Pilih &#8595;" required>
                    <?= $list_mesin ?>
                  </select>
                  <i class="form-group__bar"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group employee-row_mesin_pulang">
                <label required>Mesin Pulang</label>
                <div class="select">
                  <select name="mesin_pulang" class="form-control select2 employee-mesin_pulang" data-placeholder="Pilih &#8595;" required>
                    <?= $list_mesin ?>
                  </select>
                  <i class="form-group__bar"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label required>Jadwal</label>
            <div class="select">
              <select name="jadwal_id" class="form-control select2 employee-jadwal_id" data-placeholder="Pilih &#8595;" required>
                <?= $list_jadwal ?>
              </select>
              <i class="form-group__bar"></i>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning btn--icon-text employee-action-change_absen">
          <i class="zmdi zmdi-save"></i> Update
        </button>
        <button type="button" class="btn btn-success btn--icon-text employee-action-save_absen">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text employee-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>