<?php require_once(APPPATH . 'modules/_cssInject/main.css.php') ?>
<section id="absen">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>
            <?php include_once('form.php') ?>
            <div class="mt-3">
                <?php include_once(APPPATH . 'modules/_component/filter.report.grid.php') ?>
            </div>
            <small class="form-text text-muted">(<label required></label>)Secara default menampilkan absensi periode bulan ini</small>
            <a href="<?php echo base_url('absen') ?>" class="btn btn-sm btn-success" title="Reset filter">
                <i class="zmdi zmdi-refresh"></i>Refresh filter
            </a>
            <div class="table-responsive">
                <table id="table-absen" class="table table-bordered">
                <thead class="thead-default">
                    <tr>
                        <th width="100">No</th>
                        <th width="110">Tanggal</th>
                        <th>Pegawai</th>
                        <th>Unit</th>
                        <th>Sub Unit</th>
                        <th>Masuk</th>
                        <th>Verifikasi</th>
                        <th>Pulang</th>
                        <th>Verifikasi</th>
                        <th>Jam Kerja</th>
                        <th>Shift</th>
                        <th width="170" class="text-center">Option</th>
                    </tr>
                </thead>
                </table>
            </div>
        </div>
    </div>
</section>