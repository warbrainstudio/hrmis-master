<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Notification extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('NotificationModel'));
    }

    public function index()
    {
        $this->load->library('pagination');
        $config = array(
            'base_url'         => base_url('notification/'),
            'total_rows'       => $this->NotificationModel->countAll(array('user_to' => $this->session->userdata('user')['id'])),
            'per_page'         => 15,
            'uri_segment'      => 2,
            'first_link'       => '<i class="zmdi zmdi-caret-left-circle"></i>',
            'last_link'        => '<i class="zmdi zmdi-caret-right-circle"></i>',
            'next_link'        => '<i class="zmdi zmdi-long-arrow-right"></i>',
            'prev_link'        => '<i class="zmdi zmdi-long-arrow-left"></i>',
            'full_tag_open'    => '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">',
            'full_tag_close'   => '</ul></nav></div>',
            'num_tag_open'     => '<li class="page-item"><span class="page-link">',
            'num_tag_close'    => '</span></li>',
            'cur_tag_open'     => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close'    => '<span class="sr-only">(current)</span></span></li>',
            'next_tag_open'    => '<li class="page-item"><span class="page-link">',
            'next_tagl_close'  => '<span aria-hidden="true">&raquo,</span></span></li>',
            'prev_tag_open'    => '<li class="page-item"><span class="page-link">',
            'prev_tagl_close'  => '</span>Next</li>',
            'first_tag_open'   => '<li class="page-item"><span class="page-link">',
            'first_tagl_close' => '</span></li>',
            'last_tag_open'    => '<li class="page-item"><span class="page-link">',
            'last_tagl_close'  => '</span></li>',
        );
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data = array(
            'app' => $this->app(),
            'card_title' => 'Notifications',
            'data' => $this->NotificationModel->getPaginate(array('user_to' => $this->session->userdata('user')['id']), $config['per_page'], $page)
        );

        $this->template->set('title', $data['app']->app_name, TRUE);
        $this->template->load_view('notification', $data, TRUE);
        $this->template->render();
    }

    public function get_all()
    {
        $this->handle_ajax_request();
        $data = $this->NotificationModel->getAll(array('user_to' => $this->session->userdata('user')['id']));
        $dataUnread = $this->NotificationModel->getAll(array('user_to' => $this->session->userdata('user')['id'], 'is_read' => '0'));

        return array(
            'count' => count($data),
            'count_unread' => count($dataUnread),
            'data' => $data
        );
    }

    public function last($isJson = null)
    {
        $this->handle_ajax_request();
        $data = $this->NotificationModel->getLast(array('user_to' => $this->session->userdata('user')['id']));
        $dataUnread = $this->NotificationModel->getAll(array('user_to' => $this->session->userdata('user')['id'], 'is_read' => '0'));

        $result = array(
            'count' => count($data),
            'count_unread' => count($dataUnread),
            'data' => $data
        );

        if (!is_null($isJson)) {
            echo json_encode($result);
        } else {
            return $result;
        };
    }

    public function read($id)
    {
        $data = $this->NotificationModel->getDetail(array('id' => $id));

        if (count($data) > 0) {
            $link = $data->link;

            if (!empty($link) && !is_null($link)) {
                $this->NotificationModel->setIsRead($id);
                redirect(base_url($link));
            } else {
                redirect(base_url());
            };
        } else {
            redirect(base_url());
        };
    }

    public function minimal_stock()
    {
        $this->handle_ajax_request();
        $data = $this->NotificationModel->getMinimalStock();
        echo json_encode(array(
            'count' => count($data),
            'count_formated' => number_format(count($data))
        ));
    }
}
