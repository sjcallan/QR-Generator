<?php

Class R extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		
	}

// ------------------------------------------------------------------------

/**
 * _remap()
 *
 *  remap the incoming request to the _redirect function
|* 	this means we don't have to have /r/index/key in the call
 *
 * @access	private
 * @param	string
 * @return	FALSE
 */
	
	function _remap($param)
	{
		if($param == "")
		{
			redirect(SITE_OWNER_URL,'location',301);
		}
		else
		{
			$this->_redirect($param);
		}
	}
	
// ------------------------------------------------------------------------

/**
 * _redirect()
 *
 *  Send a 301 Redirect request to the new location
 *
 * @access	private
 * @param	string
 * @return	redirect
 */
	
	
	private function _redirect($key)
	{
		
		/* Get the URL & Track the key */
			$url = $this->key_model->get_url($key);
		
		/* Redirect if it's a valid key */
			redirect($url,'location',301);
	
			
	}


}