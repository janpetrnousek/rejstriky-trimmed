<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relations_lib {

	// Codeigniter instance
	private $CI;
	
	public function __construct() {
		$this->CI =& get_instance();

		$this->CI->load->model('relations_model');
	}

    public function create_graph_data($root, $ignoreChildren, $referencedate = null, $showrelationcolors = null)
    {
        // get nodes
        $part1 = array();
        $part2 = array();

        if (!$ignoreChildren) {
            $part1 = $this->CI->relations_model->get_incoming_relations($root['id'], $referencedate, $showrelationcolors);
            $part2 = $this->CI->relations_model->get_outgoing_relations($root['id'], $referencedate, $showrelationcolors);
        }

        // merge outgoing and incoming (ignore from outgoing that are the same as root and are already in incoming)
        $nodes = $part1;
        foreach ($part2 as $item) {
            // append if it is other than root (if so do further inspection)
            $appendItem = $item['id'] != $root['id'];

            if (!$appendItem) {
                $part1hasroot = false;
                foreach ($part1 as $part1item) {
                    if ($part1item['id'] == $root['id']) {
                        $part1hasroot = true;
                        break;
                    }
                }

                // append only if it is not contained in incoming
                $appendItem = !$part1hasroot;
            }

            if ($appendItem) {
                array_push($nodes, $item);
            }
        }

        function compare_nodes($a, $b)
        {
            if ($a['relation_type_order'] == $b['relation_type_order']) {
                return 0;
            }

            return ($a['relation_type_order'] < $b['relation_type_order']) ? -1 : 1;
        }

        usort($nodes, "compare_nodes");

        // format names and determine type
        for ($i = 0; $i < sizeof($nodes); $i++) {
            $nodes[$i]['type'] = $this->is_company($nodes[$i]['ic'])
				? 'company'
				: 'person';
            $nodes[$i]['name'] = $this->format_name($nodes[$i]['name'], $nodes[$i]['type']);
        }

        // prepare graph data
        $graph_data = new stdclass();
        $graph_data->data = new stdclass();
        $graph_data->data->type = $this->is_company($root['ic'])
			? 'company'
			: 'person';

        $graph_data->id = $root['id'];

        $root['name'] = $this->format_name($root['name'], $graph_data->data->type);
        $root['type'] = $graph_data->data->type;
        $graph_data->name = $root['name'];

        $detail_data['subject'] = $root;
        $detail_data['nodes'] = $nodes;
        $graph_data->data->details = $this->CI->load->view('relations/detail', $detail_data, true);

        $graph_data->data->isExpandable = false;

        $graph_data->children = array();

        foreach ($nodes as $node) {
            $node['is_relation'] = true;

            $newItem = new stdClass();
            $newItem->id = $node['id'];
            $newItem->name = $node['name'];
            $newItem->data = new stdClass();
            $newItem->data->type = $node['type'];

            $detail_data['subject'] = $node;
            $detail_data['nodes'] = $nodes;
            $detail_data['root'] = $root;
            $newItem->data->details = $this->CI->load->view('relations/detail', $detail_data, true);

            $visibleRelationsCount = 0;
            foreach ($nodes as $searchNode) {
                if ($searchNode['id'] == $node['id']) {
                    $visibleRelationsCount++;
                }
            }

            $newItem->data->isExpandable = isset($node['active_relations']) && $node['active_relations'] > $visibleRelationsCount;
            $newItem->data->relationColor = $node['relation_type_color'];
            $newItem->data->relationName = $node['relation_type_name'];

            array_push($graph_data->children, $newItem);
        }

        return $graph_data;
    }
	
	public function is_company($ic) {
		return $ic != '' && $ic != NULL && $ic != '0';
	}
	
	public function format_name($name, $type) {
		if ($type == 'person') {
            $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8"); 
            
            if (!isUserLoggedIn()) {
                $name = $this->CI->config->item('RELATIONS_HIDDENNODE');
            }
		}
			
		$name = str_replace(' ', '&nbsp;', $name); 
		
		// titles formatting
		$titles = array('Ing.', 'Mgr.', 'JUDr.', 'Bc.', 'BcA.', 'MgrA.', 'Ing. arch.', 'MUDr.', 'MDDr.', 'MVDr.', 'PhDr.', 'PharmDr.', 'RNDr.', 'PaedDr.', 'ThLic.', 'ThDr.');
		return str_ireplace($titles, $titles, $name);
	}
	
}
