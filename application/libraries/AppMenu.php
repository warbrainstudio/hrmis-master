<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class AppMenu
{
  var $CI;

	function __construct() {
    $this->CI =& get_instance();
    $this->CI->load->model(array('MenuModel'));
  }
    
  public function getAll() {
    return $this->CI->MenuModel->getRecursive();
  }
}
