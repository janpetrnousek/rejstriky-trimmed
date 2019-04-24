<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
  * Responsible for importing the data
  */
class Import_lib {

    // Codeigniter instance
    private $CI;

    public function __construct() {
        $this->CI =& get_instance();

        $this->CI->load->model('isir_model');

        $this->CI->load->library('form_validation');
    }
    
    /**
     * Gets data for CSV import from file
     */
    public function getcsvdata($file_path) {
        return $this->getcsvdata_internal(
            $file_path, 
            ",", 
            function(&$new_item, &$new_item_array) {
                $new_item->name = isset($new_item_array[0]) ? $new_item_array[0] : "";
                $new_item->firstname = isset($new_item_array[1]) ? $new_item_array[1] : "";
                $new_item->ic = isset($new_item_array[2]) ? $new_item_array[2] : 0;
                $new_item->rc = isset($new_item_array[3]) ? $new_item_array[3] : 0;
                $new_item->birthdate = isset($new_item_array[4]) ? $new_item_array[4] : "";
                $new_item->clientname = isset($new_item_array[5]) ? $new_item_array[5] : "";
                $new_item->note = isset($new_item_array[6]) ? $new_item_array[6] : "";
            });
    }

    /**
     * Gets data for CSV import from file generated from IS Ginis
     */
    public function getcsvdata_ginis($file_path) {
        return $this->getcsvdata_internal(
            $file_path, 
            ";", 
            function(&$new_item, &$new_item_array) {
                $new_item->name = isset($new_item_array[9]) ? $new_item_array[9] : "";
                $new_item->firstname = "";
                $new_item->ic = isset($new_item_array[7]) ? $new_item_array[7] : 0;
                $new_item->rc = isset($new_item_array[8]) ? $new_item_array[8] : 0;
                $new_item->birthdate = "";
                $new_item->clientname = "";
                $new_item->note = "";
            },
            "cp1250");
    }

    /**
     * Gets data for CSV import from file generated from Firma/IČ format
     */
    public function getcsvdata_firmaic($file_path) {
        return $this->getcsvdata_internal(
            $file_path, 
            ";;", 
            function(&$new_item, &$new_item_array) {
                $new_item->name = isset($new_item_array[0]) ? trim($new_item_array[0]) : "";
                $new_item->firstname = isset($new_item_array[1]) ? trim($new_item_array[1]) : "";;
                $new_item->ic = isset($new_item_array[2]) ? trim($new_item_array[2]) : 0;
                $new_item->rc = isset($new_item_array[3]) ? trim($new_item_array[3]) : 0;;
                $new_item->birthdate = "";
                $new_item->clientname = "";
                $new_item->note = "";
            },
            "cp1250",
            false);
    }
	
    /**
    * Validates user xls file
    */	
    public function validate($data) {	
        $ret['errors'] = array();

        foreach ($data as $item) {
            if (!$item) {
                continue;
            }
		
            if (!isset($item->rc) AND !isset($item->ic) AND !isset($item->birthdate)) {
                array_push($ret['errors'], array("id" => $item->rowNumber, "message" => "Identifikační údaj (IČ/RČ/datum narození) není vyplněn"));
                continue;
            }
			
            $item->ic = ((isset($item->ic)) && ($item->ic != NULL)) 
                ? trim($item->ic) 
                : 0;
            $item->rc = ((isset($item->rc)) && ($item->rc != NULL))
                ? trim($item->rc)
                : 0;
            $item->birthdate = ((isset($item->birthdate)) && ($item->birthdate != NULL))
                ? trim($item->birthdate)
                : 0;
			
            // ignore slash "/" and space " " in RC
            $toreplace = array('/', ' ');
            $item->rc = str_replace($toreplace, '', $item->rc);
			
            if (((is_numeric($item->ic) == FALSE) || ($item->ic == 0) || strlen($item->ic) > 8) 
                && ((is_numeric($item->rc) == FALSE) || ($item->rc == 0) || (strlen($item->rc) < 8) || strlen($item->rc) > 10)
                && (!$this->CI->form_validation->valid_date($item->birthdate))) 
            {
                array_push($ret['errors'], array("id" => $item->rowNumber, "message" => "Identifikační údaj (IČ/RČ/datum narození) není validní"));
                continue;
            }
        }

        $ret['result'] = sizeof($ret['errors']) == 0;
        return $ret;
    }
	
    /**
    * Imports the data
    */
    public function doimport($data, $usersess) {
        $ret = array(
            "message" => '',
            "result" => false
        );
		
        $watchcount = $usersess['numwatches'];

        foreach ($data as $item) {
            if (!$item) {
                continue;
            }
			
            if ($usersess['max_subjects'] <= $watchcount) {
                $ret['message'] = 'Dosáhli jste maximální počet sledovaných subjektů.';
                break;
            }

            // import it
            $item->ic = ($item->ic != NULL) ? trim($item->ic) : 0;
            $item->rc = ($item->rc != NULL) ? trim($item->rc) : 0;
            $item->birthdate = ($item->birthdate != NULL) ? trim($item->birthdate) : 0;
            $watchdata = array(
                "user_id" => $usersess['id'],
                "firstname" => trim($item->firstname),
                "name" => trim($item->name),
                "ic" => $item->ic,
                "rc" => $item->rc,
                "birthdate" => ($item->birthdate != '' && $item->birthdate != 0)
                    ? date("Y-m-d", strtotime($item->birthdate))
                    : '',
                "note" => isset($item->note) ? $item->note : null,
                "clientname" => isset($item->clientname) ? $item->clientname : null,
                "date_add" => date("Y-m-d H:i:s")				
            );
			
            // insert watch
            if (($watchdata['ic'] != 0) || ($watchdata['rc'] != 0) || (($watchdata['birthdate'] != 0) && ($watchdata['name'] != ''))) {
                $watch_id = $this->CI->isir_model->add_watch_item($watchdata, $item->name);
                if ($watch_id != 0) {
                    // increment watch count
                    $watchcount++;
                }
            }
        }			
		
        if ($ret['message'] == '') {
            $ret['result'] = true;
            $ret['message'] = 'Import dokončen.';
        }
		
        return $ret;					
    }	
    
    /**
     * Removes subjects that are in user account but are missing in import
     */
    public function deletemissing($data, $user_id) {
        $import_page_size = 500;
        
        // prior to insert, delete subjects which are not part of imported file
        $watches_ids_to_delete = array();

        $imported_ic = array();
        $imported_rc = array();
        $imported_birthdatename = array();
        foreach($data as $imported) {
            if (!$imported) {
                // skip initial empty records
                continue;
            }

            if ($imported->ic != 0 && is_numeric($imported->ic)) {
                $imported_ic[$imported->ic] = 1;
            }

            if ($imported->rc != 0 && is_numeric($imported->rc)) {
                $imported_rc[$imported->rc] = 1;
            }

            if ($imported->birthdate != '' && $imported->birthdate != '0000-00-00' && $imported->name != '') {
                $imported_birthdatename[$imported->birthdate . $imported->name] = 1;
            }
        }

        $can_load_more = true;
        $lastId = 0;
        while ($can_load_more) {
            $existingRecords = $controller->data['watches'] = $this->CI->isir_model->getimportcomparewatches(
                $user_id, 
                $lastId, 
                $import_page_size);

            $can_load_more = sizeof($existingRecords) == $import_page_size;
            if (sizeof($existingRecords) > 0) {
                $lastId = $existingRecords[sizeof($existingRecords) - 1]['id'];
            }

            // compare loaded records against imported
            foreach($existingRecords as $existing) {
                if (!isset($existing['ic'])) {
                    // skip somehow invalid records, there might be information about size only
                    continue;
                }

                $is_in_imported = isset($imported_ic[$existing['ic']])
                    || isset($imported_rc[$existing['rc']])
                    || isset($imported_birthdatename[$existing['birthdate'] . $existing['name']]);

                if (!$is_in_imported) {
                    array_push($watches_ids_to_delete, $existing['id']);
                }
            }
        }

        // we have the list of records to delete, now we can delete them
        while (sizeof($watches_ids_to_delete) > 0) {
            $this->CI->isir_model->deletewatches(
                array_splice($watches_ids_to_delete, 0, $import_page_size));
        }
        
    }

    private function getcsvdata_internal($file_path, $separator, $assignment, $encoding = null, $hasHeader = true) {
        $csv_data = file_get_contents($file_path);
        
        if ($encoding == null) {
            $csv_data = iconv(mb_detect_encoding($csv_data, mb_detect_order(), true), "utf-8//IGNORE", $csv_data);
        } else {
            $csv_data = iconv($encoding, "utf-8//IGNORE", $csv_data);
        }

        $lines = explode("\n", $csv_data);

        // remove head from the uploaded CSV
        if ($hasHeader) {
            array_shift($lines);
        }

        // fill data, make first 3 empty
        $data = array();
        array_push($data, null);
        array_push($data, null);
        array_push($data, null);

        // data start at row 2, row 1 is header
        $row = $hasHeader ? 1 : 0;
        foreach ($lines as $line) {
            $row++;

            $lineWithoutSeparators = str_replace($separator, "", $line);
            if (trim($line) == "" || trim($lineWithoutSeparators) == "") {
                // empty line or contains separators only, skip
                continue;
            }

            $new_item_array = str_getcsv($line, $separator);

            $new_item = new stdclass();
            $assignment($new_item, $new_item_array);
            $new_item->rowNumber = $row;

            array_push($data, $new_item);
        }
        
        return $data;
    }
}

/* End of file daz_image.php */
/* Location: ./system/application/libraries/daz_image.php */