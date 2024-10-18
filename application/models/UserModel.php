<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{
  private $_table = 'user';
  private $_tableView = '';
  private $_columns = array(
    'email',
    'username',
    'nama_lengkap',
    'role'
  ); // Urutan (index) harus sama dengan template excel, dan penamaan harus sama dengan tabel (case-sensitive)
  private $_permittedChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@#$%^&*()_+';

  public function getColumnName($columnIndex)
  {
    $temp = array_combine(range(1, count($this->_columns)), array_values($this->_columns)); // Reset index to 1
    $result = (isset($temp[$columnIndex])) ? $temp[$columnIndex] : 0; // Get value
    return $result;
  }

  public function rules($id)
  {
    return array(
      [
        'field' => 'role',
        'label' => 'Role',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'unit',
        'label' => 'Unit',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'sub_unit',
        'label' => 'Sub Unit',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'nama_lengkap',
        'label' => 'Nama Lengkap',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'email',
        'label' => 'Email',
        'rules' => [
          'required',
          'trim',
          'valid_email',
          [
            'email_exist',
            function ($email) use ($id) {
              return $this->_email_exist($email, $id);
            }
          ]
        ]
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
            function ($username) use ($id) {
              return $this->_username_exist($username, $id);
            }
          ]
        ]
      ]
    );
  }

  public function rules_register()
  {
    return array(
      [
        'field' => 'role',
        'label' => 'Role',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'nama_lengkap',
        'label' => 'Nama Lengkap',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required|trim|valid_email|is_unique[user.email]'
      ],
      [
        'field' => 'username',
        'label' => 'Username',
        'rules' => 'required|trim|min_length[5]|max_length[12]|is_unique[user.username]|alpha_numeric'
      ],
      [
        'field' => 'password',
        'label' => 'Password',
        'rules' => 'required|matches[password_confirm]'
      ],
      [
        'field' => 'password_confirm',
        'label' => 'Password Confirm',
        'rules' => 'required'
      ]
    );
  }

  private function _email_exist($email, $id)
  {
    $id = (!IS_NULL($id)) ? $id : 0;
    $temp = $this->db->where(array('id !=' => $id, 'email' => $email))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('email_exist', 'Email "' . $email . '" has been used by other user.');
      return false;
    } else {
      return true;
    };
  }

  private function _username_exist($username, $id)
  {
    $id = (!IS_NULL($id)) ? $id : 0;
    $temp = $this->db->where(array('id !=' => $id, 'username' => $username))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('username_exist', 'Username "' . $username . '" has been used by other user.');
      return false;
    } else {
      return true;
    };
  }

  public function getAllComboUnitRekomendasi()
  {
    $this->db->select("id, nama_lengkap, unit, sub_unit, CONCAT(unit, ' | ', sub_unit) AS unit_concated, CONCAT(unit, ' | ', sub_unit, ' (', nama_lengkap, ')') AS unit_concated_with_name");
    $this->db->where_in('role', array('Kepala Unit RS', 'Kepala Unit PT'));
    $this->db->where('id !=', $this->session->userdata('user')['id']);
    $this->db->order_by('unit', 'asc');
    $this->db->order_by('sub_unit', 'asc');

    return $this->db->get($this->_table)->result();
  }

  public function getAllByRole($role = array(), $orderField = null, $orderBy = 'asc')
  {
    $this->db->where_in('role', $role);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    };

    return $this->db->get($this->_table)->result();
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
      $post = $this->input->post();
      $post['password'] = (isset($post['password']) && !empty($post['password'])) ? md5($this->input->post('password')) : null;

      $this->email = $this->input->post('email');
      $this->username = $this->input->post('username');
      $this->password = $post['password'];
      $this->nama_lengkap = $this->input->post('nama_lengkap');
      $this->role = $this->input->post('role');
      $this->profile_photo = $this->input->post('profile_photo');
      $this->unit = $this->input->post('unit');
      $this->sub_unit = $this->input->post('sub_unit');
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
      $temp = $this->getDetail(array('id' => $id));
      $post = $this->input->post();
      $post['password'] = (isset($post['password']) && !empty($post['password'])) ? md5($this->input->post('password')) : $temp->password;

      $this->email = $this->input->post('email');
      $this->username = $this->input->post('username');
      $this->password = $post['password'];
      $this->nama_lengkap = $this->input->post('nama_lengkap');
      $this->role = $this->input->post('role');
      $this->profile_photo = $this->input->post('profile_photo');
      $this->unit = $this->input->post('unit');
      $this->sub_unit = $this->input->post('sub_unit');
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function setPassword($username, $data)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->where(array('username' => $username));
      $this->db->set('password', $data);
      $this->db->update($this->_table);

      $response = array('status' => true, 'data' => 'Password has been changed.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to change password.');
    };

    return $response;
  }

  public function generateToken($params = array())
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $token = $this->generateRandom($this->_permittedChars, 255);

      $this->db->where($params);
      $this->db->set('token', $token);
      $this->db->update($this->_table);

      $response = array('status' => true, 'data' => 'Token has been generated.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to generate token.');
    };

    return $response;
  }

  public function generatePassword($params = array())
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->where($params);
      $this->db->set('password', 'md5(concat(id, username))', false);
      $this->db->update($this->_table);

      $response = array('status' => true, 'data' => 'Password has been generated.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to generate password.');
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

      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data.');
    };

    return $response;
  }

  function generateRandom($input, $strength = 16)
  {
    $input_length = strlen($input);
    $random_string = '';

    for ($i = 0; $i < $strength; $i++) {
      $random_character = $input[mt_rand(0, $input_length - 1)];
      $random_string .= $random_character;
    };

    return $random_string;
  }
}
