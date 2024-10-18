<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IndikatorGajiMappingItemModel extends CI_Model
{
  private $_table = 'indikator_gaji_mapping_item';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'indikator_gaji_mapping_id',
        'label' => 'Indikator Gaji Mapping',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'indikator_gaji_id',
        'label' => 'Indikator Gaji',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'expression',
        'label' => 'Expression',
        'rules' => 'trim'
      ],
    );
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
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->indikator_gaji_mapping_id = $this->input->post('indikator_gaji_mapping_id');
      $this->indikator_gaji_id = $this->input->post('indikator_gaji_id');
      $this->expression = $this->input->post('expression');
      $this->order_pos = $this->input->post('order_pos');
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
      $this->indikator_gaji_mapping_id = $this->input->post('indikator_gaji_mapping_id');
      $this->indikator_gaji_id = $this->input->post('indikator_gaji_id');
      $this->expression = $this->input->post('expression');
      $this->order_pos = $this->input->post('order_pos');
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
