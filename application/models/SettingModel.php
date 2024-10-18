<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingModel extends CI_Model
{
  private $_table = 'setting';

  public function rules()
  {
    return array(
      [
        'field' => 'data',
        'label' => 'Key',
        'rules' => 'required'
      ],
      [
        'field' => 'content',
        'label' => 'Content',
        'rules' => 'required'
      ]
    );
  }

  public function getAll()
  {
    return $this->db->get($this->_table)->result();
  }

  public function getDetail($where, $value)
  {
    return $this->db->get_where($this->_table, array($where => $value))->row();
  }

  public function insert()
  {
    $post = $this->input->post();
    $this->data = $post['data'];
    $this->content = $post['content'];
    $this->db->insert($this->_table, $this);
  }

  public function update()
  {
    $post = $this->input->post();
    $this->data = $post['data'];
    $this->content = $post['content'];
    $this->db->update($this->_table, $this, array('id' => $post['id']));
  }

  public function delete($id)
  {
    return $this->db->delete($this->_table, array('id' => $id));
  }
}
