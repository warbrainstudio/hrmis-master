<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NotificationModel extends CI_Model
{
  private $_table = 'notification';
  private $_tableView = 'view_notification';

  public function countAll($params = array())
  {
    return $this->db->where($params)->get($this->_table)->num_rows();
  }

  public function getPaginate($params = array(), $per_page, $offset)
  {
    return $this->db->where($params)->order_by('id', 'desc')->get($this->_table, $per_page, $offset)->result();
  }

  public function getAll($params = array())
  {
    return $this->db->where($params)->order_by('id', 'desc')->get($this->_tableView)->result();
  }

  public function getLast($params = array())
  {
    return $this->db->where($params)->order_by('id', 'desc')->limit(10)->get($this->_tableView)->result();
  }

  public function getDetail($params = array())
  {
    return $this->db->where($params)->get($this->_tableView)->row();
  }

  public function getMinimalStock()
  {
    return $this->db->select('id')->where('stock < minimal_stock')->get('barang')->result();
  }

  public function insert($post)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $post = $this->input->post();

      $this->user_from = $this->session->userdata('user')['id'];
      $this->user_to = $post['user_to'];
      $this->ref = $post['ref'];
      $this->ref_id = $post['ref_id'];
      $this->description = $post['description'];
      $this->link = $post['link'];
      $this->db->insert($this->_table, $this);

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function insertBatch($data)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->insert_batch($this->_table, $data);

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function setIsRead($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->is_read = "1";
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }
}
