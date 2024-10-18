<style type="text/css">
    .img-home {
        margin-top: 1rem;
    }

    @media only screen and (max-width: 768px) {
        .img-home {
            margin-bottom: 2rem;
            width: 100%;
        }
    }
</style>

<section id="setting">
    <div class="card">
        <div class="card-body">

            <form id="form-setting-dashboard_image" enctype="multipart/form-data" autocomplete="off">
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
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Image</label>
                            <input type="file" name="dashboard_image_source" class="form-control setting-dashboard_image_source" />
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Width</label>
                            <div class="input-group">
                                <input type="number" name="dashboard_image_width" class="form-control mask-number no-padding-l setting-dashboard_image setting-dashboard_image_width" placeholder="Width" min="10" max="100" value="<?php echo (isset($app->dashboard_image_width)) ? $app->dashboard_image_width : '' ?>" />
                                <i class="form-group__bar"></i>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span style="width: 30px;">%</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Max Height</label>
                            <div class="input-group">
                                <input type="number" name="dashboard_image_max_height" class="form-control mask-number no-padding-l setting-dashboard_image setting-dashboard_image_max_height" placeholder="Max Height" value="<?php echo (isset($app->dashboard_image_max_height)) ? $app->dashboard_image_max_height : '' ?>" />
                                <i class="form-group__bar"></i>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span style="width: 30px;">px</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Object Fit</label>
                            <div class="select">
                                <select name="dashboard_image_object_fit" class="form-control setting-dashboard_image setting-dashboard_image_object_fit" data-placeholder="Select a object fit">
                                    <option value="fill" <?= (isset($app->dashboard_image_object_fit) && $app->dashboard_image_object_fit === 'fill') ? 'selected' : '' ?>>Fill</option>
                                    <option value="contain" <?= (isset($app->dashboard_image_object_fit) && $app->dashboard_image_object_fit === 'contain') ? 'selected' : '' ?>>Contain</option>
                                    <option value="cover" <?= (isset($app->dashboard_image_object_fit) && $app->dashboard_image_object_fit === 'cover') ? 'selected' : '' ?>>Cover</option>
                                    <option value="none" <?= (isset($app->dashboard_image_object_fit) && $app->dashboard_image_object_fit === 'none') ? 'selected' : '' ?>>None</option>
                                    <option value="scale-down" <?= (isset($app->dashboard_image_object_fit) && $app->dashboard_image_object_fit === 'scale-down') ? 'selected' : '' ?>>Scale Down</option>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Object Position</label>
                            <div class="select">
                                <select name="dashboard_image_object_position" class="form-control setting-dashboard_image setting-dashboard_image_object_position" data-placeholder="Select a object position">
                                    <option value="center" <?= (isset($app->dashboard_image_object_position) && $app->dashboard_image_object_position === 'center') ? 'selected' : '' ?>>Center</option>
                                    <option value="bottom" <?= (isset($app->dashboard_image_object_position) && $app->dashboard_image_object_position === 'bottom') ? 'selected' : '' ?>>Bottom</option>
                                    <option value="left" <?= (isset($app->dashboard_image_object_position) && $app->dashboard_image_object_position === 'left') ? 'selected' : '' ?>>Left</option>
                                    <option value="right" <?= (isset($app->dashboard_image_object_position) && $app->dashboard_image_object_position === 'right') ? 'selected' : '' ?>>Right</option>
                                    <option value="top" <?= (isset($app->dashboard_image_object_position) && $app->dashboard_image_object_position === 'top') ? 'selected' : '' ?>>Top</option>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Box Shadow</label>
                            <div class="select">
                                <select name="dashboard_image_box_shadow" class="form-control setting-dashboard_image setting-dashboard_image_box_shadow" data-placeholder="Select a box shadow">
                                    <option value="1" <?= (isset($app->dashboard_image_box_shadow) && $app->dashboard_image_box_shadow === '1') ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= (isset($app->dashboard_image_box_shadow) && $app->dashboard_image_box_shadow === '0') ? 'selected' : '' ?>>No</option>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Visibility</label>
                            <div class="select">
                                <select name="dashboard_image_visibility" class="form-control setting-dashboard_image setting-dashboard_image_visibility" data-placeholder="Select a visibility">
                                    <option value="block" <?= (isset($app->dashboard_image_visibility) && $app->dashboard_image_visibility === 'block') ? 'selected' : '' ?>>Show</option>
                                    <option value="none" <?= (isset($app->dashboard_image_visibility) && $app->dashboard_image_visibility === 'none') ? 'selected' : '' ?>>Hide</option>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($app->dashboard_image_source) && !$app->is_mobile) : ?>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Preview</label>
                                <div style="text-align: center;">
                                    <img src="<?= base_url($app->dashboard_image_source) ?>" class="setting-preview-dashboard_image img-home" />
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <small class="form-text text-muted">
                    Fields with red stars (<label required></label>) are required.
                </small>

                <div class="row" style="margin-top: 2rem;">
                    <div class="col col-md-3 col-lg-2">
                        <button class="btn btn--raised btn-primary btn--icon-text btn-block page-action-save-dashboard_image spinner-action-button">
                            Simpan Perubahan
                            <div class="spinner-action"></div>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>