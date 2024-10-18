<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PegawaiKeluargaModel extends CI_Model
{
  private $_table = 'pegawai_keluarga';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'pegawai_id',
        'label' => 'Pegawai',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'nama_lengkap',
        'label' => 'Nama Lengkap',
        'rules' => 'required|trim|max_length[100]'
      ],
      [
        'field' => 'hubungan',
        'label' => 'Hubungan',
        'rules' => 'required|trim|max_length[20]'
      ],
      [
        'field' => 'no_hp',
        'label' => 'No. HP',
        'rules' => 'trim|max_length[13]'
      ],
      [
        'field' => 'alamat_lengkap',
        'label' => 'Alamat Lengkap',
        'rules' => 'trim'
      ],
    );
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT
          pk.*,
          p.nrp,
          p.nama_lengkap AS nama_pegawai
        FROM pegawai_keluarga pk
        JOIN pegawai p ON p.id = pk.pegawai_id
      ) t
      WHERE 1=1
    ";
    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
    $this->db->select('pk.*, p.nrp, p.nama_lengkap AS nama_pegawai');
    $this->db->from($this->_table . ' as pk');
    $this->db->join('pegawai as p', 'p.id = pk.pegawai_id');
    $this->db->where($params);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    };

    return $this->db->get()->result();
  }

  public function getDetail($params = array())
  {
    $this->db->select('
      pk.*,
      p.nrp,
      p.nama_lengkap AS nama_pegawai
    ');
    $this->db->from($this->_table . ' as pk');
    $this->db->join('pegawai as p', 'p.id = pk.pegawai_id');
    $this->db->where($params);
    return $this->db->get()->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->nama_lengkap = $this->input->post('nama_lengkap');
      $this->hubungan = $this->input->post('hubungan');
      $this->no_hp = $this->input->post('no_hp');
      $this->alamat_lengkap = $this->input->post('alamat_lengkap');
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
      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->nama_lengkap = $this->input->post('nama_lengkap');
      $this->hubungan = $this->input->post('hubungan');
      $this->no_hp = $this->input->post('no_hp');
      $this->alamat_lengkap = $this->input->post('alamat_lengkap');
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
