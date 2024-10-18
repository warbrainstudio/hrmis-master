<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Dashboard extends AppBackend
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'NotificationModel',
			'DashboardModel',
			'PendidikanModel',
		));
	}

	public function index()
	{
		$data = array(
			'app' => $this->app(),
			'main_js' => $this->load_main_js('dashboard'),
			'card_title' => $this->_pageTitle,
			'card_subTitle' => 'Selamat datang ' . $this->session->userdata('user')['nama_lengkap'],
		);

		$this->template->set('title', $data['app']->app_name, TRUE);
		$this->template->load_view('index', $data, TRUE);
		$this->template->render();
	}

	public function get_total_pegawai_aktif()
	{
		$this->handle_ajax_request();
		echo $this->DashboardModel->getTotalPegawaiAktif();
	}

	public function get_total_pegawai_habis_kontrak()
	{
		$this->handle_ajax_request();
		echo $this->DashboardModel->getTotalPegawaiHabisKontrak();
	}

	public function get_total_demosi_mutasi()
	{
		$this->handle_ajax_request();
		echo $this->DashboardModel->getTotalDemosiMutasi();
	}

	public function get_total_diklat()
	{
		$this->handle_ajax_request();
		echo $this->DashboardModel->getTotalDiklat();
	}

	public function get_statistic_tingkat_pendidikan()
	{
		$this->handle_ajax_request();
		$this->load->view('statistic_tingkat_pendidikan', [
			'datas' => $this->DashboardModel->getStatisticTingkatPendidikan(),
		]);
	}

	public function get_statistic_kategori_pegawai()
	{
		$this->handle_ajax_request();
		$this->load->view('statistic_kategori_pegawai', [
			'datas' => $this->DashboardModel->getStatisticKategoriPegawai(),
		]);
	}

	public function get_statistic_usia_pegawai()
	{
		$this->handle_ajax_request();
		$this->load->view('statistic_usia_pegawai', [
			'datas' => $this->DashboardModel->getStatisticUsiaPegawai(),
		]);
	}

	public function get_statistic_jenis_kelamin()
	{
		$this->handle_ajax_request();
		$this->load->view('statistic_jenis_kelamin', [
			'datas' => $this->DashboardModel->getStatisticJenisKelamin(),
		]);
	}

	public function get_statistic_hubungan_kerja()
	{
		$this->handle_ajax_request();
		$this->load->view('statistic_hubungan_kerja', [
			'datas' => $this->DashboardModel->getStatisticHubunganKerja(),
		]);
	}
}
