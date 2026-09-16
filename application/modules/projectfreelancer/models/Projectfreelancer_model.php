<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projectfreelancer_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function getProjects($organization_id,$member_id='',$start = '', $limit = '',$count=FALSE,$where=array()){ 
		$arr=array(
				'select'=>'p.project_id,p.project_url,p.project_title,p.project_posted_date,p.project_expired_date,p.project_status,p_s.is_hourly,p_s.is_fixed,p_s.budget,p_s.is_visible_anyone,b.bid_amount,b.bid_by_project,b.bid_date,b.is_hired',
				'table'=>'project_bids as b',
				'join'=>array(
					array('table'=>'project as p','on'=>'b.project_id=p.project_id','position'=>'left'),
					array('table'=>'project_settings as p_s','on'=>'p.project_id=p_s.project_id','position'=>'left'),
					array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left')
				),
				'where'=>array('p.project_status <>'=>PROJECT_DELETED),
				'order'=>array(array('p.project_id','desc')),
			);
		$arr['where']['b.member_id']=$member_id;
		if($where){
			if(array_key_exists('project_id',$where)){
				//$arr['where']['p.project_id']=$where['project_id'];
			}
		}
		if($count==TRUE){
			$arr['return_count']=TRUE;
			$data=getData($arr);
		}else{
			$arr['limit']=array($limit,$start);
			$data=getData($arr);
		}
        return $data;
    }
    public function getBidder($project_id,$param=array(),$count=FALSE){
		$r=array(
		'select'=>'b.bid_id,b.bid_amount,b.bid_duration,b.bid_date,m.member_id,m.member_fname,m.member_lname,m_b.member_heading,c_n.country_name,c.country_code_short,m_l.logo',
		'table'=>'project_bids b',
		'join'=>array(array('table'=>'member as m','on'=>'b.member_id=m.member_id','position'=>'left'),array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left'),array('table'=>'member_logo as m_l','on'=>'(b.member_id=m_l.member_id and m_l.status=1)','position'=>'left'),array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>'c.country_code=c_n.country_code','position'=>'left')),
		'where'=>array('b.project_id'=>$project_id),
		);
		if($param){
			if(array_key_exists('is_hired',$param)){
				//$r['where']['b.is_hired']=1;
			}	
		}
		if($count){
			$r['return_count']=TRUE;
		}
		return getData($r);
	}
}
