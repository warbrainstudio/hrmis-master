<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CutiModel extends CI_Model
{
  private $_table = 'cuti';
  private $_tableView = '';

  public function rules($id = null, $pegawai_id = null)
  {
    return array(
      [
        'field' => 'tanggal_pengajuan',
        'label' => 'Tanggal Pengajuan',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'pegawai_id',
        'label' => 'Pegawai',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'jenis_cuti',
        'label' => 'Jenis Cuti',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'awal_cuti',
        'label' => 'Awal Cuti',
        'rules' => 'required|trim',
        'rules' => [
          'required',
          'trim',
          [
            'awal_cuti_exist',
            function ($value) use ($id, $pegawai_id) {
              return $this->_awal_cuti_exist($value, $id, $pegawai_id);
            }
          ]
        ]
      ],
      [
        'field' => 'akhir_cuti',
        'label' => 'Akhir Cuti',
        'rules' => 'required|trim',
        'rules' => [
          'required',
          'trim',
          [
            'akhir_cuti_exist',
            function ($value) use ($id, $pegawai_id) {
              return $this->_akhir_cuti_exist($value, $id, $pegawai_id);
            }
          ]
        ]
      ],
      [
        'field' => 'tanggal_bekerja',
        'label' => 'Tanggal Bekerja',
        'rules' => 'required|trim'
      ],
    );
  }

  private function _awal_cuti_exist($value, $id, $pegawai_id)
  {
    $temp = $this->db->where('pegawai_id', $pegawai_id)
                     ->group_start()
                     ->where('awal_cuti', $value)
                     ->or_where('akhir_cuti', $value)
                     ->group_end()
                     ->group_start()
                     ->where('status_persetujuan', null)
                     ->or_where('status_persetujuan', 'Disetujui')
                     ->group_end()
                     ->get($this->_table);
    if(empty($id)){
      if ($temp->num_rows() > 0) {
        $this->form_validation->set_message('awal_cuti_exist', 'date "' . $value . '" already exist.');
        return false;
      } else {
        return true;
      };
    }else{
      return true;
    }
  }

  private function _akhir_cuti_exist($value, $id, $pegawai_id)
  {
    $temp = $this->db->where('pegawai_id', $pegawai_id)
                     ->group_start()
                     ->where('awal_cuti', $value)
                     ->or_where('akhir_cuti', $value)
                     ->group_end()
                     ->group_start()
                     ->where('status_persetujuan', null)
                     ->or_where('status_persetujuan', 'Disetujui')
                     ->group_end()
                     ->get($this->_table);
    if(empty($id)){
      if ($temp->num_rows() > 0) {
        $this->form_validation->set_message('akhir_cuti_exist', 'date "' . $value . '" already exist.');
        return false;
      } else {
        return true;
      };
    }else{
      return true;
    }
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT 
            c.*,
            pg.nrp,
            pg.nama_lengkap,
            kp.nama_kategori_pegawai,
            kp.mkg,
            jp.nama_jenis_pegawai,
            u.kode_unit,
            u.nama_unit,
            su.kode_sub_unit,
            su.nama_sub_unit,
            j.kode_jabatan,
            j.nama_jabatan,
            tu.kode_tenaga_unit,
            tu.nama_tenaga_unit,
            (CASE WHEN pg.status_active = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END) AS nama_status_active
        FROM cuti c
        JOIN pegawai pg ON pg.id = c.pegawai_id
        LEFT JOIN kategori_pegawai kp ON kp.id = pg.kategori_pegawai_id
        LEFT JOIN jenis_pegawai jp ON jp.id = pg.jenis_pegawai_id
        LEFT JOIN unit u ON u.id = pg.unit_id
        LEFT JOIN sub_unit su ON su.id = pg.sub_unit_id
        LEFT JOIN jabatan j ON j.id = pg.jabatan_id
        LEFT JOIN tenaga_unit tu ON tu.id = pg.tenaga_unit_id
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
      cuti.*,
      pegawai.nrp,
      pegawai.nama_lengkap,
      pegawai.jenis_kelamin,
      pegawai.alamat_ktp,
      pegawai.no_hp,
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
    $this->db->from('cuti');
    $this->db->join('pegawai', 'pegawai.id = cuti.pegawai_id');
    $this->db->join('kategori_pegawai', 'kategori_pegawai.id = pegawai.kategori_pegawai_id', 'left');
    $this->db->join('jenis_pegawai', 'jenis_pegawai.id = pegawai.jenis_pegawai_id', 'left');
    $this->db->join('status_kontrak', 'status_kontrak.id = pegawai.status_kontrak_id', 'left');
    $this->db->join('unit', 'unit.id = pegawai.unit_id', 'left');
    $this->db->join('sub_unit', 'sub_unit.id = pegawai.sub_unit_id', 'left');
    $this->db->join('jabatan', 'jabatan.id = pegawai.jabatan_id', 'left');
    $this->db->join('tenaga_unit', 'tenaga_unit.id = pegawai.tenaga_unit_id', 'left');
    $this->db->where($params);
    return $this->db->get()->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');
    $jenis_detail = $this->input->post('jenis_cuti_detail');
    $jenis_cuti = '';
    if(!empty($jenis_detail)){
      $jenis_cuti = $jenis_detail;
    }else{
      $jenis_cuti = $this->input->post('jenis_cuti');
    }
    $jabatan_id = $this->input->post('jabatan_id');
    $query = $this->db->select('jabatan.*')
            ->from('jabatan')
            ->where('id',$jabatan_id)
            ->get()
            ->row();
    $jabatan = $query->nama_jabatan;
    if (strpos($jabatan, 'Manajer') !== false) {
      $jumlah_persetujuan = 2;
      $this->persetujuan_pertama = "<i class='zmdi zmdi-check'></i>";
    }else{
      $jumlah_persetujuan = 3;
    }
    try {
      $this->jenis_cuti = $jenis_cuti;
      $this->tanggal_pengajuan = $this->input->post('tanggal_pengajuan');
      $this->pegawai_id = $this->input->post('pegawai_id');
      $this->awal_cuti = $this->input->post('awal_cuti');
      $this->akhir_cuti = $this->input->post('akhir_cuti');
      $this->tanggal_bekerja = $this->input->post('tanggal_bekerja');
      $this->alamat_cuti = $this->input->post('alamat_cuti');
      $this->telepon_cuti = $this->input->post('telepon_cuti');
      $this->jumlah_persetujuan = $jumlah_persetujuan;
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
      $this->jenis_cuti = $this->input->post('jenis_cuti');
      $this->awal_cuti = $this->input->post('awal_cuti');
      $this->akhir_cuti = $this->input->post('akhir_cuti');
      $this->tanggal_bekerja = $this->input->post('tanggal_bekerja');
      $this->alamat_cuti = $this->input->post('alamat_cuti');
      $this->telepon_cuti = $this->input->post('telepon_cuti');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function approve($id)
  {
      $response = array('status' => false, 'data' => 'No operation.');
  
      try {
          $query = $this->db->select('pegawai_id, awal_cuti, akhir_cuti, 
                                      jumlah_persetujuan, persetujuan_pertama, persetujuan_kedua, 
                                      persetujuan_ketiga, status_persetujuan')
                            ->from($this->_table)
                            ->where('id', $id)
                            ->get()
                            ->row();
  
          if (!$query) {
              return array('status' => false, 'data' => 'No data found for the specified ID.');
          }
          $jumlah_p = $query->jumlah_persetujuan;
          $p1 = $query->persetujuan_pertama;
          $p2 = $query->persetujuan_kedua;
          $p3 = $query->persetujuan_ketiga;
          $ps = $query->status_persetujuan;
          $status = $this->input->post('persetujuan');
          $newStatus = $status . " " . $this->session->userdata('user')['role'];
          
              if($jumlah_p == 2){
                if (empty($p2)) {
                    if($status == 'Ditolak'){
                      $this->persetujuan_kedua = $newStatus;
                      $this->persetujuan_ketiga = $newStatus;
                      $this->status_persetujuan = $newStatus;
                    }else{
                      $this->persetujuan_kedua = $newStatus;
                    }
                } elseif (!empty($p2) && empty($p3)) {
                  if($status == 'Ditolak'){
                    $this->persetujuan_ketiga = $newStatus;
                    $this->status_persetujuan = $newStatus;
                  }else{
                    $this->persetujuan_ketiga = $newStatus;
                    $this->status_persetujuan = $newStatus;
                    $this->add_cuti_to_absen($query);
                  }
                }
              } else {
                if (empty($p1)) {
                  if($status == 'Ditolak'){
                    $this->persetujuan_pertama = $newStatus;
                    $this->persetujuan_kedua = $newStatus;
                    $this->persetujuan_ketiga = $newStatus;
                    $this->status_persetujuan = $newStatus;
                  }else{
                    $this->persetujuan_pertama = $newStatus;
                  }
                } elseif (!empty($p1) && empty($p2)) {
                    if($status == 'Ditolak'){
                      $this->persetujuan_kedua = $newStatus;
                      $this->persetujuan_ketiga = $newStatus;
                      $this->status_persetujuan = $newStatus;
                    }else{
                      $this->persetujuan_kedua = $newStatus;
                    }
                } elseif (!empty($p2) && empty($p3)) {
                    if($status == 'Ditolak'){
                      $this->persetujuan_ketiga = $newStatus;
                      $this->status_persetujuan = 'Dipertimbangkan';
                    }else{
                      $this->persetujuan_ketiga = $newStatus;
                      $this->status_persetujuan = $newStatus;
                      $this->add_cuti_to_absen($query);
                    }
                } elseif ($ps=='Dipertimbangkan'){
                  if($status == 'Ditolak'){
                    $this->persetujuan_ketiga = $newStatus;
                    $this->status_persetujuan = $newStatus;
                  }else{
                    $this->persetujuan_ketiga = $newStatus;
                    $this->status_persetujuan = $newStatus;
                    $this->add_cuti_to_absen($query);
                  }
                }
              }

          $this->updated_by = $this->session->userdata('user')['id'];
          $this->updated_date = date('Y-m-d H:i:s');
  
          // Update the database
          $this->db->update($this->_table, $this, array('id' => $id));
  
          $response = array('status' => true, 'data' => 'Data has been saved.');
      } catch (\Throwable $th) {
          $response = array('status' => false, 'data' => 'Failed to save your data: ' . $th->getMessage());
      }
  
      return $response;
  }

  public function add_cuti_to_absen($query){
    $pegawai_id = $query->pegawai_id;
    $awalCuti = $query->awal_cuti;
    $akhirCuti = $query->akhir_cuti;
    $awalTimestamp = strtotime($awalCuti);
    $akhirTimestamp = strtotime($akhirCuti);

    $query_pegawai = $this->db->select('absen_pegawai_id')
                      ->from("pegawai")
                      ->where('id', $pegawai_id)
                      ->get()
                      ->row();

    if ($awalTimestamp !== false && $akhirTimestamp !== false && $awalTimestamp <= $akhirTimestamp) {
        $absen_pegawai_id = $query_pegawai->absen_pegawai_id;
        $currentTimestamp = $awalTimestamp;
        $table = 'absen_pegawai';
        while ($currentTimestamp <= $akhirTimestamp) {
            $currentDate = date('Y-m-d', $currentTimestamp);
            $data = [
              'absen_id' => $absen_pegawai_id,
              'tanggal_absen' => $currentDate,
              'status' => "3",
              'created_by' => $this->session->userdata('user')['id']
            ];
            $this->db->insert($table, $data);
            $currentTimestamp = strtotime("+1 day", $currentTimestamp);
        }
    } else {
      $response = array('status' => false, 'data' => 'Invalid Date Range');
    }
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
    return preg_replace('/[^0-9]/', '', $number);
  }
}
