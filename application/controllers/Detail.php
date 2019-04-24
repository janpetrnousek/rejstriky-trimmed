<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base_controller.php';

class Detail extends Base_controller {

	function __construct() {
        parent::__construct();

        $this->load->model('or_model');
        $this->load->model('vat_model');
        $this->load->model('common_model');
        $this->load->model('isir_model');
        $this->load->model('relations_model');

        $this->load->helper('subject_helper');
        $this->load->helper('or');

        $this->load->library('content_lib');
        $this->load->library('relations_lib');
    }
    
    public function obchodni($id) {
        $data['subject_or'] = $this->or_model->get_detail($id);

        if (!$data['subject_or']) {
            show_404();
            return;
        }

        $data['subject_vat'] = $this->vat_model->get_detail($id);
        $data['search'] = new stdClass();
        $data['search']->name = character_limiter_ex($data['subject_or']['name'], $this->config->item('SEARCH_NAME_LENGTH') - 5);
        $data['search']->type_id = $this->config->item('SEARCH_DATA');

        //TODO:: THIS LINK BELOW DOES NOT WORK, MAYBE COOKIE IS NEEDED
        $data['subject_vat']['link'] = isset($data['subject_vat']['dic'])
            ? 'https://adisreg.mfcr.cz/adistc/DphReg?id=1&pocet=1&fu=&OK=+Search+&ZPRAC=RDPHI1&dic='. $data['subject_vat']['dic']
            : '';
        $data['subject_isir'] = $this->isir_model->get_screening_by_ic($data['subject_or']['ic']);
        $data['subject_isir']['hasRecord'] = !!$data['subject_isir'];
        $data['subject_isir']['link'] = $data['subject_isir']['hasRecord'] ? makeIsirDetailLink($data['subject_isir']) : '';
        
        $data['subjectIsProblematic'] = isSubjectProblematic($data['subject_or'], $data['subject_vat'], $data['subject_isir']);

        $data['subject']['linkOr'] = get_or_uplny_link($data['subject_or']['justice_id']);
        $data['subject']['linkAres'] = 'http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_res.cgi?ico='. $data['subject_or']['ic'] .'&jazyk=cz&xml=1';

        $data['title'] = $data['subject_or']['name'];
        $data['law_forms'] = $this->common_model->load_law_forms();
        
        $data['root'] = $this->relations_model->get_relation_by_ic($data['subject_or']['ic']);
        $data['graph_data'] = json_encode($this->relations_lib->create_graph_data($data['root'], true));
        $data['relation_types'] = $this->relations_model->get_visible_relation_types();

        $data['subject_or']['statutars'] = $this->relations_model->get_statutars($data['subject_or']['ic']);

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_search', $data);

        $this->load->view('detail/obchodni', $data);

        $this->load->view('inc/footer');
    }

    public function isir($id) {
        $data['subject_isir'] = $this->isir_model->get_detail($id);

        if (!$data['subject_isir']) {
            show_404();
            return;
        }

        $data['search'] = new stdClass();
        $data['search']->name = character_limiter_ex($data['subject_isir']['name'], $this->config->item('SEARCH_NAME_LENGTH') - 5);
        $data['search']->type_id = $this->config->item('SEARCH_DATA');

        $data['subject_isir']['hasRecord'] = true;	
        $data['subject_isir']['supervisors'] = $this->isir_model->getspissupervisors($id);	
        $data['subject_isir']['link'] = makeIsirDetailLink($data['subject_isir']);

        $data['subject_vat'] = array();
        $data['subject_vat']['isPayer'] = false;
        if (isIcDefined($data['subject_isir']['ic'])) {
            $data['subject_vat'] = $this->vat_model->get_detail_by_ic($data['subject_isir']['ic']);

            //TODO:: THIS LINK BELOW DOES NOT WORK, MAYBE COOKIE IS NEEDED
            $data['subject_vat']['link'] = isset($data['subject_vat']['dic'])
                ? 'https://adisreg.mfcr.cz/adistc/DphReg?id=1&pocet=1&fu=&OK=+Search+&ZPRAC=RDPHI1&dic='. $data['subject_vat']['dic']
                : '';
        }

        $data['subject_or'] = array();
        if (isIcDefined($data['subject_isir']['ic'])) {
            $data['subject_or'] = $this->or_model->get_screening_by_ic($data['subject_isir']['ic']);

            $data['subject']['linkOr'] = get_or_uplny_link($data['subject_or']['justice_id']);
            $data['subject']['linkAres'] = 'http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_res.cgi?ico='. $data['subject_or']['ic'] .'&jazyk=cz&xml=1';
        }
        
        $data['subjectIsProblematic'] = isSubjectProblematic($data['subject_or'], $data['subject_vat'], $data['subject_isir']);

        $data['title'] = $data['subject_isir']['name'];
        $data['law_forms'] = $this->common_model->load_law_forms();
        
        if (isset($data['subject_or']['ic'])) {
            $data['root'] = $this->relations_model->get_relation_by_ic($data['subject_or']['ic']);
            $data['graph_data'] = json_encode($this->relations_lib->create_graph_data($data['root'], true));
            $data['relation_types'] = $this->relations_model->get_visible_relation_types();
        }

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_search', $data);
        $this->load->view('detail/isir', $data);
        $this->load->view('inc/footer');
    }

    public function isironly($id) {
        $data['subject_isir'] = $this->isir_model->get_detail($id);

        if (!$data['subject_isir']) {
            show_404();
            return;
        }

        $data['search'] = new stdClass();
        $data['search']->name = character_limiter_ex($data['subject_isir']['name'], $this->config->item('SEARCH_NAME_LENGTH') - 5);
        $data['search']->type_id = $this->config->item('SEARCH_DATA');

        $data['subject_isir']['supervisors'] = $this->isir_model->getspissupervisors($id);	
        $data['isir_id'] = $id;

        $data['title'] = $data['subject_isir']['name'];
		$data['law_forms'] = $this->common_model->load_law_forms();
		$data['isir_sections'] = $this->isir_model->get_isir_sections();

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_search', $data);
        $this->load->view('detail/isirdetail', $data);
        $this->load->view('inc/footer');

    }

    public function isirsection($id, $sectionid, $order = '') {
        $data['events'] = $this->isir_model->getspisevents($id, $sectionid);
        $data['common'] = $this->isir_model->getspiscommon($id);
        $data['order'] = $order;
        $data['isSpisPrihlaskySection'] = $sectionid == $this->config->item('SECTION_POHLEDAVKY');

        $this->load->view('detail/sections/isirtable', $data);
    }

    public function screening($source, $id) {
        if ($source === $this->config->item('DETAIL_OBCHODNI')) {
            $subject_or = $this->or_model->get_screening($id);

            $subject_isir = isset($subject_or['ic'])
                ? $this->isir_model->get_screening_by_ic($subject_or['ic'])
                : null;

            $subject_vat = isset($subject_or['ic'])
                ? $this->vat_model->get_screening($subject_or['ic'])
                : null;
        } else if ($source === $this->config->item('DETAIL_ISIR')) {
            $subject_or = null;
            $subject_isir = $this->isir_model->get_screening_by_id($id);
            $subject_vat = isIcDefined($subject_isir['ic'])
                ? $this->vat_model->get_screening($subject_isir['ic'])
                : null;
        }

        if (!isset($subject_or['name']) && !isset($subject_isir['name'])) {
            show_404();
            return;
        }

        $data['title'] = isset($subject_or['name']) 
            ? $subject_or['name']
            : $subject_isir['name'];

        $data['search'] = new stdClass();
        $data['search']->name = character_limiter_ex($data['title'], $this->config->item('SEARCH_NAME_LENGTH') - 5);
        $data['search']->type_id = $this->config->item('SEARCH_SCREENING');
    
        $data['source'] = $source;
        $data['subject']['id'] = $id;
        $data['subject']['ic'] = isset($subject_or['ic']) 
            ? $subject_or['ic'] 
            : (isIcDefined($subject_isir['ic']) ? $subject_isir['ic'] : '');
        $data['subject']['name'] = $data['title'];
        $data['subject']['isProblematic'] = isSubjectProblematic($subject_or, $subject_vat, $subject_isir);
        $data['subject']['isProblematicLikvidace'] = isSubjectProblematicLikvidace($subject_or);
        $data['subject']['isProblematicExecutionAssociate'] = isSubjectProblematicExecutionAssociate($subject_or);
        $data['subject']['isProblematicVat'] = isSubjectProblematicVat($subject_vat);
        $data['subject']['isProblematicIsir'] = isSubjectProblematicIsir($subject_isir);

        $data['subject']['orLink'] = isset($subject_or['id']) ? makeObchodniLink($subject_or) : '';
        $data['subject']['isirLink'] = isset($subject_isir['id']) ? makeIsirLink($subject_isir) : '';

        $data['law_forms'] = $this->common_model->load_law_forms();

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_search', $data);
        $this->load->view('detail/screening', $data);
        $this->load->view('inc/footer');
    }

    public function isirdoc($id) {
        // download it!
        $doc = $this->isir_model->get_spis_data_row($id);

        if ($doc != null) {
            // convert to new web service
            $doc->document = str_replace('isir_ws', 'isir_public_ws', $doc->document);

            header('Location: '. $doc->document);		
        } else {
            show_404();
        }
    }

}
