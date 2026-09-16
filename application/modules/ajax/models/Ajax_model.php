<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_model extends CI_Model {

    public function __construct() {
        return parent::__construct();
    }
	
    
	public function getPlan(){
		$pln_list =  get_results(array('select' => '*', 'from' => 'plan', 'offset' => 'all', 'where' => array('status' => 'Y')));
		if(count($pln_list) > 0){
			foreach($pln_list as $k => $v){
				$pln_list[$k]['features'] = $this->_getPlanFeature($v['id']);
			}
			return $pln_list;
		}else{
			return array();
		}
		
	}
	
	public function _getPlanFeature($pln_id=''){
		$this->db->select('f.feature,f.id')->from('features f');
		$this->db->join('feature_plan fp', 'fp.feature_id = f.id', 'INNER');
		$this->db->where('fp.plan_id' , $pln_id);
		$result = $this->db->get()->result_array();
		return $result;
	}
	
}
