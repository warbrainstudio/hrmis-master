<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingAccountModel extends CI_Model
{
  private $_table = 'user';

  public function rules()
  {
    return array(
      [
        'field' => 'email',
        'label' => 'Email',
        'rules' => [
          'required',
          'trim',
          'valid_email',
          [
            'email_exist',
            function ($email) {
              return $this->_email_exist($email);
            }
          ]
        ]
      ],
      [
        'field' => 'nama_lengkap',
        'label' => 'Nama Lengkap',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'username',
        'label' => 'Username',
        'rules' => [
          'required',
          'trim',
          'min_length[5]',
          'max_length[30]',
          'alpha_numeric',
          [
            'username_exist',
            function ($username) {
              return $this->_username_exist($username);
            }
          ]
        ]
      ]
    );
  }

  private function _email_exist($email)
  {
    $id = $this->session->userdata('user')['id'];
    $temp = $this->db->where(array('id !=' => $id, 'email' => $email))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('email_exist', 'Email "' . $email . '" has been used by other user.');
      return false;
    } else {
      return true;
    };
  }

  private function _username_exist($username)
  {
    $id = $this->session->userdata('user')['id'];
    $temp = $this->db->where(array('id !=' => $id, 'username' => $username))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('username_exist', 'Username "' . $username . '" has been used by other user.');
      return false;
    } else {
      return true;
    };
  }

  public function getDetail($params = array())
  {
    return $this->db->where($params)->get($this->_table)->row();
  }

  public function update()
  {
    $response = array('status' => false, 'data' => 'No operation.');
    $id = $this->session->userdata('user')['id'];

    try {
      $temp = $this->getDetail(array('id' => $id));
      $post = $this->input->post();
      $post['password'] = (isset($post['password']) && !empty($post['password'])) ? md5($this->input->post('password')) : $temp->password;
      $profile_photo = $this->input->post('profile_photo');
      $profile_photo_temp = (!is_null($profile_photo) && !empty($profile_photo)) ? $profile_photo : $temp->profile_photo;

      $this->email = $this->input->post('email');
      $this->username = $this->input->post('username');
      $this->password = $post['password'];
      $this->nama_lengkap = $this->input->post('nama_lengkap');
      $this->profile_photo = $profile_photo_temp;
      $this->db->update($this->_table, $this, array('id' => $id));

      $user = array(
        'id' => $temp->id,
        'email' => $this->input->post('email'),
        'username' => $this->input->post('username'),
        'nama_lengkap' => $this->input->post('nama_lengkap'),
        'role' => $temp->role,
        'unit' => $temp->unit,
        'sub_unit' => $temp->sub_unit,
        'profile_photo' => $profile_photo_temp,
        'token' => $temp->token,
        'is_login' => true
      );
      $this->session->set_userdata('user', $user);

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }
}
