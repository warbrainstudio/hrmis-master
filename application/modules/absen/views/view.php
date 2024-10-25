<section id="absen">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>
            <div class="col-xs-12 col-sm-9">
                <div class="row">
                    <div class="table-action">
                        <div class="buttons">
                            <?php if($isDaily=='true') : ?>
                            <button class="btn btn-sm btn-primary absen-action-add">
                                <i class="zmdi zmdi-cloud-download"></i> Update Data Absen
                            </button>
                            <?php endif ?>
                            <button class="btn btn-sm btn-success absen-export">
                            <i class="zmdi zmdi-download"></i> Download Data (Excel)
                            </button>
                            <button class="btn btn-sm btn-dark absen-backButton">
                            <i class="zmdi zmdi-long-arrow-return"></i> Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane active fade show" id="tab-absen_periode" role="tabpanel">
                <div class="pt-4">
                    <?php require_once(APPPATH . 'modules/absen/views/absen_periode.php') ?>
                </div>
            </div>
        </div>
    </div>
</section>