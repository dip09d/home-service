<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model{
	
	
	public function __construct(){
        return parent::__construct();
	}
	public function getReview($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('c_r.review_id,c_r.contract_id,p_c.contract_title,p.project_id,p.project_title,p.project_url,c_r.for_skills,c_r.for_quality,c_r.for_availability,c_r.for_deadlines,c_r.for_communication,c_r.for_cooperation,c_r.average_review,c_r.review_comments,c_r.review_date,m.member_id as sender_id,m.member_name as review_from,m_t.member_id as receiver_id,m_t.member_name as review_to,c_r.review_status')
			->from('contract_reviews c_r')
			->join('project as p','c_r.project_id=p.project_id','left')
			->join('project_contract as p_c','c_r.contract_id=p_c.contract_id','left')
			->join('member as m','c_r.review_by=m.member_id','left')
			->join('member as m_t','c_r.review_to=m_t.member_id','left');
			
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('c_r.review_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	
	
	
}


