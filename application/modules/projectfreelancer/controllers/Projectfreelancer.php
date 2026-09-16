<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projectfreelancer extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='C';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->organization_id=$this->loggedUser['OID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}else{
			redirect(URL::get_link('loginURL').'?ref=dashboardURL');
		}	
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();	
		parent::__construct();
	}
	public function index()
	{
		redirect(get_link('dashboardURL'));
	}
	public function bids()
	{
		if($this->access_member_type=='C'){
			redirect(get_link('dashboardURL'));
		}
		
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			$where=array();
			$this->load->model('projectfreelancer_model');
			$all_projects_total=$this->projectfreelancer_model->getProjects($organization_id,$member_id,'','',true,$where);
			$this->data['max_page']=ceil($all_projects_total/10);
			$this->data['left_panel']=load_view('inc/freelancer-setting-left','',TRUE);
			$this->layout->view('all-bids-project', $this->data);
		}	
	}
	public function load_project_bid()
	{
		if($this->access_member_type=='C'){
			redirect(get_link('dashboardURL'));
		}
		$this->load->model('projectfreelancer_model');
		
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			$limit=10;
			$start=(post('page')? post('page'):0)*$limit;
			$maxcount=$start+$limit;
			$where=array();
			$this->data['all_projects']=$this->projectfreelancer_model->getProjects($organization_id,$member_id,$start,$limit,'',$where);
			if($this->data['all_projects']){
				foreach($this->data['all_projects'] as $i=>$projectL){
					$this->data['all_projects'][$i]->bids=getBids($projectL->project_id,array(),true);
					$this->data['all_projects'][$i]->hired=getBids($projectL->project_id,array('is_hired'=>TRUE),true);
					$this->data['all_projects'][$i]->message=0;
				}
				$this->layout->view('ajax-project-list-display', $this->data,true);
			}
			
		}
	}
	
	public function load_proposal_count($project_id=''){
		$res=array();
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;	
			if($organization_id){
				$data['projects']=getProjectDetails($project_id,array('project_owner'));
				if($data['projects']['project_owner']->organization_id==$organization_id){
					$res['status']='OK';
					$res['project']['total_proposal']=getBids($project_id,array('only_active'=>TRUE),true);
					$res['project']['archive_proposal']=getBids($project_id,array('is_archive'=>1),true);
					$res['project']['shortlisted_proposal']=getBids($project_id,array('is_shortlisted'=>1),true);
					$res['project']['interview_proposal']=getBids($project_id,array('is_interview'=>1),true);
					$res['project']['invite_proposal']=0;
					$res['project']['hired_proposal']=getBids($project_id,array('is_hired'=>1),true);
				}else{
					$res['status']='FAIL';
				}
			}
		}
		echo json_encode($res);
	}
	public function load_propasal($project_id='')
	{	$type=post('type');
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;		
			if($member_id){
				$this->data['projects']=getProjectDetails($project_id,array('project_owner','project_settings'));
				if($this->data['projects']['project_owner']->organization_id==$organization_id){
					$this->data['projects']['project_id']=$project_id;
					$this->data['all_data']=getBidsListDetails($project_id,array('is_'.post('type')=>1),FALSE);
					if($this->data['all_data']){
						foreach($this->data['all_data'] as $k=>$bids){
							$this->data['all_data'][$k]->country_info=getAllCountry(array('country_code'=>$bids->member_country));
							$this->data['all_data'][$k]->totalearn=0;
							$this->data['all_data'][$k]->avg_review=0;
						}
					}
					$this->data['req_type']=$type;
					$this->layout->view('ajax-proposal-list-display', $this->data,true);
				}
				
			}
		}
	}
	public function update_propasal(){
		$res=array();
		$project_id=post('project_id');
		$application_id=post('application_id');
		$formtype=post('formtype');
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;	
			if($member_id){
				$data['projects']=getProjectDetails($project_id,array('project_owner'));
				if($data['projects']['project_owner']->organization_id==$organization_id){
					if($formtype=='shortlist'){
						$up=updateTable('project_bids',array('is_shortlisted'=>1,'is_archive'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='unarchive'){
						$up=updateTable('project_bids',array('is_archive'=>NULL,'is_shortlisted'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='archive'){
						$up=updateTable('project_bids',array('is_archive'=>1,'is_shortlisted'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='interview'){
						$up=updateTable('project_bids',array('is_archive'=>NULL,'is_shortlisted'=>NULL,'is_interview'=>1),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}
						if($up){
							$status = 'OK';
						}else{
							$status = 'FAIL';
						}
					
				}
			}
		}
		echo 1;
	}
	
}
