<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DiklatModel extends CI_Model
{
  private $_table = 'diklat';
  private $_tableView = '';

  public function rules($id)
  {
    return array(
      [
        'field' => 'tipe',
        'label' => 'Kategori',
        'rules' => 'required|trim|max_length[15]'
      ],
      [
        'field' => 'nama_pelatihan',
        'label' => 'Nama Pelatihan',
        'rules' => 'required|trim|max_length[255]'
      ],
      [
        'field' => 'tanggal_mulai',
        'label' => 'Tanggal Mulai',
        'rules' => 'required'
      ],
      [
        'field' => 'tanggal_selesai',
        'label' => 'Tanggal Selesai',
        'rules' => 'required'
      ],
      [
        'field' => 'tempat_pelatihan',
        'label' => 'Tempat Pelatihan',
        'rules' => 'trim|max_length[255]'
      ],
      [
        'field' => 'template_sertifikat',
        'label' => 'Template Sertifikat',
        'rules' => [
          [
            'template_sertifikat_check',
            function () {
              return $this->_template_sertifikat_check();
            }
          ]
        ]
      ],
      [
        'field' => 'participant',
        'label' => 'Peserta',
        'rules' => [
          [
            'participant_check',
            function () {
              return $this->_participant_check();
            }
          ]
        ]
      ],
    );
  }

  private function _template_sertifikat_check()
  {
    $tipe = $this->input->post('tipe');
    $templateSertifikatTemp = $this->input->post('template_sertifikat_temp');
    if ($tipe === 'Internal' && (is_null($templateSertifikatTemp) || empty($templateSertifikatTemp)) && empty($_FILES['template_sertifikat']['name'])) {
      $this->form_validation->set_message('template_sertifikat_check', 'The Template Sertifikat field is required.');
      return false;
    };
    return true;
  }

  private function _participant_check()
  {
    $peserta = $this->input->post('participant');
    if (is_null($peserta) && empty($peserta)) {
      $this->form_validation->set_message('participant_check', 'The Peserta field is required.');
      return false;
    };
    return true;
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT * FROM (
        SELECT *, (SELECT COUNT(id) FROM diklat_peserta WHERE diklat_id = diklat.id) AS total_peserta
        FROM $this->_table
      ) t
      WHERE 1=1
    ";
    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getQueryByEmployee($filter = null)
  {
    $query = "
      SELECT DISTINCT d.*, p.sertifikat_file_name, p.id as diklat_peserta_id
      FROM $this->_table AS d
      LEFT JOIN diklat_peserta AS p ON d.id = p.diklat_id
      WHERE 1=1
    ";
    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getDetailDiklatById($diklatPesertaId = null)
  {
    $query = "
      SELECT DISTINCT
        d.*,
        p.id as diklat_peserta_id,
        p.sertifikat_file_name,
        p.pegawai_id,
        p.nomor_sertifikat,
        p.sebagai,
        p2.nrp,
        p2.nama_lengkap,
        p2.unit_id,
        p2.sub_unit_id,
        p2.jabatan_id,
        p2.tenaga_unit_id,
        u.kode_unit,
        u.nama_unit,
        su.kode_sub_unit,
        su.nama_sub_unit,
        j.kode_jabatan,
        j.nama_jabatan,
        tu.kode_tenaga_unit,
        tu.nama_tenaga_unit
      FROM diklat AS d
      LEFT JOIN diklat_peserta AS p ON d.id = p.diklat_id
      JOIN pegawai AS p2 ON p2.id = p.pegawai_id 
      LEFT JOIN unit AS u ON u.id = p2.unit_id
      LEFT JOIN sub_unit AS su ON su.id = p2.sub_unit_id 
      LEFT JOIN jabatan AS j ON j.id = p2.jabatan_id
      LEFT JOIN tenaga_unit AS tu ON tu.id = p2.tenaga_unit_id
      WHERE p.id = '$diklatPesertaId'
    ";
    $data = $this->db->query($query)->row();
    return $data;
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
    $this->db->where($params);
    return $this->db->get($this->_table)->row();
  }

  private function _handleDiklatPeserta($diklatId = null)
  {
    $peserta = $this->input->post('participant');
    $payloadInsert = array();
    $payloadInsert_trans =  true;
    $payloadUpdate = array();
    $payloadUpdate_trans = true;
    $payloadDelete = array();
    $payloadDelete_trans = true;

    if (!is_null($peserta) && count($peserta) > 0) {
      // Get existing items
      $temp = $this->db->select('id, sertifikat_file_raw_name, sertifikat_file_name')->from('diklat_peserta')->where('diklat_id', $diklatId)->get()->result();
      $tempDataId = array();

      // Collect payload : Update & insert
      foreach ($peserta as $index => $item) {
        $id = $item['id'];
        $pegawaiId = $item['pegawai_id'];
        $nomorSertifikat = $item['nomor_sertifikat'];
        $sebagai = $item['sebagai'];

        // Extract sertifikat file
        $sertifikatDirectory = 'diklat';
        $sertifikatFile = @$_FILES['participant'];
        $sertifikatFile = array(
          'name' => @$sertifikatFile['name'][$index]['sertifikat'],
          'type' => @$sertifikatFile['type'][$index]['sertifikat'],
          'tmp_name' => @$sertifikatFile['tmp_name'][$index]['sertifikat'],
          'error' => @$sertifikatFile['error'][$index]['sertifikat'],
          'size' => @$sertifikatFile['size'][$index]['sertifikat'],
        );
        $sertifikatPayload = array(
          'sertifikat_file_raw_name' => $this->searchInArrayObj($temp, 'id', $id, 'sertifikat_file_raw_name'),
          'sertifikat_file_name' => $this->searchInArrayObj($temp, 'id', $id, 'sertifikat_file_name')
        );

        if (!empty($sertifikatFile['name'])) {
          $cpUpload = new CpUpload();
          $upload = $cpUpload->run($sertifikatFile, $sertifikatDirectory, true, true, 'pdf', true);

          if ($upload->status === true) {
            $sertifikatPayload = array(
              'sertifikat_file_raw_name' => $upload->data->raw_name . $upload->data->file_ext,
              'sertifikat_file_name' => $upload->data->base_path
            );
          };
        };
        // END ## Extract sertifikat file

        if (!is_null($id) && !empty($id)) {
          $fields = array(
            'id' => $id,
            'diklat_id' => $diklatId,
            'pegawai_id' => $pegawaiId,
            'nomor_sertifikat' => $nomorSertifikat,
            'sebagai' => $sebagai
          );
          $payloadUpdate[] = array_merge($fields, $sertifikatPayload);
        } else {
          $fields = array(
            'diklat_id' => $diklatId,
            'pegawai_id' => $pegawaiId,
            'nomor_sertifikat' => $nomorSertifikat,
            'sebagai' => $sebagai
          );
          $payloadInsert[] = array_merge($fields, $sertifikatPayload);
        };

        array_push($tempDataId, $id);
      };

      // Collect payload : Delete
      if (count($temp) > 0) {
        foreach ($temp as $index => $item) {
          if (!in_array($item->id, $tempDataId)) {
            array_push($payloadDelete, $item->id);
          }
        };
      };

      // Insert transaction
      if (count($payloadInsert) > 0) {
        $payloadInsert_trans = $this->db->insert_batch('diklat_peserta', $payloadInsert);
        $payloadInsert_trans = ($payloadInsert_trans !== false) ? true : false;
      } else {
        $payloadInsert_trans =  true;
      };

      // Update transaction
      if (count($payloadUpdate) > 0) {
        $payloadUpdate_trans = $this->db->update_batch('diklat_peserta', $payloadUpdate, 'id');
        $payloadUpdate_trans = ($payloadUpdate_trans !== false) ? true : false;
      } else {
        $payloadUpdate_trans = true;
      };

      // Delete transaction
      if (count($payloadDelete) > 0) {
        $this->db->where_in('id', $payloadDelete);
        $payloadDelete_trans = $this->db->delete('diklat_peserta');
        $payloadDelete_trans = ($payloadDelete_trans !== false) ? true : false;
      } else {
        $payloadDelete_trans = true;
      };
    };

    return ($payloadInsert_trans && $payloadUpdate_trans && $payloadDelete_trans) ? true : false;
  }

  private function _handleUploadTemplateSertifikat()
  {
    $directory = 'diklatsertifikat';
    $file = $_FILES['template_sertifikat'];
    $result = null;

    if (!empty($file['name'])) {
      $cpUpload = new CpUpload();
      $upload = $cpUpload->run($file, $directory, true, true, 'docx', true);

      if ($upload->status === true) {
        $result = $upload->data->base_path;
      };
    };

    return $result;
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->trans_begin();

      $templateSertifikat = $this->_handleUploadTemplateSertifikat();

      $this->nama_pelatihan = $this->input->post('nama_pelatihan');
      $this->tanggal_mulai = $this->input->post('tanggal_mulai');
      $this->tanggal_selesai = $this->input->post('tanggal_selesai');
      $this->tempat_pelatihan = $this->input->post('tempat_pelatihan');
      $this->template_sertifikat = $templateSertifikat;
      $this->keterangan = $this->input->post('keterangan');
      $this->tipe = $this->input->post('tipe');
      $this->created_by = $this->session->userdata('user')['id'];
      $this->db->insert($this->_table, $this);

      $diklatPeserta_trans = $this->_handleDiklatPeserta($this->db->insert_id());

      if ($this->db->trans_status() === false && $diklatPeserta_trans === false) {
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

      $temp = $this->getDetail(array('id' => $id));
      $templateSertifikat = $this->_handleUploadTemplateSertifikat();
      $templateSertifikat = (!is_null($templateSertifikat)) ? $templateSertifikat : $temp->template_sertifikat;

      $this->nama_pelatihan = $this->input->post('nama_pelatihan');
      $this->tanggal_mulai = $this->input->post('tanggal_mulai');
      $this->tanggal_selesai = $this->input->post('tanggal_selesai');
      $this->tempat_pelatihan = $this->input->post('tempat_pelatihan');
      $this->template_sertifikat = $templateSertifikat;
      $this->keterangan = $this->input->post('keterangan');
      $this->tipe = $this->input->post('tipe');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $id));

      $diklatPeserta_trans = $this->_handleDiklatPeserta($id);

      if ($this->db->trans_status() === false && $diklatPeserta_trans === false) {
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
      $this->db->delete('diklat_peserta', array('diklat_id' => $id));

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

  public function searchInArrayObj($array, $key, $value, $getField = null)
  {
    $result = null;

    foreach ($array as $index => $item) {
      $item = (object) $item;
      if ($item->{$key} == $value) {
        if (!is_null($getField)) {
          $result = (isset($item->{$getField})) ? $item->{$getField} : null;
        } else {
          $result = $item;
        };
      };
    };

    return $result;
  }
}
