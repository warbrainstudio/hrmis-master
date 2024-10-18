<?php include_once(APPPATH . 'views/modal_embed.php') ?>

<section id="pembinaan" class="w-100">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <div class="table-action">
                <div class="buttons">
                    <a href="<?php echo base_url('pembinaan/input') ?>" modal-id="modal-form-pembinaan" class="btn btn--raised btn-primary btn--icon-text x-load-modal-partial">
                        <i class="zmdi zmdi-plus"></i> Buat Baru
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table-pembinaan" class="table table-bordered table-hover display nowrap" style="width: 100%;">
                    <thead class="thead-default">
                        <tr>
                            <th width="70">No</th>
                            <th>No. Pembinaan</th>
                            <th width="170">NRP</th>
                            <th>Nama Lengkap</th>
                            <th width="130">Kategori</th>
                            <th width="170">Created At</th>
                            <th width="100" style="text-align: center;">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>