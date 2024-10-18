<?php include_once(APPPATH . 'views/modal_embed.php') ?>

<section id="diklat">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <div class="table-action">
                <div class="buttons">
                    <a href="<?php echo base_url('diklat/input') ?>" modal-id="modal-form-diklat" class="btn btn--raised btn-primary btn--icon-text x-load-modal-partial">
                        <i class="zmdi zmdi-plus"></i> Buat Baru
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table-diklat" class="table table-bordered table-hover">
                    <thead class="thead-default">
                        <tr>
                            <th width="100">No</th>
                            <th>Nama Pelatihan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Total Peserta</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>