<?php

class Or_model extends Base_model {

    function __construct() {
        parent::__construct();

        $CI = & get_instance();
        $CI->ordb = $this->load->database('ordb', TRUE);
        $this->ordb = $CI->ordb;
        $this->dboverride = $CI->ordb;
    }
    
    public function createorupdatesubject($subject) {
        return $this->createorupdatesubjectentity('subjects', 'justice_id', $subject);
    }

    public function createorupdatesubjectdetail($subject_detail) {
        return $this->createorupdatesubjectentity('subjects_detail', 'subject_id', $subject_detail);
    }
    
    public function get_highest_or_id() {
        $this->ordb->limit(1);
        $this->ordb->order_by('justice_id', 'desc');
        $this->ordb->select('justice_id');
        $result = $this->ordb->get('subjects')->first_row();

        return $result == NULL ? 0 : $result->justice_id;
    }

    public function get_ids_for_update($count, $skip = 0) {
        // involve only NOT watched subjects
        $this->ordb->order_by('checkdate');
        $this->ordb->limit($count, $skip);
        $this->ordb->select('justice_id');
        $this->ordb->where('NOT EXISTS (SELECT NULL FROM subjects_watches sw WHERE sw.ic = subjects.ic)', '', FALSE);

        $query = $this->ordb->get('subjects')->result_array();
        return $this->extract_field($query, 'justice_id');
    }

    public function get_watched_ids_for_update($count, $skip = 0) {
        // involve only watched subjects
        $this->ordb->order_by('checkdate');
        $this->ordb->limit($count, $skip);
        $this->ordb->select('subjects.justice_id AS justice_id');
        $this->ordb->group_by('subjects.justice_id');
        $this->ordb->join('subjects_watches', 'subjects_watches.ic = subjects.ic');
        $this->ordb->where('subjects.ic <> ', 0);

        $query = $this->ordb->get('subjects')->result_array();
        return $this->extract_field($query, 'justice_id');
    }

    public function get_watched($currentId, $limit) {
        $this->ordb->where('id > ', $currentId);
        $this->ordb->limit($limit);
        $this->ordb->order_by('id');

        $this->ordb->select('id, watched_id');

        return $this->ordb->get('subjects_watches')->result_array();
    }

    public function get_watched_for_last_date_update($count) {
        $this->ordb->order_by('last_check_date');
        $this->ordb->limit($count);
        $this->ordb->select('id, ic');

        return $this->ordb->get('subjects_watches')->result_array();
    }

    public function update_change_dates($id, $max_date, $last_change_date, $content) {
        $data = array(
            'max_date' => $max_date,
            'last_content' => $content,
            'last_change_date' => $last_change_date,
            'last_check_date' => date('Y-m-d H:i:s')
        );

        $this->ordb->where('id', $id);
        $this->ordb->update('subjects_watches', $data);
    }

    public function update_last_check_date($id) {
        $data = array(
            'last_check_date' => date('Y-m-d H:i:s')
        );

        $this->ordb->where('id', $id);
        $this->ordb->update('subjects_watches', $data);
    }

    public function is_last_update_content_changed($id, $content) {
        $this->ordb->where('id', $id);
        $this->ordb->select('last_content');

        $result = $this->ordb->get('subjects_watches')->first_row();
        return $result == null || $result->last_content != $content;
    }

    public function delete_missing_watches($missing_watched_ids) {
        if (sizeof($missing_watched_ids) == 0) {
            return;
        }

        $this->ordb->where_in('watched_id', $missing_watched_ids);
        $this->ordb->delete('subjects_watches');
    }

    public function find_missing_watches($subjects) {
        $missing = array();

        if (sizeof($subjects) == 0) {
            return $missing;
        }

        $idList = $this->extract_field($subjects, 'id');

        $this->ordb->where_in('watched_id', $idList);
        $this->ordb->select('watched_id');
        
        $results = $this->ordb->get('subjects_watches')->result_array();

        foreach ($subjects as $s) {
            $found = false;
            foreach ($results as $r) {
                if ($r['watched_id'] == $s['id']) {
                    $found = true;
                    break;
                }
            }

            if ($found == false) {
                $item = array(
                    'watched_id' => $s['id'],
                    'ic' => $s['ic']
                );
    
                array_push($missing, $item);
            }
        }

        return $missing;
    }

    public function update_existing_watches($subjects) {
        foreach($subjects as $s) {
            $this->ordb->where('watched_id', $s['id']);
            $this->ordb->update('subjects_watches', array('ic' => $s['ic']));
        }
    }

    public function insert_new_watches($subjects) {
        if (sizeof($subjects) == 0) {
            return;
        }

        $this->ordb->insert_batch('subjects_watches', $subjects);
    }

    public function getorevents($watches) {
        if (sizeof($watches) === 0) {
            return [];
        }

        $watch_ids = $this->extract_field($watches, 'id');

        $this->ordb->where_in('subjects_watches.watched_id', $watch_ids);
        $this->ordb->where('subjects_watches.last_change_date > subjects_watches.last_notification_date', '', false);
        $this->ordb->where('subjects_watches.ic <> ', 0);
        $this->ordb->join('subjects', 'subjects_watches.ic = subjects.ic');
        
        $this->ordb->select('
            subjects.name AS name,
            subjects.ic AS ic,
            subjects.justice_id AS justiceid,
            subjects_watches.max_date AS changedate,
            subjects_watches.id AS id');

        return $this->ordb->get('subjects_watches')->result_array();
    }
    
    public function mark_events_as_sent($events_to_update, $notification_date) {
        if (sizeof($events_to_update) === 0) {
            return;
        }

        $idList = $this->extract_field($events_to_update, 'id');

        $this->ordb->where_in('id', $idList);
        $this->ordb->update('subjects_watches', array('last_notification_date' => $notification_date));
    }

    public function get_ic_newer_than($id, $count) {
        $this->ordb->select('ic, id');
        $this->ordb->order_by('id');
        $this->ordb->where('id > ', $id);
        $this->ordb->where('ic <> ', 0); // no need to export IC=0
        $this->ordb->limit($count);
        return $this->ordb->get('subjects')->result_array();
    }

    public function get_subject_for_relations($ic) {
        if ($ic == '0') {
            return null;
        }

        $this->ordb->select('name, address, name_search, name_soundex, name_metaphone, name_length, law_form_id, spis_mark, is_deleted');
        return $this->ordb->get_where('subjects', array("ic" => $ic))->first_row();
    }

    public function update_checkdate($justice_id) {
        $this->ordb->where('justice_id', $justice_id);
        $this->ordb->update('subjects', array("checkdate" => date("Y-m-d H:i:s")));
    }

    public function get_name_by_ic($ic) {
        $this->ordb->where('ic', $ic);
        $this->ordb->select('name');
        $this->ordb->limit(1);

        $result = $this->ordb->get('subjects')->first_row();

        return $result != null ? $result->name : '';
    }

    public function get_newest() {
		$this->ordb->cache_on();

        $this->ordb->where('is_deleted', false);
        $this->ordb->order_by('id', 'desc');
        $this->ordb->limit(10);
        $this->ordb->select('name, ic, address, id');

        $result = $this->ordb->get('subjects')->result_array();

        $this->ordb->cache_off();
        
        return $result;
    }

    public function search_by_ic($ic) {
        $result = null;
        $formattedIc = intval($ic);
        
        if ($formattedIc != 0) {
            $this->ordb->where('ic',  $formattedIc);
            $this->ordb->select('name');
            $result = $this->ordb->get('subjects')->first_row('array');
        }

        return $result;
    }

    public function search($search, $type, $ignoredIds, $ignoredIcs) {
        $result = array();

		$termination_conditions = $this->create_search_termination_conditions($search, $type);

        // when RC is filled, there's no chance to find anything in OR, exit immediately
        array_push($termination_conditions, $search->rc);

        if (in_array(true, $termination_conditions)) {
            return $result;
        }

        $this->search_omit_ignored($ignoredIds, $ignoredIcs, 'subjects', 'subjects');

        // search name
        $this->search_name($search, $type, 'subjects');

        // always equal search fields
        if ($search->ic) {
            $this->ordb->where('ic', $search->ic);
        }

        if ($search->law_form_id) {
            $this->ordb->where('law_form_id', $search->law_form_id);
        }

        if ($search->spis_mark) {
            $this->ordb->where('spis_mark', $search->spis_mark);
        }

		$this->search_address($search, 'subjects');

        // always reduce to not deleted subjects
        $this->ordb->where('is_deleted', false);

        $this->ordb->select('id, name, ic, address, is_likvidace, is_execution_on_associate');
        $this->ordb->order_by('name_length');

        $this->search_limit($type);

        return $this->ordb->get('subjects')->result_array();
    }

    public function get_for_result($result) {
        $result = array();
        $ics = $this->extract_field($result, 'ic');

        if (sizeof($ics) > 0) {
            $this->ordb->where_in('ic', $ics);
            $this->ordb->select('ic, is_likvidace, is_execution_on_associate');

            $result = $this->ordb->get('subjects')->result_array();
        }

        return $result;
    }

    public function insertmissingsubjects($count) {
        $this->ordb->select('justice_id + 1 AS justice_id', false);
        $this->ordb->where('NOT EXISTS (SELECT 1 FROM subjects t2 WHERE t2.justice_id = subjects.justice_id + 1)', false, false);
        $this->ordb->limit($count);
        
        $justice_ids = $this->ordb->get('subjects')->result_array();

        print_r($justice_ids);

        foreach ($justice_ids as $justice_id) {
            $subject = array(
                "justice_id" => $justice_id['justice_id'],
                "ic" => 0,
                "is_deleted" => 1 // pronouce it deleted until it is properly updated
            );

            $subject_id = $this->createorupdatesubject($subject);

            $subject_detail = array(
                "subject_id" => $subject_id
            );
        
            $this->createorupdatesubjectdetail($subject_detail);
        }
    }

    public function get_detail($id) {
        $this->ordb->where('subjects.id', $id);

        // remove blacklisted (they have law_form with 10 prefix, so they are higher than MIN_BLACKLISTED)
        $this->ordb->where('subjects.law_form_id < ', $this->config->item('MIN_BLACKLISTED'));

        $this->ordb->join('subjects_detail', 'subjects.id = subjects_detail.subject_id');

        $this->ordb->select('
			subjects.id AS id,
			subjects.ic AS ic, 
			subjects.name AS name, 
			subjects.address AS address, 
			subjects.is_likvidace AS is_likvidace, 
			subjects.is_execution_on_associate AS is_execution_on_associate, 
			subjects.is_deleted AS is_deleted, 
			subjects.spis_mark AS spis_mark,
			subjects.justice_id AS justice_id,
			subjects_detail.raw AS raw_data
		');

        return $this->ordb->get('subjects')->first_row('array');
    }

    public function get_raw_data($ic) {
        $this->ordb->where('subjects.ic', $ic);
        $this->ordb->join('subjects', 'subjects.id = subjects_detail.subject_id');

        $this->ordb->select('subjects_detail.raw AS raw');

        return $this->ordb->get('subjects_detail')->first_row();
    }

    public function get_screening_by_ic($ic) {
        $this->ordb->where('ic', $ic);
        $this->get_screening_internal();
    }

    public function get_screening($id) {
        $this->ordb->where('id', $id);
        return $this->get_screening_internal();
    }

    private function get_screening_internal() {
        $this->ordb->select('ic, is_likvidace, is_execution_on_associate, name, justice_id');
        return $this->ordb->get('subjects')->first_row('array');
    }

}
