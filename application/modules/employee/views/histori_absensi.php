<div class="table-action">
    <div class="buttons">
        <button class="btn btn-sm btn-success" onclick="window.location.href='<?= base_url('kalenderabsen/excel_pegawai/?ref=cxsmi&absen_pegawai_id='.@$pegawai->absen_pegawai_id) ?>'">
            <i class="zmdi zmdi-download"></i> Download Laporan Absen (.xlsx)
        </button>
    </div>
</div>
<table id="table-histori-absensi" class="table table-bordered">
    <thead class="thead-default">
    <tr>
        <th width="100">No</th>
        <th>Tanggal</th>
        <th>Masuk</th>
        <th class="text-center">Verifikasi</th>
        <th>Pulang</th>
        <th class="text-center">Verifikasi</th>
        <th>Jam Kerja</th>
        <th>Jenis Shift</th>
        <!--<th width="170" class="text-center">Option</th>-->
    </tr>
    </thead>
</table>