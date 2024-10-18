<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(FCPATH . 'vendor/autoload.php');

use MatthiasMullie\Minify;
use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use RioAstamal\AngkaTerbilang\Terbilang;

class AppBackend extends MX_Controller
{
    private $_charName = 'HRMIS | PT. KAH';
    private $_permittedChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $_specialRoute = array('login', 'form');
    public $_pageTitle;

    function __construct()
    {
        parent::__construct();

        $this->handle_access();
        $this->load->model(array(
            'SettingModel',
            'MenuModel',
            'UserModel',
            'NotificationModel',
        ));
        $this->load->library('form_validation');
        $this->template->set_template($this->app()->template_backend);
        $this->_pageTitle = $this->get_page_title();
    }

    public function app()
    {
        $agent = new Mobile_Detect;
        $appData = $this->SettingModel->getAll();
        $config = array();

        if (count($appData) > 0) {
            foreach ($appData as $index => $item) {
                $config[$item->data] = $item->content;
            };
        };

        $config['is_mobile'] = $agent->isMobile();

        return (object) $config;
    }

    public function get_page_title()
    {
        $class = $this->router->fetch_class();
        $menu = $this->MenuModel->getDetail('link', $class);
        return (!is_null($menu)) ? $menu->name : 'Undefined';
    }

    public function load_main_js($moduleName, $isSpecificPath = false, $variables = null)
    {
        if (!is_null($variables)) {
            extract($variables, EXTR_SKIP);
        };

        ob_start();

        if ($isSpecificPath === true) {
            @include FCPATH . '/application/modules/' . $moduleName;
        } else {
            @include FCPATH . '/application/modules/' . $moduleName . '/views/main.js.php';
        };

        $sourcePath = ob_get_clean();
        $minifier = new Minify\JS($sourcePath);

        return $minifier->minify();
        ob_end_clean();
    }

    public function handle_access()
    {
        $session = $this->session->userdata('user');
        $isLogin = (!is_null($session) && $session['is_login'] === true) ? true : false;

        if ($isLogin === false) {
            if (!in_array($this->router->fetch_class(), $this->_specialRoute)) {
                if ($this->input->is_ajax_request()) {
                    echo '
                        <script type="text/javascript">
                            swal({
                                title: "Session Expired",
                                text: "Silahkan masuk kembali dengan Tab Baru untuk tidak meninggalkan aktivitas dihalaman ini, atau masuk di Tab Ini untuk memulai dari awal.",
                                type: "error",
                                showCancelButton: true,
                                confirmButtonColor: "#32c787",
                                cancelButtonColor: "#DD6B55",
                                confirmButtonText: "Tab Ini",
                                cancelButtonText: "Tab Baru",
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.value === true) {
                                    window.location = "' . base_url('login') . '";
                                } else if (result.dismiss === "cancel") {
                                    window.open("' . base_url('login') . '", "_blank");
                                };
                            });
                        </script>
                    ';
                    exit;
                } else {
                    redirect(base_url('login'), 'location', 301);
                };
            };
        } else {
            if (in_array($this->router->fetch_class(), $this->_specialRoute)) {
                redirect(base_url(), 'location', 301);
            } else {
                // Get 2 level segment
                // $uriSegment = @$this->uri->segment(1) . '/' . @$this->uri->segment(2);
                // $uriSegment = @trim($uriSegment, '/');

                // Collect routes
                // $menuList = $this->MenuModel->getAllByRole($session['role'], array('link_tobase' => '1'));
                // Static routes
                // array_push($menuList, (object) array('link' => 'notification'));

                // $this->dd($uriSegment, false);

                // ToDo: Add hak akses manajemen
                // $accessExist = $this->searchInArrayObj($menuList, 'link', $uriSegment);
                // if (count($accessExist) === 0 && !empty($uriSegment) && !$this->input->is_ajax_request()) {
                //     show_error('You don\'t have access to this page.', 403, 'Access Denied');
                // };
            };
        };
    }

    public function handle_ajax_request($msg = 'Anda tidak diizinkan mengakses URL ini melalui tab baru')
    {
        if (!$this->input->is_ajax_request()) {
            show_error($msg, 501, 'Forbidden');
            exit;
        };
    }

    public function get_month($bulan)
    {
        switch ($bulan) {
            case '01':
                return 'Januari';
                break;
            case '02':
                return 'Februari';
                break;
            case '03':
                return 'Maret';
                break;
            case '04':
                return 'April';
                break;
            case '05':
                return 'Mei';
                break;
            case '06':
                return 'Juni';
                break;
            case '07':
                return 'Juli';
                break;
            case '08':
                return 'Agustus';
                break;
            case '09':
                return 'September';
                break;
            case '10':
                return 'Oktober';
                break;
            case '11':
                return 'November';
                break;
            case '12':
                return 'Desember';
                break;
            default:
                return 'Undefined';
                break;
        };
    }

    function get_day($hari)
    {
        switch ($hari) {
            case 'Sun':
                return 'Minggu';
                break;
            case 'Mon':
                return 'Senin';
                break;
            case 'Tue':
                return 'Selasa';
                break;
            case 'Wed':
                return 'Rabu';
                break;
            case 'Thu':
                return 'Kamis';
                break;
            case 'Fri':
                return 'Jumat';
                break;
            case 'Sat':
                return 'Sabtu';
                break;
            default:
                return 'Undefined';
                break;
        };
    }

    function get_day_by_num($hari)
    {
        switch ($hari) {
            case 7:
                return 'Minggu';
                break;
            case 1:
                return 'Senin';
                break;
            case 2:
                return 'Selasa';
                break;
            case 3:
                return 'Rabu';
                break;
            case 4:
                return 'Kamis';
                break;
            case 5:
                return 'Jumat';
                break;
            case 6:
                return 'Sabtu';
                break;
            default:
                return 'Undefined';
                break;
        };
    }

    public function set_notification($post, $role = null)
    {
        $payload = array();

        if (!is_null($role)) {
            $users = $this->UserModel->getAll(array('LOWER(role)' => strtolower($role)));

            if (count($users) > 0) {
                foreach ($users as $key => $item) {
                    $payload[] = array(
                        'user_from' => $this->session->userdata('user')['id'],
                        'user_to' => $item->id,
                        'ref' => $post['ref'],
                        'ref_id' => $post['ref_id'],
                        'description' => $post['description'],
                        'link' => $post['link']
                    );
                };
            };
        } else {
            $payload[] = array(
                'user_from' => $this->session->userdata('user')['id'],
                'user_to' => $post['user_to'],
                'ref' => $post['ref'],
                'ref_id' => $post['ref_id'],
                'description' => $post['description'],
                'link' => $post['link']
            );
        };

        return $this->NotificationModel->insertBatch($payload);
    }

    public function init_list($data, $value, $text, $default_value = null, $static = null, $attrExtend = null)
    {
        $lists = '<option disabled selected>(No data available)</option>';

        if (count($data) > 0) {
            $is_selected_ph = (is_null($default_value)) ? 'selected' : '';
            $lists = '<option disabled ' . $is_selected_ph . '>Pilih &#8595;</option>';

            if (!is_null($static)) {
                $lists .= $static;
            };

            foreach ($data as $key => $item) {
                $item = (is_object($item) === false) ? (object) $item : $item;
                $is_selected = (!is_null($default_value) && ($item->{$value} === $default_value)) ? 'selected' : '';
                $attrData = '';

                // Extend attribute
                if (!is_null($attrExtend) && is_array($attrExtend)) {
                    if (count($attrExtend) > 0) {
                        foreach ($attrExtend as $index => $name) {
                            if (isset($item->{$value})) $attrData .= 'data-' . $name . '="' . $item->{$name} . '" ';
                        };
                    };
                };

                $lists .= '<option value="' . $item->{$value} . '" ' . $attrData . ' ' . $is_selected . '>' . $item->{$text} . '</option>';
            };
        };

        return $lists;
    }

    public function validate_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function getDateID($date = null, $withDay = false)
    {
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));
        $day2 = date('D', strtotime($date));
        $dayName = $this->get_day($day2);
        $monthName = $this->get_month($month);

        if ($withDay === true) {
            return $dayName . ', ' . $day . ' ' . $monthName . ' ' . $year;
        } else {
            return $day . ' ' . $monthName . ' ' . $year;
        };
    }

    public function weekOfMonth($date)
    {
        $firstOfMonth = date('Y-m-01', strtotime($date));
        return intval(date('W', strtotime($date))) - intval(date('W', strtotime($firstOfMonth)));
    }

    public function searchInArrayObj($array, $key, $value)
    {
        $result = array();

        foreach ($array as $index => $item) {
            if ($item->{$key} == $value) {
                $result = $item;
            };
        };

        return $result;
    }

    function generateRandom($strength = 16, $input = null)
    {
        $input = (is_null($input)) ? $this->_permittedChars : $input;
        $input_length = strlen($input);
        $random_string = '';

        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        };

        return $random_string;
    }

    public function generateBarcode($path = 'directory/barcode/', $text = null, $textAsName = false, $removeSpace = false)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');

        $randomString = $this->generateRandom(10);
        $text = (is_null($text)) ? $randomString : $text;
        $text = ($removeSpace) ? preg_replace('/\s+/', '', $text) : $text;
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $text), array())->draw();
        $path = rtrim($path, '/') . '/';
        $imageDir = FCPATH . $path;
        $imageName = ($textAsName) ? $text . '.jpg' : $randomString . '.jpg';
        $isCreatedDir = true;

        if (!file_exists($imageDir)) {
            $isCreatedDir = mkdir($imageDir, 0777, true);
        };

        if ($isCreatedDir) {
            $create = imagejpeg($imageResource, $imageDir . $imageName);

            if ($create) {
                $response = array('status' => true, 'data' => 'Successfully create the barcode.', 'file_path' => $path . $imageName);
            } else {
                $response = array('status' => false, 'data' => 'Failed to create the barcode.', 'file_path' => null);
            };
        } else {
            $response = array('status' => false, 'data' => 'Failed to create directory: "' . $path . '"', 'file_path' => null);
        };

        return (object) $response;
    }

    public function generateBarcodeAsImage($text = null)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');

        Zend_Barcode::render('code128', 'image', array('text' => $text), array());
    }

    public function generateQrCode($path = 'directory/qrcode/', $text = null, $textAsName = false, $removeSpace = false)
    {
        $this->load->library('ciqrcode');

        $randomString = $this->generateRandom(10);
        $text = (is_null($text)) ? $randomString : $text;
        $text = ($removeSpace) ? preg_replace('/\s+/', '', $text) : $text;
        $path = rtrim($path, '/') . '/';
        $imageDir = FCPATH . $path;
        $imageName = ($textAsName) ? $text . '.jpg' : $randomString . '.jpg';
        $isCreatedDir = true;

        if (!file_exists($imageDir)) {
            $isCreatedDir = mkdir($imageDir, 0777, true);
        };

        if ($isCreatedDir) {
            $params['data'] = $text;
            $params['level'] = 'H';
            $params['size'] = 10;
            $params['savename'] = $imageDir . $imageName;
            $create = $this->ciqrcode->generate($params);

            if ($create) {
                $response = array('status' => true, 'data' => 'Successfully create the qrcode.', 'file_path' => $path . $imageName);
            } else {
                $response = array('status' => false, 'data' => 'Failed to create the qrcode.', 'file_path' => null);
            };
        } else {
            $response = array('status' => false, 'data' => 'Failed to create directory: "' . $path . '"', 'file_path' => null);
        };

        return (object) $response;
    }

    public function generateQrCodeAsImage($text = null)
    {
        $this->load->library('ciqrcode');

        header("Content-Type: image/png");
        $params['data'] = $text;
        $this->ciqrcode->generate($params);
    }

    public function jsonToString($data, $delimiter = ', ', $replaceSearch = null, $replaceWith = null)
    {
        $result = ($data) ? json_decode($data) : array();
        $result = (count($result) > 0) ? implode($delimiter, $result) : null;

        if (!is_null($replaceSearch) && !is_null($replaceWith)) {
            $result = (!is_null($result)) ? str_replace($replaceSearch, $replaceWith, $result) : $result;
        };

        return $result;
    }

    public function jsonToComnponent($data, $tagOpen = '<li>', $tagClose = '</li>', $replaceSearch = null, $replaceWith = null)
    {
        $result = ($data) ? json_decode($data) : array();
        $itemValue = '';

        if (count($result) > 0) {
            foreach ($result as $key => $item) {
                if (!is_null($replaceSearch) && !is_null($replaceWith)) {
                    $item = (!is_null($item)) ? str_replace($replaceSearch, $replaceWith, $item) : $item;
                };

                $itemValue .= $tagOpen . $item . $tagClose;
            };
        } else {
            $result = null;
        };

        return $itemValue;
    }

    public function getMailConfig()
    {
        $app = $this->app();
        $smtp = array(
            'protocol' => $app->smtp_protocol,
            'smtp_host' => $app->smtp_host,
            'smtp_port' => $app->smtp_port,
            'smtp_user' => $app->smtp_user,
            'smtp_pass' => $app->smtp_pass,
            'smtp_crypto' => $app->smtp_crypto,
            'smtp_timeout' => 30,
            'mailtype' => $app->smtp_mailtype,
            'wordwrap' => TRUE,
            'charset' => $app->smtp_charset
        );

        return $smtp;
    }

    public function sendMail($params = [])
    {
        error_reporting(0);
        $this->load->library('email');

        try {
            $this->config = $this->getMailConfig();
            $this->email->initialize($this->config);
            $this->email->set_newline("\r\n");
            $this->email->from($this->config['smtp_user'], $this->_charName);
            $this->email->to($params['receiver']);
            $this->email->subject($params['subject']);
            $this->email->message($params['message']);

            if ($this->email->send()) {
                return array('status' => true, 'data' => 'Email has been successfully sent to ' . $params['receiver']);
            } else {
                // DEBUG ONLY
                // print_r($this->email->print_debugger(['headers']));
                // die;
                // END ## DEBUG ONLY

                return array('status' => false, 'data' => 'Failed to send email.');
            };
        } catch (\Throwable $th) {
            return array('status' => false, 'data' => 'Failed to send email.', 'error' => $th);
        };
    }

    public static function excelChar()
    {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
            'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
            'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
            'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
            'EA', 'EB', 'EC', 'EE', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ',
        );
    }

    public function generateExcelByTemplate($fileTemplate, $startAttributeRow, $startDataRow, $payload, $outputFileName = 'dump-excel.xlsx', $inject = null)
    {
        error_reporting(0); // Handle PHP 7.4 PHPOffice bug

        // Write excel
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($fileTemplate);
        $excelChar = $this->excelChar();

        if (count($payload) > 0) {
            $dataNo = 1;
            $attributes = array();
            $sheet = $spreadsheet->setActiveSheetIndex(0);

            // Collect attributes
            foreach ($excelChar as $key => $col) {
                $col = trim($col);
                $val = $sheet->getCell($col . $startAttributeRow)->getFormattedValue();

                if (!empty($val) && !is_null($val)) {
                    $attributes[] = $val;
                };
            };

            // Set value with attributes
            foreach ($payload as $index => $item) {
                $num = 0;
                foreach ($attributes as $key => $val) {
                    $value = ($val === 'no') ? $dataNo++ : $item->{$val};
                    $sheet->setCellValue($excelChar[$num] . $startDataRow, $value);
                    $num++;
                };
                $startDataRow++;
            };

            if (!is_null($inject)) {
                eval($inject);
            };

            // Output stream
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $outputFileName . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        } else {
            echo 'Tidak ditemukan data.';
        };
    }

    public function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {
        $dates = [];
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        };
        return $dates;
    }

    public function arrayToSetter($payload = array(), $withSpecial = true)
    {
        $result = array();

        if (count($payload) > 0) {
            $fields = array_keys((array) $payload[0]);

            foreach ($fields as $index => $field) {
                $no = 1;
                $fieldValues = array();
                $fieldValues_no = array();

                foreach ($payload as $index_item => $item) {
                    $item = (array) $item;
                    $fieldValues[] = $item[$field];
                    $fieldValues_no[] = $no++;
                };

                if ($withSpecial === true) {
                    $result['[no]'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $fieldValues_no);
                    $result['[' . $field . ']'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $fieldValues);
                } else {
                    $result[$field] = $fieldValues;
                };
            };
        };

        return $result;
    }

    public function arrayToSetterSimple($payload = array())
    {
        $result = array();
        $strip_tag_keys = array('keterangan', 'catatan', 'catatan_atas_retur', 'faktur_pembelian_keterangan');

        if (!is_null($payload)) {
            foreach ($payload as $index => $item) {
                $value = $item;

                if (in_array($index, $strip_tag_keys)) {
                    $value = strip_tags($item);
                };

                $result['{' . $index . '}'] = $value;
            };
        };

        return $result;
    }

    public function dd($var, $isPrint = true)
    {
        echo '<pre>';
        if ($isPrint === true) {
            print_r($var);
        } else {
            var_dump($var);
        };
        die;
    }

    public function cleanString($string)
    {
        if (!is_null($string)) {
            $string = trim($string);
            $string = preg_replace('/[^a-zA-Z0-9_.]/', '_', $string);
            return $string;
        };
        return $string;
    }

    public function getReferencePayload($dateDiff = array(), $dateConcat = array())
    {
        $output = array(
            'hari_ini' => date('d M Y'),
            'jam' => date('H:i:s'),
            'nama_hari' => $this->get_day(date('D')),
            'tanggal' => date('d'),
            'bulan' => date('m'),
            'nama_bulan' => $this->get_month(date('m')),
            'tahun' => date('Y'),
        );

        if (count($dateDiff) > 0) {
            $date1 = new DateTime($dateDiff['start']);
            $date2 = new DateTime($dateDiff['end']);
            $dateDiff_result = $date1->diff($date2);
            $yearsInMonths = $dateDiff_result->format('%r%y') * 12;
            $months = $dateDiff_result->format('%r%m');
            $totalMonths = $yearsInMonths + $months;

            $output = array_merge($output, array($dateDiff['key'] . '_start' => $dateDiff['start']));
            $output = array_merge($output, array($dateDiff['key'] . '_end' => $dateDiff['end']));
            $output = array_merge($output, array($dateDiff['key'] . '_hari' => $dateDiff_result->days));
            $output = array_merge($output, array($dateDiff['key'] . '_bulan' => $totalMonths));
            $output = array_merge($output, array($dateDiff['key'] . '_tahun' => $dateDiff_result->y));
        };

        if (count($dateConcat) > 0) {
            $dateConcat_start = strtotime($dateConcat['start']);
            $dateConcat_end = strtotime($dateConcat['end']);
            $dateConcat_start_day = date('d', $dateConcat_start);
            $dateConcat_start_month = date('m', $dateConcat_start);
            $dateConcat_start_monthName = $this->get_month($dateConcat_start_month);
            $dateConcat_start_year = date('Y', $dateConcat_start);
            $dateConcat_end_day = date('d', $dateConcat_end);
            $dateConcat_end_month = date('m', $dateConcat_end);
            $dateConcat_end_monthName = $this->get_month($dateConcat_end_month);
            $dateConcat_end_year = date('Y', $dateConcat_end);
            $dateConcat_result = null;

            if (($dateConcat_start_day == $dateConcat_end_day) && ($dateConcat_start_month == $dateConcat_end_month) && ($dateConcat_start_year == $dateConcat_end_year)) {
                $dateConcat_result =  $dateConcat_start_day . ' ' . $dateConcat_start_monthName . ' ' . $dateConcat_start_year; // output = 05 Juli 2023
            } else if (($dateConcat_start_day != $dateConcat_end_day) && ($dateConcat_start_month == $dateConcat_end_month) && ($dateConcat_start_year == $dateConcat_end_year)) {
                $dateConcat_result = $dateConcat_start_day . ' - ' . $dateConcat_end_day . ' ' . $dateConcat_start_monthName . ' ' . $dateConcat_start_year; // output = 05 - 07 Juli 2023
            } else if (($dateConcat_start_month != $dateConcat_end_month) && ($dateConcat_start_year == $dateConcat_end_year)) {
                $dateConcat_result = $dateConcat_start_day . ' ' . $dateConcat_start_monthName . ' - ' . $dateConcat_end_day . ' ' . $dateConcat_end_monthName . ' ' . $dateConcat_start_year; // output =  05 Mei - 07 Juli 2023
            } else if ($dateConcat_start_year != $dateConcat_end_year) {
                $dateConcat_result = $dateConcat_start_day . ' ' . $dateConcat_start_monthName . ' ' . $dateConcat_start_year . ' - ' . $dateConcat_end_day . ' ' . $dateConcat_end_monthName . ' ' . $dateConcat_end_year; // output = 05 Mei 2022 - 07 Juli 2023
            };

            $output = array_merge($output, array($dateConcat['key'] => $dateConcat_result));
        };

        return $output;
    }

    public function angkaTerbilang($nominal = 0)
    {
        $terbilang = new Terbilang();
        $terbilang->pemisahDesimal = '.';
        $result = $terbilang->terbilang($nominal);
        $result = str_replace('koma nol nol', '', $result);

        return trim($result);
    }
}
