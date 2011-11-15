<?php

class Qr_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->library("qr_code_lib");
	}
	
	public function build_qr($key,$size, $width)
	{
	
		/* Get the URL & Track the key */
			$redirect_url = site_url("r/" . $key);
			
			/* Load the Library */
			$this->qr_code_lib->data = $redirect_url;
    		$this->qr_code_lib->size = $size;
		
		/* Return the image */
			return "<img src='" . $this->qr_code_lib->build() . "' width='" . $width . "'/>";
		
	}
	
}