<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Findtalents_model extends CI_Model {
	
	private $lang;
	
    public function __construct() {
		$this->lang = get_active_lang();
        return parent::__construct();
    }
	
	public function getTalentList($srch=array(), $limit=0, $offset=10, $for_list=TRUE){
		$this->load->model('skill_model');
		
		$this->db->select("m.member_id,m.member_name,m.member_username,m.member_register_date,m_b.member_heading,m_b.member_overview,m_b.member_hourly_rate,c_n.country_name,c.country_code_short,m_s.avg_rating,m_s.total_earning,m_s.no_of_reviews,m_s.success_rate")
			->from("member m")
			->join("access_panel a", "m.access_user_id=a.access_user_id", "LEFT")
			->join("member_address m_a", "m_a.member_id=m.member_id", "LEFT")
			->join("country c", "m_a.member_country=c.country_code", "LEFT")
			->join("country_names as c_n", "(c.country_code=c_n.country_code and c_n.country_lang='".$this->lang."')", "LEFT")
			->join("member_basic m_b", "m_b.member_id=m.member_id", "LEFT")
			->join("member_statistics m_s", "m.member_id=m_s.member_id", "LEFT");
		if(!empty($srch['byskillsname'])){
			$this->db->join("member_skills m_sk", "m.member_id=m_sk.member_id", "LEFT");
			$this->db->join("skills s", "m_sk.skill_id=s.skill_id", "LEFT");
			$this->db->where_in("s.skill_key", $srch['byskillsname']);
			$this->db->where("s.skill_status", 1);
		}
			
		$this->db->where("a.login_status", 1);
		$this->db->where("m.is_email_verified", 1);
		$this->db->where("m.is_doc_verified", 1);
		$this->db->where("m.is_employer", 0);
		/* if(!empty($srch['category'])){
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
		 */
		if(!empty($srch['country'])){
			$this->db->where("m_a.member_country", $srch['country']);
		}
		if(!empty($srch['min']) && $srch['min'] > 0){
			$this->db->where('m_b.member_hourly_rate >=', $srch['min']);
		}
		
		if(!empty($srch['max']) && $srch['max'] > 0){
			$this->db->where('m_b.member_hourly_rate <=', $srch['max']);
		}
		
		if(!empty($srch['term'])){
			$this->db->like('m.member_name', $srch['term']);
		}
		
		if($for_list){
			
			if(!empty($srch['order_by'])){
				if($srch['order_by'] == 'default'){
					$this->db->order_by("m.member_id", "DESC");
				}else if($srch['order_by'] == 'latest'){
					$this->db->order_by("m.member_id", "DESC");
				}else if($srch['order_by'] == 'old'){
					$this->db->order_by("m.member_id", "ASC");
				}else if($srch['order_by'] == 'random'){
					$this->db->order_by("RAND()");
				}else if($srch['order_by'] == 'rating'){
					$this->db->order_by("m_s.avg_rating", "DESC");
					$this->db->order_by("m_s.no_of_reviews", "DESC");
				}
			}else{
				$this->db->order_by("m.member_id", "ASC");
			}
		
			$result = $this->db->limit($offset, $limit)->get()->result_array();
			
			if($result){
				foreach($result as $k => $v){
					$result[$k]['user_skill'] = $this->skill_model->getUserSkill($v['member_id']);
					$result[$k]['profile_link'] = URL::get_link('viewprofileURL').'/'.md5($v['member_id']);
					$result[$k]['user_logo'] = getMemberLogo($v['member_id']);
				
					$badge_ids=array();
					$member_badges=getData(array(
						'select'=>'m.badge_id',
						'table'=>'member_badges as m',
						'where'=>array('m.member_id'=>$v['member_id']),
					));
					if($member_badges){
						foreach($member_badges as $b=>$row){
							$badge_ids[]=$row->badge_id;
						}
					}
					$membership_id=getFieldData('membership_id','member_membership','member_id',$v['member_id']);
					if($membership_id){
						$membership_badges=getData(array(
							'select'=>'m.badge_id',
							'table'=>'membership_badge as m',
							'where'=>array('m.membership_id'=>$membership_id),
						));
						if($membership_badges){
							foreach($membership_badges as $b=>$row){
								$badge_ids[]=$row->badge_id;
							}
						}
					}
					$badges=new stdClass();
					if($badge_ids){
						$badges=getData(array(
							'select'=>'b.icon_image,b_n.name,b_n.description',
							'table'=>'badges as b',
							'join'=>array(array('table'=>'badges_names as b_n','on'=>"(b.badge_id=b_n.badge_id and b_n.lang='".get_active_lang()."')",'position'=>'left')),
							'where'=>array('b.status'=>1),
							'where_in'=>array('b.badge_id'=>$badge_ids),
							'order'=>array(array('b.display_order','asc')),
						));
					}
					$result[$k]['badges'] =$badges;
					/* $result[$k]['badges'] =getData(array(
				'select'=>'b.icon_image,b_n.name,b_n.description',
				'table'=>'member_badges as m',
				'join'=>array(array('table'=>'badges as b','on'=>'m.badge_id=b.badge_id','position'=>'left'),array('table'=>'badges_names as b_n','on'=>"(b.badge_id=b_n.badge_id and b_n.lang='".get_active_lang()."')",'position'=>'left')),
				'where'=>array('m.member_id'=>$v['member_id'],'b.status'=>1),
				'order'=>array(array('b.display_order','asc')),
			)); */
				}
			}
			
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		
		return $result;
	}
	
	public function get_all_category($is_featured=false){
		$this->db->select('*')
			->from('category a')
			->join('category_names b', 'a.category_id=b.category_id');
			
		
		$this->db->where('a.category_status', STATUS_ACTIVE);	
		$this->db->where('b.category_lang', $this->lang);	
		if($is_featured){
			$this->db->where('a.is_featured', 1);	
		}
		$result = $this->db->order_by('a.category_order','asc')->get()->result_array();
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
