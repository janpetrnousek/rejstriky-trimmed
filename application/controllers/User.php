<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base_controller.php';

class User extends Base_controller {

	function __construct() {
		parent::__construct();

		$this->load->model('common_model');

        $this->load->library('emailing_lib');
		$this->load->library('form_validation');
		$this->load->library('user_lib');

		$this->load->helper('string');
	}

	public function login()
	{
		$data['title'] = 'Přihlásit';

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $result = $this->common_model->login($this->input->post('email'), $this->input->post('password'));

            if (is_array($result)) {
                // set the session and redirect to the user account
                $this->session->set_userdata($this->config->item('USER_LOGGED_SESSION'), $this->common_model->get_userinfo($result['id']));
                redirect('zpet-do-uctu', 'refresh');
            } else {
                // show the rejection screen
                switch ($result) {
                    case 0:
                        $data['result'] = 'Zkontrolujte své přihlašovací údaje.<br /><br />Pokud jste zapomněli své heslo pokračujte <a href="zapomenute-heslo">zde</a>.';
                        break;
					case $this->config->item('USER_DISABLED'):
                        $data['result'] = 'Váš účet není aktivován. Aktivujte ho prosím nebo nás <a href="kontakt">kontaktujte</a>.';
                        break;
					case $this->config->item('USER_DELETED'):
                        $data['result'] = 'Váš účet byl smazán. Pro více informací nás <a href="kontakt">kontaktujte</a>.';
                        break;
                }
            }
        }

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('user/login', $data);
		$this->load->view('inc/footer');
	}

	public function forgottenpassword() {
		$data['title'] = 'Zapomenuté heslo';
		$data['result'] = '';

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$result = $this->common_model->makeResetPasswordHash($this->input->post('email'));
			if ($result != false) {
				// send the email with password reset hash
				$maildata = array(
					"[restoreUrl]" => base_url() . 'obnovit-heslo/'. $result
				);
				$this->emailing_lib->emailUser($this->input->post('email'), $this->config->item('EMAIL_FORGOTTENPASS'), $maildata);

				// show the result
				$data['result'] = 'Instrukce pro obnovení hesla byly zaslány na Váš email.';
			} else {
				// display the error
				$data['result'] = 'Zadaný účet neexistuje.';
			}
		}

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('user/forgottenpassword', $data);
		$this->load->view('inc/footer');
	}

	public function resetpassword($hash) {
		$data['title'] = 'Obnovit heslo';

		$data['result'] = '';
		$data['isResetAllowed'] = false;

		$resetPasswordHashRow = $this->common_model->getValidResetPasswordHash($hash);
		if ($resetPasswordHashRow != null) {
			$data['isResetAllowed'] = true;

			if ($this->input->server('REQUEST_METHOD') == 'POST') {
				$this->form_validation->set_rules('password', 'Nové heslo', $this->config->item('PASSWORD_VALIDATION_CRITERIA'));
				$this->form_validation->set_rules('password2', 'Nové heslo (potvrzení)', 'trim|required');

				if ($this->form_validation->run()) {
					$this->common_model->updatepassword($resetPasswordHashRow->user_id, $hash, $this->input->post('password'));
					$data['result'] = 'Heslo bylo úspěšne změneno.';
				} else {
					$data['result'] = validation_errors('<p class="validation_error">', '</p>');				
				}
			}
			
		} else {
			$data['result'] = 'Neplatný link pro obnovu hesla.';
		}

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('user/resetpassword', $data);
		$this->load->view('inc/footer');
	}

	public function logout() {
		// unset the users session
		$this->session->unset_userdata($this->config->item('USER_LOGGED_SESSION'));

		// redirect to the home page
		redirect(base_url());
	}

	public function changeaccount() {
		$this->ensureLoggedIn();

		$data['title'] = 'Změnit / zrušit účet';
		$data['titleOverride'] = 'Monitoring rejstříků';

		$data['registerId'] = $this->common_model->generate_register_id(true);
		$data['register_session'] = $this->common_model->load_register_session($data['registerId']);

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('user/changeaccount', $data);
		$this->load->view('inc/footer');
	}

	public function changeaccountconfirmed($registerId) {
		$this->ensureLoggedIn();

		$data['title'] = 'Změnit účet';
		$data['titleOverride'] = 'Monitoring rejstříků';

		$user = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		$data['register_session'] = $this->common_model->load_register_session($registerId);

		$maildata = array(
			"[userName]" => $user['email'],
			"[oldPrice]" => $this->user_lib->calculateMonthPrice($user) . ' Kč',
			"[oldParams]" => 'počet subjektů: '. $user['max_subjects'] .'<br>'. implode('<br>', $this->user_lib->buildserviceslist($user)),
			"[newPrice]" => $this->user_lib->calculateMonthPrice($data['register_session']) . ' Kč',
			"[newParams]" => 'počet subjektů: '. $data['register_session']['max_subjects'] .'<br>'. implode('<br>', $this->user_lib->buildserviceslist($data['register_session']))
		);

		$this->emailing_lib->emailUser($this->config->item('CONTACT_EMAIL'), $this->config->item('EMAIL_CHANGEACCOUNTSERVICEMSG'), $maildata);

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('user/changeaccountconfirmed', $data);
		$this->load->view('inc/footer');
	}

	public function deleteaccount($hash) {
		$data['title'] = 'Zrušit účet';
		$data['titleOverride'] = 'Monitoring rejstříků';

        // load user data from hash
        $data['userdata'] = $this->common_model->get_user_by_hash($hash);
        if ($data['userdata'] == null) {
			show_404();
        }

		// delete user
		$data['isFree'] = $this->user_lib->deleteuser($data['userdata']);

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('user/deleteaccount', $data);
		$this->load->view('inc/footer');
	}

	public function deleteloggedaccount() {
		$this->ensureLoggedIn();

		$data['title'] = 'Zrušit účet';
		$data['titleOverride'] = 'Monitoring rejstříků';

		// load user data
		$user = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		// delete user
		$data['isFree'] = $this->user_lib->deleteuser($user);

		// logout user
		$this->session->unset_userdata($this->config->item('USER_LOGGED_SESSION'));

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('user/deleteaccount', $data);
		$this->load->view('inc/footer');
	}

	public function accounthome() {
		$user = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		if (!is_array($user)) {
			redirect('pages/index', 'refresh');
		} else {
			if ($this->user_lib->has_paid_program($user)) {
				redirect('monitoring-rejstriku/sledovane', 'refresh');
			} else {
				redirect('rejstrikovy-servis/zmena', 'refresh');
			}
		}
	}

}
