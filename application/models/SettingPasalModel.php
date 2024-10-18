<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingPasalModel extends CI_Model
{
  private $_table = 'setting';

  public function rules()
  {
    return array(
      [
        'field' => 'pasal',
        'label' => 'Content',
        'rules' => 'required|trim'
      ],
    );
  }

  public function getDetail()
  {
    $data = $this->db->get_where($this->_table, ['data' => 'pasal'])->row();
    return (is_null($data)) ? '' : $data->content;
  }

  public function update()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $post = $this->input->post();

      foreach ($post as $key => $value) {
        $this->content = $value;
        $this->db->update($this->_table, $this, array('data' => $key));
      };

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }
}
