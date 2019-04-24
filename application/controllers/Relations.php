<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'controllers/Base_controller.php';

class Relations extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('relations_model');

        $this->load->library('relations_lib');
        $this->load->library('form_validation');

        $this->load->helper('subject_helper');
    }

    public function subject($id, $referencedate = null)
    {
        $root = $this->relations_model->get_relation($id);
        if ($root == null) {
            show_404();
        }

        $data['search'] = new stdClass();
        $data['search']->name = character_limiter_ex($root['name'], $this->config->item('SEARCH_NAME_LENGTH') - 5);
        $data['search']->type_id = $this->config->item('SEARCH_RELATIONS');
    
        if ($referencedate != null) {
            $referencedate = substr($referencedate, 0, 2) .'.'. substr($referencedate, 2, 2) .'.'. substr($referencedate, 4);
            $referencedate = $this->form_validation->verifymysqldate($referencedate);
            if ($referencedate == FALSE) {
                $referencedate = date("Y-m-d");
            }
        }

        $data['root'] = $root;
        $data['graph_data'] = json_encode($this->relations_lib->create_graph_data($root, true, $referencedate));
        $data['relation_types'] = $this->relations_model->get_visible_relation_types();

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_search', $data);
		
        $this->load->view('relations/content', $data);
        $this->load->view('inc/footer', $data);
    }

    public function getchildren($id)
    {
        // check for reference date
        $referencedate = null;
        if ($this->input->post('referencedate') != false) {
            $this->form_validation->set_rules('referencedate', 'Datum', 'trim|verifymysqldate');

            if ($this->form_validation->run() == true) {
                $referencedate = $this->input->post('referencedate');
            }
        }

        $showrelationcolors = $this->input->post('showrelationcolors');

        $root = $this->relations_model->get_relation($id);

        echo json_encode($this->relations_lib->create_graph_data($root, false, $referencedate, $showrelationcolors));
    }

    public function getchangedates($id)
    {
        echo json_encode($this->relations_model->get_change_dates($id));
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
