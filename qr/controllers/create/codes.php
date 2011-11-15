<?php

Class Codes extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('table');
		$this->load->helper("string_helper");
		$this->load->library('highcharts_lib');
		$this->limit = 10;
		$this->graph_limit = 30;
		
		session_start();
	}
	
// ------------------------------------------------------------------------

/**
 * index()
 *
 *  Main codes view page - displays a table of codes that can be paged.
 *
 * @access	public
 * @return	VIEW
 */
	
	function index()
	{
	
		/* Collect any messages */
			if(isset($_SESSION['success_message']))
			{
				$view_data["message"] = $_SESSION['success_message'];
				$_SESSION['success_message'] = "";
			}
			else
			{
				$view_data["message"] = "";
			}
			
		// offset
			$uri_segment = 4;
			$offset = $this->uri->segment($uri_segment);

		// load data
			$entries = $this->key_model->get_paged_list($this->limit, $offset)->result();

		// generate pagination
			$this->load->library('pagination');
			$config['site_url'] = site_url('create/codes/index');
			$config['total_rows'] = $this->key_model->count_all();
			$config['per_page'] = $this->limit;
			$config['uri_segment'] = $uri_segment;
			
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div>';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li class="next">';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$this->pagination->initialize($config);
			$view_data['pagination'] = $this->pagination->create_links();

		// generate table data
			
			$tmpl = array (
				'row_start'		=> "<tr>",
				'row_alt_start'	=> "<tr class='alt_row'>"
			);
			
			$this->table->set_template($tmpl); 
			$this->table->set_heading('','Key', 'Redirect', 'Creation Date','Details','Hi-Res','Scans');
			$i = 0 + $offset;
		
			foreach ($entries as $entry)
			{
				if($entry->redirect_date_created == NULL)
				{
					$date = "-";
				}
				else
				{
					$date = $entry->redirect_date_created;
				}
				
				$link = $entry->redirect_url;
				$click_count = $this->key_model->get_click_count($entry->id);
			
				$this->table->add_row(
					$this->qr_model->build_qr($entry->redirect_key,"5","30"),
					$entry->redirect_key, "<a href='" . $link . "' title='" . $link . "'>" . trim_string($link,30) . "</a><br />" . $entry->redirect_notes, 
					$date, 
					"<a href='" . site_url("create/codes/details/" . $entry->redirect_key) . "'>Details</a>","<a href='" . site_url("create/codes/generate/" . $entry->redirect_key ."/100/1200") . "' target='_blank'>Download</a>",$click_count);
				++$i;
			}
		
		
		$view_data['table'] = $this->table->generate();
	
		$master_data["page_title"] = "Codes";
		$master_data["view"] = $this->load->view("codes",$view_data,TRUE);	
		
		$this->load->view("_master",$master_data);
	}

// ------------------------------------------------------------------------

/**
 * create()
 *
 *  creates a new code based on values from the Create New QR Code Form
 *
 * @access	public
 * @return	REDIRECT
 */
	
	public function create()
	{
	
		$url = "http://" . $this->input->post("url");
		$notes = $this->input->post("notes");
		$key = $this->key_model->create_key($url, $notes);
		$redirect_url = site_url() . "r/" . $key;
		
		$_SESSION['success_message'] = 'The code was created successfully.';
		redirect(site_url("create/codes"));
	
	}
	
// ------------------------------------------------------------------------

/**
 * details()
 *
 *  the details view of a given qr code - displays a hichart and code details with a button to delete the code if needed
 *
 * @access	public
 * @param	string - $key - the qr key
 * @return	VIEW
 */
	
	public function details($key)
	{
			$key_details = $this->key_model->get_details($key);
	
		/* Genereate Chart Data */
			
			$total_records = $this->key_model->count_all_days();
			$start_point = $total_records-$this->limit;
			
			if($start_point < 0)
			{
				$start_point = 0;
			}
			
			$graph_entries = $this->key_model->get_paged_list_days($this->graph_limit, $start_point, "asc", $key_details["id"])->result();
			
			$this->highcharts_lib
				->set_graph_type('spline')
				->toggle_legend(TRUE)
				->display_shadow(TRUE)
				->set_xlabels_otpions('rotation', -45)
				->set_graph_title('')
				->set_yAxis(array(), 'Scans')
				->add_serie('Scans over the last 30 days', array(),'areaspline');
			
			foreach ($graph_entries as $entry)
			{
				$graph_date =  $entry->graph_date;	
				
				if($entry->click_count == NULL)
				{
					$click_count = "0";
				}
				else
				{
					$click_count = $entry->click_count;
				}
			
				$this->highcharts_lib->push_serie_data('Scans over the last 30 days', $click_count);
				$this->highcharts_lib->push_xAxis_value($graph_date);
			}	
			
			$s_graph = $this->highcharts_lib->render();
			$view_data["graph_source"] = $s_graph;
		
		/* Details Page data */
			$view_data["redirect_details"] = $key_details;
			$view_data["qr_code"] = $this->qr_model->build_qr($key,"15","100");
		
		/* Master Data */		
			$master_data["page_title"] = "Code Details - <small>" . $key_details["redirect_url"] . "</small>";
			$master_data["view"] = $this->load->view("details",$view_data,TRUE);	
			
			$this->load->view("_master",$master_data);
		
	}
	
// ------------------------------------------------------------------------

/**
 * generate()
 *
 *  generates a qr code based on a key, size and width
 *
 * @access	public
 * @param	string - $key - the qr code key
 * @param	int - $size - the size of the qr code, 15 is LARGE 
 * @param	int - $width - the img width you want
 * @return	TRUE
 */

	public function generate($key, $size, $width)
	{
		
		/* Return the image */
			echo($this->qr_model->build_qr($key,$size, $width));
			return TRUE;
	
	}
	
// ------------------------------------------------------------------------

/**
 * deete()
 *
 *  remap the incoming request to the _redirect function
|* 	this means we don't have to have /r/index/key in the call
 *
 * @access	public
 * @param	string - key - the key of the qr code
 * @return	REDIRECT
 */
	
	public function delete($key)
	{
		
		$_SESSION['success_message'] = 'The code was removed successfully.';
		$this->key_model->delete($key);
		redirect(site_url("create/codes"));
		
	}


}