<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commission_slab_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'commission_slab';
		$this->primary_key = 'commission_slab_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('*')
			->from($this->table);
		
		
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('display_order', 'ASC')->order_by($this->primary_key, 'ASC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'min_value' => !empty($data['min_value']) ? $data['min_value'] : '',
			'max_value' => !empty($data['max_value']) ? $data['max_value'] : '',
			'commission_percent' => !empty($data['commission_percent']) ? $data['commission_percent'] : '0',
			'display_order' => !empty($data['display_order']) ? $data['display_order'] : '0',
			'status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}

	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'min_value' => !empty($data['min_value']) ? $data['min_value'] : '',
			'max_value' => !empty($data['max_value']) ? $data['max_value'] : '',
			'commission_percent' => !empty($data['commission_percent']) ? $data['commission_percent'] : '0',
			'display_order' => !empty($data['display_order']) ? $data['display_order'] : '0',
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


