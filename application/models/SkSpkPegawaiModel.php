<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SkSpkPegawaiModel extends CI_Model
{
  private $_table = 'sk_spk_pegawai';
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
        'field' => 'kategori',
        'label' => 'Kategori',
        'rules' => 'required|trim|max_length[3]'
      ],
      [
        'field' => 'no_sk_spk',
        'label' => 'No. SK / Perijinan',
        'rules' => 'required|trim|max_length[50]'
      ],
      [
        'field' => 'sk_id',
        'label' => 'Keterangan',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'tanggal_berlaku',
        'label' => 'Tanggal Berlaku',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'status_active',
        'label' => 'Status Active',
        'rules' => 'required|trim'
      ],
    );
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT
          ssp.*,
          p.nrp,
          p.nama_lengkap,
          sk.nama_sk_spk,
          u.kode_unit,
          u.nama_unit,
          su.kode_sub_unit,
          su.nama_sub_unit,
          j.kode_jabatan,
          j.nama_jabatan,
          r.kode_ruangan,
          r.nama_ruangan
        FROM sk_spk_pegawai ssp
        LEFT JOIN pegawai p ON p.id = ssp.pegawai_id
        LEFT JOIN sk_spk sk ON sk.id = ssp.sk_id
        LEFT JOIN unit u ON u.id = ssp.unit_id
        LEFT JOIN sub_unit su ON su.id = ssp.sub_unit_id
        LEFT JOIN jabatan j ON j.id = ssp.jabatan_id
        LEFT JOIN ruangan r ON r.id = ssp.ruangan_id
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
    };

    return $this->db->get($this->_table)->result();
  }

  public function getDetail($params = array())
  {
    $this->db->select('
      sk_spk_pegawai.*,
      sk_spk.nama_sk_spk,
      pegawai.nrp,
      pegawai.nama_lengkap,
      unit.kode_unit,
      unit.nama_unit,
      sub_unit.kode_sub_unit,
      sub_unit.nama_sub_unit,
      jabatan.kode_jabatan,
      jabatan.nama_jabatan,
      ruangan.kode_ruangan,
      ruangan.nama_ruangan
    ');
    $this->db->join('pegawai', 'pegawai.id = sk_spk_pegawai.pegawai_id', 'left');
    $this->db->join('sk_spk', 'sk_spk.id = sk_spk_pegawai.sk_id', 'left');
    $this->db->join('unit', 'unit.id = sk_spk_pegawai.unit_id', 'left');
    $this->db->join('sub_unit', 'sub_unit.id = sk_spk_pegawai.sub_unit_id', 'left');
    $this->db->join('jabatan', 'jabatan.id = sk_spk_pegawai.jabatan_id', 'left');
    $this->db->join('ruangan', 'ruangan.id = sk_spk_pegawai.ruangan_id', 'left');
    $this->db->where($params);
    $data = $this->db->get($this->_table);
    return $data->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->kategori = $this->input->post('kategori');
      $this->no_sk_spk = $this->input->post('no_sk_spk');
      $this->sk_id = $this->input->post('sk_id');
      $this->unit_id = $this->input->post('unit_id');
      $this->sub_unit_id = $this->input->post('sub_unit_id');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->ruangan_id = $this->input->post('ruangan_id');
      $this->tanggal_berlaku = $this->input->post('tanggal_berlaku');
      $this->status_active = $this->input->post('status_active');
      $this->created_by = $this->session->userdata('user')['id'];
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
      $this->kategori = $this->input->post('kategori');
      $this->no_sk_spk = $this->input->post('no_sk_spk');
      $this->sk_id = $this->input->post('sk_id');
      $this->unit_id = $this->input->post('unit_id');
      $this->sub_unit_id = $this->input->post('sub_unit_id');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->ruangan_id = $this->input->post('ruangan_id');
      $this->tanggal_berlaku = $this->input->post('tanggal_berlaku');
      $this->status_active = $this->input->post('status_active');
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
