<div class="row">
    <div class="col-xs-12 col-sm-6">
        <div class="x-section-group mb-4">
            <span class="x-section-group-title">Pribadi</span>
            <div class="x-section-group-body">
                <div class="form-group">
                    <label required>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control auto-filled-text employee-nama_lengkap" placeholder="Nama Lengkap" required value="<?= @$pegawai->nama_lengkap ?>" />
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control employee-tempat_lahir" placeholder="Tempat Lahir" value="<?= @$pegawai->tempat_lahir ?>" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label required>Tanggal Lahir</label>
                            <input type="text" name="tanggal_lahir" class="form-control flatpickr-date bg-white employee-tanggal_lahir" placeholder="Tanggal Lahir" required readonly value="<?= @$pegawai->tanggal_lahir ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label required>Jenis Kelamin</label>
                            <div class="select">
                                <select name="jenis_kelamin" class="form-control select2 employee-jenis_kelamin" data-placeholder="Pilih &#8595;" required>
                                    <?= $jenis_kelamin_list ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label required>Status Kawin</label>
                            <div class="select">
                                <select name="status_kawin" class="form-control select2 employee-status_kawin" data-placeholder="Pilih &#8595;" required>
                                    <?= $status_kawin_list ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label required>Pendidikan Terakhir</label>
                            <div class="select">
                                <select name="pendidikan_terakhir" class="form-control select2 employee-pendidikan_terakhir" data-placeholder="Pilih &#8595;" required>
                                    <?= $pendidikan_list ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label required>No. HP</label>
                            <input type="text" name="no_hp" class="form-control employee-no_hp" placeholder="No. HP" value="<?= @$pegawai->no_hp ?>" required />
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label required>Alamat Lengkap</label>
                    <input type="text" name="alamat_ktp" class="form-control employee-alamat_ktp" placeholder="Alamat Lengkap" value="<?= @$pegawai->alamat_ktp ?>" required />
                </div>
            </div>
        </div>
        <div class="x-section-group mb-4">
            <span class="x-section-group-title">Pendukung</span>
            <div class="x-section-group-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label required>NRP</label>
                            <input type="text" name="nrp" class="form-control auto-filled-text employee-nrp" placeholder="NRP" required value="<?= @$pegawai->nrp ?>" />
                        </div>
                        <div class="form-group">
                            <label required>No. KTP</label>
                            <input type="text" name="no_ktp" class="form-control employee-no_ktp" placeholder="No. KTP" required value="<?= @$pegawai->no_ktp ?>" />
                        </div>
                        <div class="form-group mb-0">
                            <label>No. BPJS Kesehatan</label>
                            <input type="text" name="no_bpjs_kesehatan" class="form-control employee-no_bpjs_kesehatan" placeholder="No. BPJS Kesehatan" value="<?= @$pegawai->no_bpjs_kesehatan ?>" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label>No. BPJS TK</label>
                            <input type="text" name="no_bpjs_tk" class="form-control employee-no_bpjs_tk" placeholder="No. BPJS TK" value="<?= @$pegawai->no_bpjs_tk ?>" />
                        </div>
                        <div class="form-group">
                            <label>NPWP</label>
                            <input type="text" name="npwp" class="form-control employee-npwp" placeholder="NPWP" value="<?= @$pegawai->npwp ?>" />
                        </div>
                        <div class="form-group mb-0">
                            <label>MCU</label>
                            <input type="text" name="mcu" class="form-control employee-mcu" placeholder="MCU" value="<?= @$pegawai->mcu ?>" />
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
                    <label required>Kategori Pegawai</label>
                    <div class="select">
                        <select name="kategori_pegawai_id" class="form-control select2 employee-kategori_pegawai_id" data-placeholder="Pilih &#8595;" required>
                            <?= $kategori_pegawai_list ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label required>Status Kerja</label>
                            <div class="select">
                                <select name="jenis_pegawai_id" class="form-control select2 employee-jenis_pegawai_id" data-placeholder="Pilih &#8595;" required>
                                    <?= $jenis_pegawai_list ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label required>Status Kontrak</label>
                            <div class="select">
                                <select name="status_kontrak_id" class="form-control select2 employee-status_kontrak_id" data-placeholder="Pilih &#8595;" required>
                                    <?= $status_kontrak_list ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label required>Unit</label>
                    <div class="select">
                        <select name="unit_id" class="form-control select2 auto-filled-text employee-unit_id" data-placeholder="Pilih &#8595;" required>
                            <?= $unit_list ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label required>Sub Unit</label>
                    <div class="select">
                        <select name="sub_unit_id" class="form-control select2 auto-filled-text employee-sub_unit_id" data-placeholder="Pilih &#8595;" required>
                            <?= $sub_unit_list ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label required>Jabatan</label>
                    <div class="select">
                        <select name="jabatan_id" class="form-control select2 auto-filled-text employee-jabatan_id" data-placeholder="Pilih &#8595;" required>
                            <?= $jabatan_list ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label required>Tenaga Unit</label>
                    <div class="select">
                        <select name="tenaga_unit_id" class="form-control select2 auto-filled-text employee-tenaga_unit_id" data-placeholder="Pilih &#8595;" required>
                            <?= $tenaga_unit_list ?>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label required>Status Pegawai</label>
                    <div class="form-control" style="height: 44.22px;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input employee-status_active-1" type="radio" name="status_active" id="status_active-1" value="1" <?= (@$pegawai->status_active == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status_active-1">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input employee-status_active-0" type="radio" name="status_active" id="status_active-0" value="0" <?= (@$pegawai->status_active == 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status_active-0">Tidak Aktif</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>