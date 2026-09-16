<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'member_document_application';
		$this->primary_key = 'document_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('m_c.*,m.member_name')
			->from($this->table.' m_c')
			->join('member m', 'm.member_id=m_c.member_id', 'LEFT');
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('document_status', DELETE_STATUS);	
		}else{
			$this->db->where('document_status <>', DELETE_STATUS);	
		}
		
		if(!empty($srch['term'])){
			$this->db->like('company_name', $srch['term']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by($this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'company_name' => !empty($data['company_name']) ? $data['company_name'] : '',
			'company_trade_license' => !empty($data['company_trade_license']) ? $data['company_trade_license'] : '',
			'company_size' => !empty($data['company_size']) ? $data['company_size'] : '',
			'company_description' => !empty($data['company_description']) ? $data['company_description'] : '',
			'company_contact_name' => !empty($data['company_contact_name']) ? $data['company_contact_name'] : '',
			'company_phone' => !empty($data['company_phone']) ? $data['company_phone'] : '',
			'company_address' => !empty($data['company_address']) ? $data['company_address'] : '',
			'company_logo' => !empty($data['company_logo']) ? $data['company_logo'] : '',
			'company_website' => !empty($data['company_website']) ? $data['company_website'] : '',
			'company_status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}

	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'company_name' => !empty($data['company_name']) ? $data['company_name'] : '',
			'company_trade_license' => !empty($data['company_trade_license']) ? $data['company_trade_license'] : '',
			'company_size' => !empty($data['company_size']) ? $data['company_size'] : '',
			'company_description' => !empty($data['company_description']) ? $data['company_description'] : '',
			'company_contact_name' => !empty($data['company_contact_name']) ? $data['company_contact_name'] : '',
			'company_phone' => !empty($data['company_phone']) ? $data['company_phone'] : '',
			'company_address' => !empty($data['company_address']) ? $data['company_address'] : '',
			'company_logo' => !empty($data['company_logo']) ? $data['company_logo'] : '',
			'company_website' => !empty($data['company_website']) ? $data['company_website'] : '',
			'company_status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
		return  update($ins);
	}
	
	public function deleteRecord($id=''){
		if($id && is_array($id)){
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('company_status' => DELETE_STATUS));
		}else{
			$ins['data'] = array('company_status' => DELETE_STATUS);
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


