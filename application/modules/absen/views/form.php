<div class="modal fade" id="modal-form-absen" data-backdrop="static" data-keyboard="false">
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
        <form id="form-absen" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
          <input type="hidden" name="absen_id" class="form-control absen-absen_id"/>
          <input type="hidden" name="tanggal_absen" class="form-control absen-tanggal_absen"/>
          <div class="form-group">
            <label required>Nama Pegawai</label>
            <input type="text" name="nama" class="form-control absen-nama" maxlength="100" readonly />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Jam Masuk</label>
            <input type="datetime" name="jam_masuk" class="form-control absen-jam_masuk" maxlength="100" />
            <input type="hidden" name="masuk" class="form-control absen-masuk"/>
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Jam Pulang</label>
            <input type="datetime" name="jam_pulang" class="form-control absen-jam_pulang" maxlength="100" />
            <input type="hidden" name="pulang" class="form-control absen-pulang"/>
            <i class="form-group__bar"></i>
          </div>
          
          <input type="hidden" name="verifikasi_masuk" class="form-control absen-verifikasi_masuk"/>
          <input type="hidden" name="mesin_masuk" class="form-control absen-mesin_masuk"/>
          <input type="hidden" name="verifikasi_pulang" class="form-control absen-verifikasi_pulang"/>
          <input type="hidden" name="mesin_pulang" class="form-control absen-mesin_pulang"/>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" title="Tukar absen dari masuk ke pulang atau pulang ke masuk" class="btn btn-warning btn--icon-text absen-action-change">
        <i class="zmdi zmdi-swap"></i> Tukar
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