<?php require_once(APPPATH . 'modules/_cssInject/main.css.php') ?>
<section id="cuti">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>
            <?php if ($this->session->userdata('user')['role'] === 'Administrator') : ?>
            <div class="table-action">
                <div class="buttons">
                    <a href="<?php echo base_url('cuti/input') ?>" modal-id="modal-form-cuti" class="btn btn--raised btn-primary btn--icon-text x-load-modal-partial cuti-add">
                        <i class="zmdi zmdi-plus"></i> Buat Cuti
                    </a>
                </div>
            </div>
            <?php endif ?>
            <div class="table-responsive">
            <?php if ($this->session->userdata('user')['role'] === 'Administrator') : ?>
                <table id="table-cuti" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="30">No</th>
                            <th>Pegawai</th>
                            <th>Cuti</th>
                            <th>Persetujuan 1</th>
                            <th>Persetujuan 2</th>
                            <th>Persetujuan 3</th>
                            <th>Status</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            <?php else : ?>
                <table id="table-cuti-single" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="30">No</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Pegawai</th>
                            <th>Cuti</th>
                            <th>Dimulai</th>
                            <th>Berakhir</th>
                            <th>Bekerja</th>
                            <th>Status</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            <?php endif ?>
            </div>
        </div>
    </div>
    <br>
</section>