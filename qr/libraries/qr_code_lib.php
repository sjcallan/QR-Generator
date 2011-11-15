<?php

/*
=====================================================
 Author: MaxLazar
 http://eec.ms
=====================================================
 Purpose: QR Code Generator
=====================================================
#   d = data         URL encoded data.
#   e = ECC level    L or M or Q or H   (default M)
#   s = module size  (dafault PNG:4 JPEG:8)
#   v = version      1-40 or Auto select if you do not set.
#   t = image type   J:jpeg image , other: PNG image
*/

class Qr_code_lib {
	
    function __construct()
    {    
    	
    	$this->base_path		= "";
		$this->cache			= "";
		$this->data 			= "";
		$this->ecc 				= "";
		$this->type 			= "";
		$this->version 			= "";
		$this->action 			= "";
		$this->outline_size 	= "";
		$this->tel 				= "";
		$this->email 			= "";
    	$this->title 			= "";
    	$this->base_cache 		= "";
    	$this->bk_color			= "";
    	$this->px_color			= "";
    	$this->size				= "";
		
	}
	
	function build()
	{	
		
		$libfolder = QR_LIBRARY_FOLDER;
		
		$base_path = ( ! $this->base_path) ? $_SERVER['DOCUMENT_ROOT']."/" : $this->base_path;
	
		$base_path = str_replace("\\", "/", $base_path);
		$base_path = $this->remove_double_slashes($base_path);
		
		$cache = ( ! $this->cache) ? '' : $this->cache;

		$data = array(
									'd' => $this->data,
									'e' => (!$this->ecc) ? 'M' : $this->ecc,
									't' => (!$this->type) ? 'PNG' : $this->type,
									's' => (!$this->size) ? '' : $this->size,
									'v' => (!$this->version) ? null : $this->version,    
								);   
		
		$action = $this->action;
		
		$bk_color  = ($this->bk_color) ? $this->bk_color : 'ffffff';
		$px_color  = ($this->bk_color) ? $this->px_color : '000000';
		$outline_size  = ($this->outline_size) ? $this->outline_size : 2;
		
		switch ($action)
		{
		   case "sms": 
			  $tel = (!$this->tel) ? '': $this->tel;
			  $data['d'] = "SMSTO:".((!$this->tel) ? '': $this->tel).':'.$data['d'];
			  break;
		   case "email": 
			  $data['d'] = "SMTP:".((!$this->email) ? '': $this->email).':'.((!$this->sabj) ? '': $this->sabj).':'.$data['d'];
			  break;
		   case "tel": 
			  $data['d'] = "TEL:".((!$this->tel) ? '': $this->tel);
			  break;
		   case "site": 
			  $data['d'] = urlencode($data['d']);
			  break;
		   case "bm": 
			  $data['d'] = "MEBKM:TITLE:".((!$this->title) ? '': $this->title).':'.urlencode($data['d']);
			  break;
		}

		$base_cache = $this->remove_double_slashes($base_path."images/cache/");
		$base_cache = ( !$this->base_cache) ? $base_cache : $this->base_cache;
		$base_cache = $this->remove_double_slashes($base_cache);

		if(!is_dir($base_cache))
		{
				// make the directory if we can 
				if (!mkdir($base_cache,0777,true))
				{
				return "Error: could not create cache directory ".$base_cache." with 777 permissions";
				}
		}
		
		$file_ext = ($data['t'] =='J'?'.jpeg':'.png');
		$file_name = md5(serialize($data)).$file_ext;
		
		if (!is_readable($base_cache.$file_name) ) {		
			$qrcode_data_string   = $data['d'];
			$qrcode_error_correct = $data['e'];
			$qrcode_module_size   =  $data['s'];
			$qrcode_version       = $data['v'];
			$qrcode_image_type    = $data['t'];
			
			$path  = $libfolder.'qrcode_lib/data';
			$image_path = $libfolder.'qrcode_lib/image'; 

			require_once $libfolder.'qrcode/qrlib.php';

		
			QRcode::png($qrcode_data_string, $base_cache.$file_name, $qrcode_error_correct , $qrcode_module_size, $outline_size, false,$px_color,$bk_color);
		}
		
		
		return $this->return_data =$this->remove_double_slashes("/".str_replace($base_path, '', $base_cache.$file_name));
    }

	function remove_double_slashes($str)
	{
		return preg_replace("#(^|[^:])//+#", "\\1/", $str);
	}

}