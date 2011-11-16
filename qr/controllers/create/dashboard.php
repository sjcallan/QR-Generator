<?php

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('table');
		$this->load->library("qr_code_lib");
		$this->load->library('highcharts_lib');
		$this->load->helper("string_helper");
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		
		$this->limit = 30;
	}
	
// ------------------------------------------------------------------------

/**
 * index()
 *
 *  The main dashboard screen - this screen has a hicharts chart and 
 *  a quick view of the most recent codes
 *
 * @param	string
 * @return	VIEW
 */
	
	function index()
	{
	
	
		/* Load the Chart Data */
			
			$total_records = $this->key_model->count_all_days();
			$start_point = $total_records-$this->limit;
			
			if($start_point < 0)
			{
				$start_point = 0;
			}
			
			$graph_entries = $this->key_model->get_paged_list_days($this->limit, $start_point, "asc")->result();
			
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
			
		/* Get and display the Newest Codes */
			$entries = $this->key_model->get_paged_list()->result();
			
			$tmpl = array (
				'row_start'		=> "<tr>",
				'row_alt_start'	=> "<tr class='alt_row'>"
			);
			
			$this->table->set_template($tmpl); 
			$this->table->set_heading('','Key', 'Type', 'Redirect', 'Creation Date','Details','Hi-Res','Scans');
		
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
				
				if($entry->redirect_type == "url")
				{
					$link = $entry->redirect_url;
					$click_count = $this->key_model->get_click_count($entry->id);
					$code_content = "<a href='" . $link . "' title='" . $link . "'>" . trim_string($link,30) . "</a><br />" . $entry->redirect_notes;
				}
				else
				{
					$code_content = $entry->redirect_notes;
					$click_count = "NA";
				}
			
				$this->table->add_row(
					$this->qr_model->build_qr($entry->redirect_key,"5","30"),
					$entry->redirect_key, 
					$entry->redirect_type,
					$code_content, 
					$date,
					"<a href='" . site_url("create/codes/details/" . $entry->redirect_key) . "'>Details</a>","<a href='" . site_url("create/codes/generate/" . $entry->redirect_key ."/100/1200") . "' target='_blank'>Download</a>",
					$click_count);
					
			}
		
		
			$view_data['codes'] = $this->table->generate();
	
		
		/* Load the View */
			$master_data["page_title"] = "Dashboard";
			$master_data["view"] = $this->load->view("home",$view_data,TRUE);	
		
			$this->load->view("_master",$master_data);
	}
	
	
}