<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
ini_set('pcre.backtrack_limit', '2000000');
require_once(APPPATH . 'controllers/AppBackend.php');

class Contract extends AppBackend
{
  private $_contractType = array();

  function __construct()
  {
    parent::__construct();
    $this->load->model([
      'AppMixModel',
      'KontrakPegawaiModel',
      'PegawaiModel',
      'KategoriPegawaiModel',
      'JenisPegawaiModel',
      'StatusKontrakModel',
      'JabatanModel',
      'UnitModel',
      'SubunitModel',
    ]);
    $this->load->library('form_validation');
    $this->_contractType = $this->KontrakPegawaiModel->getIdType();
  }

  public function index()
  {
    $agent = new Mobile_Detect;
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('contract', false, array(
        'contract_type' => json_encode($this->_contractType),
      )),
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
    $contract = $this->KontrakPegawaiModel->getDetail(['kontrak_pegawai.id' => $ref]);
    $pegawai = $this->PegawaiModel->getDetail(['pegawai.id' => @$contract->pegawai_id]);
    $kategoriPegawaiList = $this->init_list($this->KategoriPegawaiModel->getAll([], 'nama_kategori_pegawai', 'asc'), 'id', 'nama_kategori_pegawai', @$contract->kategori_pegawai_id);
    $jenisPegawaiList = $this->init_list($this->JenisPegawaiModel->getAll([], 'nama_jenis_pegawai', 'asc'), 'id', 'nama_jenis_pegawai', @$contract->jenis_pegawai_id);
    $statusKontrakList = $this->init_list($this->StatusKontrakModel->getAll([], 'nama_status_kontrak', 'asc'), 'id', 'nama_status_kontrak', @$contract->status_kontrak_id);
    $jabatanList = $this->init_list($this->JabatanModel->getAll([], 'nama_jabatan', 'asc'), 'id', 'nama_jabatan', @$contract->jabatan_id);
    $unitList = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', @$contract->unit_id);
    $subUnitList = $this->init_list($this->SubunitModel->getAll(['unit_id' => @$contract->unit_id], 'nama_sub_unit', 'asc'), 'id', 'nama_sub_unit', @$contract->sub_unit_id);
    // END ## Ref

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('contract', false, array(
        'key' => $ref,
        'is_load_partial' => 1,
        'pegawai_id' => @$pegawai->id,
        'pegawai_nama_lengkap' => @$pegawai->nama_lengkap,
        'contract_type' => json_encode($this->_contractType),
      )),
      'key' => $ref,
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'contract' => $contract,
      'pegawai' => $pegawai,
      'kategori_pegawai_list' => $kategoriPegawaiList,
      'jenis_pegawai_list' => $jenisPegawaiList,
      'status_kontrak_list' => $statusKontrakList,
      'jabatan_list' => $jabatanList,
      'unit_list' => $unitList,
      'sub_unit_list' => $subUnitList,
      'contract_type' => $this->_contractType,
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

    $contract = $this->KontrakPegawaiModel->getDetail(['kontrak_pegawai.id' => $ref]);
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('contract', false, array(
        'key' => $ref,
      )),
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'contract' => $contract,
    );
    $this->load->view('view', $data);
  }

  private function _getSourceType($ref = null, $type = null)
  {
    $masterPayload = $this->KontrakPegawaiModel->getDetail(array('kontrak_pegawai.id' => $ref));

    if (in_array($type, $this->_contractType->employee) != false) {
      // Employee
      $response = array(
        'master_payload' => $masterPayload,
        'gaji_payload' => array(),
        'template_name' => 'kontrak-kerja-em',
      );
    } else if (in_array($type, $this->_contractType->mitra) != false) {
      // Mitra
      $gaji = $this->KontrakPegawaiModel->getGaji(@$masterPayload->unit_id, @$masterPayload->sub_unit_id, @$masterPayload->jabatan_id, @$masterPayload->jenis_pegawai_id);

      if (count($gaji) > 0) {
        $gapok = number_format(@$gaji[0]->expression, 2);
        $gaji = array(
          'gapok' => $gapok,
          'gapok_terhitung' => $this->angkaTerbilang($gapok),
        );
      };

      $response = array(
        'master_payload' => $masterPayload,
        'gaji_payload' => $gaji,
        'template_name' => 'kontrak-kerja-mitra',
      );
    };

    return (object) $response;
  }

  public function ajax_generate()
  {
    $this->handle_ajax_request();
    try {
      $ref = $this->input->get('ref');
      $type = $this->input->get('type');
      $outputFile = $this->input->get('output');
      $outputFile = (!is_null($outputFile) && !empty($outputFile)) ? $outputFile : 'pdf';
      $fileToStream = null;

      // Get source
      $sourceType = $this->_getSourceType($ref, $type);
      $templateName = $sourceType->template_name;
      $masterPayload = $sourceType->master_payload;
      $referencePayload = $this->getReferencePayload(array(
        'key' => 'masa_kerja',
        'start' => @$masterPayload->soc,
        'end' => @$masterPayload->eoc
      ));
      $gajiPayload = $sourceType->gaji_payload;
      // END ## Get source

      if (!is_null($masterPayload)) {
        $dirTemplate = 'directory/templates/';
        $templateRealPath = FCPATH . $dirTemplate;
        $outputRealPath = FCPATH . $dirTemplate . '_temp/';
        $template = $templateRealPath . $templateName . '.docx';

        // Create directory if not exist
        if (!file_exists($outputRealPath)) {
          @mkdir($outputRealPath);
        };

        if (file_exists($template) === true) {
          // Generate docx by template
          $nrp = (isset($masterPayload->nrp)) ? trim($masterPayload->nrp) : '';
          $outputFilename = 'kontrak_kerja-' . $nrp . '-' . date('YmdHis');
          $outputPath = $outputRealPath . $outputFilename;
          $outputPath_docx = $outputPath . '.docx';
          $outputPath_pdf = $outputPath . '.pdf';

          // SET TEMPLATE PAYLOAD
          $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template);
          // Reference
          foreach ($referencePayload as $key => $value) {
            if ($this->validate_date($value)) $value = $this->getDateID($value);
            $templateProcessor->setValue(strtolower($key), htmlspecialchars($value));
            $templateProcessor->setValue(strtoupper($key), htmlspecialchars(strtoupper($value)));
          };
          // Master
          foreach ($masterPayload as $key => $value) {
            if ($this->validate_date($value)) $value = $this->getDateID($value);
            $templateProcessor->setValue(strtolower($key), htmlspecialchars($value));
            $templateProcessor->setValue(strtoupper($key), htmlspecialchars(strtoupper($value)));
          };
          // Gaji
          if (count($gajiPayload) > 0) {
            foreach ($gajiPayload as $key => $value) {
              $templateProcessor->setValue(strtolower($key), htmlspecialchars($value));
              $templateProcessor->setValue(strtoupper($key), htmlspecialchars(strtoupper($value)));
            };
          };
          $templateProcessor->saveAs($outputPath_docx);
          // END ## SET TEMPLATE PAYLOAD

          if (file_exists($outputPath_docx)) {
            if ($outputFile == 'pdf') {
              // Convert to PDF
              $convCmd = '"C:/Program Files/LibreOffice/program/soffice" --headless --convert-to pdf "' . $outputPath_docx . '" --outdir "' . $outputRealPath . '"'; // windows
              // $convCmd = 'export HOME=/tmp && soffice --headless --convert-to pdf "' . $outputPath_docx . '" --outdir "' . $outputRealPath . '"'; // linux server
              @exec($convCmd, $convOutput, $convReturn);
              @unlink($outputPath_docx);

              if (file_exists($outputPath_pdf)) {
                $output = array('status' => true, 'data' => 'Generate PDF berhasil.');
              } else {
                $output = array('status' => false, 'data' => 'Generate PDF gagal.');
              };
            } else {
              $output = array('status' => true, 'data' => 'Generate DOCX berhasil.');
            };

            $fileToStream = base_url($dirTemplate . '_temp/' . $outputFilename . '.' . $outputFile);
          } else {
            $output = array('status' => false, 'data' => 'Generate DOCX gagal.');
          };
        } else {
          $output = array('status' => false, 'data' => 'Template dengan nama "' . $templateName . '.docx" tidak ditemukan, silahkan hubungi administrator.');
        };
      } else {
        $output = array('status' => false, 'data' => 'Master data tidak ditemukan.');
      };

      $outputFileToStream = array('file_to_stream' => $fileToStream);
      $output = array_merge($output, $outputFileToStream);

      echo json_encode($output);
      return $output;
    } catch (Exception  $th) {
      return array('status' => false, 'data' => 'Terjadi kesalahan ketika membuat file.');
    };
  }

  public function ajax_get_all()
  {
    $this->handle_ajax_request();
    $filter = $this->input->get('filter');
    $query = $this->KontrakPegawaiModel->getQuery($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->KontrakPegawaiModel->rules());
    $id = $this->input->post('ref');

    if ($this->form_validation->run() === true) {
      if (is_null($id) || empty($id)) {
        echo json_encode($this->KontrakPegawaiModel->insert());
      } else {
        echo json_encode($this->KontrakPegawaiModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->KontrakPegawaiModel->delete($id));
  }
}
