<div class="modal fade" id="modal-form-tenagaunit" data-backdrop="static" data-keyboard="false">
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
        <form id="form-tenagaunit" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label required>Kode Tenaga Unit</label>
            <input type="text" name="kode_tenaga_unit" class="form-control tenagaunit-kode_tenaga_unit" placeholder="Kode Tenaga Unit" required />
          </div>
          <div class="form-group">
            <label required>Nama Tenaga Unit</label>
            <input type="text" name="nama_tenaga_unit" class="form-control tenagaunit-nama_tenaga_unit" placeholder="Nama Tenaga Unit" required />
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text tenagaunit-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text tenagaunit-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>