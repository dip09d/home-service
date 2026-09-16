<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Experience_level_model extends CI_Model{
	
	private $table , $lang_table, $primary_key;
	
	public function __construct(){
        return parent::__construct();
	}
	
	
	public function configure($options=array()){
		$this->table = !empty($options['table']) ? $options['table'] : '';
		$this->lang_table =  !empty($options['lang_table']) ? $options['lang_table'] : '';
		$this->primary_key = !empty($options['primary_key']) ? $options['primary_key'] : '';
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$admin_default_lang = admin_default_lang();
		$this->db->select('*')
			->from($this->table. ' a')
			->join($this->lang_table. ' b', 'a.'.$this->primary_key.'='.'b.'.$this->primary_key);
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('a.experience_level_status', DELETE_STATUS);	
		}else{
			$this->db->where('a.experience_level_status <>', DELETE_STATUS);	
		}
		
		if(!empty($srch['term'])){
			$this->db->like('b.experience_level_name', $srch['term']);
		}
		
		$this->db->where('b.experience_level_lang', $admin_default_lang);	
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('a.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'experience_level_key' => !empty($data['experience_level_key']) ? $data['experience_level_key'] : '0',
			'experience_level_status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		
		$lang_fields = $data['lang'];
		$this->insert_lang_data($lang_fields, $insert_id);
		
		
		return $insert_id;
	}
	
	public function insert_lang_data($lang_fields=array(), $insert_id=''){
		$all_lang = get_lang();
		
		$this->db->where($this->primary_key, $insert_id)->delete($this->lang_table);
		foreach($all_lang as $k => $v){
			
			
			$structure = array(
				$this->primary_key => $insert_id,
				'experience_level_lang' => $v,
			);
			
			foreach($lang_fields as $field_name => $lang_val){
				$structure[$field_name] = $lang_fields[$field_name][$v];
			}
			
			$lang_record['data'] = $structure;
			$lang_record['table'] = $this->lang_table;
			
			insert($lang_record);
		}
	}


	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'experience_level_key' => !empty($data['experience_level_key']) ? $data['experience_level_key'] : '0',
			'experience_level_status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
		
		
		$lang_fields = $data['lang'];
		$this->insert_lang_data($lang_fields, $id);
		
		
		return  update($ins);
	}
	
	public function deleteRecord($id=''){
		if($id && is_array($id)){
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('experience_level_status' => DELETE_STATUS));
		}else{
			$ins['data'] = array('experience_level_status' => DELETE_STATUS);
			$ins['table'] = $this->table;
			$ins['where'] = array($this->primary_key => $id);
			return  update($ins);
		}
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
		$lang_result = $this->db->where($this->primary_key, $id)->get($this->lang_table)->result_array();
		
		$lang_name=array();
		
		foreach($lang_result as $k => $v){
			$lang_name[$v['experience_level_lang']] = $v['experience_level_name'];
		}
		$result['lang'] = array();
		foreach($result as $k => $v){
			$result['lang']['experience_level_name'] = $lang_name;
		}
		return $result;
	}
	
	public function allData(){
		$admin_default_lang = admin_default_lang();
		$this->table = 'experience_level';
		$this->lang_table = 'experience_level_name'; 
		$this->primary_key = 'experience_level_id';
		
		$this->db->select('*')
			->from($this->table. ' a')
			->join($this->lang_table. ' b', 'a.'.$this->primary_key.'='.'b.'.$this->primary_key);
		
		$this->db->where('a.experience_level_status', ACTIVE_STATUS);	
		
		$this->db->where('b.experience_level_lang', $admin_default_lang);	
		$result = $this->db->order_by('a.'.$this->primary_key, 'DESC')->get()->result_array();
		
		return $result;
	}
	
	
}


