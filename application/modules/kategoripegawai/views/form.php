<div class="modal fade" id="modal-form-kategoripegawai" data-backdrop="static" data-keyboard="false">
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
        <form id="form-kategoripegawai" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label required>Nama Kategori Pegawai</label>
            <input type="text" name="nama_kategori_pegawai" class="form-control kategoripegawai-nama_kategori_pegawai" placeholder="Nama Kategori Pegawai" required />
          </div>
          <div class="form-group">
            <label required>Masa Kerja Golongan</label>
            <div class="form-control" style="height: 44.22px;">
              <div class="form-check form-check-inline">
                <input class="form-check-input kategoripegawai-mkg-1" type="radio" name="mkg" id="kategoripegawai-mkg-1" value="NAKES" checked>
                <label class="form-check-label" for="kategoripegawai-mkg-1">NAKES</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input kategoripegawai-mkg-2" type="radio" name="mkg" id="kategoripegawai-mkg-2" value="Non NAKES">
                <label class="form-check-label" for="kategoripegawai-mkg-2">Non NAKES</label>
              </div>
            </div>
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text kategoripegawai-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text kategoripegawai-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>