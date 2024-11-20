<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;

define('SPECIAL_ARRAY_TYPE', CellSetterArrayValueSpecial::class);

class Absen extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppMixModel',
      'UnitModel',
      'SubunitModel',
      'AbsenModel',
      'PegawaiModel'
    ));
  }

  private function _getQuery($isExport = false)
  {
    $filter = '';
    $unit_id = $this->input->get('cxfilter_unit_id');
    $sub_unit_id = $this->input->get('cxfilter_sub_unit_id');
    $startDate = $this->input->get('cxfilter_date_filter_start');
    $endDate = $this->input->get('cxfilter_date_filter_end');

    if (!is_null($unit_id) && $unit_id != 'all') {
        $filter .= " AND unit_id = '$unit_id'";
    }

    if (!is_null($sub_unit_id) && $sub_unit_id != 'all') {
        if ($sub_unit_id !== 'null') {
            $filter .= " AND sub_unit_id = '$sub_unit_id'";
        }
    }

    if(!empty($startDate)){
      $filter .= "AND tanggal_absen BETWEEN '$startDate' AND '$endDate'"; 
    }

    return (object) array(
      'params' => ($isExport === true) ? array(
          'cxfilter_unit' => (!is_null($unit_id) && $unit_id != 'all') ? @$this->UnitModel->getDetail(['id' => $unit_id])->nama_unit : 'Semua',
          'cxfilter_sub_unit' => (!is_null($sub_unit_id) && $sub_unit_id != 'all') ? @$this->SubunitModel->getDetail(['id' => $sub_unit_id])->nama_sub_unit : 'Semua',
      ) : array(),
      'query_string' => $this->AbsenModel->getQuery($filter),
    );
  }

  public function index()
  {
    $cxfilter__list_static = '<option value="all">--Semua--</option>';
    $cxfilter__unit_store = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', 'all', $cxfilter__list_static);
    $cxfilter__sub_unit_store = $this->init_list($this->SubunitModel->getAll(['unit_id' => @$cxfilter__unit_store->id], 'nama_sub_unit', 'asc'), 'id', 'nama_sub_unit', 'all', $cxfilter__list_static);
		
    $agent = new Mobile_Detect;
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('absen'),
      'card_title' => $this->_pageTitle,
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
            'name' => 'date_filter',
            'label' => 'Tanggal ',
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
    $query = $this->_getQuery();
    $response = $this->AppMixModel->getdata_dtAjax($query->query_string);
    echo json_encode($response);
  }

  public function ajax_get_pegawai_item()
  {
    $this->handle_ajax_request();
    $ref = $this->input->get('absen_id');
    $response = $this->AbsenModel->getAll(['absen_id' => $ref], 'asc');
    echo json_encode($response);
  }

  public function ajax_save($id = null)
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->AbsenModel->rules());

    if ($this->form_validation->run() === true) {
      if (is_null($id)) {
        echo json_encode($this->AbsenModel->insert());
      } else {
        echo json_encode($this->AbsenModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->AbsenModel->delete($id));
  }

  public function ajax_delete_pegawai($absen_id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->AbsenModel->deletepegawai($absen_id));
  }

  public function xlsx()
  {
    try {
      $fileTemplate = FCPATH . 'directory/templates/template-absensi-custom.xlsx';
      $callbacks = array();
      $query = $this->_getQuery(true);
      $queryString = $query->query_string;
      $master = $this->db->query($queryString)->result();

      /*if (preg_match("/BETWEEN '(\d{4}-\d{2}-\d{2})' AND '(\d{4}-\d{2}-\d{2})'/", $searchFilter, $matches)) {
        $fileTemplate = FCPATH . 'directory/templates/template-absensi.xlsx';
        $endDate = $matches[2];
        $dateObject = new DateTime($endDate);
        $monthNumber = $dateObject->format('m');
        $year = $dateObject->format('Y');
        $formattedMonth = $this->get_month($monthNumber);
        $formattedDate = 'periode_'.$formattedMonth . '_' . $year;
        $tanggal_periode = 'bulan ' . $formattedMonth . ' '.$year;   
      } else {
        echo "No date found.";
      }*/

      if (!is_null($master) && count($master) > 0) {
        //$outputFileName = 'absensi_pegawai_'.$formattedDate.'.xlsx';
        $outputFileName = 'absensi_pegawai_' . date('YmdHis') . '.xlsx';
        $cxFilter_params = $query->params;

        $payload = $this->arrayToSetter($master);
        $payloadStatic = $this->arrayToSetterSimple($cxFilter_params);
        //$payloadStatic = $this->arrayToSetterSimple(array('tanggal_periode' => $tanggal_periode));
        //$payloadStatic = array_merge($payloadStatic, $this->arrayToSetterSimple($cxFilter_params));
        $payload = array_merge($payload, $payloadStatic);

        PhpExcelTemplator::outputToFile($fileTemplate, $outputFileName, $payload, $callbacks);
      } else {
        show_404();
      };
    } catch (\Throwable $th) {
      show_error('Terjadi kesalahan ketika memproses data. '.$th, 500);
    };
  }

}
