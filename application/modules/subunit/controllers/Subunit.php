<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Subunit extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppModel',
      'UnitModel',
      'SubunitModel'
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('subunit'),
      'card_title' => $this->_pageTitle,
      'list_unit' => $this->init_list($this->UnitModel->getAll(), 'id', 'nama_unit'),
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function ajax_get_all()
  {
    $this->handle_ajax_request();
    $dtAjax_config = array(
      'select_column' => 'sub_unit.id, sub_unit.nama_sub_unit, sub_unit.kode_sub_unit, unit.id AS unit_id, unit.nama_unit',
      'table_name' => 'sub_unit',
      'table_join' => array(
        array(
          'table_name' => 'unit',
          'expression' => 'unit.id = sub_unit.unit_id',
          'type' => 'left'
        ),
      ),
      'order_column' => 1
    );
    $response = $this->AppModel->getData_dtAjax($dtAjax_config);
    echo json_encode($response);
  }

  public function ajax_save($id = null)
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->SubunitModel->rules());

    if ($this->form_validation->run() === true) {
      if (is_null($id)) {
        echo json_encode($this->SubunitModel->insert());
      } else {
        echo json_encode($this->SubunitModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->SubunitModel->delete($id));
  }
}
