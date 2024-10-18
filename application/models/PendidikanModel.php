<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PendidikanModel extends CI_Model
{
  public function getAll()
  {
    $data = array(
      array('id' => 'SD', 'text' => 'SD'),
      array('id' => 'SMP', 'text' => 'SMP'),
      array('id' => 'SMA/SMK', 'text' => 'SMA/SMK'),
      array('id' => 'D1', 'text' => 'D1'),
      array('id' => 'D2', 'text' => 'D2'),
      array('id' => 'D3', 'text' => 'D3'),
      array('id' => 'D4', 'text' => 'D4'),
      array('id' => 'S1', 'text' => 'S1'),
      array('id' => 'S1 Profesi', 'text' => 'S1 Profesi'),
      array('id' => 'S2', 'text' => 'S2'),
      array('id' => 'S3', 'text' => 'S3'),
    );
    return $data;
  }
}
