<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model{
	
	private $table , $lang_table, $primary_key;
	
	public function __construct(){
        return parent::__construct();
	}
	
	
	public function configure($options=array()){
		$this->table = !empty($options['table']) ? $options['table'] : '';
		$this->lang_table =  !empty($options['lang_table']) ? $options['lang_table'] : '';
		$this->category_table =  !empty($options['category_table']) ? $options['category_table'] : '';
		$this->images_table =  !empty($options['images_table']) ? $options['images_table'] : '';
		$this->tags_table =  !empty($options['tags_table']) ? $options['tags_table'] : '';
		$this->primary_key = !empty($options['primary_key']) ? $options['primary_key'] : '';
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$admin_default_lang = admin_default_lang();
		$this->db->select('*')
			->from($this->table. ' a')
			->join($this->lang_table. ' b', 'a.'.$this->primary_key.'='.'b.'.$this->primary_key);
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('a.blog_status', DELETE_STATUS);	
		}else{
			$this->db->where('a.blog_status <>', DELETE_STATUS);	
		}
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('b.name', $srch['term']);
			$this->db->or_like('a.blog_key', $srch['term']);
			$this->db->group_end();
		}
		
		$this->db->where('b.blog_lang', $admin_default_lang);	
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('a.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'blog_slug' => !empty($data['blog_slug']) ? $data['blog_slug'] : '',
			'blog_thumb' => !empty($data['blog_thumb']) ? $data['blog_thumb'] : '',
			'blog_for' => !empty($data['blog_for']) ? $data['blog_for'] : 'A',
			'blog_status' => !empty($data['status']) ? $data['status'] : '0',
			'is_featured' => !empty($data['is_featured']) ? $data['is_featured'] : '0',
			'blog_reg_date' => date('Y-m-d H:i:s'),
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		
		$lang_fields = $data['lang'];
		$this->insert_lang_data($lang_fields, $insert_id);

		$category_fields=$data['category_id'];
		$this->insert_category_data($category_fields, $insert_id);

		$images_fields=$data['blog_background'];
		$this->insert_images_data($images_fields, $insert_id);
		
		$tags_fields = $data['tags'];
		$this->insert_tags_data($tags_fields, $insert_id);
		
		
		return $insert_id;
	}
	public function insert_category_data($category_fields=array(), $insert_id=''){
		$this->db->where($this->primary_key, $insert_id)->delete($this->category_table);
		if($category_fields){
			foreach($category_fields as $category){
				$structure = array(
					$this->primary_key => $insert_id,
					'category_id' => $category,
				);
				$lang_record['data'] = $structure;
				$lang_record['table'] = $this->category_table;
				insert($lang_record);
			}
		}
	}
	public function insert_images_data($images_fields=array(), $insert_id=''){
		$this->db->where($this->primary_key, $insert_id)->delete($this->images_table);
		if($images_fields){
			$structure = array(
				$this->primary_key => $insert_id,
				'blog_image' => $images_fields,
			);
			$lang_record['data'] = $structure;
			$lang_record['table'] = $this->images_table;
			insert($lang_record);
		}
	}
	public function insert_lang_data($lang_fields=array(), $insert_id=''){
		$all_lang = get_lang();
		
		$this->db->where($this->primary_key, $insert_id)->delete($this->lang_table);
		foreach($all_lang as $k => $v){
			
			
			$structure = array(
				$this->primary_key => $insert_id,
				'blog_lang' => $v,
			);
			
			foreach($lang_fields as $field_name => $lang_val){
				$structure[$field_name] = $lang_fields[$field_name][$v];
			}
			
			$lang_record['data'] = $structure;
			$lang_record['table'] = $this->lang_table;
			
			insert($lang_record);
		}
	}
	
	public function insert_tags_data($tags_fields=array(), $insert_id=''){
		$all_lang = get_lang();
		$this->db->where($this->primary_key, $insert_id)->delete($this->tags_table);
		foreach($all_lang as $k => $v){
			$structure = array(
				$this->primary_key => $insert_id,
				'lang' => $v,
			);
			
			foreach($tags_fields as $field_name => $tag_val){
				$tags=explode(',',$tags_fields[$field_name][$v]);
				if($tags){
					foreach($tags as $t=>$tag){
						$structure[$field_name] = $tag;
						$lang_record['data'] = $structure;
						$lang_record['table'] = $this->tags_table;
						insert($lang_record);
					}
				}
			}
		}
	}


	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'blog_slug' => !empty($data['blog_slug']) ? $data['blog_slug'] : '',
			'blog_thumb' => !empty($data['blog_thumb']) ? $data['blog_thumb'] : '',
			'blog_for' => !empty($data['blog_for']) ? $data['blog_for'] : 'A',
			'blog_status' => !empty($data['status']) ? $data['status'] : '0',
			'is_featured' => !empty($data['is_featured']) ? $data['is_featured'] : '0',
			'blog_reg_date' => date('Y-m-d H:i:s'),
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
		
		
		$lang_fields = $data['lang'];
		$this->insert_lang_data($lang_fields, $id);

		$category_fields=$data['category_id'];
		$this->insert_category_data($category_fields, $id);

		$images_fields=$data['blog_background'];
		$this->insert_images_data($images_fields, $id);
		
		$tags_fields = $data['tags'];
		$this->insert_tags_data($tags_fields, $id);
		
		return  update($ins);
	}
	
	public function deleteRecord($id=''){
		if($id && is_array($id)){
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('blog_status' => DELETE_STATUS));
		}else{
			$ins['data'] = array('blog_status' => DELETE_STATUS);
			$ins['table'] = $this->table;
			$ins['where'] = array($this->primary_key => $id);
			return  update($ins);
		}
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
		if($result){
			$result['category']=$this->db->where($this->primary_key, $id)->get($this->category_table)->row_array();
			$result['images']=$this->db->where($this->primary_key, $id)->get($this->images_table)->row_array();
		}
		$lang_result = $this->db->where($this->primary_key, $id)->get($this->lang_table)->result_array();
		$tags_result = $this->db->where($this->primary_key, $id)->get($this->tags_table)->result_array();
		
		$lang_name=$lang_blog_description=$lang_blog_short_description=$lang_meta_title=$lang_meta_keys=$lang_meta_description=$tag_name=array();
		
		foreach($lang_result as $k => $v){
			$lang_name[$v['blog_lang']] = $v['blog_title'];
			$lang_blog_description[$v['blog_lang']] = $v['blog_description'];
			$lang_blog_short_description[$v['blog_lang']] = $v['blog_short_description'];
			$lang_meta_title[$v['blog_lang']] = $v['meta_title'];
			$lang_meta_keys[$v['blog_lang']] = $v['meta_keys'];
			$lang_meta_description[$v['blog_lang']] = $v['meta_description'];
		}
		if($tags_result){
			foreach($tags_result as $k => $v){
				$tag_name[$v['lang']][] = $v['name'];
			}
		}
		

		$result['lang'] = array();
		$result['tags'] = array();
		foreach($result as $k => $v){
			$result['lang']['blog_title'] = $lang_name;
			$result['lang']['blog_short_description'] = $lang_blog_short_description;
			$result['lang']['blog_description'] = $lang_blog_description;
			$result['lang']['meta_title'] = $lang_meta_title;
			$result['lang']['meta_keys'] = $lang_meta_keys;
			$result['lang']['meta_description'] = $lang_meta_description;
			$result['tags']['name'] = $tag_name;

		}
		return $result;
	}
	
	public function get_all_blog(){
		$admin_default_lang = admin_default_lang();
		$this->db->select('*')
			->from('blog a')
			->join('blog_names b', 'a.blog_id=b.blog_id');
			
		
		$this->db->where('a.blog_status', ACTIVE_STATUS);	
		$this->db->where('b.blog_lang', $admin_default_lang);	
		$result = $this->db->get()->result_array();
		return $result;
	}
	
	public function get_blog_title($blog_id=''){
		$admin_default_lang = admin_default_lang();
		$this->db->select('b.blog_title')
			->from('blog a')
			->join('blog_names b', 'a.blog_id=b.blog_id');
			
		$this->db->where('b.blog_lang', $admin_default_lang);	
		$this->db->where('a.blog_id', $blog_id);	
		$result = $this->db->get()->row_array();
		return !empty($result['blog_title']) ? $result['blog_title'] : '';
	}
	
	public function get_blog_title_by_key($blog_key=''){
		$admin_default_lang = admin_default_lang();
		$this->db->select('b.blog_title')
			->from('blog a')
			->join('blog_names b', 'a.blog_id=b.blog_id');
			
		$this->db->where('b.blog_lang', $admin_default_lang);	
		$this->db->where('a.blog_key', $blog_key);	
		$result = $this->db->get()->row_array();
		return !empty($result['blog_title']) ? $result['blog_title'] : '';
	}
	
	public function hasNoChild($cat_id=''){
		$count = (bool) $this->db->where('blog_id', $cat_id)->count_all_results('blog_subchild');
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


