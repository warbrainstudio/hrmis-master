<section id="setting">
    <div class="card">
        <div class="card-body">

            <form id="form-setting-account" enctype="multipart/form-data" autocomplete="off">
                <!-- CSRF -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                <div class="row">
                    <div class="col-xs-10 col-md-10">
                        <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
                        <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>
                        <div class="clear-card"></div>
                    </div>
                </div>
                <div class="clear-card"></div>

                <div class="row">
                    <div class="col-xs-10 col-md-4">
                        <?php if (!$this->session->userdata('user')['profile_photo']) : ?>
                            <div class="alert alert-warning text-center" style="background: var(--orange);">
                                <i class="zmdi zmdi-alert-triangle"></i>
                                Please upload your profile picture!
                            </div>
                        <?php endif ?>

                        <div class="form-group">
                            <label required>Foto</label>
                            <div class="upload-inline">
                                <div class="upload-button">
                                    <input type="file" name="profile_photo" class="upload-pure-button user-profile_photo" accept="image/jpg,image/jpeg,image/png,image/gif" />
                                </div>
                                <div class="upload-preview">
                                    <!-- Existing -->
                                    <?php $profile_photo = (!is_null($data->profile_photo) && !empty($data->profile_photo)) ? $data->profile_photo : 'themes/_public/img/avatar/male-1.png' ?>
                                    <a href="<?= base_url($profile_photo) ?>" data-fancybox>
                                        <img src="<?= base_url($profile_photo) ?>" alt="Profile Photo">
                                    </a>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                Use file with format .JPG, .JPEG, .PNG or .GIF
                            </small>
                        </div>
                    </div>
                    <div class="col xs-10 col-md-8">
                        <div class="row">
                            <div class="col-xs-10 col-md-8">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <input type="text" name="unit" class="form-control user-unit" placeholder="Unit" value="<?php echo $data->unit ?>" readonly />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-10 col-md-8">
                                <div class="form-group">
                                    <label>Sub Unit</label>
                                    <input type="text" name="sub_unit" class="form-control user-sub_unit" placeholder="Sub Unit" value="<?php echo $data->sub_unit ?>" readonly />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-10 col-md-8">
                                <div class="form-group">
                                    <label required>Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control user-nama_lengkap" placeholder="Nama Lengkap" required value="<?php echo $data->nama_lengkap ?>" />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-10 col-md-8">
                                <div class="form-group">
                                    <label required>Email</label>
                                    <input type="email" name="email" class="form-control user-email" placeholder="Email" required value="<?php echo $data->email ?>" />
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-10 col-md-8">
                                <div class="form-group">
                                    <label required>Username</label>
                                    <div class="position-relative">
                                        <input type="text" name="username" class="form-control user-username" placeholder="Username" maxlength="30" required value="<?php echo $data->username ?>" />
                                        <i class="form-group__bar"></i>
                                    </div>
                                    <small class="form-text text-muted">
                                        Use alpha-numeric with minimum 5 and maximum 30 characters.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-10 col-md-8">
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control no-padding-l user-password" placeholder="(Optional) Type new password for change" autocomplete="new-password" />
                                        <i class="form-group__bar"></i>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <a href="javascript:;" class="visibility-password" data-input=".user-password"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <small class="form-text text-muted">
                            Fields with red stars (<label required></label>) are required.
                        </small>

                        <div class="mt-3 mb-3">
                            <button class="btn btn--raised btn-primary btn--icon-text pl-4 pr-4 page-action-save-account spinner-action-button">
                                Simpan Perubahan
                                <div class="spinner-action"></div>
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>