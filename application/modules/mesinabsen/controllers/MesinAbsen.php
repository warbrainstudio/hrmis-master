<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class MesinAbsen extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppMixModel',
      'MesinAbsenModel',
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    /*$machines = $this->MesinModel->getAll();
    $result = $this->init_list($machines, 'id', 'ipadress');

    foreach ($result as $machine) {
        $ipAddress = $machine['ipadress'];
        $this->checkConnect($ipAddress);
    }*/
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('mesinabsen'),
      'card_title' => 'Mesin',
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function ajax_get_all()
  {
    $filter = $this->input->get('filter');
    $query = $this->MesinAbsenModel->getQuery($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();
    $id = $this->input->post('ref');
    $this->form_validation->set_rules($this->MesinAbsenModel->rules($id));

    if ($this->form_validation->run() === true) {
      if (is_null($id) || empty($id)) {
        echo json_encode($this->MesinAbsenModel->insert());
      } else {
        echo json_encode($this->MesinAbsenModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_check($ip)
  {
    $this->handle_ajax_request();
    echo json_encode($this->MesinAbsenModel->checkConnect($ip));
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->MesinAbsenModel->delete($id));
  }
}
