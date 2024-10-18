<section id="employeefamily" class="w-100 bg-white">
    <?php if (@$action_route !== 'detail') : ?>
        <div class="table-action">
            <div class="buttons">
                <a href="<?php echo base_url('employeefamily/input?pegawai_id=' . $pegawai_id) ?>" modal-id="modal-form-employeefamily" class="btn btn--raised btn-primary btn--icon-text x-load-modal-partial2">
                    <i class="zmdi zmdi-plus"></i> Buat Baru
                </a>
            </div>
        </div>
    <?php endif ?>
    <div class="table-responsive">
        <table id="table-employeefamily" class="table table-bordered table-hover display nowrap" style="width: 100%;">
            <thead class="thead-default">
                <tr>
                    <th width="70">No</th>
                    <th>Nama Lengkap</th>
                    <th width="200">Hubungan</th>
                    <th width="180">No. HP</th>
                    <th width="100" style="text-align: center;">#</th>
                </tr>
            </thead>
        </table>
    </div>
</section>