<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_slider_model extends CI_Model{
	
	private $table , $lang_table, $primary_key;
	
	public function __construct(){
        return parent::__construct();
	}
	
	
	public function configure($options=array()){
		$this->table = !empty($options['table']) ? $options['table'] : '';
		$this->primary_key = !empty($options['primary_key']) ? $options['primary_key'] : '';
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$admin_default_lang = admin_default_lang();
		$this->db->select('*')
			->from($this->table. ' a');
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('a.status', DELETE_STATUS);	
		}else{
			$this->db->where('a.status <>', DELETE_STATUS);	
		}
		
		if(!empty($srch['term'])){
			$this->db->like('b.name', $srch['term']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('a.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'slide_image' => !empty($data['slide_image']) ? $data['slide_image'] : '', 
			'status' => !empty($data['status']) ? $data['status'] : '0',
			'display_order' => !empty($data['display_order']) ? $data['display_order'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}
	
	


	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'slide_image' => !empty($data['slide_image']) ? $data['slide_image'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '0',
			'display_order' => !empty($data['display_order']) ? $data['display_order'] : '0',
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
		
		$lang_name=$lang_description=array();

		foreach($result as $k => $v){
			$result['lang']['name'] = $lang_name;
			$result['lang']['description'] = $lang_description;
		}
		return $result;
	}
	
}


