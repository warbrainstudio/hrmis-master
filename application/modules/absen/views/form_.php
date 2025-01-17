<div class="modal fade" id="modal-form-absen" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-x1">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left">
          <?= (isset($card_title)) ? $card_title : 'Form' ?>
        </h5>
      </div>
      <div class="spinner">
        <div class="lds-hourglass"></div>
      </div>
      <div class="modal-body">
        <form id="form-absen" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
          
          <div class="form-group">
            <label required>Nama Pegawai</label>
            <input type="text" name="nama" class="form-control absen-nama" maxlength="100" readonly />
            <i class="form-group__bar"></i>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label required>Jam Masuk</label>
                <input type="datetime-local" name="masuk" class="form-control absen-masuk" maxlength="100" />
                <i class="form-group__bar"></i>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label required>Jam Pulang</label>
                <input type="datetime-local" name="pulang" class="form-control absen-pulang" maxlength="100" />
                <i class="form-group__bar"></i>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group absen-row_verifikasi_masuk">
                <label required>Verifikasi Masuk</label>
                <div class="select">
                  <select name="verifikasi_masuk" class="form-control select2 absen-verifikasi_masuk" data-placeholder="Pilih &#8595;" required>
                    <?= $list_verifikasi ?>
                  </select>
                  <i class="form-group__bar"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group absen-row_verifikasi_pulang">
                <label required>Verifikasi Pulang</label>
                <div class="select">
                  <select name="verifikasi_pulang" class="form-control select2 absen-verifikasi_pulang" data-placeholder="Pilih &#8595;" required>
                    <?= $list_verifikasi ?>
                  </select>
                  <i class="form-group__bar"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group absen-row_mesin_masuk">
                <label required>Mesin Masuk</label>
                <div class="select">
                  <select name="mesin_masuk" class="form-control select2 absen-mesin_masuk" data-placeholder="Pilih &#8595;" required>
                    <?= $list_mesin ?>
                  </select>
                  <i class="form-group__bar"></i>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group absen-row_mesin_pulang">
                <label required>Mesin Pulang</label>
                <div class="select">
                  <select name="mesin_pulang" class="form-control select2 absen-mesin_pulang" data-placeholder="Pilih &#8595;" required>
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
              <input type="hidden" name="unit_id" class="form-control absen-unit_id" maxlength="100" readonly />
              <select name="jadwal_id" class="form-control select2 absen-jadwal_id" data-placeholder="Pilih &#8595;" required>
                <?= $list_jadwal ?>
              </select>
              <i class="form-group__bar"></i>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning btn--icon-text absen-action-change">
          <i class="zmdi zmdi-save"></i> Update
        </button>
        <button type="button" class="btn btn-success btn--icon-text absen-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text absen-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>