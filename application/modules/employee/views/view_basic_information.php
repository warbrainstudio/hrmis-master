<div class="row">
    <div class="col-xs-12 col-sm-6">
        <div class="x-section-group mb-4">
            <span class="x-section-group-title">Pribadi</span>
            <div class="x-section-group-body">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <div class="form-control"><?= @$pegawai->nama_lengkap ?>&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <div class="form-control"><?= @$pegawai->tempat_lahir ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <div class="form-control"><?= @$pegawai->tanggal_lahir ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <div class="form-control"><?= @$pegawai->jenis_kelamin ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Status Kawin</label>
                            <div class="form-control"><?= @$pegawai->status_kawin ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Pendidikan Terakhir</label>
                            <div class="form-control"><?= @$pegawai->pendidikan_terakhir ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>No. HP</label>
                            <div class="form-control"><?= @$pegawai->no_hp ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label>Alamat Lengkap</label>
                    <div class="form-control"><?= @$pegawai->alamat_ktp ?>&nbsp;</div>
                </div>
            </div>
        </div>
        <div class="x-section-group mb-4">
            <span class="x-section-group-title">Pendukung</span>
            <div class="x-section-group-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>NRP</label>
                            <div class="form-control"><?= @$pegawai->nrp ?>&nbsp;</div>
                        </div>
                        <div class="form-group">
                            <label>No. KTP</label>
                            <div class="form-control"><?= @$pegawai->no_ktp ?>&nbsp;</div>
                        </div>
                        <div class="form-group mb-0">
                            <label>No. BPJS Kesehatan</label>
                            <div class="form-control"><?= @$pegawai->no_bpjs_kesehatan ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>No. BPJS TK</label>
                            <div class="form-control"><?= @$pegawai->no_bpjs_tk ?>&nbsp;</div>
                        </div>
                        <div class="form-group">
                            <label>NPWP</label>
                            <div class="form-control"><?= @$pegawai->npwp ?>&nbsp;</div>
                        </div>
                        <div class="form-group mb-0">
                            <label>MCU</label>
                            <div class="form-control"><?= @$pegawai->mcu ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div class="x-section-group mb-4">
            <span class="x-section-group-title">Status Kepegawaian</span>
            <div class="x-section-group-body">
                <div class="form-group">
                    <label>Kategori Pegawai</label>
                    <div class="form-control"><?= @$pegawai->nama_kategori_pegawai ?>&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Status Kerja</label>
                            <div class="form-control"><?= @$pegawai->nama_jenis_pegawai ?>&nbsp;</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Status Kontrak</label>
                            <div class="form-control"><?= @$pegawai->nama_status_kontrak ?>&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Unit</label>
                    <div class="form-control"><?= @$pegawai->nama_unit ?>&nbsp;</div>
                </div>
                <div class="form-group">
                    <label>Sub Unit</label>
                    <div class="form-control"><?= @$pegawai->nama_sub_unit ?>&nbsp;</div>
                </div>
                <div class="form-group">
                    <label>Jabatan</label>
                    <div class="form-control"><?= @$pegawai->nama_jabatan ?>&nbsp;</div>
                </div>
                <div class="form-group">
                    <label>Tenaga Unit</label>
                    <div class="form-control"><?= @$pegawai->nama_tenaga_unit ?>&nbsp;</div>
                </div>
                <div class="form-group mb-0">
                    <label>Status Pegawai</label>
                    <div class="form-control"><?= (@$pegawai->status_active == 1) ? 'Aktif' : 'Tidak Aktif' ?>&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div>