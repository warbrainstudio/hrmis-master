<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HubunganKeluargaModel extends CI_Model
{
  public function getAll()
  {
    $data = array(
      array('id' => 'Anak', 'text' => 'Anak'),
      array('id' => 'Mertua', 'text' => 'Mertua'),
      array('id' => 'Orang Tua', 'text' => 'Orang Tua'),
      array('id' => 'Pasangan', 'text' => 'Pasangan'),
      array('id' => 'Saudara', 'text' => 'Saudara'),
    );
    return $data;
  }
}
