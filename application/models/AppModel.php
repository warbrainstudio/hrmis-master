<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppModel extends CI_Model
{
	function getData_dtAjax($config)
	{
		/**
		How to use? Call from controller.
		
		// Example
		$static_conditional = array(
			// 'col1_example' => 'value_col1_example',
			// 'col2_example' => 'value_col2_example',
		);

		// Specific condition or remove if no have
		$static_conditional_spec = array();
		if ( $this->session->userdata('type') == 'R04' ) {
			$static_conditional_spec = array(
				'SPVNIK' => $this->session->userdata('nik'),
			);
		};

		$dtAjax_config = array(
			'table_name'              	=> 'table_name', // required
			'static_conditional'      	=> $static_conditional, // optional
			'static_conditional_spec' 	=> $static_conditional_spec, // optional
			'static_conditional_in_key' => 'id', // optional, but required if static_conditional_in defined
			'static_conditional_in' 	=> array(1, 2, 3, 4), // optional, but required if static_conditional_in_key defined
			'order_column			  	=> 1 // required, index column in dataTables
			'order_column_dir		  	=> 'asc' // optional, default to asc
			'table_join'			  	=> array( // optional
				array(
					'table_name' => 'table_name_2',
					'expression' => 'table_name_2.id = table_name.id',
					'type' => 'left'
				),
				array(
					'table_name' => 'table_name_3',
					'expression' => 'table_name_3.id = table_name_2.id',
					'type' => 'left'
				),
			)
		);

		$response = $this->AppModel->getData_dtAjax( $dtAjax_config );
		echo json_encode( $response );
		// END - Example
		 */

		// Retrive from request header
		$draw    = $_REQUEST['draw'];
		$length  = $_REQUEST['length'];
		$start   = $_REQUEST['start'];
		$search  = $_REQUEST['search']['value'];
		$order   = $_REQUEST['order'][0];
		$columns = $_REQUEST['columns'];

		// Get config
		$select_column             = (isset($config['select_column'])) ? $config['select_column'] : null;
		$table_name                = (isset($config['table_name'])) ? $config['table_name'] : null;
		$order_column              = (isset($config['order_column'])) ? $config['order_column'] : 1; // You can set to action column for default order by query
		$order_column_dir          = (isset($config['order_column_dir'])) ? $config['order_column_dir'] : $order['dir'];
		$static_conditional        = (isset($config['static_conditional'])) ? $config['static_conditional'] : array();
		$static_conditional_spec   = (isset($config['static_conditional_spec'])) ? $config['static_conditional_spec'] : array();
		$static_conditional_in_key = (isset($config['static_conditional_in_key'])) ? $config['static_conditional_in_key'] : null;
		$static_conditional_in     = (isset($config['static_conditional_in'])) ? $config['static_conditional_in'] : array();
		$filter_conditional        = array();
		$table_join				   = (isset($config['table_join'])) ? $config['table_join'] : null;

		// Get client params
		$columnItem = array();
		$columnSearchDisable = array();
		foreach ($columns as $key => $item) {
			if (!empty($item['data']) && $item['searchable'] == 'false') {
				$columnSearchDisable[] = $item['data'];
			};
			$columnItem[] = $item['data'];

			// Set filter by datatable column
			if (!empty($item['search']['value'])) {
				$search_value = trim(stripslashes($item['search']['value']), '^$');
				$search_value = ($search_value == 'null') ? null : $search_value;

				$filter_conditional[$item['data']] = $search_value;
			};
		};

		// Set no include filter
		$columnNoFilter = array_merge(array('no'), array_keys($static_conditional), array_keys($static_conditional_spec), $columnSearchDisable);

		// Get column for search
		foreach ($columnItem as $key => $item) {
			if (!empty($item) && !in_array($item, $columnNoFilter)) {
				$columnSearch['LOWER(CAST(' . $item . ' AS TEXT))'] = strtolower($search);
			};
		};

		// Set order by
		$orderBy  = $columnItem[$order['column']];
		$orderBy  = (!in_array($order['column'], array(0))) ? $columnItem[$order['column']] : $columnItem[$order_column];
		$orderDir = $order_column_dir;
		$response = array();

		$conditional = array_merge($static_conditional, $static_conditional_spec, $filter_conditional);

		// Set conditional for get rows count
		if (count($conditional) > 0) {
			$this->db->where($conditional);
		};

		$totalRow = $this->db->count_all_results($table_name);

		$response['draw'] = $draw;
		$response['recordsTotal'] = $response['recordsFiltered'] = $totalRow;
		$response['data'] = array();

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->or_like($columnSearch);
			$this->db->group_end();
		};

		// Set conditional for get rows data
		if (count($conditional) > 0) {
			$this->db->where($conditional);
		};

		// Set conditional in for get rows data
		if (!empty($static_conditional_in_key) && count($static_conditional_in) > 0) {
			$this->db->where_in($static_conditional_in_key, $static_conditional_in);
		};

		$this->db->limit($length, $start);
		$this->db->order_by($orderBy, $orderDir); // Uncomment for dynamic order by first column in datatables

		// Set select column
		if (!is_null($select_column)) {
			$this->db->select($select_column);
		};

		// Set join
		if (!is_null($table_join) && count($table_join) > 0) {
			foreach ($table_join as $index => $join) {
				if (count($join) === 3) {
					(array) $join;
					$this->db->join($join['table_name'], $join['expression'], $join['type']);
				};
			};
		};

		$query = $this->db->get($table_name);

		if (!empty($search)) {
			$this->db->or_like($columnSearch);
			$results = $this->db->get($table_name);
			$response['recordsTotal'] = $response['recordsFiltered'] = $results->num_rows();
		};

		// Fetch data
		foreach ($query->result_array() as $item) {
			$response['data'][] = $item;
		};

		$response['filter_cond'] = $filter_conditional;
		$response['column_rendered'] = $columnSearch;
		$response['filter_all'] = $conditional;

		return $response;
	}
}
