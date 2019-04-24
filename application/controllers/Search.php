<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base_controller.php';

class Search extends Base_controller {

    private $name_search_results_length = 40;
    private $address_search_results_length = 40;

	function __construct() {
        parent::__construct();

        $this->load->model('common_model');
        $this->load->model('or_model');
        $this->load->model('isir_model');
        $this->load->model('vat_model');
        $this->load->model('relations_model');

        $this->load->helper('subject_helper');
    }
    
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
            return;
        }

        $data = $this->make_search_object();
        $search_id = $this->common_model->save_search($data);

        redirect('/hledat/vysledky/'. $search_id, 'refresh');
    }

	public function results($id)
	{
        $search = $this->common_model->load_search($id);
        if (!$search) {
            show_404();
            return;
        }

        $data['title'] = 'Hledat';
        $data['law_forms'] = $this->common_model->load_law_forms();
        $data['search_id'] = $id;

        $data['search'] = $search;

		$this->load->view('inc/header', $data);
        $this->load->view('inc/header_search', $data);
        $this->load->view('search/results', $data);
		$this->load->view('inc/footer');
    }

    public function results_ajax($id, $source, $type) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
            return;
        }

        $search = $this->common_model->load_search($id);
        if (!$search) {
            show_404();
            return;
        }

        $ignoredIds = json_decode($this->input->post('ignoredIds'));
        $ignoredIcs = json_decode($this->input->post('ignoredIcs'));

        $this->search_ajax_internal($source, $type, $search, $ignoredIcs, $ignoredIds);
    }

    public function results_input_ajax() {
        // put array to object, inspired at: https://stackoverflow.com/a/1869147
        $search = json_decode(json_encode($this->make_search_object(), false));
        $this->search_ajax_internal($this->config->item('DETAIL_OBCHODNI'), 'like_exact', $search, array(), array());
    }

    public function results_relations_ajax($id, $type) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_404();
            return;
        }

        $search = $this->common_model->load_search($id);
        if (!$search) {
            show_404();
            return;
        }

        $this->search_relations_ajax_internal($search, $type, $ignoredIds);
    }

    public function results_relations_input_ajax() {
        // put array to object, inspired at: https://stackoverflow.com/a/1869147
        $search = json_decode(json_encode($this->make_search_object(), false));
        $this->search_relations_ajax_internal($search, 'like_exact', array());
    }

    private function make_search_object() {
        $law_form_id = $this->input->post('law_form_id', true);

        return array(
            'type_id' => $this->input->post('type', true),
            'name' => character_limiter_ex($this->input->post('name', true), $this->config->item('SEARCH_NAME_LENGTH')),
            'name_exact' => !!$this->input->post('name_exact', true),
            'ic' => character_limiter_ex($this->input->post('ic', true), $this->config->item('SEARCH_IC_LENGTH')),
            'rc' => character_limiter_ex($this->input->post('rc', true), $this->config->item('SEARCH_RC_LENGTH')),
            'law_form_id' => $law_form_id != false && $law_form_id != '0' && $law_form_id != '-1' ? $law_form_id : '',
            'address' => character_limiter_ex($this->input->post('address', true), $this->config->item('SEARCH_ADDRESS_LENGTH')),
            'spis_mark' => character_limiter_ex($this->input->post('spis_mark', true), $this->config->item('SEARCH_SPISMARK_LENGTH'))
        );
    }

    private function search_relations_ajax_internal($search, $type, $ignoredIds) {
        $result = $this->relations_model->search($search, $type, $ignoredIds);

        $aux_isir = $this->isir_model->get_for_result($result);
        $aux_or = $this->or_model->get_for_result($result);
        $aux_vat = $this->vat_model->get_for_result($result);

        foreach ($result as &$value) {
            $value['formattedIc'] = !!$value['ic'] ? formatIc($value['ic']) : 'neuvedeno';
            $value['screeningLink'] = makeScreeningLink($this->config->item('DETAIL_OBCHODNI'), $value);
            $value['link'] = makeRelationsLink($value);
            
            $value['address_short'] = character_limiter_ex($value['address'], $this->name_search_results_length);
            $value['name_short'] = character_limiter_ex($value['name'], $this->address_search_results_length);

            $value['isProblematic'] = isSubjectProblematic(
                $this->findByIc($aux_or, $value['ic']), 
                $this->findByIc($aux_vat, $value['ic']), 
                $this->findByIc($aux_isir, $value['ic']));
        }

        echo json_encode($result);
    }

    private function search_ajax_internal($source, $type, $search, $ignoredIds, $ignoredIcs) {
        $result = array();
        $aux_or = array();
        $aux_isir = array();
        $aux_vat = array();

        if ($source === $this->config->item('DETAIL_OBCHODNI')) {
            $result = $this->or_model->search($search, $type, $ignoredIds, $ignoredIcs);
            $aux_isir = $this->isir_model->get_for_result($result);
        } else if ($source === $this->config->item('DETAIL_ISIR')) {
            $result = $this->isir_model->search($search, $type, $ignoredIds, $ignoredIcs);
            $aux_or = $this->or_model->get_for_result($result);
        }

        $aux_vat = $this->vat_model->get_for_result($result);

        foreach ($result as &$value) {
            $value['formattedIc'] = !!$value['ic'] ? formatIc($value['ic']) : 'neuvedeno';
            $value['screeningLink'] = makeScreeningLink($source, $value);
            $value['link'] = $search->type_id == $this->config->item('SEARCH_SCREENING')
                ? $value['screeningLink']
                : makeGeneralDetailLink($source, $value);
            
            $value['address_short'] = character_limiter_ex($value['address'], $this->name_search_results_length);
            $value['name_short'] = character_limiter_ex($value['name'], $this->address_search_results_length);

            $value['isProblematic'] = false;
            $value_vat = $this->findByIc($aux_vat, $value['ic']);
            if ($source === $this->config->item('DETAIL_OBCHODNI')) {
                $value['isProblematic'] = 
                    isSubjectProblematic($value, $value_vat, $this->findByIc($aux_isir, $value['ic']));
            } else if ($source === $this->config->item('DETAIL_ISIR')) {
                $value['isProblematic'] = 
                    isSubjectProblematic($this->findByIc($aux_or, $value['ic']), $value_vat, $value);
            }
        }

        echo json_encode($result);
    }

    private function findByIc($results, $ic) {
        foreach ($results as $value) {
            if ($value['ic'] == $ic) {
                return $value;
            }
        }

        return false;
    }

}
