<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spistools_lib {

	// WS URL
	private $isir_ws;
        
        private $isir_ws2;
	
	// The id of the event that was get as the last one
	private $lastgetId;
	
	// Maximum number of rows that occure in a single batch, used to recognize if the import is over
	public $maxrowsinonebatch;
	
	// Maximum number of full iterations allowed
	private $maxnumberofiterations;
	
	// Classname of the class which sets the disbaled row
	private $disabledclass;
	
	// Codeigniter instance
	private $CI;	
	
	/**
	* Sets values
	*/
	public function __construct() {
		$this->isir_ws = 'https://isir.justice.cz:8443/isir_ws/services/IsirPub001/getIsirPub0012?long_1='; //'https://isir.justice.cz:8443/isir_ws/services/IsirPub001/getIsirPub0012?long_1='; // 'http://localhost/isir/test/';
		$this->isir_ws2 = 'https://isir.justice.cz:8443/isir_public_ws/IsirWsPublicService?wsdl';
		$this->maxrowsinonebatch = 1000;
		$this->maxnumberofiterations = 4;
		$this->disabledclass = "posledniCislo";
		$this->CI =& get_instance();

		$this->CI->load->model('isir_model');
	}
	
	/**
	* Gets text content from td cell
	*/
	public function getText($item, $html, $keepmultisplaces = true) {
		$remove = array("&#13;", "\n", "\r");
		$ret = str_replace($remove, "", trim(strip_tags(html_entity_decode($html->saveXML($item)))));
		
		if ($keepmultisplaces == false) {
			$ret = $output = preg_replace('!\s+!', ' ', $ret);
		}
		
		return $ret;		
	}
	
	/**
	* Gets IC from td cell
	*/
	public function getIC($item, $html) {
		$ret = 0;
				
		$text = $this->getText($item, $html);
		preg_match_all('/([\d]+)/', $text, $match);
		if ((sizeof($match) > 0) && (sizeof($match[0]) > 0)) {
			$ret = $match[0][0];
		}
		
		return $ret;		
	}
	
	/**
	* Gets rodne cislo from td cell
	*/
	public function getRC($item, $html) {
		$ret = 0;
				
		$text = $this->getText($item, $html);		
		$parts = explode('/', $text);
		if (sizeof($parts) > 2) {
			$ret = trim($parts[0]) . trim($parts[1]);			
		}
		
		return $ret;
	}
	
	
	/**
	* Gets id value from the ciselnik ... if the value is not present it is saved and cached ciselnik is updated
	* @return id - id value from the DB, data - cached ciselnik
	*/
	public function getIdValue($data, $tablename, $idval, $nameval, $searched) {
            $searched = trim($searched);

            $ret = array(
                "data" => $data,
                "id" => -1
            );

            if (is_array($data)) {
                foreach($data as $i) {
                    if ($i[$nameval] == $searched) {
                        $ret['id'] = $i[$idval];
                        break;
                    }
                }			
            }

            if ($ret['id'] == -1) {
                $ret['id'] = $this->CI->isir_model->insert_into_table($tablename, array($nameval => $searched));
                $ret['data'] = $this->CI->isir_model->get_table_contents($tablename);
            }

            return $ret;
	}
	
	/**
	* Compares sourceA against sourceB
	* @return true if sourceB contains sourceA record false otherwise
	*/
	public function findInAnother($sourceA, $sourceB) {
		$ret = false;
		
		foreach ($sourceB as $i) {			
			if (
				($i['spis_id'] == $sourceA['spis_id']) &&
				($i['order_id'] == $sourceA['order_id']) && 
				($i['text_id'] == $sourceA['text_id']) && 
				($i['publishdate'] == $sourceA['publishdate']) &&
				($i['section_id'] == $sourceA['section_id']) &&
				($i['document'] == $sourceA['document'])
			) {
				$ret = true;
				break;
			}
		}
		
		return $ret;
	}
        
        /**
         * Gets new events from WS2 web service since id
         */
        public function getWs2Events($id) {
            $soapClient = new SoapClient($this->isir_ws2);
            $soapResult = $soapClient->getIsirWsPublicPodnetId(array("idPodnetu" => $id));

            return $soapResult->data;
        }
	
	/**
	* gets the list of new events which have to be considered
	* If method is not specified returns spis names only
	* If method ws returns entire xml parsed
	* @param id of the last update
	*/
	public function getNewEvents($lastupdate, $logtable) {
		$ret = array();		

		$numberofrows = $this->maxrowsinonebatch;
		$iterations = 0;

		// if we still have thousands of rows then carry on
		while (($numberofrows == $this->maxrowsinonebatch) && ($iterations < $this->maxnumberofiterations)) {
			$iterations++;

			// get the list of spises which need to be updated	
			$is_loaded = false;
			$results = array();

			$soapClient = new SoapClient($this->isir_ws2);
			$soapResult = $soapClient->getIsirWsPublicPodnetId(array("idPodnetu" => $lastupdate));

			$is_loaded = $soapResult->status->stav == 'OK';
			$this->CI->isir_model->update_row_in_table($logtable, 'id', 1, array("is_error" => !$is_loaded));
			if ($is_loaded == true && isset($soapResult->data) == true) {
				$results = $soapResult->data;
			}

			// mark attempt to database
			$attempt_data = array("try_date" => date("Y-m-d H:i:s"));
			if ($is_loaded == true) {
				$attempt_data['last_available_date'] = date("Y-m-d H:i:s");
			}

			$this->CI->isir_model->update_row_in_table('spis_lastavailable', 'id', 1, $attempt_data);

			// has the fetching failed, if so do nothing
			if ($is_loaded == true) {
				// now get the spis items and their identifiers (INS 0001/2012) and get their newest details
				foreach ($results as $i) {
					// this is ISIR ws fetching - get entire xml parsed
					array_push($ret, $i);
				}						

				$numberofrows = sizeof($results);
				if ($numberofrows > 0) {
					$lastupdate = $results[($numberofrows - 1)]->id;
				}
			}
		}
		$this->lastgetId = $lastupdate;
		return $ret;		
	}
	
	/**
	* Gets url content 
	*/
	public function getURL($url) {
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url);			
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 	
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		 					
	    $ret = curl_exec($ch);	
	    curl_close($ch);
				
		return $ret;
	}
	
	/**
	* last get ID 
	*/
	public function getLastgetId() {
		return $this->lastgetId;
	}

	/**
	* Gets zalozka offset (where the spis letter is)
	*/
	public function getzalozkaoffset($idname) {
		$ret = 8;
		if (strtoupper(substr($idname, 7, 1)) == 'P') {
			$ret = 9;
		}
		
		return $ret;		
	}
	
	/**
	* Gets disabled status from the table cell class on span is poslednicislo
	*/
	public function isDisabled($item, $html) {		
		$ret = false;
		
		$spans = $item->getElementsByTagName("span");
		if ($tds->length > 0) {
			$class = $spans->item(0)->getAttribute("class");
			$ret = ($class == $this->disabledClass);
		}		
		
		return $ret;		
	}
	
	public function search_preparerc($rc) {
		$ret = '';
		$temp = explode('/', trim($rc));

		foreach($temp as $i) {
			$ret = $ret . $i;
		}
		
		return $ret;
	}
	
	public function search_prepareic($ic) {
		$ret = (int)$ic;
		return $ret;
	}

	/**
	* Parses the spis data out of ths spis (INS 1111/2011)
	* @return numberyear, numberid
	*/
	public function parsespisdata($spisname, $checkitem) {
		$ret = array(
			"numberYear" => '',
			"numberId" => ''
		);
				
		if (($checkitem != '') && (strstr($checkitem, '/') != '')) {
			$ret['numberYear'] = substr(strstr($checkitem, '/'), 1);			
			$replace = array($spisname . ' ', "/". $ret['numberYear']);
			$ret['numberId'] = str_replace($replace, "", $checkitem);					
		}
		
		return $ret;
	}
	
	public function formatrc($rc) {
		return substr($rc, 0, 6) .'/'. substr($rc, 6);
	}
	
	public function formatspis($prefix, $id, $year) {
		return $prefix . ' ' . $id . '/' . $year;
	}
	
	public function formatspisdate($date) {
		return date("d.m.Y", strtotime($date));
	}
	
	/**
	* Inserts new events into the database and deletes the obsolete ones
	*/
	public function saveevents($spisevents, $dbevents) {
		// compare and find new events to be added
		$toadd = array();
		foreach($spisevents as $i) {
			$res = $this->findInAnother($i, $dbevents);
			if ($res == false) {
				array_push($toadd, $i);
			}
		}
		
		// compare and find events to be deleted
		$todelete = array();
		foreach($dbevents as $i) {
			$res = $this->findInAnother($i, $spisevents);
			if ($res == false) {
				array_push($todelete, $i['id']);
			}						
		}					
		
		// save new events into the database
		if (sizeof($toadd) > 0) {
			$this->CI->isir_model->insert_batch('spis_data', $toadd);						
		}
		
		// delete events from the database that are not present in the gathered data
		if (sizeof($todelete) > 0) {
			$this->CI->isir_model->delete_batch_by_id('spis_data', $todelete);						
		}		
	}
	
	/**
	* Gets the data of a letter in a section list passed
	* @return null if not found, id otherwise
	*/
	public function getsectiondata($sectionlist, $letter) {
		$ret = null;
		
		foreach ($sectionlist as $i) {
			if ($i['name'] == $letter) {
				$ret = $i;
				break;
			}
		}
		
		return $ret;
	}
	
	/**
	* Format the name of the section from the passed data to the front end format
	*/
	public function formatsection($sectiondata) {
		return 'Oddíl '. $sectiondata['name'] .' - '. $sectiondata['description'];
	}
	
	/**
	* Formats the address (removes the map text)
	*/
	public function removemapfromaddress($str) {
		return str_replace(' (Zobrazit na mape)', '', trim($str));
	}
	
	/**
	* Removes slash from the RC
	*/
	public function mergerc($rc) {
		$ret = '';
		$parts = explode('/', $rc);
		foreach ($parts as $i) {
			$ret = $ret . $i;
		}
		
		return $ret;
	}
	
	public function showIcOrRc($ic, $rc) {
		$ret = '';
		if (($rc != '') && ($rc != '0')) {
			$ret = $this->formatrc($rc);
		} else if ($ic != '') {
			$ret = $ic;
		}					
		
		return $ret;
	}
	
	public function showIcSlashRc($ic, $rc) {
		$ret = '';

		if (($ic != '') && ($ic != '0')) {
			$ret = $ic;
		}					
		
		if (($rc != '') && ($rc != '0')) {
			if ($ret != '') {
				$ret .= ' / ';
			}
			
			$ret .= $this->formatrc($rc);
		} 

		return $ret;
	}

}

/* End of file daz_image.php */
/* Location: ./system/application/libraries/daz_image.php */