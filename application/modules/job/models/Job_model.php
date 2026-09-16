<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Job_model extends CI_Model {
	
	private $lang;
	
    public function __construct() {
		$this->lang = get_active_lang();
        return parent::__construct();
    }
	
	public function getJobList($srch=array(), $limit=0, $offset=10, $for_list=TRUE){
		$this->load->model('skill_model');
		
		$this->db->select("p.project_id,p.project_url,p.project_title,p.project_short_info,p.project_posted_date,p_s.is_hourly,p_s.is_fixed,p_s.budget,p_s.project_type_code,p_o.member_id as owner_id,p_o.organization_id,e_l.experience_level_key,e_l_n.experience_level_name,p_s.hourly_duration,p_s.hourly_time_required")
			->from("project p")
			->join("project_settings p_s", "p_s.project_id=p.project_id", "LEFT")
			->join("experience_level as e_l", "p_s.experience_level=e_l.experience_level_id", "LEFT")
			->join("experience_level_name as e_l_n", "(e_l.experience_level_id=e_l_n.experience_level_id and e_l_n.experience_level_lang='".$this->lang."')", "LEFT")
			->join("project_category p_c", "p_c.project_id=p.project_id", "LEFT")
			->join("project_owner p_o", "p_o.project_id=p.project_id", "LEFT");
		
		if(!empty($srch['category'])){
			$this->db->where("p_c.category_id", $srch['category']);
		}
		
		if(!empty($srch['sub_category'])){
			$this->db->where("p_c.category_subchild_id", $srch['sub_category']);
		}
		
		if(!empty($srch['experience_level'])){
			$this->db->where('p_s.experience_level', $srch['experience_level']);
		}
		
		if(!empty($srch['job_type'])){
			$this->db->where('p_s.project_type_code', $srch['job_type']);
		}
		if(!empty($srch['is_hourly'])){
			$this->db->where('p_s.is_hourly', $srch['is_hourly']);
		}
		
		if(!empty($srch['min']) && $srch['min'] > 0){
			$this->db->where('p_s.budget >=', $srch['min']);
		}
		
		if(!empty($srch['max']) && $srch['max'] > 0){
			$this->db->where('p_s.budget <=', $srch['max']);
		}
		$this->db->where('p.project_status',PROJECT_OPEN);
		if(!empty($srch['term'])){
			$this->db->like('p.project_title', $srch['term']);
		}
		
		if($for_list){
			
			if(!empty($srch['order_by'])){
				if($srch['order_by'] == 'default'){
					$this->db->order_by("p.project_id", "DESC");
				}else if($srch['order_by'] == 'latest'){
					$this->db->order_by("p.project_id", "DESC");
				}else if($srch['order_by'] == 'old'){
					$this->db->order_by("p.project_id", "ASC");
				}else if($srch['order_by'] == 'random'){
					$this->db->order_by("RAND()");
				}
			}
		
			$result = $this->db->limit($offset, $limit)->order_by("p.project_id", "DESC")->get()->result_array();
			
			if($result){
				foreach($result as $k => $v){
					
					if($v['organization_id'] > 0){
						$memberData=getData(array(
							'select'=>'o.organization_name,o.organization_register_date,o_a.organization_timezone,o_a.organization_city,o_a.organization_state,c_n.country_name',
							'table'=>'organization as o',
							'join'=>array(
								array('table'=>'organization_address as o_a','on'=>'o.organization_id=o_a.organization_id','position'=>'left'),
								array('table'=>'country as c','on'=>'o_a.organization_country=c.country_code','position'=>'left'),
								array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
							),
							'where'=>array('o.organization_id'=>$v['organization_id']),
							'single_row'=>true,
						));
				
						$client_name=$memberData->organization_name;
						$client_address=array();
						$location_address=array();
						if($memberData->organization_city){
							$location_address[]=$memberData->organization_city;
						}
						if($memberData->organization_state){
							$location_address[]=$memberData->organization_state;
						}
						$location=implode(', ',$location_address);
						$client_country="";
						if($memberData->country_name){
							$client_country=$memberData->country_name;
						}
						$client_address['location']=$location;
						$client_address['country']=$client_country;
					}else{
						$memberData=getData(array(
							'select'=>'m.member_name,m.member_register_date,m_a.member_city,m_a.member_state,c_n.country_name',
							'table'=>'member as m',
							'join'=>array(
								array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),
								array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),
								array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
							),
							'where'=>array('m.member_id'=>$v['owner_id']),
							'single_row'=>true,
						));
						$client_name=$memberData->member_name;
						$client_address=array();
						$location_address=array();
						if($memberData->member_city){
							$location_address[]=$memberData->member_city;
						}
						if($memberData->member_state){
							$location_address[]=$memberData->member_state;
						}
						$location=implode(', ',$location_address);
						$client_country="";
						if($memberData->country_name){
							$client_country=$memberData->country_name;
						}
						$client_address['location']=$location;
						$client_address['country']=$client_country;
					}
					
					
					$result[$k]['clientInfo']=array(
						'client_name'=>$client_name,
						'client_address'=>$client_address,
					);
			
					$result[$k]['skills'] = $this->skill_model->getProjectSkill($v['project_id']);
					$result[$k]['total_proposal'] = getBids($v['project_id'],array(),true);
					$result[$k]['project_detail_url'] = URL::get_link('myProjectDetailsURL').'/'.$v['project_url'];
				}
			}
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		
		return $result;
	}
	
	public function get_all_category(){
		$this->db->select('*')
			->from('category a')
			->join('category_names b', 'a.category_id=b.category_id');
			
		
		$this->db->where('a.category_status', STATUS_ACTIVE);	
		$this->db->where('b.category_lang', $this->lang);	
		$result = $this->db->order_by('a.category_order,b.category_name','asc')->get()->result_array();
		return $result;
	}
	
	public function get_category_name($category_id=''){
		$this->db->select('b.category_name')
			->from('category a')
			->join('category_names b', 'a.category_id=b.category_id');
			
		$this->db->where('b.category_lang', $this->lang);	
		$this->db->where('a.category_id', $category_id);	
		$result = $this->db->get()->row_array();
		return !empty($result['category_name']) ? $result['category_name'] : '';
	}
	
	public function get_category_name_by_key($category_key=''){
		$this->db->select('b.category_name')
			->from('category a')
			->join('category_names b', 'a.category_id=b.category_id');
			
		$this->db->where('b.category_lang', $this->lang);	
		$this->db->where('a.category_key', $category_key);	
		$result = $this->db->get()->row_array();
		return !empty($result['category_name']) ? $result['category_name'] : '';
	}
	
	public function get_experience_level(){
		$this->db->select("e.experience_level_key,e.experience_level_id,e_n.experience_level_name")
				->from("experience_level e")
				->join("experience_level_name e_n", "e_n.experience_level_id=e.experience_level_id", "LEFT");
		
		$this->db->where('e.experience_level_status', STATUS_ACTIVE);	
		$this->db->where('e_n.experience_level_lang', $this->lang);	
		$result = $this->db->get()->result_array();
		
		return $result;
		
	}
	
	public function get_sub_category($category_id=''){
		$this->db->select('c.category_subchild_id,c.category_id,c_n.category_subchild_name')
			->from('category_subchild c')
			->join('category_subchild_names c_n', 'c_n.category_subchild_id=c.category_subchild_id', 'LEFT');
			
		
		$this->db->where('c.category_id', $category_id);	
		$this->db->where('c.category_subchild_status', STATUS_ACTIVE);	
		$this->db->where('c_n.category_subchild_lang', $this->lang);	
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	

}
