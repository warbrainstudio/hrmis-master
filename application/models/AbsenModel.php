<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AbsenModel extends CI_Model
{
  private $_table = 'absen_pegawai';
  public $_tableView = 'absen_pegawai';

  
  public function getQueryRaw($filter = null)
  {
    $query = "
      SELECT t.* FROM (
        SELECT 
          abr.*,
          (CASE WHEN p.absen_pegawai_id IS NOT NULL THEN p.nama_lengkap ELSE 'ID Absen : ' || CAST(abr.absen_id AS VARCHAR) END) AS nama, 
          (CASE WHEN abr.status = 0 THEN 'Masuk' WHEN abr.status = 3 THEN 'Cuti' ELSE 'Pulang' END) AS nama_status,
          (CASE WHEN abr.verified = 1 THEN 'Finger' ELSE 'Input' END) AS verifikasi,
          p.id as id_pegawai,
          p.nrp,
          p.unit_id,
          p.sub_unit_id,
          u.kode_unit,
          u.nama_unit,
          su.kode_sub_unit,
          su.nama_sub_unit,
          m.ipadress, 
          m.nama_mesin,
          m.lokasi
        FROM absen_pegawai_raw abr
        LEFT JOIN pegawai p ON abr.absen_id = p.absen_pegawai_id
        LEFT JOIN unit u ON u.id = p.unit_id
        LEFT JOIN sub_unit su ON su.id = p.sub_unit_id
        LEFT JOIN mesin_absen m ON m.ipadress = abr.ipmesin
        ORDER BY abr.tanggal_absen ASC
      ) t
      WHERE 1=1
    ";

    if (!is_null($filter)) $query .= $filter;
    return $query;
  }

  public function getQuery($filter = null)
  {
      $query = "
        SELECT t.* FROM (
          SELECT 
            ab.*, 
            p.id as id_pegawai,
            p.nrp,
            (CASE WHEN p.absen_pegawai_id IS NOT NULL THEN p.nama_lengkap ELSE 'ID Absen : ' || CAST(ab.absen_id AS VARCHAR) END) AS nama,
            (CASE WHEN ab.masuk IS NULL THEN '-' WHEN TO_CHAR(ab.masuk, 'YYYY-MM-DD') != TO_CHAR(ab.pulang, 'YYYY-MM-DD') THEN TO_CHAR(ab.masuk, 'HH24:MI:SS DD-MM-YYYY ') ELSE TO_CHAR(ab.masuk, 'HH24:MI:SS') END) AS jam_masuk,
            (CASE WHEN ab.verifikasi_masuk = 1 THEN 'Finger' WHEN ab.verifikasi_masuk = 0 THEN 'Input' ELSE '-' END) AS verifikasi_m, 
            (CASE WHEN ab.pulang IS NULL THEN '-' ELSE TO_CHAR(ab.pulang, 'HH24:MI:SS') END) AS jam_pulang,
            (CASE WHEN ab.verifikasi_pulang = 1 THEN 'Finger' WHEN ab.verifikasi_pulang = 0 THEN 'Input' ELSE '-' END) AS verifikasi_p,
            EXTRACT(EPOCH FROM (ab.pulang - ab.masuk)) / 3600 AS jam_kerja,
            (CASE WHEN TO_CHAR(ab.masuk, 'YYYY-mm-dd') != TO_CHAR(ab.pulang, 'YYYY-mm-dd') THEN 'Shift Malam' ELSE '-' END) AS jenis_shift,
            p.unit_id,
            p.sub_unit_id,
            m_masuk.nama_mesin as nama_mesin_masuk,
            m_masuk.lokasi as lokasi_masuk, 
            m_pulang.nama_mesin as nama_mesin_pulang,
            m_pulang.lokasi as lokasi_pulang,
            (CASE WHEN ab.masuk IS NULL THEN '-' WHEN ab.pulang IS NULL THEN '-' ELSE j.nama_jadwal END) AS jadwal_nama,
            j.jadwal_masuk,
            j.jadwal_pulang,
            u.kode_unit,
            u.nama_unit,
            su.kode_sub_unit,
            su.nama_sub_unit
          FROM absen_pegawai ab
          LEFT JOIN pegawai p ON ab.absen_id = p.absen_pegawai_id
          LEFT JOIN unit u ON u.id = p.unit_id
          LEFT JOIN sub_unit su ON su.id = p.sub_unit_id
          LEFT JOIN mesin_absen m_masuk ON m_masuk.ipadress = ab.mesin_masuk
          LEFT JOIN mesin_absen m_pulang ON m_pulang.ipadress = ab.mesin_pulang
          LEFT JOIN jadwal j ON (
              ab.masuk::time >= (j.jadwal_masuk - interval '1 minute') 
              AND ab.masuk::time <= (j.jadwal_masuk + interval '10 minute')
              AND ab.pulang::time >= (j.jadwal_pulang - interval '1 minute')
              AND ab.pulang::time <= (j.jadwal_pulang + interval '30 minute')
          )
          ORDER BY ab.tanggal_absen, p.nama_lengkap, ab.absen_id ASC
        ) t
        WHERE 1=1
      ";

      /*LEFT JOIN jadwal j ON (
              ab.masuk::time >= (j.jadwal_masuk - interval '10 minute') 
              AND ab.masuk::time <= (j.jadwal_masuk + interval '10 minute')
              AND ab.pulang::time >= (j.jadwal_pulang - interval '10 minute')
              AND ab.pulang::time <= (j.jadwal_pulang + interval '30 minute')
          )
              LEFT JOIN jadwal j ON j.unit_id = u.id */

      if (!is_null($filter)) $query .= $filter;
      return $query;
  }

  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
      if(isset($params['absen_id'])){
        
        $orderField = 'tanggal_absen';

        $this->db->select('absen_pegawai.absen_id,
                          absen_pegawai.tanggal_absen, 
                          CASE WHEN absen_pegawai.masuk IS NULL THEN \'-\' ELSE TO_CHAR(absen_pegawai.masuk, \'HH24:MI:SS\') END AS jam_masuk,
                          CASE WHEN absen_pegawai.verifikasi_masuk = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_masuk = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_m, 
                          CASE WHEN absen_pegawai.mesin_masuk IS NULL THEN \'-\' ELSE absen_pegawai.mesin_masuk END AS mesin_m,
                          CASE WHEN absen_pegawai.pulang IS NULL THEN \'-\' WHEN TO_CHAR(absen_pegawai.masuk, \'YYYY-MM-DD\') != TO_CHAR(absen_pegawai.pulang, \'YYYY-MM-DD\') THEN TO_CHAR(absen_pegawai.pulang, \'HH24:MI:SS DD-MM-YYYY\') ELSE TO_CHAR(absen_pegawai.pulang, \'HH24:MI:SS\') END AS jam_pulang,
                          CASE WHEN absen_pegawai.verifikasi_pulang = 1 THEN \'Finger\' WHEN absen_pegawai.verifikasi_pulang = 0 THEN \'Input\' ELSE \'-\' END AS verifikasi_p,
                          CASE WHEN absen_pegawai.mesin_pulang IS NULL THEN \'-\' ELSE absen_pegawai.mesin_pulang END AS mesin_p,
                          CASE WHEN absen_pegawai.pulang - absen_pegawai.masuk IS NULL THEN \'-\' ELSE (EXTRACT(EPOCH FROM (absen_pegawai.pulang - absen_pegawai.masuk)) / 3600)::text END AS jam_kerja,
                          CASE WHEN TO_CHAR(absen_pegawai.masuk, \'YYYY-mm-dd\') != TO_CHAR(absen_pegawai.pulang, \'YYYY-mm-dd\') THEN \'Shift Malam\' ELSE \'-\' END AS jenis_shift,
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
  
  public function getMonth()
  {
    $data = array(
        array('id' => '01', 'text' => 'Januari'),
        array('id' => '02', 'text' => 'Februari'),
        array('id' => '03', 'text' => 'Maret'),
        array('id' => '04', 'text' => 'April'),
        array('id' => '05', 'text' => 'Mei'),
        array('id' => '06', 'text' => 'Juni'),
        array('id' => '07', 'text' => 'Juli'),
        array('id' => '08', 'text' => 'Agustus'),
        array('id' => '09', 'text' => 'September'),
        array('id' => '10', 'text' => 'Oktober'),
        array('id' => '11', 'text' => 'November'),
        array('id' => '12', 'text' => 'Desember'),
    );
    return $data;
  }

  public function getYear()
  {  
    $startYear = 2023;
    $currentYear = date('Y');

    $years = array();

    for ($year = $startYear; $year < $currentYear; $year++) {
        $years[] = array('id' => $year, 'text' => $year);
    }

    return $years;
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

    usort($filldata, function($a, $b) {
        return strtotime($a['DateTime']) - strtotime($b['DateTime']);
    });

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

              $dateObj = new DateTime($dateTime);
              $date = $dateObj->format('Y-m-d');
              $time = $dateObj->format('H:i:s');

              $yesterdayObj = new DateTime($dateTime);
              $yesterdayObj->modify('-1 day');
              $yesterday = $yesterdayObj->format('Y-m-d');

              $data = [
                'absen_id' => $userID,
                'tanggal_absen' => $date
              ];

              
              $this->db->where('absen_id', $userID);
              $this->db->where('tanggal_absen', $date);
              $count = $this->db->count_all_results($this->_table);

                switch ($status) {
                    case "0":
                        if ($count == 0) {
                            $data['masuk'] = $dateTime;
                            $data['verifikasi_masuk'] = $verified;
                            $data['mesin_masuk'] = $machine;
                            if (!$this->db->insert($arrayDB['table'], $data)) {
                                $failedInsertions[] = [
                                    'absen_id' => $userID,
                                    'dateTime' => $date,
                                    'error' => $this->db->error()['message']
                                ];
                            }
                        }else{
                            $query = $this->db->select('*')
                                    ->from($arrayDB['table'])
                                    ->where('absen_id', $userID)
                                    ->where('tanggal_absen', $date)
                                    ->get()
                                    ->row();
                            $exists_masuk = $query->masuk; 
                            $exists_pulang = $query->pulang;

                            if(!empty($exists_masuk)){
                                if($exists_masuk > $dateTime){
                                    $this->db->where('absen_id', $userID);
                                    $this->db->where('tanggal_absen', $date);
                                    if (!$this->db->update($arrayDB['table'], [
                                        'masuk' => $dateTime,
                                        'verifikasi_masuk' => $verified,
                                        'mesin_masuk' => $machine
                                    ])) {
                                        $failedInsertions[] = [
                                            'absen_id' => $userID,
                                            'dateTime' => $date,
                                            'error' => $this->db->error()['message']
                                        ];
                                    }
                                }else{
                                    $this->db->where('absen_id', $userID);
                                    $this->db->where('tanggal_absen', $date);
                                    $this->db->where('masuk', $dateTime);
                                    $existingRecord = $this->db->get($arrayDB['table'])->row();
                                    if (empty($existingRecord)) {
                                        //if(!empty($exists_pulang) && $exists_pulang < $dateTime){
                                            if (!$this->db->insert($arrayDB['table'], [
                                                'absen_id' => $userID,
                                                'tanggal_absen' => $date,
                                                'masuk' => $dateTime,
                                                'verifikasi_masuk' => $verified,
                                                'mesin_masuk' => $machine
                                            ])) {
                                                $failedInsertions[] = [
                                                    'absen_id' => $userID,
                                                    'dateTime' => $date,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }
                                        //}
                                    }
                                }
                            }else{
                                if(!empty($exists_pulang)){
                                    if($exists_pulang < $dateTime){
                                        $verifikasi_pulang = $query->verifikasi_pulang;
                                        $mesin_pulang = $query->mesin_pulang;

                                        $this->db->where('absen_id', $userID);
                                        $this->db->where('tanggal_absen', $yesterday);
                                        $this->db->where('pulang IS NULL');
                                        $pulangNull = $this->db->get($arrayDB['table'])->row();
                                        if(!empty($pulangNull)){
                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $yesterday);
                                            if (!$this->db->update($arrayDB['table'], [
                                                'pulang' => $exists_pulang,
                                                'verifikasi_pulang' => $verifikasi_pulang,
                                                'mesin_pulang' => $machine
                                            ])) {
                                                $failedInsertions[] = [
                                                    'absen_id' => $userID,
                                                    'dateTime' => $date,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }
        
                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $date);
                                            if (!$this->db->update($arrayDB['table'], [
                                                'masuk' => $dateTime,
                                                'verifikasi_masuk' => $verified,
                                                'mesin_masuk' => $machine,
                                                'pulang' => null,
                                                'verifikasi_pulang' => null,
                                                'mesin_pulang' => null
                                            ])) {
                                                $failedInsertions[] = [
                                                    'absen_id' => $userID,
                                                    'dateTime' => $date,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }
                                        }else{
                                            if (!$this->db->insert($arrayDB['table'], [
                                                'absen_id' => $userID,
                                                'tanggal_absen' => $date,
                                                'masuk' => $dateTime,
                                                'verifikasi_masuk' => $verified,
                                                'mesin_masuk' => $machine
                                            ])) {
                                                $failedInsertions[] = [
                                                    'absen_id' => $userID,
                                                    'dateTime' => $date,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }
                                        }
                                    }else{
                                        $this->db->where('absen_id', $userID);
                                        $this->db->where('tanggal_absen', $date);
                                        if (!$this->db->update($arrayDB['table'], [
                                            'masuk' => $dateTime,
                                            'verifikasi_masuk' => $verified,
                                            'mesin_masuk' => $machine
                                        ])) {
                                            $failedInsertions[] = [
                                                'absen_id' => $userID,
                                                'dateTime' => $date,
                                                'error' => $this->db->error()['message']
                                            ];
                                        }
                                    }
                                }else{
                                    $this->db->where('absen_id', $userID);
                                    $this->db->where('tanggal_absen', $date);
                                    if (!$this->db->update($arrayDB['table'], [
                                        'masuk' => $dateTime,
                                        'verifikasi_masuk' => $verified,
                                        'mesin_masuk' => $machine
                                    ])) {
                                        $failedInsertions[] = [
                                            'absen_id' => $userID,
                                            'dateTime' => $date,
                                            'error' => $this->db->error()['message']
                                        ];
                                    }
                                }
                            }
                        }
                    break;
                        
                    case "1":
                        if ($count == 0) {
                            $this->db->where('absen_id', $userID);
                            $this->db->where('tanggal_absen', $yesterday);
                            $this->db->where('pulang IS NULL');
                            $pulangNull = $this->db->get($arrayDB['table'])->row();
                            if(empty($pulangNull)){
                                $this->db->where('absen_id', $userID);
                                $this->db->where('tanggal_absen', $yesterday);
                                $this->db->where('pulang', $dateTime);
                                $existingRecord = $this->db->get($arrayDB['table'])->row();
                                if (empty($existingRecord)) {
                                    $data['pulang'] = $dateTime;
                                    $data['verifikasi_pulang'] = $verified;
                                    $data['mesin_pulang'] = $machine;
                                    if (!$this->db->insert($arrayDB['table'], $data)) {
                                        $failedInsertions[] = [
                                            'absen_id' => $userID,
                                            'dateTime' => $date,
                                            'error' => $this->db->error()['message']
                                        ];
                                    }
                                }
                            }else{
                                $masukDate = $pulangNull->masuk;
                                $dateTimeUnix = strtotime($dateTime);
                                $existsMasukUnix = strtotime($masukDate);
                                $check = ($dateTimeUnix - $existsMasukUnix) / 3600;
                                if($check < 18){
                                    $this->db->where('absen_id', $userID);
                                    $this->db->where('tanggal_absen', $yesterday);
                                    $this->db->where('pulang IS NULL');
                                    if (!$this->db->update($arrayDB['table'], [
                                        'pulang' => $dateTime,
                                        'verifikasi_pulang' => $verified,
                                        'mesin_pulang' => $machine
                                    ])) {
                                        $failedInsertions[] = [
                                            'absen_id' => $userID,
                                            'dateTime' => $date,
                                            'error' => $this->db->error()['message']
                                        ];
                                    }
                                }else{
                                    $data['pulang'] = $dateTime;
                                    $data['verifikasi_pulang'] = $verified;
                                    $data['mesin_pulang'] = $machine;
                                    if (!$this->db->insert($arrayDB['table'], $data)) {
                                        $failedInsertions[] = [
                                            'absen_id' => $userID,
                                            'dateTime' => $date,
                                            'error' => $this->db->error()['message']
                                        ];
                                    }
                                }
                            }
                        }else{
                            $query = $this->db->select('*')
                                    ->from($arrayDB['table'])
                                    ->where('absen_id', $userID)
                                    ->where('tanggal_absen', $date)
                                    ->get()
                                    ->row();
                            $exists_masuk = $query->masuk; 
                            $exists_pulang = $query->pulang;
                            
                            if(!empty($exists_pulang)){

                                if($exists_pulang < $dateTime){
                                    $this->db->where('absen_id', $userID);
                                    $this->db->where('tanggal_absen', $date);
                                    $this->db->where('pulang IS NULL');
                                    if (!$this->db->update($arrayDB['table'], [
                                        'pulang' => $dateTime,
                                        'verifikasi_pulang' => $verified,
                                        'mesin_pulang' => $machine
                                    ])) {
                                        $failedInsertions[] = [
                                            'absen_id' => $userID,
                                            'dateTime' => $date,
                                            'error' => $this->db->error()['message']
                                        ];
                                    }
                                }else{
                                    if(empty($exists_masuk)){
                                        $this->db->where('absen_id', $userID);
                                        $this->db->where('tanggal_absen', $yesterday);
                                        $this->db->where('pulang', $dateTime);
                                        $yesterdayExistingRecord = $this->db->get($arrayDB['table'])->row();
                                        if(empty($yesterdayExistingRecord)){
                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $date);
                                            $this->db->where('pulang', $dateTime);
                                            $existingRecord = $this->db->get($arrayDB['table'])->row();
                                            if (empty($existingRecord)) {
                                                $data['pulang'] = $dateTime;
                                                $data['verifikasi_pulang'] = $verified;
                                                $data['mesin_pulang'] = $machine;
                                                if (!$this->db->insert($arrayDB['table'], $data)) {
                                                    $failedInsertions[] = [
                                                        'absen_id' => $userID,
                                                        'dateTime' => $date,
                                                        'error' => $this->db->error()['message']
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                }

                            }else{
                                if(!empty($exists_masuk)){
                                    if($exists_masuk < $dateTime){
                                        $dateTimeUnix = strtotime($dateTime);
                                        $existsMasukUnix = strtotime($exists_masuk);
                                        $check = ($dateTimeUnix - $existsMasukUnix) / 3600;
                                        if($check > 1){
                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $date);
                                            if (!$this->db->update($arrayDB['table'], [
                                                'pulang' => $dateTime,
                                                'verifikasi_pulang' => $verified,
                                                'mesin_pulang' => $machine
                                            ])) {
                                                $failedInsertions[] = [
                                                    'absen_id' => $userID,
                                                    'dateTime' => $date,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }
                                        }else{
                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $yesterday);
                                            $this->db->where('pulang IS NULL');
                                            if (!$this->db->update($arrayDB['table'], [
                                                'pulang' => $dateTime,
                                                'verifikasi_pulang' => $verified,
                                                'mesin_pulang' => $machine
                                            ])) {
                                                $failedInsertions[] = [
                                                    'absen_id' => $userID,
                                                    'dateTime' => $date,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }
                                        }
                                    }else{
                                        if(empty($exists_pulang)){
                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $yesterday);
                                            $this->db->where('pulang IS NULL');
                                            if (!$this->db->update($arrayDB['table'], [
                                                'pulang' => $dateTime,
                                                'verifikasi_pulang' => $verified,
                                                'mesin_pulang' => $machine
                                            ])) {
                                                $failedInsertions[] = [
                                                    'absen_id' => $userID,
                                                    'dateTime' => $date,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }
                                        }else{
                                            if (!$this->db->insert($arrayDB['table'], [
                                                'absen_id' => $userID,
                                                'tanggal_absen' => $date,
                                                'pulang' => $dateTime,
                                                'verifikasi_pulang' => $verified,
                                                'mesin_pulang' => $machine
                                            ])) {
                                                $failedInsertions[] = [
                                                    'absen_id' => $userID,
                                                    'dateTime' => $date,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    break;
                }
            }

            $this->db->trans_complete();
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $failedInsertions[] = [
                'absen_id' => isset($userID) ? $userID : 'N/A',
                'dateTime' => isset($date) ? $date : 'N/A',
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
