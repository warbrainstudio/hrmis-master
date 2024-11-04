<div class="modal fade" id="modal-form-mesin" data-backdrop="static" data-keyboard="false">
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
        <form id="form-mesin" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
          <input type="hidden" name="id" class="form-control mesin-id" maxlength="255" placeholder="ID" required />

          <div class="form-group">
            <label required>Nama Mesin</label>
            <input type="text" name="nama_mesin" class="form-control mesin-nama_mesin" maxlength="255" placeholder="Nama Mesin" required />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>IP Adress</label>
            <input type="text" name="ipadress" class="form-control mesin-ipadress" maxlength="30" placeholder="xxx.xxx.xxx.xxx" />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Comm Key</label>
            <input type="text" name="commkey" class="form-control mesin-commkey" maxlength="30" placeholder="Comm Key" />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Lokasi</label>
            <textarea name="lokasi" class="form-control mesin-lokasi" rows="3" placeholder="Lokasi"></textarea>
            <i class="form-group__bar"></i>
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text mesin-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text mesin-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>