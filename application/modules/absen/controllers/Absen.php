<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;

define('SPECIAL_ARRAY_TYPE', CellSetterArrayValueSpecial::class);

class Absen extends AppBackend
{
  public $prefs;
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

    $this->prefs = array(
			'start_day'    => 'senin',
			'month_type'   => 'long',
			'day_type'     => 'long',
			'show_next_prev' => TRUE,
			'next_prev_url'   => base_url('absen/index/'),
		);

		$this->prefs['template'] = array(
			'table_open'           		=> '<table class="calendar">',
			'heading_row_start' 		=> '<tr class="header_month">',
			'heading_previous_cell'		=> '<th><a href="{previous_url}"><i class="zmdi zmdi-caret-left-circle"></i></a></th>',
			'heading_title_cell'		=> '<th class="month_name" colspan="{colspan}"><a class="month_content">{heading}</a></th>',
			'heading_next_cell'			=> '<th><a href="{next_url}"><i class="zmdi zmdi-caret-right-circle"></i></a></th>',
			'week_row_start' 			=> '<tr class="header_day">',
			'cal_cell_start'       		=> '<td class="day">',
			'cal_cell_start_today' 		=> '<td class="today">',
			'cal_cell_content'			=> '<a class="content_fill_day" href="'.base_url('absen/detail?date=').'{content}" title="Click untuk lihat data absen tanggal {content}">{day}</a>',
			'cal_cell_content_today'	=> '<a class="content_fill_today" href="'.base_url('absen/detail?date=').'{content}" title="Click untuk lihat data absen hari ini"><strong>{day}</strong></a>',
			'cal_cell_no_content'		=> '<p class="no_content_fill_day" title="Data absen belum ada. click untuk tarik data">{day}</p>',
			'cal_cell_no_content_today'	=> '<a class="no_content_fill_today" title="Data absen belum ada. Click untuk tarik data hari ini"><strong>{day}</strong></a>'
		);
  }

  public function index($year = NULL , $month = NULL)
	{
		if(empty($year)||empty($month)){
			$year = date('Y');
			$month = date('m');
		}
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('absen'),
      'card_title' => $this->_pageTitle,
      'calendar' => $this->getcalender($year , $month),
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function getcalender($year , $month)
	{
		$this->load->library('calendar',$this->prefs);
		$data = $this->get_calender_data($year,$month);
		return $this->calendar->generate($year , $month , $data);
	}

	public function get_calender_data($year , $month)
	{
		$startDate = date('Y-m-d', strtotime("$year-$month-1"));
        $endDate = date('Y-m-d', strtotime("$year-$month-1 +1 month"));
		$query = $this->db->select('DATE(tanggal_absen) AS absendate, COUNT(tanggal_absen) AS attendance_count')
							->from('absen_pegawai')
          					->where("tanggal_absen BETWEEN '$startDate' AND '$endDate'")
							->group_by('absendate')
							->order_by('absendate')
							->get();

		$cal_data = array();
		foreach ($query->result() as $row) {
            $calendar_date = date("Y-m-j", strtotime($row->absendate));
			$cal_data[substr($calendar_date, 8,2)] = $row->absendate;
		}
		
		return $cal_data;
	}

	public function detail()
  {
		$agent = new Mobile_Detect;
		$ref = $this->input->get('date');
    $cxfilter__list_static = '<option value="all">--Semua--</option>';
    $cxfilter__unit_store = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', 'all', $cxfilter__list_static);
    $cxfilter__sub_unit_store = $this->init_list($this->SubunitModel->getAll([], 'nama_sub_unit', 'asc'), 'id', 'nama_sub_unit', 'all', $cxfilter__list_static);
		$searchFilter = "";
    $searchFilterPeriode = "";
		$status = "";
		$card = "";
		if (DateTime::createFromFormat('Y-m-d', $ref) !== false) {
			$dateTime = DateTime::createFromFormat('Y-m-d', $ref);
			$Day = $dateTime->format('D');
      $DayNumber = $dateTime->format('d');
      $monthNumber = $dateTime->format('m');
      $year = $dateTime->format('Y');
      $formattedDay = $this->get_day($Day);
      $formattedMonth = $this->get_month($monthNumber);
      $formattedDate = $formattedDay.', '.$DayNumber.' '.$formattedMonth . ' ' . $year;
			$searchFilter .= "AND tanggal_absen::date='$ref'";
			$status = true;
			$card = "hari ".$formattedDate;
		}else if(DateTime::createFromFormat('Y-m', $ref) !== false){
			$dateTime = DateTime::createFromFormat('Y-m', $ref);
			$monthNumber = $dateTime->format('m');
			$year = $dateTime->format('Y');
			$formattedMonth = $this->get_month($monthNumber);
			$formattedDate = $formattedMonth . ' ' . $year;
      $startDate = date('Y-m-d', strtotime("$year-$monthNumber-1"));
      $endDate = date('Y-m-d', strtotime("$year-$monthNumber-1 +1 month"));
			$searchFilter .= "AND tanggal_absen BETWEEN '$startDate' AND '$endDate'";
      $startDatePeriode = date('Y-m-d', strtotime("$year-$monthNumber-21 -1 month"));
      $endDatePeriode = date('Y-m-d', strtotime("$year-$monthNumber-21"));
      $searchFilterPeriode .= "AND tanggal_absen BETWEEN '$startDatePeriode' AND '$endDatePeriode'";
			$status = false;
			$card = "periode bulan ".$formattedDate;
		}else{
			show_404();
		}

		$data = array(
			'app' => $this->app(),
			'main_js' => $this->load_main_js('absen', false, array(
				'action_route' => 'detail',
				'key' => $ref,
				'searchFilter' => $searchFilter,
        'searchFilterPeriode' => $searchFilterPeriode,
				'isDaily' => $status,
			)),
			'card_title' => 'Absen '.$card,
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
        ),
        'cxfilter__submit_filter' => true,
        'cxfilter__submit_xlsx' => true,
      ),
			'isDaily' => $status,
			'isAll' => false,
		);

    $query = $this->_getQuery(false, $searchFilter);
    $data['query_string'] = $query->query_string;
		$this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('view', $data, TRUE);
		$this->template->render();
  }

  private function _getQuery($isExport = false, $searchFilter = '')
  {
      $filter = '';
      $unit_id = $this->input->get('cxfilter_unit_id');
      $sub_unit_id = $this->input->get('cxfilter_sub_unit_id');
  
      if (!is_null($unit_id) && $unit_id != 'all') {
          $filter .= " AND unit_id = '$unit_id'";
      }

      if (!is_null($sub_unit_id) && $sub_unit_id != 'all') {
        if ($sub_unit_id !== 'null') {
          $filter .= " AND sub_unit_id = '$sub_unit_id'";
        }else{
          $filter .= " AND sub_unit_id = null";
        }
    }

      if (!empty($searchFilter)) {
          $filter .= " $searchFilter"; 
      }
  
      return (object) array(
          'params' => ($isExport === true) ? array(
              'cxfilter_unit' => (!is_null($unit_id) && $unit_id != 'all') ? @$this->UnitModel->getDetail(['id' => $unit_id])->nama_unit : 'Semua',
              'cxfilter_sub_unit' => (!is_null($sub_unit_id) && $sub_unit_id != 'all') ? @$this->SubunitModel->getDetail(['id' => $sub_unit_id])->nama_sub_unit : 'Semua',
          ) : array(),
          'query_string' => $this->AbsenModel->getQuery($filter),
      );
  }

  public function ajax_get_all()
  {
    $this->handle_ajax_request();
    $searchFilter = $this->input->get('searchFilter', true); 
    $query = $this->_getQuery(false, $searchFilter);
    $response = $this->AppMixModel->getdata_dtAjax($query->query_string);
    echo json_encode($response);
  }

  public function ajax_fetch_data() {

    $this->handle_ajax_request();
    $tanggal = $this->input->get('tanggal');
    $data['filteredData'] = [];
    $query = $this->db->get('mesin_absen');
    $mesins = $query->result();
    foreach ($mesins as $mesin) {
        $ip = $mesin->ipadress;
        $key = $mesin->commkey;
        $data['filteredData'] = array_merge(
          $data['filteredData'],
          $this->AbsenModel->fetchDataFromMachine($ip, $key, $tanggal, $tanggal)
        );

        usort($data['filteredData'], function($a, $b) {
          return strtotime($a['DateTime']) - strtotime($b['DateTime']);
        });

        $dataCount['dataCount'] = count($data['filteredData']);

        $result = $this->AbsenModel->import_data($data['filteredData']);
        $existingRecordsCount = $result['existingRecordsCount'];
        $failedInsertions = $result['failedInsertions'];

        $response = array(
          'status' => true,
          'data' => array(
              'dataCount' => $dataCount['dataCount'],
              'existingRecordsCount' => $existingRecordsCount,
              'failedInsertions' => $failedInsertions
          )
        );
    }
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response));
  }

  public function ajax_fetch_data_api() {

    $this->handle_ajax_request();
    $tanggal = $this->input->get('tanggal');

    $status = 'false';
    $token = 'XVd17lwEgOHcvKgjJWGWbuufQdte7WhiPLerllmSWcvr8jKLz6vqqkQkl4DIQzvbOUAtsxvl1TDviMlS3bQEewLszTxxGeAuv8XS';
    $task = '/fetchData?';
    $tableView = $this->AbsenModel->_tableView;

    $apiUrl = base_url('api/'.$task . http_build_query([
        'token' => $token,
        'host' => 'localhost',
        'port' => $this->db->port,
        'username' => $this->db->username,
        'password' => $this->db->password,
        'database' => $this->db->database,
        'table' => $tableView,
        'alldata' => $status,
        'start_date' => $tanggal,
        'end_date' => $tanggal,
    ]));

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return json_encode(['error' => 'cURL error: ' . curl_error($ch)]);
    }

    curl_close($ch);
    $data_api = json_decode($response, true);

    if (is_array($data_api) && isset($data_api['status'])) {
      if ($data_api['status'] == 'true') {
          $response = array(
              'status' => true,
          );
      } else {
          $response = array(
              'status' => false,
              'message' => $data_api['message'],
          );
      }
    } else {
        return json_encode(['error' => 'Invalid response from API']);
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
        
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

  public function ajax_delete_pegawai($absen_pegawai_id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->AbsenModel->deletepegawai($absen_pegawai_id));
  }

  public function xlsx()
  {
    try {
      $fileTemplate = FCPATH . 'directory/templates/template-absensi.xlsx';
      $callbacks = array();
      $searchFilter = $this->input->get('searchFilterPeriode', true); 
      $query = $this->_getQuery(true, $searchFilter);
      $queryString = $query->query_string;
      //$queryString .= " ORDER BY tanggal_absen ASC";
      $master = $this->db->query($queryString)->result();

      if (!is_null($master) && count($master) > 0) {
        $outputFileName = 'absensi_' . date('YmdHis') . '.xlsx';
        $cxFilter_params = $query->params;

        $payload = $this->arrayToSetter($master);
        $payloadStatic = $this->arrayToSetterSimple($cxFilter_params);
        $payload = array_merge($payload, $payloadStatic);

        PhpExcelTemplator::outputToFile($fileTemplate, $outputFileName, $payload, $callbacks);
      } else {
        show_404();
      };
    } catch (\Throwable $th) {
      show_error('Terjadi kesalahan ketika memproses data. '.$th, 500);
    };
  }

  public function xlsx_harian()
  {
      try {
          $date = $this->input->get('date');
          $status = '';
          $formattedDate = $date;
          if (DateTime::createFromFormat('Y-m-d', $date) !== false) {
              $fileTemplate = FCPATH . 'directory/templates/template-absensi-harian.xlsx';
              $dateTime = DateTime::createFromFormat('Y-m-d', $date);
              $Day = $dateTime->format('D');
              $DayNumber = $dateTime->format('d');
              $monthNumber = $dateTime->format('m');
              $year = $dateTime->format('Y');
              $formattedDay = $this->get_day($Day);
              $formattedMonth = $this->get_month($monthNumber);
              $formattedDate = $formattedDay.', '.$DayNumber.' '.$formattedMonth . ' ' . $year;
              $status = $formattedDate;
          }  else{
              ini_set('memory_limit', '4G');
              $fileTemplate = FCPATH . 'directory/templates/template-absensi.xlsx';
              $status = 'Tahun'; 
          }
          $callbacks = array();

          $payload = $this->AbsenModel->getAll(array('tanggal_absen' => $date));

          $user = $this->session->userdata('user')['nama_lengkap'];
  
          if (!is_null($payload)) {

              $outputFileName = 'histori absen ' . $formattedDate . '.xlsx';
              $payloadStatic = $this->arrayToSetterSimple(array('tanggal_absen' => $formattedDate,'status' => $status));
              $payloadStatic = array_merge($payloadStatic, $this->arrayToSetterSimple(array('app_export_date' => date('Y-m-d H:i:s'), 'user' => $user)));
              $payload = $this->arrayToSetter($payload);
              $payload = array_merge($payload, $payloadStatic);
  
              PhpExcelTemplator::outputToFile($fileTemplate, $outputFileName, $payload, $callbacks);
          } else {
              show_404();
          }
      } catch (\Throwable $th) {
          log_message('error', $th->getMessage());
  
          show_error('Terjadi kesalahan ketika memproses data. Detail: ' . $th->getMessage(), 500);
      }
  }

  public function excel_pegawai()
  {
    try {
        $absen_pegawai_id = $this->input->get('absen_pegawai_id');
        $fileTemplate = FCPATH . 'directory/templates/template-absensi-pegawai.xlsx';
        $callbacks = array();
        $user = $this->session->userdata('user')['nama_lengkap'];
        $pegawai = $this->PegawaiModel->getDetail(array('absen_pegawai_id' => $absen_pegawai_id));
        
        if ($pegawai) {
            $payload = $this->AbsenModel->getAll(array('absen_id' => $pegawai->absen_pegawai_id));
        } else {
            show_404();
            return;
        }

        if (!is_null($payload)) {
            $outputFileName = 'laporan absen ' . (!empty($pegawai->nama_lengkap) ? $pegawai->nama_lengkap : "ID ".$absen_pegawai_id) . '.xlsx';

            $payloadStatic = $this->arrayToSetterSimple(array('nama_lengkap' => $pegawai->nama_lengkap));
            $payloadStatic = array_merge($payloadStatic, $this->arrayToSetterSimple(array('app_export_date' => date('Y-m-d H:i:s'), 'user' => $user)));
            $payloadSimple = $this->arrayToSetterSimple((array) $pegawai);
            $payload = $this->arrayToSetter($payload);
            $payload = array_merge($payload, $payloadSimple, $payloadStatic);

            PhpExcelTemplator::outputToFile($fileTemplate, $outputFileName, $payload, $callbacks);
        } else {
            show_404();
        }
    } catch (\Throwable $th) {
        log_message('error', $th->getMessage());

        show_error('Terjadi kesalahan ketika memproses data. Detail: ' . $th->getMessage(), 500);
    }
  }

}
