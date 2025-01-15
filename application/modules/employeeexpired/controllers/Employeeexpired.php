<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;

define('SPECIAL_ARRAY_TYPE', CellSetterArrayValueSpecial::class);

class Employeeexpired extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model([
      'AppMixModel',
      'EmployeeExpiredModel',
      'UnitModel',
      'SubunitModel',
    ]);
    $this->load->library('form_validation');
  }

  private function _getQuery($isExport = false, $searchFilter = '')
  {
      $filter = '';
      $unit_id = $this->input->get('cxfilter_unit_id');
      $sub_unit_id = $this->input->get('cxfilter_sub_unit_id');
      $eoc_start = $this->input->get('cxfilter_eoc_start');
      $eoc_end = $this->input->get('cxfilter_eoc_end');
  
      if (!is_null($unit_id) && $unit_id != 'all') {
          $filter .= " AND unit_id = '$unit_id'";
      }
  
      if (!is_null($sub_unit_id) && $sub_unit_id != 'all') {
          if ($sub_unit_id !== 'null') {
              $filter .= " AND sub_unit_id = '$sub_unit_id'";
          }
      }

      if (!is_null($eoc_start) && $eoc_end != '') $filter .= " AND DATE(eoc) BETWEEN '$eoc_start' AND '$eoc_end'";
  
      if (!empty($searchFilter)) {
          $filter .= " $searchFilter"; 
      }
  
      return (object) array(
        'params' => ($isExport === true) ? array(
            'cxfilter_unit' => (!is_null($unit_id) && $unit_id != 'all') ? @$this->UnitModel->getDetail(['id' => $unit_id])->nama_unit : 'Semua',
            'cxfilter_sub_unit' => (!is_null($sub_unit_id) && $sub_unit_id != 'all') ? @$this->EmployeeExpiredModel->getDetail_sub_unit(['id' => $sub_unit_id])->nama_sub_unit : 'Semua',
            'cxfilter_eoc' => (!is_null($eoc_start) && !empty($eoc_end) && $eoc_end != 'all') ? $eoc_start . ' s/d ' . $eoc_end : 'Semua',
        ) : array(),
        'query_string' => $this->EmployeeExpiredModel->getQuery($filter),
      );
  } 

  public function index()
  {
    $agent = new Mobile_Detect;
    $cxfilter__list_static = '<option value="all">--Semua--</option>';
    $cxfilter__unit_store = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', 'all', $cxfilter__list_static);
    $cxfilter__sub_unit_store = $this->init_list($this->SubunitModel->getAll(['unit_id' => @$cxfilter__unit_store->id], 'nama_sub_unit', 'asc'), 'id', 'nama_sub_unit', 'all', $cxfilter__list_static);
		
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('employeeexpired'),
      'card_title' => "Pegawai akan habis kontrak",
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'cx_filter' => array(
        'component' => array(
          array(
            'type' => 'combo',
            'name' => 'unit_id',
            'label' => 'Unit',
            'store' => $cxfilter__unit_store,
          ),
          array(
            'type' => 'combo',
            'name' => 'sub_unit_id',
            'label' => 'Sub Unit',
            'store' => $cxfilter__sub_unit_store,
          ),
          array(
            'type' => 'date',
            'name' => 'eoc',
            'label' => 'Akhir Kontrak',
          ),
        ),
        'cxfilter__submit_filter' => true,
        'cxfilter__submit_xlsx' => true,
      ),
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function ajax_get_all()
  {
    $this->handle_ajax_request();
    $searchFilter = $this->input->get('searchFilter', true); 
    $query = $this->_getQuery(false, $searchFilter);
    $response = $this->AppMixModel->getdata_dtAjax($query->query_string);
    echo json_encode($response);
  }

  public function xlsx()
  {
    try {
      $fileTemplate = FCPATH . 'directory/templates/template-employee-expired.xlsx';
      $callbacks = array();

      $query = $this->_getQuery(true);
      $queryString = $query->query_string;
      $master = $this->db->query($queryString)->result();

      if (!is_null($master) && count($master) > 0) {
        $outputFileName = 'employee_expired_' . date('YmdHis') . '.xlsx';
        $cxFilter_params = $query->params;
        if (!isset($cxFilter_params['cxfilter_sub_unit']) || empty($cxFilter_params['cxfilter_sub_unit'])) {
          $cxFilter_params['cxfilter_sub_unit'] = 'Semua';
        }

        $payload = $this->arrayToSetter($master);
        $payloadStatic = $this->arrayToSetterSimple($cxFilter_params);
        $payload = array_merge($payload, $payloadStatic);

        PhpExcelTemplator::outputToFile($fileTemplate, $outputFileName, $payload, $callbacks);
      } else {
        show_404();
      };
    } catch (\Throwable $th) {
      show_error('Terjadi kesalahan ketika memproses data.', 500);
    };
  }

}
