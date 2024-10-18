<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PegawaiModel extends CI_Model
{
  private $_table = 'pegawai';
  private $_tableView = '';

  public function rules($id = null)
  {
    return array(
      [
        'field' => 'nama_lengkap',
        'label' => 'Nama Lengkap',
        'rules' => 'required|trim|max_length[100]'
      ],
      [
        'field' => 'nrp',
        'label' => 'NRP',
        'rules' => [
          'required',
          'trim',
          'max_length[50]',
          [
            'nrp_exist',
            function ($value) use ($id) {
              return $this->_data_exist('nrp', 'NRP', $value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'kategori_pegawai_id',
        'label' => 'Kategori Pegawai',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'jenis_pegawai_id',
        'label' => 'Status Kerja',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'status_kontrak_id',
        'label' => 'Status Kontrak',
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
        'field' => 'jabatan_id',
        'label' => 'Jabatan',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'tempat_lahir',
        'label' => 'Tempat Lahir',
        'rules' => 'trim|max_length[150]'
      ],
      [
        'field' => 'tanggal_lahir',
        'label' => 'Tanggal Lahir',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'jenis_kelamin',
        'label' => 'Jenis Kelamin',
        'rules' => 'required|trim|max_length[10]'
      ],
      [
        'field' => 'status_kawin',
        'label' => 'Status Kawin',
        'rules' => 'required|trim|max_length[15]'
      ],
      [
        'field' => 'pendidikan_terakhir',
        'label' => 'Pendidikan Terakhir',
        'rules' => 'required|trim|max_length[50]'
      ],
      [
        'field' => 'no_hp',
        'label' => 'No. HP',
        'rules' => [
          'required',
          'trim',
          'max_length[13]',
          [
            'no_hp_exist',
            function ($value) use ($id) {
              return $this->_data_exist('no_hp', 'No. HP', $value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'alamat_ktp',
        'label' => 'Alamat Lengkap',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'no_ktp',
        'label' => 'No. KTP',
        'rules' => [
          'required',
          'trim',
          'max_length[16]',
          [
            'no_ktp_exist',
            function ($value) use ($id) {
              return $this->_data_exist('no_ktp', 'No. KTP', $value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'no_bpjs_kesehatan',
        'label' => 'No. BPJS Kesehatan',
        'rules' => [
          'trim',
          'max_length[13]',
          [
            'no_bpjs_kesehatan_exist',
            function ($value) use ($id) {
              return $this->_data_exist('no_bpjs_kesehatan', 'No. BPJS Kesehatan', $value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'no_bpjs_tk',
        'label' => 'Np. BPJS TK',
        'rules' => [
          'trim',
          'max_length[11]',
          [
            'no_bpjs_tk_exist',
            function ($value) use ($id) {
              return $this->_data_exist('no_bpjs_tk', 'No. BPJS TK', $value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'npwp',
        'label' => 'NPWP',
        'rules' => [
          'trim',
          'max_length[15]',
          [
            'npwp_exist',
            function ($value) use ($id) {
              return $this->_data_exist('npwp', 'NPWP', $value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'mcu',
        'label' => 'MCU',
        'rules' => 'trim|max_length[50]'
      ],
      [
        'field' => 'foto',
        'label' => 'Foto',
        'rules' => 'trim|max_length[255]'
      ],
      [
        'field' => 'status_active',
        'label' => 'Status Active',
        'rules' => 'required|trim'
      ],
    );
  }

  private function _data_exist($field, $label, $value, $id)
  {
    if (!empty(trim($value))) {
      $id = (!IS_NULL($id)) ? $id : 0;
      $temp = $this->db->where(array('id !=' => $id, $field => $value))->get($this->_table);

      if ($temp->num_rows() > 0) {
        $this->form_validation->set_message($field . '_exist', 'The ' . $label . ' "' . $value . '" has been used.');
        return false;
      };
    };
    return true;
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT
          p.*,
          kp.nama_kategori_pegawai,
          kp.mkg,
          jp.nama_jenis_pegawai,
          sk.nama_status_kontrak,
          u.kode_unit,
          u.nama_unit,
          su.kode_sub_unit,
          su.nama_sub_unit,
          j.kode_jabatan,
          j.nama_jabatan,
          tu.kode_tenaga_unit,
          tu.nama_tenaga_unit,
          (CASE WHEN p.status_active = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END) AS nama_status_active
        FROM pegawai p
        LEFT JOIN kategori_pegawai kp ON kp.id = p.kategori_pegawai_id
        LEFT JOIN jenis_pegawai jp ON jp.id = p.jenis_pegawai_id
        LEFT JOIN status_kontrak sk ON sk.id = p.status_kontrak_id
        LEFT JOIN unit u ON u.id = p.unit_id
        LEFT JOIN sub_unit su ON su.id = p.sub_unit_id
        LEFT JOIN jabatan j ON j.id = p.jabatan_id
        LEFT JOIN tenaga_unit tu ON tu.id = p.tenaga_unit_id
      ) t
      WHERE 1=1
    ";
    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getSearch($value = null)
  {
    $this->db->select('
      pegawai.*,
      unit.nama_unit,
      sub_unit.nama_sub_unit,
      jabatan.nama_jabatan,
      tenaga_unit.nama_tenaga_unit,
      status_kontrak.nama_status_kontrak,
      jenis_pegawai.nama_jenis_pegawai,
      kategori_pegawai.nama_kategori_pegawai
    ');
    $this->db->from($this->_table);
    $this->db->join('unit', 'unit.id = pegawai.unit_id', 'left');
    $this->db->join('sub_unit', 'sub_unit.id = pegawai.sub_unit_id', 'left');
    $this->db->join('jabatan', 'jabatan.id = pegawai.jabatan_id', 'left');
    $this->db->join('tenaga_unit', 'tenaga_unit.id = pegawai.tenaga_unit_id', 'left');
    $this->db->join('status_kontrak', 'status_kontrak.id = pegawai.status_kontrak_id', 'left');
    $this->db->join('jenis_pegawai', 'jenis_pegawai.id = pegawai.jenis_pegawai_id', 'left');
    $this->db->join('kategori_pegawai', 'kategori_pegawai.id = pegawai.kategori_pegawai_id', 'left');
    $this->db->where('status_active', 1);
    $this->db->like('lower(nrp)', $value);
    $this->db->or_like('lower(nama_lengkap)', $value);
    return $this->db->get()->result();
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
      pegawai.*,
      kategori_pegawai.nama_kategori_pegawai,
      kategori_pegawai.mkg,
      jenis_pegawai.nama_jenis_pegawai,
      status_kontrak.nama_status_kontrak,
      unit.kode_unit,
      unit.nama_unit,
      sub_unit.kode_sub_unit,
      sub_unit.nama_sub_unit,
      jabatan.kode_jabatan,
      jabatan.nama_jabatan,
      tenaga_unit.kode_tenaga_unit,
      tenaga_unit.nama_tenaga_unit
    ');
    $this->db->join('kategori_pegawai', 'kategori_pegawai.id = pegawai.kategori_pegawai_id', 'left');
    $this->db->join('jenis_pegawai', 'jenis_pegawai.id = pegawai.jenis_pegawai_id', 'left');
    $this->db->join('status_kontrak', 'status_kontrak.id = pegawai.status_kontrak_id', 'left');
    $this->db->join('unit', 'unit.id = pegawai.unit_id', 'left');
    $this->db->join('sub_unit', 'sub_unit.id = pegawai.sub_unit_id', 'left');
    $this->db->join('jabatan', 'jabatan.id = pegawai.jabatan_id', 'left');
    $this->db->join('tenaga_unit', 'tenaga_unit.id = pegawai.tenaga_unit_id', 'left');
    $this->db->where($params);
    $data = $this->db->get($this->_table);
    return $data->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->nama_lengkap = $this->input->post('nama_lengkap');
      $this->nrp = $this->input->post('nrp');
      $this->kategori_pegawai_id = $this->input->post('kategori_pegawai_id');
      $this->jenis_pegawai_id = $this->input->post('jenis_pegawai_id');
      $this->status_kontrak_id = $this->input->post('status_kontrak_id');
      $this->unit_id = $this->input->post('unit_id');
      $this->sub_unit_id = $this->input->post('sub_unit_id');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->tenaga_unit_id = $this->input->post('tenaga_unit_id');
      $this->alamat_ktp = $this->input->post('alamat_ktp');
      $this->tempat_lahir = $this->input->post('tempat_lahir');
      $this->tanggal_lahir = $this->input->post('tanggal_lahir');
      $this->jenis_kelamin = $this->input->post('jenis_kelamin');
      $this->status_kawin = $this->input->post('status_kawin');
      $this->pendidikan_terakhir = $this->input->post('pendidikan_terakhir');
      $this->no_ktp = $this->input->post('no_ktp');
      $this->no_bpjs_kesehatan = $this->input->post('no_bpjs_kesehatan');
      $this->no_bpjs_tk = $this->input->post('no_bpjs_tk');
      $this->npwp = $this->input->post('npwp');
      $this->no_hp = $this->input->post('no_hp');
      $this->mcu = $this->input->post('mcu');
      $this->foto = $this->input->post('foto');
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
      $temp = $this->getDetail(array('pegawai.id' => $id));
      $post_foto = $this->input->post('foto');
      $foto = (!is_null($post_foto) && !empty($post_foto)) ? $post_foto : $temp->foto;

      $this->nama_lengkap = $this->input->post('nama_lengkap');
      $this->nrp = $this->input->post('nrp');
      $this->kategori_pegawai_id = $this->input->post('kategori_pegawai_id');
      $this->jenis_pegawai_id = $this->input->post('jenis_pegawai_id');
      $this->status_kontrak_id = $this->input->post('status_kontrak_id');
      $this->unit_id = $this->input->post('unit_id');
      $this->sub_unit_id = $this->input->post('sub_unit_id');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->tenaga_unit_id = $this->input->post('tenaga_unit_id');
      $this->alamat_ktp = $this->input->post('alamat_ktp');
      $this->tempat_lahir = $this->input->post('tempat_lahir');
      $this->tanggal_lahir = $this->input->post('tanggal_lahir');
      $this->jenis_kelamin = $this->input->post('jenis_kelamin');
      $this->status_kawin = $this->input->post('status_kawin');
      $this->pendidikan_terakhir = $this->input->post('pendidikan_terakhir');
      $this->no_ktp = $this->input->post('no_ktp');
      $this->no_bpjs_kesehatan = $this->input->post('no_bpjs_kesehatan');
      $this->no_bpjs_tk = $this->input->post('no_bpjs_tk');
      $this->npwp = $this->input->post('npwp');
      $this->no_hp = $this->input->post('no_hp');
      $this->mcu = $this->input->post('mcu');
      $this->foto = $foto;
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

  public function updateStatusKepegawaian($data = array(), $pegawaiId = null)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $temp = $this->getDetail(array('pegawai.id' => $pegawaiId));

      $this->kategori_pegawai_id = isset($data['kategori_pegawai_id']) ? $data['kategori_pegawai_id'] : $temp->kategori_pegawai_id;
      $this->jenis_pegawai_id = isset($data['jenis_pegawai_id']) ? $data['jenis_pegawai_id'] : $temp->jenis_pegawai_id;
      $this->status_kontrak_id = isset($data['status_kontrak_id']) ? $data['status_kontrak_id'] : $temp->status_kontrak_id;
      $this->unit_id = isset($data['unit_id']) ? $data['unit_id'] : $temp->unit_id;
      $this->sub_unit_id = isset($data['sub_unit_id']) ? $data['sub_unit_id'] : $temp->sub_unit_id;
      $this->jabatan_id = isset($data['jabatan_id']) ? $data['jabatan_id'] : $temp->jabatan_id;
      $this->tenaga_unit_id = isset($data['tenaga_unit_id']) ? $data['tenaga_unit_id'] : $temp->tenaga_unit_id;
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $pegawaiId));

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
      $this->db->delete('sk_spk_pegawai', array('pegawai_id' => $id));
      $this->db->delete('kontrak_pegawai', array('pegawai_id' => $id));
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
