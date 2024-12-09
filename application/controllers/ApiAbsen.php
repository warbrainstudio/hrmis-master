<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiAbsen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('parse');
        $this->load->model('MesinAbsenModel');
    }

    public function index()
    {
        
        $api = 'Welcome to Simabsen API. to get input name and data finger machine, go to api/getdata. For fetch and import data into your database, use api/fetchdata. You need token to fetch data, ask developer for token';
        
        $input_list = [
            'token'     => 'Input for the token of this API',
            'host'      => 'Input for your host',
            'port'      => 'Input for your port',
            'database'  => 'Input for your database',
            'username'  => 'Input for your database username',
            'password'  => 'Input for your database password',
            'table'     => 'Input for the table to store data',
            'ip'        => 'Input for the IP of the fingerprint machine. If you let empty this input, this API will automatically use all IP from \'list_mesin\' ',
            'key'       => 'Input for the Comm key of the fingerprint machine',
            'alldata'   => 'Input for choosing whether you want to fetch all data or not. The value is boolean true or false.',
            'start_date'=> 'Input date to fetch data based on the start date you choose',
            'end_date'  => 'Input date to fetch data based on the end date you choose' ,
            'Note:' => 'You don\'t have to choose a date, but the default will be to fetch yesterday\'s data.'
        ];
        

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($api));

        return $input_list;
        
    }

    //check connection fingerprint machine
    public function ping($ip) 
    {
        $reply = 1;
        $ping = exec("ping -n $reply $ip", $output, $status);
        return $status === 0;
    }

    //get data fingerprint machine
    public function getData() 
    {

            $this->load->database();
            $query = $this->db->get('mesin_absen');
            $mesins = $query->result();
            $list_input = $this->index('input_list');
            $list_mesin = [];
        
            foreach ($mesins as $mesin) {
                $pingResult = $this->ping($mesin->ipadress);

                if ($pingResult) {
                    $status = "Connect";
                } else {
                    $status = "Disconnect";
                }

                $list_mesin[] = [
                    'ip' => $mesin->ipadress,
                    'commkey' => $mesin->commkey
                ];
            }

            $data_api = [
                'list_input' => $list_input,
                'list_mesin' => $list_mesin,
            ];

            $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($data_api));

             return $list_mesin;
    }

    //fetching data from fingerprint machine by using cron
    public function fetch_data_cron()
    {
        $query = $this->db->get('mesin_absen');
        $mesins = $query->result();
        foreach ($mesins as $mesin) {
            $IP = $mesin->ipadress;
            $key = $mesin->commkey;
            $yesterday = date('Y-m-d', strtotime('-1 day'));

            if($this->checkIPMachine($IP)){
                $data = $this->fetchDataFromMachine($IP, $key, $yesterday, $yesterday);

                if (!is_array($data)) {
                    $data = [];
                }
                $filldata = $data;
                if(empty($filldata)){
                    $response = array(
                        'status' => false,
                        'message' => "Data kosong atau salah"
                    );
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response));
                }else{
                    $dataCount['dataCount'] = count($filldata);
                    $arrayDB = array(
                        'table' => 'absen_pegawai',
                    );
                    $this->import_Data($filldata, $arrayDB);
                }
            }else{
                $response = array(
                    'status' => false,
                    'message' => "Gagal gunakan API. Cek IP Address atau Mesin Finger"
                );
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            }  
        }
    }
    
    //authorization procedure before retrieving and storing data from the fingerprint machine in a database
    public function fetchData() 
    {
        
        $token = 'XVd17lwEgOHcvKgjJWGWbuufQdte7WhiPLerllmSWcvr8jKLz6vqqkQkl4DIQzvbOUAtsxvl1TDviMlS3bQEewLszTxxGeAuv8XS';
        $getToken = $this->input->get('token');
        $host = $this->input->get('host');
        $port = $this->input->get('port');
        $user = $this->input->get('username');
        $pwd = $this->input->get('password');
        $dbs = $this->input->get('database');
        $table = $this->input->get('table');
        $IP = $this->input->get('ip');
        $Key = $this->input->get('key');
        $isAll = $this->input->get('alldata') === 'true';
        $startDate = $this->input->get('start_date');
        $endDate = $this->input->get('end_date');

        if($getToken!==$token){

            $response = array(
                'status' => false,
                'message' => "Gagal gunakan API. Token kosong atau salah"
            );
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));

        }else{
            
            $arrayInput = array(
                'host' => $host,
                'port' => $port,
                'user' => $user,
                'password' => $pwd,
                'database' => $dbs,
                'table' => $table,
                'ip' => $IP,
                'key' => $Key,
                'start_date' => $startDate,
                'end_date' => $endDate,
            );

            if(!empty($IP)){

                $this->fetchingData($arrayInput);

            }else{

                $query = $this->db->get('mesin_absen');
                $mesins = $query->result();

                foreach ($mesins as $mesin) {
                    $arrayInput['ip'] = $mesin->ipadress;
                    $arrayInput['key'] = $mesin->commkey;
                    $this->fetchingData($arrayInput);  
                }

            }

        }

    }

    //first step fetching data based on date and machine
    public function fetchingData($arrayInput)
    {
        $IP = $arrayInput['ip'];
        $firstDate = date('2023-07-03');
        $currentDate = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $startDate = $arrayInput['start_date'];
        $endDate = $arrayInput['end_date'];

        if($this->checkIPMachine($IP)){
            if (empty($startDate) || empty($endDate)) {
                if($isAll){
                    $startDate = $firstDate;
                    $endDate = $currentDate;
                    $data = $this->fetchDataFromMachine($IP, $arrayInput['Key'], $startDate, $endDate);
                }else{
                    $startDate = $yesterday;
                    $endDate = $yesterday;
                    $data = $this->fetchDataFromMachine($IP, $arrayInput['key'], $startDate, $endDate);
                }
            }else{
                $data = $this->fetchDataFromMachine($IP, $arrayInput['key'], $startDate, $endDate);
            }

            if (!is_array($data)) {
                $data = [];
            }
            $filldata = $data;
            if(empty($filldata)){
                $response = array(
                    'status' => false,
                    'message' => "Data kosong atau salah"
                );
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            }else{
                $dataCount['dataCount'] = count($filldata);
                $arrayDB = array(
                    'host' => $arrayInput['host'],
                    'port' => $arrayInput['port'],
                    'user' => $arrayInput['user'],
                    'password' => $arrayInput['password'],
                    'database' => $arrayInput['database'],
                    'table' => $arrayInput['table'],
                    'ip' => $IP,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                );
                $this->import_Data($filldata, $arrayDB);
            }
            //$this->checkConnectionDB($filldata, $arrayDB);
        }else{
            $response = array(
                'status' => false,
                'message' => "Gagal gunakan API. Cek IP Address atau Mesin Finger"
            );
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    public function checkIPMachine($IP) 
    { 
        $timeout = 200;
        $Connect = @fsockopen($IP, 80, $errno, $errstr, $timeout);
        
        return $Connect !== false;
    }

    //SOAP API for fetching data from fingerprint machine
    public function fetchDataFromMachine($IP, $Key, $startDate, $endDate) 
    {
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
                return ["error" => "The server encountered an error while processing the request."];
            }
    
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
                            'Machine' => $IP
                        ];
                    }
                }
            }                        
        } else {
            return ["error" => "Connection failed: $errstr ($errno)"];
        }
        return $filteredData;        
    }
    
    //process filtering data before insert into database
    public function import_Data($filldata, $arrayDB) 
    {

        $dataCount = count($filldata);

        usort($filldata, function($a, $b) {
            return strtotime($a['DateTime']) - strtotime($b['DateTime']);
        });
    
        if ($dataCount > 0) {
            $failedInsertions = [];
    
            $this->db->trans_start();

            try {

                foreach ($filldata as $row) {
                
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
                    $count = $this->db->count_all_results($arrayDB['table']);

                    //switch case based on $status value. $status have 2 value, 0 and 1. 0 for 'masuk', 1 for 'pulang
                    switch ($status) {
                        
                        //filtering data for $status with value 0
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

                                //checking if data already exists
                                $query = $this->db->select('*')
                                        ->from($arrayDB['table'])
                                        ->where('absen_id', $userID)
                                        ->where('tanggal_absen', $date)
                                        ->get()
                                        ->row();
                                $exists_masuk = $query->masuk; 
                                $exists_pulang = $query->pulang;

                                //if 'masuk' already exists, code will replace old data 'masuk' or insert new data
                                if(!empty($exists_masuk)){

                                    //if $exists_masuk is more than $dateTime, then update data masuk
                                    if($exists_masuk > $dateTime){
                                        $this->db->where('absen_id', $userID);
                                        $this->db->where('tanggal_absen', $date);
                                        $this->db->where('masuk', $exists_masuk);
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
                                    
                                    //if less, then insert new data
                                    }else{

                                        //check data 'masuk'
                                        $this->db->where('absen_id', $userID);
                                        $this->db->where('tanggal_absen', $date);
                                        $this->db->where('masuk', $dateTime);
                                        $existingRecord = $this->db->get($arrayDB['table'])->row();

                                        //if the result is empty, then insert new data. but if not, don't insert new data
                                        if (empty($existingRecord)) {

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

                                    }

                                //if 'masuk' not exists
                                }else{

                                    //if 'masuk' not exists but there is data 'pulang'
                                    if(!empty($exists_pulang)){

                                        //if '$exists_pulang' is less than '$dateTime', insert new data because 'dateTime' cannot to be more than '$exists_pulang'
                                        if($exists_pulang < $dateTime){

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
                                            
                                        //if not less, then update data. to be more spesific, only data who don't have 'masuk'
                                        }else{

                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $date);
                                            $this->db->where('masuk IS NULL');
                                            $this->db->where('pulang', $exists_pulang);
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
                        
                         //filtering data for $status with value 1
                        case "1":
                            //if there is no data, then insert new data. but before insert, check if there is already data from yesterday
                            if ($count == 0) {

                                $this->db->where('absen_id', $userID);
                                $this->db->where('tanggal_absen', $yesterday);
                                $this->db->where('pulang IS NULL');
                                $this->db->order_by('masuk DESC');
                                $pulangNull = $this->db->get($arrayDB['table'])->row();

                                //if the value $pulangNull is empty, then insert new data
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

                                //but if value $pulangNull is not empty, then get yesterday's data if yesterday's data don't have 'pulang'
                                }else{

                                    $masukDate = $pulangNull->masuk;
                                    $verifikasiMasuk = $pulangNull->verifikasi_masuk;
                                    $mesinMasuk = $pulangNull->mesin_masuk;
                                    $dateTimeUnix = strtotime($dateTime);
                                    $existsMasukUnix = strtotime($masukDate);
                                    $check = ($dateTimeUnix - $existsMasukUnix) / 3600;

                                    //check data hour. if the result less than 18, data pulang is match and can be use, but if not. insert new data
                                    if($check < 18){

                                        $data['masuk'] = $masukDate;
                                        $data['verifikasi_masuk'] = $verifikasiMasuk;
                                        $data['mesin_masuk'] = $mesinMasuk;
                                        $data['pulang'] = $dateTime;
                                        $data['verifikasi_pulang'] = $verified;
                                        $data['mesin_pulang'] = $machine;

                                        if ($this->db->insert($arrayDB['table'], $data)) {

                                            //deleting data from yesterday who already use
                                            /*$this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $yesterday);
                                            $this->db->where('masuk', $masukDate);
                                            $this->db->where('pulang IS NULL');

                                            if (!$this->db->delete($arrayDB['table'])) {
                                                $failedDeletions[] = [
                                                    'absen_id' => $userID,
                                                    'tanggal_absen' => $yesterday,
                                                    'error' => $this->db->error()['message']
                                                ];
                                            }*/
                                            
                                        } else {

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
                                    
                                    //if $exists_pulang value is less than $dateTime, that mean $dateTime value is bug and can't use to update data
                                    if($exists_pulang < $dateTime){

                                        if(empty($exists_masuk)){

                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $yesterday);
                                            $this->db->where('pulang', $dateTime);
                                            $yesterdayExistingRecord = $this->db->get($arrayDB['table'])->row();

                                            //check if data is exists in yesterday data
                                            if(empty($yesterdayExistingRecord)){

                                                $this->db->where('absen_id', $userID);
                                                $this->db->where('tanggal_absen', $date);
                                                $this->db->where('pulang', $dateTime);
                                                $existingRecord = $this->db->get($arrayDB['table'])->row();
                                                
                                                //check if there is already same data
                                                if (empty($existingRecord)) {

                                                    $this->db->where('absen_id', $userID);
                                                    $this->db->where('tanggal_absen', $date);
                                                    $this->db->where('masuk IS NOT NULL');
                                                    $this->db->where('pulang IS NULL');
                                                    $checkMistake = $this->db->get($arrayDB['table'])->row();

                                                    if(empty($checkMistake)){

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

                                                    }else{

                                                        $masukMistake = $checkMistake->masuk;

                                                        if($masukMistake < $dateTime){
                                                            $this->db->where('absen_id', $userID);
                                                            $this->db->where('tanggal_absen', $date);
                                                            $this->db->where('masuk', $exists_masuk);
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

                                                }

                                            }

                                        }else{

                                            $this->db->where('absen_id', $userID);
                                            $this->db->where('tanggal_absen', $date);
                                            $this->db->where('masuk', $exists_masuk);
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

                                    }
    
                                }else{

                                    if(!empty($exists_masuk)){

                                        //check if $exists_masuk is less than $dateTime
                                        if($exists_masuk < $dateTime){

                                            $dateTimeUnix = strtotime($dateTime);
                                            $existsMasukUnix = strtotime($exists_masuk);
                                            $check = ($dateTimeUnix - $existsMasukUnix) / 3600;
                                            
                                            //count hour. if $check value is more than 1 hour, then update data
                                            if($check > 1){

                                                $this->db->where('absen_id', $userID);
                                                $this->db->where('tanggal_absen', $date);
                                                $this->db->where('masuk', $exists_masuk);
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
                                            
                                            //but if less than 1 hour, code will check if there a data from yesterday who don't have 'pulang'
                                            }else{

                                                $this->db->where('absen_id', $userID);
                                                $this->db->where('tanggal_absen', $yesterday);
                                                $this->db->where('masuk', $exists_masuk);
                                                $this->db->where('pulang IS NULL');
                                                $pulangNull = $this->db->get($arrayDB['table'])->row();

                                                //if the result is empty, then insert new data
                                                if(!empty($pulangNull)){
                                                    $masukDate = $pulangNull->masuk;
                                                    $verifikasiMasuk = $pulangNull->verifikasi_masuk;
                                                    $mesinMasuk = $pulangNull->mesin_masuk;

                                                    if (!$this->db->insert($arrayDB['table'], [
                                                        'absen_id' => $userID,
                                                        'tanggal_absen' => $date,
                                                        'masuk' => $masukDate,
                                                        'verifikasi_masuk' => $verifikasiMasuk,
                                                        'mesin_masuk' => $mesinMasuk,
                                                        'pulang' => $dateTime,
                                                        'verifikasi_pulang' => $verified,
                                                        'mesin_pulang' => $machine
                                                    ])) {
                                                        $failedInsertions[] = [
                                                            'absen_id' => $userID,
                                                            'dateTime' => $date,
                                                            'error' => $this->db->error()['message']
                                                        ];

                                                    } else {
                                                        
                                                        //deleting data from yesterday who already use
                                                        /*$this->db->where('absen_id', $userID);
                                                        $this->db->where('tanggal_absen', $yesterday);
                                                        $this->db->where('masuk', $masukDate);
                                                        $this->db->where('pulang IS NULL');

                                                        if (!$this->db->delete($arrayDB['table'])) {
                                                            $failedDeletions[] = [
                                                                'absen_id' => $userID,
                                                                'tanggal_absen' => $yesterday,
                                                                'error' => $this->db->error()['message']
                                                            ];
                                                        }*/

                                                    }
                                                    
                                                }

                                            }

                                        }else{

                                            //check if $exists_pulang is empty, then update the data from yesterday, but if not, insert new data
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

                    $this->db->where('absen_id', $userID);
                    $this->db->where('tanggal_absen', $dateTime);
                    $count_raw = $this->db->count_all_results('absen_pegawai_raw');

                    if ($count_raw == 0) {
                        $data_raw = [
                            'absen_id' => $userID,
                            'tanggal_absen' => $dateTime,
                            'verified' => $verified,
                            'status' => $status,
                            'ipmesin' => $machine
                        ];
                        if (!$this->db->insert('absen_pegawai_raw', $data_raw)) {
                            $failedInsertions[] = [
                                'absen_id' => $userID,
                                'dateTime' => $dateTime,
                                'error' => $this->db->error()['message']
                            ];
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
                'status' => true,
                'data' => [
                    'arrayDB' => $arrayDB
                ]
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

    //check connection database from different server
    public function checkConnectionDB($filldata, $arrayDB) {
        $db_config = array(
            'dsn'      => '',
            'hostname' => $arrayDB['host'],
            'port'     => $arrayDB['port'],
            'username' => $arrayDB['user'],
            'password' => $arrayDB['password'],
            'database' => $arrayDB['database'],
            'dbdriver' => 'postgre',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'strict_on' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );

        try {
            $this->db = $this->load->database($db_config, TRUE);
            if ($this->db->conn_id) {
                $data = $filldata;
                $dbarray = $arrayDB;
                $this->import_Data($data, $arrayDB);
            } else {
                $error = $this->db->error();
                $response = array(
                    'status' => false,
                    'message' => "Gagal gunakan API. Cek koneksi Database: " . $error['message']
                );
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            }
        } catch (Exception $e) {
            $response = array(
                'status' => false,
                'message' => "Gagal gunakan API. Cek koneksi Database: " . $e->getMessage()
            );
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }
    
}
