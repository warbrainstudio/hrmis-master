<section id="absen">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
            <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>

            <div class="table-action">
                <!--<div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label required>Tanggal Lahir</label>
                        <input type="text" name="tanggal_lahir" class="form-control flatpickr-date bg-white employee-tanggal_lahir" placeholder="Tanggal Lahir" required readonly value="<?= @$pegawai->tanggal_lahir ?>" />
                     </div>
                </div>-->
                <div class="buttons">
                    <button class="btn btn--raised btn-primary btn--icon-text absen-getdata-today">
                        <i class="zmdi zmdi-search"></i>Data Hari : <input type="text" name="tanggal" class="form-control flatpickr-date bg-white absen-tanggal" placeholder="Tanggal" />
                    </button>
                    <button class="btn btn--raised btn-secondary btn--icon-text absen-getdata-month">
                        <i class="zmdi zmdi-search"></i>Data Bulan : <input type="text" name="bulan" class="form-control flatpickr-date bg-white absen-bulan" placeholder="Bulan" />
                    </button>
                    <!--<button class="btn btn--raised btn-dark btn--icon-text absen-getdata-year">
                    <i class="zmdi zmdi-download"></i>Data Tahun : <input type="text" name="tahun" class="form-control flatpickr-date bg-white absen-tahun" placeholder="tahun" />
                    </button>-->
                </div>
                <br>
                <div class="exportbutton">
                    <button class="btn btn--raised btn-success btn--icon-text absen-exportdata"><i class="zmdi zmdi-download"></i>Download Data</button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-absen" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="100">No</th>
                            <th>ID ABSEN</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Verifikasi</th>
                            <th>Mesin</th>
                            <!--<th width="170" class="text-center">Option</th>-->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>