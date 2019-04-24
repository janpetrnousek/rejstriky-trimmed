<?php
class MY_Form_validation extends CI_Form_validation {    

    function __construct($config = array()){
         parent::__construct($config);
    }
	 
    function antispamthousandword($str)
    {
        if (($str == 'TISÍC') || ($str == 'TISIC') || ($str == 'tisíc') || ($str == 'tisic') || ($str == 'Tisíc') || ($str == 'Tisic'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
	
    function validaterc($str) {
        // ignore slash "/" and space " " in RC
        $toreplace = array('/', ' ');
        $str = str_replace($toreplace, '', $str);

        return ((strlen($str) > 7) && (strlen($str) < 11));
    }
        
    public function validatewatch($str) {
        return !((($_POST['ic'] == '') || ($_POST['ic'] == '0')) 
            && (($_POST['rc'] == '') || ($_POST['rc'] == '0')) 
            && (($_POST['birthdate'] == '') || ($_POST['name'] == '')));
    }

	public function verifymysqldate($str) {
		$mysqldate = strtotime($str);
		return $mysqldate != FALSE
			? date("Y-m-d", $mysqldate) 
			: false;
	}

    /**
     * Valid Date (ISO format)
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function valid_date($str)
    {
    	$dotSplitter = ".";
        $dashSplitter = "-";
        if (preg_match("/([0-9]{1,2})". $dotSplitter ."([0-9]{1,2})". $dotSplitter ."([0-9]{4})/", $str) ) 
        {
            $arr = explode($dotSplitter, $str);    // splitting the array
            $arr_ok = sizeof($arr) > 2;
            $dd = $arr_ok ? $arr[0] : '';            // first element is day
            $mm = $arr_ok ? $arr[1] : '';              // second element is month
            $yyyy = $arr_ok ? $arr[2] : '';              // third element is year
            
            return (@checkdate($mm, $dd, $yyyy));
        } 
        else if (preg_match("/([0-9]{4})". $dashSplitter ."([0-9]{1,2})". $dashSplitter ."([0-9]{1,2})/", $str) ) 
        {
            $arr = explode($dashSplitter, $str);    // splitting the array
            $arr_ok = sizeof($arr) > 2;
            $yyyy = $arr_ok ? $arr[0] : '';              // first element is year
            $mm = $arr_ok ? $arr[1] : '';              // second element is month
            $dd = $arr_ok ? $arr[2] : '';            // third element is day
            
            return (@checkdate($mm, $dd, $yyyy));
        } 
        else 
        {
            return FALSE;
        }
    }	
    
    /**
     * Requires additional fields for subject (IC/RC, birthdate, city)
     */
    public function subjectrequireadditionalfields($str) {
        return $_POST['icrc'] != '' || $_POST['birthdate'] != '' || $_POST['city'] != '';
    }
}