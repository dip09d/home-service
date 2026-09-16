<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews_model extends MX_Controller {
	private $lang;
	function __construct()
	{
			$this->lang = get_active_lang();
			parent::__construct();
	}
	
	public function getreviews($srch=array(), $limit=0, $offset=20, $for_list=TRUE){ 
	
	 	$this->db->select('c_r.review_id,p.project_id,p.project_title,p.project_url,c_r.for_skills,c_r.for_quality,c_r.for_availability,c_r.for_deadlines,c_r.for_communication,c_r.for_cooperation,c_r.average_review,c_r.review_comments,c_r.review_date,m.member_id,m.member_name');		 
		$this->db->from('contract_reviews as c_r');
		$this->db->join('project as p','c_r.project_id=p.project_id','left')
		->join('member as m','c_r.review_by=m.member_id','left');


		if($srch){
		 	if(array_key_exists('member_id',$srch)){
				$this->db->where('c_r.review_to',$srch['member_id']);
			}
		}
		$this->db->where('c_r.review_status',1);
		$this->db->where('c_r.is_display_public',1);
		$this->db->order_by('c_r.review_id','desc');

		if($for_list){
			$result = $this->db->limit($offset, $limit)->get()->result();
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}

}
