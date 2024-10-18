<div class="modal fade" id="modal-view-demosimutasi" data-backdrop="static" data-keyboard="false">
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
                            <div class="form-control"><?= @$demosimutasi->kategori ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Pegawai</label>
                            <div class="form-control"><?= @$demosimutasi->nrp . ' / ' . @$demosimutasi->nama_lengkap ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>No. SK</label>
                            <div class="form-control"><?= @$demosimutasi->no_sk ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Tanggal SK</label>
                            <div class="form-control"><?= @$demosimutasi->tanggal_sk ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>No. SKPPJ</label>
                            <div class="form-control"><?= @$demosimutasi->no_skppj ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>DOJ</label>
                            <div class="form-control"><?= @$demosimutasi->doj ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="x-section-group mb-3 mt-2">
                            <span class="x-section-group-title">Sebelumnya</span>
                            <div class="x-section-group-body">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <div class="form-control"><?= @$demosimutasi->old_nama_unit ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Sub Unit</label>
                                    <div class="form-control"><?= @$demosimutasi->old_nama_sub_unit ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <div class="form-control"><?= @$demosimutasi->old_nama_jabatan ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Tenaga Unit</label>
                                    <div class="form-control"><?= @$demosimutasi->old_nama_tenaga_unit ?>&nbsp;</div>
                                </div>
                                <div class="form-group mb-0">
                                    <label>Status Kerja</label>
                                    <div class="form-control"><?= @$demosimutasi->old_nama_jenis_pegawai ?>&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="x-section-group mb-3 mt-2">
                            <span class="x-section-group-title">Baru</span>
                            <div class="x-section-group-body">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <div class="form-control"><?= @$demosimutasi->new_nama_unit ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Sub Unit</label>
                                    <div class="form-control"><?= @$demosimutasi->new_nama_sub_unit ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <div class="form-control"><?= @$demosimutasi->new_nama_jabatan ?>&nbsp;</div>
                                </div>
                                <div class="form-group">
                                    <label>Tenaga Unit</label>
                                    <div class="form-control"><?= @$demosimutasi->new_nama_tenaga_unit ?>&nbsp;</div>
                                </div>
                                <div class="form-group mb-0">
                                    <label>Status Kerja</label>
                                    <div class="form-control"><?= @$demosimutasi->new_nama_jenis_pegawai ?>&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn--icon-text demosimutasi-action-cancel" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>