<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MenuModel extends CI_Model
{
  private $_table = 'menu';
  private $_tableView = 'view_menu';

  public function rules()
  {
    return array(
      [
        'field' => 'parent_id',
        'label' => 'Parent',
        'rules' => 'required'
      ],
      [
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'required'
      ],
      [
        'field' => 'link',
        'label' => 'Link',
        'rules' => 'required'
      ],
      [
        'field' => 'is_newtab',
        'label' => 'Open In New Tab',
        'rules' => 'required'
      ],
      [
        'field' => 'order_pos',
        'label' => 'Order',
        'rules' => 'required'
      ]
    );
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
    $this->db->where($params);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    };

    return $this->db->get($this->_tableView)->result();
  }

  public function getAllByRole($role = null, $params = array())
  {
    $this->db->like('role_pic', $role);
    $this->db->where($params);
    return $this->db->get($this->_tableView)->result();
  }

  public function getDetail($where, $value)
  {
    return $this->db->get_where($this->_tableView, array($where => $value))->row();
  }

  public function getRecursive($parent_id = null)
  {
    $result = array();
    $role = $this->session->userdata('user')['role'];
    $data = $this->db->order_by('order_pos', 'asc')->get_where($this->_tableView, array('parent_id' => $parent_id))->result();

    foreach ($data as $index => $item) {
      $item->childs = $this->getRecursive($item->id);
      $role_pic = $item->role_pic;

      if (!empty($role_pic) && !is_null($role_pic)) {
        $role_pic = json_decode($role_pic);

        if (in_array($role, $role_pic)) {
          $result[$item->id] = $item;
        };
      };
    };

    return $result;
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $post = $this->input->post();
      $roles = (isset($post['role_pic'])) ? $post['role_pic'] : null;
      $post['parent_id'] = ($post['parent_id'] == "0") ? null : $post['parent_id'];
      $post['link_tobase'] = (isset($post['link_tobase']) && !is_null($post['link_tobase'])) ? $post['link_tobase'] : '0';

      if (!is_null($roles) && count($roles) > 0) {
        $this->parent_id = $post['parent_id'];
        $this->name = $post['name'];
        $this->order_pos = $post['order_pos'];
        $this->link = $post['link'];
        $this->link_tobase = $post['link_tobase'];
        $this->icon = $post['icon'];
        $this->is_newtab = $post['is_newtab'];
        $this->role_pic = json_encode($roles);
        $this->db->insert($this->_table, $this);

        $response = array('status' => true, 'data' => 'Data has been saved.');
      } else {
        $response = array('status' => false, 'data' => 'The Role PIC field is required.');
      };
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function update($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $post = $this->input->post();
      $roles = (isset($post['role_pic'])) ? $post['role_pic'] : null;
      $post['parent_id'] = ($post['parent_id'] == "0") ? null : $post['parent_id'];
      $post['link_tobase'] = (isset($post['link_tobase']) && !is_null($post['link_tobase'])) ? $post['link_tobase'] : '0';

      if (!is_null($roles) && count($roles) > 0) {
        $this->parent_id = $post['parent_id'];
        $this->name = $post['name'];
        $this->order_pos = $post['order_pos'];
        $this->link = $post['link'];
        $this->link_tobase = $post['link_tobase'];
        $this->icon = $post['icon'];
        $this->is_newtab = $post['is_newtab'];
        $this->role_pic = json_encode($roles);
        $this->db->update($this->_table, $this, array('id' => $id));

        $response = array('status' => true, 'data' => 'Data has been saved.');
      } else {
        $response = array('status' => false, 'data' => 'The Role PIC field is required.');
      };
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function delete($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->delete($this->_table, array('id' => $id));
      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data.');
    };

    return $response;
  }
}
