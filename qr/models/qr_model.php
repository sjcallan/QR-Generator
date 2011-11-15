<?php

class Qr_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->library("qr_code_lib");
	}

// ------------------------------------------------------------------------

/**
 * build_qr()
 *
 *  generates a qr code based on a key, size and width
 *
 * @access	public
 * @param	string - $key - the qr code key
 * @param	int - $size - the size of the qr code, 15 is LARGE 
 * @param	int - $width - the img width you want
 * @return	img HTML tag string
 */
	
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