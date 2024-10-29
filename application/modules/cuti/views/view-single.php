<div class="modal fade" id="modal-view-cuti-single" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title.". Tanggal Pengajuan : ".@$cuti->tanggal_pengajuan : 'Rincian' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <div class="x-section-group mb-3 mt-2">
                    <span class="x-section-group-title">Data Pegawai</span>
                    <div class="x-section-group-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Pegawai</label>
                                    <div class="form-control"><?= @$cuti->nama_lengkap ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>NRP</label>
                                    <div class="form-control"><?= @$cuti->nrp ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Kategori Pegawai</label>
                                    <div class="form-control"><?= @$cuti->nama_kategori_pegawai ?>&nbsp;</div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <div class="form-control"><?= @$cuti->nama_unit ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Sub Unit</label>
                                    <div class="form-control"><?= @$cuti->nama_sub_unit ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <div class="form-control"><?= @$cuti->nama_jabatan ?>&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="x-section-group mb-3 mt-2">
                    <span class="x-section-group-title">Data Cuti</span>
                    <div class="x-section-group-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Alasan cuti</label>
                                    <div class="form-control"><?= @$cuti->jenis_cuti ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>No. Telepon saat cuti</label>
                                    <div class="form-control"><?= (@$cuti->telepon_cuti == '') ? "<label required></label> ".@$cuti->no_hp : @$cuti->telepon_cuti ?>&nbsp;</div>
                                    </div>
                                <div class="form-group">
                                    <label>Alamat saat cuti</label>
                                    <div class="form-control"><?= (@$cuti->alamat_cuti == '') ? "<label required></label> ".@$cuti->alamat_ktp : @$cuti->alamat_cuti ?>&nbsp;</div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Dimulai dari</label>
                                    <div class="form-control"><?= @$cuti->awal_cuti ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Sampai dengan</label>
                                    <div class="form-control"><?= @$cuti->akhir_cuti ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Bekerja kembali</label>
                                    <div class="form-control"><?= @$cuti->tanggal_bekerja ?>&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Data with red stars (<label required></label>) are from data pegawai.
                    </small>
                </div>
                <div class="x-section-group mb-3 mt-2">
                    <span class="x-section-group-title">Daftar Persetujuan</span>
                    <div class="x-section-group-body">
                        <div class="row">
                            <div class="form-group">
                                <label>Status Persetujuan</label>
                                <div class="form-control"><?= ($status_persetujuan == '') ? 'Menunggu persetujuan' : $status_persetujuan ?>&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
            <?php if($status_persetujuan == '' || $status_persetujuan == 'Ditolak' && @$cuti->status_persetujuan == "Dipertimbangkan") : ?>
                <form id="form-persetujuan" autocomplete="off">
                <input type="hidden" name="ref" value="<?= @$cuti->id ?>" readonly />
                <input type="hidden" name="nama" value="<?= @$cuti->nama_lengkap ?>" />
                <input type="hidden" name="persetujuan" />
                </form>
                <button type="button" class="btn btn-success btn--icon-text cuti-action-setuju">
                    <i class="zmdi zmdi-check"></i> Setuju
                </button>
                <button type="button" class="btn btn-danger btn--icon-text cuti-action-tolak">
                    <i class="zmdi zmdi-check"></i> Tolak
                </button>
            <?php endif ?>
                <button type="button" class="btn btn-light btn--icon-text cuti-action-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>