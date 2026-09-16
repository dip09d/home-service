<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proposal_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'project';
		$this->primary_key = 'project_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('p.*,m.member_name,o.member_id,s.is_hourly,s.is_fixed')
			->from($this->table . ' p')
			->join('project_category c', 'c.project_id=p.project_id', 'LEFT')
			->join('project_settings s', 's.project_id=p.project_id', 'LEFT')
			->join('project_owner o', 'o.project_id=p.project_id', 'LEFT')
			->join('member m', 'm.member_id=o.member_id', 'LEFT');
			
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('p.project_status', PROJECT_DELETED);	
		}else{
			$this->db->where('p.project_status <>', PROJECT_DELETED);	
		}
		
		if(!empty($srch['term'])){
			$this->db->like('p.project_title', $srch['term']);
		}
		
		if(!empty($srch['member_id'])){
			$this->db->where('o.member_id', $srch['member_id']);
		}
		
		if(!empty($srch['category'])){
			$this->db->where('c.category_id', $srch['category']);
		}
		if(!empty($srch['project_type'])){
			if($srch['project_type']=='F'){
				$this->db->where('s.is_fixed', 1);
			}
			elseif($srch['project_type']=='H'){
				$this->db->where('s.is_hourly', 1);
			}
			
		}
		
		if(!empty($srch['project_id'])){
			$this->db->where('p.project_id', $srch['project_id']);
		}
		
		if(!empty($srch['status'])){
			if($srch['status'] == 'featured'){
				$this->db->where('s.project_featured', 1);
			}else{
				$this->db->where('p.project_status', $srch['status']);
			}
		}
		
		
		$this->db->group_by('p.project_id');
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('p.'.$this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
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
	
	
	public function get_active_proposal(){
		$this->db->select('p.*')
			->from($this->table . ' p');
			
		
		$this->db->where('p.proposal_status', PROPOSAL_ACTIVE);	
		
		$result = $this->db->order_by($this->primary_key, 'DESC')->get()->result_array();
		
		return $result;
	}
	
	public function getReferralList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('p_r.*,s.member_name as seller,r.member_name as referrer,b.member_name as buyer,p.proposal_title as proposal')
			->from('proposals_referrals p_r')
			->join('member s', 's.member_id=p_r.seller_id', 'LEFT')
			->join('member r', 'r.member_id=p_r.referrer_id', 'LEFT')
			->join('member b', 'b.member_id=p_r.buyer_id', 'LEFT')
			->join('proposals p', 'p.proposal_id=p_r.proposal_id', 'LEFT');
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('p_r.status', PROPOSAL_DELETED);	
		}else{
			$this->db->where('p_r.status <>', PROPOSAL_DELETED);	
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('p_r.referral_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	
	}
	
	
	public function getAllDetail($project_id=''){
		$this->db->select('p.*,p_a.project_description')
			->from('project p')
			->join('project_additional p_a', 'p_a.project_id=p.project_id', 'LEFT');
		
		$this->db->where('p.project_id', $project_id);
		$result = $this->db->get()->row_array();
		
		$result['additional'] = $this->_getProjectAdditional($project_id); 
		$result['category'] = $this->_getProjectCategory($project_id); 
		$result['files'] = $this->_getProjectFiles($project_id);
		$result['owner'] = $this->_getProjectOwner($project_id);
		$result['settings'] = $this->_getProjectSettings($project_id);
		$result['questions'] = $this->_getProjectQuestions($project_id);
		$result['skills'] = $this->_getProjectSkills($project_id);
		/* $result['location'] = $this->_getProjectLocation($project_id); */
		/* $result['contact'] = $this->_getProjectContact($project_id); */
		/* $result['component'] = $this->_getProjectComponent($project_id); */
		
		return $result;
	}
	
	private function _getProjectSkills($project_id=''){
		$admin_default_lang = admin_default_lang();
		$this->db->select('s.skill_id,s.skill_key,s_n.skill_name')
			->from('project_skills p_s')
			->join('skills s', 's.skill_id=p_s.skill_id', 'LEFT')
			->join('skill_names s_n', 's_n.skill_id=s.skill_id', 'LEFT');
		
		$this->db->where('p_s.project_skill_status', 1);		
		$this->db->where('s_n.skill_lang', $admin_default_lang);
		$this->db->where('p_s.project_id', $project_id);
		
		$result = $this->db->get()->result_array();
		$return_result = array();
		if($result){
			$return_result['all_skills'] = $result;
			$return_result['skill_names'] = get_k_value_from_array($result, 'skill_name');
			
		}
		return $return_result;
	}
	
	private function _getProjectAdditional($project_id=''){
		$result = $this->db->where('project_id', $project_id)->get('project_additional')->row_array();
		return $result;
	}
	
	private function _getProjectQuestions($project_id=''){
		$this->db->select('q.*')
				->from('question q')
				->join('project_question p_q', 'p_q.question_id=q.question_id', 'INNER');
		$this->db->where('p_q.project_id', $project_id);
		$result = $this->db->get()->result_array();
		return $result;
	}
	private function _getProjectSettings($project_id=''){
		$admin_default_lang = admin_default_lang();
		$result = $this->db->where('project_id', $project_id)->get('project_settings')->row_array();
		if($result){
			$name_info = get_row(array(
				'select' => '*',
				'from' => 'experience_level_name',
				'where' => array(
						'experience_level_id' => $result['experience_level'],
						'experience_level_lang' => $admin_default_lang,
					),
			));
		
			$result['experience_level'] = array(
				'name' => $name_info['experience_level_name'],
				'ID' => $result['experience_level'],
			);
		}
		return $result;
	}
	
	private function _getProjectCategory($project_id=''){
		$admin_default_lang = admin_default_lang();
		$table = 'project_category';
		$this->db->select('p_c.*')
			->from("$table p_c")
			->where('project_id', $project_id);
		$result = $this->db->get()->row();
		$p_category = array();
		if($result){
			$p_category['category'] = get_row(array(
				'select' => 'c.category_key as key,c_n.category_name as name ,c.category_id as id',
				'from' => 'category c',
				'join' => array(
							array('category_names c_n', 'c_n.category_id=c.category_id', 'LEFT'),
						),
				'where' => array(
							'c_n.category_lang' => $admin_default_lang,
							'c.category_id' => $result->category_id,
						)
			));
			
			$p_category['sub_category'] = get_row(array(
				'select' => 'c.category_subchild_key as key,c_n.category_subchild_name as name ,c.category_subchild_id as id',
				'from' => 'category_subchild c',
				'join' => array(
							array('category_subchild_names c_n', 'c_n.category_subchild_id=c.category_subchild_id', 'LEFT'),
						),
				'where' => array(
							'c_n.category_subchild_lang' => $admin_default_lang,
							'c.category_subchild_id' => $result->category_subchild_id,
						)
			));
			
			/* $p_category['sub_category_level_3'] = get_row(array(
				'select' => 'c.category_subchild_key as key,c_n.category_subchild_name as name ,c.category_subchild_level_3_id as id',
				'from' => 'category_subchild_level_3 c',
				'join' => array(
							array('category_subchild_level_3_names c_n', 'c_n.category_subchild_level_3_id=c.category_subchild_level_3_id', 'LEFT'),
						),
				'where' => array(
							'c_n.category_subchild_lang' => $admin_default_lang,
							'c.category_subchild_level_3_id' => $result->category_subchild_level_3_id,
						)
			));
			
			$p_category['sub_category_level_4'] = get_row(array(
				'select' => 'c.category_subchild_key as key,c_n.category_subchild_name as name ,c.category_subchild_level_4_id as id',
				'from' => 'category_subchild_level_4 c',
				'join' => array(
						array('category_subchild_level_4_names c_n', 'c_n.category_subchild_level_4_id=c.category_subchild_level_4_id', 'LEFT'),
					),
				'where' => array(
							'c_n.category_subchild_lang' => $admin_default_lang,
							'c.category_subchild_level_4_id' => $result->category_subchild_level_4_id,
						)
			)); */
		}
		
		return $p_category;
	}
	
	private function _getProjectFiles($project_id=''){
		$table = 'project_files';
		$this->db->select('f.*')
			->from("$table p_f")
			->join('files f', 'f.file_id=p_f.file_id', 'INNER')
			->where('p_f.project_id', $project_id);
			
		$result = $this->db->get()->result_array();
		foreach($result as $k => $file){
			$result[$k]['file_url'] = UPLOAD_HTTP_PATH.'projects-files/projects-requirement/'.$file['server_name'];
		}
		
		return $result;
	}
	
	private function _getProjectOwner($project_id=''){
		$this->load->model('member/member_model');
		$table = 'project_owner';
		$owner_id = getField('member_id', $table, 'project_id', $project_id);
		$owner_info = $this->member_model->getDetail($owner_id);
		return $owner_info;
	}
	
	private function _getProjectLocation($project_id=''){
		$admin_default_lang = admin_default_lang();
		$table = 'project_location';
		$result = get_row(array(
			'select' => '*',
			'from' => $table,
			'where' => array('project_id' => $project_id),
		));
		
		$result['city']  = $result['state'] = $result['country'] = array();
		
		$country_info = get_row(array(
			'select' => '*',
			'from' => 'country_names',
			'where' => array(
					'country_code' => $result['location_country'],
					'country_lang' => $admin_default_lang,
				),
		));
		
		$state_info = get_row(array(
			'select' => '*',
			'from' => 'state_names',
			'where' => array(
					'state_id' => $result['location_state'],
					'state_lang' => $admin_default_lang,
				),
		));
		
		$city_info = get_row(array(
			'select' => '*',
			'from' => 'city_names',
			'where' => array(
					'city_id' => $result['location_city'],
					'city_lang' => $admin_default_lang,
				),
		));
		
		if($country_info){
			$result['country'] = array(
				'name' => $country_info['country_name'],
				'code' => $result['location_country'],
			);
		}
		
		if($state_info){
			$result['state'] = array(
				'name' => $state_info['state_name'],
				'id' => $result['location_state'],
			);
		}
		
		if($city_info){
			$result['city'] = array(
				'name' => $city_info['city_name'],
				'id' => $result['location_city'],
			);
		}
		
		return $result;
		
	}
	
	private function _getProjectContact($project_id=''){
		$table = 'project_contact';
		$result = get_row(array(
			'select' => '*',
			'from' => $table,
			'where' => array('project_id' => $project_id),
		));
		
		return $result;
	}
	private function _getProjectComponent($project_id=''){
		$table = 'project_component_data';
		$result = get_results(array(
			'select' => '*',
			'from' => 'project_component_data',
			'where' => array('project_id' => $project_id),
		));
		
		$component_data = array();
		
		foreach($result as $k => $v){
			$component_data[$v['component_key']] = $v['component_value'];
		}
		
		return $component_data;
	}
	
	public function getProjectComponent($project_id=''){
		return $this->_getProjectComponent($project_id);
	}
	
	public function updateBasicInfo($data=array(), $project_id=''){
		return $this->db->where('project_id', $project_id)->update('project', $data);
	}
	
	public function updateAdditionalInfo($data=array(), $project_id=''){
		return $this->db->where('project_id', $project_id)->update('project_additional', $data);
	}
	
	public function updateProjectCategory($data=array(), $project_id=''){
		return $this->db->where('project_id', $project_id)->update('project_category', $data);
	}
	
	public function updateProjectContact($data=array(), $project_id=''){
		return $this->db->where('project_id', $project_id)->update('project_contact', $data);
	}
	
	public function updateProjectLocation($data=array(), $project_id=''){
		return $this->db->where('project_id', $project_id)->update('project_location', $data);
	}
	
	public function updateProjectSettings($data=array(), $project_id=''){
		return $this->db->where('project_id', $project_id)->update('project_settings', $data);
	}
	
	public function updateProjectComponent($data=array(), $project_id=''){
		$ins = array();
		$this->db->where('project_id', $project_id)->delete('project_component_data');
		foreach($data as $component_key => $component_value){
			if(is_array($component_value)){
				$component_value = implode(',', $component_value);
			}
			$ins[] = array(
				'project_id' => $project_id,
				'component_key' => $component_key,
				'component_value' => $component_value,
			);
		}
		
		if($ins){
			$this->db->insert_batch('project_component_data', $ins);
		}
		
	}
	
}


