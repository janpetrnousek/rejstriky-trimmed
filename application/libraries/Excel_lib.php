<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Excel_lib
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->helper('file');

        require_once dirname(__FILE__) . '/../../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        require_once dirname(__FILE__) . '/../../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
    }

    public function csv_to_excel($csvstring, $exportdir, $fileprefix)
    {
        $tempdir = dirname(__FILE__) . '/../../' . $exportdir;
        $file = $fileprefix . md5(date("Y-m-d H:i:s")) . rand();
        $filename = $tempdir . $file . '.csv';
        write_file($filename, $csvstring);

        $objReader = PHPExcel_IOFactory::createReader('CSV');
        $objPHPExcel = $objReader->load($filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($tempdir . $file . '.xlsx');

        return $exportdir . $file . '.xlsx';
    }

    public function load_xls_as_objects($file, $extension) {

        $data = array();

        $objReader = PHPExcel_IOFactory::createReader($extension == '.xlsx' ? 'Excel2007' : 'Excel5');
        $objReader->setReadDataOnly(true);

        if ($objReader->canRead($file)) {
            $objPHPExcel = @$objReader->load($file);
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $cols = array();
            $i = 0;
            foreach ($objWorksheet->getRowIterator() as $row) {
                $skipRow = false;
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $item = null;

                if (++$i < 2) {
                    // header row
                    foreach ($cellIterator as $cell) {
                        //$cols[] = strtolower(text::strToAscii((string)$cell->getValue(), FALSE));
                    }
                    $cols = array("name", "firstname", "ic", "rc", "birthdate", "clientname", "note");
                } else {
                    $saveTimeZone = date_default_timezone_get();
                    date_default_timezone_set('UTC'); // PHP's date function uses this value!	          	
				  
                    $counter = 0;
                    $item = new stdclass();
                    $item->rowNumber = $i;
                    
                    foreach ($cellIterator as $cell) {
                        // check whether it is not 'old' Excel version where data started on row 4
                        $field = $cols[$counter];
                        $value = $cell->getValue();
                        if ($field == "name") {
                            if (($i == 2 && $value == "Firma nebo příjmení") 
                                || ($i == 3 && startsWith($value, "zadejte název firmy nebo"))) {
                                
                                $skipRow = true;
                                break;
                            }
                        }
                        
                        $item->$field = $value;

                        if ($field == "birthdate" && $item->$field != NULL && $item->$field != '') {
	                    // to parse date, try strtotime first, if this one succeeds, value is in date format, but if not try Excel date format then
                            if (!strtotime($item->$field)) {
                                $item->$field = PHPExcel_Shared_Date::ExcelToPHP($item->$field); // 1007596800 (Unix time)
                                $item->$field = date('d.m.Y', $item->$field); // 06.12.2001 (formatted date)	                  
                            }
                        }
					  
                        $counter++;
                        if ($counter >= sizeof($cols)) {
                            break;
                        }
                    }
	              
                    date_default_timezone_set($saveTimeZone);
                }

                if ($skipRow == false) {
                    $data[] = $item;
                }
            }
        }
		        
        return $data;
    }

    public function xl2timestamp($xl_date)
    {
        $excel_timestamp = $xl_date-25568; // 1970-01-01 is day 25567.
        $php_timestamp = mktime(0,0,0,1,$excel_timestamp,1970); // No, really - this works!
        $mysql_timestamp = date('Y-m-d', $php_timestamp); // Or whatever the format is.
        return $php_timestamp;
        
        //$timestamp = ($xl_date - 25569) * 86400;
        //return $timestamp;
    }

}

/* End of file daz_image.php */
/* Location: ./system/application/libraries/daz_image.php */
