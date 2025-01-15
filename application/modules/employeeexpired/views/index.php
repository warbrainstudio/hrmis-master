<?php require_once(APPPATH . 'modules/_cssInject/main.css.php') ?>
<section id="employeeexpired" class="w-100">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <div class="mt-3">
                <?php include_once(APPPATH . 'modules/_component/filter.report.grid.php') ?>
            </div>

            <div class="table-responsive">
                <table id="table-expired" class="table table-bordered table-hover display nowrap" style="width: 100%;">
                    <thead class="thead-default">
                        <tr>
                            <th>No</th>
                            <th>NRP</th>
                            <th>Nama Lengkap</th>
                            <th>Unit</th>
                            <th>Sub Unit</th>
                            <th>Jenis Kontrak</th>
                            <th width="170">EOC</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>