<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Employeefamily extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model([
      'AppMixModel',
      'PegawaiKeluargaModel',
      'HubunganKeluargaModel',
    ]);
    $this->load->library('form_validation');
  }

  public function index()
  {
    $agent = new Mobile_Detect;
    $pegawaiId = $this->input->get('ref');
    $actionRoute = $this->input->get('action_route');
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('employeefamily', false, array(
        'action_route' => $actionRoute,
        'pegawai_id' => $pegawaiId,
      )),
      'card_title' => $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'action_route' => $actionRoute,
      'pegawai_id' => $pegawaiId,
    );
    $this->template->set_template('sb_admin_partial');
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function input()
  {
    $this->handle_ajax_request();

    $agent = new Mobile_Detect;
    $ref = $this->input->get('ref');
    $ref = (!is_null($ref) && is_numeric($ref)) ? $ref : null;
    $actionLabel = (!is_null($ref) && is_numeric($ref)) ? 'Edit' : 'New';
    $actionLabel = '<span class="badge badge-info">' . $actionLabel . '</span> ';
    $pegawaiId = $this->input->get('pegawai_id');

    // Ref
    $uniqueId = md5(date('YmdHis'));
    $employeeFamily = $this->PegawaiKeluargaModel->getDetail(['pk.id' => $ref]);
    $hubunganList = $this->init_list($this->HubunganKeluargaModel->getAll(), 'id', 'text', @$employeeFamily->hubungan);
    // END ## Ref

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('employeefamily', false, array(
        'key' => $ref,
        'unique_id' => $uniqueId,
        'is_load_partial' => 1,
        'pegawai_id' => $pegawaiId,
      )),
      'key' => $ref,
      'unique_id' => $uniqueId,
      'card_title' => $actionLabel . 'Keluarga',
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'pegawai_id' => $pegawaiId,
      'employee_family' => $employeeFamily,
      'hubungan_list' => $hubunganList,
    );
    $this->template->set_template('sb_admin_modal_partial');
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('form', $data, TRUE);
    $this->template->render();
  }

  public function ajax_get_all()
  {
    $this->handle_ajax_request();
    $filter = $this->input->get('filter');
    $query = $this->PegawaiKeluargaModel->getQuery($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->PegawaiKeluargaModel->rules());
    $id = $this->input->post('ref');

    if ($this->form_validation->run() === true) {
      if (is_null($id) || empty($id)) {
        echo json_encode($this->PegawaiKeluargaModel->insert());
      } else {
        echo json_encode($this->PegawaiKeluargaModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->PegawaiKeluargaModel->delete($id));
  }
}
