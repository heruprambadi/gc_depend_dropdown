<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examples extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	public function index()
	{
		$crud = new grocery_CRUD();

		//Jangan lupa load library ini
		$this->load->library('gc_dependent_select');
		$crud->set_crud_url_path(site_url('examples/index'));

		$crud->set_table('data_diri');
		$crud->columns('nama','fk_id_prov','fk_id_kota','fk_id_kec','fk_id_kel');
		$crud->display_as('fk_id_prov','Provinsi')
			 ->display_as('fk_id_kota','Kota')
			 ->display_as('fk_id_kec','Kecamatan')
			 ->display_as('fk_id_kel','Kelurahan');
		$crud->set_subject('Data Diri');
		$crud->set_relation('fk_id_prov','provinsi','nama');
		$crud->set_relation('fk_id_kota','kabupaten','nama');
		$crud->set_relation('fk_id_kec','kecamatan','nama');
		$crud->set_relation('fk_id_kel','kelurahan','nama');

		$fields = array(

		// Field Provinsi
		'fk_id_prov' => array( // first dropdown name
		'table_name' => 'provinsi', // table of country
		'title' => 'nama', // country title
		'relate' => null, // the first dropdown hasn't a relation
		'data-placeholder' => 'Pilih Provinsi' //dropdown's data-placeholder:
		),
		// Field Kabupaten
		'fk_id_kota' => array( // second dropdown name
		'table_name' => 'kabupaten', // table of state
		'title' => 'nama', // state title
		'id_field' => 'id_kab', // table of state: primary key
		'relate' => 'id_prov', // table of state:
		'data-placeholder' => 'Pilih Kota' //dropdown's data-placeholder:

		),
		// Field Kecamatan
		'fk_id_kec' => array(
		'table_name' => 'kecamatan',
		'title' => 'ID: {id_kec} / Kota : {nama}',  // now you can use this format )))
		//'where' =>"post_code>'167'",  // string. It's an optional parameter.
		//'order_by'=>"id_kab DESC",  // string. It's an optional parameter.
		'id_field' => 'id_kec',
		'relate' => 'id_kab',
		'data-placeholder' => 'Pilih Kecamatan'
		),
		// Field Kabupaten
		'fk_id_kel' => array( // second dropdown name
		'table_name' => 'kelurahan', // table of state
		'title' => 'nama', // state title
		'id_field' => 'id_kel', // table of state: primary key
		'relate' => 'id_kec', // table of state:
		'data-placeholder' => 'Pilih Kelurahan' //dropdown's data-placeholder:

		)
		);

		$config = array(
		'main_table' => 'data_diri',
		'main_table_primary' => 'id',
		"url" => site_url() . '/examples/index/',
		'ajax_loader' => base_url() . 'assets/ajax-loader.gif'
		);
		$categories = new gc_dependent_select($crud, $fields, $config);

		// first method:
		//$output = $categories->render();

		// the second method:
		$js = $categories->get_js();
		$output = $crud->render();
		$output->output.= $js;

		$this->_example_output($output);
	}

}