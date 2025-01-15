<?php require_once(APPPATH . 'modules/_cssInject/main.css.php') ?>

<section id="employee" class="w-100">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <div class="table-action">
                <div class="buttons">
                    <a href="<?php echo base_url('employee/input') ?>" class="btn btn--raised btn-primary btn--icon-text x-load-partial">
                        <i class="zmdi zmdi-plus"></i> Buat Baru
                    </a>
                    <button class="btn btn--raised btn-dark btn--icon-text" data-toggle="modal" data-target="#modal-form-import">
                        <i class="zmdi zmdi-file-plus"></i> Import
                    </button>
                </div>
            </div>

            <div class="mt-3">
                <?php include_once(APPPATH . 'modules/_component/filter.report.grid.php') ?>
            </div>

            <div class="table-responsive">
                <table id="table-employee" class="table table-bordered table-hover display nowrap" style="width: 100%;">
                    <thead class="thead-default">
                        <tr>
                            <th width="70">No</th>
                            <th width="170">NRP</th>
                            <th>Nama Lengkap</th>
                            <th>Unit</th>
                            <th>Jabatan</th>
                            <th width="70">Status</th>
                            <th width="170">Created At</th>
                            <th width="100" style="text-align: center;">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include_once('form_import.php') ?>