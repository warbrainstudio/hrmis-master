<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends CI_Model
{
    public function getTotalPegawaiAktif()
    {
        return @$this->db->select('COALESCE(COUNT(id), 0) AS total')->get_where('pegawai', ['status_active' => 1])->row()->total;
    }

    public function getTotalPegawaiHabisKontrak()
    {
        return @$this->db->query("
            SELECT COALESCE(COUNT(id), 0) AS total
            FROM kontrak_pegawai
            WHERE (eoc BETWEEN CURRENT_DATE AND (CURRENT_DATE + INTERVAL '3 months')) and status_active = 1
        ")->row()->total;
    }

    public function getTotalDemosiMutasi()
    {
        return @$this->db->select('COALESCE(COUNT(id), 0) AS total')->get_where('demosi_mutasi', ['EXTRACT(year from tanggal_sk) =' => date('Y')])->row()->total;
    }

    public function getTotalDiklat()
    {
        return @$this->db->select('COALESCE(COUNT(id), 0) AS total')->get_where('diklat', ['EXTRACT(year from tanggal_mulai) =' => date('Y')])->row()->total;
    }

    public function getStatisticTingkatPendidikan()
    {
        return @$this->db->query("
            SELECT
                t.pendidikan,
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 1) AS \"001\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 2) AS \"002\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 3) AS \"003\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 4) AS \"004\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 5) AS \"005\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 6) AS \"006\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 7) AS \"007\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 8) AS \"008\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 9) AS \"009\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan AND unit_id = 10) AS \"010\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND pendidikan_terakhir = t.pendidikan) AS \"jumlah\"
            FROM (VALUES ('S3'), ('S2'), ('S1 Profesi'), ('S1'), ('D4'), ('D3'), ('D2'), ('D1'), ('SMA/SMK'), ('SMP'), ('SD')) AS t(pendidikan)
        ")->result();
    }

    public function getStatisticKategoriPegawai()
    {
        return @$this->db->query("
            SELECT
                t.kategori_id,
                t.kategori_colspan,
                t.kategori,
                (
                    CASE
                    WHEN (t.kategori_id in (5, 6, 7, 8)) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND kategori_pegawai_id = t.kategori_id)
                    WHEN (t.kategori_id = -1) THEN (SELECT COALESCE(COUNT(p.id), 0) FROM pegawai p JOIN kategori_pegawai kp ON kp.id = p.kategori_pegawai_id WHERE p.status_active = 1 AND kp.mkg = 'Non NAKES')
                    ELSE 0
                    END
                ) AS \"jumlah\",
                0 AS \"jumlah_hk\",
                0 AS \"jumlah_mk\"
            FROM (VALUES 
                    (0, 4, '# Dokter'),
                    (5, 0, '&nbsp;&nbsp;&nbsp; a. Dokter Umum'),
                    (6, 0, '&nbsp;&nbsp;&nbsp; b. Dokter Gigi'),
                    (8, 0, '&nbsp;&nbsp;&nbsp; c. Dokter Gigi Spesialis'),
                    (7, 0, '&nbsp;&nbsp;&nbsp; d. Dokter Spesialis'),
                    (0, 4, '# Nakes Non Dokter'),
                    (0, 0, '&nbsp;&nbsp;&nbsp; a. Perawat'),
                    (0, 0, '&nbsp;&nbsp;&nbsp; b. Bidan'),
                    (0, 0, '&nbsp;&nbsp;&nbsp; c. Nakes Lainnya'),
                    (-1, 0, '# Non Nakes')
                ) AS t(kategori_id, kategori_colspan, kategori)
        ")->result();
    }

    public function getStatisticUsiaPegawai()
    {
        return @$this->db->query("
            SELECT
                t.usia_id,
                t.usia,
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 1 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 1 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 1 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 1 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 1 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"001\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 2 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 2 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 2 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 2 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 2 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"002\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 3 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 3 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 3 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 3 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 3 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"003\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 4 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 4 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 4 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 4 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 4 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"004\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 5 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 5 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 5 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 5 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 5 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"005\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 6 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 6 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 6 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 6 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 6 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"006\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 7 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 7 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 7 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 7 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 7 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"007\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 8 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 8 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 8 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 8 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 8 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"008\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 9 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 9 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 9 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 9 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 9 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"009\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 10 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 10 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 10 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 10 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND unit_id = 10 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"010\",
                (
                    CASE
                    WHEN (t.usia_id = 1) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND DATE_PART('year', AGE(tanggal_lahir)) <= 20)
                    WHEN (t.usia_id = 2) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND DATE_PART('year', AGE(tanggal_lahir)) between 21 and 30)
                    WHEN (t.usia_id = 3) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND DATE_PART('year', AGE(tanggal_lahir)) between 31 and 40)
                    WHEN (t.usia_id = 4) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND DATE_PART('year', AGE(tanggal_lahir)) between 41 and 50)
                    WHEN (t.usia_id = 5) THEN (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND DATE_PART('year', AGE(tanggal_lahir)) > 50)
                    ELSE 0
                    END
                ) AS \"jumlah\"
            FROM (VALUES (1, '< 20 tahun'), (2, '21 – 30 tahun'), (3, '31 – 40 tahun'), (4, '41 – 50 tahun'), (5, '> 50 tahun')) AS t(usia_id, usia)
        ")->result();
    }

    public function getStatisticJenisKelamin()
    {
        return @$this->db->query("
            SELECT
                t.jenis_kelamin,
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 1) AS \"001\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 2) AS \"002\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 3) AS \"003\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 4) AS \"004\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 5) AS \"005\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 6) AS \"006\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 7) AS \"007\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 8) AS \"008\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 9) AS \"009\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin AND unit_id = 10) AS \"010\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND jenis_kelamin = t.jenis_kelamin) AS \"jumlah\"
            FROM (VALUES ('Laki-laki'), ('Perempuan')) AS t(jenis_kelamin)
        ")->result();
    }

    public function getStatisticHubunganKerja()
    {
        return @$this->db->query("
            SELECT
                t.status_kontrak,
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 1) AS \"001\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 2) AS \"002\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 3) AS \"003\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 4) AS \"004\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 5) AS \"005\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 6) AS \"006\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 7) AS \"007\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 8) AS \"008\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 9) AS \"009\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id AND unit_id = 10) AS \"010\",
                (SELECT COALESCE(COUNT(id), 0) FROM pegawai WHERE status_active = 1 AND status_kontrak_id = t.status_kontrak_id) AS \"jumlah\"
            FROM (VALUES (0, 'Khusus (MK.C/D)'), (0, 'Profesional (MK.E/F)'), (0, 'Tetap (HK.A)'), (0, 'Kontrak I (HK.B)'), (0, 'Kontrak II (HK.B)'), (0, 'OJS (HK.B)')) AS t(status_kontrak_id, status_kontrak)
        ")->result();
    }
}
