<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IndikatorGajiModel extends CI_Model
{
  private $_table = 'indikator_gaji';
  private $_tableView = '';

  public function rules($id = null)
  {
    return array(
      [
        'field' => 'nama_indikator_gaji',
        'label' => 'Nama Indikator Gaji',
        'rules' => 'required|trim|max_length[255]'
      ],
      [
        'field' => 'nama_alias',
        'label' => 'Alias',
        'rules' => 'required|trim|max_length[255]',
        'rules' => [
          'required',
          'trim',
          'alpha_dash',
          'max_length[255]',
          [
            'nama_alias_exist',
            function ($value) use ($id) {
              return $this->_nama_alias_exist($value, $id);
            }
          ]
        ]
      ],
      [
        'field' => 'default_expression',
        'label' => 'Default Expression',
        'rules' => 'trim'
      ],
    );
  }

  private function _nama_alias_exist($value, $id)
  {
    $id = (!IS_NULL($id)) ? $id : 0;
    $temp = $this->db->where(array('id !=' => $id, 'nama_alias' => $value))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('nama_alias_exist', 'The Alias "' . $value . '" already exist.');
      return false;
    } else {
      return true;
    };
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
      $this->nama_indikator_gaji = $this->input->post('nama_indikator_gaji');
      $this->nama_alias = $this->input->post('nama_alias');
      $this->default_expression = $this->input->post('default_expression');
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
      $this->nama_indikator_gaji = $this->input->post('nama_indikator_gaji');
      $this->nama_alias = $this->input->post('nama_alias');
      $this->default_expression = $this->input->post('default_expression');
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
