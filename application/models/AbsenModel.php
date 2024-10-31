<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AbsenModel extends CI_Model
{
  private $_table = 'absen_pegawai';
  public $_tableView = 'absen_pegawai';


  public function getQuery($filter = null)
  {
      $query = "
        SELECT t.* FROM (
          SELECT 
            ab.*, 
            p.id as id_pegawai,
            COALESCE(p.nama_lengkap, '-') AS nama,
            (CASE WHEN ab.verifikasi_masuk = 1 THEN 'Finger' WHEN ab.verifikasi_masuk = 0 THEN 'Input' ELSE '' END) AS verifikasi_m,
            (CASE WHEN ab.verifikasi_pulang = 1 THEN 'Finger' WHEN ab.verifikasi_pulang = 0 THEN 'Input' ELSE '' END) AS verifikasi_p,
            EXTRACT(EPOCH FROM (ab.pulang - ab.masuk)) / 3600 AS jam_kerja
          FROM absen_pegawai ab
          LEFT JOIN pegawai p ON ab.absen_id = p.absen_pegawai_id
          ORDER BY p.nama_lengkap, ab.tanggal_absen ASC
        ) t
        WHERE 1=1
      ";

      if (!is_null($filter)) $query .= $filter;
      return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
      if (isset($params['tanggal_absen'])) {
          
        $orderField = 'tanggal_absen';
        $dateParam = $params['tanggal_absen'];

        $this->db->select('absen_pegawai.tanggal_absen,
                          CASE WHEN absen_pegawai.masuk IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.masuk, \'HH24:MI:SS\') END AS jam_masuk,
                          CASE WHEN absen_pegawai.verifikasi_masuk = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_masuk = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_m, 
                          CASE WHEN absen_pegawai.mesin_masuk IS NULL THEN \'-\' ELSE absen_pegawai.mesin_masuk END AS mesin_m,
                          CASE WHEN absen_pegawai.pulang IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.pulang, \'HH24:MI:SS\') END AS jam_pulang,
                          CASE WHEN absen_pegawai.verifikasi_pulang = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_pulang = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_p,
                          CASE WHEN absen_pegawai.mesin_pulang IS NULL THEN \'-\' ELSE absen_pegawai.mesin_pulang END AS mesin_p,
                          CASE WHEN absen_pegawai.pulang - absen_pegawai.masuk IS NULL THEN \'-\' ELSE (EXTRACT(EPOCH FROM (absen_pegawai.pulang - absen_pegawai.masuk)) / 3600)::text END AS jam_kerja,
                          pegawai.nrp,
                          COALESCE(pegawai.nama_lengkap, \'-\') AS pegawai_nama');
        $this->db->join('pegawai', 'absen_pegawai.absen_id = pegawai.absen_pegawai_id', 'left');
        $this->db->where('absen_pegawai_id IS NOT NULL');
        $this->db->order_by('absen_pegawai.tanggal_absen, pegawai.nama_lengkap ASC');
        
        if (preg_match('/^\d{4}-\d{2}$/', $dateParam)) {

          list($year, $month) = explode('-', $dateParam);
          $startDate = date('Y-m-d', strtotime("$year-$month-1"));
          $endDate = date('Y-m-d', strtotime("last day of $year-$month"));
          $this->db->where("tanggal_absen BETWEEN '$startDate' AND '$endDate'");

        }elseif (preg_match('/^\d{4}$/', $dateParam)) {

          $startDate = date('Y-m-d', strtotime("$dateParam-01-01"));
          $endDate = date('Y-m-d', strtotime("$dateParam-12-31"));
          $this->db->where("tanggal_absen BETWEEN '$startDate' AND '$endDate'");

        }else{  

          $this->db->where("DATE(absen_pegawai.tanggal_absen)", $dateParam);

        }

        unset($params['tanggal_absen']);
        
        if (!is_null($orderField)) {
          $this->db->order_by($orderField, $orderBy);
        }
    
        return $this->db->get($this->_table)->result();

      }elseif(isset($params['absen_id'])){
        
        $orderField = 'tanggal_absen';

        $this->db->select('absen_pegawai.absen_id,
                          absen_pegawai.tanggal_absen, 
                          CASE WHEN absen_pegawai.masuk IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.masuk, \'HH24:MI:SS\') END AS jam_masuk,
                          CASE WHEN absen_pegawai.verifikasi_masuk = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_masuk = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_m, 
                          CASE WHEN absen_pegawai.mesin_masuk IS NULL THEN \'-\' ELSE absen_pegawai.mesin_masuk END AS mesin_m,
                          CASE WHEN absen_pegawai.pulang IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.pulang, \'HH24:MI:SS\') END AS jam_pulang,
                          CASE WHEN absen_pegawai.verifikasi_pulang = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_pulang = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_p,
                          CASE WHEN absen_pegawai.mesin_pulang IS NULL THEN \'-\' ELSE absen_pegawai.mesin_pulang END AS mesin_p,
                          CASE WHEN absen_pegawai.pulang - absen_pegawai.masuk IS NULL THEN \'-\' ELSE (EXTRACT(EPOCH FROM (absen_pegawai.pulang - absen_pegawai.masuk)) / 3600)::text END AS jam_kerja
                          ');
        $this->db->join('pegawai', 'absen_pegawai.absen_id = pegawai.absen_pegawai_id', 'left');
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

  public function delete_pegawai($absen_id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->delete($this->_table, array('absen_id' => $absen_id));

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
