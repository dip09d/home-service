<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Skill_model extends CI_Model {
	
	var $ci;
	
    public function __construct() {
	   $this->ci =& get_instance();
        return parent::__construct();
    }
	
	public function getProjectSkill($project_id=''){
		$default_lang = get_active_lang();
		$this->ci->db->select("p_s.skill_id,s_n.skill_name")
				->from("project_skills p_s")
				->join("skill_names s_n", "s_n.skill_id=p_s.skill_id AND s_n.skill_lang='$default_lang'", "LEFT");
		
		$this->ci->db->where("p_s.project_id", $project_id);
		$result = $this->ci->db->get()->result_array();
		return $result;
	}
	
	public function getUserSkill($user_id=''){
		$default_lang = get_active_lang();
		$this->ci->db->select("m_s.skill_id,s_n.skill_name")
				->from("member_skills m_s")
				->join("skill_names s_n", "s_n.skill_id=m_s.skill_id AND s_n.skill_lang='$default_lang'", "LEFT");
		
		$this->ci->db->where("m_s.member_id", $user_id);
		$this->db->order_by("m_s.member_skills_order", "ASC");
		$result = $this->ci->db->get()->result_array();
		return $result;
	}
	public function getPolularSkillsList($srch=array(), $limit=0, $offset=10, $for_list=TRUE){
		$default_lang = get_active_lang();
		$this->ci->db->select("m_s.skill_id,s_n.skill_name,s.skill_key,count(m_s.skill_id) as total_freelancer")
				->from("skills s")
				->join("skill_names s_n", "s.skill_id=s_n.skill_id AND s_n.skill_lang='$default_lang'", "LEFT")
				->join("member_skills m_s", "s.skill_id=m_s.skill_id", "LEFT");
		$this->db->where(array('s.skill_status'=>'1'));
		$this->db->group_by('s.skill_id');
		$this->db->order_by('total_freelancer','desc');
		$this->db->order_by('s_n.skill_name','asc');
		if($for_list){
			$result = $this->db->limit($offset, $limit)->get()->result();
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}



	
}
