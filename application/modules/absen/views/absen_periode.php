<table id="table-absen-periode" class="table table-bordered">
    <thead class="thead-default">
        <tr>
            <th width="100">No</th>
            <?php if($isDaily=='false') : ?>
            <th>Hari</th>
            <?php else : ?>
            <th>ID</th>
            <?php endif ?>
            <th>Pegawai</th>
            <th>Masuk</th>
            <th>Verifikasi</th>
            <th>Pulang</th>
            <th>Verifikasi</th>
            <th>Jam Kerja</th>
            <!--<th width="170" class="text-center">Option</th>-->
        </tr>
    </thead>
</table>