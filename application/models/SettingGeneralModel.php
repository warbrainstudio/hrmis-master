<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingGeneralModel extends CI_Model
{
  private $_table = 'setting';

  public function rules()
  {
    return array(
      [
        'field' => 'company_name',
        'label' => 'Company Name',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'company_slogan',
        'label' => 'Slogan',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'ppn_persentase',
        'label' => 'PPN',
        'rules' => 'required|trim'
      ]
    );
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
