<?php

Class Install extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}
	
// ------------------------------------------------------------------------

/**
 *  index()
 *
 *  installs the qr generator software
 *
 * @access	public
 * @return	string
 */
	
	function index()
	{
	
		
		/* Create the Key Table */
			$this->dbforge->add_field("id");
			
			$field_array = array(
				"redirect_key"=>array("type"=>"VARCHAR","constraint"=>"50"),
				"redirect_url" => array("type"=>"VARCHAR","constraint"=>"255"),
				"redirect_notes"=>array("type"=>"LONGTEXT"),
				"redirect_date_created"=>array("type"=>"DATETIME"),
				"redirect_status"=>array("type"=>"INT","default"=>1)
			);
			
			$this->dbforge->add_field($field_array);
			$this->dbforge->create_table("qr_redirects");
		
		/* Create the Tracking Table */
			$this->dbforge->add_field("id");
			
			$field_array = array(
				"tracking_redirect_id"=>array("type"=>"INT"),
				"tracking_datetime" => array("type"=>"DATETIME"),
				"tracking_ip"=>array("type"=>"VARCHAR","constraint"=>"20"),
				"tracking_user_agent"=>array("type"=>"LONGTEXT")
			);
			
			$this->dbforge->add_field($field_array);
			$this->dbforge->add_key("tracking_redirect_id");
			$this->dbforge->create_table("qr_tracking");
			
		/* Sessions */	
			$session_db = "CREATE TABLE IF NOT EXISTS  `ci_sessions` (session_id varchar(40) DEFAULT '0' NOT NULL,ip_address varchar(16) DEFAULT '0' NOT NULL,user_agent varchar(120) NOT NULL,last_activity int(10) unsigned DEFAULT 0 NOT NULL,user_data text NOT NULL,PRIMARY KEY (session_id),KEY `last_activity_idx` (`last_activity`));";
		
			$this->db->query($session_db);
			
		/* Load the installation view */
			$this->load->view("installation");
			
	}
	
}