<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_lib {

	// Codeigniter instance
	private $CI;
	
	public function __construct() {
        $this->CI =& get_instance();
        
        $this->CI->load->model('common_model');

        $this->CI->load->library('emailing_lib');
	}
	
	public function buildserviceslist($user) {
        $services_list = array();
        
        if ($user['is_insolvencewatch']) {
            array_push($services_list, $this->CI->config->item('SPIS_NOTIFICATION_NAME'));
        }

        if ($user['is_vatdebtorswatch']) {
            array_push($services_list, $this->CI->config->item('VATDEBTORS_NOTIFICATION_NAME'));
        }

        if ($user['is_likvidacewatch']) {
            array_push($services_list, $this->CI->config->item('LIKVIDACE_NOTIFICATION_NAME'));
        }

        if ($user['is_accountchangewatch']) {
            array_push($services_list, $this->CI->config->item('ACCOUNTS_NOTIFICATION_NAME'));
        }

        if ($user['is_claimswatch']) {
            array_push($services_list, $this->CI->config->item('CLAIMS_NOTIFICATION_NAME'));
        }
		
        if ($user['is_orwatch']) {
            array_push($services_list, $this->CI->config->item('OR_NOTIFICATION_NAME'));
        }

        if ($user['is_orskwatch']) {
            array_push($services_list, $this->CI->config->item('ORSK_NOTIFICATION_NAME'));
        }

        if ($user['discount_lawyer']) {
            array_push($services_list, 'Sleva - advokátní nebo insolvenční kancelář');
        }

        if ($user['discount_year']) {
            array_push($services_list, 'Sleva - roční platba');
        }
        
        return $services_list;
	}
	
	public function calculateMonthPrice($user) {
        $result = 0;
        
        if ($user['is_insolvencewatch']) {
            $result = $result + $user['price_insolvencewatch'];
        }

        if ($user['is_vatdebtorswatch']) {
            $result = $result + $user['price_vatdebtorswatch'];
        }

        if ($user['is_likvidacewatch']) {
            $result = $result + $user['price_likvidacewatch'];
        }

        if ($user['is_accountchangewatch']) {
            $result = $result + $user['price_accountchangewatch'];
        }

        if ($user['is_claimswatch']) {
            $result = $result + $user['price_claimswatch'];
        }
		
        if ($user['is_orwatch']) {
            $result = $result + $user['price_orwatch'];
        }

        if ($user['is_orskwatch']) {
            $result = $result + $user['price_orskwatch'];
        }

        if ($user['discount_lawyer']) {
            $result = $result - $user['price_discount_lawyer'];
        }

        if ($user['discount_year']) {
            $result = $result - $user['price_discount_year'];
        }

        return $result;
    }
    
    public function has_paid_program($user) {
        return $user['is_insolvencewatch'] == true
            || $user['is_vatdebtorswatch'] == true
            || $user['is_likvidacewatch'] == true 
            || $user['is_accountchangewatch'] == true 
            || $user['is_claimswatch'] == true
            || $user['is_orwatch'] == true
            || $user['is_orskwatch'] == true;
    }

    public function deleteuser($user) {
		$daySeconds = 60 * 60 * 24;
		$trialSeconds = $this->CI->config->item('TRIAL_LENGTH_DAYS') * $daySeconds;
		$isFree = (time() - $trialSeconds) < strtotime($user['createdate']);

		// delete user
		if ($isFree) {
			$this->CI->common_model->delete_user($user['id']);
			$this->CI->emailing_lib->emailUser($user['email'], $this->CI->config->item('EMAIL_DELETEDACCOUNTFREE'), array());
		} else {
			$this->CI->common_model->request_delete_user($user['id']);
			$this->CI->emailing_lib->emailUser($user['email'], $this->CI->config->item('EMAIL_DELETEDACCOUNTPAID'), array());
        }
        
        return $isFree;
    }
	
}

/* End of file daz_image.php */
/* Location: ./system/application/libraries/daz_image.php */