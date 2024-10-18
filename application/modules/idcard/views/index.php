<?php require_once(APPPATH . 'modules/_cssInject/main.css.php') ?>

<style type="text/css">
    .draggable-area {
        background-color: #f2f2f2;
        width: 300px;
        height: 500px;
        border: 1px solid #f2f2f2;
    }

    .draggable-area img {
        width: 100px;
        height: 100px;
    }

    .draggable {
        position: relative;
        cursor: move;
    }

    .element-item-icon {
        display: none;
    }
</style>

<section id="setting">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xs-10 col-md-10">
                    <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
                    <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>
                    <div class="clear-card"></div>
                </div>
            </div>
            <div class="clear-card"></div>

            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 mb-3">
                            <ul class="list-group">
                                <li class="list-group-item active"><b>Background</b></li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-2">Image</label>
                                        <div class="col-xs-12 col-sm-10">
                                            <input type="file" class="form-control form-control-sm attributeFormBacgkround attributeFormBacgkround-src" data-key="src" />
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-2">Width</label>
                                        <div class="col-xs-12 col-sm-10">
                                            <input type="text" class="form-control form-control-sm attributeFormBacgkround attributeFormBacgkround-width" data-key="width" value="<?= @$config['background']['width'] ?>" />
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-2">Height</label>
                                        <div class="col-xs-12 col-sm-10">
                                            <input type="text" class="form-control form-control-sm attributeFormBacgkround attributeFormBacgkround-height" data-key="height" value="<?= @$config['background']['height'] ?>" />
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4 mb-3">
                            <ul class="list-group">
                                <li class="list-group-item active"><b>Element</b></li>
                                <?php if (count($config) > 0) : ?>
                                    <?php foreach ($config as $index => $item) : ?>
                                        <?php if (@$index !== 'background') : ?>
                                            <a href="javascript:;" class="list-group-item list-group-item-action element-item element-item-<?= $index ?>" data-key="<?= $index ?>" data-type="<?= @$item['type'] ?>">
                                                <?= @$item['text'] ?>
                                                <span class="zmdi zmdi-swap float-right element-item-icon element-item-icon-<?= $index ?>"></span>
                                            </a>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php else : ?>
                                    <li class="list-group-item text-muted">No data available</li>
                                <?php endif ?>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-8 mb-3">
                            <ul class="list-group attribute-info">
                                <li class="list-group-item active"><b>Attribute</b> <span class="attribute-active"></span></li>
                                <li class="list-group-item">
                                    <div class="text-center mt-5 mb-5">
                                        <span class="zmdi zmdi-info text-muted" style="font-size: 48px;"></span>
                                        <p class="text-muted">Select an element to load the form</p>
                                    </div>
                                </li>
                            </ul>
                            <ul class="list-group attribute-form" style="display: none;">
                                <li class="list-group-item active"><b>Attribute</b> <span class="attribute-active"></span></li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Width</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control form-control-sm attributeForm attributeForm-width" data-key="width" />
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Height</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control form-control-sm attributeForm attributeForm-height" data-key="height" />
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item atrribute-label">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Font Size</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control form-control-sm attributeForm attributeForm-font-size" data-key="font-size" />
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item atrribute-label">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Text Style : <i>Italic</i></label>
                                        <div class="col-xs-12 col-sm-8">
                                            <div class="form-check">
                                                <input class="form-check-input attributeForm attributeForm-font-style" type="checkbox" value="1" data-key="font-style">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item atrribute-label">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Text Style : <b>Bold</b></label>
                                        <div class="col-xs-12 col-sm-8">
                                            <div class="form-check">
                                                <input class="form-check-input attributeForm attributeForm-font-weight" type="checkbox" value="1" data-key="font-weight">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item atrribute-label">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Background Color</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control form-control-sm attributeForm attributeForm-background-color" data-key="background-color" />
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item atrribute-label">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Color</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control form-control-sm attributeForm attributeForm-color" data-key="color" />
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item atrribute-label">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Text Align</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <select class="form-select form-select-sm attributeForm attributeForm-text-align" style="height: 31px;" data-key="text-align">
                                                <option value="start">Start</option>
                                                <option value="center" selected>Center</option>
                                                <option value="justify">Justify</option>
                                                <option value="end">End</option>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item atrribute-image">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Object Fit</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <select class="form-select form-select-sm attributeForm attributeForm-object-fit" style="height: 31px;" data-key="object-fit">
                                                <option value="contain">Contain</option>
                                                <option value="cover" selected>Cover</option>
                                                <option value="fill">Fill</option>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label class="col-xs-12 col-sm-4">Is Active</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <div class="form-check">
                                                <input class="form-check-input attributeForm attributeForm-active" type="checkbox" value="1" data-key="active">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="draggable-area">
                        <?php if (count($config) > 0) : ?>
                            <?php foreach ($config as $index => $item) : ?>
                                <?php if (@$item['type'] === 'label') : ?>
                                    <div class="draggable draggable-<?= $index ?>" data-key="<?= $index ?>" style="display: <?= (@$item['active'] == 1) ? 'inherit' : 'none' ?>"><?= @$item['text'] ?></div>
                                <?php elseif (@$item['type'] === 'image' && @$index !== 'background') : ?>
                                    <img src="<?= base_url(@$item['src']) ?>" class="draggable draggable-<?= $index ?>" data-key="<?= $index ?>" style="display: <?= (@$item['active'] == 1) ? 'inherit' : 'none' ?>" />
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="buttons-container">
                <button class="btn btn--raised btn-primary btn--icon-text btn-custom action-save">
                    <i class="zmdi zmdi-save"></i> Simpan Perubahan
                </button>
                <a href="<?= base_url('idcard/preview') ?>" modal-id="modal-view-idcard" class="btn btn-dark x-load-modal-partial" title="Test"><i class="zmdi zmdi-print"></i> Pratinjau</a>
            </div>
        </div>
    </div>
</section>