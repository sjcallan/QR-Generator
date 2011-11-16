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
	
	public function build_qr($key, $size, $width)
	{
	
		/* Get the type of QR code this is */
			$code_data = $this->key_model->get_details($key);
			$code_type = $code_data["redirect_type"];
			$code_notes = $code_data["redirect_notes"];
	
			if($code_type == "url")
			{
				$code_content = site_url("r/" . $key);
			}
			else
			{
				$code_content = $code_notes;	
			}
	
		/* Load the Library */
			$this->qr_code_lib->data = $code_content;
    		$this->qr_code_lib->size = $size;
		
		/* Return the image */
			return "<img src='" . $this->qr_code_lib->build() . "' width='" . $width . "'/>";
		
	}
	
// ------------------------------------------------------------------------

/**
 * get_code_type()
 *
 *  gets the type of QR code this is
 *
 * @access	public
 * @param	string - $key - the qr code key
 * @return	string code type
 */	
	
	public function get_code_type($key)
	{
		$this->db->select("redirect_type");
		$qr_query = $this->db->get("qr_redirects");
		
		return $qr_query->row("redirect_type");
		
	}
	
}