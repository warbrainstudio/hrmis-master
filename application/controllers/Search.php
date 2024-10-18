<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Search extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('SearchModel'));
    }

    public function index()
    {
        $keyword = (isset($_GET['q'])) ? $_GET['q'] : '';
        $model = array();
        $data = array(
            'app' => $this->app(),
            'card_title' => 'Search',
            'card_subTitle' => count($model) . ' data found for "' . $keyword . '".',
            'controller' => $this,
            'data' => $model,
        );
        $this->template->set('title', $data['app']->app_name, TRUE);
        $this->template->load_view('search', $data, TRUE);
        $this->template->render();
    }
}
