<section id="setting">
    <div class="card">
        <div class="card-body">

            <form id="form-setting-general" enctype="multipart/form-data" autocomplete="off">
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

                <!-- Superadmin only -->
                <?php if ($this->session->userdata('user')['role'] === 'Administrator') : ?>
                    <div class="row">
                        <div class="col-xs-10 col-md-6">
                            <div class="form-group">
                                <label required>Company Name</label>
                                <input type="text" name="company_name" class="form-control setting-company_name" placeholder="Company Name" value="<?php echo (isset($app->company_name)) ? $app->company_name : '' ?>" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-10 col-md-6">
                            <div class="form-group">
                                <label required>Slogan</label>
                                <input type="text" name="company_slogan" class="form-control setting-company_slogan" placeholder="Slogan" value="<?php echo (isset($app->company_slogan)) ? $app->company_slogan : '' ?>" />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <!-- END ## Superadmin only -->

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>PPN</label>
                            <div class="input-group">
                                <input type="text" name="ppn_persentase" class="form-control mask-number setting-ppn_persentase" placeholder="PPN" value="<?php echo (isset($app->ppn_persentase)) ? $app->ppn_persentase : '' ?>" />
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0">%</span>
                                </div>
                            </div>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <small class="form-text text-muted">
                    Fields with red stars (<label required></label>) are required.
                </small>

                <div class="row" style="margin-top: 2rem;">
                    <div class="col col-md-3 col-lg-2">
                        <button class="btn btn--raised btn-primary btn--icon-text btn-block page-action-save-general spinner-action-button">
                            Simpan Perubahan
                            <div class="spinner-action"></div>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>