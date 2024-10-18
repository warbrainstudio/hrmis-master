<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DocumentModel extends CI_Model
{
  private $_table = 'document';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'file_name',
        'label' => 'File',
        'rules' => 'required|trim'
      ]
    );
  }

  public function getAll_list_count($params = array())
  {
    return $this->db->where($params)->count_all_results($this->_table);
  }

  public function getAll_list($params = array(), $per_page = 16, $offset = 0)
  {
    return $this->db->where($params)->limit($per_page, $offset)->get($this->_table)->result();
  }

  public function getAll($where = array(), $whereInKey = null, $whereInValues = array())
  {
    if (count($where) > 0) {
      $this->db->where($where);
    };

    if (!is_null($whereInKey) && count($whereInValues) > 0) {
      $this->db->where_in($whereInKey, $whereInValues);
    };

    return $this->db->get($this->_table)->result();
  }

  public function getDetail($params = array())
  {
    return $this->db->where($params)->get($this->_table)->row();
  }

  public function insert($post)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->ref = $post['ref'];
      $this->ref_id = $post['ref_id'];
      $this->description = $post['description'];
      $this->file_raw_name = $post['file_raw_name'];
      $this->file_raw_name_thumb = $post['file_raw_name_thumb'];
      $this->file_name = $post['file_name'];
      $this->file_name_thumb = $post['file_name_thumb'];
      $this->file_size = $post['file_size'];
      $this->file_type = $post['file_type'];
      $this->file_ext = $post['file_ext'];
      $this->created_by = $this->session->userdata('user')['id'];
      $this->db->insert($this->_table, $this);

      $response = array('status' => true, 'data' => 'File has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your file.');
    };

    return $response;
  }

  public function insertBatch($data)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->insert_batch($this->_table, $data);

      $response = array('status' => true, 'data' => 'File has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your file.');
    };

    return $response;
  }

  public function setIsVerified($id, $value = 0)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->is_verified = $value;
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function setIsVerifiedByRefId($refId, $value = 0)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->is_verified = $value;
      $this->db->update($this->_table, $this, array('ref_id' => $refId));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function delete($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $document = $this->getDetail(array('id' => $id));

      if (!empty($document) && !is_null($document)) {
        // Delete data
        $this->db->delete($this->_table, array('id' => $id));

        // Delete file
        @unlink(FCPATH . $document->file_name);
        @unlink(FCPATH . $document->file_name_thumb);
      };

      $response = array('status' => true, 'data' => 'File has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your file.');
    };

    return $response;
  }

  public function truncate()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->truncate($this->_table);

      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data.');
    };

    return $response;
  }

  function br2nl($text)
  {
    return str_replace("\r\n", '<br/>', htmlspecialchars_decode($text));
  }
}
