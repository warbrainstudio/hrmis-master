<div class="modal fade" id="modal-form-kalenderabsen" data-backdrop="static" data-keyboard="false">
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
        <form id="form-kalenderabsen" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
          <input type="hidden" name="absen_id" class="form-control kalenderabsen-absen_id"/>
          <input type="hidden" name="tanggal_absen" class="form-control kalenderabsen-tanggal_absen"/>
          <div class="form-group">
            <label required>Nama Pegawai</label>
            <input type="text" name="nama" class="form-control kalenderabsen-nama" maxlength="100" readonly />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Jam Masuk</label>
            <input type="datetime" name="jam_masuk" class="form-control kalenderabsen-jam_masuk" maxlength="100" />
            <input type="hidden" name="masuk" class="form-control kalenderabsen-masuk"/>
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Jam Pulang</label>
            <input type="datetime" name="jam_pulang" class="form-control kalenderabsen-jam_pulang" maxlength="100" />
            <input type="hidden" name="pulang" class="form-control kalenderabsen-pulang"/>
            <i class="form-group__bar"></i>
          </div>
          
          <input type="hidden" name="verifikasi_masuk" class="form-control kalenderabsen-verifikasi_masuk"/>
          <input type="hidden" name="mesin_masuk" class="form-control kalenderabsen-mesin_masuk"/>
          <input type="hidden" name="verifikasi_pulang" class="form-control kalenderabsen-verifikasi_pulang"/>
          <input type="hidden" name="mesin_pulang" class="form-control kalenderabsen-mesin_pulang"/>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" title="Tukar absen dari masuk ke pulang atau pulang ke masuk" class="btn btn-warning btn--icon-text kalenderabsen-action-change">
        <i class="zmdi zmdi-swap"></i> Tukar
        </button>
        <button type="button" class="btn btn-success btn--icon-text kalenderabsen-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <!--<button type="button" class="btn btn-danger btn--icon-text kalenderabsen-action-delete">
          <i class="zmdi zmdi-delete"></i> Hapus
        </button>-->
        <button type="button" class="btn btn-light btn--icon-text kalenderabsen-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>