<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base_controller.php';

class Pages extends Base_controller {

	function __construct() {
		parent::__construct();

		$this->load->model('common_model');
		$this->load->model('or_model');
	}

	public function index()
	{
		$data['new_companies'] = $this->or_model->get_newest();
		$data['law_forms'] = $this->common_model->load_law_forms();

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_search', $data);
		$this->load->view('home', $data);
		$this->load->view('inc/footer');
	}

	public function content() {
		$this->contentpage(uri_string());
	}

	public function notfound() {
		$this->contentpage('nenalezeno');
	}

	private function contentpage($uri) {
		$page = $this->common_model->load_page($uri);

		$data['title'] = $page->title;
		$data['content'] = $page->content;
		$data['law_forms'] = $this->common_model->load_law_forms();

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_search', $data);
		$this->load->view('content', $data);
		$this->load->view('inc/footer');
	}
}
