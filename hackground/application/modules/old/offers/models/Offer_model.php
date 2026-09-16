<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offer_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'project_bids';
		$this->primary_key = 'bid_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		
		$this->db->select('p_c.*')
		->from('project_contract p_c');
		
		if(!empty($srch['project_id'])){
			$this->db->where('p_c.project_id', $srch['project_id']);
		}
		if(!empty($srch['contract_id'])){
			$this->db->where('p_c.contract_id', $srch['contract_id']);
		}
		if(isset($srch['status']) && $srch['status'] != ''){
			$this->db->where('p_c.contract_status', $srch['status']);
		}
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('p_c.contract_id', 'DESC')->get()->result_array();
			foreach($result as $k => $v){
				$bidder_info = get_row(array(
					'select' => 'member_name,member_id',
					'from' => 'member',
					'where' => array('member_id' => $v['contractor_id'])
				));
				$bidder_info['logo'] = getMemberLogo($v['contractor_id']);
				
				$employer_info = get_row(array(
					'select' => 'member_name,member_id',
					'from' => 'member',
					'where' => array('member_id' => $v['offer_by'])
				));
				$employer_info['logo'] = getMemberLogo($v['offer_by']);
				
				$result[$k]['employer_info'] = $employer_info;
				$result[$k]['bidder_info'] = $bidder_info;
				
			}
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
		
	}
	
	public function getMilestones($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		
		$this->db->select('p_c_m.*')
			->from('project_contract p_c')
			->join('project_contract_milestone p_c_m', 'p_c_m.contract_id=p_c.contract_id');
		
		
		if(!empty($srch['project_id'])){
			$this->db->where('p_c.project_id', $srch['project_id']);
		}
		if(!empty($srch['contract_id'])){
			$this->db->where('p_c_m.contract_id', $srch['contract_id']);
		}
		$this->db->where('p_c.contract_status', 1);
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('p_c.contract_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
		
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'name' => !empty($data['name']) ? $data['name'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}

	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'name' => !empty($data['name']) ? $data['name'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
		return  update($ins);
	}
	
	public function deleteRecord($id=''){
		if($id && is_array($id)){
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('status' => DELETE_STATUS));
		}else{
			$ins['data'] = array('status' => DELETE_STATUS);
			$ins['table'] = $this->table;
			$ins['where'] = array($this->primary_key => $id);
			return  update($ins);
		}
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
		return $result;
	}
	
}


