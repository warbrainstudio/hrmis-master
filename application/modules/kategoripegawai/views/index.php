<section id="kategoripegawai">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <div class="table-action">
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text kategoripegawai-action-add" data-toggle="modal" data-target="#modal-form-kategoripegawai">
                        <i class="zmdi zmdi-plus-circle"></i> Buat Baru
                    </button>
                </div>
            </div>

            <?php include_once('form.php') ?>

            <div class="table-responsive">
                <table id="table-kategoripegawai" class="table table-bordered table-hover">
                    <thead class="thead-default">
                        <tr>
                            <th width="100">No</th>
                            <th>Nama Kategori Pegawai</th>
                            <th>Masa Kerja Golongan</th>
                            <th width="170" class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>