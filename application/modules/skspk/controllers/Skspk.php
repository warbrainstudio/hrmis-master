<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Skspk extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model([
      'AppMixModel',
      'SkSpkPegawaiModel',
      'RefSkSpkModel',
      'PegawaiModel',
      'UnitModel',
      'SubunitModel',
      'JabatanModel',
      'RuanganModel',
    ]);
    $this->load->library('form_validation');
  }

  public function index()
  {
    $agent = new Mobile_Detect;
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('skspk'),
      'card_title' => $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
    );
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

    // Ref
    $skSpk = $this->SkSpkPegawaiModel->getDetail(['sk_spk_pegawai.id' => $ref]);
    $pegawai = $this->PegawaiModel->getDetail(['pegawai.id' => @$skSpk->pegawai_id]);
    $skList = $this->init_list($this->RefSkSpkModel->getAll([], 'nama_sk_spk', 'asc'), 'id', 'nama_sk_spk', @$skSpk->sk_id);
    $unitList = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', @$skSpk->unit_id);
    $subUnitList = $this->init_list($this->SubunitModel->getAll(['unit_id' => @$skSpk->unit_id], 'nama_sub_unit', 'asc'), 'id', 'nama_sub_unit', @$skSpk->sub_unit_id);
    $jabatanList = $this->init_list($this->JabatanModel->getAll([], 'nama_jabatan', 'asc'), 'id', 'nama_jabatan', @$skSpk->jabatan_id);
    $ruanganList = $this->init_list($this->RuanganModel->getAll([], 'nama_ruangan', 'asc'), 'id', 'nama_ruangan', @$skSpk->ruangan_id);
    // END ## Ref

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('skspk', false, array(
        'key' => $ref,
        'is_load_partial' => 1,
        'pegawai_id' => @$pegawai->id,
        'pegawai_nama_lengkap' => @$pegawai->nama_lengkap,
      )),
      'key' => $ref,
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'skspk' => $skSpk,
      'pegawai' => $pegawai,
      'skspk_list' => $skList,
      'unit_list' => $unitList,
      'sub_unit_list' => $subUnitList,
      'jabatan_list' => $jabatanList,
      'ruangan_list' => $ruanganList,
    );
    $this->template->set_template('sb_admin_modal_partial');
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('form', $data, TRUE);
    $this->template->render();
  }

  public function detail()
  {
    $this->handle_ajax_request();

    $agent = new Mobile_Detect;
    $ref = $this->input->get('ref');
    $ref = (!is_null($ref) && is_numeric($ref)) ? $ref : null;
    $actionLabel = '<span class="badge badge-info">View</span> ';

    $skspk = $this->SkSpkPegawaiModel->getDetail(['sk_spk_pegawai.id' => $ref]);
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('skspk', false, array(
        'key' => $ref,
      )),
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'skspk' => $skspk,
    );
    $this->load->view('view', $data);
  }

  public function ajax_get_all()
  {
    $this->handle_ajax_request();
    $filter = $this->input->get('filter');
    $query = $this->SkSpkPegawaiModel->getQuery($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->SkSpkPegawaiModel->rules());
    $id = $this->input->post('ref');

    if ($this->form_validation->run() === true) {
      if (is_null($id) || empty($id)) {
        echo json_encode($this->SkSpkPegawaiModel->insert());
      } else {
        echo json_encode($this->SkSpkPegawaiModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->SkSpkPegawaiModel->delete($id));
  }
}
