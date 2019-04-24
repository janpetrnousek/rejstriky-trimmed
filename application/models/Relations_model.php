<?php

class Relations_model extends Base_model {

    function __construct() {
        parent::__construct();

        $CI = & get_instance();
        $CI->relationsdb = $this->load->database('relationsdb', TRUE);
        $this->relationsdb = $CI->relationsdb;
        $this->dboverride = $CI->relationsdb;
    }
    
    public function get_incoming_relations($id, $referencedate, $showrelationcolors) {
        $this->relationsdb->join('relations', 'relations.id = relations2relations.source_id');
        $this->relationsdb->where('relations2relations.destination_id', $id);

        return $this->get_relations($referencedate, $showrelationcolors);
    }

    public function get_outgoing_relations($id, $referencedate, $showrelationcolors) {
        $this->relationsdb->join('relations', 'relations.id = relations2relations.destination_id');
        $this->relationsdb->where('relations2relations.source_id', $id);

        return $this->get_relations($referencedate, $showrelationcolors);
    }

    public function update_relations_count($id) {
        // incoming query
        $this->relationsdb->join('relations2relations', 'relations2relations.destination_id = relations.id', 'left');
        $this->prepare_relations_count_query_result($id);
        $incoming = $this->relationsdb->get('relations')->first_row();

        // outgoing query
        $this->relationsdb->join('relations2relations', 'relations2relations.source_id = relations.id', 'left');
        $this->prepare_relations_count_query_result($id);
        $outgoing = $this->relationsdb->get('relations')->first_row();

        $count = 0;
        if ($incoming != NULL) {
            $count += $incoming->relations;
        }

        if ($outgoing != NULL) {
            $count += $outgoing->relations;
        }

        // update it
        $this->relationsdb->where('id', $id);
        $this->relationsdb->update('relations', array('active_relations' => $count));
    }

    public function createorupdatesubject($subject, $deleterelations) {
        // does it exist
        if (isset($subject['ic'])) {
            $this->relationsdb->where('ic', $subject['ic']);
        } else {
            if (isset($subject['birthdate'])) {
                $this->relationsdb->where('birthdate', $subject['birthdate']);
            } else {
                $this->relationsdb->where('address', $subject['address']);
            }

            $this->relationsdb->where('name', $subject['name']);
        }

        $row = $this->relationsdb->get('relations')->first_row();

        $id = 0;
        if ($row == null) {
            // create new record
            $this->relationsdb->insert('relations', $subject);
            $id = $this->relationsdb->insert_id();
        } else {
            // update record
            $id = $row->id;
            $this->relationsdb->where('id', $id);
            $this->relationsdb->update('relations', $subject);

            if ($deleterelations) {
				//commented out to preserve relations such as (Novigo <> Shanna)
                //$this->relationsdb->or_where('source_id', $id);
                $this->relationsdb->or_where('destination_id', $id);
                $this->relationsdb->delete('relations2relations');
            }
        }

        return $id;
    }

    public function add_relation($relation) {
        $this->relationsdb->insert('relations2relations', $relation);
    }

    public function get_relation($id) {
        return $this->relationsdb->get_where('relations', array('id' => $id))->first_row('array');
    }

    public function search($search, $type, $ignoredIds) {
        $result = array();

		$termination_conditions = $this->create_search_termination_conditions($search, $type);

        // when RC is filled, there's no chance to find anything in relations, exit immediately
        array_push($termination_conditions, $search->rc);

        if (in_array(true, $termination_conditions)) {
            return $result;
        }

        $this->search_omit_ignored($ignoredIds, array(), 'relations', '');

        // search name
        $this->search_name($search, $type, 'relations');

        // always equal search fields
        if ($search->ic) {
            $this->relationsdb->where('ic', $search->ic);
        }

        if ($search->law_form_id) {
            $this->relationsdb->where('law_form_id', $search->law_form_id);
        }

        if ($search->spis_mark) {
            $this->relationsdb->where('spis_mark', $search->spis_mark);
        }

        if (!isUserLoggedIn()) {
            $this->relationsdb->where('ic IS NOT NULL', false, false);
        }

		$this->search_address($search, 'relations');

        // always reduce to not deleted and subjects with some relations
        $this->relationsdb->where('is_deleted', false);
        $this->relationsdb->where('active_relations > ', 0);

        $this->relationsdb->select('id, name, ic, address');
        $this->relationsdb->order_by('name_length');

        $this->search_limit($type);

        return $this->relationsdb->get('relations')->result_array();
    }

    public function get_statutars($ic) {
        $types = array_merge($this->config->item('STATUTAR_RELATION_TYPES'), $this->config->item('SPOLECNIK_RELATION_TYPES'));
        $this->relationsdb->where_in('relations2relations.relation_type_id', $types);
        $this->relationsdb->where('relations2relations.date_end', NULL);
        $this->relationsdb->where('relations.ic', $ic);

        $this->relationsdb->join('relations2relations', 'relations.id = relations2relations.destination_id');
        $this->relationsdb->join('relations AS target', 'relations2relations.source_id = target.id');
        $this->relationsdb->join('relations_types', 'relations_types.id = relations2relations.relation_type_id');
        $this->relationsdb->select('CONCAT(target.name, ",", relations_types.name) AS name, relation_type_id', false);
        $this->relationsdb->distinct();

        return $this->relationsdb->get('relations')->result_array();
    }

    public function get_relation_by_ic($ic) {
        $ret = $this->relationsdb->get_where('relations', array('ic' => $ic))->result_array();
        return sizeof($ret) ? $ret[0] : null;
    }
    
    public function get_change_dates($id) {
        $this->relationsdb->or_where('source_id', $id);
        $this->relationsdb->or_where('destination_id', $id);
        $this->relationsdb->distinct();
        $this->relationsdb->select('date_start, date_end');
        $query = $this->relationsdb->get('relations2relations')->result_array();

        $result = array();
        foreach ($query as $row) {
            $result = $this->add_change_date($row['date_start'], $result);
            $result = $this->add_change_date($row['date_end'], $result);
        }

        $result = $this->add_change_date(date("Y-m-d"), $result);

        sort($result);
        for ($i = 0; $i < sizeof($result); $i++) {
            $result[$i] = date("d.m.Y", strtotime($result[$i]));
        }

        return $result;
    }

    public function get_visible_relation_types() {
		$this->relationsdb->cache_on();

        $this->relationsdb->or_where('display_name', 'Vlastník / Zřizovatel');
        $this->relationsdb->or_where('display_name', 'Člen statutárního orgánu');

        $this->relationsdb->group_by('display_name');
        $this->relationsdb->select('display_name, color');

        $result = $this->relationsdb->get('relations_types')->result_array();

        $this->relationsdb->cache_off();

        return $result;
    }

    private function add_change_date($date, $result) {
        if ($date != NULL && !in_array($date, $result)) {
            array_push($result, $date);
        }

        return $result;
    }

    private function get_relations($referencedate, $showrelationcolors) {
        $this->relationsdb->select('
            relations.id AS id, 
            relations.ic AS ic, 
            relations.name AS name, 
            relations.address AS address, 
            relations.active_relations AS active_relations,
            relations2relations.date_start AS relation_date_start, 
            relations_types.display_name AS relation_type_name,
            relations_types.display_order AS relation_type_order,
            relations_types.name AS relation_type_detail_name,
            relations_types.color AS relation_type_color');

        $this->relationsdb->join('relations_types', 'relations_types.id = relations2relations.relation_type_id');

        if ($referencedate != NULL) {
            $this->relationsdb->where('relations2relations.date_start <=', $referencedate);
            $this->relationsdb->where('
				(relations2relations.date_end >= ' . $this->relationsdb->escape($referencedate) . ' OR relations2relations.date_end IS NULL)', null, false);
        } else {
            // optimization, don't ask about start date, we are fine with relations which have no end so far
            $this->relationsdb->where('relations2relations.date_end IS NULL', null, false);
        }

        if ($showrelationcolors != NULL) {
            $this->relationsdb->where_in('relations_types.color', $showrelationcolors);
        }

        $this->relationsdb->group_by('id, relation_type_name');

        return $this->relationsdb->get('relations2relations')->result_array();
    }

    private function prepare_relations_count_query_result($id) {
        $this->relationsdb->distinct();
        $this->relationsdb->select('COUNT(relations2relations.id) AS relations');

        $this->relationsdb->where('relations.id', $id);
        $this->relationsdb->where('relations2relations.date_end', NULL);

        $this->relationsdb->group_by('relations.id');
    }

}
