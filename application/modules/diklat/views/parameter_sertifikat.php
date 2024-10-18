<div class="alert alert-warning p-2 mt-3">
    <small>
        <i class="zmdi zmdi-alert-triangle"></i>
        Parameter ini hanya bisa digunakan pada template sertifikat internal.
    </small>
</div>
<table class="table table-sm table-bordered mt-3 mb-0">
    <tr>
        <td>
            <span class="text-primary">${hari_ini}</span>
            <p class="m-0">
                Menampilkan tanggal lengkap hari ini.<br />
                <small class="text-muted">Contoh: <?= date('d M Y') ?></small>
            </p>
        </td>
        <td>
            <span class="text-primary">${jam}</span>
            <p class="m-0">
                Menampilkan jam saat ini.<br />
                <small class="text-muted">Contoh: <?= date('H:i:s') ?></small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nama_hari}</span>
            <p class="m-0">
                Menampilkan nama hari.<br />
                <small class="text-muted">Contoh: <?= $controller->get_day(date('D')) ?></small>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <span class="text-primary">${tanggal}</span>
            <p class="m-0">
                Menampilkan nomor tanggal saat ini.<br />
                <small class="text-muted">Contoh: <?= date('d') ?></small>
            </p>
        </td>
        <td>
            <span class="text-primary">${bulan}</span>
            <p class="m-0">
                Menampilkan nomor bulan saat ini.<br />
                <small class="text-muted">Contoh: <?= date('m') ?></small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nama_bulan}</span>
            <p class="m-0">
                Menampilkan nama bulan saat ini.<br />
                <small class="text-muted">Contoh: <?= $controller->get_month(date('m')) ?></small>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <span class="text-primary">${tahun}</span>
            <p class="m-0">
                Menampilkan tahun saat ini.<br />
                <small class="text-muted">Contoh: <?= date('Y') ?></small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nrp}</span>
            <p class="m-0">
                Menampilkan NRP pegawai.<br />
                <small class="text-muted">Contoh: 01.01.01.0001</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nama_lengkap}</span>
            <p class="m-0">
                Menampilkan nama lengkap pegawai.<br />
                <small class="text-muted">Contoh: Hafiz Maulana Ibrahim</small>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <span class="text-primary">${kode_unit}</span>
            <p class="m-0">
                Menampilkan kode unit pegawai.<br />
                <small class="text-muted">Contoh: 001</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nama_unit}</span>
            <p class="m-0">
                Menampilkan nama unit pegawai.<br />
                <small class="text-muted">Contoh: PT. KAH</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${kode_sub_unit}</span>
            <p class="m-0">
                Menampilkan kode sub unit pegawai.<br />
                <small class="text-muted">Contoh: 001-001-100</small>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <span class="text-primary">${nama_sub_unit}</span>
            <p class="m-0">
                Menampilkan nama sub unit pegawai.<br />
                <small class="text-muted">Contoh: Teknologi Informasi</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${kode_jabatan}</span>
            <p class="m-0">
                Menampilkan kode jabatan pegawai.<br />
                <small class="text-muted">Contoh: 007-100</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nama_jabatan}</span>
            <p class="m-0">
                Menampilkan nama jabatan pegawai.<br />
                <small class="text-muted">Contoh: Direktur</small>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <span class="text-primary">${kode_tenaga_unit}</span>
            <p class="m-0">
                Menampilkan kode tenaga unit pegawai.<br />
                <small class="text-muted">Contoh: T-0001</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nama_tenaga_unit}</span>
            <p class="m-0">
                Menampilkan nama tenaga unit pegawai.<br />
                <small class="text-muted">Contoh: Tenaga Unit 1</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nama_pelatihan}</span>
            <p class="m-0">
                Menampilkan nama pelatihan.<br />
                <small class="text-muted">Contoh: UAT HRMIS Tahap 1</small>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <span class="text-primary">${tanggal_mulai}</span>
            <p class="m-0">
                Menampilkan tanggal mulai pelatihan.<br />
                <small class="text-muted">Contoh: 01 <?= date('M Y') ?></small>
            </p>
        </td>
        <td>
            <span class="text-primary">${tanggal_selesai}</span>
            <p class="m-0">
                Menampilkan tanggal selesai pelatihan.<br />
                <small class="text-muted">Contoh: 03 <?= date('M Y') ?></small>
            </p>
        </td>
        <td>
            <span class="text-primary">${tanggal_pelatihan}</span>
            <p class="m-0">
                Menampilkan tanggal dengan format otomatis (mulai-selesai).<br />
                <small class="text-muted">Contoh: 01 - 03 <?= $controller->get_month(date('m')) . ' ' . date('Y') ?></small>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <span class="text-primary">${tempat_pelatihan}</span>
            <p class="m-0">
                Menampilkan tempat pelatihan.<br />
                <small class="text-muted">Contoh: Bale</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${nomor_sertifikat}</span>
            <p class="m-0">
                Menampilkan nomor sertifikat peserta.<br />
                <small class="text-muted">Contoh: 00001/HRD-DIKLAT/IV/23</small>
            </p>
        </td>
        <td>
            <span class="text-primary">${sebagai}</span>
            <p class="m-0">
                Menampilkan informasi posisi / peserta sebagai.<br />
                <small class="text-muted">Contoh: Peserta</small>
            </p>
        </td>
    </tr>
</table>