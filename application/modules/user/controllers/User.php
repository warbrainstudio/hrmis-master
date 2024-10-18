<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');
require_once(FCPATH . 'vendor/autoload.php');

class User extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppModel',
      'UserModel',
      'RoleModel',
      'UnitModel',
      'SubunitModel'
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('user'),
      'card_title' => $this->_pageTitle,
      'list_role' => $this->init_list($this->RoleModel->getAll(array(), 'name', 'asc'), 'name', 'name'),
      'list_unit' => $this->init_list($this->UnitModel->getAll(array(), 'nama_unit', 'asc'), 'nama_unit', 'nama_unit'),
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function ajax_getAll()
  {
    $this->handle_ajax_request();
    $dtAjax_config = array(
      'table_name' => 'user',
      'order_column' => 5,
      'order_column_dir' => 'desc'
    );
    $response = $this->AppModel->getData_dtAjax($dtAjax_config);
    echo json_encode($response);
  }

  public function ajax_save($id = null)
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->UserModel->rules($id));

    if ($this->form_validation->run() === true) {
      if (is_null($id)) {
        echo json_encode($this->UserModel->insert());
      } else {
        echo json_encode($this->UserModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->UserModel->delete($id));
  }

  public function ajax_get_sub_unit()
  {
    $this->handle_ajax_request();
    $unit = $this->input->post('unit');
    $unitData = $this->UnitModel->getDetail(array('LOWER(nama_unit)' => strtolower($unit)));
    $unitId = (!is_null($unitData)) ? $unitData->id : -1;
    $response = $this->init_list($this->SubunitModel->getAll(array('unit_id' => $unitId), 'nama_sub_unit', 'asc'), 'nama_sub_unit', 'nama_sub_unit');

    echo json_encode($response);
  }
}
