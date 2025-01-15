<div class="modal fade" id="modal-form-import" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left">
          <i class="zmdi zmdi-file-plus"></i> Import <?php echo (isset($card_title)) ? $card_title : '' ?>
        </h5>
      </div>
      <div class="spinner">
        <div class="lds-hourglass"></div>
      </div>
      <div class="modal-body">
        <form id="form-import" enctype="multipart/form-data">

          <div class="alert alert-warning">
            <div class="row">
              <div class="col-auto mb-0">
                <i class="zmdi zmdi-info" style="font-size: 2rem"></i>
              </div>
              <div class="col text-left mb-0">
                Pastikan berkas yang diunggah sesuai dengan template. <br>
                <small><i>*Disarankan untuk tidak lebih dari 1.000 baris</i></small>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <a href="<?= base_url('directory/templates/import-employee.xlsx') ?>" download>
              <div style="border: 1px dashed #03a9f4; border-radius: 0.35rem; padding: 10px; text-align: center;">
                <i class="zmdi zmdi-file"></i> &nbsp;Unduh Template
              </div>
            </a>
          </div>

          <div class="form-group mb-2">
            <label required>Berkas</label>
            <input type="file" name="source_file" class="form-control import-source_file" accept=".xlsx" required>
            <i class="form-group__bar"></i>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="import-submit" class="btn btn-success btn--icon-text import-action-save">
          <i class="zmdi zmdi-save"></i> Import
        </button>
        <button type="button" class="btn btn-light btn--icon-text import-action-cancel" data-dismiss="modal">
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>