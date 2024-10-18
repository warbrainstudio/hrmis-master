<div class="modal fade" id="modal-view-skspk" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Rincian' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <div class="form-control"><?= @$skspk->kategori ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <div class="form-control"><?= @$skspk->nama_sk_spk ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Nomor</label>
                            <div class="form-control"><?= @$skspk->no_sk_spk ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Pegawai</label>
                            <div class="form-control"><?= @$skspk->nrp . ' / ' . @$skspk->nama_lengkap ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Unit</label>
                            <div class="form-control"><?= @$skspk->nama_unit ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Sub Unit</label>
                            <div class="form-control"><?= @$skspk->nama_sub_unit ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Jabatan</label>
                            <div class="form-control"><?= @$skspk->nama_jabatan ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Ruangan</label>
                            <div class="form-control"><?= @$skspk->nama_ruangan ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Tanggal Berlaku</label>
                            <div class="form-control"><?= @$skspk->tanggal_berlaku ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-control"><?= (@$skspk->status_active == 1) ? 'Aktif' : 'Tidak Aktif' ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn--icon-text skspk-action-download">
                    <i class="zmdi zmdi-download"></i> Cetak SK / Perijinan
                </button>
                <button type="button" class="btn btn-light btn--icon-text skspk-action-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>