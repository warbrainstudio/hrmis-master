<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingDashboardModel extends CI_Model
{
  private $_table = 'setting';

  public function rules()
  {
    return array(
      [
        'field' => 'dashboard_image_width',
        'label' => 'Width',
        'rules' => [
          'required',
          'trim',
          [
            'width_validate',
            function ($width) {
              return $this->_width_validate($width);
            }
          ]
        ]
      ],
      [
        'field' => 'dashboard_image_max_height',
        'label' => 'Max Height',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'dashboard_image_object_fit',
        'label' => 'Object Fit',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'dashboard_image_object_position',
        'label' => 'Object Position',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'dashboard_image_box_shadow',
        'label' => 'Box Shadow',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'dashboard_image_visibility',
        'label' => 'Visibility',
        'rules' => 'required|trim'
      ]
    );
  }

  private function _width_validate($width)
  {
    if ((int) ($width) >= 10 && (int) ($width) <= 100) {
      return true;
    } else {
      $this->form_validation->set_message('width_validate', 'Minimum of Width is 10% and maximum of 100%.');
      return false;
    };
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
