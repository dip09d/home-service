<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Favorite_model extends MX_Controller {
	private $lang;
	function __construct()
	{
			$this->lang = get_active_lang();
			parent::__construct();
	}
	
	public function getfavoriteFreelancers($srch=array(), $limit=0, $offset=20, $for_list=TRUE){ 
	
	 	$this->db->select('m.member_id,m.member_name,m.member_username,m.member_register_date,m_b.member_heading,m_b.member_overview,m_b.member_hourly_rate,c_n.country_name,c.country_code_short,m_s.avg_rating,m_s.total_earning,m_s.no_of_reviews');		 
		$this->db->from('favorite_member as f');
		$this->db->join('member as m','f.favorite_member_id=m.member_id','left')
			->join("member_address m_a", "m_a.member_id=m.member_id", "LEFT")
			->join("country c", "m_a.member_country=c.country_code", "LEFT")
			->join("country_names as c_n", "(c.country_code=c_n.country_code and c_n.country_lang='".$this->lang."')", "LEFT")
			->join("member_basic m_b", "m_b.member_id=m.member_id", "LEFT")
			->join("member_statistics m_s", "m.member_id=m_s.member_id", "LEFT");


		if($srch){
		 	if(array_key_exists('member_id',$srch)){
				$this->db->where('f.member_id',$srch['member_id']);
			}
		}

		
		$this->db->group_by('f.favorite_member_id');
		$this->db->order_by('f.reg_date','desc');

		if($for_list){
			$result = $this->db->limit($offset, $limit)->get()->result();
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}
	public function getfavoriteProjects($srch=array(), $limit=0, $offset=20, $for_list=TRUE){ 
	
		$this->db->select('p.project_id,p.project_url,p.project_title,p.project_short_info,p.project_posted_date,p_s.is_hourly,p_s.is_fixed,p_s.budget,p_s.project_type_code,e_l.experience_level_key,e_l_n.experience_level_name,p_s.hourly_duration,p_s.hourly_time_required,c_n.category_name,p_c.category_id');		 
	   $this->db->from('favorite_project as f');
	   $this->db->join("project p",'f.project_id=p.project_id','left')
	   ->join("project_settings p_s", "p_s.project_id=p.project_id", "LEFT")
	   ->join("experience_level as e_l", "p_s.experience_level=e_l.experience_level_id", "LEFT")
	   ->join("experience_level_name as e_l_n", "(e_l.experience_level_id=e_l_n.experience_level_id and e_l_n.experience_level_lang='".$this->lang."')", "LEFT")
	   ->join("project_category p_c", "p_c.project_id=p.project_id", "LEFT")
	   ->join("category_names as c_n", "(p_c.category_id=c_n.category_id and c_n.category_lang='".$this->lang."')", "LEFT");


	   if($srch){
			if(array_key_exists('member_id',$srch)){
			   $this->db->where('f.member_id',$srch['member_id']);
		   }
	   }

	   
	   $this->db->group_by('f.project_id');
	   $this->db->order_by('f.reg_date','desc');

	   if($for_list){
		   $result = $this->db->limit($offset, $limit)->get()->result();
	   }else{
		   $result = $this->db->get()->num_rows();
	   }
	   return $result;
   }
}
