<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_application_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'project_bids';
		$this->primary_key = 'bid_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		
		$this->db->select('b.bid_id,b.bid_amount,b.bid_by_project,b.bid_duration,b.bid_details,b.bid_date,b.is_archive,b.is_shortlisted,b.is_interview,b.is_hired,b.member_id,b.organization_id,m.member_name,m.is_email_verified,m_b.member_heading,m_b.member_hourly_rate,m_a.member_country,m_l.logo,p.project_title,b.project_id')
		->from('project_bids b');
		$this->db->join('member m', 'b.member_id=m.member_id','left');
		$this->db->join('member_address m_a', 'b.member_id=m_a.member_id','left');
		$this->db->join('member_basic m_b', 'b.member_id=m_b.member_id','left');
		$this->db->join('member_logo m_l', 'b.member_id=m_l.member_id','left');
		$this->db->join('project p', 'p.project_id=b.project_id','left');
		
		if(!empty($srch['project_id'])){
			$this->db->where('b.project_id', $srch['project_id']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('b.bid_id', 'DESC')->get()->result_array();
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


