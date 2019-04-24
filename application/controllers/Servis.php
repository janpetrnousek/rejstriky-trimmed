<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base_controller.php';

class Servis extends Base_controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('emailing_lib');
        $this->load->library('form_validation');
        $this->load->library('user_lib');

        $this->load->model('or_model');
    }

    public function index()
    {
        $data['title'] = 'Rejstříkový servis';

        $this->load->view('inc/header', $data);
        $this->load->view('inc/header_common', $data);
        $this->load->view('servis/index', $data);
        $this->load->view('inc/footer');
    }

    public function create()
    {
        $data['title'] = 'Založení nové společnosti';
        $data['titleOverride'] = 'Rejstříkový servis';

        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
			if ($this->validate_contact()) {
                $services = array();
                if ($this->input->post('sro_basic') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_CREATE_SROBASIC'));
                }

                if ($this->input->post('sro_extra') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_CREATE_SROEXTRA'));
                }

                if ($this->input->post('osvc_to_sro') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_CREATE_OSCVTOSRO'));
                }

                if ($this->input->post('as_basic') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_CREATE_ASBASIC'));
                }

                if ($this->input->post('other') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_CREATE_OTHER') . ' - '. $this->input->post('other_text'));
                }

				$maildata = array(
					"[name]" => $this->input->post('name'),
					"[email]" => $this->input->post('email'),
                    "[phone]" => $this->input->post('phone'),
                    "[services]" => implode('<br>', $services)
				);

                $this->emailing_lib->emailUser($this->config->item('CONTACT_EMAIL'), $this->config->item('EMAIL_REGSERVICECREATE'), $maildata);

				$data['result'] = 'Formulář byl odeslán. Budeme Vás kontaktovat.';
            } else {
				$data['result'] = validation_errors('<p class="validation_error">', '</p>');
            }
        }

        $this->load->view('inc/header', $data);
        $this->load->view('inc/header_common', $data);
        $this->load->view('servis/create', $data);
        $this->load->view('inc/footer');
    }

    public function change()
    {
        $data['title'] = 'Změna údajů v obchodním rejstříku';
        $data['titleOverride'] = 'Rejstříkový servis';

        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
			if ($this->validate_contact()) {
                $services = array();
                if ($this->input->post('alter_address') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_ALTER_ADDRESS'));
                }

                if ($this->input->post('alter_member') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_ALTER_MEMBER'));
                }

                if ($this->input->post('alter_prokura') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_ALTER_PROKURA'));
                }

                if ($this->input->post('alter_agreement') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_ALTER_AGREEMENT'));
                }

                if ($this->input->post('other') == 1) {
                    array_push($services, $this->config->item('REGSERVIS_ALTER_OTHER') . ' - '. $this->input->post('other_text'));
                }

				$maildata = array(
					"[name]" => $this->input->post('name'),
					"[email]" => $this->input->post('email'),
                    "[phone]" => $this->input->post('phone'),
                    "[company]" => 'IČ: '. $this->input->post('company_ic') .'; název: '. $this->input->post('company_name'),
                    "[services]" => implode('<br>', $services)
				);

                $this->emailing_lib->emailUser($this->config->item('CONTACT_EMAIL'), $this->config->item('EMAIL_REGSERVICEALTER'), $maildata);

				$data['result'] = 'Formulář byl odeslán. Budeme Vás kontaktovat.';
            } else {
				$data['result'] = validation_errors('<p class="validation_error">', '</p>');
            }
        }

        $this->load->view('inc/header', $data);
        $this->load->view('inc/header_common', $data);
        $this->load->view('servis/change', $data);
        $this->load->view('inc/footer');
    }

    public function find() {
        $result = '';

		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $company = $this->or_model->search_by_ic($this->input->post('ic'));
            $result = $company != null ? $company['name'] : '';
        }

        echo $result;
    }

    private function validate_contact() {
        $this->form_validation->set_rules('name', 'Jméno a příjmení / funkce či pozice', 'trim|required');
        $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Telefon', 'trim|required');
        $this->form_validation->set_rules('form_agree', 'Souhlasím s obchodními podmínkami a beru na vědomí zpracování osobních údajů', 'trim|required');

        return $this->form_validation->run();
    }

}
