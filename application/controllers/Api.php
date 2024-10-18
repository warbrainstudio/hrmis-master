<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(FCPATH . 'vendor/autoload.php');

use chriskacerguis\RestServer\RestController;

class Api extends RestController
{
  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->model(array('UnitModel', 'HasilScanModel'));
  }

  private function _convertRawToPost()
  {
    $jsonInput = json_decode($this->input->raw_input_stream, true);

    if (!is_null($jsonInput) && count($jsonInput) > 0) {
      foreach ($jsonInput as $index => $item) {
        $_POST[$index] = $item;
      };
    };
  }

  private function _checkUbs($uid = null)
  {
    $ubs = $this->UnitModel->getDetail(array('uid' => $uid));
    return (!is_null($ubs)) ? true : false;
  }

  function hasilscan_post()
  {
    try {
      $this->_convertRawToPost();
      $this->form_validation->set_rules($this->HasilScanModel->rules());

      if ($this->form_validation->run() === true) {
        if ($this->_checkUbs($this->input->post('ubs_uid')) === true) {
          $transaction = $this->HasilScanModel->insert();
          $this->response($transaction, $transaction['code']);
        } else {
          $this->response([
            'status' => false,
            'code' => 404,
            'message' => 'The ubs_uid is not found'
          ], 404);
        };
      } else {
        $this->response([
          'status' => false,
          'message' => $this->form_validation->error_array()
        ], 500);
      };
    } catch (\Throwable $th) {
      $this->response([
        'status' => false,
        'code' => 500,
        'message' => 'An error occurred while performing the transaction'
      ], 500);
    };
  }
}
