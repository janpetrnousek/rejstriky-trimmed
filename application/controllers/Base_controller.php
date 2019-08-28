<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Auth.php';

class Base_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('common_model');
        $this->load->library('google');
    }
    
  
    protected function ensureLoggedIn()
    {
        $user = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

        if (!is_array($user)) {
            redirect('prihlasit', 'refresh');
        }
    }

    protected function refreshSession($userId) 
    {
        $this->session->set_userdata($this->config->item('USER_LOGGED_SESSION'), $this->common_model->get_userinfo($userId));
        return $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));
    }

}
