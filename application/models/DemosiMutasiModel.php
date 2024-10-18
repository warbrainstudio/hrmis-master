<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DemosiMutasiModel extends CI_Model
{
  private $_table = 'demosi_mutasi';
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
        'field' => 'pegawai_id',
        'label' => 'Pegawai',
        'rules' => 'required'
      ],
      [
        'field' => 'no_sk',
        'label' => 'No. SK',
        'rules' => [
          'required',
          'trim',
          'max_length[50]',
          [
            'no_sk_check',
            function ($value) use ($id) {
              return $this->_no_sk_check($value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'tanggal_sk',
        'label' => 'Tanggal SK',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'no_skppj',
        'label' => 'No. SKPPJ',
        'rules' => [
          'required',
          'trim',
          'max_length[50]',
          [
            'no_skppj_check',
            function ($value) use ($id) {
              return $this->_no_skppj_check($value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'doj',
        'label' => 'DOJ',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'kode_pendidikan',
        'label' => 'Kode Pendidikan',
        'rules' => 'trim|max_length[10]'
      ],
      [
        'field' => 'old_unit_id',
        'label' => 'Unit (Sebelumnya)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'old_sub_unit_id',
        'label' => 'Sub Unit (Sebelumnya)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'old_jabatan_id',
        'label' => 'Jabatan (Sebelumnya)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'old_tenaga_unit_id',
        'label' => 'Tenaga Unit (Sebelumnya)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'old_jenis_pegawai_id',
        'label' => 'Status Kerja (Sebelumnya)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'new_unit_id',
        'label' => 'Unit (Baru)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'new_sub_unit_id',
        'label' => 'Sub Unit (Baru)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'new_jabatan_id',
        'label' => 'Jabatan (Baru)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'new_tenaga_unit_id',
        'label' => 'Tenaga Unit (Baru)',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'new_jenis_pegawai_id',
        'label' => 'Status Kerja (Baru)',
        'rules' => 'required|trim'
      ],
    );
  }

  private function _no_sk_check($value, $id)
  {
    $id = (!is_null($id) && !empty($id)) ? $id : 0;
    $temp = $this->db->where(array('id !=' => $id, 'no_sk' => $value))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('no_sk_check', 'Then No. SK "' . $value . '" is used.');
      return false;
    } else {
      return true;
    };
  }

  private function _no_skppj_check($value, $id)
  {
    $id = (!is_null($id) && !empty($id)) ? $id : 0;
    $temp = $this->db->where(array('id !=' => $id, 'no_skppj' => $value))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('no_skppj_check', 'Then No. SKPPJ "' . $value . '" is used.');
      return false;
    } else {
      return true;
    };
  }

  public function getKategori()
  {
    $data = array(
      array('id' => 'Demosi', 'text' => 'Demosi'),
      array('id' => 'Mutasi', 'text' => 'Mutasi'),
    );
    return $data;
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT * FROM (
        SELECT
          dm.*,
          pg.nrp,
          pg.nama_lengkap,
          old_u.kode_unit AS old_kode_unit,
          old_u.nama_unit AS old_nama_unit,
          old_su.kode_sub_unit AS old_kode_sub_unit,
          old_su.nama_sub_unit AS old_nama_sub_unit,
          old_j.kode_jabatan AS old_kode_jabatan,
          old_j.nama_jabatan AS old_nama_jabatan,
          old_tu.kode_tenaga_unit AS old_kode_tenaga_unit,
          old_tu.nama_tenaga_unit AS old_nama_tenaga_unit,
          old_jp.nama_jenis_pegawai AS old_nama_jenis_pegawai,
          new_u.kode_unit AS new_kode_unit,
          new_u.nama_unit AS new_nama_unit,
          new_su.kode_sub_unit AS new_kode_sub_unit,
          new_su.nama_sub_unit AS new_nama_sub_unit,
          new_j.kode_jabatan AS new_kode_jabatan,
          new_j.nama_jabatan AS new_nama_jabatan,
          new_tu.kode_tenaga_unit AS new_kode_tenaga_unit,
          new_tu.nama_tenaga_unit AS new_nama_tenaga_unit,
          new_jp.nama_jenis_pegawai AS new_nama_jenis_pegawai
        FROM demosi_mutasi dm
        JOIN pegawai pg ON pg.id = dm.pegawai_id
        LEFT JOIN unit old_u ON old_u.id = dm.old_unit_id
        LEFT JOIN sub_unit old_su ON old_su.id = dm.old_sub_unit_id
        LEFT JOIN jabatan old_j ON old_j.id = dm.old_jabatan_id
        LEFT JOIN tenaga_unit old_tu ON old_tu.id = dm.old_tenaga_unit_id
        LEFT JOIN jenis_pegawai old_jp ON old_jp.id = dm.old_jenis_pegawai_id
        LEFT JOIN unit new_u ON new_u.id = dm.new_unit_id
        LEFT JOIN sub_unit new_su ON new_su.id = dm.new_sub_unit_id
        LEFT JOIN jabatan new_j ON new_j.id = dm.new_jabatan_id
        LEFT JOIN tenaga_unit new_tu ON new_tu.id = dm.new_tenaga_unit_id
        LEFT JOIN jenis_pegawai new_jp ON new_jp.id = dm.new_jenis_pegawai_id
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
      dm.*,
      pg.nrp,
      pg.nama_lengkap,
      old_u.kode_unit AS old_kode_unit,
      old_u.nama_unit AS old_nama_unit,
      old_su.kode_sub_unit AS old_kode_sub_unit,
      old_su.nama_sub_unit AS old_nama_sub_unit,
      old_j.kode_jabatan AS old_kode_jabatan,
      old_j.nama_jabatan AS old_nama_jabatan,
      old_tu.kode_tenaga_unit AS old_kode_tenaga_unit,
      old_tu.nama_tenaga_unit AS old_nama_tenaga_unit,
      old_jp.nama_jenis_pegawai AS old_nama_jenis_pegawai,
      new_u.kode_unit AS new_kode_unit,
      new_u.nama_unit AS new_nama_unit,
      new_su.kode_sub_unit AS new_kode_sub_unit,
      new_su.nama_sub_unit AS new_nama_sub_unit,
      new_j.kode_jabatan AS new_kode_jabatan,
      new_j.nama_jabatan AS new_nama_jabatan,
      new_tu.kode_tenaga_unit AS new_kode_tenaga_unit,
      new_tu.nama_tenaga_unit AS new_nama_tenaga_unit,
      new_jp.nama_jenis_pegawai AS new_nama_jenis_pegawai
    ');
    $this->db->from('demosi_mutasi AS dm');
    $this->db->join('pegawai AS pg', 'pg.id = dm.pegawai_id');
    $this->db->join('unit AS old_u', 'old_u.id = dm.old_unit_id', 'left');
    $this->db->join('sub_unit AS old_su', 'old_su.id = dm.old_sub_unit_id', 'left');
    $this->db->join('jabatan AS old_j', 'old_j.id = dm.old_jabatan_id', 'left');
    $this->db->join('tenaga_unit AS old_tu', 'old_tu.id = dm.old_tenaga_unit_id', 'left');
    $this->db->join('jenis_pegawai AS old_jp', 'old_jp.id = dm.old_jenis_pegawai_id', 'left');
    $this->db->join('unit AS new_u', 'new_u.id = dm.new_unit_id', 'left');
    $this->db->join('sub_unit AS new_su', 'new_su.id = dm.new_sub_unit_id', 'left');
    $this->db->join('jabatan AS new_j', 'new_j.id = dm.new_jabatan_id', 'left');
    $this->db->join('tenaga_unit AS new_tu', 'new_tu.id = dm.new_tenaga_unit_id', 'left');
    $this->db->join('jenis_pegawai AS new_jp', 'new_jp.id = dm.new_jenis_pegawai_id', 'left');
    $this->db->where($params);
    return $this->db->get()->row();
  }

  public function insert()
  {
    $this->load->model('PegawaiModel');
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->trans_begin();

      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->kategori = $this->input->post('kategori');
      $this->no_sk = $this->input->post('no_sk');
      $this->tanggal_sk = $this->input->post('tanggal_sk');
      $this->no_skppj = $this->input->post('no_skppj');
      $this->doj = $this->input->post('doj');
      $this->kode_pendidikan = $this->input->post('kode_pendidikan');
      $this->old_unit_id = $this->input->post('old_unit_id');
      $this->old_sub_unit_id = $this->input->post('old_sub_unit_id');
      $this->old_jabatan_id = $this->input->post('old_jabatan_id');
      $this->old_tenaga_unit_id = $this->input->post('old_tenaga_unit_id');
      $this->old_jenis_pegawai_id = $this->input->post('old_jenis_pegawai_id');
      $this->new_unit_id = $this->input->post('new_unit_id');
      $this->new_sub_unit_id = $this->input->post('new_sub_unit_id');
      $this->new_jabatan_id = $this->input->post('new_jabatan_id');
      $this->new_tenaga_unit_id = $this->input->post('new_tenaga_unit_id');
      $this->new_jenis_pegawai_id = $this->input->post('new_jenis_pegawai_id');
      $this->created_by = $this->session->userdata('user')['id'];
      $this->db->insert($this->_table, $this);

      // Update on employee
      $pegawaiPayload = array(
        'jenis_pegawai_id' => $this->new_jenis_pegawai_id,
        'unit_id' => $this->new_unit_id,
        'sub_unit_id' => $this->new_sub_unit_id,
        'jabatan_id' => $this->new_jabatan_id,
        'tenaga_unit_id' => $this->new_tenaga_unit_id,
      );
      $this->PegawaiModel->updateStatusKepegawaian($pegawaiPayload, $this->pegawai_id);
      // END ## Update on employee

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
    $this->load->model('PegawaiModel');
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->trans_begin();

      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->kategori = $this->input->post('kategori');
      $this->no_sk = $this->input->post('no_sk');
      $this->tanggal_sk = $this->input->post('tanggal_sk');
      $this->no_skppj = $this->input->post('no_skppj');
      $this->doj = $this->input->post('doj');
      $this->kode_pendidikan = $this->input->post('kode_pendidikan');
      $this->old_unit_id = $this->input->post('old_unit_id');
      $this->old_sub_unit_id = $this->input->post('old_sub_unit_id');
      $this->old_jabatan_id = $this->input->post('old_jabatan_id');
      $this->old_tenaga_unit_id = $this->input->post('old_tenaga_unit_id');
      $this->old_jenis_pegawai_id = $this->input->post('old_jenis_pegawai_id');
      $this->new_unit_id = $this->input->post('new_unit_id');
      $this->new_sub_unit_id = $this->input->post('new_sub_unit_id');
      $this->new_jabatan_id = $this->input->post('new_jabatan_id');
      $this->new_tenaga_unit_id = $this->input->post('new_tenaga_unit_id');
      $this->new_jenis_pegawai_id = $this->input->post('new_jenis_pegawai_id');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $id));

      // Update on employee
      $pegawaiPayload = array(
        'jenis_pegawai_id' => $this->new_jenis_pegawai_id,
        'unit_id' => $this->new_unit_id,
        'sub_unit_id' => $this->new_sub_unit_id,
        'jabatan_id' => $this->new_jabatan_id,
        'tenaga_unit_id' => $this->new_tenaga_unit_id,
      );
      $this->PegawaiModel->updateStatusKepegawaian($pegawaiPayload, $this->pegawai_id);
      // END ## Update on employee

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
