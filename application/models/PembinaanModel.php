<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembinaanModel extends CI_Model
{
  private $_table = 'pembinaan';
  private $_tableView = '';

  public function rules($id)
  {
    return array(
      [
        'field' => 'kategori',
        'label' => 'Kategori',
        'rules' => 'required|trim|max_length[10]'
      ],
      [
        'field' => 'no_pembinaan',
        'label' => 'No. Pembinaan',
        'rules' => [
          'required',
          'trim',
          'max_length[50]',
          [
            'no_pembinaan_check',
            function ($value) use ($id) {
              return $this->_no_pembinaan_check($value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'pegawai_id',
        'label' => 'Pegawai',
        'rules' => 'required'
      ],
      [
        'field' => 'perihal',
        'label' => 'Perihal',
        'rules' => 'required|trim|max_length[200]'
      ],
      [
        'field' => 'unit_id',
        'label' => 'Unit',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'jabatan_id',
        'label' => 'Jabatan',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'start_date',
        'label' => 'Tanggal Mulai',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'end_date',
        'label' => 'Tanggal Selesai',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'pelanggaran',
        'label' => 'Pelanggaran',
        'rules' => [
          [
            'pelanggaran_check',
            function () {
              return $this->_pelanggaran_check();
            }
          ]
        ]
      ],
      [
        'field' => 'sanksi',
        'label' => 'Sanksi',
        'rules' => [
          [
            'sanksi_check',
            function () {
              return $this->_sanksi_check();
            }
          ]
        ]
      ],
    );
  }

  private function _no_pembinaan_check($value, $id)
  {
    $id = (!is_null($id) && !empty($id)) ? $id : 0;
    $temp = $this->db->where(array('id !=' => $id, 'no_pembinaan' => $value))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('no_pembinaan_check', 'Then No. Pembinaan "' . $value . '" is exist.');
      return false;
    } else {
      return true;
    };
  }

  private function _pelanggaran_check()
  {
    $kategori = $this->input->post('kategori');
    $pelanggaran = $this->input->post('pelanggaran');

    if ($kategori !== 'Scorsing' && trim(empty($pelanggaran))) {
      $this->form_validation->set_message('pelanggaran_check', 'Then Pelanggaran field is required.');
      return false;
    } else {
      return true;
    };
  }

  private function _sanksi_check()
  {
    $kategori = $this->input->post('kategori');
    $sanksi = $this->input->post('sanksi');

    if ($kategori !== 'Scorsing' && trim(empty($sanksi))) {
      $this->form_validation->set_message('sanksi_check', 'Then Sanksi field is required.');
      return false;
    } else {
      return true;
    };
  }

  public function getKategori()
  {
    $data = array(
      array('id' => 'SP 1', 'text' => 'SP 1'),
      array('id' => 'SP 2', 'text' => 'SP 2'),
      array('id' => 'SP 3', 'text' => 'SP 3'),
      array('id' => 'Scorsing', 'text' => 'Scorsing'),
    );
    return $data;
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT * FROM (
        SELECT p.*, pg.nrp, pg.nama_lengkap, u.kode_unit, u.nama_unit, j.kode_jabatan, j.nama_jabatan
        FROM pembinaan p
        JOIN pegawai pg ON pg.id = p.pegawai_id
        LEFT JOIN unit u ON u.id = p.unit_id
        LEFT JOIN jabatan j ON j.id = p.jabatan_id
      ) t
      WHERE 1=1
    ";
    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
    $this->db->select('p.*, pg.nrp, pg.nama_lengkap, u.kode_unit, u.nama_unit, j.kode_jabatan, j.nama_jabatan');
    $this->db->from('pembinaan AS p');
    $this->db->join('pegawai AS pg', 'pg.id = p.pegawai_id');
    $this->db->join('unit AS u', 'u.id = p.unit_id', 'left');
    $this->db->join('jabatan AS j', 'j.id = p.jabatan_id', 'left');
    $this->db->where($params);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    };

    return $this->db->get()->result();
  }

  public function getDetail($params = array())
  {
    $this->db->select('p.*, pg.nrp, pg.nama_lengkap, u.kode_unit, u.nama_unit, j.kode_jabatan, j.nama_jabatan');
    $this->db->from('pembinaan AS p');
    $this->db->join('pegawai AS pg', 'pg.id = p.pegawai_id');
    $this->db->join('unit AS u', 'u.id = p.unit_id', 'left');
    $this->db->join('jabatan AS j', 'j.id = p.jabatan_id', 'left');
    $this->db->where($params);
    return $this->db->get()->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->trans_begin();

      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->no_pembinaan = $this->input->post('no_pembinaan');
      $this->kategori = $this->input->post('kategori');
      $this->start_date = $this->input->post('start_date');
      $this->end_date = $this->input->post('end_date');
      $this->unit_id = $this->input->post('unit_id');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->perihal = $this->input->post('perihal');
      $this->pelanggaran = $this->input->post('pelanggaran');
      $this->sanksi = $this->input->post('sanksi');
      $this->created_by = $this->session->userdata('user')['id'];
      $this->db->insert($this->_table, $this);

      if ($this->db->trans_status() === false) {
        $this->db->trans_rollback();
        $response = array('status' => false, 'data' => 'Failed to save your data.');
      } else {
        $this->db->trans_commit();
        $response = array('status' => true, 'data' => 'Data has been saved.');
      };
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
      $this->db->trans_begin();

      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->no_pembinaan = $this->input->post('no_pembinaan');
      $this->kategori = $this->input->post('kategori');
      $this->start_date = $this->input->post('start_date');
      $this->end_date = $this->input->post('end_date');
      $this->unit_id = $this->input->post('unit_id');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->perihal = $this->input->post('perihal');
      $this->pelanggaran = $this->input->post('pelanggaran');
      $this->sanksi = $this->input->post('sanksi');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $id));

      if ($this->db->trans_status() === false) {
        $this->db->trans_rollback();
        $response = array('status' => false, 'data' => 'Failed to save your data.');
      } else {
        $this->db->trans_commit();
        $response = array('status' => true, 'data' => 'Data has been saved.');
      };
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
