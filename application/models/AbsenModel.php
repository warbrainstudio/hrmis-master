<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AbsenModel extends CI_Model
{
  private $_table = 'absen_pegawai';
  public $_tableView = 'absen_pegawai';


  public function getQuery($filter = null)
  {
      $query = "
        SELECT t.* FROM (
          SELECT 
            ab.*, 
            p.id as id_pegawai,
            COALESCE(p.nama_lengkap, '-') AS nama,
            (CASE WHEN ab.verifikasi_masuk = 1 THEN 'Finger' WHEN ab.verifikasi_masuk = 0 THEN 'Input' ELSE '' END) AS verifikasi_m,
            (CASE WHEN ab.verifikasi_pulang = 1 THEN 'Finger' WHEN ab.verifikasi_pulang = 0 THEN 'Input' ELSE '' END) AS verifikasi_p,
            EXTRACT(EPOCH FROM (ab.pulang - ab.masuk)) / 3600 AS jam_kerja,
            m_masuk.nama_mesin as nama_mesin_masuk, 
            m_pulang.nama_mesin as nama_mesin_pulang
          FROM absen_pegawai ab
          LEFT JOIN pegawai p ON ab.absen_id = p.absen_pegawai_id
          LEFT JOIN mesin_absen m_masuk ON m_masuk.ipadress = ab.mesin_masuk
          LEFT JOIN mesin_absen m_pulang ON m_pulang.ipadress = ab.mesin_pulang
          ORDER BY p.nama_lengkap, ab.tanggal_absen ASC
        ) t
        WHERE 1=1
      ";

      if (!is_null($filter)) $query .= $filter;
      return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
      if (isset($params['tanggal_absen'])) {
          
        $orderField = 'tanggal_absen';
        $dateParam = $params['tanggal_absen'];

        $this->db->select('absen_pegawai.absen_id,
                          absen_pegawai.tanggal_absen,
                          CASE WHEN absen_pegawai.masuk IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.masuk, \'HH24:MI:SS\') END AS jam_masuk,
                          CASE WHEN absen_pegawai.verifikasi_masuk = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_masuk = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_m, 
                          CASE WHEN absen_pegawai.pulang IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.pulang, \'HH24:MI:SS\') END AS jam_pulang,
                          CASE WHEN absen_pegawai.verifikasi_pulang = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_pulang = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_p,
                          CASE WHEN absen_pegawai.pulang - absen_pegawai.masuk IS NULL THEN \'-\' ELSE (EXTRACT(EPOCH FROM (absen_pegawai.pulang - absen_pegawai.masuk)) / 3600)::text END AS jam_kerja,
                          pegawai.nrp,
                          COALESCE(pegawai.nama_lengkap, \'-\') AS pegawai_nama,
                          m_masuk.nama_mesin as mesin_m, 
                          m_pulang.nama_mesin as mesin_p');
        $this->db->join('pegawai', 'absen_pegawai.absen_id = pegawai.absen_pegawai_id', 'left');
        $this->db->join('mesin_absen m_masuk', 'm_masuk.ipadress = absen_pegawai.mesin_masuk', 'left');
        $this->db->join('mesin_absen m_pulang', 'm_pulang.ipadress = absen_pegawai.mesin_pulang', 'left');
        //$this->db->where('absen_pegawai_id IS NOT NULL');
        $this->db->order_by('absen_pegawai.tanggal_absen, pegawai.nama_lengkap ASC');
        
        if (preg_match('/^\d{4}-\d{2}$/', $dateParam)) {

          list($year, $month) = explode('-', $dateParam);
          $startDate = date('Y-m-d', strtotime("$year-$month-1"));
          $endDate = date('Y-m-d', strtotime("last day of $year-$month"));
          $this->db->where("tanggal_absen BETWEEN '$startDate' AND '$endDate'");

        }elseif (preg_match('/^\d{4}$/', $dateParam)) {

          $startDate = date('Y-m-d', strtotime("$dateParam-01-01"));
          $endDate = date('Y-m-d', strtotime("$dateParam-12-31"));
          $this->db->where("tanggal_absen BETWEEN '$startDate' AND '$endDate'");

        }else{  

          $this->db->where("DATE(absen_pegawai.tanggal_absen)", $dateParam);

        }

        unset($params['tanggal_absen']);
        
        if (!is_null($orderField)) {
          $this->db->order_by($orderField, $orderBy);
        }
    
        return $this->db->get($this->_table)->result();

      }elseif(isset($params['absen_id'])){
        
        $orderField = 'tanggal_absen';

        $this->db->select('absen_pegawai.absen_id,
                          absen_pegawai.tanggal_absen, 
                          CASE WHEN absen_pegawai.masuk IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.masuk, \'HH24:MI:SS\') END AS jam_masuk,
                          CASE WHEN absen_pegawai.verifikasi_masuk = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_masuk = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_m, 
                          CASE WHEN absen_pegawai.mesin_masuk IS NULL THEN \'-\' ELSE absen_pegawai.mesin_masuk END AS mesin_m,
                          CASE WHEN absen_pegawai.pulang IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.pulang, \'HH24:MI:SS\') END AS jam_pulang,
                          CASE WHEN absen_pegawai.verifikasi_pulang = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_pulang = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_p,
                          CASE WHEN absen_pegawai.mesin_pulang IS NULL THEN \'-\' ELSE absen_pegawai.mesin_pulang END AS mesin_p,
                          CASE WHEN absen_pegawai.pulang - absen_pegawai.masuk IS NULL THEN \'-\' ELSE (EXTRACT(EPOCH FROM (absen_pegawai.pulang - absen_pegawai.masuk)) / 3600)::text END AS jam_kerja,
                          m_masuk.nama_mesin as mesin_m, 
                          m_pulang.nama_mesin as mesin_p');
        $this->db->join('pegawai', 'absen_pegawai.absen_id = pegawai.absen_pegawai_id', 'left');
        $this->db->join('mesin_absen m_masuk', 'm_masuk.ipadress = absen_pegawai.mesin_masuk', 'left');
        $this->db->join('mesin_absen m_pulang', 'm_pulang.ipadress = absen_pegawai.mesin_pulang', 'left');
        $this->db->where($params);
        $this->db->order_by('tanggal_absen ASC');

        if (!is_null($orderField)) {
          $this->db->order_by($orderField, $orderBy);
        }
        
        return $this->db->get($this->_table)->result();

      }else{

        $this->db->where($params);

        if (!is_null($orderField)) {
          $this->db->order_by($orderField, $orderBy);
        }
        
        return $this->db->get($this->_table)->result();

      }
  }
   
  public function getDetail($params = array())
  {
    return $this->db->where($params)->get($this->_table)->row();
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

  public function fetchDataFromMachine($IP, $Key, $startDate, $endDate) {
    $timeout = 200;
    $Connect = fsockopen($IP, "80", $errno, $errstr, $timeout);
    $filteredData = [];
    if ($Connect) {
        $formattedStartDate = date('Y-m-d\TH:i:s', strtotime($startDate . ' 00:00:00'));
        $formattedEndDate = date('Y-m-d\TH:i:s', strtotime($endDate . ' 23:59:59'));

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

        $buffer = "";
        while (!feof($Connect)) {
            $Response = fgets($Connect, 1024);
            if ($Response === false) break;
            $buffer .= $Response;
        }
        fclose($Connect);

        if (strpos($buffer, '500 Internal Server Error') !== false) {
            echo "The server encountered an error while processing the request.";
            exit;
        }

        $this->load->helper('parse');
        $buffer = Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
        $buffer = explode("\r\n", $buffer);

        foreach ($buffer as $line) {
            $data = Parse_Data($line, "<Row>", "</Row>");
            if ($data) {
                $PIN = Parse_Data($data, "<PIN>", "</PIN>");
                $DateTime = Parse_Data($data, "<DateTime>", "</DateTime>");
                $Verified = Parse_Data($data, "<Verified>", "</Verified>");
                $Status = Parse_Data($data, "<Status>", "</Status>");

                $dataDateTime = date('Y-m-d', strtotime($DateTime));

                if ($dataDateTime >= $startDate && $dataDateTime <= $endDate) {
                    $filteredData[] = [
                        'PIN' => htmlspecialchars($PIN),
                        'DateTime' => htmlspecialchars($DateTime),
                        'Verified' => htmlspecialchars($Verified),
                        'Status' => htmlspecialchars($Status),
                        'Machine' => htmlspecialchars($IP),
                    ];
                }
            }
        }
    } else {
        echo "Connection failed: $errstr ($errno)";
    }

    return $filteredData;
}

  public function import_data($data) {

    $dataCount = count($data);

    if ($dataCount > 0) {
        $failedInsertions = [];
        $existingRecordsCount = 0;

        $this->db->trans_start();
        try {
            foreach ($data as $row) {
            
                $userID = $row['PIN'];
                $dateTime = $row['DateTime'];
                $verified = $row['Verified'];
                $status = $row['Status'];
                $machine = $row['Machine'];

                $data = [
                    'absen_id' => $userID,
                    'tanggal_absen' => $dateTime
                ];

                
                if ($status === "0") { 
                    $data['masuk'] = $dateTime;
                    $data['verifikasi_masuk'] = $verified;
                    $data['mesin_masuk'] = $machine;
                } else { 
                    $data['pulang'] = $dateTime;
                    $data['verifikasi_pulang'] = $verified;
                    $data['mesin_pulang'] = $machine;
                }

                
                $this->db->where('absen_id', $userID);
                $this->db->where('tanggal_absen', $dateTime);
                $count = $this->db->count_all_results($this->_table);

                if ($count == 0) {
                    
                    if (!$this->db->insert($this->_table, $data)) {
                        $failedInsertions[] = [
                            'absen_id' => $userID,
                            'dateTime' => $dateTime,
                            'error' => $this->db->error()['message']
                        ];
                    }
                } else {
                  
                    if ($status === "0") {
                        
                        $this->db->where('absen_id', $userID);
                        $this->db->where('tanggal_absen', $dateTime);
                        $this->db->where('masuk IS NULL'); 
                        if (!$this->db->update($this->_table, [
                            'masuk' => $dateTime,
                            'verifikasi_masuk' => $verified,
                            'mesin_masuk' => $machine
                        ])) {
                            $failedInsertions[] = [
                                'absen_id' => $userID,
                                'dateTime' => $dateTime,
                                'error' => $this->db->error()['message']
                            ];
                        }
                    } else {
                        
                        $this->db->where('absen_id', $userID);
                        $this->db->where('tanggal_absen', $dateTime);
                        $this->db->where('pulang IS NULL'); 
                        if (!$this->db->update($this->_table, [
                            'pulang' => $dateTime,
                            'verifikasi_pulang' => $verified,
                            'mesin_pulang' => $machine
                        ])) {
                            $failedInsertions[] = [
                                'absen_id' => $userID,
                                'dateTime' => $dateTime,
                                'error' => $this->db->error()['message']
                            ];
                        }
                    }
                }
            }

            $this->db->trans_complete();
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $failedInsertions[] = [
                'absen_id' => isset($userID) ? $userID : 'N/A',
                'dateTime' => isset($dateTime) ? $dateTime : 'N/A',
                'error' => $e->getMessage()
            ];
        }

        $response = [
            'status' => true
        ];

    } else {
        $response = [
            'status' => false,
            'message' => "No data to import."
        ];
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
  }

  function br2nl($text)
  {
    return str_replace("\r\n", '<br/>', htmlspecialchars_decode($text));
  }

  function clean_number($number)
  {
    return preg_replace('/[^0-9]/', '', $number);
  }
}
