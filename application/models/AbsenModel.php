<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AbsenModel extends CI_Model
{
  private $_table = 'absen_pegawai';
  private $_tableView = '';

  public function getVerifikasi()
  {
    $data = array(
        array('id' => '0', 'text' => 'Input'),
        array('id' => '1', 'text' => 'Finger'),
    );
    return $data;
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
      $query_config = $this->db->get('jadwal_config');
      $config = $query_config->result();

      if(empty($config)){
        $masuk_cepat = 0;
        $masuk_terlambat = 0;
        $pulang_cepat = 0;
        $pulang_terlambat = 0;
      }else{
        $data_config = $config[0];
        $masuk_cepat = $data_config->masuk_cepat;
        $masuk_terlambat = $data_config->masuk_terlambat;
        $pulang_cepat = $data_config->pulang_cepat;
        $pulang_terlambat = $data_config->pulang_terlambat;
      }

      $query = "
        SELECT t.* FROM (
          SELECT 
            ab.*, 
            p.id as id_pegawai,
            p.nrp,
            (CASE WHEN p.absen_pegawai_id IS NOT NULL THEN p.nama_lengkap ELSE 'ID Absen : ' || CAST(ab.absen_id AS VARCHAR) END) AS nama,
            (CASE WHEN ab.masuk IS NULL THEN '-' WHEN TO_CHAR(ab.masuk, 'YYYY-MM-DD') = TO_CHAR(ab.tanggal_absen, 'YYYY-MM-DD') THEN TO_CHAR(ab.masuk, 'HH24:MI:SS') ELSE TO_CHAR(ab.masuk, 'HH24:MI:SS (DD-MM-YYYY)') END) AS jam_masuk,
            TO_CHAR(ROUND(EXTRACT(EPOCH FROM (ab.masuk::time - j.jadwal_masuk::time)) / 60), '999') || ' menit' AS cek_waktu_masuk,
            (CASE WHEN ab.verifikasi_masuk = 1 THEN 'Finger' WHEN ab.verifikasi_masuk = 0 THEN 'Input' ELSE '-' END) AS verifikasi_m, 
            (CASE WHEN ab.pulang IS NULL THEN '-' WHEN TO_CHAR(ab.pulang, 'YYYY-MM-DD') = TO_CHAR(ab.tanggal_absen, 'YYYY-MM-DD') THEN TO_CHAR(ab.pulang, 'HH24:MI:SS') ELSE TO_CHAR(ab.pulang, 'HH24:MI:SS (DD-MM-YYYY)') END) AS jam_pulang,
            TO_CHAR(ROUND(EXTRACT(EPOCH FROM (ab.pulang::time - j.jadwal_pulang::time)) / 60), '999') || ' menit' AS cek_waktu_pulang,
            (CASE WHEN ab.verifikasi_pulang = 1 THEN 'Finger' WHEN ab.verifikasi_pulang = 0 THEN 'Input' ELSE '-' END) AS verifikasi_p,
            EXTRACT(EPOCH FROM (ab.pulang - ab.masuk)) / 3600 AS jam_kerja,
            (CASE WHEN TO_CHAR(ab.masuk, 'YYYY-mm-dd') != TO_CHAR(ab.pulang, 'YYYY-mm-dd') THEN 'Shift Malam' ELSE '-' END) AS jenis_shift,
            p.unit_id,
            p.sub_unit_id,
            COALESCE(m_masuk.nama_mesin, ab.mesin_masuk) AS nama_mesin_masuk,
            m_masuk.lokasi as lokasi_masuk,
            COALESCE(m_pulang.nama_mesin, ab.mesin_pulang) AS nama_mesin_pulang,
            m_pulang.lokasi as lokasi_pulang,
            (CASE WHEN ab.masuk IS NULL THEN '-' WHEN ab.pulang IS NULL THEN '-' ELSE j.nama_jadwal END) AS jadwal_nama,
            j.id as id_jadwal,
            j.nama_jadwal,
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
              ab.jadwal_id = j.id
              OR (
                ab.jadwal_id IS NULL
                AND ab.masuk::time >= (j.jadwal_masuk - interval '$masuk_cepat minute') 
                AND ab.masuk::time <= (j.jadwal_masuk + interval '$masuk_terlambat minute')
                AND ab.pulang::time >= (j.jadwal_pulang - interval '$pulang_cepat minute')
                AND ab.pulang::time <= (j.jadwal_pulang + interval '$pulang_terlambat minute')
                AND p.unit_id = j.unit_id
              )
          )
          ORDER BY ab.tanggal_absen, p.nama_lengkap, ab.absen_id, masuk, pulang ASC
        ) t
        WHERE 1=1
      ";

      if (!is_null($filter)) $query .= $filter;
      return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {

    $this->db->where($params);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    }
        
    return $this->db->get($this->_table)->result();

  }

  public function getDetail_sub_unit($params = array())
  {
    $this->db->join('unit', 'unit.id = sub_unit.unit_id', 'left');
    if (isset($params['id']) && $params['id'] !== 'null') {
      $this->db->where('sub_unit.id', $params['id']);
    } else {
        $this->db->where('sub_unit.id IS NULL', null, false);
    }
    return $this->db->get('sub_unit')->row();
  }

  public function getDetail($params = array())
  {
    return $this->db->where($params)->get($this->_table)->row();
  }

  public function update($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $absenId = $this->input->post('absen_id');
      $date = $this->input->post('tanggal_absen');
      $masuk = $this->input->post('masuk');
      $verifikasi_masuk = $this->input->post('verifikasi_masuk');
      $mesin_masuk = $this->input->post('mesin_masuk');
      $pulang = $this->input->post('pulang');
      $verifikasi_pulang = $this->input->post('verifikasi_pulang');
      $mesin_pulang = $this->input->post('mesin_pulang');
      $jadwal_id = $this->input->post('jadwal_id');
      
      if (empty($masuk)) {
        $masuk = null;
        $verifikasi_masuk = null;
        $mesin_masuk = null;
      }

      if (empty($pulang)) {
        $pulang = null;
        $verifikasi_pulang = null;
        $mesin_pulang = null;
      }

      if (empty($verifikasi_masuk) || !is_numeric($verifikasi_masuk)) {
        $verifikasi_masuk = NULL;
      }

      if (empty($verifikasi_pulang) || !is_numeric($verifikasi_pulang)) {
          $verifikasi_pulang = NULL;
      }

      $this->masuk = $masuk;
      $this->verifikasi_masuk = $verifikasi_masuk;
      $this->mesin_masuk = $mesin_masuk;
      $this->pulang = $pulang;
      $this->verifikasi_pulang = $verifikasi_pulang;
      $this->mesin_pulang = $mesin_pulang;
      $this->jadwal_id = $jadwal_id;
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $id));
      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.', 'error' => $th);
    };

    return $response;
  }

  public function update_jadwal($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->jadwal_id = $this->input->post('jadwal_id');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
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

  public function fetchData($start_date, $end_date){
    $status = 'false';
    $token = 'XVd17lwEgOHcvKgjJWGWbuufQdte7WhiPLerllmSWcvr8jKLz6vqqkQkl4DIQzvbOUAtsxvl1TDviMlS3bQEewLszTxxGeAuv8XS';
    $task = '/fetchData?';
    $table = 'absen_pegawai';

    $apiUrl = base_url('api/'.$task . http_build_query([
        'token' => $token,
        'host' => 'localhost',
        'port' => $this->db->port,
        'username' => $this->db->username,
        'password' => $this->db->password,
        'database' => $this->db->database,
        'table' => $table,
        'alldata' => $status,
        'start_date' => $start_date,
        'end_date' => $end_date,
    ]));

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        $response = array(
          'status' => false,
          'error' => 'cURL error: ' . curl_error($ch),
        );
    }

    curl_close($ch);
    $data_api = json_decode($response, true);

    if (is_array($data_api) && isset($data_api['status'])) {
      if ($data_api['status'] == 'true') {
          $response = array(
              'status' => true,
          );
          $this->filter_data_absen($start_date, $end_date);
      } else {
          $response = array(
              'status' => false,
              'message' => $data_api['message'],
          );
      }
    } else {
        $response = array(
          'status' => false,
          'message' => 'Invalid response from API',
        );
    }
    
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
