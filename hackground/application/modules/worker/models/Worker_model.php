<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Worker_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'worker';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$admin_default_lang = admin_default_lang();
		$this->db->select("
			u.*,
			wi.employee_id,
			TIMESTAMPDIFF(YEAR, u.worker_dob, CURDATE()) AS age,
			CASE 
			WHEN EXISTS (
					SELECT 1 
					FROM {$this->db->dbprefix('invoice')} inv 
					WHERE inv.recipient_member_id = u.worker_id 
					AND inv.invoice_status = '1'
				) THEN 'PAID'
				ELSE 'UNPAID'
			END AS worker_payment_status,
			GROUP_CONCAT(
				DISTINCT csn.category_subchild_name
				ORDER BY ws.category_subchild_order
			) AS sub_cat_names, sn.state_name,wa.worker_city,wa.worker_address,wa.worker_flat,wa.worker_street,wa.worker_pincode,wa.worker_landmark
		")
		->from($this->table.' as u')
		->join('worker_icard as wi', 'wi.worker_id = u.worker_id', 'LEFT')
		//->join('invoice as inv', 'inv.recipient_member_id = u.worker_id', 'LEFT')
		->join('worker_service as ws', 'ws.worker_id = u.worker_id', 'LEFT')
		->join('worker_address as wa', 'u.worker_id = wa.worker_id', 'LEFT')
		->join('state_names as sn', "(wa.worker_state = sn.state_id and sn.state_lang='".$admin_default_lang."')", 'LEFT')
		->join(
			'category_subchild_names as csn',
			'csn.category_subchild_id = ws.category_subchild_id
			AND csn.category_subchild_lang = '.$this->db->escape($admin_default_lang),
			'LEFT'
		)
		->group_by('u.worker_id');
			
		
		if(!empty($srch['worker_id'])){
			$this->db->where('u.worker_id',$srch['worker_id']);
		}

		if(!empty($srch['term'])){
			$this->db->group_start();
			$this->db->like('u.worker_name', $srch['term']);
			$this->db->or_like('u.worker_phone', $srch['term']);
			$this->db->group_end();
		}
		if (isset($srch['status'])) {
			$this->db->where('u.login_status', $srch['status']);
		}
		if (isset($srch['invoice_status']) && $srch['invoice_status']!='') {
			
			if($srch['invoice_status']==1){
				$this->db->having('worker_payment_status ','PAID');
			}else{
				$this->db->having('worker_payment_status ','UNPAID');
			}
			
		}
		
		if(!empty($srch['category'])){
			$this->db->where('ws.category_subchild_id',$srch['category']);
		}
		$this->db->group_by('u.worker_id');
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('u.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		return $result;
	}
	
	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'worker_name' => !empty($data['worker_name']) ? $data['worker_name'] : '',
			'worker_email' => !empty($data['worker_email']) ? $data['worker_email'] : '',
			'worker_phone' => !empty($data['worker_phone']) ? $data['worker_phone'] : '',
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
	
	
	public function getAllDetail($worker_id=''){
		$worker = $this->db->where('worker_id', $worker_id)->get('worker')->row_array();
		$worker['worker_address'] = $this->_getWorkerAddress($worker_id);
		//$worker['worker_basic'] = $this->_getWorkerBasic($worker_id);
		$worker['worker_logo'] = $this->_getWorkerLogo($worker_id);
		$worker['worker_service'] = $this->_getWorkerService($worker_id);
		$worker['worker_kyc'] = $this->_getWorkerKyc($worker_id);
		$worker['worker_bank'] = $this->_getWorkerbank($worker_id);
	/* 	$worker['worker_skills'] = $this->_getWorkerSkills($worker_id);
		$worker['worker_language'] = $this->_getWorkerLanguage($worker_id);
		$worker['worker_employment'] = $this->_getWorkerEmployment($worker_id);
		$worker['worker_education'] = $this->_getWorkerEducation($worker_id);
		$worker['worker_portfolio'] = $this->_getWorkerPortfolio($worker_id); */
		
		
		return $worker;
	}
	private function _getWorkerKyc($worker_id=''){
		$admin_default_lang = admin_default_lang();
		$result = $this->db->select('*')
				->from('worker_kyc')
				->where('worker_id', $worker_id)
				->where('kyc_status', 1)
				->get()
				->result_array();

		return $result;
	}
	private function _getWorkerbank($worker_id=''){
		$admin_default_lang = admin_default_lang();
		$result = $this->db->select('*')
				->from('worker_bank')
				->where('worker_id', $worker_id)
				->where('bank_status', 1)
				->get()
				->result_array();

		return $result;
	}
	
	private function _getWorkerEmployment($worker_id=''){
		$result = $this->db->select('*')
				->from('worker_employment')
				->where('worker_id', $worker_id)
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
	
	private function _getWorkerPortfolio($worker_id=''){
		$admin_default_lang = admin_default_lang();
		$result = $this->db->select('*')
				->from('worker_portfolio')
				->where('worker_id', $worker_id)
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
	
	private function _getWorkerEducation($worker_id=''){
		$result = $this->db->select('*')
				->from('worker_education')
				->where('worker_id', $worker_id)
				->where('education_status', 1)
				->get()
				->result_array();
		return $result;
	}
	
	private function _getWorkerLanguage($worker_id=''){
		$this->db->select('m_l.worker_language_id,l.language_name,l.language_id,m_l.language_preference_id,l_p.language_preference_name,l_p.language_preference_info')
			->from('worker_language m_l')
			->join('language l', 'm_l.language_id=l.language_id', 'LEFT')
			->join('language_preference l_p', 'l_p.language_preference_id=m_l.language_preference_id', 'LEFT');
		
		$this->db->where('m_l.worker_id', $worker_id);
		$this->db->where('m_l.language_status', '1');
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	private function _getWorkerService($worker_id=''){
		 $admin_default_lang = admin_default_lang();
		$this->db->select('s.category_subchild_id,s_n.category_subchild_name')
			->from('worker_service p_s')
			->join('category_subchild s', 's.category_subchild_id=p_s.category_subchild_id', 'LEFT')
			->join('category_subchild_names s_n', 's_n.category_subchild_id=s.category_subchild_id', 'LEFT');
			
		$this->db->where('s_n.category_subchild_lang', $admin_default_lang);
		$this->db->where('p_s.worker_id', $worker_id);
		
		$result = $this->db->get()->result_array();
		$return_result = array();
		if($result){
			$return_result['all_service'] = $result;
			$return_result['service_names'] = get_k_value_from_array($result, 'category_subchild_name');
			
		}
		return $return_result; 
	}
	
	private function _getWorkerSkills($worker_id=''){
		$admin_default_lang = admin_default_lang();
		$this->db->select('s.skill_id,s.skill_key,s_n.skill_name')
			->from('worker_skills p_s')
			->join('skills s', 's.skill_id=p_s.skill_id', 'LEFT')
			->join('skill_names s_n', 's_n.skill_id=s.skill_id', 'LEFT');
			
		$this->db->where('s_n.skill_lang', $admin_default_lang);
		$this->db->where('p_s.worker_id', $worker_id);
		
		$result = $this->db->get()->result_array();
		$return_result = array();
		if($result){
			$return_result['all_skills'] = $result;
			$return_result['skill_names'] = get_k_value_from_array($result, 'skill_name');
			
		}
		return $return_result;
	}
	
	private function _getWorkerAddress($worker_id=''){
		$worker_address = $this->db->where('worker_id', $worker_id)->get('worker_address')->row_array();
		
		return $worker_address;
	}
	private function _getOrganizationAddress($organization_id=''){
		$worker_address = $this->db->where('organization_id', $organization_id)->get('organization_address')->row_array();
		if($worker_address){
			$worker_address['worker_country'] = array(
				'code' => $worker_address['organization_country'] ,
				'name' => get_country_name($worker_address['organization_country']) ,
			);
			
			$worker_address['worker_current_location'] = array(
				'code' => $worker_address['organization_country'] ,
				'name' => get_country_name($worker_address['organization_country']) ,
			);
			
		}
		
		return $worker_address;
	}
	private function _getOrganizationBasic($organization_id=''){
		$organization_basic = $this->db->where('organization_id', $organization_id)->get('organization')->row_array();
		return $organization_basic;
	}
	private function _getWorkerBasic($worker_id=''){
		$worker_basic = $this->db->where('worker_id', $worker_id)->get('worker_basic')->row_array();
		return $worker_basic;
	}
	
	private function _getWorkerIndustry($worker_id=''){
		$this->db->select('*')
				->from('worker_industry')
				->where('worker_id', $worker_id);
				
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	private function _getWorkerLogo($worker_id=''){
		return getWorkerLogo($worker_id);
	}
	
	private function _getWorkerProfessional($worker_id=''){
		return $this->getWorkerInfo('professional', $worker_id);
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
	
	public function saveWorkerInfo($data=array(), $worker_id=0){
		if(($worker_id > 0) === false){
			return false;
		}
	
		$worker_main = !empty($data['worker']) ? $data['worker'] : array(); 
		$worker_professional = !empty($data['worker_professional']) ? $data['worker_professional'] : array(); 
		$worker_address = !empty($data['worker_address']) ? $data['worker_address'] : array(); 
		$worker_basic = !empty($data['worker_basic']) ? $data['worker_basic'] : array(); 
		$where = array(
			'worker_id' => $worker_id
		);
		if($worker_main){
			$this->db->where($where)->update('worker', $worker_main);
		}
		if($worker_professional){
			$table = 'worker_professional';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $worker_professional);
			}else{
				$worker_professional['worker_id'] = $worker_id;
				$this->db->insert($table, $worker_professional);
			}
			
		}
		if($worker_address){
			$table = 'worker_address';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $worker_address);
			}else{
				$worker_address['worker_id'] = $worker_id;
				$this->db->insert($table, $worker_address);
			}
			
		}
		if($worker_basic){
			$table = 'worker_basic';
			$count = (bool) $this->db->where($where)->count_all_results($table);
			
			if($count){
				$this->db->where($where)->update($table, $worker_basic);
			}else{
				$worker_basic['worker_id'] = $worker_id;
				$this->db->insert($table, $worker_basic);
			}
			
			
		}
		
	}
	public function saveOrganizationInfo($data=array(), $organization_id=0){
		if(($organization_id > 0) === false){
			return false;
		}
	
		$worker_main = !empty($data['worker']) ? $data['worker'] : array(); 
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
		if($worker_main){
			$this->db->where($where)->update('worker', $worker_main);
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
	
	public function getWorkerInfo($info_type='', $worker_id=0){
		if(($worker_id > 0) === false){
			return false;
		}
		$where = array(
			'worker_id' => $worker_id
		);
		$result = array();
		if($info_type == 'professional'){
			$table = 'worker_professional';
			$result = $this->db->where($where )->get($table)->row_array();
			if($result){
				$result['worker_career_level'] = array(
					'name' => $this->_getOptionName('career_level', $result['worker_career_level']),
					'ID' => $result['worker_career_level'],
				);
				$result['worker_current_position'] = array(
					'name' => $this->_getOptionName('current_position', $result['worker_current_position']),
					'ID' => $result['worker_current_position'],
				);
				$result['worker_salary_expectation'] = array(
					'name' => $this->_getOptionName('salary_expectation', $result['worker_salary_expectation']),
					'ID' => $result['worker_salary_expectation'],
				);
				$result['worker_commitment'] = array(
					'name' => $this->_getOptionName('commitment', $result['worker_commitment']),
					'ID' => $result['worker_commitment'],
				);
				$result['worker_notice_period'] = array(
					'name' => $this->_getOptionName('notice_period', $result['worker_notice_period']),
					'ID' => $result['worker_notice_period'],
				);
				$result['worker_visa_status'] = array(
					'name' => $this->_getOptionName('visa_status', $result['worker_visa_status']),
					'ID' => $result['worker_visa_status'],
				);
			}
		}else if($info_type == 'address'){
			$table = 'worker_address';
			$result = $this->db->where($where)->get($table)->row_array();
			if($result){
				$result['worker_country'] = array(
					'code' => $result['worker_country'],
					'name' => get_country_name($result['worker_country'])
				);
				$result['worker_current_location'] = array(
					'code' => $result['worker_current_location'],
					'name' => get_country_name($result['worker_current_location'])
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
	
	public function saveWorkerIndustry($data=array(), $worker_id=''){
		$this->db->where('worker_id', $worker_id)->delete('worker_industry');
		$ins = array();
		foreach($data['industry'] as $k => $v){
			$ins[] = array(
				'worker_id' => $worker_id,
				'experience' => $data['experience'][$k],
				'industry' => $data['industry'][$k],
			);
		}
		
		$this->db->insert_batch('worker_industry', $ins);
	}
	
	public function updateWorkerLogo($logo='', $worker_id=''){
		$where = array(
			'worker_id' => $worker_id,
		);
		$data = array(
			'logo' => $logo,
			'status' => 1,
		);
		$table = 'worker_logo';
		$count = (bool) $this->db->where($where)->count_all_results($table);
		
		if($count){
			$this->db->where($where)->update($table, $data);
		}else{
			$data['worker_id'] = $worker_id;
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
				->from('worker_badges m_b')
				->join('badges_names b', 'b.badge_id=m_b.badge_id')
				->where('b.lang', $default_lang);
		$this->db->where('m_b.worker_id', $user_id);
		
		$result = $this->db->get()->result_array();
		return $result;
	}
	public function getData(){
		$default_lang = admin_default_lang();
		
		$this->db->select('a.category_subchild_id,b.category_subchild_name')
			->from('category_subchild a')
			->join('category_subchild_names b', 'a.category_subchild_id=b.category_subchild_id');
		
			$this->db->where('a.category_subchild_status', 1);
		if(!empty($srch['category'])){
			$this->db->where('a.category_id', $srch['category']);	
			
		}
		
		$this->db->where('b.category_subchild_lang', $default_lang);	
		$result = $this->db->order_by('a.category_subchild_id', 'DESC')->get()->result_array();
		
		return $result;
	}
}


