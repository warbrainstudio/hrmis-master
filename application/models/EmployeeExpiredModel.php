<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmployeeExpiredModel extends CI_Model
{
  private $_table = 'pegawai';
  private $_tableView = '';

  public function getQuery($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT
          p.nrp,
          p.nama_lengkap,
          p.unit_id,
          p.sub_unit_id,
          kp.jenis_pegawai_id,
          kp.eoc,
          j.nama_jenis_pegawai,
          u.kode_unit,
          u.nama_unit,
          su.kode_sub_unit,
          su.nama_sub_unit,
          (CASE WHEN p.status_active = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END) AS nama_status_active
        FROM pegawai p
        LEFT JOIN kontrak_pegawai kp ON kp.pegawai_id = p.id
        LEFT JOIN jenis_pegawai j on j.id = kp.jenis_pegawai_id
        LEFT JOIN unit u ON u.id = p.unit_id
        LEFT JOIN sub_unit su ON su.id = p.sub_unit_id
        WHERE p.status_active = 1
            AND kp.status_active = 1
            AND (
                kp.eoc BETWEEN CURRENT_DATE AND (CURRENT_DATE + INTERVAL '3 months')
            OR
                kp.eoc <= CURRENT_DATE
                )
        GROUP BY p.id, p.nrp, p.nama_lengkap, p.unit_id, p.sub_unit_id, kp.jenis_pegawai_id, kp.eoc, j.nama_jenis_pegawai, u.kode_unit,
          u.nama_unit, su.kode_sub_unit, su.nama_sub_unit, p.status_active
        ORDER BY eoc DESC
      ) t
      WHERE 1=1
    ";
    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
    $this->db->where($params);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    };

    return $this->db->get($this->_table)->result();
  }

  public function getDetail_sub_unit($params = array())
  {
    $this->db->join('unit', 'unit.id = sub_unit.unit_id', 'left');
    if (isset($params['id']) && $params['id'] !== 'null') {
      $this->db->where('sub_unit.id', $params['id']);
    } else {
        $this->db->where('sub_unit.id IS NULL', null, false);
    }
    return $this->db->get('sub_unit')->row();
  }

  public function truncate()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->truncate($this->_table);

      $response = array('status' => true, 'data' => 'Data has been truncated.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to truncate your data.');
    };

    return $response;
  }

  function br2nl($text)
  {
    return str_replace("\r\n", '<br/>', htmlspecialchars_decode($text));
  }

  function clean_number($number)
  {
    return preg_replace('/[^0-9.]/', '', $number);
  }
}