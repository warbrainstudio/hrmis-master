<?php require_once(APPPATH . 'modules/_cssInject/main.css.php') ?>
<section id="kalenderabsen">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>
            <div class="col-xs-12 col-sm-9">
                <div class="row">
                    <div class="table-action">
                        
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <?php include_once(APPPATH . 'modules/_component/filter.report.grid.php') ?>
            </div>
            <div class="tab-pane active fade show" id="tab-absen_periode" role="tabpanel">
                <div class="pt-4">
                    <?php require_once(APPPATH . 'modules/kalenderabsen/views/absen_periode.php') ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                <div class="row">
                    <div class="table-action">
                        <div class="buttons">
                            <a href="<?= base_url('kalenderabsen') ?>" class="btn btn--raised btn-dark btn--icon-text btn-custom">
                                <i class="zmdi zmdi-chevron-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>