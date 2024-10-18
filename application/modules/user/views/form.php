<div class="modal fade" id="modal-form-user" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left">Pengguna</h5>
      </div>
      <div class="spinner">
        <div class="lds-hourglass"></div>
      </div>
      <div class="modal-body">
        <form id="form-user" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label required>Role</label>
            <div class="select">
              <select name="role" class="user-role form-control" data-placeholder="Pilih &#8595;" required>
                <?= $list_role ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label required>Unit</label>
            <div class="select">
              <select name="unit" class="user-unit form-control select2" data-placeholder="Pilih &#8595;" required>
                <?= $list_unit ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label required>Sub Unit</label>
            <div class="select">
              <select name="sub_unit" class="user-sub_unit form-control select2" data-placeholder="Pilih &#8595;" required></select>
            </div>
          </div>

          <div class="form-group">
            <label required>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control user-nama_lengkap" placeholder="Nama Lengkap" required>
            <i class="form-group__bar"></i>
          </div>

          <div class="form-group">
            <label required>Email</label>
            <input type="email" name="email" class="form-control user-email" placeholder="Email" required>
            <i class="form-group__bar"></i>
          </div>

          <div class="form-group">
            <label required>Username</label>
            <div class="position-relative">
              <input type="text" name="username" class="form-control user-username" placeholder="Username" maxlength="30" required>
              <i class="form-group__bar"></i>
            </div>
            <small class="form-text text-muted">
              Use alpha-numeric with minimum 5 and maximum 30 characters.
            </small>
          </div>

          <div class="form-group">
            <label>Password</label>
            <div class="input-group">
              <input type="password" name="password" class="form-control no-padding-l user-password" placeholder="(Optional) Type new password for change" autocomplete="new-password">
              <i class="form-group__bar"></i>
              <div class="input-group-append">
                <span class="input-group-text">
                  <a href="javascript:;" class="visibility-password" data-input=".user-password"></a>
                </span>
              </div>
            </div>
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text user-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text user-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>