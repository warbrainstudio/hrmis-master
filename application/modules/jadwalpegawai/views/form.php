<div class="modal fade" id="modal-form-jadwal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
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
        <form id="form-jadwal" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label required>Nama Jadwal</label>
            <input type="text" name="nama_jadwal" class="form-control jadwal-nama_jadwal" maxlength="50" placeholder="Nama Jadwal" required />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Unit</label>
            <div class="select">
              <select name="unit_id" class="form-control select2 jadwal-unit_id" data-placeholder="Pilih &#8595;" required>
                <?= $list_unit ?>
              </select>
              <i class="form-group__bar"></i>
            </div>
          </div>
          <div class="form-group">
            <label required>Jam Masuk</label>
            <input type="time" name="jadwal_masuk" class="form-control jadwal-jadwal_masuk" maxlength="100" required />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Jam Pulang</label>
            <input type="time" name="jadwal_pulang" class="form-control jadwal-jadwal_pulang" maxlength="100" required />
            <i class="form-group__bar"></i>
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text jadwal-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text jadwal-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>