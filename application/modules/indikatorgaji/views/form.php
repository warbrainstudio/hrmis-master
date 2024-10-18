<div class="modal fade" id="modal-form-indikatorgaji" data-backdrop="static" data-keyboard="false">
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
        <form id="form-indikatorgaji" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label required>Nama Indikator Gaji</label>
            <input type="text" name="nama_indikator_gaji" class="form-control indikatorgaji-nama_indikator_gaji" placeholder="Nama Indikator Gaji" required />
          </div>
          <div class="form-group">
            <label required>Alias</label>
            <input type="text" name="nama_alias" class="form-control indikatorgaji-nama_alias" placeholder="Alias" required />
          </div>
          <div class="form-group">
            <label>Default Expression</label>
            <textarea name="default_expression" class="form-control indikatorgaji-default_expression" placeholder="Default Expression" rows="3"></textarea>
          </div>

          <div class="alert alert-warning p-2">
            <small>
              <i class="zmdi zmdi-alert-triangle"></i> <b>Aturan Expression</b>
              <ul class="m-0" style="padding-left: 15px;">
                <li>Jika menggunakan indikator lain pastikan penamaan alias & huruf besar / kecil nya sama persis (case-sensitive)</li>
                <li>Penulisan angka / nominal tidak boleh ada format uang, untuk desimal gunakan titik (Contoh : 1500.25)</li>
                <li>Untuk perhitungan pastikan penulisan aritmatika seperti kurung buka tutup nya sudah benar</li>
              </ul>
            </small>
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text indikatorgaji-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text indikatorgaji-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>