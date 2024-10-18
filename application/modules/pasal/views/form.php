<section id="pasal">
    <div class="card">
        <div class="card-body">

            <form id="form-pasal" enctype="multipart/form-data" autocomplete="off">
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
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <textarea name="pasal" class="form-control tinymce-init pasal-content" placeholder="Content" data-height="500" required><?php echo (isset($app->pasal)) ? $app->pasal : '' ?></textarea>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <small class="form-text text-muted">
                    Fields with red stars (<label required></label>) are required.
                </small>

                <div class="row" style="margin-top: 2rem;">
                    <div class="col col-md-3 col-lg-2">
                        <button class="btn btn--raised btn-primary btn--icon-text btn-block page-action-save spinner-action-button">
                            Simpan Perubahan
                            <div class="spinner-action"></div>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>