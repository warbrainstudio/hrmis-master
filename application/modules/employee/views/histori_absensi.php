<div class="table-action">
    <div class="buttons">
        <button class="btn btn-sm btn-success" onclick="window.location.href='<?= base_url('absen/excel_pegawai/?ref=cxsmi&absen_pegawai_id='.@$pegawai->absen_pegawai_id) ?>'">
            <i class="zmdi zmdi-download"></i> Download Data Absen (Excel)
        </button>
    </div>
</div>
<table id="table-histori-absensi" class="table table-bordered">
    <thead class="thead-default">
    <tr>
        <th width="100">No</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Status</th>
        <th>Verifikasi</th>
        <th>Mesin</th>
        <th width="170" class="text-center">Option</th>
    </tr>
    </thead>
</table>