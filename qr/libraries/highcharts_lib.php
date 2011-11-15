<?php

class Highcharts_lib{

	private $a_options;
	private $s_rendering;
	private $i_serie_index;

	function __construct(){
		$this->a_options=array();

		# Set Default Values
		$this->set_container_id('highchart_id');
		$this->set_graph_type('spline');
		$this->display_credit(FALSE);

		$this->i_serie_index = 0;
		$this->a_options['series'] = array();
	}

	function set_chart_otpions($option_name, $option_value){
		$option_name = trim($option_name);
		if($option_name != ''){
			if(is_array($option_value) && !empty($option_value)){
				$this->a_options['chart'][$option_name] = json_encode($option_value);
			}else{
				$this->a_options['chart'][$option_name] = trim($option_value);
			}
		}
		return $this;
	}

	function set_xlabels_otpions($option_name, $option_value){
		$option_name = trim($option_name);
		if($option_name != ''){
			if(is_array($option_value) && !empty($option_value)){
				$this->a_options['xAxis']['labels'][$option_name] = json_encode($option_value);
			}else{
				$this->a_options['xAxis']['labels'][$option_name] = trim($option_value);
			}
		}
		return $this;
	}

	function set_ylabels_otpions($option_name, $option_value){
		$option_name = trim($option_name);
		if($option_name != ''){
			if(is_array($option_value) && !empty($option_value)){
				$this->a_options['yAxis']['labels'][$option_name] = json_encode($option_value);
			}else{
				$this->a_options['yAxis']['labels'][$option_name] = trim($option_value);
			}
		}
		return $this;
	}

	/* Graph Options */
	/**
	 * Set the title option
	 *
	 * @param String $s_title
	 * @param String $align
	 * @param Integer $x
	 * @param Integer $y
	 * @param CSSObject $a_css
	 * @return unknown
	 */
	function set_graph_title($s_title='', $align='center', $x=0, $y=20, $a_css=array()){
		$this->a_options['title']['text'] = ($s_title=='') ? NULL : $s_title;
		$this->a_options['title']['align'] = $align;
		$this->a_options['title']['x'] = $x;
		$this->a_options['title']['y'] = $y;
		if(!empty($a_css)){
			$this->a_options['title']['style'] = json_encode($a_css);
		}
		return $this;
	}

	function set_graph_margin($top=50, $right=50, $bottom=70, $left=80){

		echo json_encode(array($top, $right, $bottom, $left));
		$this->a_options['chart']['margin'] = json_encode(array($top, $right, $bottom, $left));
		return $this;
	}

	function set_backgroundcolor($color='#000000'){
		if(trim($color)!= ''){
			$this->a_options['chart']['backgroundColor'] = $color;
		}
		return $this;
	}

	function display_shadow($bool=FALSE){
		if(is_bool($bool)!= ''){
			$this->a_options['chart']['shadow'] = $bool;
		}
		return $this;
	}

	function set_container_id($s_id=''){
		if(trim($s_id)!= ''){
			$this->a_options['chart']['renderTo'] = $s_id;
		}
		return $this;
	}

	function set_graph_type($s_type=''){
		if(trim($s_type)!= ''){
			$this->a_options['chart']['defaultSeriesType'] = $s_type;
		}
		return $this;
	}

	function toggle_legend($enabled=TRUE){
		if(is_bool($enabled)){
			$this->a_options['legend']['enabled'] = $enabled;
		}
		return $this;
	}

	function display_credit($enabled=TRUE){
		if(is_bool($enabled)){
			$this->a_options['credits']['enabled'] = $enabled;
		}
		return $this;
	}

	/* Axis Options */
	function set_xAxis($a_value=array(), $title=FALSE){
		if(is_array($a_value) && !empty($a_value)){
			$this->a_options['xAxis']['categories'] = $a_value;
		}
		if($title !== FALSE && trim($title) != ''){
			$this->a_options['xAxis']['title']['text'] = $title;
		}
		return $this;
	}

	function push_xAxis_value($value){
		if(trim($value)!= ''){
			$this->a_options['xAxis']['categories'][] = $value;
		}
		return $this;
	}

	function set_yAxis($a_value=array(), $title=FALSE){
		if(is_array($a_value) && !empty($a_value)){
			$this->a_options['yAxis']['categories'] = $a_value;
		}
		if($title !== FALSE && trim($title) != ''){
			$this->a_options['yAxis']['title']['text'] = $title;
		}
		return $this;
	}

	function push_yAxis_value($value){
		if(trim($value)!= ''){
			$this->a_options['yAxis']['categories'][] = $value;
		}
		return $this;
	}

	/* Series Options */
	function add_serie($s_serie_name='', $a_value=array(),$type=''){
		$s_serie_name = trim($s_serie_name);
		if($s_serie_name != '' && is_array($a_value)){
			$f=false;
			foreach($this->a_options['series'] as $index => $serie){
				if(strtolower($serie['name']) == strtolower($s_serie_name)){
					$f=$index;
					break;
				}
			}

			if( $f!==FALSE ){

				if($type!=='')$this->a_options['series'][$f]['type'] = $type;
				foreach($a_value as $value){
					$value = (is_numeric($value)) ? (float)$value : $value;
					$this->a_options['series'][$f]['data'][] = $value;
				}
			}else{
				if($type!=='')$this->a_options['series'][$this->i_serie_index]['type'] = $type;
				$this->a_options['series'][$this->i_serie_index]['name'] = $s_serie_name;
				foreach($a_value as $value){
					$value = (is_numeric($value)) ? (float)$value : $value;
					$this->a_options['series'][$this->i_serie_index]['data'][] = $value;
				}

				$this->i_serie_index++;
			}
		}
		return $this;
	}

	function set_serie($s_serie_name='', $a_value=array()){
		$s_serie_name = trim($s_serie_name);

		if($s_serie_name != '' && is_array($a_value)){
			$f=false;
			foreach($this->a_options['series'] as $index => $serie){
				if(strtolower($serie['name']) == strtolower($s_serie_name)){
					$f=$index;
					break;
				}
			}

			if( $f !== FALSE ){
				# Reset the serie
				$this->a_options['series']['data'][$f] = array();
				foreach($a_value as $value){
					$value = (is_numeric($value)) ? (float)$value : $value;
					$this->a_options['series'][$f]['data'][] = $value;
				}
			}else{
				$this->add_serie($s_serie_name, $a_value);
			}
		}
		return $this;
	}

	function set_serie_option($s_serie_name='', $s_option='', $value=''){
		# First we search the serie
		$s_serie_name = trim($s_serie_name);
		if($s_serie_name !== '' && $s_option!==''){
			$f=false;
			# We check that we have a serie with the name
			foreach($this->a_options['series'] as $index => $serie){
				if(strtolower($serie['name']) == strtolower($s_serie_name)){
					$f=$index;
					break;
				}
			}

			# If Yes
			if( $f !== FALSE ){
				$this->a_options['series'][$f][$s_option] = $value;
			}
		}
		return $this;

	}

	function push_serie_data($s_serie_name = '', $s_value=''){
		if($s_serie_name !== '' && $s_value !=='' ){
			$f=false;
			foreach($this->a_options['series'] as $index => $serie){
				//echo strtolower($serie['name'])." == ".strtolower($s_serie_name)."";
				if(strtolower($serie['name']) == strtolower($s_serie_name)){
					$f=$index;
					break;
				}
			}

			if( $f !== FALSE ){
				$s_value = (is_numeric($s_value)) ? (float)$s_value : $s_value;
				$this->a_options['series'][$f]['data'][] = $s_value;
			}else{
				$this->add_serie($s_serie_name, array($s_value));
			}
		}
		return $this;
	}

	/* Render */
	function render($width=FALSE,$height=FALSE,$div_id=FALSE){
		$width = ($width !== FALSE && is_numeric($width)) ? $width.'px' : '100%';
		$height = ($height !== FALSE && is_numeric($height)) ? $height.'px' : '100px';
		$div_id = ($div_id !== FALSE && trim($div_id) != '') ? $div_id : $this->a_options['chart']['renderTo'];
		$this->set_container_id($div_id);

		$this->s_rendering = '<script src="/assets/js/hc/highcharts.js" type="text/javascript"></script>';
		$this->s_rendering .= '<script>';
		$this->s_rendering .= '$(document).ready(function(){';
		$this->s_rendering .= 'var options = '.json_encode($this->a_options).';';
		$this->s_rendering .= 'var chart = new Highcharts.Chart(options);';
		$this->s_rendering .= '});';
		$this->s_rendering .= '</script>';
		$this->s_rendering .= '<div id="'.$this->a_options['chart']['renderTo'].'" style=""></div>';
		return $this->s_rendering;
	}

	function debug(){
		echo '<p><pre>';
		print_r($this->a_options);
		echo '</pre></p><br />';
	}
	
}