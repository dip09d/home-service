<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projectclient extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='F';
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
		redirect(get_link('myprojectrecentClientURL'));
	}
	public function all()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('dashboardURL'));
		}
		$this->layout->set_js(array(
				'mycustom.js',
			));
		if($this->loggedUser){
			$this->data['left_panel']=load_view('inc/client-setting-left','',TRUE);
			$this->layout->view('all-post-project', $this->data);
		}	
	}
	public function load_project()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		$this->load->model('projectclient_model');
		
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			$limit=10;
			$start=0;
			$project_total=0;
			if(post('page')){
				$start=(post('page')-1)*$limit;
			}
			
			$where=array();
			$this->data['all_projects']=$this->projectclient_model->getProjects($organization_id,$member_id,$start,$limit,'',$where);
			if($this->data['all_projects']){
				foreach($this->data['all_projects'] as $i=>$projectL){
					$this->data['all_projects'][$i]->bids=getBids($projectL->project_id,array(),true);
					$this->data['all_projects'][$i]->hired=getBids($projectL->project_id,array('is_hired'=>TRUE),true);
					$this->data['all_projects'][$i]->message=0;
				}
				$project_total=$this->projectclient_model->getProjects($organization_id,$member_id,'','',TRUE,$where);
			}
			$this->data['list']=$this->layout->view('ajax-project-list-display',$this->data, TRUE, TRUE);
			$this->data['total_page']=ceil($project_total/$limit);	

		}
		//print_r($this->data);
		echo json_encode($this->data);
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
					$res['project']['invite_proposal']=$this->db->where('project_id',$project_id)->from('project_bid_invitation')->count_all_results();
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
					if($type=='invite'){
						$this->data['all_data']=new stdClass();
					}else{
					
						$this->data['all_data']=getBidsListDetails($project_id,array('is_'.post('type')=>1),FALSE);
						if($this->data['all_data']){
							foreach($this->data['all_data'] as $k=>$bids){
								$this->data['all_data'][$k]->country_info=getAllCountry(array('country_code'=>$bids->member_country));
								/* $this->data['all_data'][$k]->totalearn=0;
								$this->data['all_data'][$k]->avg_review=0;
								$this->data['all_data'][$k]->success_rate=0; */
							}
						}
							
					}
					$this->data['req_type']=$type;
					$this->layout->view('ajax-proposal-list-display', $this->data,true);
				}
				
			}
		}
	}
	public function invitetoproject(){
		checkrequestajax();
		$project_id=get('project_id');
		$member_id=$this->member_id;	
		$form_type=get('formtype');
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
					
					$this->data['req_type']=$form_type;
					$this->layout->view('ajax-proposal-invite', $this->data,true);
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
			$msg=array();
			$i=0;
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;	
			if($member_id){
				if(!$project_id){
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'project_id';
					$msg['errors'][$i]['message'] = 'Select project';
					$i++;
					unset($_POST);
					echo json_encode($msg);
					die();
				}
				$data['projects']=getProjectDetails($project_id,array('project_owner','project'));
				if($data['projects']['project_owner']->organization_id==$organization_id){
					$SENDER_NAME=$data['projects']['project_owner']->organization_name;
					$TITLE=$data['projects']['project']->project_title;
					$URL=get_link('myProjectDetailsURL').'/'.$data['projects']['project']->project_url;
					$notification_URL=$this->config->item('myProjectDetailsURL').'/'.$data['projects']['project']->project_url;
					if($formtype=='shortlist'){
						$up=updateTable('project_bids',array('is_shortlisted'=>1,'is_archive'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='unarchive'){
						$up=updateTable('project_bids',array('is_archive'=>NULL,'is_shortlisted'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='archive'){
						$up=updateTable('project_bids',array('is_archive'=>1,'is_shortlisted'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='interview'){
						$up=updateTable('project_bids',array('is_archive'=>NULL,'is_shortlisted'=>NULL,'is_interview'=>1),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='hire_freelancer'){
						$enc_member_id=post('fid');
						if(!$enc_member_id){
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'project_id';
							$msg['errors'][$i]['message'] = 'Invalid freelancer';
							$i++;
						}else{
							$freelancer=$this->db->select('m.member_id,m.member_name,m.member_email')->where('m.is_employer',0)->where('md5(m.member_id)',$enc_member_id)->from('member as m')->group_by('m.member_id')->get()->row();
							if($freelancer){
								$msg['status'] = 'OK';
								$msg['redirect_url'] = get_link('HireProjectURL').'/'.md5($project_id).'/'.$enc_member_id;
							}else{
								$msg['status'] = 'FAIL';
								$msg['errors'][$i]['id'] = 'project_id';
								$msg['errors'][$i]['message'] = 'Invalid freelancer';
								$i++;
							}
						}
						unset($_POST);
						echo json_encode($msg);
						die();

					}elseif($formtype=='invite_freelancer'){
						$enc_member_id=post('fid');
						if(!$enc_member_id){
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'project_id';
							$msg['errors'][$i]['message'] = 'Invalid freelancer';
							$i++;
						}else{
							$freelancer=$this->db->select('m.member_id,m.member_name,m.member_email')->where('m.is_employer',0)->where('md5(m.member_id)',$enc_member_id)->from('member as m')->group_by('m.member_id')->get()->row();
							if($freelancer){
								$template="project-invitation";
								$RECEIVER_NAME=$freelancer->member_name;
								$to=$freelancer->member_email;
								$data_parse = array(
									'SENDER_NAME' =>$SENDER_NAME,
									'RECEIVER_NAME' =>$RECEIVER_NAME,
									'TITLE' =>$TITLE,
									'URL' =>$URL,
								);
								$this->notification_model->log(
									$template, // template key
									$data_parse, // template data
									$notification_URL, // link (without base_url)
									$freelancer->member_id, // notification to,
									$this->member_id // notification_from
								);
								$data_parse = array(
									'SENDER_NAME' =>$SENDER_NAME,
									'RECEIVER_NAME' =>$RECEIVER_NAME,
									'TITLE' =>$TITLE,
									'URL' =>$URL,
								);
								SendMail($to,$template,$data_parse);
								$project_bid_invitation=array(
									'project_id'=>$project_id,
									'invite_email'=>$to,
									'user_id'=>$freelancer->member_id,
									'invite_date'=>date('Y-m-d H:i:s'),
								);
								insert_record('project_bid_invitation',$project_bid_invitation);
								$msg['status'] = 'OK';


							}else{
								$msg['status'] = 'FAIL';
								$msg['errors'][$i]['id'] = 'project_id';
								$msg['errors'][$i]['message'] = 'Invalid freelancer';
								$i++;
							}
						}
						unset($_POST);
						echo json_encode($msg);
						die();

					}elseif($formtype=='invite'){
						$inviteemails=$this->input->post('inviteemails');
						if($inviteemails){
							if(count($inviteemails)>100){
								$msg['status'] = 'FAIL';
				    			$msg['errors'][$i]['id'] = 'emails';
								$msg['errors'][$i]['message'] = 'Maximum limit 100';
				   				$i++;
							}
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'emails';
							$msg['errors'][$i]['message'] = 'Please enter  email id';
							$i++;
						}
						if($i==0){
							$template="project-invitation";
							$existingfreelancer=array();
							$allfreelancer=$this->db->select('m.member_id,m.member_name,m.member_email')->where('m.is_employer',0)->where_in('m.member_email',$inviteemails)->from('member as m')->group_by('m.member_id')->get()->result();
							if($allfreelancer){
								foreach($allfreelancer as $f=>$freelancer){
									$RECEIVER_NAME=$freelancer->member_name;
									$data_parse = array(
										'SENDER_NAME' =>$SENDER_NAME,
										'RECEIVER_NAME' =>$RECEIVER_NAME,
										'TITLE' =>$TITLE,
										'URL' =>$URL,
									);
									$this->notification_model->log(
										$template, // template key
										$data_parse, // template data
										$notification_URL, // link (without base_url)
										$freelancer->member_id, // notification to,
										$this->member_id // notification_from
									);
									$existingfreelancer[$freelancer->member_email]=$freelancer;
								}
							}
							//print_r($allfreelancer);
							foreach($inviteemails as $to){
								$RECEIVER_NAME='Guest';
								$user_id=NULL;
								if(array_key_exists($to,$existingfreelancer)){
									$user_id=$existingfreelancer[$to]->member_id;
									$RECEIVER_NAME=$existingfreelancer[$to]->member_name;
								}
								$data_parse = array(
									'SENDER_NAME' =>$SENDER_NAME,
									'RECEIVER_NAME' =>$RECEIVER_NAME,
									'TITLE' =>$TITLE,
									'URL' =>$URL,
								);
								SendMail($to,$template,$data_parse);
								$project_bid_invitation=array(
									'project_id'=>$project_id,
									'invite_email'=>$to,
									'user_id'=>$user_id,
									'invite_date'=>date('Y-m-d H:i:s'),
								);
								insert_record('project_bid_invitation',$project_bid_invitation);
							}
							$msg['status'] = 'OK';
						}
						unset($_POST);
						echo json_encode($msg);
						die();
					}else{
						$up=0;	
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
	public function hireinviteform(){
		checkrequestajax();
		$member_id=$this->member_id;	
		$form_type=get('formtype');
		$enc_member_id=get('mid');
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		if($this->loggedUser){
			$this->load->model('projectclient_model');
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;		
			if($member_id){
				$start=$limit=0;
				$where=array('status'=>PROJECT_OPEN);
				$limit=$this->projectclient_model->getProjects($organization_id,$member_id,$start,$limit,true,$where);
				$this->data['all_projects']=$this->projectclient_model->getProjects($organization_id,$member_id,$start,$limit,'',$where);
				$this->data['req_type']=$form_type;
				$this->data['fid']=$enc_member_id;
				if($form_type=='hire'){
					$this->layout->view('ajax-project-hire', $this->data,true);
				}elseif($form_type=='invite'){
					$this->layout->view('ajax-project-invite', $this->data,true);
				}else{
					show_404();
				}
				
				
			}
		}
	}
	
}
