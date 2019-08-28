<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base_controller.php';

class Register extends Base_controller {

	// The file where the terms and conditions are located
	private $termsfile;

	function __construct() {
		parent::__construct();

		$this->termsfile = dirname(__FILE__) . '/../../files/podminky-uzivani-sluzby.pdf';

		$this->load->model('common_model');

		$this->load->library('form_validation');
		$this->load->library('emailing_lib');
        $this->load->library('user_lib');
        
        $this->load->helper('subject_helper');
	}

	public function index()
	{
		$data['google_login_url']=$this->google->get_login_url();
		$data['title'] = 'Registrace';

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('register/index', $data);
		$this->load->view('inc/footer');
	}

	public function monitoring() {
		$data['title'] = 'Monitoring rejstříků';

		$data['registerId'] = $this->common_model->generate_register_id(true);
		$data['register_session'] = $this->common_model->load_register_session($data['registerId']);

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('register/monitoring', $data);
		$this->load->view('inc/footer');
	}

	public function userdata($source, $registerId = null) {
		if ($registerId == null) {
			$registerId = $this->session->userdata($this->config->item('REGISTRATION_SESSION'));

			if ($registerId == null) {
				$registerId = $this->common_model->generate_register_id(false);
				$this->session->set_userdata($this->config->item('REGISTRATION_SESSION'), $registerId);
			}
		}

		$data['register_session'] = $this->common_model->load_register_session($registerId);
		if ($data['register_session'] == null) {
			show_404();
			return;
		}
	
		$data['title'] = '';
		if ($source == $this->config->item('WATCH_SOURCE_JUSTDATA')) {
			$data['title'] = 'Registrace';
		} else if ($source == $this->config->item('WATCH_SOURCE_SERVIS')) {
			$data['title'] = 'Rejstřikový servis';
		} else if ($source == $this->config->item('WATCH_SOURCE_MONITORING')) {
			$data['title'] = 'Monitoring rejstříků';
		}

		$data['payment_frequencies'] = $this->common_model->get_payment_frequencies();
		$year_frequency = null;
		foreach ($data['payment_frequencies'] as $value) {
			if ($value['months'] == 12) {
				$year_frequency = $value;
				break;
			}
		}

		if ($data['register_session']['discount_year'] == true) {
			$data['payment_frequencies'] = array($year_frequency);
		}

		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
			$this->form_validation->set_rules('name', 'Obchodní firma / jméno a příjmení', 'trim|required|max_length[100]');
			$this->form_validation->set_rules('ico', 'IČ', 'trim|numeric');
			$this->form_validation->set_rules('representedby', 'Zastoupen/jednající', 'trim|required|max_length[100]');

			$caknumber_rules = 'trim|min_length[4]|max_length[6]';
			if ($data['register_session']['discount_lawyer'] == true) {
				$caknumber_rules .= '|required';
			}
			
			$this->form_validation->set_rules('caknumber', 'Evidenční číslo advokáta', $caknumber_rules);

			$this->form_validation->set_rules('address', 'Ulice, č.p.', 'trim|required|max_length[100]');
			$this->form_validation->set_rules('city', 'Město', 'trim|required|max_length[50]');
			$this->form_validation->set_rules('zip', 'PSČ', 'trim|required|max_length[10]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]|is_unique[users.email]');
			$this->form_validation->set_rules('phone', 'Telefon', 'trim|required|max_length[14]');
			$this->form_validation->set_rules('password', 'Heslo', $this->config->item('PASSWORD_VALIDATION_CRITERIA'));
			$this->form_validation->set_rules('password2', 'Heslo (potvrzení)', 'trim|required');

			$this->form_validation->set_rules('type_id', 'Objednatel (fyzická/právnická osoba)', '');
			$this->form_validation->set_rules('dic', 'DIČ', 'trim');
			$this->form_validation->set_rules('faktura_address', 'Ulice, č.p.', 'trim');
			$this->form_validation->set_rules('faktura_city', 'Město', 'trim');
			$this->form_validation->set_rules('faktura_zip', 'PSČ', 'trim');
			$this->form_validation->set_rules('payment_frequency_id', 'Frekvence platby', '');
			$this->form_validation->set_rules('note', 'Poznáka', 'trim');										
			$this->form_validation->set_rules('has_invoice_same', '', '');

			if ($this->form_validation->run()) {
				// create the user
				$user = $_POST;
				unset($user['send']);
				unset($user['password2']);
				unset($user['has_invoice_same']);

				$user = array_merge($user, $data['register_session']);

				unset($user['id']);
				unset($user['account']);
				unset($user['date']);

				if ($data['register_session']['discount_year'] == true || !isset($user['payment_frequency_id'])) {
					$user['payment_frequency_id'] = $year_frequency['id'];
				}

				// send the first invoice after defined amount of days (trial period which is free of charge)
				$payment = $this->common_model->get_payment_frequency($user['payment_frequency_id']);
				$trial_plus_invoice_advance_days = $this->config->item('TRIAL_LENGTH_DAYS') + $this->config->item('SEND_INVOICE_BEFORE_DAYS');

				$user['invoice_has_automatic'] = 1;
				$user['is_trial'] = 1;
				$user['invoice_interval_date'] = date(
					'Y-m-d H:i:s', 
					strtotime('-'. $payment->months .' months' . ' +'. $trial_plus_invoice_advance_days .' days'));

				// activate the account immediately
				$user['status_id'] = $this->config->item('USER_ACTIVE');

				// save the invoice address
				if ($this->input->post('has_invoice_same') == '1') {
					// set the office one
					unset($user['faktura_address']);
					unset($user['faktura_city']);
					unset($user['faktura_zip']);
				}
				
				// create new user
				$user['id'] = $this->common_model->add_user($user);

				// send the email with terms and conditions
				$maildata = array(
					"[email]" => $this->input->post('email'),
					"[name]" => $this->input->post('name'),
					"[ico]" => $this->input->post('ico'),
					"[dic]" => $this->input->post('dic'),
					"[representedby]" => $this->input->post('representedby'),
					"[address]" => $this->input->post('address') . ', '. $this->input->post('postal') .' '. $this->input->post('city'),
					"[phone]" => $this->input->post('phone'),
					"[maxsubjects]" => $user['max_subjects'],
					"[monthprice]" => $this->user_lib->calculateMonthPrice($user),
					"[payment_frequency]" => $payment->name,
					"[listOfOrderedServices]" => implode(', ', $this->user_lib->buildserviceslist($user))
				);

				// invoice address for the email
				if ($this->input->post('faktura_address') == '') {
					$maildata['[address_invoice]'] = $this->input->post('address') . ', '. $this->input->post('postal') .' '. $this->input->post('city');
				} else {
					$maildata['[address_invoice]'] = $this->input->post('faktura_address') . ', '. $this->input->post('faktura_zip') .' '. $this->input->post('faktura_city');
				}

				$attachments = array($this->termsfile);

				$email_id = $this->config->item('EMAIL_ACTIVATEPAID');
				$stage = 'DONE_PAID';

				$this->emailing_lib->emailUser($this->input->post('email'), $email_id, $maildata, $attachments);	

				// show the ok message
				$data['showmessage'] = true;
			} else {
				$data['showvalidation'] = true;
			}
		}

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('register/userdata', $data);
		$this->load->view('inc/footer');
	}

	public function calculateprice($id) {
		$update_data = array(
			'account' => $this->input->post('account'),
			'max_subjects' => $this->config->item('USER_ACCOUNTS')[$this->input->post('account')]['subjects'],
			'is_insolvencewatch' => $this->input->post('is_insolvencewatch'),
			'is_claimswatch' => $this->input->post('is_claimswatch'),
			'is_orwatch' => $this->input->post('is_orwatch'),
			'is_likvidacewatch' => $this->input->post('is_likvidacewatch'),
			'is_orskwatch' => $this->input->post('is_orskwatch'),
			'is_vatdebtorswatch' => $this->input->post('is_vatdebtorswatch'),
			'is_accountchangewatch' => $this->input->post('is_accountchangewatch'),
			'discount_lawyer' => $this->input->post('discount_lawyer'),
			'discount_year' => $this->input->post('discount_year')
		);

		$pricedata = $this->common_model->calculateprice($update_data);

		$update_data = array_merge($update_data, $pricedata);
		unset($update_data['totalprice']);

		$this->common_model->save_register_session($id, $update_data);

		echo json_encode($pricedata);
	}

}