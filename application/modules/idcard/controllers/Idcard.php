<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Idcard extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('SettingAppModel', 'PegawaiModel'));
    }

    public function index()
    {
        // Retrive json config
        $jsonPath = 'directory/setting/id-card.json';
        $jsonFullPath = FCPATH . $jsonPath;

        if (file_exists($jsonFullPath)) {
            $jsonFile = file_get_contents($jsonFullPath);
            $config = json_decode($jsonFile, true);
            $data = array(
                'app' => $this->app(),
                'main_js' => $this->load_main_js('idcard/views/main.js.php', true, array(
                    'config' => json_encode($config),
                )),
                'card_title' => 'Pengaturan › ID Card',
                'config' => $config,
            );
            $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
            $this->template->load_view('idcard/index', $data, TRUE);
            $this->template->render();
        } else {
            show_error('Config file is not found in "' . $jsonPath . '"');
        }
    }

    public function ajax_save()
    {
        $this->handle_ajax_request();
        $config = $this->input->post('config');

        if (!is_null($config)) {
            try {
                $jsonPath = FCPATH . 'directory/setting/id-card.json';
                $config = json_encode($config);
                @chmod($jsonPath, 0777);
                @file_put_contents($jsonPath, $config);
                $response = array('status' => true, 'data' => 'Data has been saved.', 'config' => json_decode($config));
            } catch (\Throwable $th) {
                $response = array('status' => false, 'data' => 'Failed to save your data.', 'config' => json_decode($config));
            };
        } else {
            $response = array('status' => false, 'data' => 'No config to submit');
        };

        echo json_encode($response);
    }

    public function preview()
    {
        $employee = (object) array(
            'foto' => 'themes/sb_admin/assets/img/illustrations/profiles/profile-4.png',
            'nrp' => '1234567890',
            'nama_lengkap' => 'Hafiz Maulana Ibrahim, S.H., M.Kn.',
            'unit' => 'PT. KAH',
            'sub_unit' => 'Teknologi Informatika',
            'jabatan' => 'Rahasia',
        );
        $this->detail($employee);
    }

    public function generate()
    {
        $ref = $this->input->get('ref');
        $employee = $this->PegawaiModel->getDetail(array('pegawai.id' => $ref));
        $this->detail($employee);
    }

    public function detail($employee = null)
    {
        $this->handle_ajax_request();

        // Retrive json config
        $jsonPath = 'directory/setting/id-card.json';
        $jsonFullPath = FCPATH . $jsonPath;

        if (file_exists($jsonFullPath)) {
            $jsonFile = file_get_contents($jsonFullPath);
            $config = json_decode($jsonFile, true);
            $data = array(
                'app' => $this->app(),
                'main_js' => $this->load_main_js('idcard/views/main.js.php', true, array(
                    'config' => json_encode($config),
                )),
                'card_title' => 'ID Card',
                'config' => $config,
                'employee' => $employee,
            );
            $this->load->view('view', $data);
        } else {
            show_error('Config file is not found in "' . $jsonPath . '"');
        }
    }
}
