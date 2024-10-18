<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppMixModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getData_dtAjax($query, $searchOnlyFields = null)
    {
        /**
		How to use? Call from controller.
		
		// Example
        // Set query yang akan di gunakan dengan ketentuan :
        // -- main query harus di akhiri dengan WHERE, jika tidak ada kondisional tambahkan " WHERE 1=1 "
        // -- main query tidak boleh di akhiri dengan ORDER, jika membutuhkan order simpan didalam sub query
        // -- hindari menyimpan query pada controller, usahakan simpan didalam model
		$query = "SELECT * FROM table_name WHERE 1=1";

        // Set kolom yang bisa digunakan untuk pencarian :
        // -- Jika NULL maka pencarian akan menggunakan semua kolom yang ada di dataTables,
        // -- atau set kolom spesifik dalam bentuk array() untuk menentukan kolom yang hanya bisa di search
        $searchOnlyFields = array('field_1', 'field_2');

		$response = $this->AppMixModel->getData_dtAjax( $query, $searchOnlyFields );
		echo json_encode( $response );
		// END - Example
         */

        // Retrive from request header
        $draw = $_REQUEST['draw'];
        $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_REQUEST['length']}");
        $start = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_REQUEST['start']}");
        $search = htmlspecialchars($_REQUEST['search']['value']);
        $order = $_REQUEST['order'][0];
        $columns = $_REQUEST['columns'];

        // Clean query
        $query = str_replace(';', '', $query);

        // Get column params
        $columnItem = array();
        $columnSearchDisable = array();
        foreach ($columns as $key => $item) {
            if (!empty($item['data']) && $item['searchable'] == 'false') {
                $columnSearchDisable[] = $item['data'];
            };
            $columnItem[] = $item['data'];
        };

        // Set no include filter
        $columnNoFilter = array_merge(array('no'), $columnSearchDisable);

        $sql = $this->db->query($query);
        $sql_count = $sql->num_rows();

        $dtSearch = '';
        if (!empty($search)) {
            $searchOnlyFields = (!is_null($searchOnlyFields)) ? $searchOnlyFields : $columnItem;
            foreach ($searchOnlyFields as $key => $item) {
                if (!empty($item) && !in_array($item, $columnNoFilter)) {
                    $dtSearch .= "LOWER(CAST(" . $item . " AS TEXT)) LIKE '%" . strtolower($search) . "%' OR ";
                };
            };
            $dtSearch = trim($dtSearch, " OR ");
            $query .= " AND (" . $dtSearch . ")";
        };

        // Set ordering
        $orderBy  = $columnItem[$order['column']];
        $orderBy  = (!in_array($order['column'], array(0))) ? $columnItem[$order['column']] : $columnItem[0];
        $orderDir = $order['dir'];

        $order = '';
        if (!empty($orderBy)) {
            $order = " ORDER BY " . $orderBy . " " . $orderDir;
        };

        // Get data
        $queryMain = $query;
        $queryData = $query . $order . " LIMIT " . $limit . " OFFSET " . $start;
        $data = $this->db->query($queryData)->result_array();

        // Get data count
        if (isset($search)) {
            $sql_cari =  $this->db->query($queryMain);
            $sql_filter_count = $sql_cari->num_rows();
        } else {
            $sql_filter = $this->db->query($query);
            $sql_filter_count = $sql_filter->num_rows();
        };

        $callback = array(
            'draw' => $draw,
            'recordsTotal' => $sql_count,
            'recordsFiltered' => $sql_filter_count,
            'data' => $data
        );

        return $callback;
    }
}
