<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model{
	
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
			$this->db->where('a.category_status', DELETE_STATUS);	
		}else{
			$this->db->where('a.category_status <>', DELETE_STATUS);	
		}
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('b.category_name', $srch['term']);
			$this->db->or_like('a.category_key', $srch['term']);
			$this->db->group_end();
		}
		
		$this->db->where('b.category_lang', $admin_default_lang);	
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('a.category_order', 'ASC')->order_by('a.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'category_thumb' => !empty($data['category_thumb']) ? $data['category_thumb'] : '',
			'category_icon' => !empty($data['category_icon']) ? $data['category_icon'] : '',
			'is_featured' => !empty($data['is_featured']) ? $data['is_featured'] : '0',
			/* 'category_background' => !empty($data['category_background']) ? $data['category_background'] : '', */
			'category_key' => !empty($data['category_key']) ? $data['category_key'] : '',
			/* 'detail_template' => !empty($data['detail_template']) ? $data['detail_template'] : '',
			'list_template' => !empty($data['list_template']) ? $data['list_template'] : '', */
			'category_status' => !empty($data['status']) ? $data['status'] : '0',
			'category_order' => !empty($data['category_order']) ? $data['category_order'] : '0',
			/* 'show_price' => !empty($data['show_price']) ? $data['show_price'] : '0',
			'show_in_home' => !empty($data['show_in_home']) ? $data['show_in_home'] : '0',
			'is_module' => !empty($data['is_module']) ? $data['is_module'] : '',
			'marge_with' => !empty($data['marge_with']) ? $data['marge_with'] : '', */
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
				'category_lang' => $v,
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
			'category_thumb' => !empty($data['category_thumb']) ? $data['category_thumb'] : '',
			'category_icon' => !empty($data['category_icon']) ? $data['category_icon'] : '',
			'is_featured' => !empty($data['is_featured']) ? $data['is_featured'] : '0',
			/* 'category_background' => !empty($data['category_background']) ? $data['category_background'] : '', */
			'category_key' => !empty($data['category_key']) ? $data['category_key'] : '',
			/* 'detail_template' => !empty($data['detail_template']) ? $data['detail_template'] : '',
			'list_template' => !empty($data['list_template']) ? $data['list_template'] : '', */
			'category_status' => !empty($data['status']) ? $data['status'] : '0',
			'category_order' => !empty($data['category_order']) ? $data['category_order'] : '0',
			/* 'show_price' => !empty($data['show_price']) ? $data['show_price'] : '0',
			'show_in_home' => !empty($data['show_in_home']) ? $data['show_in_home'] : '0',
			'is_module' => !empty($data['is_module']) ? $data['is_module'] : '',
			'marge_with' => !empty($data['marge_with']) ? $data['marge_with'] : '', */
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
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('category_status' => DELETE_STATUS));
		}else{
			$ins['data'] = array('category_status' => DELETE_STATUS);
			$ins['table'] = $this->table;
			$ins['where'] = array($this->primary_key => $id);
			return  update($ins);
		}
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
		$lang_result = $this->db->where($this->primary_key, $id)->get($this->lang_table)->result_array();
		
		$lang_name=$lang_info=array();
		
		foreach($lang_result as $k => $v){
			$lang_name[$v['category_lang']] = $v['category_name'];
			$lang_info[$v['category_lang']] = $v['description'];
		}
		$result['lang'] = array();
		foreach($result as $k => $v){
			$result['lang']['category_name'] = $lang_name;
			$result['lang']['description'] = $lang_info;
		}
		return $result;
	}
	
	public function get_all_category(){
		$admin_default_lang = admin_default_lang();
		$this->db->select('*')
			->from('category a')
			->join('category_names b', 'a.category_id=b.category_id');
			
		
		$this->db->where('a.category_status', ACTIVE_STATUS);	
		$this->db->where('b.category_lang', $admin_default_lang);	
		$result = $this->db->get()->result_array();
		return $result;
	}
	
	public function get_category_name($category_id=''){
		$admin_default_lang = admin_default_lang();
		$this->db->select('b.category_name')
			->from('category a')
			->join('category_names b', 'a.category_id=b.category_id');
			
		$this->db->where('b.category_lang', $admin_default_lang);	
		$this->db->where('a.category_id', $category_id);	
		$result = $this->db->get()->row_array();
		return !empty($result['category_name']) ? $result['category_name'] : '';
	}
	
	public function get_category_name_by_key($category_key=''){
		$admin_default_lang = admin_default_lang();
		$this->db->select('b.category_name')
			->from('category a')
			->join('category_names b', 'a.category_id=b.category_id');
			
		$this->db->where('b.category_lang', $admin_default_lang);	
		$this->db->where('a.category_key', $category_key);	
		$result = $this->db->get()->row_array();
		return !empty($result['category_name']) ? $result['category_name'] : '';
	}
	
	public function hasNoChild($cat_id=''){
		$count = (bool) $this->db->where('category_id', $cat_id)->count_all_results('category_subchild');
		if($count > 0){
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function getTemplate($type=''){
		$template = array(
			'list' => array(
				array(
					'name' => 'List Project',
					'file' => 'list-project',
				),
				array(
					'name' => 'List Project With Top Search',
					'file' => 'list-project-with-top-search',
				),
			),
			'detail' => array(
				array(
					'name' => 'View Project',
					'file' => 'view-project',
				),
				array(
					'name' => 'View Project With Search',
					'file' => 'view-project-with-search',
				),
				array(
					'name' => 'View Project With Search & Modal',
					'file' => 'view-project-with-search-and-modal',
				),
			)
		);
		
		return !empty($template[$type]) ? $template[$type] : array();
	}

	public function getAllModule(){
		$template = array(
			array(
				'name' => 'Is job',
				'key' => 'is-job',
			),
			array(
				'name' => 'Job wanted',
				'key' => 'is-job-wanted',
			),
			array(
				'name' => 'Property for Sale',
				'key' => 'is-sale-property',
			),
			array(
				'name' => 'Property for Rent',
				'key' => 'is-rent-property',
			),
		);
		
		return $template;
	}
}


