<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IndikatorGajiMappingModel extends CI_Model
{
  private $_table = 'indikator_gaji_mapping';
  private $_tableView = '';

  public function rules()
  {
    return array(
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
        'field' => 'jenis_pegawai_id',
        'label' => 'Status Pegawai',
        'rules' => 'required|trim'
      ],
    );
  }

  public function getQuery($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT
          i.*,
          u.nama_unit,
          su.nama_sub_unit,
          j.nama_jabatan,
          jp.nama_jenis_pegawai
        FROM indikator_gaji_mapping i
        LEFT JOIN unit u ON u.id = i.unit_id
        LEFT JOIN sub_unit su ON su.id = i.sub_unit_id
        LEFT JOIN jabatan j ON j.id = i.jabatan_id
        LEFT JOIN jenis_pegawai jp ON jp.id = i.jenis_pegawai_id
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
    return $this->db->where($params)->get($this->_table)->row();
  }

  private function _handleIndikatorItem($mappingGajiId = null)
  {
    $indikator = $this->input->post('indikator');
    $payloadInsert = array();
    $payloadInsert_trans =  true;
    $payloadUpdate = array();
    $payloadUpdate_trans = true;
    $payloadDelete = array();
    $payloadDelete_trans = true;
    $orderPos = 0;

    if (!is_null($indikator) && count($indikator) > 0) {
      // Get existing items
      $temp = $this->db->select('id')->from('indikator_gaji_mapping_item')->where('indikator_gaji_mapping_id', $mappingGajiId)->get()->result();
      $tempDataId = array();

      // Collect payload : Update & insert
      foreach ($indikator as $index => $item) {
        $id = $item['id'];
        $indikatorGajiId = $item['indikator_gaji_id'];
        $expression = $item['expression'];
        $orderPos = $orderPos + 1;

        if (!is_null($id) && !empty($id)) {
          $payloadUpdate[] = [
            'id' => $id,
            'indikator_gaji_mapping_id' => $mappingGajiId,
            'indikator_gaji_id' => $indikatorGajiId,
            'expression' => $expression,
            'order_pos' => $orderPos
          ];
        } else {
          $payloadInsert[] = [
            'indikator_gaji_mapping_id' => $mappingGajiId,
            'indikator_gaji_id' => $indikatorGajiId,
            'expression' => $expression,
            'order_pos' => $orderPos
          ];
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
        $payloadInsert_trans = $this->db->insert_batch('indikator_gaji_mapping_item', $payloadInsert);
        $payloadInsert_trans = ($payloadInsert_trans !== false) ? true : false;
      } else {
        $payloadInsert_trans =  true;
      };

      // Update transaction
      if (count($payloadUpdate) > 0) {
        $payloadUpdate_trans = $this->db->update_batch('indikator_gaji_mapping_item', $payloadUpdate, 'id');
        $payloadUpdate_trans = ($payloadUpdate_trans !== false) ? true : false;
      } else {
        $payloadUpdate_trans = true;
      };

      // Delete transaction
      if (count($payloadDelete) > 0) {
        $this->db->where_in('id', $payloadDelete);
        $payloadDelete_trans = $this->db->delete('indikator_gaji_mapping_item');
        $payloadDelete_trans = ($payloadDelete_trans !== false) ? true : false;
      } else {
        $payloadDelete_trans = true;
      };
    };

    return ($payloadInsert_trans && $payloadUpdate_trans && $payloadDelete_trans) ? true : false;
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->trans_begin();

      $this->unit_id = $this->input->post('unit_id');
      $this->sub_unit_id = $this->input->post('sub_unit_id');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->jenis_pegawai_id = $this->input->post('jenis_pegawai_id');
      $this->db->insert($this->_table, $this);

      $indikatorItem_trans = $this->_handleIndikatorItem($this->db->insert_id());

      if ($this->db->trans_status() === false && $indikatorItem_trans === false) {
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

      $this->unit_id = $this->input->post('unit_id');
      $this->sub_unit_id = $this->input->post('sub_unit_id');
      $this->jabatan_id = $this->input->post('jabatan_id');
      $this->jenis_pegawai_id = $this->input->post('jenis_pegawai_id');
      $this->db->update($this->_table, $this, array('id' => $id));

      $indikatorItem_trans = $this->_handleIndikatorItem($id);

      if ($this->db->trans_status() === false && $indikatorItem_trans === false) {
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
      $this->db->delete('indikator_gaji_mapping_item', array('indikator_gaji_mapping_id' => $id));

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
