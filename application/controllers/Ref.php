<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Ref extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'PegawaiModel',
      'UnitModel',
      'SubunitModel',
    ));
  }

  public function ajax_search_pegawai()
  {
    $this->handle_ajax_request();
    $keyword = $this->input->get('q');
    $data = $this->PegawaiModel->getSearch(strtolower($keyword));
    $result = array();

    if (count($data) > 0) {
      foreach ($data as $index => $item) {
        $item->text = $item->nama_lengkap;
        $result['items'][] = $item;
      };
    };

    echo json_encode($result);
  }

  public function ajax_get_list_sub_unit()
  {
    $this->handle_ajax_request();
    $unitId = $this->input->get('unit_id');
    $defaultValue = $this->input->get('default_value');
    $defaultValue = (!is_null($defaultValue) && !empty($defaultValue)) ? $defaultValue : null;
    $response = $this->init_list($this->SubunitModel->getAll(array('unit_id' => $unitId), 'nama_sub_unit', 'asc'), 'id', 'nama_sub_unit', $defaultValue);

    echo json_encode($response);
  }
}
