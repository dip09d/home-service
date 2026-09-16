<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_escrow_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'project_payment_escrow';
		$this->primary_key = 'payment_escrow_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		
		$this->db->select('p_p_e.*,p_c.contractor_id,p.project_title')
			->from('project_payment_escrow p_p_e')
			->join('project_contract p_c', 'p_c.contract_id=p_p_e.contract_id', 'LEFT')
			->join('project p', 'p.project_id=p_p_e.project_id', 'LEFT');
		
		if(!empty($srch['project_id'])){
			$this->db->where('p_p_e.project_id', $srch['project_id']);
		}
		
		if(!empty($srch['contract_id'])){
			$this->db->where('p_p_e.contract_id', $srch['contract_id']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('p_p_e.payment_escrow_id', 'DESC')->get()->result_array();
			foreach($result as $k => $v){
				$bidder_info = get_row(array(
					'select' => 'member_name,member_id',
					'from' => 'member',
					'where' => array('member_id' => $v['contractor_id'])
				));
				$bidder_info['logo'] = getMemberLogo($v['contractor_id']);
				
				$result[$k]['bidder_info'] = $bidder_info;
			}
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
		
	}
	
	public function getTotal($srch=array(), $type=''){
		if($type == 'credit'){
			$this->db->select_sum('credit');
		}else if($type == 'debit'){
			$this->db->select_sum('debit');
		}
		$this->db->from('project_payment_escrow p_p_e');
		
		if(!empty($srch['project_id'])){
			$this->db->where('p_p_e.project_id', $srch['project_id']);
		}
		
		if(!empty($srch['contract_id'])){
			$this->db->where('p_p_e.contract_id', $srch['contract_id']);
		}
		$result = $this->db->get()->row_array();
		if($result){
			return !empty($result[$type]) ? $result[$type] : 0;
		}
		
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


