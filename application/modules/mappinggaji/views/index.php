<section id="mappinggaji">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <div class="table-action">
                <div class="buttons">
                    <a href="<?php echo base_url('mappinggaji/input') ?>" modal-id="modal-form-mappinggaji" class="btn btn--raised btn-primary btn--icon-text x-load-modal-partial">
                        <i class="zmdi zmdi-plus"></i> Buat Baru
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table-mappinggaji" class="table table-bordered table-hover">
                    <thead class="thead-default">
                        <tr>
                            <th width="100">No</th>
                            <th>Unit</th>
                            <th>Sub Unit</th>
                            <th>Jabatan</th>
                            <th>Jenis Pegawai</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>