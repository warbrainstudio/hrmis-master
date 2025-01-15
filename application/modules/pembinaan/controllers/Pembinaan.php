<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
ini_set('pcre.backtrack_limit', '2000000');
require_once(APPPATH . 'controllers/AppBackend.php');

class Pembinaan extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model([
      'AppMixModel',
      'PembinaanModel',
      'PegawaiModel',
      'JabatanModel',
      'UnitModel',
    ]);
    $this->load->library('form_validation');
  }

  public function index()
  {
    $agent = new Mobile_Detect;
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('pembinaan'),
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
    $pembinaan = $this->PembinaanModel->getDetail(['p.id' => $ref]);
    $pegawai = $this->PegawaiModel->getDetail(['pegawai.id' => @$pembinaan->pegawai_id]);
    $jabatanList = $this->init_list($this->JabatanModel->getAll([], 'nama_jabatan', 'asc'), 'id', 'nama_jabatan', @$pembinaan->jabatan_id);
    $unitList = $this->init_list($this->UnitModel->getAll([], 'nama_unit', 'asc'), 'id', 'nama_unit', @$pembinaan->unit_id);
    $kategoriPembinaanList = $this->init_list($this->PembinaanModel->getKategori(), 'id', 'text', @$pembinaan->kategori);
    // END ## Ref

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('pembinaan', false, array(
        'key' => $ref,
        'is_load_partial' => 1,
        'pegawai_id' => @$pegawai->id,
        'pegawai_nama_lengkap' => @$pegawai->nama_lengkap,
      )),
      'key' => $ref,
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'pembinaan' => $pembinaan,
      'pegawai' => $pegawai,
      'jabatan_list' => $jabatanList,
      'unit_list' => $unitList,
      'kategori_pembinaan_list' => $kategoriPembinaanList,
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

    $pembinaan = $this->PembinaanModel->getDetail(['p.id' => $ref]);
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('pembinaan', false, array(
        'key' => $ref,
      )),
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'pembinaan' => $pembinaan,
    );
    $this->load->view('view', $data);
  }

  private function _getSourceType($ref = null, $type = null)
  {
    $masterPayload = $this->PembinaanModel->getDetail(array('p.id' => $ref));

    switch ($type) {
      case 'SP 1':
        $response = array(
          'master_payload' => $masterPayload,
          'template_name' => 'pembinaan-sp1',
        );
        break;
      case 'SP 2':
        $response = array(
          'master_payload' => $masterPayload,
          'template_name' => 'pembinaan-sp2',
        );
        break;
      case 'SP 3':
        $response = array(
          'master_payload' => $masterPayload,
          'template_name' => 'pembinaan-sp3',
        );
        break;
      case 'Scorsing':
        $response = array(
          'master_payload' => $masterPayload,
          'template_name' => 'pembinaan-scorsing',
        );
        break;
      default:
        $response = array(
          'master_payload' => null,
          'template_name' => null,
        );
        break;
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
      $referencePayload = $this->getReferencePayload();
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
          $outputFilename = 'pembinaan-' . strtolower(str_replace(' ', '', $type)) . '-' . $nrp . '-' . date('YmdHis');
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
            if (in_array($key, ['pelanggaran', 'sanksi'])) {
              // Set HTML value
              $phpWord = new \PhpOffice\PhpWord\PhpWord();
              $section = $phpWord->addSection();
              $wordTable = $section->addTable();
              $wordTable->addRow();
              $cell = $wordTable->addCell();
              $value = str_replace('&', '&amp;', $value); // Fixed file error with symbol "&"
              \PhpOffice\PhpWord\Shared\Html::addHtml($cell, $value, false, false);
              $templateProcessor->setComplexBlock($key, $wordTable);
            } else {
              $templateProcessor->setValue(strtolower($key), htmlspecialchars($value));
              $templateProcessor->setValue(strtoupper($key), htmlspecialchars(strtoupper($value)));
            };
          };
          $templateProcessor->saveAs($outputPath_docx);
          // END ## SET TEMPLATE PAYLOAD

          if (file_exists($outputPath_docx)) {
            if ($outputFile == 'pdf') {
              // Convert to PDF
              // $convCmd = '"C:/Program Files/LibreOffice/program/soffice" --headless --convert-to pdf "' . $outputPath_docx . '" --outdir "' . $outputRealPath . '"'; // windows
              $convCmd = 'export HOME=/tmp && soffice --headless --convert-to pdf "' . $outputPath_docx . '" --outdir "' . $outputRealPath . '"'; // linux server
              @exec($convCmd, $convOutput, $convReturn);
              @unlink($outputPath_docx);

              if (file_exists($outputPath_pdf)) {
                $output = array('status' => true, 'data' => 'Generate PDF berhasil.');
              } else {
                $output = array('status' => false, 'data' => 'Generate PDF gagal.', 'command' => $convCmd);
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
    $query = $this->PembinaanModel->getQuery($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();
    $id = $this->input->post('ref');

    $this->form_validation->set_rules($this->PembinaanModel->rules($id));

    if ($this->form_validation->run() === true) {
      if (is_null($id) || empty($id)) {
        echo json_encode($this->PembinaanModel->insert());
      } else {
        echo json_encode($this->PembinaanModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->PembinaanModel->delete($id));
  }
}
