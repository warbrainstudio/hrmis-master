<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StatusKawinModel extends CI_Model
{
  public function getAll()
  {
    $data = array(
      array('id' => 'Belum Kawin', 'text' => 'Belum Kawin'),
      array('id' => 'Kawin', 'text' => 'Kawin'),
      array('id' => 'Cerai Hidup', 'text' => 'Cerai Hidup'),
      array('id' => 'Cerai Mati', 'text' => 'Cerai Mati'),
    );
    return $data;
  }
}
