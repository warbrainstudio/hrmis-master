<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingAppModel extends CI_Model
{
  private $_table = 'setting';

  public function rules()
  {
    return array(
      [
        'field' => 'app_name',
        'label' => 'App Name',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'app_version',
        'label' => 'App Version',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'template_backend',
        'label' => 'Template Backend',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'theme_color',
        'label' => 'Theme Color',
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
