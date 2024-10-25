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
          p.id AS id_pegawai,
          COALESCE(p.nama_lengkap, '-') AS nama,
          (CASE WHEN ab.status = 0 THEN 'Masuk' ELSE 'Pulang' END) AS nama_status,
          (CASE WHEN ab.verified = 1 THEN 'Finger' ELSE 'Input' END) AS verifikasi
        FROM absen_pegawai ab
        LEFT JOIN pegawai p ON ab.absen_id = p.absen_pegawai_id
      ) t
      WHERE 1=1
    ";

    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getnullpegawaiQuery($filter = null)
  {
    $query = "
        SELECT t.* FROM (
          SELECT
            ab.absen_id, 
            COUNT(ab.tanggal_absen) AS datetime_count
          FROM absen_pegawai ab
          LEFT JOIN pegawai p ON ab.absen_id = p.absen_pegawai_id
          WHERE p.absen_pegawai_id IS NULL
          GROUP BY ab.absen_id
        ) t
         WHERE 1=1
    ";
    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getNull(){
    $query = "
          SELECT
            absen_pegawai.absen_id 
          FROM absen_pegawai
          LEFT JOIN pegawai ON absen_pegawai.absen_id = pegawai.absen_pegawai_id
          WHERE pegawai.absen_pegawai_id IS NULL
          GROUP BY absen_pegawai.absen_id
    ";
    
    // Assuming you have a database connection and using CodeIgniter's query builder
    $result = $this->db->query($query);
    return $result->result();
  }



  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
      if (isset($params['tanggal_absen'])) {
          
        $orderField = 'tanggal_absen';
        $dateParam = $params['tanggal_absen'];

        $this->db->select('absen_pegawai.tanggal_absen,
                          TO_CHAR(absen_pegawai.tanggal_absen, \'HH24:MI:SS\') AS jam_absen,
                          CASE WHEN absen_pegawai.verified = 1 THEN \'Finger\' ELSE \'Input\' END AS verifikasi, 
                          CASE WHEN absen_pegawai.status = 0 THEN \'Masuk\' ELSE \'Pulang\' END AS nama_status,
                          absen_pegawai.ipmesin as mesin_nama,
                          pegawai.nrp,
                          COALESCE(pegawai.nama_lengkap, \'-\') AS pegawai_nama');
        $this->db->join('pegawai', 'absen_pegawai.absen_id = pegawai.absen_pegawai_id', 'left');
        
        if (preg_match('/^\d{4}-\d{2}$/', $dateParam)) {

          list($year, $month) = explode('-', $dateParam);
          $startDate = date('Y-m-d', strtotime("$year-$month-21 -1 month"));
          $endDate = date('Y-m-d', strtotime("$year-$month-21"));
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

      }elseif(isset($params['absen_pegawai_id'])){
        
        $orderField = 'tanggal_absen';

        $this->db->select('absen_pegawai.absen_id, 
                TO_CHAR(absen_pegawai.tanggal_absen, \'YYYY-MM-DD\') AS tanggal,
                TO_CHAR(absen_pegawai.tanggal_absen, \'HH24:MI:SS\') AS jam_absen,
                CASE WHEN absen_pegawai.verified = 1 THEN \'Finger\' ELSE \'Input\' END AS verifikasi, 
                CASE WHEN absen_pegawai.status = 0 THEN \'Masuk\' ELSE \'Pulang\' END AS nama_status,
                absen_pegawai.ipmesin as mesin_nama');
        $this->db->join('pegawai', 'absen_pegawai.absen_id = pegawai.absen_pegawai_id', 'left');
        $this->db->where($params);

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
