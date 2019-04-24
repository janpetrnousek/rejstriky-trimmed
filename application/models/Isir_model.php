<?php
class Isir_model extends Base_model  {
		
	private $spisesForStatusOldUpdate;
	
	private $pagesize = 100;
	
	function __construct()
    {
        parent::__construct();

		$this->load->library('content_lib');

		$this->spisesForStatusOldUpdate = date('Y-m-d', strtotime("-1 week"));

        $CI = & get_instance();
        $CI->isirdb = $this->load->database('isirdb', TRUE);
        $this->isirdb = $CI->isirdb;
		$this->dboverride = $CI->isirdb;
    }
    
	public function get_table_row_by_id($tablename, $idname, $idvalue, $getarray = false) {
		$ret = null;
		
		$this->isirdb->where($idname, $idvalue);
		$query = $this->isirdb->get($tablename);
		
		if ($query->num_rows() > 0)  {
			if ($getarray == false) {
				$ret = $query->first_row();
			} else {
				$ret = $query->result_array();				
				$ret = $ret[0];
			}
		}
		
		return $ret;
	}
	
	public function get_table_contents($tablename) {
		return $this->isirdb->get($tablename)->result_array();		
    }
    
    public function find_missing_watches($subjects) {
        $missing = array();

        if (sizeof($subjects) == 0) {
            return $missing;
        }

        $idList = $this->extract_field($subjects, 'watched_id');

        $this->isirdb->where_in('id', $idList);
        $this->isirdb->select('id');
        
        $results = $this->isirdb->get('users_watches')->result_array();

        foreach ($subjects as $s) {
            $found = false;
            foreach ($results as $r) {
                if ($r['id'] == $s['watched_id']) {
                    $found = true;
                    break;
                }
            }

            if ($found == false) {
                array_push($missing, $s['watched_id']);
            }
        }

        return $missing;
    }

    public function get_watches_with_ic($currentId, $limit, $userId = null) {
        $this->isirdb->where('ic <> ', 0);
        $this->isirdb->where('id > ', $currentId);
        $this->isirdb->limit($limit);
        $this->isirdb->order_by('id');
        $this->isirdb->select('ic, id, name, official_name, firstname, clientname');

        if ($userId != null) {
            $this->isirdb->where('user_id', $userId);
        }

        return $this->isirdb->get('users_watches')->result_array();
    }

	public function insert_into_table($tablename, $values) {
		$this->isirdb->insert($tablename, $values);
		return $this->isirdb->insert_id();
	}

	public function update_row_in_table($tablename, $idname, $id, $newdata) {
		$this->isirdb->where($idname, $id);
		$this->isirdb->update($tablename, $newdata);
	}

	public function update_or_insert_in_table($tablename, $idname, $idvalue, $data, $selectedidname = null) {
		$row = $this->get_table_row_by_id($tablename, $idname, $idvalue, true);
		$result = null;

		if ($row == null) {
			$result = $this->insert_into_table($tablename, $data);
		} else {
			$this->update_row_in_table($tablename, $idname, $idvalue, $data);
			$result = $selectedidname == null ? $row[$idname] : $row[$selectedidname];
		}

		return $result;
	}

	public function insert_batch($tablename, $values) {
		$this->isirdb->insert_batch($tablename, $values);
	}
	
	public function delete_batch_by_id($tablename, $ids, $idname = 'id') {
		$this->isirdb->where_in($idname, $ids);
		$this->isirdb->delete($tablename);
	}
    
	public function iterate_table($tablename, $lastId, $limit) {
		$this->isirdb->where('id > ', $lastId);
		$this->isirdb->order_by('id');
		$this->isirdb->limit($limit);

		return $this->isirdb->get($tablename)->result_array();
	}

	public function get_watches_without_official_name() {
		$this->isirdb->join('spis2users_watches', 'users_watches.id = spis2users_watches.watched_id');
		$this->isirdb->join('spis', 'spis2users_watches.spis_id = spis.id');
		$this->isirdb->where('official_name', '');
		$this->isirdb->group_by('users_watches.id');
		$this->isirdb->select('spis.name AS official_name, users_watches.id AS id');
		return $this->isirdb->get('users_watches')->result_array();
	}

	public function update_official_name($name) {
		$update = array(
			"official_name" => $name['official_name']
		);
		
		$this->update_row_in_table('users_watches', 'id', $name['id'], $update);
	}

	public function isstatuschangeemailed($spis2users_watches_id, $state_id) {
		$this->isirdb->order_by('date', 'desc');
		$this->isirdb->limit(1);
		$query = $this->isirdb->get_where('emails_spisstatuses', array("spis2users_watches_id" => $spis2users_watches_id));
		
		$last = $query->first_row();
		
		return $last != null && $last->status_id == $state_id;
	}

	public function getlaststatuschangeemailed($spis2users_watches) {
		$this->isirdb->join('spis2users_watches', 'spis2users_watches.id = emails_spisstatuses.spis2users_watches_id');
		$this->isirdb->join('spis', 'spis.id = spis2users_watches.spis_id');
		$this->isirdb->join('spis_status', 'spis_status.id = spis.status_id');

		$this->isirdb->where('spis2users_watches_id', $spis2users_watches);

		$this->isirdb->limit(1);
		$this->isirdb->order_by('date', 'DESC');

		$this->isirdb->select('spis_status.name AS name');

		$query = $this->isirdb->get('emails_spisstatuses');

		$ret = null;
		if ($query->num_rows() > 0) {
			$ret = $query->first_row();
		}

		return $ret;
	}

	public function getstatusstart($spis_id, $status_id) {
		$result = null;

		// try to enahnce like this: http://stackoverflow.com/questions/12557761/is-there-a-way-to-specify-use-index-or-force-index-in-codeigniter
		$spis_id = $this->db->escape($spis_id);
		$status_id = $this->db->escape($status_id);
		$query = $this->isirdb->query("
			SELECT 
				publishdate 
			FROM 
				(spis_data FORCE INDEX(spis_id)) 
			WHERE 
				spis_id = ". $spis_id ." AND status_id = ". $status_id ." 
			ORDER BY 
				publishdate ASC 
			LIMIT 1");

		if ($query->num_rows() > 0) {
			$result = date("d.m.Y", strtotime($query->first_row()->publishdate));
		}

		return $result;
	}

	public function getnewlikvidace($userid, $lastemail) {
		$this->isirdb->where('date_likvidace > "'. $lastemail .'"', '', false);
		$this->isirdb->where('has_likvidace', 1);
		$this->isirdb->where('user_id', $userid);		
		return $this->isirdb->get('users_watches')->result_array();
	}

	/**
	* Gets all new claims for watched creditors of given user since last emailing
	*/
	public function get_new_claims($userid, $lastemail) {
		$this->isirdb->where('users_creditors.user_id', $userid);
		$this->isirdb->where('users_creditors.from < ', date("Y-m-d H:i:s"));
		$this->isirdb->where('users_creditors.to', null);
		$this->isirdb->where('users_creditors_news.modified_on > ', $lastemail);
		
		$this->isirdb->join('users_creditors_news', 'users_creditors_news.users_creditor_id = users_creditors.id');
		$this->isirdb->join('spis', 'spis.id = users_creditors_news.spis_id');
		
		$this->isirdb->select('
			users_creditors.name AS creditor_name,
			spis.number_prefix AS number_prefix,
			spis.number_id AS number_id,
			spis.number_year AS number_year,
			spis.name AS debtor_name,
			spis.id AS spis_id,
			users_creditors_news.date AS date,
			users_creditors_news.oddil AS oddil');
		
		$this->isirdb->order_by('users_creditors.name', 'asc');
		$this->isirdb->order_by('users_creditors_news.date', 'asc');
		
		return $this->isirdb->get('users_creditors')->result_array();
	}

	/**
	 * Gets the new events which will be emailed to the user since the last emailing
	 * NOTE: keep in sync with getnewevents_allforadded
	 */
	public function getnewevents($userid, $lastemail, $ignoreminordocuments) {
		// first get changes since the last emailing
		$this->isirdb->where('spis_data.publishdate > "'. $lastemail .'"', '', false);

		if ($ignoreminordocuments == true) {
			$this->isirdb->where('spis_data.is_document_minor', false);
		}

		$this->isirdb->order_by('spis_data.publishdate');
		$this->_prepareNewEventsSelect($userid, false, true);

		$temp = $this->isirdb->get('users_watches')->result_array();

		// then get changes for recently added subjects
		$this->isirdb->where('users_watches.date_add > "'. $lastemail .'"', '', false);
		$this->_prepareNewEventsSelect($userid, true, false);

		$tempNewlyAdded = $this->isirdb->get('users_watches')->result_array();

		return $this->_mergeEvents($temp, $tempNewlyAdded);
	}

	/**
	 * Gets new status changes which will be email to the user since the last emailing
	 * NOTE: you have to return the same result set as getnewevents
	 */
	public function getnewstatuschanges($userid, $lastemail) {
		// get changes since last emailing 
		$this->isirdb->where('spis_status_history.date > ', $lastemail);
		$this->_prepareStatusChangeSelect($userid);
		
		$events = $this->isirdb->get('users_watches')->result_array();
		
		// then get changes for recently added subjects
		$this->isirdb->where('users_watches.date_add > ', $lastemail);
		$this->_prepareStatusChangeSelect($userid);

		$eventsNewlyAdded = $this->isirdb->get('users_watches')->result_array();

		return $this->_mergeEvents($events, $eventsNewlyAdded);
	}

	/**
	 * Gets the new events which will be emailed to the user since the last emailing
	 * But gets all events for subjects added since last emailing
	 * NOTE: keep in sync with getnewevents
	 */
	public function getnewevents_allforadded($userid, $lastemail, $ignoreminordocuments) {
		// first get all events for subjects added after last emailing
		$this->isirdb->where('users_watches.date_add > "'. $lastemail .'"', '', false);

		if ($ignoreminordocuments == true) {
			$this->isirdb->where('spis_data.is_document_minor', false);
		}
		
		$this->_prepareNewEventsSelect($userid, false, false);

		$tempNewlyAdded = $this->isirdb->get('users_watches')->result_array();

		// then get changes since the last emailing for subjects added before last emailing
		$this->isirdb->where('spis_data.publishdate > "'. $lastemail .'"', '', false);

		if ($ignoreminordocuments == true) {
			$this->isirdb->where('spis_data.is_document_minor', false);
		}

		$this->isirdb->order_by('spis_data.publishdate');
		$this->_prepareNewEventsSelect($userid, false, true);

		$temp = $this->isirdb->get('users_watches')->result_array();
		
		return $this->_mergeEvents($tempNewlyAdded, $temp);
	}

        
	/**
	 * Merges events from two groups taking all from first and from second only
	 * those which are not present in first group
	 */
	private function _mergeEvents($first, $second) {
		$ret = array();

		$tempEvents = array();

		// events from first group
		// group it by spis and prepare arrays of events
		foreach ($first as $i) {
			$key = $i['spis_id'] .' - '. $i['spis2users_watches_id'];
			if (isset($ret[$key]) == false) {
				$this->_prepareSpis($i, $key, $ret);
			}

			if (isset($tempEvents[$key]) == false) {
				$tempEvents[$key] = array();
			}

			array_push($tempEvents[$key], $this->_prepareEvent($i['spis_id'], $i));
		}

		// events from second group
		// add only those changes which are not in the first group
		$second_group_keys = array();
		foreach ($second as $i) {
			$key = $i['spis_id'] .' - '. $i['spis2users_watches_id'];
			if (isset($ret[$key]) == false) {
				// watch notification does not already exist
				$this->_prepareSpis($i, $key, $ret);
				$second_group_keys[$key] = true;
			}

			if (isset($second_group_keys[$key])) {
				if (isset($tempEvents[$key]) == false) {
					$tempEvents[$key] = array();
				}

				array_push($tempEvents[$key], $this->_prepareEvent($i['spis_id'], $i));
			}
		}
		
		// attach array of events
		foreach($ret as $key => $value) {
			$ret[$key]['events'] = $tempEvents[$key];
		}

		return $ret;
	}

	private function _prepareNewEventsSelect($userid, $lasteventonly, $forcePublishDateIndex) {
		$this->isirdb->where('users_watches.user_id', $userid);

		$this->isirdb->join('spis2users_watches', 'spis2users_watches.watched_id = users_watches.id');
		$this->isirdb->join('spis', 'spis.id = spis2users_watches.spis_id');

		if ($lasteventonly == true) {
			// join last event only
			$this->isirdb->join('spis_data', 'spis_data.id = spis.last_spis_data_id');
		} else {
			if ($forcePublishDateIndex == true) {
				// force publishdate index to improve performance (MariaDB does not choose optimal by default)
				$this->isirdb->join('(spis_data FORCE INDEX(publishdate))', 'spis_data.spis_id = spis.id');		
			} else {
				$this->isirdb->join('spis_data', 'spis_data.spis_id = spis.id');		
			}
		}

		$this->isirdb->join('spis_status', 'spis_status.id = spis.status_id');
		$this->isirdb->join('spis_status AS event_status', 'event_status.id = spis_data.status_id');
		$this->isirdb->join('spis_sections', 'spis_sections.id = spis_data.section_id');
		$this->isirdb->join('spis_texts', 'spis_texts.id = spis_data.text_id');

		$this->isirdb->select('
			users_watches.ic AS ic, 
			users_watches.rc AS rc, 
			users_watches.birthdate AS birthdate,
			users_watches.clientname AS clientname,
			users_watches.firstname AS watch_firstname,
			users_watches.name AS watch_name,
			users_watches.official_name AS watch_official_name,
			spis.id AS spis_id,
			spis.name AS name, 
			spis.number_prefix AS number_prefix, 
			spis.number_id AS number_id, 
			spis.number_year AS number_year, 
			spis_status.name AS state, 
			spis_status.id AS state_id, 
			event_status.name AS event_state,
			spis_data.status_id AS event_state_id,
			spis_sections.name AS section, 
			spis_sections.description AS section_desc, 
			spis_texts.text AS text,
			spis_texts.isir_text_id AS isir_text_id,
			spis_texts.isir_text_id_for_c AS isir_text_id_for_c,
			spis_data.id AS data_id,
			spis_data.publishdate AS publishdate,
			spis_data.is_document_minor AS is_document_minor,
			spis2users_watches.id AS spis2users_watches_id
		');		
	}
	
	private function _prepareStatusChangeSelect($userid) {
		$this->isirdb->where('users_watches.user_id', $userid);

		$this->isirdb->join('spis2users_watches', 'spis2users_watches.watched_id = users_watches.id');
		$this->isirdb->join('spis', 'spis.id = spis2users_watches.spis_id');
		$this->isirdb->join('spis_data', 'spis_data.id = spis.last_spis_data_id');
		$this->isirdb->join('spis_sections', 'spis_sections.id = spis_data.section_id');
		$this->isirdb->join('spis_status_history', 'spis_status_history.spis_id = spis.id AND spis_status_history.is_last = 1');
		$this->isirdb->join('spis_status', 'spis_status.id = spis_status_history.status_id');
		
		$this->isirdb->order_by('spis_status_history.date', 'desc');
		$this->isirdb->group_by('spis_status_history.spis_id, users_watches.id');
		
		$this->isirdb->select('
			users_watches.ic AS ic, 
			users_watches.rc AS rc, 
			users_watches.birthdate AS birthdate,
			users_watches.clientname AS clientname,
			users_watches.firstname AS watch_firstname,
			users_watches.name AS watch_name,
			users_watches.official_name AS watch_official_name,
			spis.id AS spis_id,
			spis.name AS name, 
			spis.number_prefix AS number_prefix, 
			spis.number_id AS number_id, 
			spis.number_year AS number_year, 
			spis_status.name AS state, 
			spis_status.id AS state_id, 
			spis_status.name AS event_state,
			spis_status.id AS event_state_id,
			spis_sections.name AS section, 
			spis_sections.description AS section_desc, 
			NULL AS text,
			NULL AS isir_text_id,
			NULL AS isir_text_id_for_c,
			spis_data.id AS data_id,
			spis_data.publishdate AS publishdate,
			spis_data.is_document_minor AS is_document_minor,
			spis2users_watches.id AS spis2users_watches_id
		', false);		
	}
	
	private function _prepareEvent($spis_id, $event) {
		return array(
			"spis_id" => $spis_id,
			"id" => $event['data_id'],
			"text" => $event['text'],
			"isir_text_id" => $event['isir_text_id'],
			"isir_text_id_for_c" => $event['isir_text_id_for_c'],
			"date" => date("d.m.Y", strtotime($event['publishdate'])),
			"section" => $event['section'],
			"section_desc" => $event['section_desc'],
			"status_id" => $event['event_state_id'],							
			"status_name" => $event['event_state'],							
			"is_document_minor" => $event['is_document_minor']
		);
	}
	
	private function _prepareSpis($spis, $key, &$ret) {
		$ret[$key]['spis_number'] = $this->content_lib->format_spisname($spis['number_prefix'], $spis['number_id'], $spis['number_year']);
		$ret[$key]['ic'] = $spis['ic'];
		$ret[$key]['rc'] = $spis['rc'];
		$ret[$key]['birthdate'] = $spis['birthdate'];
		$ret[$key]['clientname'] = $spis['clientname'];
		$ret[$key]['watch_firstname'] = $spis['watch_firstname'];
		$ret[$key]['watch_name'] = $spis['watch_name'];
		$ret[$key]['watch_official_name'] = $spis['watch_official_name'];
		$ret[$key]['state'] = $spis['state'];
		$ret[$key]['state_id'] = $spis['state_id'];
		$ret[$key]['spis2users_watches_id'] = $spis['spis2users_watches_id'];
	}

	private function _prepareNewEventsResult($temp, $i, $ret, $key) {
		// build up the associated events
		$events = array();
		foreach ($temp as $ii) {
			if ($ii['spis_id'] == $i['spis_id']) {
				$newevent = array(
					"spis_id" => $i['spis_id'],
					"id" => $ii['data_id'],
					"text" => $ii['text'],
					"date" => date("d.m.Y", strtotime($ii['publishdate'])),
					"section" => $ii['section'],
					"section_desc" => $ii['section_desc'],
					"status_id" => $ii['event_state_id'],							
					"status_name" => $ii['event_state'],							
					"is_document_minor" => $ii['is_document_minor']
				);
				array_push($events, $newevent);
			}
		}

		$ret[$key]['spis_number'] = $this->content_lib->format_spisname($i['number_prefix'], $i['number_id'], $i['number_year']);
		$ret[$key]['events'] = $events;
		$ret[$key]['ic'] = $i['ic'];
		$ret[$key]['rc'] = $i['rc'];
		$ret[$key]['birthdate'] = $i['birthdate'];
		$ret[$key]['clientname'] = $i['clientname'];
		$ret[$key]['watch_firstname'] = $i['watch_firstname'];
		$ret[$key]['watch_name'] = $i['watch_name'];
		$ret[$key]['watch_official_name'] = $i['watch_official_name'];
		$ret[$key]['state'] = $i['state'];
		$ret[$key]['state_id'] = $i['state_id'];
		$ret[$key]['spis2users_watches_id'] = $i['spis2users_watches_id'];

		return $ret;
	}

	public function get_num_watches($userId) {
		$this->isirdb->where('user_id', $userId);
		$this->isirdb->select('COUNT(*) AS numwatches');

		return $this->isirdb->get('users_watches')->first_row()->numwatches;
	}

	public function get_account_changes($userid, $lastemail) {
//            // get all changes which are:
//            // - modified after last emailing
//            // - not older than addition of a subject
//            $this->db->where('users_watches_accounts.modified_on > ', $lastemail);
//            
//            $date_add_where = '(users_watches_accounts.date_end > users_watches.date_add '
//                . 'OR users_watches_accounts.date_start > users_watches.date_add)';
//            $this->db->where($date_add_where, '', false);
		
		// replace until line $this->db->where('users_watches.user_id', $userid); - ten uz nie
		$date_where = '(users_watches_accounts.date_end > "'. $lastemail . '" '
			. 'OR users_watches_accounts.date_start > "'. $lastemail . '")';
		
		$this->isirdb->where($date_where, '', false);
		
		$this->isirdb->where('users_watches.user_id', $userid);
		$this->isirdb->join('users_watches', 'users_watches.id = users_watches_accounts.user_watch_id');
		$this->isirdb->select('
			users_watches_accounts.account AS account,
			users_watches_accounts.date_start AS date_start,
			users_watches_accounts.date_end AS date_end,
			users_watches.official_name AS official_name,
			users_watches.name AS name,
			users_watches.ic AS ic,
			users_watches.rc AS rc,
			users_watches.id AS id');
		
		$result = $this->isirdb->get('users_watches_accounts')->result_array();
		
		return $result;
	}

	public function getvatdebtorsevents($userid, $lastemail) {
		$this->isirdb->where('date_vat_debtor > "'. $lastemail .'"', '', false);
		$this->isirdb->where('date_vat_debtor <> ', $this->config->item('START_YEAR'));
		$this->isirdb->where('user_id', $userid);		
		return $this->isirdb->get('users_watches')->result_array();
	}

    /**
    * Gets a query to get user watches for export to CSV
    */
    public function get_watches_for_export($user_id, $is_automatic_filling_enabled) {
		$select = $is_automatic_filling_enabled == 0 
			? 'name AS Nazev, firstname AS Jmeno, ic AS IC, rc AS RC, CASE birthdate = "0000-00-00" WHEN 1 THEN "" ELSE DATE_FORMAT(birthdate, "%d.%m.%Y") END AS DatumNarozeni, note AS Poznamka, clientname AS JmenoKlienta'
			: 'official_name AS Nazev, "" AS Jmeno, ic AS IC, rc AS RC, CASE birthdate = "0000-00-00" WHEN 1 THEN "" ELSE DATE_FORMAT(birthdate, "%d.%m.%Y") END AS DatumNarozeni, note AS Poznamka, clientname AS JmenoKlienta';

		return $this->isirdb->query("SELECT ". $select ." FROM users_watches WHERE user_id = ". $user_id);
	}

    /**
    * Deletes all watches for the selected user
    */
    public function deleteallwatchforuser($user_id) {
		// get the list of watches for this user
		$query = $this->isirdb->get_where('users_watches', array("user_id" => $user_id))->result_array();

		if (sizeof($query) > 0) {
			$ids = array();
			foreach ($query as $i) {
				array_push($ids, $i['id']);
			}

			// delete them
			$this->deletewatches($ids);				
		}
	}

	/**
	* Deletes watch from the database by its id
	*/
	public function deletewatches($ids) {
		// delete mailing status if used
		$this->isirdb->where_in('watched_id', $ids);
		$spis2users_watches = $this->isirdb->get('spis2users_watches')->result_array();

		// build ids array
		if (sizeof($spis2users_watches) > 0) {
			$spis2users_watches_ids = array();
			foreach ($spis2users_watches as $i) {
				array_push($spis2users_watches_ids, $i['id']);
			}

			$this->isirdb->where_in('spis2users_watches_id', $spis2users_watches_ids);
			$this->isirdb->delete('emails_spisstatuses');
		}

		// delete associations
		$this->isirdb->where_in('watched_id', $ids);
		$this->isirdb->delete('spis2users_watches');

		// delete account changes
		$this->isirdb->where_in('user_watch_id', $ids);
		$this->isirdb->delete('users_watches_accounts');

		// delete watches
		$this->isirdb->where_in('id', $ids);
		$this->isirdb->delete('users_watches');		
	}

    public function mark_new_vat_debtors($vatDebtors_dic) {
		// mark only those that are not currently debtors
		for ($i = 0; $i < sizeof($vatDebtors_dic); $i = $i + $this->pagesize) { 
			$this->isirdb->where_in('dic', array_slice($vatDebtors_dic, $i, $this->pagesize));
			$this->isirdb->where('has_vat_debtor', 0);
	
			$update_data = array(
				"has_vat_debtor" => 1,
				"date_vat_debtor" => date("Y-m-d H:i:s")
			);
	
			$this->isirdb->update('users_watches', $update_data);	
		}
	}

	public function unmark_vat_debtors($vatDebtors_dic) {
		// unmark only those that are currently debtors
		for ($i = 0; $i < sizeof($vatDebtors_dic); $i = $i + $this->pagesize) { 
			$this->isirdb->where_not_in('dic', array_slice($vatDebtors_dic, $i, $this->pagesize));
			$this->isirdb->where('has_vat_debtor', 1);

			$update_data = array(
				"has_vat_debtor" => 0,
				"date_vat_debtor" => $this->config->item('START_YEAR')
			);

			$this->isirdb->update('users_watches', $update_data);
		}
	}

    public function get_last_checked_likvidace_spis() {
		$this->isirdb->order_by('last_likvidace_check', 'asc');
		$this->isirdb->limit(300);

		$this->isirdb->where('ic <> 0');
		return $this->isirdb->get('users_watches')->result_array();
	}

	public function savelastid($value, $logtable) {
        $this->isirdb->where('id', 1);
        $this->isirdb->update($logtable, array("value" => $value));
	}

	public function getspiscommon($id) {
		$ret = NULL;

		$this->isirdb->select('
			spis.name AS subject_name, 
			spis.address AS address, 
			spis_status.name AS status, 
			spis_status.id AS status_id, 
			senat_number AS senat_number,
			number_prefix, 
			number_year, 
			number_id, 
			spis.id AS id, 
			spis.ic AS ic, 
			spis.rc AS rc, 
			spis.birthdate AS birthdate, 
			courts.name AS court,
			courts.fullname AS court_fullname');
		$this->isirdb->join('spis_status', 'spis.status_id = spis_status.id');
		$this->isirdb->join('courts', 'spis.court_id = courts.id');

		$query = $this->isirdb->get_where('spis', array("spis.id" => $id));
		$ret = $query->first_row();

		// get relating spis items
		if ($ret != null) {
			if ($ret->ic != 0 || $ret->rc != 0) {
				$this->isirdb->where('ic', $ret->ic);
				$this->isirdb->where('rc', $ret->rc);
				$this->isirdb->join('courts', 'spis.court_id = courts.id');
				$this->isirdb->join('spis_status', 'spis.status_id = spis_status.id');
				$this->isirdb->select(''
					. 'courts.name AS court, '
					. 'senat_number, '
					. 'number_id, '
					. 'number_year, '
					. 'number_prefix, '
					. 'status_id, '
					. 'spis.id AS id, '
					. 'spis.name AS subject_name, '
					. 'spis_status.name AS status');
				$relating = $this->isirdb->get('spis');
				$ret->relating = $relating->result();
			} else {
				$ret->relating = array();
				$ret->relating[0] = $ret;
			}
		}

		return $ret;
	}
	
	public function getspisid($number, $year, $doinsert = true) {
		$ret = -1;

		$this->isirdb->where('number_id', $number);
		$this->isirdb->where('number_year', $year);
		$query = $this->isirdb->get('spis');
		
		if (($query->num_rows() == 0) && ($doinsert == true)) {
			// it does not exist yet, create it
			$newdata = array(	
				"number_id" => $number,
				"number_year" => $year,
				"number_prefix" => $this->config->item('SPIS_PREFIX')
			);
			$this->isirdb->insert('spis', $newdata);
			$ret = $this->isirdb->insert_id();
		} else {
			$ret = $query->first_row()->id;
		}

		return $ret;
	}

	public function getEventCreditor($spis_id, $order_id) {
		$result = null;
		
		$this->isirdb->where('spis_id', $spis_id);
		$this->isirdb->where('order_id', $order_id);
		$creditorRow = $this->isirdb->get('spis_data_creditors')->first_row();
		
		if ($creditorRow != null) {
			// delete temporary record
			$this->isirdb->where('id', $creditorRow->id);
			$this->isirdb->delete('spis_data_creditors');
			
			$result = $creditorRow->creditor_id;
		}
		
		return $result;
	}

	public function spis_assign($spisdata, $spis_id) {
		// get appropriate watches which do not exist yet
		$watches_queried = false;
		$watches = array();
		
		if (($spisdata['rc'] != 0) || ($spisdata['ic'] != 0)) {
			if (($spisdata['rc'] == 0) || ($spisdata['ic'] == 0)) {
				// if any is 0 use and logic
				if ($spisdata['ic'] != 0) {
					$this->isirdb->where('ic', $spisdata['ic']);
				}
				
				if ($spisdata['rc'] != 0) {
					$this->isirdb->where('rc', $spisdata['rc']);
				}
			} else {
				// if both are filled use or logic
				$this->isirdb->or_where('ic', $spisdata['ic']);
				$this->isirdb->or_where('rc', $spisdata['rc']);
			}

			$watches = $this->isirdb->get('users_watches')->result_array();
			$watches_queried = true;
		}
		
		// query by birthdate too
		if (($spisdata['birthdate'] != '0000-00-00') && ($spisdata['birthdate'] != '') && ($spisdata['surname'] != '')) {
			$this->isirdb->where('birthdate', $spisdata['birthdate']);
			$this->isirdb->where('birthdate <> ', '0000-00-00');
			$this->isirdb->where('name', $spisdata['surname']);
			
			// TODO: solve cases when twins are submitted by follwing proposal:
			/*
			if ($spisdata['firstname'] != '') {
				$this->isirdb->where('firstname', $spisdata['firstname']); OR where firstname is empty
			}
			*/
			
			$watches_birthdate = $this->isirdb->get('users_watches')->result_array();

			if ($watches_queried == true) {
				// merge with previous watches
				foreach($watches_birthdate as $watch_birthdate) {
					$is_in_watches = false;
					foreach($watches as $watch) {
						if ($watch_birthdate['id'] == $watch['id']) {
							$is_in_watches = true;
							break;
						}
					}
					
					if ($is_in_watches == false) {
						array_push($watches, $watch_birthdate);
					}
				}
			} else {
				$watches = $watches_birthdate;
				$watches_queried = true;
			}
		}
		
		if (($watches_queried == true) && (sizeof($watches) > 0)) {
			// associate found watches with the newly added record
			foreach ($watches as $i) {
				$adddata = array(
					"spis_id" => $spis_id,
					"watched_id" => $i['id']
				);

				// check if the watches does not exist already?
				$existsquery = $this->isirdb->get_where('spis2users_watches', $adddata);
				if ($existsquery->num_rows() == 0) {
					// it does not exist - create it				
					$this->isirdb->insert('spis2users_watches', $adddata);
				}
			}
		}		
	}

    public function get_last_checked_dic() {
		$this->isirdb->order_by('date_dic_check', 'asc');
		$this->isirdb->limit(300);

		$this->isirdb->where('ic <> 0');
		return $this->isirdb->get('users_watches')->result_array();
	}

	public function generate_dic_by_rc() {
		$this->isirdb->order_by('date_dic_check', 'asc');
		$this->isirdb->limit(1000);

		$this->isirdb->where('rc <> 0');
		$this->isirdb->where('ic', 0);
		$watches_rc = $this->isirdb->get('users_watches')->result_array();

		foreach ($watches_rc as $watch) {
			$this->isirdb->where('id', $watch['id']);
			$update_data = array(
				"dic" => $watch['rc'],
				"date_dic_check" => date("Y-m-d H:i:s")
			);

			$this->isirdb->update('users_watches', $update_data);
		}
	}


	public function getCreditorId($name) {
		$creditor_data = array('name' => $name);

		$this->isirdb->select('id');
		$result = $this->isirdb->get_where('spis_creditors', $creditor_data)->first_row();
		
		if ($result == null) {
			$result = $this->insert_into_table('spis_creditors', $creditor_data);
		} else {
			$result = $result->id;
		}
		
		return $result;
	}

    public function getclaimwatchusers($ic) {
        $this->isirdb->where('ic', $ic);
        $this->isirdb->where('from < ', date("Y-m-d H:i:s"));
        $this->isirdb->where('to', null);

        $this->isirdb->select('id');
        return $this->isirdb->get('users_creditors')->result_array();
	}
	
    public function addclaimwatchnews($users_creditor_id, $spis_id, $oddil, $date) {
        // create object with 'key' properties only to ease finding related object
        $key = array(
            "users_creditor_id" => $users_creditor_id,
            "spis_id" => $spis_id,
            "oddil" => $oddil,
        );

        // check whether it does not already exist
        $this->isirdb->where($key);
        $existing = $this->isirdb->get('users_creditors_news')->first_row();

        // make data to insert/update by extending key with non key properties
        $data = $key;
        $data["date"] = $date;
        $data["modified_on"] = date("Y-m-d H:i:s");

        if ($existing == null) {
            // insert it
            $this->isirdb->insert('users_creditors_news', $data);
        } else {
            // update it
            $this->isirdb->where('id', $existing->id);
            $this->isirdb->update('users_creditors_news', $data);
        }
	}
	
	public function remove_supervisor($spis_id, $idOsoby, $date_end) {
		$this->isirdb->set('spis2spis_supervisors.date_end', $date_end);

		$this->isirdb->where("spis_supervisors.personIdentifier", $idOsoby);
		$this->isirdb->where("spis2spis_supervisors.spis_id", $spis_id);
		$this->isirdb->where("spis2spis_supervisors.date_end IS NULL", null, false);
		
		$this->isirdb->update('spis2spis_supervisors '
			. 'JOIN spis_supervisors ON spis2spis_supervisors.spis_supervisor_id = spis_supervisors.id');
	}

	public function update_creditor($spis_id, $order_id, $creditorId) {
		$this->isirdb->where('spis_id', $spis_id);
		$this->isirdb->where('order_id', $order_id);
		$this->isirdb->update('spis_data', array("creditor_id" => $creditorId));
	}

	public function add_supervisor($spis_id, $persondata, $date_start, $supervisor_type_id) {
		$supervisor_id = $this->update_or_insert_in_table(
			'spis_supervisors', 
			"personIdentifier", 
			$persondata['personIdentifier'], 
			$persondata,
			'id');
		
		// check if the same record does not already exist
		// we search for a record date_end = NULL means that supervisor is active on spis
		// we do not care about date_start as we only search for active presence and overwriting
		// date is not important
		$this->isirdb->where('spis_id', $spis_id);
		$this->isirdb->where('spis_supervisor_id', $supervisor_id);
		$this->isirdb->where('date_end IS NULL', NULL, false);
		$this->isirdb->where('spis_supervisor_type_id', $supervisor_type_id);
		$first_row = $this->isirdb->get('spis2spis_supervisors')->first_row();
		
		if ($first_row == null) {
			$added_data = array(
				"spis_id" => $spis_id,
				"spis_supervisor_id" => $supervisor_id,
				"date_start" => $date_start,
				"date_end" => NULL,
				"spis_supervisor_type_id" => $supervisor_type_id
			);
			$this->isirdb->insert('spis2spis_supervisors', $added_data);
		}
	}

	public function getwithoutcourt($skip = 0) {
		$this->isirdb->limit(40, $skip);
		$this->isirdb->order_by('id', 'desc');
		$this->isirdb->where('court_id', 0);
		return $this->isirdb->get('spis')->result_array();
	}

	public function addcourt($id, $court) {
		// insert court
		$this->isirdb->where('sourcename', $court);
		$defined = $this->isirdb->get('courts')->result_array();
		
		if (sizeof($defined) == 0) {
			$this->isirdb->insert('courts', array("sourcename" => $court));
			$court_id = $this->isirdb->insert_id();
		} else {
			$court_id = $defined[0]['id'];
		}
		
		// assign it
		$this->isirdb->where('id', $id);
		$this->isirdb->update('spis', array("court_id" => $court_id));
	}

    public function mapwatch2spis($ic, $rc, $birthdate, $surname, $watch_id, $needcheck = false) {	
        if (($ic != '') || ($rc != '') || (($birthdate != '') && ($birthdate != '0000-00-00'))) {
            $matches = $this->searchspis($ic, $rc, $birthdate, $surname);

            // Link the matches together
            foreach ($matches as $i) {
                $watch = array(
                    "spis_id" => $i,
                    "watched_id" => $watch_id		
                );	

                $insertdata = true;
                if ($needcheck == true) {
                    // check if such watch already exists
                    $exists = $this->isirdb->get_where('spis2users_watches', $watch);
                    $insertdata = $exists->num_rows() == 0;
                }

                if ($insertdata == true) {
                    $this->isirdb->insert('spis2users_watches', $watch);					
                }
            }
        }		
	}
	
    public function searchspis($ic, $rc, $birthdate, $surname) {
        // actual spis data
        $this->prepare_mapwatch2spis($ic, $rc, $birthdate, $surname);
        $this->isirdb->select('id');
        $matches_original = $this->isirdb->get('spis')->result_array();

        // other subjects in spis
        $this->prepare_mapwatch2spis($ic, $rc, $birthdate, $surname);
        $this->isirdb->select('spis_id');
        $matches_other = $this->isirdb->get('spis_subjects')->result_array();

        // merge actual spis data with other subjects in spises
        $matches = array();
        foreach($matches_original as $match_original) {
            array_push($matches, $match_original['id']);
        }

        foreach($matches_other as $match_other) {
            if (!in_array($match_other['spis_id'], $matches)) {
                array_push($matches, $match_other['spis_id']);
            }
        }

        return $matches;
    }

	public function getwithoutsenat($skip = 0) {
		$this->isirdb->limit(100, $skip);
		$this->isirdb->order_by('id', 'desc');
		$this->isirdb->where('senat_number IS NULL', null, false);
		return $this->isirdb->get('spis')->result_array();
	}

	public function addsenat($id, $senat) {
		$this->isirdb->where('id', $id);
		$this->isirdb->update('spis', array("senat_number" => $senat));
	}

    public function get_watches_for_accounts_update() {
        $this->isirdb->order_by('date_account_check');
        $this->isirdb->limit(100);
        $this->isirdb->select('id, dic');
        $this->isirdb->where("dic <> 0", null, false);

        return $this->isirdb->get('users_watches')->result_array();
	}
	
    public function update_watches_for_accounts($idlist) {
        $this->isirdb->where_in('id', $idlist);
        $this->isirdb->update('users_watches', array("date_account_check" => date("Y-m-d H:i:s")));
	}
	
    public function get_accounts_for_watch($id) {
        $this->isirdb->where("user_watch_id", $id);
        return $this->isirdb->get('users_watches_accounts')->result_array();
    }

    public function get_watches_for_accounts_change_update() {
        $this->isirdb->order_by('date_has_account_change_check');
        $this->isirdb->limit(1000);
        $this->isirdb->select('id');

        $query = $this->isirdb->get('users_watches')->result_array();
		return $this->extract_field($query, 'id');
	}
	
    public function update_watches_for_accounts_change($idlist) {
        $this->isirdb->where_in('id', $idlist);
        $this->isirdb->update('users_watches', array("date_has_account_change_check" => date("Y-m-d H:i:s")));
	}
	
    public function calculate_has_account_change($idlist) {
        $has_account_change_days = 30;
        $idlist_comma_separated = implode(",", $idlist);
        $this->isirdb->query("
            UPDATE 
              users_watches uw
            SET
              has_account_change = EXISTS(
                SELECT 
                  NULL 
                FROM 
                  users_watches_accounts uwa 
                WHERE 
                  uwa.user_watch_id = uw.id
                  AND (uwa.date_start > DATE_SUB(CURDATE(), INTERVAL ". $has_account_change_days ." DAY) 
                      OR uwa.date_end > DATE_SUB(CURDATE(), INTERVAL ". $has_account_change_days ." DAY)))
            WHERE uw.id IN (". $idlist_comma_separated  .")");
	}
	
	public function fillSpisStatusUpdateQueue() {
		// get date from
		$this->isirdb->where('id', 1);
		$dateFrom = $this->isirdb->get('spis_lastupdate_status')->first_row()->value;
		
		// insert distinct spis_ids since date from
		$this->isirdb->query(""
			. "INSERT INTO spis_status_update_queue (spis_id) "
			. "(SELECT DISTINCT spis_id FROM spis_data sd WHERE publishdate > '". $dateFrom ."' "
				. "AND NOT EXISTS("
					. "SELECT "
						. "NULL "
					. "FROM "
						. "spis_status_update_queue q "
					. "WHERE "
						. "q.spis_id = sd.spis_id "
						. "AND q.date > '". $this->spisesForStatusOldUpdate ."'))");
		
		// set new date from
		$this->isirdb->where('id', 1);
		$this->isirdb->update('spis_lastupdate_status', array("value" => date("Y-m-d H:i:s")));
	}

	public function getSpisesForStatusUpdate($moduloDegree, $moduloValue, $take) {
		$this->isirdb->where('MOD(spis_status_update_queue.id, '. $moduloDegree .') = '. $moduloValue, null, false);
		$this->isirdb->where('spis_status_update_queue.date > ', $this->spisesForStatusOldUpdate);
		$this->isirdb->limit($take);
		$this->isirdb->join('spis', 'spis.id = spis_status_update_queue.spis_id');
		$this->isirdb->order_by('spis_status_update_queue.date');
		$this->isirdb->select(''
			. 'spis.number_id AS number_id, '
			. 'spis.number_year AS number_year, '
			. 'spis.id AS spis_id, '
			. 'spis.status_id AS status_id, '
			. 'spis_status_update_queue.id AS id,'
			. 'spis_status_update_queue.date AS sync_date');
		return $this->isirdb->get('spis_status_update_queue')->result_array();        
	}

	public function insertStatusHistory($spis_id, $status_id) {
		$status_history = array(
			"spis_id" => $spis_id,
			"is_last" => true
		);

		// make rest of the rows not last
		$this->isirdb->where($status_history);
		$this->isirdb->update('spis_status_history', array("is_last" => false));

		// add current status to history as the last one
		$status_history['status_id'] = $status_id;

		// insert new row as last
		$this->insert_into_table('spis_status_history', $status_history);
	}

	public function findSpisStatusWithoutLast($take) {
		$this->isirdb->select('spis_id');
		$this->isirdb->having('MAX(is_last)', 0);
		
		$this->isirdb->group_by('spis_id');
		$this->isirdb->limit($take);
		
		return $this->isirdb->get('spis_status_history')->result_array();
	}
	
	public function findSpisStatusWithManyLast($take) {
		$this->isirdb->select('spis_id');
		$this->isirdb->where('is_last', 1);
		$this->isirdb->having('COUNT(*) > ', 1);
		
		$this->isirdb->group_by('spis_id');
		$this->isirdb->limit($take);
		
		return $this->isirdb->get('spis_status_history')->result_array();
	}
	
	public function setLastSpisStatus($spis_id) {
		// set zeroes
		$this->isirdb->where('spis_id', $spis_id);
		$this->isirdb->where('is_last', 1);
		
		$this->isirdb->update('spis_status_history', array("is_last" => 0));
		
		// set last
		$this->isirdb->where('ssh.spis_id', $spis_id);
		$this->isirdb->where('ssh.id', '(SELECT MAX(sshsub.id) FROM (SELECT * FROM spis_status_history) sshsub WHERE sshsub.spis_id = '. $spis_id .' LIMIT 1)', false);
		
		$this->isirdb->update('spis_status_history ssh', array("ssh.is_last" => 1));
	}

	public function getLastImportedSpis() {
		$this->isirdb->where('isimported', true);
		$this->isirdb->order_by('spis_id', 'desc');
		$this->isirdb->limit(1);
		return $this->isirdb->get('spis_subjects')->first_row();
	}

    private function prepare_mapwatch2spis($ic, $rc, $birthdate, $surname) {
        // IC
        if (($ic != '') && ($ic != '0')) {
            $this->isirdb->or_where('ic', $ic);			
        }

        // RC
        $hasRc = $rc != '' && $rc != '0';
        if ($hasRc) {
            $this->isirdb->or_where('rc', $rc);			
        }

        // birthdate
        if (($birthdate != '') && ($birthdate != '0000-00-00') && ($surname != '')) {
            $query_string = 'birthdate = '. $this->isirdb->escape($birthdate) .' AND surname = '. $this->isirdb->escape($surname);
            if ($hasRc) {
                $query_string .= ' AND rc = '. $this->isirdb->escape($rc);
            }

            $this->isirdb->or_where('('. $query_string .')');
        }
	}
	
	public function search($search, $type, $ignoredIds, $ignoredIcs) {
		$result = array();
		
		if (!isUserLoggedIn()) {
			// do not search in isir when not logged in
			return $result;
		}

		$termination_conditions = $this->create_search_termination_conditions($search, $type);

        if (in_array(true, $termination_conditions)) {
            return $result;
        }

        $this->search_omit_ignored($ignoredIds, $ignoredIcs, 'spis', 'spis_subjects');

		// search name
        $this->search_name($search, $type, 'spis_subjects');

        // always equal search fields
        if ($search->ic) {
            $this->isirdb->where('spis_subjects.ic', $search->ic);
        }

        if ($search->spis_mark) {
			$numbers = preg_match_all('!\d+!', $search->spis_mark, $matches);

			if (isset($numbers[0])) {
				$this->isirdb->where('spis.number_id', $numbers[0]);
			}

			if (isset($numbers[1])) {
				$this->isirdb->where('spis.number_year', $numbers[1]);
			}
		}
		
		$this->search_address($search, 'spis_subjects');

        $this->isirdb->select('spis.id, spis_subjects.name, spis_subjects.ic, spis.address, spis.status_id');
		$this->isirdb->order_by('spis_subjects.name_length');
		
        $this->search_limit($type);

		$this->isirdb->join('spis_subjects', 'spis.id = spis_subjects.spis_id');
		$this->isirdb->group_by('spis.id');

        return $this->isirdb->get('spis')->result_array();
	}

    public function get_for_result($result) {
        $result = array();
        $ics = $this->extract_field($result, 'ic');

        if (sizeof($ics) > 0) {
			$this->isirdb->where_in('spis_subjects.ic', $ics);
			$this->isirdb->join('spis_subjects', 'spis.id = spis_subjects.spis_id');
			$this->isirdb->select('spis_subjects.ic, spis.status_id');
	
			$result = $this->isirdb->get('spis')->result_array();
		}

        return $result;
	}
	
	public function get_screening_by_ic($ic) {
		$this->isirdb->where('ic', $ic);
		$this->isirdb->select('status_id, id, name, ic');
		
		return $this->isirdb->get('spis')->first_row('array');
	}

	public function get_screening_by_id($id) {
		$this->isirdb->where('id', $id);
		$this->isirdb->select('status_id, id, name, ic');
		
		return $this->isirdb->get('spis')->first_row('array');
	}

	public function get_detail($id) {
		$this->isirdb->where('spis.id', $id);
		$this->isirdb->select('
			spis.id AS id, 
			spis.name AS name, 
			spis.address AS address, 
			spis.ic AS ic, 
			spis.rc AS rc, 
			spis.number_prefix AS number_prefix, 
			spis.number_id AS number_id, 
			spis.number_year AS number_year,
			spis.senat_number AS senat_number,
			courts.name AS court_name,
			courts.fullname AS court_fullname,
			spis_status.name AS status_name');

		$this->isirdb->join('spis_status', 'spis.status_id = spis_status.id');
		$this->isirdb->join('courts', 'spis.court_id = courts.id');
		
		$ret = $this->isirdb->get('spis')->first_row('array');

		// get relating spis items
		if ($ret != null) {
			if ($ret['ic'] != 0 || $ret['rc'] != 0) {
				$this->isirdb->where('ic', $ret['ic']);
				$this->isirdb->where('rc', $ret['rc']);
				$this->isirdb->where('status_id <> ', $this->config->item('SPIS_STATUS_NOTAVAILABLE'));
				$this->isirdb->join('courts', 'spis.court_id = courts.id');
				$this->isirdb->join('spis_status', 'spis.status_id = spis_status.id');
				$this->isirdb->select(''
					. 'courts.name AS court, '
					. 'senat_number, '
					. 'number_id, '
					. 'number_year, '
					. 'number_prefix, '
					. 'status_id, '
					. 'spis.id AS id, '
					. 'spis.name AS name, '
					. 'spis_status.name AS status');
				$relating = $this->isirdb->get('spis');
				$ret['relating'] = $relating->result_array();
			} else {
				$ret['relating'] = array();
				$ret['relating'][0] = $ret;
			}
		}

		return $ret;
	}

	public function getspissupervisors($spis_id) {
		$this->isirdb->where('spis_id', $spis_id);
		$this->isirdb->where('date_end is NULL', NULL, true);
		$this->isirdb->where('spis_supervisor_type_id < ', $this->config->item('MAX_SUPPORTED_SPIS_SUPERVISOR_TYPE') + 1);
		$this->isirdb->join('spis_supervisors', 'spis2spis_supervisors.spis_supervisor_id = spis_supervisors.id');
		$this->isirdb->join('spis_supervisors_types', 'spis2spis_supervisors.spis_supervisor_type_id = spis_supervisors_types.id');
		$this->isirdb->order_by('spis2spis_supervisors.id', 'desc');
		
		// get only the last result, previous may be incorrect, only the last one is 100% right
		$this->isirdb->limit(1);
		$this->isirdb->select(''
			. 'spis2spis_supervisors.date_start AS date_start,'
			. 'spis_supervisors_types.displayname AS type,'
			. 'spis_supervisors.name AS name,'
			. 'spis_supervisors.address AS address,'
			. 'spis_supervisors.ic AS ic,'
			. 'spis_supervisors.rc AS rc');
		
		return $this->isirdb->get('spis2spis_supervisors')->first_row('array');
	}

	public function getspisevents($id, $section_id) {
		$this->isirdb->join('spis_orders', 'spis_data.order_id = spis_orders.id');
		$this->isirdb->join('spis_texts', 'spis_data.text_id = spis_texts.id');	
		
		if ($section_id == $this->config->item('SECTION_POHLEDAVKY')) {
			$this->isirdb->join('spis_creditors', 'spis_data.creditor_id = spis_creditors.id', 'left');
		}
		
		$this->isirdb->where('spis_id', $id);
		$this->isirdb->where('section_id', $section_id);
		
		$this->isirdb->order_by('publishdate', 'desc');
		
		if ($section_id == $this->config->item('SECTION_POHLEDAVKY')) {
			$this->isirdb->select('spis_orders.name AS order_text, publishdate, spis_texts.text AS text, spis_data.id AS id, spis_data.document AS document, spis_data.is_document_minor AS is_minor, spis_creditors.name AS creditor');
		} else {
			$this->isirdb->select('spis_orders.name AS order_text, publishdate, spis_texts.text AS text, spis_data.id AS id, spis_data.document AS document, spis_data.is_document_minor AS is_minor');
		}
		
		return $this->isirdb->get_where('spis_data', array("spis_id" => $id))->result_array();		
	}

	public function get_isir_sections() {
		$this->isirdb->cache_on();
		$this->isirdb->order_by('id, name, description');
		$result = $this->isirdb->get('spis_sections')->result_array();
		$this->isirdb->cache_off();

		return $result;

	}

	public function get_spis_data_row($id) {
		return $this->get_table_row_by_id('spis_data', 'id', $id);
	}

    public function getimportcomparewatches($user_id, $from_id, $take) {
        $this->isirdb->where('user_id', $user_id);
        $this->isirdb->where('id > ', $from_id);
        $this->isirdb->limit($take);
        $this->isirdb->select('id, ic, rc, name, birthdate');
        $this->isirdb->order_by('id');
        return $this->isirdb->get('users_watches')->result_array();
    }

    /**
    * Adds a watch item
    */
    public function add_watch_item($watchdata, $surname) {
        $ret = 0;

        // convert rc to birthdate if this one is not explicitly set
        if (($watchdata['birthdate'] == '' || $watchdata['birthdate'] == '0000-00-00') 
            && ($watchdata['rc'] != 0 && is_numeric($watchdata['rc']))) {

            $birthdate = $this->content_lib->verifyRC($watchdata['rc'], false);
            if ($birthdate != false) {
                $watchdata['birthdate'] = $birthdate;
            }
        }

        // is this already watched?

        // rc and ic check
        $ic_rc_array = array();
        if (($watchdata['ic'] != 0) && (is_numeric($watchdata['ic']))) {
            array_push($ic_rc_array, "ic = ". $this->db->escape($watchdata['ic']));
        }

        if (($watchdata['rc'] != 0) && (is_numeric($watchdata['rc']))) {
            array_push($ic_rc_array, "rc = ". $this->db->escape($watchdata['rc']));
        }

        // birthdate with name check
        if (($watchdata['birthdate'] != '') && ($watchdata['birthdate'] != '0000-00-00') && ($watchdata['name'] != '')) {
            $birthdatequery = "birthdate = ". $this->db->escape($watchdata['birthdate']) ." AND name = ". $this->db->escape($watchdata['name']);

            if ($watchdata['firstname'] != '') {
                $birthdatequery = $birthdatequery . " AND firstname = ". $this->db->escape($watchdata['firstname']);
            }

            array_push($ic_rc_array, '('. $birthdatequery .')');
        }

        $record_exists = false;

        if (sizeof($ic_rc_array) > 0) {
            $ic_rc_query = implode(" OR ", $ic_rc_array);

            $query_string = "
                SELECT 
                    * 
                FROM
                    users_watches
                WHERE 
                    (". $ic_rc_query .") AND
                    user_id = ". $this->db->escape($watchdata['user_id']) ."
            ";
            $query = $this->isirdb->query($query_string);
            $record_exists = $query->num_rows() > 0;
        }

        if ($record_exists == FALSE) {
            // insert it
            $this->isirdb->insert('users_watches', $watchdata);
            $watch_id = $this->isirdb->insert_id();

            // map it
            $this->mapwatch2spis($watchdata['ic'], $watchdata['rc'], $watchdata['birthdate'], $surname, $watch_id);
            $ret = $watch_id;
        } else {
            $updated = $query->first_row();
            // update it
            $this->isirdb->where('id', $updated->id);

            $updated->rc = $watchdata['rc'];
            $updated->ic = $watchdata['ic'];
            $updated->name = $watchdata['name'];
            $updated->firstname = $watchdata['firstname'];
            $updated->birthdate = $watchdata['birthdate'];
            $updated->note = $watchdata['note'];
            $updated->clientname = $watchdata['clientname'];

            $this->isirdb->update('users_watches', $updated);
        }

        return $ret;
    }

    /**
    * Updates a watch in the database
    */
    public function edit_watch_item($id, $watchdata) {
		// check whether the ic or rc has been changed
		$oldwatch = $this->get_table_row_by_id('users_watches', 'id', $id);
		$attributes_changed = ($oldwatch->ic != $watchdata['ic']) 
			|| ($oldwatch->rc != $watchdata['rc'])
			|| ($oldwatch->birthdate != $watchdata['birthdate'])
			|| ($oldwatch->name != $watchdata['name']);

		// update the watch itself
		$this->update_row_in_table('users_watches', 'id', $id, $watchdata);

		// re-map it if needed
		if ($attributes_changed == true) {
			// clean old
			$this->isirdb->where('watched_id', $id);
			$this->isirdb->delete('spis2users_watches');

			// associate new
			$this->mapwatch2spis($watchdata['ic'], $watchdata['rc'], $watchdata['birthdate'], $watchdata['name'], $id);
		}
	}
	
	public function get_watch($userId, $id) {
		$this->isirdb->where('user_id', $userId);
		$this->isirdb->where('id', $id);

		return $this->isirdb->get('users_watches')->first_row();
	}

    /**
    * Gets the watched files by the user ID, starting from specified offset
    * Also filters data if necessary by the filter string (is considered as ic, rc or name)
    */
    public function getwatchesbyuserid($userdata, $offset, $pagesize, $filter, $orderid) {
		$user_id = $userdata['id'];
		for ($i = 0; $i < 2; $i++) {

			// get watches now
			$where = 'users_watches.user_id = '. $user_id;
			if ($filter != '') {
				$namefilter = '';
				if (ctype_digit($filter) == false) {
					$filter = $this->isirdb->escape_like_str($filter);
				}

				if ($userdata['is_automatic_filling_enabled'] == 0) {
					$namefilter = 'users_watches.name LIKE "'. $filter .'%" OR users_watches.firstname LIKE "'. $filter .'%"';
				} else {
					$namefilter = 'users_watches.official_name LIKE "'. $filter .'%"';
				}

				if (ctype_digit($filter) == true) {
					$where = $where 
							.' AND (users_watches.ic = '. $filter
							.' OR users_watches.rc = '. $filter
							.' OR '. $namefilter .')';
				} else {
					$where = $where .' AND '. $namefilter;
				}
			}

			$this->isirdb->where($where);

			// order the data
			switch ($orderid) {
				case $this->config->item('ORDER_NAME'): 
					if ($userdata['is_automatic_filling_enabled'] == 0) {
						$this->isirdb->order_by('users_watches.name');				
					} else {
						$this->isirdb->order_by('users_watches.official_name');				
					}
					break;
				case $this->config->item('ORDER_INSERT'): 
					$this->isirdb->order_by('users_watches.id');				
					break;
			}		

			if ($i == 0) {
				$this->isirdb->group_by('users_watches.id');
				$this->isirdb->limit($pagesize, $offset);					
				$this->buildselectforwatches();		

				// get the rows for the first page
				$ret = $this->isirdb->get('users_watches')->result_array();				
			} else {
				// get the number of rows
				$this->isirdb->select("COUNT(id) AS size");
				$ret[0]['size'] = $this->isirdb->get("users_watches")->first_row()->size;						
			}
		}

		return $ret;
	}

    /**
    * Makes select and join statement for the watches query
    */
    private function buildselectforwatches() {
        $this->isirdb->join('spis2users_watches', 'users_watches.id = spis2users_watches.watched_id', 'left');
        $this->isirdb->join('spis', 'spis2users_watches.spis_id = spis.id', 'left');

        $this->isirdb->select('
            users_watches.id AS id, 
            users_watches.name AS name, 
            users_watches.ic AS ic, 
            users_watches.rc AS rc, 
            users_watches.birthdate AS birthdate, 
            users_watches.firstname AS firstname, 
            users_watches.note AS note,
            users_watches.clientname AS clientname,
            has_likvidace AS likvidace,
            has_vat_debtor AS vatdebtor,
            has_account_change AS account_change,
            GROUP_CONCAT(spis.id, "|", spis.status_id SEPARATOR ",") AS spises,
            users_watches.official_name AS official_name'
        , false);						
    }

}