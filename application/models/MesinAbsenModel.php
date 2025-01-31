<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MesinAbsenModel extends CI_Model
{
  private $_table = 'mesin_absen';
  private $_tableView = '';
  public $status_ip = '';
  public $status_commkey = '';
  public $ipadress = '';

  public function rules($id = null, $ip)
  {
    return array(
      [
        'field' => 'nama_mesin',
        'label' => 'Nama Mesin',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'ipadress',
        'label' => 'IP Adress',
        'rules' => 'required|trim',
      ],
      [
        'field' => 'commkey',
        'label' => 'Comm Key',
        'rules' => 'required|trim',
        'rules' => [
          'required',
          'trim',
          [
            'check_connect',
            function ($value) use ($id, $ip) {
              return $this->_check_connect($value, $id, $ip);
            }
          ]
        ]
      ],
      [
        'field' => 'lokasi',
        'label' => 'Lokasi',
        'rules' => 'required|trim'
      ],
    );
  }

  private function _check_connect($value, $id, $ip)
  {
    if(empty($id)){
      $result_ip = $this->ping($ip);
      if($result_ip===true){
        $this->status_ip = "Connect";
        $Key = $value;
        $result = $this->fetch_data_from_machine($ip, $Key);
        if($result){
          $this->status_commkey = "Connect";
          return true;
        }else{
          $this->form_validation->set_message('check_connect', 'Comm Key "'.$value.'" atau IP "' . $ip . '" salah');
          $this->status_commkey = "Disconnect";
          return false;
        }
      }else{
        $this->form_validation->set_message('check_connect', 'IP "' . $ip . '" tidak terhubung atau sedang offline');
        $this->status_ip = "Disconnect";
        return false;
      }
    }else{
      $result_ip = $this->ping($ip);
      if($result_ip===true){
        $this->status_ip = "Connect";
        $Key = $value;
        $result = $this->fetch_data_from_machine($ip, $Key);
        if($result){
          $this->status_commkey = "Connect";
          return true;
        }else{
          $this->form_validation->set_message('check_connect', 'Comm Key "'.$value.'" atau IP "' . $ip . '" salah');
          $this->status_commkey = "Disconnect";
          return false;
        }
      }else{
        $this->form_validation->set_message('check_connect', 'IP "' . $ip . '" tidak terhubung atau sedang offline');
        $this->status_ip = "Disconnect";
        return false;
      }
    }
  }
  

  public function getQuery($filter = null)
  {
    $query = "
      SELECT mesin_absen.* FROM mesin_absen
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

  public function getDetail($params = array())
  {
    $this->db->where($params);
    return $this->db->get($this->_table)->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    if($this->status_ip === "Connect" && $this->status_commkey === "Connect"){
      $status = "Connect";
    }else{
      $status = "Disconnect";
    }

    try {
      $this->nama_mesin = $this->input->post('nama_mesin');
      $this->lokasi = $this->input->post('lokasi');
      $this->ipadress = $this->input->post('ipadress');
      $this->status_ip = $this->status_ip;
      $this->commkey = $this->input->post('commkey');
      $this->status_commkey = $this->status_commkey;
      $this->status = $status;
      $this->created_by = $this->session->userdata('user')['id'];
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

  public function update($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    if($this->status_ip === "Connect" && $this->status_commkey === "Connect"){
      $status = "Connect";
    }else{
      $status = "Disconnect";
    }

    try {
      $this->nama_mesin = $this->input->post('nama_mesin');
      $this->lokasi = $this->input->post('lokasi');
      $this->ipadress = $this->input->post('ipadress');
      $this->status_ip = $this->status_ip;
      $this->commkey = $this->input->post('commkey');
      $this->status_commkey = $this->status_commkey;
      $this->status = $status;
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $id));

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
      $this->db->delete($this->_table, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data.');
    };

    return $response;
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
    return preg_replace('/[^0-9]/', '', $number);
  }

  public function checkConnect($ip)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    $this->db->where('ipadress', $ip);
    $query = $this->db->get('mesin_absen');
    $mesin = $query->row();

    if ($mesin) {
        $comm = $mesin->commkey;
        $commResult = $this->fetch_data_from_machine($ip, $comm);
        if ($commResult) {
            $this->status_commkey = "Connect";
        } else {
            $this->status_commkey = "Disconnect";
        }
    }

    if($this->status_ip === "Connect" && $this->status_commkey === "Connect"){
      $status = "Connect";
    }else{
      $status = "Disconnect";
    }

    try {
        $this->ipadress = $ip;
        $this->status_ip = $this->status_ip;
        $this->status_commkey = $this->status_commkey;
        $this->status = $status; 
        $this->updated_by = $this->session->userdata('user')['id'];
        $this->updated_date = date('Y-m-d H:i:s');
        $this->db->update($this->_table, $this, array('ipadress' => $ip));
        if($status=="Connect"){
          $response = array('status' => true, 'data' => 'Koneksi berhasil');
        }else{
          $response = array('status' => false, 'data' => 'Koneksi gagal');
        }
    } catch (\Throwable $th) {
        $response = array('status' => false, 'data' => 'Error.');
    }

    return $response;
  }

  public function ping($ip)
  {
      $reply = 1;
      $ping = exec("ping -n $reply $ip", $output, $status);
      return $status === 0;
  }
  

  public function fetch_data_from_machine($IP, $Key) 
  {
      $timeout = 200;
      $Connect = @fsockopen($IP, "80", $errno, $errstr, $timeout);
      $filteredData = [];
      $currentDate = date('Y-m-d');
      
      if ($Connect) {

          $this->status_ip = "Connect";
          $formattedStartDate = date('Y-m-d\TH:i:s', strtotime($currentDate . ' 00:00:00'));
          $formattedEndDate = date('Y-m-d\TH:i:s', strtotime($currentDate . ' 23:59:59'));
  
          $soap_request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <soap:Body>
      <GetAttLog xmlns="http://tempuri.org/">
          <ArgComKey xsi:type="xsd:integer">$Key</ArgComKey>
          <Arg></Arg>
          <DateTimeRange>
              <StartDate>$formattedStartDate</StartDate>
              <EndDate>$formattedEndDate</EndDate>
          </DateTimeRange>
      </GetAttLog>
  </soap:Body>
</soap:Envelope>
XML;
  
          $newLine = "\r\n";
          fputs($Connect, "POST /iWsService HTTP/1.1" . $newLine);
          fputs($Connect, "Host: $IP" . $newLine);
          fputs($Connect, "Content-Type: text/xml" . $newLine);
          fputs($Connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
          fputs($Connect, $soap_request . $newLine);

          $startTime = microtime(true);
          $buffer = "";
          while (!feof($Connect)) {
            if (microtime(true) - $startTime > 3) {
              fclose($Connect);
              $this->status_commkey = "Connect";
              return true;
            }else{
              $Response = fgets($Connect, 1024);
              if ($Response === false) break;
              $buffer .= $Response;
            }
          }
          fclose($Connect);
  
          if (strpos($buffer, '500 Internal Server Error') !== false) {
              return ["error" => "The server encountered an error while processing the request."];
          }
  
          $this->load->helper('parse');
          $buffer = Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
          $buffer = explode("\r\n", $buffer);
  
          foreach ($buffer as $line) {
              $data = Parse_Data($line, "<Row>", "</Row>");
              if ($data) {
                  $filteredData[] = $data;
              }
          }
  
          if (!empty($filteredData)) {

              $this->status_commkey = "Connect";
              return true;

          } else {

              $this->status_commkey = "Disconnect";
              return false;
              
          }

      } else {

          $this->status_ip = "Disconnect";
          return false;

      }
  }
  
}
