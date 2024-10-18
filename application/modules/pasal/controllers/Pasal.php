<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Pasal extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array('SettingPasalModel'));
  }

  public function index()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('pasal'),
      'card_title' => 'Pasal'
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('form', $data, TRUE);
    $this->template->render();
  }

  public function content()
  {
    $this->handle_ajax_request();
    $pasal = $this->SettingPasalModel->getDetail();
    echo $pasal;
  }

  public function detail()
  {
    $this->handle_ajax_request();

    $agent = new Mobile_Detect;
    $actionLabel = '<span class="badge badge-info">View</span> ';

    $pasal = $this->SettingPasalModel->getDetail();
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('pasal'),
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'pasal' => $pasal,
    );
    $this->load->view('view', $data);
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->SettingPasalModel->rules());

    if ($this->form_validation->run() === true) {
      echo json_encode($this->SettingPasalModel->update());
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }
}
