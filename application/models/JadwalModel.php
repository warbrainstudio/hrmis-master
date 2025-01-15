<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JadwalModel extends CI_Model
{
  private $_table = 'jadwal';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'nama_jadwal',
        'label' => 'Nama Jadwal',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'unit_id',
        'label' => 'Unit ID',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'jadwal_masuk',
        'label' => 'Jadwal Masuk',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'jadwal_pulang',
        'label' => 'Jadwal Pulang',
        'rules' => 'required|trim'
      ]
    );
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT 
          j.*,
          u.kode_unit,
          u.nama_unit
        FROM jadwal j
        LEFT JOIN unit u ON u.id = j.unit_id
        ORDER BY j.id ASC
      ) t
      WHERE 1=1
    ";

    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getConfig()
  {
    $query = $this->db->get('jadwal_config');
    $config = $query->result();
    return $config;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
    $this->db->where($params);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    };

    return $this->db->get($this->_table)->result();
  }

  public function getDetail($params = array())
  {
    return $this->db->where($params)->get($this->_table)->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->nama_jadwal = $this->input->post('nama_jadwal');
      $this->unit_id = $this->input->post('unit_id');
      $this->jadwal_masuk = $this->input->post('jadwal_masuk');
      $this->jadwal_pulang = $this->input->post('jadwal_pulang');
      $this->db->insert($this->_table, $this);

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function insertBatch($data)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->insert_batch($this->_table, $data);

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function update($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->nama_jadwal = $this->input->post('nama_jadwal');
      $this->unit_id = $this->input->post('unit_id');
      $this->jadwal_masuk = $this->input->post('jadwal_masuk');
      $this->jadwal_pulang = $this->input->post('jadwal_pulang');
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
    return preg_replace('/[^0-9.]/', '', $number);
  }

  public function insert_config()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->masuk_cepat = $this->input->post('masuk_cepat') ? $this->input->post('masuk_cepat') : 0;
      $this->masuk_terlambat = $this->input->post('masuk_terlambat') ? $this->input->post('masuk_terlambat') : 0;
      $this->pulang_cepat = $this->input->post('pulang_cepat') ? $this->input->post('pulang_cepat') : 0;
      $this->pulang_terlambat = $this->input->post('pulang_terlambat') ? $this->input->post('pulang_terlambat') : 0;
      $this->created_by = $this->session->userdata('user')['id'];
      $this->db->insert('jadwal_config', $this);

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function update_config($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->masuk_cepat = $this->input->post('masuk_cepat') ? $this->input->post('masuk_cepat') : 0;
      $this->masuk_terlambat = $this->input->post('masuk_terlambat') ? $this->input->post('masuk_terlambat') : 0;
      $this->pulang_cepat = $this->input->post('pulang_cepat') ? $this->input->post('pulang_cepat') : 0;
      $this->pulang_terlambat = $this->input->post('pulang_terlambat') ? $this->input->post('pulang_terlambat') : 0;
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update('jadwal_config', $this, array('id' => $id));
      
      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }
}
