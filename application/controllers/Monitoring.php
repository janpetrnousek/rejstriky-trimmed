<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base_controller.php';

class Monitoring extends Base_controller {

	private $resultcarrier = 'result';
	
	private $exportdir = 'files/export/';

	private $importdir = 'files/imported/';

	private $extension_excel = '.xls';
	
	private $extension_excel2007 = '.xlsx';

	private $extension_csv = '.csv';


	// Index name which carries the last page on the list of watches
	private $listlast = 'listLastUrl';
	
	function __construct() {
		parent::__construct();

		$this->load->model('common_model');
		$this->load->model('isir_model');
		$this->load->model('or_model');

		$this->load->library('cron_lib');
		$this->load->library('excel_lib');
		$this->load->library('form_validation');
		$this->load->library('import_lib');
		$this->load->library('pagination');
        $this->load->library('user_lib');

		$this->load->dbutil();		
    }
    
	public function index() {
        $data['title'] = 'Monitoring rejstříků';
        
		$data['registerId'] = $this->common_model->generate_register_id(true);
		$data['register_session'] = $this->common_model->load_register_session($data['registerId']);

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/index', $data);
		$this->load->view('inc/footer');
	}
	
	public function watches($filterid, $orderid, $page) {
		$this->ensureLoggedIn();
		
		$data['title'] = 'Sledované osoby';
		$data['titleOverride'] = 'Monitoring rejstříků';
		
		$this->session->set_userdata($this->listlast, current_url());
        
        // are we filtering? if yes, redirect
		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $searchdata['name'] = $this->input->post('name');
			$search_id = $this->common_model->save_search($searchdata);
			redirect('monitoring-rejstriku/sledovane/'. $search_id);				
		}
        
        // get user data
        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		// set filter and order for views
		$data['orderid'] = $orderid;
		$data['filterid'] = $filterid;
		$data['pagenum'] = $page;

		// get the flashdata
		$data['result'] = $this->session->flashdata($this->resultcarrier);

		// get the data		
		$filter = $this->common_model->getwatchesfilter($filterid);
		if ($filter != '') {
			$data['fillintext'] = $filter;
		}
		
		$data['watches'] = $this->isir_model->getwatchesbyuserid($data['userinfo'], $page, $this->config->item('PAGE_SIZE'), $filter, $orderid);

		// set the pagination
		// do we search for all or for filtered ones 
		$config['uri_segment'] = '5'; 		
		$config['base_url'] = base_url() . 'monitoring-rejstriku/sledovane/'. $filterid .'/'. $orderid;				
		
		$config['total_rows'] = $data['watches'][0]['size'];
		$config['per_page'] = $this->config->item('PAGE_SIZE');
		
		$config['prev_link'] = 'předcházející';
		$config['next_link'] = 'další';
		$config['num_tag_open'] = '';		
		$config['num_tag_close'] = '';		
		$config['cur_tag_open'] = '<strong>';
		$config['cur_tag_close'] = '</strong>';
		
		$config['first_link'] = '';		
		$config['last_link'] = '';		
		$this->pagination->initialize($config); 
		
		$data['pagination'] = $this->pagination->create_links();	

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/watches', $data);
		$this->load->view('inc/footer');
	}

	public function watches_add() {
		$this->ensureLoggedIn();

		$data['title'] = 'Vložit osobu ke sledování';
		$data['titleOverride'] = 'Monitoring rejstříků';

		$data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		// insert the watch
		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
			// run the validation
			$this->form_validation->set_rules('ic', 'IČ', 'trim|numeric|max_length[9]|callback_temporary_insert_validation|callback_max_subjects_validation');
			$this->form_validation->set_rules('rc', 'RČ', 'trim|validaterc');
			$this->form_validation->set_rules('name', 'Název / Příjmení', 'trim|max_length[255]');
			$this->form_validation->set_rules('firstname', 'Jméno', 'trim|max_length[100]');
			$this->form_validation->set_rules('birthdate', 'Datum narození', 'trim|valid_date|max_length[10]');
			$this->form_validation->set_rules('note', 'Poznámka', 'trim|max_length[1000]');
			$this->form_validation->set_rules('clientname', 'Identifikátor', 'trim|max_length[1000]');
	
			if ($this->form_validation->run()) {	
				// insert it					
				$watchdata = array(
					"user_id" => $data['userinfo']['id'],
					"firstname" => $this->input->post('firstname'),
					"name" => $this->input->post('name'),
					"ic" => $this->input->post('ic'),
					"rc" => str_replace('/', '', $this->input->post('rc')),
					"birthdate" => $this->input->post('birthdate') != '' 
						? date("Y-m-d", strtotime($this->input->post('birthdate')))
						: '',
					"note" => $this->input->post('note'),
					"clientname" => $this->input->post('clientname'),
					"date_add" => date("Y-m-d H:i:s")
				);
				
				$newid = $this->isir_model->add_watch_item($watchdata, $watchdata['name']);
				if ($newid != 0) {
					// added
					$data['result'] = 'Záznam byl přidán ke sledování.';	
					$data['clean_form'] = true;

					// get data from session, but refresh it
					$data['userinfo'] = $this->refreshSession($data['userinfo']['id']);
				} else {
					// already exists
					$data['result'] = 'Záznam je již sledován.';
				}
			} else {
				$data['result'] = validation_errors('<p class="validation_error">', '</p>');
			}				
		}
				
		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/add', $data);
		$this->load->view('inc/footer');
	}

	public function watches_add_direct($ic) {
		$this->ensureLoggedIn();

		$name = $this->or_model->get_name_by_ic($ic);
		$data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		$watchdata = array(
			"birthdate" => null,
            "clientname" => null,
			"date_add" => date("Y-m-d H:i:s"),
            "firstname" => '',
            "ic" => $ic,
			"name" => $name,
            "note" => null,
			"official_name" => $name,
            "rc" => '',
			"user_id" => $data['userinfo']['id'],
		);

		$result = $this->isir_model->add_watch_item($watchdata, $name);

		echo $result != 0 
			? 'Záznam byl přidán ke sledování.'
			: 'Záznam je již sledován.';
	}
    
	public function watches_import() {
		$this->ensureLoggedIn();

		$data['title'] = 'Načíst osoby ze souboru';
		$data['titleOverride'] = 'Monitoring rejstříků';

		$data['datasources'] = $this->common_model->get_data_sources();

		$data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		// import the data
		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
			$config['upload_path'] = $this->importdir;
			$config['max_size'] = '8192';
			$config['allowed_types'] = '*';
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('import')) {
				// an error occured
				$data['result'] = $this->upload->display_errors();
			} else {
				$updata = $this->upload->data();

				$importdata = array(
					"user_id" => $data['userinfo']['id'],
					"date" => date("Y-m-d H:i:s"),
					"filename" => $updata['file_name'],
					"delete_missing" => $this->input->post("delete-missing") == true);

				$data['importid'] = $this->common_model->insert_import($importdata);

                $import = null;
                $extension_lower = strtolower($this->upload->file_ext);
				if ($extension_lower == $this->extension_csv) {
                    // retrieve CSV data
					if ($this->input->post("datasource") == $this->config->item('DATA_SOURCE_DEFAULT')) {
						$import = $this->import_lib->getcsvdata($updata['full_path']);
					} else if ($this->input->post("datasource") == $this->config->item('DATA_SOURCE_GINIS')) {
						$import = $this->import_lib->getcsvdata_ginis($updata['full_path']);
					} else if ($this->input->post("datasource") == $this->config->item('DATA_SOURCE_FIRMAIC')) {
						$import = $this->import_lib->getcsvdata_firmaic($updata['full_path']);
					}
				} else if ($extension_lower == $this->extension_excel || $extension_lower == $this->extension_excel2007) {
					// retrieve Excel data
					$import = $this->excel_lib->load_xls_as_objects($updata['full_path'], $extension_lower);
				} else {
					// the extension does not match supported
					$data['result'] = 'Pokoušíte se nahrát nepovolený typ souboru.';
				}

				if ($import != null) {
					// we have the data, import them
					if (sizeof($import) > 0) {
						$importvalidation = $this->import_lib->validate($import);

						if ($importvalidation['result'] == true) {
							if ($this->input->post("delete-missing") == "1") {
								$this->import_lib->deletemissing($import, $data['userinfo']['id']);
							}

							// insert new watches
							$importresult = $this->import_lib->doimport($import, $data['userinfo']);

							$data['importpassed'] = $importresult['result'];
							$data['result'] = $importresult['message'];

							// get data from session, but refresh it
							$data['userinfo'] = $this->refreshSession($data['userinfo']['id']);
						} else {
							$linenumbers = array();
							foreach($importvalidation['errors'] as $errors) {
								array_push($linenumbers, $errors['id']);
							}

							$data['result'] = 'Identifikační údaj (IČ/RČ) není validní na řádcích: '. implode($linenumbers, ', ' );
						}
					} else {
						$data['result'] = 'Soubor neobsahuje žádné subjekty pro import.';
					}						
				}
			}					
		}

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/import', $data);
		$this->load->view('inc/footer');
	}

	public function watches_import_notify() {
		$this->ensureLoggedIn();

		$data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		$this->session->set_userdata(
			$this->config->item("SESS_COLLECT_EMAILS"), 
			$this->config->item("SESS_COLLECT_EMAILS_ON"));
		
		$this->cron_lib->sendnotifications(
			'all',
			null,
			null,
			$data['userinfo']['id'], 
			array($this->config->item('NOTIFICATION_FREQUENCY_AFTER_IMPORT_ID')),
			true);
		
		// when sending succeeds - we are here - generate message
		$emails_collection = $this->session->userdata($this->config->item("SESS_COLLECTED_EMAILS")) != null
			? $this->session->userdata($this->config->item("SESS_COLLECTED_EMAILS"))
			: array();
		
		// clean session
		$this->session->unset_userdata($this->config->item("SESS_COLLECT_EMAILS"));
		$this->session->unset_userdata($this->config->item("SESS_COLLECTED_EMAILS"));
		
		$data['emails_collection'] = $emails_collection;

		$this->load->view('monitoring/importnotify', $data);
	}

	public function watches_import_report($id) {
		$this->ensureLoggedIn();

		$data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));
		
		// get import file and check validity
		$import = $this->common_model->get_import($id);
		if ($import == null || $import->user_id != $data['userinfo']['id']) {
			show_404();
		}
	
		// analyze import and prepare report
		$importitems = null;
		$filenameWithPath = $this->importdir . $import->filename;
		$extension = '.'. pathinfo($filenameWithPath, PATHINFO_EXTENSION);
		if ($extension == $this->extension_csv) {
			// retrieve CSV
			$importitems = $this->import_lib->getcsvdata($filenameWithPath);
		} else if ($extension == $this->extension_excel || $extension == $this->extension_excel2007) {
			// retrieve Excel data
			$importitems = $this->excel_lib->load_xls_as_objects($filenameWithPath, $extension);
		}

		$data['subjects'] = array();
		$data['filename'] = $import->filename;
	
		foreach ($importitems as $item) {
			if (!$item) {
				continue;
			}

			$item->ic = ($item->ic != NULL) ? trim($item->ic) : 0;
			$item->rc = ($item->rc != NULL) ? trim($item->rc) : 0;
			$item->birthdate = (isset($item->birthdate) && $item->birthdate != NULL) ? trim($item->birthdate) : 0;
		
			if (($item->ic != '') || ($item->rc != '') || (($item->birthdate != '') && ($item->birthdate != '0000-00-00'))) {
				$item->spisids = $this->isir_model->searchspis($item->ic, $item->rc, $item->birthdate, $item->name);
			}
		
			array_push($data['subjects'], $item);
		}
	
		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/importreport', $data);
		$this->load->view('inc/footer');
	}

	public function watches_export() {
		$this->ensureLoggedIn();

        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		$watches = $this->isir_model->get_watches_for_export($data['userinfo']['id'], $data['userinfo']['is_automatic_filling_enabled']);
		
		$output = $this->dbutil->csv_from_result($watches);
		
		$excel_file = $this->excel_lib->csv_to_excel($output, $this->exportdir, 'exportsledovane');
		redirect($excel_file);	
	}

	public function watches_delete($id, $filter = '0', $order = '1', $page) {
		$this->ensureLoggedIn();

        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		$ids = array($id);
		$this->isir_model->deletewatches($ids);

		$data['userinfo'] = $this->refreshSession($data['userinfo']['id']);

		$this->session->set_flashdata($this->resultcarrier, 'Záznam byl smazán.');				
		redirect('monitoring-rejstriku/sledovane/'. $filter .'/'. $order .'/'. $page);		
	}

	public function watches_delete_all() {
		$this->ensureLoggedIn();

        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		$this->isir_model->deleteallwatchforuser($data['userinfo']['id']);
		$this->session->set_flashdata($this->resultcarrier, 'Záznamy byly smazané.');		

		$data['userinfo'] = $this->refreshSession($data['userinfo']['id']);

		redirect('monitoring-rejstriku/sledovane');
	}

	public function watches_edit($id) {
		$this->ensureLoggedIn();

		$data['title'] = 'Editovat subjekt';
        $data['titleOverride'] = 'Monitoring rejstříků';

        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		// get the data
		$data['record'] = $this->isir_model->get_watch($data['userinfo']['id'], $id);
		if ($data['record'] == null) {
			show_404();
		}

		// save the changes
		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
			// run the validation
			$this->form_validation->set_rules('ic', 'IČ', 'trim|numeric|callback_temporary_insert_validation');
			$this->form_validation->set_rules('rc', 'RČ', 'trim');
			$this->form_validation->set_rules('name', 'Název / Příjmení', 'trim|max_length[255]');
			$this->form_validation->set_rules('firstname', 'Jméno', 'trim|max_length[100]');
			$this->form_validation->set_rules('birthdate', 'Datum narození', 'trim|valid_date|max_length[10]');
			$this->form_validation->set_rules('note', 'Poznámka', 'trim|max_length[1000]');
			$this->form_validation->set_rules('clientname', 'Identifikátor', 'trim|max_length[1000]');
	
			if ($this->form_validation->run()) {	
				// submit changes it					
				$watchdata = array(
					"firstname" => $this->input->post('firstname'),
					"name" => $this->input->post('name'),
					"ic" => $this->input->post('ic'),
					"rc" => str_replace('/', '', $this->input->post('rc')),
					"birthdate" => $this->input->post('birthdate') != '' 
						? date("Y-m-d", strtotime($this->input->post('birthdate')))
						: '',
					"note" => $this->input->post('note'),
					"clientname" => $this->input->post('clientname')
				);

				$newid = $this->isir_model->edit_watch_item($id, $watchdata);
				$this->session->set_flashdata($this->resultcarrier, 'Záznam byl uložen.');

				// redirect to the list
				$redirecturl = 'monitoring-rejstriku/sledovane';
				if ($this->session->userdata($this->listlast) != null) {
					$redirecturl = $this->session->userdata($this->listlast);
				}

				redirect($redirecturl);
			} else {
				$this->data['result'] = validation_errors('<p class="validation_error">', '</p>');
			}				
		}

		// preprocess IC and RC for displaying
		if ($data['record']->ic == 0) {
			$data['record']->ic = '';
		}

		if ($data['record']->rc == 0) {
			$data['record']->rc = '';
		}

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/edit', $data);
		$this->load->view('inc/footer');
	}

	public function settings() {
		$this->ensureLoggedIn();

		$data['title'] = 'Nastavení upozorňování';
        $data['titleOverride'] = 'Monitoring rejstříků';

        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		// save data
		if (($this->input->server('REQUEST_METHOD') == 'POST')) {
			$savedata = array(
				"is_automatic_filling_enabled" => $this->input->post('is_automatic_filling_enabled')
			);
			
			if ($data['userinfo']['is_insolvencewatch'] == true) {
                $savedata['spis_notification_frequency_id'] = $this->input->post('spis_notification_frequency_id');

                $savedata['notification_filtering_id'] = $this->input->post('notification_filtering_id');

                $savedata['is_spis_notification_empty'] = $this->input->post('is_spis_notification_empty');
				$savedata['is_spis_notification_minor_documents'] = $this->input->post('is_spis_notification_minor_documents');
				$savedata['is_spis_notification_compressed'] = $this->input->post('is_spis_notification_compressed');
            }

			if ($data['userinfo']['is_claimswatch'] == true) {
                $savedata['claims_notification_frequency_id'] = $this->input->post('claims_notification_frequency_id');
            }

            if ($data['userinfo']['is_orwatch'] == true) {
                $savedata['or_notification_frequency_id'] = $this->input->post('or_notification_frequency_id');
                $this->common_model->update_user_notifications_or(
                    $data['userinfo']['id'], 
                    $this->collect_notification_settings('notifications_or_'));
            }

            if ($data['userinfo']['is_likvidacewatch'] == true) {
                $savedata['likvidace_notification_frequency_id'] = $this->input->post('likvidace_notification_frequency_id');
            }

            if ($data['userinfo']['is_orskwatch'] == true) {
                $savedata['orsk_notification_frequency_id'] = $this->input->post('orsk_notification_frequency_id');
                $this->common_model->update_user_notifications_orsk(
                    $data['userinfo']['id'], 
                    $this->collect_notification_settings('notifications_orsk_'));
            }

            $savedata['ifilter_notification_frequency_id'] = $this->input->post('ifilter_notification_frequency_id');
			
			if ($data['userinfo']['is_vatdebtorswatch'] == true) {
				$savedata['vatdebtors_notification_frequency_id'] = $this->input->post('vatdebtors_notification_frequency_id');
                $savedata['is_vatdebtors_notification_empty'] = $this->input->post('is_vatdebtors_notification_empty');
			}
			
			if ($data['userinfo']['is_accountchangewatch'] == true) {
                $savedata['accounts_notification_frequency_id'] = $this->input->post('accounts_notification_frequency_id');
            }
                        
            $this->common_model->update_user($data['userinfo']['id'], $savedata);
            $this->common_model->update_extra_emails($data['userinfo']['id'], $this->input->post('extra_emails'));
			
			$data['message'] = 'Změny byly uloženy.';

			// get data from session, but refresh it
			$data['userinfo'] = $this->refreshSession($data['userinfo']['id']);
		}

        // load necessary data
		$data['updates'] = $this->common_model->get_notification_frequencies();
		$data['filters'] = $this->common_model->get_notification_filtering();
		$data['extra_emails'] = $this->common_model->get_extra_emails($data['userinfo']['id']);			
		$data['automatic_filling'][0] = array("id" => 0, "name" => "deaktivovováno (výchozí nastavení)");
		$data['automatic_filling'][1] = array("id" => 1, "name" => "aktivováno");

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/settings', $data);
		$this->load->view('inc/footer');
	}

	public function history() {
		$this->ensureLoggedIn();

		$data['title'] = 'Historie upozorňování';
        $data['titleOverride'] = 'Monitoring rejstříků';
        
        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));
        $data['emails'] = $this->common_model->get_sent_emails($data['userinfo']['id']);

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/history', $data);
		$this->load->view('inc/footer');
	}

	public function synchronization() {
		$this->ensureLoggedIn();

		$data['title'] = 'Synchronizace';
        $data['titleOverride'] = 'Monitoring rejstříků';
        
        $data['userinfo'] = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));
        $data['logs'] = $this->common_model->get_visible_logs($data['userinfo']['id']);

		$this->load->view('inc/header', $data);
		$this->load->view('inc/header_common', $data);
		$this->load->view('monitoring/synchronization', $data);
		$this->load->view('inc/footer');
	}

	public function temporary_insert_validation($str)
	{
		$result = !((($_POST['ic'] == '') || ($_POST['ic'] == '0')) 
			&& (($_POST['rc'] == '') || ($_POST['rc'] == '0')) 
			&& (($_POST['birthdate'] == '') || !isset($_POST['name']) || ($_POST['name'] == '')));
				
		if ($result == false) {
			$this->form_validation->set_message('temporary_insert_validation', 'Zadejte IČ nebo RČ nebo datum narození (datum narození je nutno zadat společně se jménem a přijmením subjektu).');		
		}
		
		return $result;
	}	

	public function max_subjects_validation($str) {
        $userinfo = $this->session->userdata($this->config->item('USER_LOGGED_SESSION'));

		$result = $userinfo['max_subjects'] > $userinfo['numwatches'];

		if ($result == false) {
			$this->form_validation->set_message('max_subjects_validation', 'Dosáhli jste maximální počet sledovaných subjektů. Chcete li přidat další změnte si program na vyšší tarifu.');
		}

		return $result;
    }
    
    private function collect_notification_settings($prefix) {
        $settings = array();
        foreach ($this->config->item('NOTIFICATION_OR_TYPES') as $key => $value) {
            $settings[$key] = $this->input->post($prefix . $key) == '1';
        }

        return $settings;
    }
	
}
