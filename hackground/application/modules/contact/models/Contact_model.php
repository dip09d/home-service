<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'contact';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('*')
			->from($this->table);
		
		if(!empty($srch['term'])){
			$this->db->like('email', $srch['term']);
		}
		
		if(!empty($srch['inquiry'])){
			$this->db->where('inquiry', $srch['inquiry']);
		}
		
		
		if(isset($srch['status'])){
			$this->db->like('replied', $srch['status']);
		}
		
		if(!empty($srch['reg_date'])){
			$date_range = explode(' - ', $srch['reg_date']);
			$this->db->where("DATE(date) BETWEEN DATE('{$date_range[0]}') AND DATE('{$date_range[1]}')");
		}
		
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by($this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function replyUser($data=array(), $id=''){
		$structure = array(
			'replied' => 1,
			'reply_message' => !empty($data['reply_message']) ? $data['reply_message'] : '',
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


