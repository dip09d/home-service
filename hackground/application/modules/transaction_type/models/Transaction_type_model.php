<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_type_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'wallet_transaction_type';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('*')
			->from($this->table);
		
		
		if(!empty($srch['term'])){
			$this->db->like('description_tkey', $srch['term']);
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
			'title_tkey' => !empty($data['title_tkey']) ? $data['title_tkey'] : '',
			'description_tkey' => !empty($data['description_tkey']) ? $data['description_tkey'] : '',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}

	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'title_tkey' => !empty($data['title_tkey']) ? $data['title_tkey'] : '',
			'description_tkey' => !empty($data['description_tkey']) ? $data['description_tkey'] : '',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
		return  update($ins);
	}
	
	public function deleteRecord($id=''){
		return TRUE;
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
		return $result;
	}
	
}


