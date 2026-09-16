<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agency_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'agency';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('u.*')
			->from($this->table.' as u');
		
		
		if(!empty($srch['agency_id'])){
			$this->db->where('u.agency_id',$srch['agency_id']);
		}
		
		if (isset($srch['status'])) {
			$this->db->where('u.login_status', $srch['status']);
		}

		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('u.agency_name', $srch['term']);
			$this->db->or_like('u.agency_member_name', $srch['term']);
			$this->db->or_like('u.agency_phone', $srch['term']);
			$this->db->group_end();
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('u.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		// echo $this->db->last_query(); die;
		return $result;
	}
	
	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'agency_name' => !empty($data['agency_name']) ? $data['agency_name'] : '',
			'agency_email' => !empty($data['agency_email']) ? $data['agency_email'] : '',
			'agency_phone' => !empty($data['agency_phone']) ? $data['agency_phone'] : '',
			'login_status' => !empty($data['is_login']) ? $data['is_login'] : '0',
			
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
	
	
	public function getAllDetail($agency_id=''){
		$agency = $this->db->where('agency_id', $agency_id)->get('agency')->row_array();
		$agency['agency_address'] = $this->_getAgencyAddress($agency_id);
		//$agency['agency_basic'] = $this->_getAgencyBasic($agency_id);
	/* 	$agency['agency_logo'] = $this->_getAgencyLogo($agency_id);
		$agency['agency_service'] = $this->_getAgencyService($agency_id);
		$agency['agency_kyc'] = $this->_getAgencyKyc($agency_id);
		$agency['agency_bank'] = $this->_getAgencybank($agency_id); */
	/* 	$agency['agency_skills'] = $this->_getAgencySkills($agency_id);
		$agency['agency_language'] = $this->_getAgencyLanguage($agency_id);
		$agency['agency_employment'] = $this->_getAgencyEmployment($agency_id);
		$agency['agency_education'] = $this->_getAgencyEducation($agency_id);
		$agency['agency_portfolio'] = $this->_getAgencyPortfolio($agency_id); */
		
		
		return $agency;
	}
	private function _getAgencyKyc($agency_id=''){
		$admin_default_lang = admin_default_lang();
		$result = $this->db->select('*')
				->from('agency_kyc')
				->where('agency_id', $agency_id)
				->where('kyc_status', 1)
				->get()
				->result_array();

		return $result;
	}
	private function _getAgencybank($agency_id=''){
		$admin_default_lang = admin_default_lang();
		$result = $this->db->select('*')
				->from('agency_bank')
				->where('agency_id', $agency_id)
				->where('bank_status', 1)
				->get()
				->result_array();

		return $result;
	}
	
	private function _getAgencyEmployment($agency_id=''){
		$result = $this->db->select('*')
				->from('agency_employment')
				->where('agency_id', $agency_id)
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
	
	private function _getAgencyPortfolio($agency_id=''){
		$admin_default_lang = admin_default_lang();
		$result = $this->db->select('*')
				->from('agency_portfolio')
				->where('agency_id', $agency_id)
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
	
	private function _getAgencyEducation($agency_id=''){
		$result = $this->db->select('*')
				->from('agency_education')
				->where('agency_id', $agency_id)
				->where('education_status', 1)
				->get()
				->result_array();
		return $result;
	}
	
	private function _getAgencyLanguage($agency_id=''){
		$this->db->select('m_l.agency_language_id,l.language_name,l.language_id,m_l.language_preference_id,l_p.language_preference_name,l_p.language_preference_info')
			->from('agency_language m_l')
			->join('language l', 'm_l.language_id=l.language_id', 'LEFT')
			->join('language_preference l_p', 'l_p.language_preference_id=m_l.language_preference_id', 'LEFT');
		
		$this->db->where('m_l.agency_id', $agency_id);
		$this->db->where('m_l.language_status', '1');
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	private function _getAgencyService($agency_id=''){
		 $admin_default_lang = admin_default_lang();
		$this->db->select('s.category_subchild_id,s_n.category_subchild_name')
			->from('agency_service p_s')
			->join('category_subchild s', 's.category_subchild_id=p_s.category_subchild_id', 'LEFT')
			->join('category_subchild_names s_n', 's_n.category_subchild_id=s.category_subchild_id', 'LEFT');
			
		$this->db->where('s_n.category_subchild_lang', $admin_default_lang);
		$this->db->where('p_s.agency_id', $agency_id);
		
		$result = $this->db->get()->result_array();
		$return_result = array();
		if($result){
			$return_result['all_service'] = $result;
			$return_result['service_names'] = get_k_value_from_array($result, 'category_subchild_name');
			
		}
		return $return_result; 
	}
	
	private function _getAgencySkills($agency_id=''){
		$admin_default_lang = admin_default_lang();
		$this->db->select('s.skill_id,s.skill_key,s_n.skill_name')
			->from('agency_skills p_s')
			->join('skills s', 's.skill_id=p_s.skill_id', 'LEFT')
			->join('skill_names s_n', 's_n.skill_id=s.skill_id', 'LEFT');
			
		$this->db->where('s_n.skill_lang', $admin_default_lang);
		$this->db->where('p_s.agency_id', $agency_id);
		
		$result = $this->db->get()->result_array();
		$return_result = array();
		if($result){
			$return_result['all_skills'] = $result;
			$return_result['skill_names'] = get_k_value_from_array($result, 'skill_name');
			
		}
		return $return_result;
	}
	
	private function _getAgencyAddress($agency_id=''){
		$agency_address = $this->db->where('agency_id', $agency_id)->get('agency_address')->row_array();
		
		return $agency_address;
	}
	private function _getOrganizationAddress($organization_id=''){
		$agency_address = $this->db->where('organization_id', $organization_id)->get('organization_address')->row_array();
		if($agency_address){
			$agency_address['agency_country'] = array(
				'code' => $agency_address['organization_country'] ,
				'name' => get_country_name($agency_address['organization_country']) ,
			);
			
			$agency_address['agency_current_location'] = array(
				'code' => $agency_address['organization_country'] ,
				'name' => get_country_name($agency_address['organization_country']) ,
			);
			
		}
		
		return $agency_address;
	}
	private function _getOrganizationBasic($organization_id=''){
		$organization_basic = $this->db->where('organization_id', $organization_id)->get('organization')->row_array();
		return $organization_basic;
	}
	private function _getAgencyBasic($agency_id=''){
		$agency_basic = $this->db->where('agency_id', $agency_id)->get('agency_basic')->row_array();
		return $agency_basic;
	}
	
	private function _getAgencyIndustry($agency_id=''){
		$this->db->select('*')
				->from('agency_industry')
				->where('agency_id', $agency_id);
				
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	private function _getAgencyLogo($agency_id=''){
		return getAgencyLogo($agency_id);
	}
	
	private function _getAgencyProfessional($agency_id=''){
		return $this->getAgencyInfo('professional', $agency_id);
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
	
	public function saveAgencyInfo($data=array(), $agency_id=0){
		if(($agency_id > 0) === false){
			return false;
		}
	
		$agency_main = !empty($data['agency']) ? $data['agency'] : array(); 
		$agency_professional = !empty($data['agency_professional']) ? $data['agency_professional'] : array(); 
		$agency_address = !empty($data['agency_address']) ? $data['agency_address'] : array(); 
		$agency_basic = !empty($data['agency_basic']) ? $data['agency_basic'] : array(); 
		$where = array(
			'agency_id' => $agency_id
		);
		if($agency_main){
			$this->db->where($where)->update('agency', $agency_main);
		}
		if($agency_professional){
			$table = 'agency_professional';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $agency_professional);
			}else{
				$agency_professional['agency_id'] = $agency_id;
				$this->db->insert($table, $agency_professional);
			}
			
		}
		if($agency_address){
			$table = 'agency_address';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $agency_address);
			}else{
				$agency_address['agency_id'] = $agency_id;
				$this->db->insert($table, $agency_address);
			}
			
		}
		if($agency_basic){
			$table = 'agency_basic';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $agency_basic);
			}else{
				$agency_basic['agency_id'] = $agency_id;
				$this->db->insert($table, $agency_basic);
			}
			
			
		}
		
	}
	public function saveOrganizationInfo($data=array(), $organization_id=0){
		if(($organization_id > 0) === false){
			return false;
		}
	
		$agency_main = !empty($data['agency']) ? $data['agency'] : array(); 
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
		if($agency_main){
			$this->db->where($where)->update('agency', $agency_main);
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
	
	public function getAgencyInfo($info_type='', $agency_id=0){
		if(($agency_id > 0) === false){
			return false;
		}
		$where = array(
			'agency_id' => $agency_id
		);
		$result = array();
		if($info_type == 'professional'){
			$table = 'agency_professional';
			$result = $this->db->where($where )->get($table)->row_array();
			if($result){
				$result['agency_career_level'] = array(
					'name' => $this->_getOptionName('career_level', $result['agency_career_level']),
					'ID' => $result['agency_career_level'],
				);
				$result['agency_current_position'] = array(
					'name' => $this->_getOptionName('current_position', $result['agency_current_position']),
					'ID' => $result['agency_current_position'],
				);
				$result['agency_salary_expectation'] = array(
					'name' => $this->_getOptionName('salary_expectation', $result['agency_salary_expectation']),
					'ID' => $result['agency_salary_expectation'],
				);
				$result['agency_commitment'] = array(
					'name' => $this->_getOptionName('commitment', $result['agency_commitment']),
					'ID' => $result['agency_commitment'],
				);
				$result['agency_notice_period'] = array(
					'name' => $this->_getOptionName('notice_period', $result['agency_notice_period']),
					'ID' => $result['agency_notice_period'],
				);
				$result['agency_visa_status'] = array(
					'name' => $this->_getOptionName('visa_status', $result['agency_visa_status']),
					'ID' => $result['agency_visa_status'],
				);
			}
		}else if($info_type == 'address'){
			$table = 'agency_address';
			$result = $this->db->where($where)->get($table)->row_array();
			if($result){
				$result['agency_country'] = array(
					'code' => $result['agency_country'],
					'name' => get_country_name($result['agency_country'])
				);
				$result['agency_current_location'] = array(
					'code' => $result['agency_current_location'],
					'name' => get_country_name($result['agency_current_location'])
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
	
	public function saveAgencyIndustry($data=array(), $agency_id=''){
		$this->db->where('agency_id', $agency_id)->delete('agency_industry');
		$ins = array();
		foreach($data['industry'] as $k => $v){
			$ins[] = array(
				'agency_id' => $agency_id,
				'experience' => $data['experience'][$k],
				'industry' => $data['industry'][$k],
			);
		}
		
		$this->db->insert_batch('agency_industry', $ins);
	}
	
	public function updateAgencyLogo($logo='', $agency_id=''){
		$where = array(
			'agency_id' => $agency_id,
		);
		$data = array(
			'logo' => $logo,
			'status' => 1,
		);
		$table = 'agency_logo';
		$count = (bool) $this->db->where($where)->count_all_results($table);
		
		if($count){
			$this->db->where($where)->update($table, $data);
		}else{
			$data['agency_id'] = $agency_id;
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
				->from('agency_badges m_b')
				->join('badges_names b', 'b.badge_id=m_b.badge_id')
				->where('b.lang', $default_lang);
		$this->db->where('m_b.agency_id', $user_id);
		
		$result = $this->db->get()->result_array();
		return $result;
	}
	
}


