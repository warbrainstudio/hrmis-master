<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoginModel extends CI_Model
{
  private $_table = 'user';

  public function rules()
  {
    return array(
      [
        'field' => 'username',
        'label' => 'Username',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'password',
        'label' => 'Password',
        'rules' => 'required'
      ]
    );
  }

  public function getDetail($params = array())
  {
    $username = $params['username'];
    $password = $params['password'];

    $this->db->select('user.*, unit.uid as unit_uid');
    $this->db->join('unit', 'unit.nama_unit = user.unit', 'left');
    $this->db->where('password', md5($password));
    $this->db->group_start();
    $this->db->where('username', $username);
    $this->db->or_where('email', $username);
    $this->db->group_end();
    $query = $this->db->get($this->_table);

    return $query->row();
  }
}
