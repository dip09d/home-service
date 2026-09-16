<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_user_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'admin';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$is_super_admin = is_super_admin();
		$this->db->select('a.*,b.name as role')
			->from($this->table. ' a')
			->join('admin_role b', 'b.role_id=a.role_id', 'LEFT');
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('a.status', DELETE_STATUS);	
		}else{
			$this->db->where('a.status <>', DELETE_STATUS);	
		}
		
		if(!$is_super_admin){
			$this->db->where('a.super_admin', '0');	
		}
		
		if(!empty($srch['term'])){
			$this->db->like('a.name', $srch['term']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('a.admin_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$password = !empty($data['password']) ? md5($data['password']) : '';
		$structure = array(
			'username' => !empty($data['username']) ? $data['username'] : '',
			'full_name' => !empty($data['full_name']) ? $data['full_name'] : '',
			'email' => !empty($data['email']) ? $data['email'] : '',
			'password' => $password,
			'role_id' => !empty($data['role_id']) ? $data['role_id'] : 1,
			'registered_on' => date('Y-m-d H:i:s'),
			'status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}

	public function updateRecord($data=array(), $id=''){
		if(!empty($data['change_password']) &&  $data['change_password'] > 0){
			$password = md5($data['password']);
		}else{
			$password = null;
		}
		
		$structure = array(
			'full_name' => !empty($data['full_name']) ? $data['full_name'] : '',
			'email' => !empty($data['email']) ? $data['email'] : '',
			/* 'role_id' => !empty($data['role_id']) ? $data['role_id'] : 1, */
			'status' => !empty($data['status']) ? $data['status'] : '0',
		);
		
		if($data['role_id']){
			$structure['role_id'] = $data['role_id'];
		}
		
		if($password){
			$structure['password'] = $password;
		}
		
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
	
	public function get_role(){
		$data = get_results(array('select' => '*', 'from' => 'admin_role', 'where' => array('status' => ACTIVE_STATUS)));
		return $data;
	}
}


