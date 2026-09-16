<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'member';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('u.*,a.login_status,a.access_user_email')
			->from($this->table.' as u');
		/* $this->db->join('profile_connection as p_c',"(u.member_id=p_c.member_id and p_c.organization_id IS NULL)",'left'); */
		$this->db->join('access_panel as a','u.access_user_id=a.access_user_id','left');
		
		
		if(!empty($srch['member_id'])){
			$this->db->where('u.member_id',$srch['member_id']);
		}
		
		if(!empty($srch['u_type']) && $srch['u_type'] == 'freelancer'){
			$this->db->where('u.is_employer',0);
		}else if(!empty($srch['u_type']) && $srch['u_type'] == 'employer'){
			$this->db->where('u.is_employer',1);
		}
		
		
		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('u.member_name', $srch['term']);
			$this->db->or_like('u.member_email', $srch['term']);
			$this->db->group_end();
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('u.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		return $result;
	}
	
	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'member_name' => !empty($data['member_name']) ? $data['member_name'] : '',
			'member_email' => !empty($data['member_email']) ? $data['member_email'] : '',
			'is_email_verified' => !empty($data['is_email_verified']) ? $data['is_email_verified'] : '0',
			'is_phone_verified' => !empty($data['is_phone_verified']) ? $data['is_phone_verified'] : '0',
			'is_doc_verified' => !empty($data['is_doc_verified']) ? $data['is_doc_verified'] : '0',
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
	
	
	public function getAllDetail($member_id=''){
		$member = $this->db->where('member_id', $member_id)->get('member')->row_array();
		$member['organization_id']=0;
		$organization=$this->db->where('member_id', $member_id)->get('organization')->row();
		if($organization){
			$member['organization_address'] = $this->_getOrganizationAddress($organization->organization_id);
			$member['organization_id']=$organization->organization_id;
			$member['organization_basic'] = $this->_getOrganizationBasic($organization->organization_id);
		}else{
			$member['member_address'] = $this->_getMemberAddress($member_id);
		}
		$member['member_basic'] = $this->_getMemberBasic($member_id);
		$member['member_logo'] = $this->_getMemberLogo($member_id);
		$member['member_skills'] = $this->_getMemberSkills($member_id);
		$member['member_language'] = $this->_getMemberLanguage($member_id);
		$member['member_employment'] = $this->_getMemberEmployment($member_id);
		$member['member_education'] = $this->_getMemberEducation($member_id);
		$member['member_portfolio'] = $this->_getMemberPortfolio($member_id);
		
		
		return $member;
	}
	
	private function _getMemberEmployment($member_id=''){
		$result = $this->db->select('*')
				->from('member_employment')
				->where('member_id', $member_id)
				->where('employment_status', 1)
				->get()
				->result_array();
		if($result){
			foreach($result as $k => $v){
				$result[$k]['employment_country'] = array(
					'code' => $v['employment_country_code'] ,
					'name' => get_country_name($v['employment_country_code']) ,
				);
			
			}
		}
		
		return $result;
	}
	
	private function _getMemberPortfolio($member_id=''){
		$admin_default_lang = admin_default_lang();
		$result = $this->db->select('*')
				->from('member_portfolio')
				->where('member_id', $member_id)
				->where('portfolio_status', 1)
				->get()
				->result_array();
		if($result){
			foreach($result as $k => $v){
				$result[$k]['category']  = get_row(array(
					'select' => 'c.category_key as key,c_n.category_name as name ,c.category_id as ID',
					'from' => 'category c',
					'join' => array(
								array('category_names c_n', 'c_n.category_id=c.category_id', 'LEFT'),
							),
					'where' => array(
								'c_n.category_lang' => $admin_default_lang,
								'c.category_id' => $v['category_id'],
							)
				));
			
				$result[$k]['sub_category'] = get_row(array(
					'select' => 'c.category_subchild_key as key,c_n.category_subchild_name as name ,c.category_subchild_id as ID',
					'from' => 'category_subchild c',
					'join' => array(
								array('category_subchild_names c_n', 'c_n.category_subchild_id=c.category_subchild_id', 'LEFT'),
							),
					'where' => array(
								'c_n.category_subchild_lang' => $admin_default_lang,
								'c.category_subchild_id' => $v['category_subchild_id'],
							)
				));
			
			}
		}
		
		return $result;
	}
	
	private function _getMemberEducation($member_id=''){
		$result = $this->db->select('*')
				->from('member_education')
				->where('member_id', $member_id)
				->where('education_status', 1)
				->get()
				->result_array();
		return $result;
	}
	
	private function _getMemberLanguage($member_id=''){
		$this->db->select('m_l.member_language_id,l.language_name,l.language_id,m_l.language_preference_id,l_p.language_preference_name,l_p.language_preference_info')
			->from('member_language m_l')
			->join('language l', 'm_l.language_id=l.language_id', 'LEFT')
			->join('language_preference l_p', 'l_p.language_preference_id=m_l.language_preference_id', 'LEFT');
		
		$this->db->where('m_l.member_id', $member_id);
		$this->db->where('m_l.language_status', '1');
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	
	private function _getMemberSkills($member_id=''){
		$admin_default_lang = admin_default_lang();
		$this->db->select('s.skill_id,s.skill_key,s_n.skill_name')
			->from('member_skills p_s')
			->join('skills s', 's.skill_id=p_s.skill_id', 'LEFT')
			->join('skill_names s_n', 's_n.skill_id=s.skill_id', 'LEFT');
			
		$this->db->where('s_n.skill_lang', $admin_default_lang);
		$this->db->where('p_s.member_id', $member_id);
		
		$result = $this->db->get()->result_array();
		$return_result = array();
		if($result){
			$return_result['all_skills'] = $result;
			$return_result['skill_names'] = get_k_value_from_array($result, 'skill_name');
			
		}
		return $return_result;
	}
	
	private function _getMemberAddress($member_id=''){
		$member_address = $this->db->where('member_id', $member_id)->get('member_address')->row_array();
		if($member_address){
			$member_address['member_country'] = array(
				'code' => $member_address['member_country'] ,
				'name' => get_country_name($member_address['member_country']) ,
			);
			
			$member_address['member_current_location'] = array(
				'code' => $member_address['member_current_location'] ,
				'name' => get_country_name($member_address['member_current_location']) ,
			);
			
		}
		
		return $member_address;
	}
	private function _getOrganizationAddress($organization_id=''){
		$member_address = $this->db->where('organization_id', $organization_id)->get('organization_address')->row_array();
		if($member_address){
			$member_address['member_country'] = array(
				'code' => $member_address['organization_country'] ,
				'name' => get_country_name($member_address['organization_country']) ,
			);
			
			$member_address['member_current_location'] = array(
				'code' => $member_address['organization_country'] ,
				'name' => get_country_name($member_address['organization_country']) ,
			);
			
		}
		
		return $member_address;
	}
	private function _getOrganizationBasic($organization_id=''){
		$organization_basic = $this->db->where('organization_id', $organization_id)->get('organization')->row_array();
		return $organization_basic;
	}
	private function _getMemberBasic($member_id=''){
		$member_basic = $this->db->where('member_id', $member_id)->get('member_basic')->row_array();
		return $member_basic;
	}
	
	private function _getMemberIndustry($member_id=''){
		$this->db->select('*')
				->from('member_industry')
				->where('member_id', $member_id);
				
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	private function _getMemberLogo($member_id=''){
		return getMemberLogo($member_id);
	}
	
	private function _getMemberProfessional($member_id=''){
		return $this->getMemberInfo('professional', $member_id);
	}
	
	public function getOption($option=''){
		$default_lang = admin_default_lang();
		
		$option_map = array(
			'career_level' => array(
				'table' => 'option_career_level',
				'table_lang' => 'option_career_level_names',
				'primary_key' => 'career_level_id',
			),
			
			'current_position' => array(
				'table' => 'option_position',
				'table_lang' => 'option_position_names',
				'primary_key' => 'position_id',
			),
			
			'salary_expectation' => array(
				'table' => 'option_salary',
				'table_lang' => 'option_salary_names',
				'primary_key' => 'salary_id',
			),
			
			'commitment' => array(
				'table' => 'option_commitment',
				'table_lang' => 'option_commitment_names',
				'primary_key' => 'commitment_id',
			),
			
			'notice_period' => array(
				'table' => 'option_notice_period',
				'table_lang' => 'option_notice_period_names',
				'primary_key' => 'notice_period_id',
			),
			
			'visa_status' => array(
				'table' => 'option_visa_status',
				'table_lang' => 'option_visa_status_names',
				'primary_key' => 'visa_status_id',
			),
			
			'experience_area' => array(
				'table' => 'option_experience_area',
				'table_lang' => 'option_experience_area_names',
				'primary_key' => 'experience_area_id',
			),
			
			'academy' => array(
				'table' => 'option_academy',
				'table_lang' => 'option_academy_names',
				'primary_key' => 'academy_id',
			),
			
		);
		
		
		$selected_option = !empty($option_map[$option]) ? $option_map[$option] : null;
		
		if($selected_option === null){
			return array();
		}
		
		$this->db->select("a.{$selected_option['primary_key']} , b.name")
			->from("{$selected_option['table']} a")
			->join("{$selected_option['table_lang']} b", "a.{$selected_option['primary_key']}=b.{$selected_option['primary_key']}", 'INNER');
		
			
		$this->db->where('b.lang', $default_lang);
		$this->db->where('a.status', 1);
		
		$result = $this->db->order_by("a.{$selected_option['primary_key']}", "ASC")->get()->result_array();
		
		return $result;
	}
	
	public function saveMemberInfo($data=array(), $member_id=0){
		if(($member_id > 0) === false){
			return false;
		}
	
		$member_main = !empty($data['member']) ? $data['member'] : array(); 
		$member_professional = !empty($data['member_professional']) ? $data['member_professional'] : array(); 
		$member_address = !empty($data['member_address']) ? $data['member_address'] : array(); 
		$member_basic = !empty($data['member_basic']) ? $data['member_basic'] : array(); 
		$where = array(
			'member_id' => $member_id
		);
		if($member_main){
			$this->db->where($where)->update('member', $member_main);
		}
		if($member_professional){
			$table = 'member_professional';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $member_professional);
			}else{
				$member_professional['member_id'] = $member_id;
				$this->db->insert($table, $member_professional);
			}
			
		}
		if($member_address){
			$table = 'member_address';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $member_address);
			}else{
				$member_address['member_id'] = $member_id;
				$this->db->insert($table, $member_address);
			}
			
		}
		if($member_basic){
			$table = 'member_basic';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $member_basic);
			}else{
				$member_basic['member_id'] = $member_id;
				$this->db->insert($table, $member_basic);
			}
			
			
		}
		
	}
	public function saveOrganizationInfo($data=array(), $organization_id=0){
		if(($organization_id > 0) === false){
			return false;
		}
	
		$member_main = !empty($data['member']) ? $data['member'] : array(); 
		$organization_address = !empty($data['organization_address']) ? $data['organization_address'] : array(); 
		$organization_basic = !empty($data['organization_basic']) ? $data['organization_basic'] : array(); 
		$where = array(
			'organization_id' => $organization_id
		);
		if($organization_address){
			$table = 'organization_address';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $organization_address);
			}else{
				$organization_address['organization_id'] = $organization_id;
				$this->db->insert($table, $organization_address);
			}
			
		}
		if($member_main){
			$this->db->where($where)->update('member', $member_main);
		}
	
		
		if($organization_basic){
			$table = 'organization';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $organization_basic);
			}else{
				$organization_basic['organization_id'] = $organization_id;
				$this->db->insert($table, $organization_basic);
			}
			
			
		}
		
	}
	
	public function getMemberInfo($info_type='', $member_id=0){
		if(($member_id > 0) === false){
			return false;
		}
		$where = array(
			'member_id' => $member_id
		);
		$result = array();
		if($info_type == 'professional'){
			$table = 'member_professional';
			$result = $this->db->where($where )->get($table)->row_array();
			if($result){
				$result['member_career_level'] = array(
					'name' => $this->_getOptionName('career_level', $result['member_career_level']),
					'ID' => $result['member_career_level'],
				);
				$result['member_current_position'] = array(
					'name' => $this->_getOptionName('current_position', $result['member_current_position']),
					'ID' => $result['member_current_position'],
				);
				$result['member_salary_expectation'] = array(
					'name' => $this->_getOptionName('salary_expectation', $result['member_salary_expectation']),
					'ID' => $result['member_salary_expectation'],
				);
				$result['member_commitment'] = array(
					'name' => $this->_getOptionName('commitment', $result['member_commitment']),
					'ID' => $result['member_commitment'],
				);
				$result['member_notice_period'] = array(
					'name' => $this->_getOptionName('notice_period', $result['member_notice_period']),
					'ID' => $result['member_notice_period'],
				);
				$result['member_visa_status'] = array(
					'name' => $this->_getOptionName('visa_status', $result['member_visa_status']),
					'ID' => $result['member_visa_status'],
				);
			}
		}else if($info_type == 'address'){
			$table = 'member_address';
			$result = $this->db->where($where)->get($table)->row_array();
			if($result){
				$result['member_country'] = array(
					'code' => $result['member_country'],
					'name' => get_country_name($result['member_country'])
				);
				$result['member_current_location'] = array(
					'code' => $result['member_current_location'],
					'name' => get_country_name($result['member_current_location'])
				);
			}
		}
		
		
		
		return $result;
	}
	
	private function _getOptionName($table='', $ID=''){
		$option_map = array(
			'career_level' => array(
				'table' => 'option_career_level',
				'table_lang' => 'option_career_level_names',
				'primary_key' => 'career_level_id',
			),
			
			'current_position' => array(
				'table' => 'option_position',
				'table_lang' => 'option_position_names',
				'primary_key' => 'position_id',
			),
			
			'salary_expectation' => array(
				'table' => 'option_salary',
				'table_lang' => 'option_salary_names',
				'primary_key' => 'salary_id',
			),
			
			'commitment' => array(
				'table' => 'option_commitment',
				'table_lang' => 'option_commitment_names',
				'primary_key' => 'commitment_id',
			),
			
			'notice_period' => array(
				'table' => 'option_notice_period',
				'table_lang' => 'option_notice_period_names',
				'primary_key' => 'notice_period_id',
			),
			
			'visa_status' => array(
				'table' => 'option_visa_status',
				'table_lang' => 'option_visa_status_names',
				'primary_key' => 'visa_status_id',
			),
			
			'academy' => array(
				'table' => 'optionacademy',
				'table_lang' => 'option_academy_names',
				'primary_key' => 'academy_id',
			),
		);
		
		$selected_option = $option_map[$table];
		
		return $this->_getOptionFieldValue($selected_option, $ID);
		
	}
	
	private function _getOptionFieldValue($table_info=array(), $ID=''){
		$default_lang = admin_default_lang();
		$this->db->select('name')
				->from($table_info['table_lang'])
				->where($table_info['primary_key'], $ID)
				->where('lang', $default_lang);
		$result = $this->db->get()->row_array();
		return !empty($result['name']) ? $result['name'] : '';
	}
	
	public function getFile($file_id=''){
		$result = array();
		if($file_id > 0){
			$result = $this->db->where('file_id', $file_id)->get('files')->row_array();
		}
		
		return $result;
	}
	
	public function saveMemberIndustry($data=array(), $member_id=''){
		$this->db->where('member_id', $member_id)->delete('member_industry');
		$ins = array();
		foreach($data['industry'] as $k => $v){
			$ins[] = array(
				'member_id' => $member_id,
				'experience' => $data['experience'][$k],
				'industry' => $data['industry'][$k],
			);
		}
		
		$this->db->insert_batch('member_industry', $ins);
	}
	
	public function updateMemberLogo($logo='', $member_id=''){
		$where = array(
			'member_id' => $member_id,
		);
		$data = array(
			'logo' => $logo,
			'status' => 1,
		);
		$table = 'member_logo';
		$count = (bool) $this->db->where($where)->count_all_results($table);
		
		if($count){
			$this->db->where($where)->update($table, $data);
		}else{
			$data['member_id'] = $member_id;
			$data['reg_date'] = date('Y-m-d H:i:s');
			$this->db->insert($table, $data);
		}
	}
	
	
	public function getAllIndustry(){
		$default_lang = admin_default_lang();
		$this->db->select('c_c.category_subchild_id,c_c_n.category_subchild_name')
			->from('category c')
			->join('category_subchild c_c', 'c_c.category_id=c.category_id', 'LEFT')
			->join('category_subchild_names c_c_n', 'c_c_n.category_subchild_id=c_c.category_subchild_id', 'LEFT');
		$this->db->where('c.is_module', 'is-job');
		$this->db->where('c_c_n.category_subchild_lang', $default_lang);
		
		$this->db->group_by('c_c.category_subchild_id');
		
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	public function getUserBadge($user_id=''){
		$default_lang = admin_default_lang();
		$this->db->select('m_b.badge_id as ID, b.name')
				->from('member_badges m_b')
				->join('badges_names b', 'b.badge_id=m_b.badge_id')
				->where('b.lang', $default_lang);
		$this->db->where('m_b.member_id', $user_id);
		
		$result = $this->db->get()->result_array();
		return $result;
	}
	
}


