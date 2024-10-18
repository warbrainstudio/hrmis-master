<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Setting extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'UserModel',
      'SettingAppModel',
      'SettingSmtpModel',
      'SettingAccountModel',
      'SettingDashboardModel',
      'SettingGeneralModel',
    ));
  }

  public function index()
  {
    redirect(base_url('setting/account'));
  }

  public function application()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('setting'),
      'card_title' => 'Pengaturan › Aplikasi'
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('application', $data, TRUE);
    $this->template->render();
  }

  public function smtp()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('setting'),
      'card_title' => 'Pengaturan › SMTP'
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('smtp', $data, TRUE);
    $this->template->render();
  }

  public function account()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('setting'),
      'card_title' => 'Pengaturan › Akun',
      'role' => $this->session->userdata('user')['role'],
      'data' => $this->SettingAccountModel->getDetail(array('id' => $this->session->userdata('user')['id'])),
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('account', $data, TRUE);
    $this->template->render();
  }

  public function dashboard()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('setting'),
      'card_title' => 'Pengaturan › Dashboard Image'
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('dashboard_image', $data, TRUE);
    $this->template->render();
  }

  public function general()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('setting'),
      'card_title' => 'Pengaturan › Umum'
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('general', $data, TRUE);
    $this->template->render();
  }

  public function ajax_save_application()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->SettingAppModel->rules());

    if ($this->form_validation->run() === true) {
      echo json_encode($this->SettingAppModel->update());
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_test_smtp()
  {
    $this->handle_ajax_request();

    $app = $this->app();
    $mailParams = array(
      'receiver' => $app->smtp_user,
      'subject' => 'Test (' . md5(date('Y-m-d H:i:s')) . ')',
      'message' => '<h2>Test</h2><p>Congratulations, email has been successfully sent from ' . $app->app_name . '.</p>'
    );

    echo json_encode($this->sendMail($mailParams));
  }

  public function ajax_save_smtp()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->SettingSmtpModel->rules());

    if ($this->form_validation->run() === true) {
      echo json_encode($this->SettingSmtpModel->update());
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_save_account()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->SettingAccountModel->rules());

    if ($this->form_validation->run() === true) {
      // Upload foto prifile
      if (!empty($_FILES['profile_photo']['name'])) {
        $cpUpload = new CpUpload();
        $upload = $cpUpload->run('profile_photo', 'profile', true, true, 'jpg|jpeg|png|gif');

        $_POST['profile_photo'] = '';

        if ($upload->status === true) {
          $_POST['profile_photo'] = $upload->data->base_path;
        };
      };

      echo json_encode($this->SettingAccountModel->update());
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_save_dashboard()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->SettingDashboardModel->rules());

    if ($this->form_validation->run() === true) {
      if (!empty($_FILES['dashboard_image_source']['name'])) {
        $cpUpload = new CpUpload();
        $upload = $cpUpload->run('dashboard_image_source', 'dashboard', true, true, 'jpg|jpeg|png|gif');

        $_POST['dashboard_image_source'] = '';

        if ($upload->status === true) {
          $_POST['dashboard_image_source'] = $upload->data->base_path;
        };
      };

      echo json_encode($this->SettingDashboardModel->update());
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_save_general()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->SettingGeneralModel->rules());

    if ($this->form_validation->run() === true) {
      echo json_encode($this->SettingGeneralModel->update());
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }
}
