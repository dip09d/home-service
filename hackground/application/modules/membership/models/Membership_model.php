<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membership_model extends CI_Model{
	
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
			$this->db->where('a.membership_status', DELETE_STATUS);	
		}else{
			$this->db->where('a.membership_status <>', DELETE_STATUS);	
		}
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('b.name', $srch['term']);
			$this->db->or_where('b.description', $srch['term']);
			$this->db->group_end();
		}
		
		$this->db->where('b.lang', $admin_default_lang);	
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('a.display_order', 'ASC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function addRecord($data=array()){
		$structure = array(
			'display_order' => !empty($data['display_order']) ? $data['display_order'] : '0',
			'membership_status' => !empty($data['status']) ? $data['status'] : '0',
			'membership_bid' => !empty($data['membership_bid']) ? $data['membership_bid'] : '0',
			'membership_portfolio' => !empty($data['membership_portfolio']) ? $data['membership_portfolio'] : '0',
			'membership_skills' => !empty($data['membership_skills']) ? $data['membership_skills'] : '0',
			'membership_commission_percent' => !empty($data['membership_commission_percent']) ? $data['membership_commission_percent'] : '0',
			'price_per_month' => !empty($data['price_per_month']) ? $data['price_per_month'] : '0',
			'price_per_year' => !empty($data['price_per_year']) ? $data['price_per_year'] : '0',
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
				'lang' => $v,
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
			'display_order' => !empty($data['display_order']) ? $data['display_order'] : '0',
			'membership_status' => !empty($data['status']) ? $data['status'] : '0',
			'membership_bid' => !empty($data['membership_bid']) ? $data['membership_bid'] : '0',
			'membership_portfolio' => !empty($data['membership_portfolio']) ? $data['membership_portfolio'] : '0',
			'membership_skills' => !empty($data['membership_skills']) ? $data['membership_skills'] : '0',
			'membership_commission_percent' => !empty($data['membership_commission_percent']) ? $data['membership_commission_percent'] : '0',
			'price_per_month' => !empty($data['price_per_month']) ? $data['price_per_month'] : '0',
			'price_per_year' => !empty($data['price_per_year']) ? $data['price_per_year'] : '0',
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
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('membership_status' => DELETE_STATUS));
		}else{
			$ins['data'] = array('membership_status' => DELETE_STATUS);
			$ins['table'] = $this->table;
			$ins['where'] = array($this->primary_key => $id);
			return  update($ins);
		}
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
		$lang_result = $this->db->where($this->primary_key, $id)->get($this->lang_table)->result_array();
		
		$lang_name=$lang_company_name=$lang_description=array();
		
		foreach($lang_result as $k => $v){
			$lang_name[$v['lang']] = $v['name'];
			$lang_description[$v['lang']] = $v['description'];
		}
		$result['lang'] = array();
		foreach($result as $k => $v){
			$result['lang']['name'] = $lang_name;
			$result['lang']['description'] = $lang_description;
		}
		return $result;
	}
	
	public function get_all_membership(){
		$admin_default_lang = admin_default_lang();
		$this->db->select('*')
			->from('membership a')
			->join('membership_names b', 'a.membership_code=b.membership_code');
		
		$this->db->where('a.membership_status', ACTIVE_STATUS);	
		
		$this->db->where('b.lang', $admin_default_lang);	
		$result = $this->db->order_by('b.name', 'ASC')->get()->result_array();
		return $result;
	}
	public function getUserBadge($membership_id=''){
		$default_lang = admin_default_lang();
		$this->db->select('m_b.badge_id as ID, b.name')
				->from('membership_badge m_b')
				->join('badges_names b', 'b.badge_id=m_b.badge_id')
				->where('b.lang', $default_lang);
		$this->db->where('m_b.membership_id', $membership_id);
		
		$result = $this->db->get()->result_array();
		return $result;
	}
	
}


