<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KontrakPegawaiModel extends CI_Model
{
  private $_table = 'kontrak_pegawai';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'no_kontrak',
        'label' => 'No. Kontrak',
        'rules' => 'required|trim|max_length[50]'
      ],
      [
        'field' => 'pegawai_id',
        'label' => 'Pegawai',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'kategori_pegawai_id',
        'label' => 'Kategori Pegawai',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'jenis_pegawai_id',
        'label' => 'Status Pegawai',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'status_kontrak_id',
        'label' => 'Status Kontrak',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'jabatan_id',
        'label' => 'Jabatan',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'unit_id',
        'label' => 'Unit',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'sub_unit_id',
        'label' => 'Sub Unit',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'soc',
        'label' => 'SOC',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'eoc',
        'label' => 'EOC',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'status_active',
        'label' => 'Status Active',
        'rules' => 'required|trim'
      ],
    );
  }

  public function getIdType()
  {
    // ID ini didapat dari data pada tabel kontrak_pegawai
    return (object) array(
      'employee' => array(1, 2),
      'mitra' => array(3, 4, 5, 6)
    );
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT
          kp.*,
          p.nrp,
          p.nama_lengkap,
          kp2.nama_kategori_pegawai,
          kp2.mkg,
          j.kode_jabatan,
          j.nama_jabatan,
          u.kode_unit,
          u.nama_unit,
          su.kode_sub_unit,
          su.nama_sub_unit,
          jp.nama_jenis_pegawai,
          sk.nama_status_kontrak
        FROM kontrak_pegawai kp
        LEFT JOIN pegawai p ON p.id = kp.pegawai_id
        LEFT JOIN kategori_pegawai kp2 ON kp2.id = kp.kategori_pegawai_id
        LEFT JOIN jabatan j ON j.id = kp.jabatan_id
        LEFT JOIN unit u ON u.id = kp.unit_id
        LEFT JOIN sub_unit su ON su.id = kp.sub_unit_id
        LEFT JOIN jenis_pegawai jp ON jp.id = kp.jenis_pegawai_id
        LEFT JOIN status_kontrak sk ON sk.id = kp.status_kontrak_id
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
      kontrak_pegawai.*,
      pegawai.nrp,
      pegawai.nama_lengkap,
      pegawai.no_ktp,
      pegawai.tempat_lahir,
      pegawai.tanggal_lahir,
      pegawai.alamat_ktp,
      kategori_pegawai.nama_kategori_pegawai,
      kategori_pegawai.mkg,
      jabatan.kode_jabatan,
      jabatan.nama_jabatan,
      unit.kode_unit,
      unit.nama_unit,
      sub_unit.kode_sub_unit,
      sub_unit.nama_sub_unit,
      jenis_pegawai.nama_jenis_pegawai,
      status_kontrak.nama_status_kontrak,
      tenaga_unit.kode_tenaga_unit,
      tenaga_unit.nama_tenaga_unit
    ');
    $this->db->join('pegawai', 'pegawai.id = kontrak_pegawai.pegawai_id', 'left');
    $this->db->join('kategori_pegawai', 'kategori_pegawai.id = kontrak_pegawai.kategori_pegawai_id', 'inner');
    $this->db->join('jabatan', 'jabatan.id = kontrak_pegawai.jabatan_id', 'inner');
    $this->db->join('unit', 'unit.id = kontrak_pegawai.unit_id', 'inner');
    $this->db->join('sub_unit', 'sub_unit.id = kontrak_pegawai.sub_unit_id', 'left');
    $this->db->join('jenis_pegawai', 'jenis_pegawai.id = kontrak_pegawai.jenis_pegawai_id', 'left');
    $this->db->join('status_kontrak', 'status_kontrak.id = kontrak_pegawai.status_kontrak_id', 'inner');
    $this->db->join('tenaga_unit', 'tenaga_unit.id = pegawai.tenaga_unit_id', 'left');
    $this->db->where($params);
    $data = $this->db->get($this->_table);
    return $data->row();
  }

  public function getGaji($unitId = null, $subUnitId = null, $jabatanId = null, $jenisPegawaiId = null)
  {
    $mapping = $this->db->select('id')->where(['unit_id' => $unitId, 'sub_unit_id' => $subUnitId, 'jabatan_id' => $jabatanId, 'jenis_pegawai_id' => $jenisPegawaiId])->get('indikator_gaji_mapping')->row();
    $data = array();

    if (!is_null($mapping)) {
      $gajiMappingId = $mapping->id;

      $this->db->select('
        igi.id,
        igi.indikator_gaji_id,
        ig.nama_indikator_gaji,
        ig.nama_alias,
        igi."expression",
        igi.order_pos 
      ');
      $this->db->from('indikator_gaji_mapping_item as igi');
      $this->db->join('indikator_gaji as ig', 'ig.id = igi.indikator_gaji_id');
      $this->db->where('igi.indikator_gaji_mapping_id', $gajiMappingId);
      $this->db->order_by('igi.order_pos', 'asc');
      $data = $this->db->get()->result();
    };

    return $data;
  }

  public function insert()
  {
    $this->load->model('PegawaiModel');
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->trans_begin();

      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->kategori_pegawai_id = $this->input->post('kategori_pegawai_id');
      $this->no_kontrak = $this->input->post('no_kontrak');
      $this->soc = $this->input->post('soc');
      $this->eoc = $this->input->post('eoc');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->unit_id = $this->input->post('unit_id');
      $this->sub_unit_id = $this->input->post('sub_unit_id');
      $this->jenis_pegawai_id = $this->input->post('jenis_pegawai_id');
      $this->status_kontrak_id = $this->input->post('status_kontrak_id');
      $this->status_active = $this->input->post('status_active');
      $this->created_by = $this->session->userdata('user')['id'];

      if ($this->status_active == 1) {
        // Update on employee
        $pegawaiPayload = array(
          'kategori_pegawai_id' => $this->kategori_pegawai_id,
          'jenis_pegawai_id' => $this->jenis_pegawai_id,
          'status_kontrak_id' => $this->status_kontrak_id,
          'unit_id' => $this->unit_id,
          'sub_unit_id' => $this->sub_unit_id,
          'jabatan_id' => $this->jabatan_id
        );
        $this->PegawaiModel->updateStatusKepegawaian($pegawaiPayload, $this->pegawai_id);

        // Non active existing contract
        $this->db->update($this->_table, array('status_active' => 0), array('pegawai_id' => $this->pegawai_id));
      };

      // Insert current contract
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
    $this->load->model('PegawaiModel');
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->trans_begin();

      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->kategori_pegawai_id = $this->input->post('kategori_pegawai_id');
      $this->no_kontrak = $this->input->post('no_kontrak');
      $this->soc = $this->input->post('soc');
      $this->eoc = $this->input->post('eoc');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->unit_id = $this->input->post('unit_id');
      $this->sub_unit_id = $this->input->post('sub_unit_id');
      $this->jenis_pegawai_id = $this->input->post('jenis_pegawai_id');
      $this->status_kontrak_id = $this->input->post('status_kontrak_id');
      $this->status_active = $this->input->post('status_active');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');

      if ($this->status_active == 1) {
        // Update on employee
        $pegawaiPayload = array(
          'kategori_pegawai_id' => $this->kategori_pegawai_id,
          'jenis_pegawai_id' => $this->jenis_pegawai_id,
          'status_kontrak_id' => $this->status_kontrak_id,
          'unit_id' => $this->unit_id,
          'sub_unit_id' => $this->sub_unit_id,
          'jabatan_id' => $this->jabatan_id
        );
        $this->PegawaiModel->updateStatusKepegawaian($pegawaiPayload, $this->pegawai_id);

        // Non active existing contract
        $this->db->update($this->_table, array('status_active' => 0), array('pegawai_id' => $this->pegawai_id));
      };

      // Update current contract
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
