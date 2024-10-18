<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
ini_set('pcre.backtrack_limit', '2000000');
require_once(APPPATH . 'controllers/AppBackend.php');

class Diklat extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppMixModel',
      'DiklatModel',
      'DiklatPesertaModel',
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('diklat'),
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
    $diklat = $this->DiklatModel->getDetail(['id' => $ref]);
    // END ## Ref

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('diklat', false, array(
        'key' => $ref,
        'is_load_partial' => 1,
      )),
      'key' => $ref,
      'card_title' => $actionLabel . $this->_pageTitle,
      'controller' => $this,
      'is_mobile' => $agent->isMobile(),
      'diklat' => $diklat,
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
    $query = $this->DiklatModel->getQuery($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_get_by_employee()
  {
    $this->handle_ajax_request();
    $filter = $this->input->get('filter');
    $query = $this->DiklatModel->getQueryByEmployee($filter);
    $response = $this->AppMixModel->getdata_dtAjax($query);
    echo json_encode($response);
  }

  public function ajax_get_participant_item()
  {
    $this->handle_ajax_request();
    $ref = $this->input->get('diklat_id');
    $response = $this->DiklatPesertaModel->getAll(['diklat_id' => $ref], 'id', 'asc');
    echo json_encode($response);
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();
    $id = $this->input->post('ref');
    $this->form_validation->set_rules($this->DiklatModel->rules($id));

    if ($this->form_validation->run() === true) {
      if (is_null($id) || empty($id)) {
        echo json_encode($this->DiklatModel->insert());
      } else {
        echo json_encode($this->DiklatModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->DiklatModel->delete($id));
  }

  public function ajax_generate()
  {
    $this->handle_ajax_request();
    try {
      $ref = $this->input->get('ref');
      $outputFile = $this->input->get('output');
      $outputFile = (!is_null($outputFile) && !empty($outputFile)) ? $outputFile : 'pdf';
      $fileToStream = null;

      // Get source
      $source = $this->DiklatModel->getDetailDiklatById($ref);
      $templateName = @$source->template_sertifikat;
      $masterPayload = $source;
      $referencePayload = $this->getReferencePayload();
      $referencePayload = $this->getReferencePayload(array(), array(
        'key' => 'tanggal_pelatihan',
        'start' => @$source->tanggal_mulai,
        'end' => @$source->tanggal_selesai
      ));
      // END ## Get source

      if ((!is_null($templateName) || !empty($templateName)) && !is_null($masterPayload)) {
        $dirTemplate = 'directory/diklat/';
        $outputRealPath = FCPATH . $dirTemplate . '_temp/';
        $template = FCPATH . $templateName;

        // Create directory if not exist
        if (!file_exists($outputRealPath)) {
          @mkdir($outputRealPath);
        };

        if (file_exists($template) === true) {
          // Generate docx by template
          $nrp = (isset($masterPayload->nrp)) ? trim($masterPayload->nrp) : '';
          $outputFilename = 'sertifikat-' . $nrp . '-' . date('YmdHis');
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
          $output = array('status' => false, 'data' => 'Template "' . str_replace('directory/diklatsertifikat/', '', $templateName) . '" tidak ditemukan, silahkan hubungi administrator.');
        };
      } else {
        $output = array('status' => false, 'data' => 'Master data / template tidak ditemukan.');
      };

      $outputFileToStream = array('file_to_stream' => $fileToStream);
      $output = array_merge($output, $outputFileToStream);

      echo json_encode($output);
      return $output;
    } catch (Exception  $th) {
      return array('status' => false, 'data' => 'Terjadi kesalahan ketika membuat file.');
    };
  }
}
