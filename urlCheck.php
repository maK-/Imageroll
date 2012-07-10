<?php
/*
This class returns true or false as to whether a provided link is valid or not.
It seems to be correct for most cases.

--Usage--
include('urlcheck.php');
$urlcheck = new checkURL();

if($urlcheck->is_valid_url($url)){
	//do something;
}

*/

class urlCheck{
	
	function is_valid_url($url){ 
		if($this->str_starts_with(strtolower($url), 'http://localhost'))
			return false;

		//Scheme, User and password (optional), Hostname or IP,Port (optional),GET Query (optional)
		$urlregex = '^(https?|s?ftp\:\/\/)|(mailto\:)';
		$urlregex .= '([a-z0-9\+!\*\(\)\,\;\?&=\$_\.\-]+(\:[a-z0-9\+!\*\(\)\,\;\?&=\$_\.\-]+)?@)?';
		$urlregex .= "[a-z0-9\+\$_\-]+(\.[a-z0-9+\$_\-]+)+";
		$urlregex .= '(\:[0-9]{2,5})?';
		$urlregex .= '(\?[a-z\+&\$_\.\-][a-z0-9\;\:@\/&%=\+\$_\.\-]*)?';
		$match = '/'.$urlregex.'/i';
	  
		return preg_match($match, $url);
	}


	function str_starts_with($string, $niddle) {
      	return substr($string, 0, strlen($niddle)) == $niddle;
	}

}

?>


	
