<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Project_model extends BaseModel{

    public function __construct() {
        return parent::__construct();
    }

	public function list_project($srch_param=array() , $limit=0 , $offset=40 , $for_list=TRUE){
		
		$project = $this->db->dbprefix('projects');
		$user = $this->db->dbprefix('user');
		$this->db->select("$project.project_id,$project.title,$project.description,$project.project_type,$project.buget_min,$project.buget_max,$project.featured,$project.post_date,$project.expiry_date,$project.member_id,$project.status,$project.post_time")->from('projects');
		$this->db->join("user", "$project.member_id = $user.member_id" , "INNER");
		$this->db->join("projects_category", "projects_category.project_id=projects.project_id" , "LEFT");
		$this->db->join("project_skill", "project_skill.project_id=projects.project_id" , "LEFT");
		$this->db->join("skills", "skills.id=project_skill.skill_id" , "LEFT");
		
		$this->db->where("$project.visibility_mode", "Public");
		$this->db->where("$project.published <>",null);
		if(!empty($srch_param['skills'])){
			$skills=array_filter($srch_param['skills']);
			if($skills){
				$this->db->where_in("project_skill.skill_id" , $skills);
			}
		}
		
		if(!empty($srch_param['member_id'])){
			$this->db->where("$project.member_id" , $srch_param['member_id']);
		}
		
		if(!empty($srch_param['category_id'])){
			if(is_array($srch_param['category_id']) && count($srch_param['category_id']) > 0){
				$this->db->where_in("projects_category.category_id", $srch_param['category_id']);
			}
			//$this->db->where("$project.category" , $srch_param['category_id']);
		}
		
		if(!empty($srch_param['sub_catgory_id'])){
			if(is_array($srch_param['sub_catgory_id']) && count($srch_param['sub_catgory_id']) > 0){
				$this->db->where_in("projects_category.sub_category_id", $srch_param['sub_catgory_id']);
			}
			//$this->db->where("$project.sub_category" , $srch_param['sub_catgory_id']);
		}
		
		if(!empty($srch_param['exp_level'])){
			$this->db->where("$project.exp_level" , $srch_param['exp_level']);
		}
		
		if(!empty($srch_param['ccode'])){
			$this->db->where("$user.country" , $srch_param['ccode']);
		}
		
		if(!empty($srch_param['env']) AND $srch_param['env'] != 'All'){
			$this->db->where_in("$project.environment" , array($srch_param['env'],'BOTH'));
		}
		
		if(!empty($srch_param['ptype']) AND $srch_param['ptype'] != 'All'){
			$this->db->where("$project.project_type" , $srch_param['ptype']);
		}
		
		if(!empty($srch_param['featured']) AND $srch_param['featured'] != 'All'){
			$this->db->where("$project.featured" , $srch_param['featured']);
		}
		if(!empty($srch_param['budget'])){
			$budget = explode(',',$srch_param['budget']);
			$srch_param['min'] = $budget[0];
			$srch_param['max'] = $budget[1];
		}
		
		if(!empty($srch_param['min'])){
			$this->db->where("$project.buget_min >=",$srch_param['min']);
		}
		
		if(!empty($srch_param['max'])){
			$this->db->where("$project.buget_max <=" , $srch_param['max']);
		}
		
		if(!empty($srch_param['q']) || !empty($srch_param['term'])){
			$term = !empty($srch_param['q']) ? $srch_param['q'] : $srch_param['term'];
			$term = addslashes($term);
			$this->db->where("($project.title LIKE '%{$term}%' OR $project.description LIKE '%{$term}%')");
		}
		
		if(!empty($srch_param['posted']) AND $srch_param['posted'] != 'All'){
			$newdate=date('Y-m-d',strtotime("-".$srch_param['posted']." day",strtotime(date('Y-m-d'))));
			$this->db->where('post_date >=',$newdate);
		}
		
		$this->db->where(array("$project.status"=>'O',"$project.project_status"=>'Y'));
		$this->db->group_by("$project.project_id");
		if($for_list){
			$this->db->limit($offset , $limit);
			$this->db->order_by("$project.featured" , 'ASC');
			if($srch_param && array_key_exists('sort_by',$srch_param)){
				if($srch_param['sort_by'] == 'latest'){
					$this->db->order_by("$project.id" , "DESC");
				}elseif($srch_param['sort_by'] == 'oldest'){
					$this->db->order_by("$project.id" , "ASC");
				}elseif($srch_param['sort_by'] == 'price_low_to_high'){
					$this->db->order_by("$project.buget_min" , "ASC");
				}elseif($srch_param['sort_by'] == 'price_high_to_low'){
					$this->db->order_by("$project.buget_max" , "DESC");
				}
			}else{
				$this->db->order_by("$project.id" , "DESC");
			}
			$result = $this->db->order_by("$project.id" , "DESC")->get()->result_array();
			if($result){
				foreach($result as $k => $v){
					$result[$k]['owner'] = get_row(array(
						'select' => 'member_id,fname,lname,username,country,city',
						'from' => 'user',
						'where' => array(
							'member_id' => $v['member_id']
						)
					));
					$result[$k]['owner']['logo']=get_user_logo( $v['member_id']);
					$result[$k]['owner']['location'] = array(
						'country' => array(
							'name' => get_country_name($result[$k]['owner']['country']),
							'code' => $result[$k]['owner']['country'],
							'flag' => get_country_flag($result[$k]['owner']['country']),
						),
						'city' => array(
							'name' => get_city_name($result[$k]['owner']['city']),
							'city_id' => $result[$k]['owner']['city'],
						)
					);
					$result[$k]['skills'] = get_results(array(
						'select' => 's.skill_name,s.id',
						'from' => 'project_skill ps',
						'join' => array(
							array('skills s' , 'ps.skill_id = s.id' , 'INNER')
						),
						'offset' => 'all',
						'where' => array('ps.project_id' => $v['project_id'])
					));
					$buget="";
					$buget=CURRENCY."". $result[$k]['buget_min']. " - ".CURRENCY."". $result[$k]['buget_max'];
					$result[$k]['budget']= $buget;
					
					$result[$k]['proposal_count'] = $this->db->where(array('project_id' => $v['project_id']))->count_all_results('bids');
					
				}
			}
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}
	public function getProjectNew($srch=array(), $limit=0, $offset=10, $for_list=TRUE){
		$this->db->select("id,project_id,title,project_type,buget_min,buget_max,post_date,status");
		$this->db->from("projects");
							   
		 
		 if(array_key_exists('member_id', $srch)){
			 $this->db->where("member_id", $srch['member_id']);    
		 }
		 
		 if(array_key_exists('status', $srch)){
			 $this->db->where("status", $srch['status']); 
		 }
		 
		 if(!empty($srch['q'])){
			 $this->db->like("title", $srch['q']); 
		 }
		 
		 if($for_list){
			 $result = $this->db->limit($offset, $limit)->order_by("post_date", "desc")->get()->result_array();
			 /* echo $this->db->last_query(); */
			 foreach($result as $k => $v){
				 $result[$k]['proposal_count'] = $this->db->where(array('project_id' => $v['project_id']))->count_all_results('bids');
			 }
		 }else{
			 $result = $this->db->get()->num_rows();
		 }
		 
		 return $result;
	 }	  
	 public function getSentOffer($srch=array(), $limit=0, $offset=100, $for_list=TRUE){
		$this->db->select('o.*,IF(o.title IS NULL, p.title, o.title) as title,p.project_type')
		->from('offers o')
		->join('projects p', 'p.project_id=o.project_id', 'LEFT');

		if(array_key_exists('member_id', $srch)){
			$this->db->where('o.employer_id',$srch['member_id']);
		}
	
	
		if(!empty($srch['q'])){
			$this->db->where("IF(o.title IS NULL, p.title, o.title) LIKE '%{$srch['q']}%'");
		}
	
		if($for_list){
		$this->db->limit($offset, $limit);
		$this->db->order_by('o.offer_id','desc');
		$result = $this->db->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
	
		return $result;
	}
	public function getContract($srch=array(), $limit=0, $offset=100, $for_list=TRUE){

		$this->db->select('c.*,IF(o.title IS NULL, p.title, o.title) as title,p.project_type,o.offer_amount')
				->from('contract c')
				->join('projects p', 'p.project_id=c.project_id', 'LEFT')
				->join('offers o', 'o.contract_id=c.contract_id', 'LEFT');

		if(array_key_exists('freelancer_id', $srch)){
		   $this->db->where('c.freelancer_id',$srch['freelancer_id']);
		}
		
		if(array_key_exists('employer_id', $srch)){
			$this->db->where('c.employer_id',$srch['employer_id']);
		}

		if(array_key_exists('status', $srch)){
			$this->db->where('c.status',$srch['status']);
		}

		if(!empty($srch['q'])){
		   $this->db->where("IF(o.title IS NULL, p.title, o.title) LIKE '%{$srch['q']}%'");
		}
	   
		if($for_list){
		   $this->db->limit($offset, $limit);
		   $this->db->order_by('c.contract_id','desc');
		   $result = $this->db->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
	   
		return $result;
	   
	}

	public function getProposalsNew($srch=array(), $limit=0, $offset=100, $for_list=TRUE){
		$this->db->select('b.id,b.project_id,b.bidder_id,b.bidder_amt,b.total_amt,b.contract_id,p.status,p.title,p.project_type,b.add_date');
		$this->db->from('bids b');
		$this->db->join('projects p', 'p.project_id=b.project_id', 'LEFT');

	  // $this->db->where(['offer_id' => null, 'contract_id' => null]);

	   if(array_key_exists('member_id', $srch)){
		   $this->db->where('b.bidder_id',$srch['member_id']);
	   }
	  
	   if(!empty($srch['q'])){
		   $this->db->like('p.title',$srch['q']);
	   }
	   
	   if($for_list){
		   $this->db->limit($offset, $limit);
		   $this->db->order_by('b.id','desc');
		   $result = $this->db->get()->result_array();
		   if($result){
			   foreach($result as $k=>$row){
				$bid_lost=0;
				if($row['status'] == 'C' && !$row['contract_id']){
					$bid_lost=1;
				}
				$result[$k]['bid_lost']=$bid_lost;
			   }
		   }
	   }else{
		   $result = $this->db->get()->num_rows();
	   }
	   
	   return $result;	
	}
	
	
	
	
	public function freelancer_project($srch_param=array() , $limit=0 , $offset=40 , $for_list=TRUE){
		$member_id =  $srch_param['member_id'];
		$status = $srch_param['status'];
		
		$project = $this->db->dbprefix('projects');
		$user = $this->db->dbprefix('user');
		
		$this->db->select("$project.id,$project.project_id,$project.title,$project.description,$project.project_type,$project.buget_min,$project.buget_max,$project.featured,$project.post_date,$project.expiry_date,$project.member_id,$project.status,$project.post_time,$project.visibility_mode")->from('projects');
		
		$this->db->where("$project.status",$status);
		
		$this->db->where("FIND_IN_SET('".$member_id."', $project.bidder_id)!=",0);
		
		if($status=='P'){
			$this->db->where("!FIND_IN_SET('$member_id',$project.ended_contractor)!=",0);
			
		}else if($status=='C'){
			$this->db->or_where("FIND_IN_SET('$member_id',$project.ended_contractor)!=",0);	
		}   
		
		if($for_list){
			$result = $this->db->limit($offset , $limit)->order_by("$project.id" , "DESC")->get()->result_array();
			if($result){
				foreach($result as $k => $v){
					$result[$k]['project_owner'] = $this->getUser($v['member_id']);
					
					$result[$k]['skills'] = get_results(array(
						'select' => 's.skill_name,s.id',
						'from' => 'project_skill ps',
						'join' => array(
							array('skills s' , 'ps.skill_id = s.id' , 'INNER')
						),
						'offset' => 'all',
						'where' => array('ps.project_id' => $v['project_id'])
					));
					
					$result[$k]['proposal_count'] = $this->db->where(array('project_id' => $v['project_id']))->count_all_results('bids');
					
				}
			}
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
		
	}
	public function getProposalsOffer($srch=array(), $limit=0, $offset=100, $for_list=TRUE){

		$this->db->select('o.*,IF(o.title IS NULL, p.title, o.title) as title,p.project_type')
				->from('offers o')
				->join('projects p', 'p.project_id=o.project_id', 'LEFT');

		if(array_key_exists('member_id', $srch)){
		   $this->db->where('o.freelancer_id',$srch['member_id']);
		}
	  
	  
		if(!empty($srch['q'])){
			$this->db->where("IF(o.title IS NULL, p.title, o.title) LIKE '%{$srch['q']}%'");
		 }
	   
		if($for_list){
		   $this->db->limit($offset, $limit);
		   $this->db->order_by('o.offer_id','desc');
		   $result = $this->db->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
	   
		return $result;
	   
	}
	public function getMyContestsEntryNew($srch=array(), $limit=0, $offset=100, $for_list=TRUE){
		
		$this->db->select("c.title as contest_title,c.status as contest_status,c_n.*")
			->from('contest c')
			->join('contest_entry c_n', 'c_n.contest_id=c.contest_id');
			
		if(array_key_exists('member_id', $srch)){
			$this->db->where('c_n.member_id', $srch['member_id']);
		}	
		
		if(!empty($srch['q'])){
			$this->db->like('c.title', $srch['q']);
		}
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('c_n.entry_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}	
		
		
		return $result;
	}

	protected function getUser($member_id){
		$result = get_row(array(
			'select' => 'member_id,fname,lname,display_name,country,city',
			'from' => 'user',
			'where' => array(
				'member_id' => $member_id
			)
		));
		
		if($result){
			$result['location'] = array(
				'country' => array(
					'name' => get_country_name($result['country']),
					'code' => $result['country'],
					'flag' => get_country_flag($result['country']),
				),
				'city' => array(
					'name' => get_city_name($result['city']),
					'city_id' => $result['city'],
				)
			);
		}
		
		
		return $result;
	}

	
}