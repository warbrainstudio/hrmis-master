<section id="setting">
    <div class="card">
        <div class="card-body">

            <form id="form-setting-application" enctype="multipart/form-data" autocomplete="off">
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
                            <label required>App Name</label>
                            <input type="text" name="app_name" class="form-control setting-app_name" placeholder="App Name" value="<?php echo (isset($app->app_name)) ? $app->app_name : '' ?>" />
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>App Version</label>
                            <input type="text" name="app_version" class="form-control setting-app_version" placeholder="App Version" value="<?php echo (isset($app->app_version)) ? $app->app_version : '' ?>" />
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Template Backend</label>
                            <div class="select">
                                <select name="template_backend" class="form-control setting-template_backend" data-placeholder="Select a color">
                                    <?php
                                    $colors = array(
                                        // 'material_admin' => 'Material Admin 2.6',
                                        'sb_admin' => 'SB Admin Pro',
                                    );
                                    foreach ($colors as $key => $item) {
                                        $isSelected = ($key == $app->template_backend) ? 'selected' : '';
                                        echo '<option value="' . $key . '" ' . $isSelected . '>' . $item . '</option>';
                                    };
                                    ?>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="form-group">
                            <label required>Theme Color</label>
                            <div class="select">
                                <select name="theme_color" class="form-control setting-theme_color" data-placeholder="Select a color">
                                    <?php
                                    $colors = array(
                                        'green' => 'Green',
                                        'blue' => 'Blue',
                                        'red' => 'Red',
                                        'orange' => 'Orange',
                                        'teal' => 'Teal',
                                        'cyan' => 'Cyan',
                                        'blue-grey' => 'Blue Grey',
                                        'purple' => 'Purple',
                                        'indigo' => 'Indigo',
                                        'brown' => 'Brown'
                                    );
                                    foreach ($colors as $key => $item) {
                                        $isSelected = ($key == $app->theme_color) ? 'selected' : '';
                                        echo '<option value="' . $key . '" ' . $isSelected . '>' . $item . '</option>';
                                    };
                                    ?>
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <small class="form-text text-muted">
                    Fields with red stars (<label required></label>) are required.
                </small>

                <div class="row" style="margin-top: 2rem;">
                    <div class="col col-md-3 col-lg-2">
                        <button class="btn btn--raised btn-primary btn--icon-text btn-block page-action-save-application spinner-action-button">
                            Simpan Perubahan
                            <div class="spinner-action"></div>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>