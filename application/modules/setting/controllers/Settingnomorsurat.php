<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Settingnomorsurat extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'SettingAppModel',
      'SuratnomorModel',
    ));
  }

  public function index()
  {
    $unit = $this->session->userdata('user')['unit'];
    $subUnit = $this->session->userdata('user')['sub_unit'];

    // Tambahkan baris dibawah ini untuk get nomor lainnya
    // Nomor list
    $nomorPO = $this->SuratnomorModel->getDetail(array(
      'LOWER(unit)' => strtolower($unit),
      'LOWER(sub_unit)' => strtolower($subUnit),
      'ref' => 'pembelian_persediaan'
    ));
    $nomorPORsjk = $this->SuratnomorModel->getDetail(array(
      'LOWER(unit)' => strtolower($unit),
      'LOWER(sub_unit)' => strtolower($subUnit),
      'ref' => 'pembelian_persediaan_rsjk'
    ));
    $nomorMutasi = $this->SuratnomorModel->getDetail(array(
      'LOWER(unit)' => strtolower($unit),
      'LOWER(sub_unit)' => strtolower($subUnit),
      'ref' => 'mutasi'
    ));
    $nomorReturPembelian = $this->SuratnomorModel->getDetail(array(
      'LOWER(unit)' => strtolower($unit),
      'LOWER(sub_unit)' => strtolower($subUnit),
      'ref' => 'retur_pembelian'
    ));
    // END ## Nomor list

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('setting/views/suratnomor/main.js.php', true),
      'card_title' => 'Pengaturan › Nomor Surat',
      'nomor_mutasi' => $nomorMutasi,
      'nomor_po' => $nomorPO,
      'nomor_po_rsjk' => $nomorPORsjk,
      'nomor_retur_pembelian' => $nomorReturPembelian,
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('suratnomor/index', $data, TRUE);
    $this->template->render();
  }

  public function ajax_save()
  {
    $this->handle_ajax_request();

    // Tambahkan baris dibawah ini untuk post format nomor lainnya
    // Nomor List
    $this->SuratnomorModel->setFormatByRef('pembelian_persediaan', $this->input->post('pembelian_persediaan')); // Pembelian Persediaan (KAH)
    $this->SuratnomorModel->setFormatByRef('pembelian_persediaan_rsjk', $this->input->post('pembelian_persediaan_rsjk')); // Pembelian Persediaan (RSJK)
    $this->SuratnomorModel->setFormatByRef('mutasi', $this->input->post('mutasi')); // Mutasi
    $this->SuratnomorModel->setFormatByRef('retur_pembelian', $this->input->post('retur_pembelian')); // Retur Pembelian
    // END ## Nomor List

    echo json_encode(array('status' => true, 'data' => 'Data has been saved.'));
  }
}
