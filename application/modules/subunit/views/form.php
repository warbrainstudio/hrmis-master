<div class="modal fade" id="modal-form-subunit" data-backdrop="static" data-keyboard="false">
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
        <form id="form-subunit" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label required>Unit</label>
            <div class="select">
              <select name="unit_id" class="form-control select2 subunit-unit_id" data-placeholder="Pilih &#8595;" required>
                <?= $list_unit ?>
              </select>
              <i class="form-group__bar"></i>
            </div>
          </div>

          <div class="form-group">
            <label required>Kode Sub Unit</label>
            <input type="text" name="kode_sub_unit" class="form-control subunit-kode_sub_unit" maxlength="50" placeholder="Kode Sub Unit" required />
            <i class="form-group__bar"></i>
          </div>

          <div class="form-group">
            <label required>Nama Sub Unit</label>
            <input type="text" name="nama_sub_unit" class="form-control subunit-nama_sub_unit" maxlength="100" placeholder="Nama Sub Unit" required />
            <i class="form-group__bar"></i>
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text subunit-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text subunit-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>