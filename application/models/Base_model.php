<?php
class Base_model extends CI_Model {

    protected $dboverride;

    function __construct()
    {
        parent::__construct();
        
        $this->dboverride = $this->db;
    }

    public function clear_cache() {
        $this->dboverride->cache_delete_all();
    }
    
    protected function createorupdatesubjectentity($tablename, $idname, $data) {
        $result = null;
        
        $this->dboverride->where($idname, $data[$idname]);
        $row = $this->dboverride->get($tablename)->first_row();    	
        
        if ($row == null) {
            // create new record
            $this->dboverride->insert($tablename, $data);
            $result = $this->dboverride->insert_id();
        } else {
            // update record
            if (isset($row->law_form_id) && $row->law_form_id > $this->config->item('MIN_BLACKLISTED') && isset($data['law_form_id'])) {
                unset($data['law_form_id']);
            }
            
            $this->dboverride->where('id', $row->id);
            $this->dboverride->update($tablename, $data);
            
            $result = $row->id;
        }
        
        return $result;
    }
    
    protected function update_or_insert_in_table($tablename, $idname, $idvalue, $data, $selectedidname = null) {
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
    
    protected function get_table_row_by_id($tablename, $idname, $idvalue, $getarray = false) {
        $ret = null;
        
        $this->dboverride->where($idname, $idvalue);
        $query = $this->dboverride->get($tablename);
        
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
    
    protected function insert_into_table($tablename, $values) {
        $this->dboverride->insert($tablename, $values);
        $ret = $this->dboverride->insert_id();
        
        return $ret;
    }
    
    protected function update_row_in_table($tablename, $idname, $id, $newdata) {
        $this->dboverride->where($idname, $id);
        $this->dboverride->update($tablename, $newdata);
    }

    protected function extract_field($query, $field) {
        $result = array();
        foreach ($query as $record) {
            array_push($result, $record[$field]);
        }

        return $result;
    }

    protected function search_limit($type) {
        $limit = $type == 'like_exact' || $type == 'fulltext_exact' || $type == 'fulltext_free'
            ? 10
            : 3;

        $this->dboverride->limit($limit);
    }

    protected function search_name($search, $type, $tablename) {
        if (!$search->name) {
            // when search name is not specified it has no sense to search by name
            return;
        }

        // name search, this one is tried in various ways to find a match
        $similarSearchTypes = array('similar_like', 'similar_fulltext');
        $name_search = normalize_for_search($search->name, in_array($type, $similarSearchTypes));

        if ($search->name_exact) {
            // if we need exact name, just try this one
            $this->dboverride->where($tablename . '.name_search', $name_search);
        } else if (ctype_digit($name_search) && strlen($name_search) > 4) {
            $this->dboverride->where($tablename . '.ic', $name_search);
        } else {
            // name should not be exact, try various ways
            if ($type === 'like_exact') {
                $this->dboverride->like($tablename . '.name_search', $name_search, 'after');
            } else if ($type === 'fulltext_exact') {
                $name_fulltext = prepare_for_fulltext($name_search, true);
                
                if ($name_fulltext == '') {
                    // empty fulltext has no sense to search
                    return;
                }

                $this->dboverride->where('MATCH ('. $tablename . '.name_search) AGAINST ('. $name_fulltext .' IN BOOLEAN MODE)', NULL, FALSE);
            } else if ($type === 'fulltext_free') {
                $name_fulltext = prepare_for_fulltext($name_search, false);

                if ($name_fulltext == '') {
                    // empty fulltext has no sense to search
                    return;
                }

                $this->dboverride->where('MATCH ('. $tablename . '.name_search) AGAINST ('. $name_fulltext .' IN BOOLEAN MODE)', NULL, FALSE);
            } else if ($type === 'similar_like_a1') {
                $this->dboverride->where('name_length > ', 3);
                $this->dboverride->like($tablename . '.name_soundex', partial_phonetical($name_search, 'soundex'), 'after');
            } else if ($type === 'similar_like_a2') {
                $this->dboverride->where('name_length > ', 3);
                $this->dboverride->like($tablename . '.name_metaphone', partial_phonetical($name_search, 'metaphone'), 'after');
            } else if ($type === 'similar_fulltext_a1') {
                $name_fulltext_soundex = prepare_for_fulltext(partial_phonetical($name_search, 'soundex'), false);

                if ($name_fulltext_soundex != '') {
                    $this->dboverride->where('name_length > ', 3);
                    $this->dboverride->where('MATCH ('. $tablename . '.name_soundex) AGAINST ('. $name_fulltext_soundex .' IN BOOLEAN MODE)', NULL, FALSE);
                }
            } else if ($type === 'similar_fulltext_a2') {
                $name_fulltext_metaphone = prepare_for_fulltext(partial_phonetical($name_search, 'metaphone'), false);

                if ($name_fulltext_metaphone != '') {
                    $this->dboverride->where('name_length > ', 3);
                    $this->dboverride->where('MATCH ('. $tablename . '.name_metaphone) AGAINST ('. $name_fulltext_metaphone .' IN BOOLEAN MODE)', NULL, FALSE);
                }
            }
        }
    }

    protected function create_search_termination_conditions($search, $type) {
        $termination_conditions = array();

        // when we search for exact name, skip other variations of name search
        array_push(
            $termination_conditions, 
            $search->name_exact && $type !== 'like_exact');

        // when we search for name, but IC is given, skip other variations of name search
        array_push(
            $termination_conditions, 
            ctype_digit($search->name) && strlen($search->name) > 4 && $type !== 'like_exact');

        return $termination_conditions;
    }

    protected function search_address($search, $tablename) {
        // always perform fulltext search on address, regardless of search type
        if ($search->address) {
            $address_fulltext = prepare_for_fulltext($search->address, true);
            $this->dboverride->where('MATCH ('. $tablename .'.address) AGAINST ('. $address_fulltext .' IN BOOLEAN MODE)', NULL, FALSE);
        }
    }

    protected function search_omit_ignored($ignoredIds, $ignoredIcs, $tableIdName, $tableIcName) {
		if (is_array($ignoredIds) && sizeof($ignoredIds) > 0) {
			$this->dboverride->where_not_in($tableIdName . '.id', $ignoredIds);
		}

		if (is_array($ignoredIcs) && sizeof($ignoredIcs) > 0) {
			$this->dboverride->where_not_in($tableIcName . '.ic', $ignoredIcs);
		}
    }

	/**
	* Gets the id as key and name as value array for the dropdown list
	* @param undefined $tablename
	* @param undefined $idname
	* @param undefined $idvalue
	* @param undefined $namename
	* 
	*/
	protected function get_items_for_select($tablename, $idname, $namename, $orderby = null) {
		$this->dboverride->select($idname .', '. $namename);
		
		if ($orderby != null) {
			$this->dboverride->order_by($orderby);
		}
		
		$query = $this->dboverride->get($tablename)->result_array();
		
		$ret = array();
		foreach ($query as $i) {
			$ret[$i[$idname]] = $i[$namename];
		}
		
		return $ret;
    }
    
	/**
	* Gets entire table contents
	* @param undefined $tablename name of the table
	*/
	protected function get_table_contents($tablename) {
		$ret = $this->dboverride->get($tablename)->result_array();		
		return $ret;
    }
    
	/**
	* Gets table rows by the id
	* @param undefined $tablename
	* @param undefined $idname
	* @param undefined $idvalue
	* 
	*/
	protected function get_table_rows_by_id($tablename, $idname, $idvalue) {
		$ret = array();
		
		$this->dboverride->where($idname, $idvalue);
		$query = $this->dboverride->get($tablename);
		
		if ($query->num_rows() > 0)  {
			$ret = $query->result_array();
		}
		
		return $ret;		
    }
    
	/**
	* Deletes values from the selected table
	* @param undefined $tablename name of the table
	* @param undefined $id array of ids
	*/	
	protected function delete_batch_by_id($tablename, $ids, $idname = 'id') {
		$this->dboverride->where_in($idname, $ids);
		$this->dboverride->delete($tablename);
    }
    
	/**
	* Inserts values in a batch into the selected table
	* @param undefined $tablename name of the table
	* @param undefined $values array of array
	*/	
	protected function insert_batch($tablename, $values) {
		$this->db->insert_batch($tablename, $values);
	}
}