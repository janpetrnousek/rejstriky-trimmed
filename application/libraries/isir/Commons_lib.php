<?php
class Commons_lib {

	public function geturlcontent($url) {
		$content = file_get_contents($url);
		
		if ($content === FALSE) {
			return FALSE;
		}
		
		// we have the data - lets parse
		$dom = new DOMDocument();
		@$dom->loadHTML($content);    
		$xpath = new DOMXPath($dom);
		
		return $xpath;
	}	
}

?>