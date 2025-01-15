<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;

define('SPECIAL_ARRAY_TYPE', CellSetterArrayValueSpecial::class);

class Employee extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model([
      'AppMixModel',
      'PegawaiModel',
      'SkSpkPegawaiModel',
      'KontrakPegawaiModel',
      'KategoriPegawaiModel',
      'JenisPegawaiModel',
      'StatusKontrakModel',
      'UnitModel',
      'SubunitModel',
      'JabatanModel',
      'TenagaUnitModel',
      'JenisKelaminModel',
      'StatusKawinModel',
      'PendidikanModel',
      'PegawaiKeluargaModel',
      'HubunganKeluargaModel',
    ]);
    $this->load->library('form_validation');
  }

  private function _getQuery($isExport = false)
  {
    $filter = '';
    $unit_id = $this->input->get('cxfilter_unit_id');
    $jabatan_id = $this->input->get('cxfilter_jabatan_id');
    $kategori_pegawai_id = $this->input->get('cxfilter_kategori_pegawai_id');
    $jenis_pegawai_id = $this->input->get('cxfilter_jenis_pegawai_id');
    $status_kontrak_id = $this->input->get('cxfilter_status_kontrak_id');
    $status_active = $this->input->get('cxfilter_status_active');
    $status_active_name = ($status_active == 1) ? 'Aktif' : 'Tidak Aktif';

    if (!is_null($unit_id) && $unit_id != 'all') $filter .= " AND unit_id = '$unit_id'";
    if (!is_null($jabatan_id) && $jabatan_id != 'all') $filter .= " AND jabatan_id = '$jabatan_id'";
    if (!is_null($kategori_pegawai_id) && $kategori_pegawai_id != 'all') $filter .= " AND kategori_pegawai_id = '$kategori_pegawai_id'";
    if (!is_null($jenis_pegawai_id) && $jenis_pegawai_id != 'all') $filter .= " AND jenis_pegawai_id = '$jenis_pegawai_id'";
    if (!is_null($status_kontrak_id) && $status_kontrak_id != 'all') $filter .= " AND status_kontrak_id = '$status_kontrak_id'";
    if (!is_null($status_active) && $status_active != 'all') $filter .= " AND status_active = '$status_active'";

    return (object) array(
      'params' => ($isExport === true) ? array(
        'cxfilter_unit' => (!is_null($unit_id) && $unit_id != 'all') ? @$this->UnitModel->getDetail(['id' => $unit_id])->nama_unit : 'Semua',
        'cxfilter_jabatan' => (!is_null($jabatan_id) && $jabatan_id != 'all') ? @$this->JabatanModel->getDetail(['id' => $jabatan_id])->nama_jabatan : 'Semua',
        'cxfilter_kategori_pegawai' => (!is_null($kategori_pegawai_id) && $kategori_pegawai_id != 'all') ? @$this->KategoriPegawaiModel->getDetail(['id' => $kategori_pegawai_id])->nama_kategori_pegawai : 'Semua',
        'cxfilter_status_kerja' => (!is_null($jenis_pegawai_id) && $jenis_pegawai_id != 'all') ? @$this->JenisPegawaiModel->getDetail(['id' => $jenis_pegawai_id])->nama_jenis_pegawai : 'Semua',
        'cxfilter_status_kontrak' => (!is_null($status_kontrak_id) && $status_kontrak_id != 'all') ? @$this->StatusKontrakModel->getDetail(['id' => $status_kontrak_id])->nama_status_kontrak : 'Semua',
        'cxfilter_status_active' => (!is_null($status_active) && $status_active != 'all') ? $status_active_name : 'Semua',
      ) : array(),
      'query_string' => $this->PegawaiModel->getQuery($filter),
    );
  }

  public function index()
  {
    // Init combo store
    $cxfilter__list_static = '<option value="all">--Semua--</option>';
    $cxfilter__unit_store = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', 'all', $cxfilter__list_static);
    $cxfilter__kategoriPegawai_store = $this->init_list($this->KategoriPegawaiModel->getAll([], 'nama_kategori_pegawai', 'asc'), 'id', 'nama_kategori_pegawai', 'all', $cxfilter__list_static);
    $cxfilter__jabatan_store = $this->init_list($this->JabatanModel->getAll([], 'nama_jabatan', 'asc'), 'id', 'nama_jabatan', 'all', $cxfilter__list_static);
    $cxfilter__statuKerja_store = $this->init_list($this->JenisPegawaiModel->getAll([], 'nama_jenis_pegawai', 'asc'), 'id', 'nama_jenis_pegawai', 'all', $cxfilter__list_static);
    $cxfilter__statusKontrak_store = $this->init_list($this->StatusKontrakModel->getAll([], 'nama_status_kontrak', 'asc'), 'id', 'nama_status_kontrak', 'all', $cxfilter__list_static);
    $cxfilter__statusActive_store = $this->init_list([['id' => '1', 'nama' => 'Aktif'], ['id' => '0', 'nama' => 'Tidak Aktif']], 'id', 'nama', 'all', $cxfilter__list_static);

    $agent = new Mobile_Detect;
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('employee'),
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
            'name' => 'jabatan_id',
            'label' => 'Jabatan',
            'store' => $cxfilter__jabatan_store,
          ),
          array(
            'type' => 'combo',
            'name' => 'kategori_pegawai_id',
            'label' => 'Kategori Pegawai',
            'store' => $cxfilter__kategoriPegawai_store,
          ),
          array(
            'type' => 'combo',
            'name' => 'jenis_pegawai_id',
            'label' => 'Status Kerja',
            'store' => $cxfilter__statuKerja_store,
          ),
          array(
            'type' => 'combo',
            'name' => 'status_kontrak_id',
            'label' => 'Status Kontrak',
            'store' => $cxfilter__statusKontrak_store,
          ),
          array(
            'type' => 'combo',
            'name' => 'status_active',
            'label' => 'Status',
            'store' => $cxfilter__statusActive_store,
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

  public function input()
  {
    $this->handle_ajax_request();

    $agent = new Mobile_Detect;
    $ref = $this->input->get('ref');
    $ref = (!is_null($ref) && is_numeric($ref)) ? $ref : null;
    $actionLabel = (!is_null($ref) && is_numeric($ref)) ? 'Edit' : 'New';
    $actionLabel = '<span class="badge badge-info">' . $actionLabel . '</span> ';

    // Ref
    $pegawai = $this->PegawaiModel->getDetail(['pegawai.id' => $ref]);
    $kategoriPegawaiList = $this->init_list($this->KategoriPegawaiModel->getAll([], 'nama_kategori_pegawai', 'asc'), 'id', 'nama_kategori_pegawai', @$pegawai->kategori_pegawai_id);
    $jenisPegawaiList = $this->init_list($this->JenisPegawaiModel->getAll([], 'nama_jenis_pegawai', 'asc'), 'id', 'nama_jenis_pegawai', @$pegawai->jenis_pegawai_id);
    $statusKontrakList = $this->init_list($this->StatusKontrakModel->getAll([], 'nama_status_kontrak', 'asc'), 'id', 'nama_status_kontrak', @$pegawai->status_kontrak_id);
    $unitList = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', @$pegawai->unit_id);
    $subUnitList = $this->init_list($this->SubunitModel->getAll(['unit_id' => @$pegawai->unit_id], 'nama_sub_unit', 'asc'), 'id', 'nama_sub_unit', @$pegawai->sub_unit_id);
    $jabatanList = $this->init_list($this->JabatanModel->getAll([], 'nama_jabatan', 'asc'), 'id', 'nama_jabatan', @$pegawai->jabatan_id);
    $tenagaUnitList = $this->init_list($this->TenagaUnitModel->getAll([], 'nama_tenaga_unit', 'asc'), 'id', 'nama_tenaga_unit', @$pegawai->tenaga_unit_id);
    $jenisKelaminList = $this->init_list($this->JenisKelaminModel->getAll(), 'id', 'text', @$pegawai->jenis_kelamin);
    $statusKawinList = $this->init_list($this->StatusKawinModel->getAll(), 'id', 'text', @$pegawai->status_kawin);
    $pendidikanList = $this->init_list($this->PendidikanModel->getAll(), 'id', 'text', @$pegawai->pendidikan_terakhir);
    // END ## Ref

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('employee', false, array(
        'action_route' => 'input',
        'key' => $ref,
        'pegawai_id' => @$pegawai->id,
      )),
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'pegawai' => $pegawai,
      'kategori_pegawai_list' => $kategoriPegawaiList,
      'jenis_pegawai_list' => $jenisPegawaiList,
      'status_kontrak_list' => $statusKontrakList,
      'unit_list' => $unitList,
      'sub_unit_list' => $subUnitList,
      'jabatan_list' => $jabatanList,
      'tenaga_unit_list' => $tenagaUnitList,
      'jenis_kelamin_list' => $jenisKelaminList,
      'status_kawin_list' => $statusKawinList,
      'pendidikan_list' => $pendidikanList,
    );
    $this->template->set_template('sb_admin_partial');
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

    $pegawai = $this->PegawaiModel->getDetail(['pegawai.id' => $ref]);
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('employee', false, array(
        'action_route' => 'detail',
        'key' => $ref,
        'pegawai_id' => @$pegawai->id,
        'absen_id' => @$pegawai->absen_pegawai_id,
      )),
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'pegawai' => $pegawai,
    );
    $this->template->set_template('sb_admin_partial');
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('view', $data, TRUE);
    $this->template->render();
  }

  public function ajax_get_all()
  {
    $this->handle_ajax_request();
    $query = $this->_getQuery();
    $response = $this->AppMixModel->getdata_dtAjax($query->query_string);
    echo json_encode($response);
  }

  public function ajax_save($id = null)
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->PegawaiModel->rules($id));

    if ($this->form_validation->run() === true) {
      $_POST['foto'] = $this->upload_foto();

      if (is_null($id)) {
        echo json_encode($this->PegawaiModel->insert());
      } else {
        echo json_encode($this->PegawaiModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->PegawaiModel->delete($id));
  }


  public function ajax_import()
  {
    $this->handle_ajax_request();

    ini_set('max_execution_time', 0);
    set_time_limit(0);

    try {
      $sourceFile = $_FILES['source_file']['tmp_name'];

      if (empty($sourceFile)) {
        echo json_encode(['status' => false, 'data' => 'The Berkas field is required.']);
        return;
      };

      $startDataIndex = 4;
      $payload = [];

      $spreadsheet = IOFactory::load($sourceFile);
      $sheet = $spreadsheet->getSheet(0);
      $sheetData = $sheet->toArray();

      if (count($sheetData) > 0) {
        foreach ($sheetData as $index => $item) {
          if ($index >= $startDataIndex) {
            $payload[] = [
              'nrp' => @$item[0],
              'nama_lengkap' => @$item[1],
              'kategori_pegawai_id' => @$item[2],
              'jenis_pegawai_id' => @$item[3],
              'status_kontrak_id' => @$item[4],
              'unit_id' => @$item[5],
              'sub_unit_id' => @$item[6],
              'jabatan_id' => @$item[7],
              'tenaga_unit_id' => @$item[8],
              'alamat_ktp' => @$item[9],
              'tempat_lahir' => @$item[10],
              'tanggal_lahir' => @$item[11],
              'jenis_kelamin' => @$item[12],
              'status_kawin' => @$item[13],
              'pendidikan_terakhir' => @$item[14],
              'no_ktp' => @$item[15],
              'no_bpjs_kesehatan' => @$item[16],
              'no_bpjs_tk' => @$item[17],
              'npwp' => @$item[18],
              'no_hp' => @$item[19],
              'mcu' => @$item[20],
              'status_active' => @$item[21],
              'created_by' => $this->session->userdata('user')['id'],
            ];
          };
        };
      };

      if (count($payload) === 0) {
        echo json_encode(['status' => true, 'data' => 'No record found.']);
        return;
      };

      $response = $this->PegawaiModel->importBatch($payload);

      echo json_encode($response);
    } catch (\Throwable $th) {
      echo json_encode(['status' => false, 'data' => $th->getMessage()]);
    };
  }

  private function upload_foto()
  {
    $result = null;

    if (!empty($_FILES['foto']['name'])) {
      $cpUpload = new CpUpload();
      $upload = $cpUpload->run('foto', 'employee', true, true, 'jpg|jpeg|png');

      if ($upload->status === true) {
        $result = $upload->data->base_path;
      };
    };

    return $result;
  }

  public function xlsx()
  {
    try {
      $fileTemplate = FCPATH . 'directory/templates/template-employee.xlsx';
      $callbacks = array();

      $query = $this->_getQuery(true);
      $queryString = $query->query_string;
      $master = $this->db->query($queryString)->result();

      if (!is_null($master) && count($master) > 0) {
        $outputFileName = 'employee_' . date('YmdHis') . '.xlsx';
        $cxFilter_params = $query->params;

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
