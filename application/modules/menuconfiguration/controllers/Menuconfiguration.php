<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Menuconfiguration extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppModel',
      'MenuModel',
      'RoleModel'
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('menuconfiguration'),
      'card_title' => 'Konfigurasi Menu',
      'list_role' => $this->init_list($this->RoleModel->getAll(array(), 'name', 'asc'), 'name', 'name'),
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function ajax_getAll()
  {
    $this->handle_ajax_request();
    $dtAjax_config = array(
      'table_name' => 'view_menu',
      'order_column' => 7
    );
    $response = $this->AppModel->getData_dtAjax($dtAjax_config);
    echo json_encode($response);
  }

  public function ajax_getRefAll()
  {
    $this->handle_ajax_request();
    $response['data'] = $this->MenuModel->getAll(array('parent_id' => null), 'name', 'asc');
    echo json_encode($response);
  }

  public function ajax_save($id = null)
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->MenuModel->rules());

    if ($this->form_validation->run() === true) {
      if (is_null($id)) {
        echo json_encode($this->MenuModel->insert());
      } else {
        echo json_encode($this->MenuModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->MenuModel->delete($id));
  }
}
