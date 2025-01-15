<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Jadwalpegawai extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppMixModel',
      'JadwalModel',
      'UnitModel'
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    $config = $this->JadwalModel->getConfig();
    if(empty($config)){
      $data_config = [];
    }else{
      $data_config = $config[0];
    }

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('jadwalpegawai'),
      'card_title' => $this->_pageTitle,
      'list_unit' => $this->init_list($this->UnitModel->getAll(), 'id', 'nama_unit'),
      'data_config' => $data_config,
    );

    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function ajax_get_all()
  {
    $filter = $this->input->get('filter');
    $query = $this->JadwalModel->getQuery($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_save($id = null)
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->JadwalModel->rules());

    if ($this->form_validation->run() === true) {
      if (is_null($id)) {
        echo json_encode($this->JadwalModel->insert());
      } else {
        echo json_encode($this->JadwalModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_save_config()
  {
    $this->handle_ajax_request();
    $id = $this->input->post('id');
    if(empty($id)){
      echo json_encode($this->JadwalModel->insert_config());
    }else{
      echo json_encode($this->JadwalModel->update_config($id));
    }
  }


  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->JadwalModel->delete($id));
  }
}
