<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HasilPemeriksaanModel extends CI_Model
{
  private $_table = 'hasil_pemeriksaan';
  private $_tableItem = 'hasil_pemeriksaan_item';

  public function rules()
  {
    return array(
      ['field' => 'tanggal', 'label' => 'Tanggal', 'rules' => 'required|trim'],
      ['field' => 'no_lab', 'label' => 'No. Lab', 'rules' => 'required|trim'],
      ['field' => 'id_pelanggan', 'label' => 'ID Pelanggan', 'rules' => 'required|trim'],
      ['field' => 'nama_pasien', 'label' => 'Nama Pasien', 'rules' => 'required|trim'],
      ['field' => 'ubs_uid', 'label' => 'UBS UID', 'rules' => 'required|trim'],
    );
  }

  public function getQuery()
  {
    $role = $this->session->userdata('user')['role'];
    $unitUid = $this->session->userdata('user')['unit_uid'];
    $filterExpression = '';

    if ($role !== 'Administrator') $filterExpression = "AND hp.ubs_uid = '$unitUid'";

    $query = "
      SELECT hp.* FROM $this->_table hp
      WHERE 1=1 $filterExpression
    ";

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
    return $this->db->where($params)->get($this->_table)->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'code' => 500, 'message' => 'Failed to save your data', 'data' => null);

    try {
      $hasilScan = $this->input->post('hasil_scan');
      $hasilScan_temp = array();

      if (!is_null($hasilScan) && count($hasilScan) > 0) {
        $this->db->trans_begin();

        $this->tanggal = $this->input->post('tanggal');
        $this->no_lab = $this->input->post('no_lab');
        $this->id_pelanggan = $this->input->post('id_pelanggan');
        $this->nama_pasien = $this->input->post('nama_pasien');
        $this->jenis_kelamin = $this->input->post('jenis_kelamin');
        $this->ubs_uid = $this->input->post('ubs_uid');
        $this->db->insert($this->_table, $this);

        // Get inserted id
        $this->id = $this->db->insert_id();

        foreach ($hasilScan as $index => $item) {
          if (isset($item['is_checked']) && $item['is_checked'] == true) {
            $hasilScan_temp[] = array(
              'hasil_pemeriksaan_id' => $this->id,
              'hasil_scan_id' => $item['hasil_scan_id'],
              'created_by' => $this->session->userdata('user')['id'],
            );
          };
        };

        if (count($hasilScan_temp) > 0) {
          $this->db->insert_batch($this->_tableItem, $hasilScan_temp);

          if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $response = array('status' => true, 'code' => 200, 'message' => 'Data has been saved', 'data' => $this);
          };
        } else {
          $this->db->trans_rollback();
          $response = array('status' => false, 'code' => 500, 'message' => 'Hasil scan tidak boleh kosong', 'data' => $this);
        };
      } else {
        $response = array('status' => false, 'code' => 500, 'message' => 'Hasil scan tidak ditemukan', 'data' => $this);
      };
    } catch (\Throwable $th) {
      $response = array('status' => false, 'code' => 500, 'message' => 'Failed to save your data', 'data' => $this);
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
