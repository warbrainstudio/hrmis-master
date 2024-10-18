<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Login extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'LoginModel',
      'UserModel'
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('login'),
      'page_title' => 'Login | ' . $this->app()->app_name
    );
    $this->load->view('index', $data);
  }

  public function ajax_submit()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->LoginModel->rules());

    if ($this->form_validation->run() === true) {
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      $temp = $this->LoginModel->getDetail(array('username' => $username, 'password' => $password));

      if (!is_null($temp)) {
        $user = array(
          'id' => $temp->id,
          'email' => $temp->email,
          'username' => $temp->username,
          'nama_lengkap' => $temp->nama_lengkap,
          'role' => $temp->role,
          'unit' => $temp->unit,
          'unit_uid' => $temp->unit_uid,
          'sub_unit' => $temp->sub_unit,
          'profile_photo' => $temp->profile_photo,
          'token' => $temp->token,
          'is_login' => true,
        );
        $this->session->set_userdata('user', $user);

        echo json_encode(array('status' => true, 'data' => 'Successfully login.'));
      } else {
        echo json_encode(array('status' => false, 'data' => 'Username or Password is wrong, try again.'));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }
}
