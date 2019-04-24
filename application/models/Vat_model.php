<?php

class Vat_model extends Base_model {

    function __construct() {
        parent::__construct();

        $CI = & get_instance();
        $CI->vatdb = $this->load->database('vatdb', TRUE);
        $this->vatdb = $CI->vatdb;
        $this->dboverride = $CI->vatdb;
    }

    public function createorupdatesubject($subject, $idname = 'ic') {
        return $this->createorupdatesubjectentity('subjects', $idname, $subject);
    }

    public function delete_vat_accouts($subject_id) {
        $this->vatdb->where('subject_id', $subject_id);
        $this->vatdb->delete('subjects_accounts');
    }

    public function insert_vat_account($account) {
        $this->vatdb->insert('subjects_accounts', $account);
    }

    public function get_last_or_id() {
        $this->vatdb->order_by('id', 'desc');
        $this->vatdb->select('or_id');
        $this->vatdb->limit(1);

        $result = $this->vatdb->get('subjects')->first_row();

        return $result && is_numeric($result->or_id) ? $result->or_id : 0;
    }

    public function get_dics_for_vat_update($count, $skip) {
        $this->vatdb->select('dic');
        $this->vatdb->order_by('checkdate_vat');
        $this->vatdb->limit($count);
        $this->vatdb->offset($skip);
        $this->vatdb->where('dic IS NOT NULL', false, false);
        $this->vatdb->where('dic <> ', '');
        
        $query = $this->vatdb->get('subjects')->result_array();
        return $this->extract_field($query, 'dic');
    }

    public function get_ic_for_dic_update($nullDic, $count, $skip) {
        $this->vatdb->select('ic');
        $this->vatdb->order_by('checkdate_dic');
        $this->vatdb->limit($count);
        $this->vatdb->offset($skip);

        if ($nullDic) {
            $this->vatdb->where('dic IS NULL', false, false);
        } else {
            $this->vatdb->where('dic IS NOT NULL', false, false);
        }

        $query = $this->vatdb->get('subjects')->result_array();
        return $this->extract_field($query, 'ic');
    }

    public function update_checkdate_dic($ic) {
        $this->vatdb->where('ic', $ic);
        $this->vatdb->update('subjects', array("checkdate_dic" => date("Y-m-d H:i:s")));
    }

    public function update_checkdate_vat($dics) {
        $this->vatdb->where_in('dic', $dics);
        $this->vatdb->update('subjects', array("checkdate_vat" => date("Y-m-d H:i:s")));
    }

    public function get_detail_by_ic($ic) {
        $this->vatdb->where('subjects.ic', $ic);
        return $this->get_detail_internal();
    }

    public function get_detail($id) {
        $this->vatdb->where('subjects.or_id', $id);
        return $this->get_detail_internal();
    }

    public function get_accounts($subject_id) {
        $query = $this->vatdb->get_where('subjects_accounts', array("subject_id" => $subject_id))->result_array();
        return $this->extract_field($query, 'account');
    }

    public function get_for_result($result) {
        $result = array();
        $ics = $this->extract_field($result, 'ic');

        if (sizeof($ics) > 0) {
            $this->vatdb->where_in('ic', $ics);
            $this->vatdb->select('ic, unreliable');
    
            $result = $this->vatdb->get('subjects')->result_array();
        }

        return $result;
    }

    public function get_screening($ic) {
        $this->vatdb->where('ic', $ic);
        $this->vatdb->select('ic, unreliable');

        $result = $this->vatdb->get('subjects')->first_row('array');
    }

    private function get_detail_internal() {
        $this->vatdb->where('subjects.dic <> ', '');
        $this->vatdb->where('subjects.dic IS NOT NULL', false, false);
        $this->vatdb->join('subjects_bureaus', 'subjects.bureau_id = subjects_bureaus.id', 'left');

        $this->vatdb->select('
            subjects.id AS id,
            subjects.dic AS dic,
            subjects.unreliable AS unreliable,
            subjects_bureaus.name AS bureau
        ');

        $ret = $this->vatdb->get('subjects')->first_row('array');

        if ($ret != null) {
            $ret['isPayer'] = true;
            $ret['accounts'] = $this->get_accounts($ret['id']);
        } else {
            $ret['isPayer'] = false;
            $ret['isUnreliableVat'] = false;
        }
        
        return $ret;
    }

}
