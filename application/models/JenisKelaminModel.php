<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JenisKelaminModel extends CI_Model
{
  public function getAll()
  {
    $data = array(
      array('id' => 'Laki-laki', 'text' => 'Laki-laki'),
      array('id' => 'Perempuan', 'text' => 'Perempuan'),
    );
    return $data;
  }
}
