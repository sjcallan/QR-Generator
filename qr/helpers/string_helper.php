<?php


	function trim_string($string, $length)
	{
		if(strlen($string) > $length)
		{
			return substr($string,0,$length) . "…";
		}
		else
		{
			return $string;
		}
	}