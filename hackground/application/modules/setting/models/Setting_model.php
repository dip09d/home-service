<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'settings';
		$this->primary_key = 'id';
        return parent::__construct();
	}
	
	public function getSettings($type='', $srch=array()){
		
		$general_keys = array('site_title', 'admin_email','language', 'admin_default_lang', 'default_lang');
		
		$this->db->select('*')
			->from($this->table);
		
		
		if($type == 'GENERAL'){
			$this->db->where_in('setting_key', $general_keys);	
		}else{
			$this->db->where_not_in('setting_key', $general_keys);	
		}
		
		if(!empty($srch['show']) && $srch['show'] == 'all'){
			
		}else{
			$this->db->where('editable',1);
		}
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('setting_key', $srch['term']);
			$this->db->or_like('title', $srch['term']);
			$this->db->group_end();
		}
		
		$this->db->order_by('display_order', 'ASC');
		$this->db->order_by('title', 'ASC');
		
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	public function getGroupSettings($key='', $srch=array()){
		
		$general_keys = array('site_title', 'admin_email','language', 'admin_default_lang', 'default_lang');
		
		$this->db->select('*')
			->from($this->table);
		
		$this->db->where('setting_group', $key);
		
		if(!empty($srch['show']) && $srch['show'] == 'all'){
			
		}else{
			$this->db->where('editable',1);
		}
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('setting_key', $srch['term']);
			$this->db->or_like('title', $srch['term']);
			$this->db->group_end();
		}
		
		$this->db->order_by('display_order', 'ASC');
		$this->db->order_by('title', 'ASC');
		
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('*')
			->from($this->table);
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('status', DELETE_STATUS);	
		}else{
			$this->db->where('status <>', DELETE_STATUS);	
		}
		
		if(!empty($srch['term'])){
			$this->db->like('name', $srch['term']);
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
			'title' => !empty($data['title']) ? $data['title'] : '',
			'setting_key' => !empty($data['setting_key']) ? $data['setting_key'] : '',
			'setting_value' => !empty($data['setting_value']) ? $data['setting_value'] : (($data['setting_value']!='') ? $data['setting_value']:''),
			'setting_group' => !empty($data['setting_group']) ? $data['setting_group'] : '',
			'deletable' => !empty($data['deletable']) ? $data['deletable'] : 0,
			'editable' => !empty($data['editable']) ? $data['editable'] : 0,
			'display_order' => !empty($data['display_order']) ? $data['display_order'] : 0,
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}

	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'title' => !empty($data['title']) ? $data['title'] : '',
			'setting_value' => !empty($data['setting_value']) ? $data['setting_value'] : (($data['setting_value']!='') ? $data['setting_value']:''),
			'display_order' => !empty($data['display_order']) ? $data['display_order'] : 0,
			'setting_group' => !empty($data['setting_group']) ? $data['setting_group'] : '',
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


