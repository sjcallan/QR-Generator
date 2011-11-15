<?php

Class Key_model extends CI_Model
{
	
	private $tbl= 'qr_redirects';

	function __construct()
	{
		parent::__construct();
	}
	
	// get number of urls in database
	public function count_all()
	{
		$this->db->where("redirect_status",1);
		$count = $this->db->get($this->tbl);
		
		return $count->num_rows();
	}
	
	// get url with paging
	public function get_paged_list($limit = 10, $offset = 0, $sort = "desc", $redirect_id = NULL){
		
		$this->db->order_by('id',$sort);
		$this->db->where("redirect_status",1);
		$result_sql = $this->db->get($this->tbl, $limit, $offset);
		return $result_sql;
		
	}
	
	public function get_new_codes()
	{
		
		$this->db->order_by('id',"desc");
		$this->db->where("redirect_status",1);
		$this->db->limit(10);
		$result_sql = $this->db->get($this->tbl);
		return $result_sql;
	}
	
	
	public function get_details($key)
	{
		$key_details = $this->db->query("SELECT id,redirect_url, redirect_key, redirect_notes, redirect_date_created FROM qr_redirects WHERE redirect_key=? AND redirect_status=1",array($key));
		
		if($key_details->num_rows() > 0)
		{
			return array(
				"id" => $key_details->row("id"),
				"redirect_url" => $key_details->row("redirect_url"),
				"redirect_notes" => $key_details->row("redirect_notes"),
				"redirect_key" => $key_details->row("redirect_key"),
				"redirect_date_created" => $key_details->row("redirect_date_created"),
				"redirect_click_count"=>$this->get_click_count($key_details->row("id"))	
			);
		}
		else
		{
			return array();
		}
		
	}
	
	public function get_click_count($id)
	{
		
		$key_query = $this->db->query("SELECT id FROM qr_tracking WHERE tracking_redirect_id=?",array($id));
		return $key_query->num_rows();
		
	}
	
	public function get_url($key = NULL)
	{
		if($key == NULL)
		{
			return SITE_OWNER_URL;
		}
		else
		{
			$url_query = $this->db->query("SELECT id,redirect_url FROM qr_redirects WHERE redirect_key=? AND redirect_status=1",array($key));
			
			if($url_query->num_rows() == 0)
			{
				return SITE_OWNER_URL;	
			}
			else
			{
				/* Track the redirect */
					$user_ip = $this->input->ip_address();
					$user_user_agent = $this->agent->agent_string();
					$redirect_id = $url_query->row("id");
					$redirect_url = $url_query->row("redirect_url");
				
					$this->db->query("INSERT INTO qr_tracking (tracking_redirect_id,tracking_datetime,tracking_ip,tracking_user_agent) values (?,NOW(),?,?)",array($redirect_id,$user_ip,$user_user_agent));
				
				/* Return the corrsect url */
					return $redirect_url;
			}
			
		}
	}
	
	
	public function create_key($url = NULL, $redirect_notes = "")
	{
		if($url == NULL)
		{
			return "";
		}
		else
		{
			$key = $this->_get_key();
			$this->db->query("INSERT INTO qr_redirects (redirect_url,redirect_key,redirect_notes,redirect_date_created,redirect_status) values (?,?,?,NOW(),1)",array($url,$key,$redirect_notes));
			
			return $key;
		}
	}
	
	private function _get_key()
	{

	    $key = $this->_random_string(5);
	    $key_query = $this->db->query("SELECT id FROM qr_redirects WHERE redirect_key=? AND redirect_status=1",array($key));
	    
	    if($key_query->num_rows() == 0)
	    {
	    	return $key;
	    }
	    else
	    {
	    	return $this->_get_key();
	    }
    
    }
    
    private function _random_string($count = 10)
    {
    	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
	
	    srand((double)microtime()*1000000);
	
	    $i = 0;
	    $pass = '' ;
	
	    while ($i <= $count) {
	        $num = rand() % 33;
	        $tmp = substr($chars, $num, 1);
	        $pass = $pass . $tmp;
	        $i++;
	    }
	
	    return $pass;
    }
    
    
    public function count_all_days()
	{
		$count_query = $this->db->query("SELECT DISTINCT(date_format(tracking_datetime,'%M %d %Y')) FROM qr_tracking");
		return $count_query->num_rows();
	}

	public function get_paged_list_days($limit = 10, $offset = 0, $sort = "asc", $redirect_id = NULL)
	{
		
		$limit += 0;
		$offset += 0;
		
		$this->db->select("date_format(tracking_datetime,'%W, %M %d, %Y') AS tracking_datetime ,date_format(tracking_datetime,'%m/%d/%y') AS graph_date , COUNT(qr_tracking.id) AS click_count",FALSE);
		$this->db->from("qr_tracking");
		
		$this->db->_protect_identifiers = FALSE;
		$this->db->join("qr_redirects","qr_redirects.id = qr_tracking.tracking_redirect_id");
		$this->db->_protect_identifiers = TRUE;
		
		$this->db->where("redirect_status",1);
		
		if($redirect_id != NULL)
		{
			$this->db->where("tracking_redirect_id",$redirect_id);
		}
		
		$this->db->_protect_identifiers = FALSE;
		$this->db->group_by("date_format(tracking_datetime,'%m %d %Y')",FALSE);
		$this->db->_protect_identifiers = TRUE;
		
		$this->db->order_by("graph_date " . strtoupper($sort));
		$this->db->limit($limit,$offset);
		
		$query = $this->db->get();
		
		//echo($this->db->last_query());
		
		return $query;
	}
	
	
	public function delete($key)
	{
		$this->db->query("UPDATE qr_redirects SET redirect_status=0 WHERE redirect_key=?",array($key));
		return TRUE;
	}
	

}