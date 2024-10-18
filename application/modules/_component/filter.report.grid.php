<!--
    How to use? Set variable in controller, include in views and give a javascript logic

    1. Set in the $data variable, set value with TRUE or FALSE, or delete if not use
    // Init combo list
    $cxfilter__list_static = '<option value="all">--Semua--</option>';
    $cxfilter__combo_store = $this->init_list($this->ModelName->getAll(), 'key', 'label', 'all', $cxfilter__list_static);
    // Set payload
    'cx_filter' => array(
        'component' => array(
          array(
            'type' => 'text',
            'name' => 'input_text',
            'label' => 'Input Text',
          ),
          array(
            'type' => 'date',
            'name' => 'input_date',
            'label' => 'Input Date',
          ),
          array(
            'type' => 'combo',
            'name' => 'input_combo',
            'label' => 'Input Combo',
            'store' => $cxfilter__combo_store,
          ),
        ),
        'cxfilter__submit_filter' => true,
        'cxfilter__submit_xlsx' => true,
        'cxfilter__submit_simple_xlsx' => true,
    )
    2. Include this file with php function : include_once(APPPATH . 'modules/_component/filter.report.grid.php')
    3. Create function and give your logic in javascript module file
        - handleCxFilter_submit();
        - handleCxFilter_xlsx();
        - handleCxFilter_xlsxSimple();
        To retrieve parameters according to form fields, use function handleCxFilter_getParams() this will return the query param as string
        To set visibilty export button, use function handleCxFilter_setXlsx("dataTableId") and place in drawCallback() dataTables event
-->

<div class="card card-icon card-collapsable">
    <div class="row no-gutters">
        <?php if (!$is_mobile) : ?>
            <div class="col-auto card-icon-aside bg-primary">
                <i data-feather="filter" class="text-white"></i>
            </div>
        <?php endif ?>
        <div class="col">
            <div id="collapseCardCxFilter">
                <div class="card-body pb-3">
                    <div class="row">
                        <?php if (isset($cx_filter['component'])) : ?>
                            <?php foreach ($cx_filter['component'] as $index => $cmp) : ?>
                                <?php if ($cmp['type'] === 'text') : ?>
                                    <!-- Text -->
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group row mb-2">
                                            <label class="control-label col-xs-12 col-md-3" style="align-self: center; <?= (!$is_mobile) ? 'text-align: left;' : '' ?>"><?= $cmp['label'] ?></label>
                                            <div class="col-xs-12 col-md-8">
                                                <input type="text" name="cx_filter[<?= $cmp['name'] ?>]" class="form-control cx__filter-textfield" placeholder="<?= $cmp['label'] ?>" use-param="1" />
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($cmp['type'] === 'date') : ?>
                                    <!-- Date -->
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group row mb-2">
                                            <label class="control-label col-xs-12 col-md-3" style="align-self: center; <?= (!$is_mobile) ? 'text-align: left;' : '' ?>"><?= $cmp['label'] ?></label>
                                            <div class="col-xs-12 col-md-8">
                                                <input type="text" name="cx_filter[<?= $cmp['name'] ?>]" class="form-control cx__filter-textfield flatpickr-date-range bg-white" data-prefix="cx__filter-<?= $cmp['name'] ?>" placeholder="<?= $cmp['label'] ?>" />
                                                <!-- Temp field -->
                                                <input type="hidden" name="cx_filter[<?= $cmp['name'] ?>_start]" class="cx__filter-<?= $cmp['name'] ?>_start" use-param="1" readonly />
                                                <input type="hidden" name="cx_filter[<?= $cmp['name'] ?>_end]" class="cx__filter-<?= $cmp['name'] ?>_end" use-param="1" readonly />
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($cmp['type'] === 'combo') : ?>
                                    <!-- Combo -->
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group row mb-2">
                                            <label class="control-label col-xs-12 col-md-3" style="align-self: center; <?= (!$is_mobile) ? 'text-align: left;' : '' ?>"><?= $cmp['label'] ?></label>
                                            <div class="col-xs-12 col-md-8">
                                                <div class="cx__filter-combo-wrapper">
                                                    <select name="cx_filter[<?= $cmp['name'] ?>]" class="form-control cx__filter-combo select2" use-param="1">
                                                        <?= (isset($cmp['store']) && count($cmp['store']) > 0) ? $cmp['store'] : '<option value="all">--Semua--</option>' ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                    <div class="form-group row mb-2 mt-2 <?= ($is_mobile) ? 'text-center' : '' ?>">
                        <div class="col-xs-12 col-md-12 border-top pt-3">
                            <?php if (isset($cx_filter['cxfilter__submit_filter']) && $cx_filter['cxfilter__submit_filter'] === true) : ?>
                                <button class="btn btn--raised btn-primary btn--icon-text rounded cx__filter-submit page-action-filter" onclick="handleCxFilter_submit()">
                                    <i class="zmdi zmdi-filter-list"></i> Filter
                                </button>
                            <?php endif ?>
                            <?php if (isset($cx_filter['cxfilter__submit_xlsx']) && $cx_filter['cxfilter__submit_xlsx'] === true) : ?>
                                <button class="btn btn--raised btn-warning btn--icon-text rounded cx__filter-submit page-action-xlsx" onclick="handleCxFilter_xlsx()" style="display: none;">
                                    <i class="zmdi zmdi-download"></i> Unduh Excel
                                </button>
                            <?php endif ?>
                            <?php if (isset($cx_filter['cxfilter__submit_simple_xlsx']) && $cx_filter['cxfilter__submit_simple_xlsx'] === true) : ?>
                                <button class="btn btn--raised btn-warning btn--icon-text rounded cx__filter-submit page-action-xlsx" onclick="handleCxFilter_xlsxSimple()" style="display: none;">
                                    <i class="zmdi zmdi-download"></i> Unduh Excel (Simpel)
                                </button>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>