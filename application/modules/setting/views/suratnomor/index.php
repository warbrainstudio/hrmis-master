<section id="setting">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-xs-10 col-md-10">
                    <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
                    <h6 class="card-subtitle"><?php echo (isset($card_subTitle)) ? $card_subTitle : '' ?></h6>
                    <div class="clear-card"></div>
                </div>
            </div>
            <div class="clear-card"></div>

            <form id="form-suratnomor" enctype="multipart/form-data" autocomplete="off">
                <!-- CSRF -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                <div class="row">
                    <div class="col-xs-10 col-md-6">
                        <div class="mb-3" style="border: 1px solid #c5ccd6; padding: 8px 10px;">
                            Parameter :
                            <table class="table table-sm table-bordered mt-3 mb-0">
                                <tr>
                                    <td>
                                        <b class="text-primary">{INC}</b>
                                        <p class="m-0">
                                            Auto increment, nomor akan ditambah 1 mengikuti format yang sama.<br />
                                            <small class="text-muted">Contoh: 0001, 0002, etc...</small>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b class="text-primary">{MONTH}</b>
                                        <p class="m-0">
                                            Menampilkan urutan bulan dalam format romawi. <br />
                                            <small class="text-muted">Contoh: I, II, III, etc...</small>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b class="text-primary">{MONTH_NUM}</b>
                                        <p class="m-0">
                                            Menampilkan urutan bulan dalam format nomor. <br />
                                            <small class="text-muted">Contoh: 1, 2, 3, etc...</small>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b class="text-primary">{YEAR}</b>
                                        <p class="m-0">
                                            Menampilkan tahun dalam 4 digit. <br />
                                            <small class="text-muted">Contoh: <?= date('Y') ?></small>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b class="text-primary">{YEAR_2}</b>
                                        <p class="m-0">
                                            Menampilkan tahun dalam 2 digit. <br />
                                            <small class="text-muted">Contoh: <?= date('y') ?></small>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-10 col-md-6">
                        <!-- Tambahkan baris dibawah ini untuk post format nomor lainnya -->
                        <!-- Nomor List -->
                        <div class="form-group">
                            <label>Pembelian Persediaan (KAH)</label>
                            <input type="text" name="pembelian_persediaan" class="form-control setting-pembelian_persediaan" placeholder="Format Nomor" value="<?php echo (!is_null($nomor_po)) ? $nomor_po->format_nomor : '' ?>" />
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <label>Pembelian Persediaan (RSJK)</label>
                            <input type="text" name="pembelian_persediaan_rsjk" class="form-control setting-pembelian_persediaan_rsjk" placeholder="Format Nomor" value="<?php echo (!is_null($nomor_po_rsjk)) ? $nomor_po_rsjk->format_nomor : '' ?>" />
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <label>Mutasi</label>
                            <input type="text" name="mutasi" class="form-control setting-mutasi" placeholder="Format Nomor" value="<?php echo (!is_null($nomor_mutasi)) ? $nomor_mutasi->format_nomor : '' ?>" />
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <label>Retur Pembelian</label>
                            <input type="text" name="retur_pembelian" class="form-control setting-retur_pembelian" placeholder="Format Nomor" value="<?php echo (!is_null($nomor_retur_pembelian)) ? $nomor_retur_pembelian->format_nomor : '' ?>" />
                            <i class="form-group__bar"></i>
                        </div>
                        <!-- END ## Nomor List -->

                        <small class="form-text text-muted">
                            Fields with red stars (<label required></label>) are required.
                        </small>

                        <div class="mt-3">
                            <button class="btn btn--raised btn-primary btn--icon-text btn-block page-action-save spinner-action-button">
                                Simpan Perubahan
                                <div class="spinner-action"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</section>