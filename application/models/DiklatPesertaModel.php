<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DiklatPesertaModel extends CI_Model
{
  private $_table = 'diklat_peserta';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'diklat_id',
        'label' => 'Diklat',
        'rules' => 'required'
      ],
      [
        'field' => 'pegawai_id',
        'label' => 'Pegawai',
        'rules' => 'required'
      ],
    );
  }

  public function getQuery($filter = null)
  {
    $query = "SELECT * FROM $this->_table WHERE 1=1 ";
    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
    $this->db->select('diklat_peserta.*, pegawai.nrp, pegawai.nama_lengkap');
    $this->db->from($this->_table);
    $this->db->join('pegawai', 'pegawai.id = diklat_peserta.pegawai_id');
    $this->db->where($params);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    };

    return $this->db->get()->result();
  }

  public function getDetail($params = array())
  {
    $this->db->where($params);
    return $this->db->get($this->_table)->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->diklat_id = $this->input->post('diklat_id');
      $this->pegawai_id = $this->input->post('pegawai_id');
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
      $this->diklat_id = $this->input->post('diklat_id');
      $this->pegawai_id = $this->input->post('pegawai_id');
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
}
