<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AbsenModel extends CI_Model
{
  private $_table = 'absen_pegawai';
  public $_tableView = 'absen_pegawai';

  
  public function getQueryRaw($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT 
          abr.*,
          (CASE WHEN p.absen_pegawai_id IS NOT NULL THEN p.nama_lengkap ELSE 'ID Absen : ' || CAST(abr.absen_id AS VARCHAR) END) AS nama, 
          (CASE WHEN abr.status = 0 THEN 'Masuk' WHEN abr.status = 3 THEN 'Cuti' ELSE 'Pulang' END) AS nama_status,
          (CASE WHEN abr.verified = 1 THEN 'Finger' ELSE 'Input' END) AS verifikasi,
          p.id as id_pegawai,
          p.nrp,
          p.unit_id,
          p.sub_unit_id,
          u.kode_unit,
          u.nama_unit,
          su.kode_sub_unit,
          su.nama_sub_unit,
          m.ipadress, 
          m.nama_mesin,
          m.lokasi
        FROM absen_pegawai_raw abr
        LEFT JOIN pegawai p ON abr.absen_id = p.absen_pegawai_id
        LEFT JOIN unit u ON u.id = p.unit_id
        LEFT JOIN sub_unit su ON su.id = p.sub_unit_id
        LEFT JOIN mesin_absen m ON m.ipadress = abr.ipmesin
        ORDER BY abr.tanggal_absen ASC
      ) t
      WHERE 1=1
    ";

    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getQuery($filter = null)
  {
      $query = "
        SELECT t.* FROM (
          SELECT 
            ab.*, 
            p.id as id_pegawai,
            p.nrp,
            (CASE WHEN p.absen_pegawai_id IS NOT NULL THEN p.nama_lengkap ELSE 'ID Absen : ' || CAST(ab.absen_id AS VARCHAR) END) AS nama,
            (CASE WHEN ab.masuk IS NULL THEN '-' WHEN TO_CHAR(ab.masuk, 'YYYY-MM-DD') != TO_CHAR(ab.pulang, 'YYYY-MM-DD') THEN TO_CHAR(ab.masuk, 'HH24:MI:SS DD-MM-YYYY ') ELSE TO_CHAR(ab.masuk, 'HH24:MI:SS') END) AS jam_masuk,
            (CASE WHEN ab.verifikasi_masuk = 1 THEN 'Finger' WHEN ab.verifikasi_masuk = 0 THEN 'Input' ELSE '-' END) AS verifikasi_m, 
            (CASE WHEN ab.pulang IS NULL THEN '-' ELSE TO_CHAR(ab.pulang, 'HH24:MI:SS') END) AS jam_pulang,
            (CASE WHEN ab.verifikasi_pulang = 1 THEN 'Finger' WHEN ab.verifikasi_pulang = 0 THEN 'Input' ELSE '-' END) AS verifikasi_p,
            EXTRACT(EPOCH FROM (ab.pulang - ab.masuk)) / 3600 AS jam_kerja,
            (CASE WHEN TO_CHAR(ab.masuk, 'YYYY-mm-dd') != TO_CHAR(ab.pulang, 'YYYY-mm-dd') THEN 'Shift Malam' ELSE '-' END) AS jenis_shift,
            p.unit_id,
            p.sub_unit_id,
            m_masuk.nama_mesin as nama_mesin_masuk,
            m_masuk.lokasi as lokasi_masuk, 
            m_pulang.nama_mesin as nama_mesin_pulang,
            m_pulang.lokasi as lokasi_pulang,
            (CASE WHEN ab.masuk IS NULL THEN '-' WHEN ab.pulang IS NULL THEN '-' ELSE j.nama_jadwal END) AS jadwal_nama,
            j.jadwal_masuk,
            j.jadwal_pulang,
            u.kode_unit,
            u.nama_unit,
            su.kode_sub_unit,
            su.nama_sub_unit
          FROM absen_pegawai ab
          LEFT JOIN pegawai p ON ab.absen_id = p.absen_pegawai_id
          LEFT JOIN unit u ON u.id = p.unit_id
          LEFT JOIN sub_unit su ON su.id = p.sub_unit_id
          LEFT JOIN mesin_absen m_masuk ON m_masuk.ipadress = ab.mesin_masuk
          LEFT JOIN mesin_absen m_pulang ON m_pulang.ipadress = ab.mesin_pulang
          LEFT JOIN jadwal j ON (
              ab.masuk::time >= (j.jadwal_masuk - interval '1 minute') 
              AND ab.masuk::time <= (j.jadwal_masuk + interval '10 minute')
              AND ab.pulang::time >= (j.jadwal_pulang - interval '1 minute')
              AND ab.pulang::time <= (j.jadwal_pulang + interval '30 minute')
          )
          ORDER BY ab.tanggal_absen, p.nama_lengkap, ab.absen_id ASC
        ) t
        WHERE 1=1
      ";

      /*LEFT JOIN jadwal j ON (
              ab.masuk::time >= (j.jadwal_masuk - interval '10 minute') 
              AND ab.masuk::time <= (j.jadwal_masuk + interval '10 minute')
              AND ab.pulang::time >= (j.jadwal_pulang - interval '10 minute')
              AND ab.pulang::time <= (j.jadwal_pulang + interval '30 minute')
          )
              LEFT JOIN jadwal j ON j.unit_id = u.id */

      if (!is_null($filter)) $query .= $filter;
      return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
      if(isset($params['absen_id'])){
        
        $orderField = 'tanggal_absen';

        $this->db->select('absen_pegawai.absen_id,
                          absen_pegawai.tanggal_absen, 
                          CASE WHEN absen_pegawai.masuk IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.masuk, \'HH24:MI:SS\') END AS jam_masuk,
                          CASE WHEN absen_pegawai.verifikasi_masuk = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_masuk = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_m, 
                          CASE WHEN absen_pegawai.mesin_masuk IS NULL THEN \'-\' ELSE absen_pegawai.mesin_masuk END AS mesin_m,
                          CASE WHEN absen_pegawai.pulang IS NULL THEN \'-\' WHEN TO_CHAR(absen_pegawai.masuk, \'YYYY-MM-DD\') != TO_CHAR(absen_pegawai.pulang, \'YYYY-MM-DD\') THEN TO_CHAR(absen_pegawai.pulang, \'HH24:MI:SS DD-MM-YYYY\') ELSE TO_CHAR(absen_pegawai.pulang, \'HH24:MI:SS\') END AS jam_pulang,
                          CASE WHEN absen_pegawai.verifikasi_pulang = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_pulang = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_p,
                          CASE WHEN absen_pegawai.mesin_pulang IS NULL THEN \'-\' ELSE absen_pegawai.mesin_pulang END AS mesin_p,
                          CASE WHEN absen_pegawai.pulang - absen_pegawai.masuk IS NULL THEN \'-\' ELSE (EXTRACT(EPOCH FROM (absen_pegawai.pulang - absen_pegawai.masuk)) / 3600)::text END AS jam_kerja,
                          CASE WHEN TO_CHAR(absen_pegawai.masuk, \'YYYY-mm-dd\') != TO_CHAR(absen_pegawai.pulang, \'YYYY-mm-dd\') THEN \'Shift Malam\' ELSE \'-\' END AS jenis_shift,
                          m_masuk.nama_mesin as mesin_m, 
                          m_pulang.nama_mesin as mesin_p');
        $this->db->join('pegawai', 'absen_pegawai.absen_id = pegawai.absen_pegawai_id', 'left');
        $this->db->join('mesin_absen m_masuk', 'm_masuk.ipadress = absen_pegawai.mesin_masuk', 'left');
        $this->db->join('mesin_absen m_pulang', 'm_pulang.ipadress = absen_pegawai.mesin_pulang', 'left');
        $this->db->where($params);
        $this->db->order_by('tanggal_absen ASC');

        if (!is_null($orderField)) {
          $this->db->order_by($orderField, $orderBy);
        }
        
        return $this->db->get($this->_table)->result();

      }else{

        $this->db->where($params);

        if (!is_null($orderField)) {
          $this->db->order_by($orderField, $orderBy);
        }
        
        return $this->db->get($this->_table)->result();

      }
  }
  
  public function getMonth()
  {
    $data = array(
        array('id' => '01', 'text' => 'Januari'),
        array('id' => '02', 'text' => 'Februari'),
        array('id' => '03', 'text' => 'Maret'),
        array('id' => '04', 'text' => 'April'),
        array('id' => '05', 'text' => 'Mei'),
        array('id' => '06', 'text' => 'Juni'),
        array('id' => '07', 'text' => 'Juli'),
        array('id' => '08', 'text' => 'Agustus'),
        array('id' => '09', 'text' => 'September'),
        array('id' => '10', 'text' => 'Oktober'),
        array('id' => '11', 'text' => 'November'),
        array('id' => '12', 'text' => 'Desember'),
    );
    return $data;
  }

  public function getYear()
  {  
    $startYear = 2023;
    $currentYear = date('Y');

    $years = array();

    for ($year = $startYear; $year < $currentYear; $year++) {
        $years[] = array('id' => $year, 'text' => $year);
    }

    return $years;
  }

  public function getDetail($params = array())
  {
    return $this->db->where($params)->get($this->_table)->row();
  }

  public function delete($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->delete($this->_table, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data.');
    };

    return $response;
  }

  public function truncate()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->truncate($this->_table);

      $response = array('status' => true, 'data' => 'Data has been truncated.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to truncate your data.');
    };

    return $response;
  }

  function br2nl($text)
  {
    return str_replace("\r\n", '<br/>', htmlspecialchars_decode($text));
  }

  function clean_number($number)
  {
    return preg_replace('/[^0-9]/', '', $number);
  }
}
