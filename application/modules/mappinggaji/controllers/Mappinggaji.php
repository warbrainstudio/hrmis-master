<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Mappinggaji extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppMixModel',
      'IndikatorGajiMappingModel',
      'IndikatorGajiMappingItemModel',
      'IndikatorGajiModel',
      'UnitModel',
      'SubunitModel',
      'JabatanModel',
      'JenisPegawaiModel'
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('mappinggaji'),
      'card_title' => $this->_pageTitle,
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
    $mappingGaji = $this->IndikatorGajiMappingModel->getDetail(['indikator_gaji_mapping.id' => $ref]);
    $indikatorList = $this->init_list($this->IndikatorGajiModel->getAll([], 'nama_indikator_gaji', 'asc'), 'id', 'nama_indikator_gaji', null, null, ['nama_alias', 'default_expression']);
    $unitList = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', @$mappingGaji->unit_id);
    $subUnitList = $this->init_list($this->SubunitModel->getAll(['unit_id' => @$mappingGaji->unit_id], 'nama_sub_unit', 'asc'), 'id', 'nama_sub_unit', @$mappingGaji->sub_unit_id);
    $jabatanList = $this->init_list($this->JabatanModel->getAll([], 'nama_jabatan', 'asc'), 'id', 'nama_jabatan', @$mappingGaji->jabatan_id);
    $jenisPegawaiList = $this->init_list($this->JenisPegawaiModel->getAll([], 'nama_jenis_pegawai', 'asc'), 'id', 'nama_jenis_pegawai', @$mappingGaji->jenis_pegawai_id);
    // END ## Ref

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('mappinggaji', false, array(
        'key' => $ref,
        'is_load_partial' => 1,
        'indikator_list' => $indikatorList,
      )),
      'key' => $ref,
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'mapping_gaji' => $mappingGaji,
      'indikator_list' => $indikatorList,
      'unit_list' => $unitList,
      'sub_unit_list' => $subUnitList,
      'jabatan_list' => $jabatanList,
      'jenis_pegawai_list' => $jenisPegawaiList,
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
    $query = $this->IndikatorGajiMappingModel->getQuery($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_get_indikator_item()
  {
    $this->handle_ajax_request();
    $ref = $this->input->get('mapping_gaji_id');
    $response = $this->IndikatorGajiMappingItemModel->getAll(['indikator_gaji_mapping_id' => $ref], 'order_pos', 'asc');
    echo json_encode($response);
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();
    $id = $this->input->post('ref');
    $this->form_validation->set_rules($this->IndikatorGajiMappingModel->rules($id));

    if ($this->form_validation->run() === true) {
      if (is_null($id) || empty($id)) {
        echo json_encode($this->IndikatorGajiMappingModel->insert());
      } else {
        echo json_encode($this->IndikatorGajiMappingModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->IndikatorGajiMappingModel->delete($id));
  }
}
