<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projectview extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->organization_id=$this->loggedUser['OID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
			parent::__construct();
	}
	public function index()
	{
		redirect(get_link('dashboardURL'));
	}
	public function view($project_url='')
	{
		$project_url = urldecode($project_url);
		$member_id="";
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
				'bootbox_custom.js',
			));
		$is_owner=FALSE;
		$login_user_id=0;
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			$login_user_id=$this->member_id;
		}
		$this->data['login_user_id']=$login_user_id;
		$arr=array(
				'select'=>'p.project_id,p.project_title,p.project_posted_date,p.project_expired_date,p.project_status,p_s.is_hourly,p_s.is_fixed,p_s.budget,p_s.is_visible_anyone',
				'table'=>'project as p',
				'join'=>array(
					array('table'=>'project_settings as p_s','on'=>'p.project_id=p_s.project_id','position'=>'left'),
					array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left')
				),
				'where'=>array('p.project_status <>'=>PROJECT_DELETED,'p.project_url'=>$project_url),
				'single_row'=>true,
			);
		$ProjectDataBasic=getData($arr);
		if($ProjectDataBasic){
			$project_id=$ProjectDataBasic->project_id;
			$this->data['projectData']=getProjectDetails($project_id);
			if($this->data['projectData'] && $this->data['projectData']['project_owner']){
				
				if($this->loggedUser){
					if($this->data['projectData']['project_owner']->organization_id && $this->data['projectData']['project_owner']->organization_id==$organization_id){
						$is_owner=TRUE;
					}elseif($this->data['projectData']['project_owner']->member_id==$member_id){
						$is_owner=TRUE;
					}
				}
				
				if($this->data['projectData']['project_owner']->organization_id){
					$memberData=getData(array(
						'select'=>'o.organization_name,o.organization_register_date,o_a.organization_timezone,o_a.organization_city,o_a.organization_state,c_n.country_name,o.is_payment_verified',
						'table'=>'organization as o',
						'join'=>array(
							array('table'=>'organization_address as o_a','on'=>'o.organization_id=o_a.organization_id','position'=>'left'),
							array('table'=>'country as c','on'=>'o_a.organization_country=c.country_code','position'=>'left'),
							array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
						),
						'where'=>array('o.organization_id'=>$this->data['projectData']['project_owner']->organization_id),
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
					$client_payment_verify=$memberData->is_payment_verified;
					
					$client_member_since=$memberData->organization_register_date;
					
				}else{
					$memberData=getData(array(
						'select'=>'m.member_name,m.member_register_date,m_a.member_timezone,m_a.member_city,m_a.member_state,c_n.country_name',
						'table'=>'member as m',
						'join'=>array(
							array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),
							array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),
							array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
						),
						'where'=>array('m.member_id'=>$data['projectData']['project_owner']->member_id),
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
					$client_payment_verify="0";
			
					$client_member_since=$memberData->member_register_date;
					
				}
			}
			$client_total_payment="0";
			$client_review_rating=array('rating'=>'0','review'=>0);
			
			$total_project=getData(array(
				'select'=>'p.project_id',
				'table'=>'project as p',
				'join'=>array(
					array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left'),
				),
				'where'=>array('p_o.member_id'=>$this->data['projectData']['project_owner']->member_id),
				'where_in'=>array('p.project_status'=>array(PROJECT_OPEN,PROJECT_HIRED,PROJECT_CLOSED)),
				'return_count'=>TRUE
			));
			
			$total_hired=getData(array(
				'select'=>'p.project_id',
				'table'=>'project as p',
				'join'=>array(
					array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left'),
					array('table'=>'project_bids as p_b','on'=>'p.project_id=p_b.project_id','position'=>'left'),
				),
				'where'=>array('p_o.member_id'=>$this->data['projectData']['project_owner']->member_id,'p_b.is_hired'=>1),
				'return_count'=>TRUE
			));
			
			$client_project_info=array('total_project'=>$total_project,'total_hired'=>$total_hired,'total_active'=>0);
			$memberDatacount=getData(array(
				'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
				'table'=>'member_statistics as m_s',
				'where'=>array('m_s.member_id'=>$this->data['projectData']['project_owner']->member_id),
				'single_row'=>TRUE
			));
			if($memberDatacount){
				$client_review_rating=array('rating'=>$memberDatacount->avg_rating,'review'=>$memberDatacount->no_of_reviews);
				$client_total_payment=displayamount($memberDatacount->total_spent,2);
			}
			
			$this->data['projectData']['clientInfo']=array(
			'client_name'=>$client_name,
			'client_address'=>$client_address,
			'client_payment_verify'=>$client_payment_verify,
			'client_total_payment'=>$client_total_payment,
			'client_member_since'=>$client_member_since,
			'client_review_rating'=>$client_review_rating,
			'client_project_info'=>$client_project_info,
			);
			$this->data['projectData']['proposal']=array(
			'total_proposal'=>getBids($project_id,array(),true),
			'total_invite'=>getBids($project_id,array('is_invite'=>TRUE),true),
			'total_interview'=>getBids($project_id,array('is_interview'=>TRUE),true),
			'total_hires'=>getBids($project_id,array('is_hired'=>TRUE),true),
			);
			$this->data['is_owner']=$is_owner;
			$display_tabs=array();
			if($this->data['projectData']['proposal']['total_proposal']>0){
				if($is_owner){
					$display_tabs[]='show_details';
					$display_tabs[]='show_application';
				}
			}
			$this->data['display_tabs']=$display_tabs;
			
			$is_already_bid=array();
			if($this->loggedUser){
			$is_already_bid=getData(array(
			'select'=>'bid_id,is_hired',
			'table'=>'project_bids',
			'where'=>array('project_id'=>$project_id,'member_id'=>$this->member_id),
			'single_row'=>TRUE
			));
			}
			$this->data['is_already_bid']=$is_already_bid;		
			$this->layout->view('view-project', $this->data);
		}else{
			show_404();
		}
		
	}
	public function applications($project_id){
		if($this->loggedUser){
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
				'bootbox_custom.js',
				'bootstrap-tagsinput.min.js',
			));
		$this->layout->set_css(array(
				'bootstrap-tagsinput.css',
			));
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;	
			$this->data['projectData']=getProjectDetails($project_id,array('project','project_owner','project_settings','project_category'));
			if($this->data['projectData']['project_owner']->organization_id==$organization_id){

			}else{
				show_403();
			}
			$this->layout->view('project-bid-list', $this->data);
		}else{
			redirect(URL::get_link('loginURL').'?ref=dashboardURL');
		}
		
	}
	public function apply($project_url='')
	{
		$project_url = urldecode($project_url);
		$member_id="";
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
				'bootbox_custom.js',
				'upload-drag-file.js',
				'moment-with-locales.js',
				'bootstrap-datetimepicker.min.js'
			));
		$this->layout->set_css(array(
		'bootstrap-datetimepicker.css'
		));
		$is_owner=FALSE;
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
		}else{
			redirect(URL::get_link('loginURL').'?reffer='.$this->config->item('myProjectDetailsURL')."/".$project_url);
		}
		
		$arr=array(
				'select'=>'p.project_id,p.project_title,p.project_posted_date,p.project_expired_date,p.project_status,p_s.is_hourly,p_s.is_fixed,p_s.budget,p_s.is_visible_anyone',
				'table'=>'project as p',
				'join'=>array(
					array('table'=>'project_settings as p_s','on'=>'p.project_id=p_s.project_id','position'=>'left'),
					array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left')
				),
				'where'=>array('p.project_status <>'=>PROJECT_DELETED,'p.project_url'=>$project_url),
				'single_row'=>true,
			);
		$ProjectDataBasic=getData($arr);
		if($ProjectDataBasic){
			$project_id=$ProjectDataBasic->project_id;
			$this->data['projectData']=getProjectDetails($project_id,array('project','project_category','project_question','project_settings','project_owner','project_additional'));
			if($this->data['projectData'] && $this->data['projectData']['project_owner']){
				if($this->loggedUser){
					if($this->data['projectData']['project_owner']->organization_id && $this->data['projectData']['project_owner']->organization_id==$organization_id){
						$is_owner=TRUE;
					}elseif($this->data['projectData']['project_owner']->member_id==$member_id){
						$is_owner=TRUE;
					}
				}
			}
			if($is_owner){
				redirect(URL::get_link('myProjectDetailsURL').'/'.$project_url);
			}else{
				$is_email_verified=getFieldData('is_email_verified','member','member_id',$this->member_id);
				if($is_email_verified){
					$is_doc_verified=getFieldData('is_doc_verified','member','member_id',$this->member_id);
					if(!$is_doc_verified){
						$this->session->set_flashdata('not_verified','doc');
						redirect(URL::get_link('myProjectDetailsURL').'/'.$project_url);
					}
				}else{
					$this->session->set_flashdata('not_verified','email');
					redirect(URL::get_link('myProjectDetailsURL').'/'.$project_url);
				}
				
			}
			$this->data['bidduration']=getAllBidDuration();
			$this->data['bid_site_fee']=getSiteCommissionFee($member_id);
			
			$bid_id=NULL;
			$this->data['getBidDetails']=getData(array(
					'select'=>'bid_id,bid_amount,bid_site_fee,bid_by_project,bid_duration,bid_details,bid_attachment,is_hired',
					'table'=>'project_bids',
					'where'=>array('project_id'=>$project_id,'member_id'=>$member_id),
					'single_row'=>TRUE
					));
			if($this->data['getBidDetails']){
				if($this->data['getBidDetails']->is_hired){
					redirect(URL::get_link('myProjectDetailsURL').'/'.$project_url);
				}
				$bid_id=$this->data['getBidDetails']->bid_id;
				if($this->data['getBidDetails']->bid_by_project!=1){
				$arr=array(
						'select'=>'p_b_m.bid_milestone_id,p_b_m.bid_milestone_title,p_b_m.bid_milestone_due_date,p_b_m.bid_milestone_amount',
						'table'=>'project_bid_milestones as p_b_m',
						'where'=>array('p_b_m.bid_id'=>$bid_id),
					);
				$this->data['getBidDetails']->milestone=getData($arr);
				}
			}
			$arr=array(
				'select'=>'q.question_id,q.question_title,a.question_answer',
				'table'=>'project_question as p_q',
				'join'=>array(
					array('table'=>'question as q','on'=>'p_q.question_id=q.question_id','position'=>'left'),
					array('table'=>'project_bid_answer as a','on'=>"(q.question_id=a.question_id and a.bid_id='".$bid_id."')",'position'=>'left'),
				),
				'where'=>array('p_q.project_id'=>$project_id,'p_q.project_question_status'=>1),
				'order'=>array(array('p_q.project_question_id','asc'))
			);
			
			$this->data['limit_over']=1;
			$membership=getMembershipData($member_id,array('bid'));
			if($membership['max_bid'] > $membership['used_bid']){
				$this->data['limit_over']=0;
			}
			$this->data['project_question']=getData($arr);
			$this->layout->view('apply-project', $this->data);
		}else{
			show_404();
		}
		
	}
	public function apply_project_form_check(){
		$this->load->library('form_validation');
		checkrequestajax();
		if($this->access_member_type!='F'){
			redirect(get_link('dashboardURL'));
		}
		$i=0;
		$msg=array();
		if($this->loggedUser){
			$is_email_verified=getFieldData('is_email_verified','member','member_id',$this->member_id);
			if($is_email_verified){
				$is_doc_verified=getFieldData('is_doc_verified','member','member_id',$this->member_id);
				if(!$is_doc_verified){
					$this->session->set_flashdata('not_verified','doc');
					redirect(get_link('dashboardURL'));
				}
			}else{
				$this->session->set_flashdata('not_verified','email');
				redirect(get_link('dashboardURL'));
			}
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			$pid=post('pid');
			$is_edited=0;
			$projectDetails=getProjectDetails($pid,array('project','project_owner'));
			if($projectDetails){
				if($this->input->post()){
					$project_is_cover_required=getFieldData('project_is_cover_required','project_additional','project_id',$pid);
					$is_hourly=getFieldData('is_hourly','project_settings','project_id',$pid);
					$all_milestone=array();
					$getBidDetails=getData(array(
					'select'=>'bid_id,is_hired',
					'table'=>'project_bids',
					'where'=>array('project_id'=>$pid,'member_id'=>$member_id),
					'single_row'=>TRUE
					));
					if($getBidDetails){
						$is_edited=1;
						$bid_id=$getBidDetails->bid_id;
						if($getBidDetails->is_hired){
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'error';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
							unset($_POST);
							echo json_encode($msg);
							die;
						}
					}
					$this->form_validation->set_rules('pid', 'pid', 'required|trim|xss_clean|is_numeric');
					if($is_hourly==1){
						$this->form_validation->set_rules('bid_amount', 'amount', 'required|trim|xss_clean|is_numeric');
					}else{
						$this->form_validation->set_rules('bid_by_project', 'bid_by_project', 'required|trim|xss_clean');
						if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
							
						}else{
							$this->form_validation->set_rules('milestone_id[]', 'milestone_id', 'required|trim|xss_clean');
							if($this->input->post('milestone_id[]')){
								$all_milestone_count=post('milestone_id');
								foreach($all_milestone_count as $mid){
									$this->form_validation->set_rules('milestone_title_'.$mid, 'title', 'required|trim|xss_clean');
									//$this->form_validation->set_rules('milestone_due_date_'.$mid, 'due date', 'required|trim|xss_clean');
									$this->form_validation->set_rules('milestone_amount_'.$mid, 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
									$milestone=array(
									'title'=>post('milestone_title_'.$mid),
									'due_date'=>post('milestone_due_date_'.$mid),
									'amount'=>post('milestone_amount_'.$mid),
									);
									$all_milestone[]=$milestone;
								}	
								
							}
						}
						if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
							$this->form_validation->set_rules('bid_amount', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
						}
						$this->form_validation->set_rules('bid_duration', 'duration', 'required|trim|xss_clean');
					}
					
					
					if($project_is_cover_required){
						$this->form_validation->set_rules('bid_details', 'details', 'required|trim|xss_clean');	
					}
					if ($this->form_validation->run() == FALSE){
						$error=validation_errors_array();
						if($error){
							foreach($error as $key=>$val){
								$msg['status'] = 'FAIL';
				    			$msg['errors'][$i]['id'] = $key;
								$msg['errors'][$i]['message'] = $val;
				   				$i++;
							}
						}
					}
					if($i==0){
						$bid_site_fee=getSiteCommissionFee($member_id);
						$bid_amount=0;
						$project_bids=array(
							'member_id'=>$member_id,
							'organization_id'=>$organization_id,
							'project_id'=>$pid,
							'bid_amount'=>$bid_amount,
							'bid_site_fee'=>$bid_site_fee,
							'bid_by_project'=>0,
							'bid_duration'=>NULL,
							'bid_details'=>NULL,
							'bid_attachment'=>NULL,
							'bid_date'=>date('Y-m-d H:i:s'),
						);
						if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
							$project_bids['bid_by_project']=1;
						}
						if($this->input->post('bid_details')){
							$project_bids['bid_details']=post('bid_details');
						}
						if($is_hourly==1){
							$bid_amount=post('bid_amount');
						}else{
							if($project_bids['bid_by_project']==1){
								$bid_amount=post('bid_amount');
							}else{
								if($all_milestone){
									foreach($all_milestone as $mv){
										$bid_amount=$bid_amount+$mv['amount'];
									}
								}
							}
							$project_bids['bid_duration']=post('bid_duration');
						}
						$project_bids['bid_amount']=$bid_amount;
						
						$attahment=array();
						if($is_edited==1){
							if(post('projectfileprevious')){
								$projectfileprevious=post('projectfileprevious');
								foreach($projectfileprevious as $file){
									$file_data_p=json_decode($file);
									if($file_data_p){
										$attahment[]=array(
										'name'=>$file_data_p->original_name,
										'file'=>$file_data_p->file_name,
										);
									}
								}
							}
						}
						if(post('projectfile')){
							$projectfiles=post('projectfile');
							foreach($projectfiles as $file){
								$file_data=json_decode($file);
								if($file_data){
									if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
										rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."projects-files/projects-applications/".$file_data->file_name);
										$attahment[]=array(
										'name'=>$file_data->original_name,
										'file'=>$file_data->file_name,
										);
									}
								}
							}
						}
						if($attahment){
							$project_bids['bid_attachment']=json_encode($attahment);
						}
						
						if($is_edited==1){
							updateTable('project_bids',$project_bids,array('bid_id'=>$bid_id));
						}else{
							$bid_id=insert_record('project_bids',$project_bids,TRUE);
						}
						
						
						if($bid_id){
							$this->db->where('bid_id',$bid_id)->delete('project_bid_milestones');
							if($project_bids['bid_by_project']!=1){
								if($all_milestone){
									foreach($all_milestone as $milestone){
										$project_bid_milestones=array(
										'bid_id'=>$bid_id,
										'bid_milestone_title'=>$milestone['title'],
										'bid_milestone_due_date'=>$milestone['due_date'],
										'bid_milestone_amount'=>$milestone['amount'],
										'bid_milestone_id'=>time(),
										);
										insert_record('project_bid_milestones',$project_bid_milestones);
										
									}
								}
							}
							$this->db->where('bid_id',$bid_id)->delete('project_bid_answer');
							if($this->input->post('qid')){
								$questionids=array_unique($this->input->post('qid'));
								$answer=post('question');
								foreach($questionids as $question_id){
									if($answer[$question_id]){
										$project_bid_answer=array(
										'bid_id'=>$bid_id,
										'question_id'=>$question_id,
										'question_answer'=>$answer[$question_id],
										);
										insert_record('project_bid_answer',$project_bid_answer);
									}
								}
							}
							$msg['status'] = 'OK';
							if($is_edited==1){
								
							}else{
								$template="new-proposal";
								$to=getFieldData('member_email','member','member_id',$projectDetails['project_owner']->member_id);
								$RECEIVER_NAME=$projectDetails['project_owner']->member_name;
								if($projectDetails['project_owner']->organization_name){
									$RECEIVER_NAME=$projectDetails['project_owner']->organization_name;
								}
								$SENDER_NAME=getFieldData('member_name','member','member_id',$member_id);
								$data_parse = array(
									'SENDER_NAME' =>$SENDER_NAME,
									'RECEIVER_NAME' =>$RECEIVER_NAME,
									'TITLE' =>$projectDetails['project']->project_title,
									'PROJECT_URL' =>get_link('myProjectDetailsURL')."/".$projectDetails['project']->project_url,
								);
								SendMail($to,$template,$data_parse);
								
								$this->notification_model->log(
									$template, // template key
									$data_parse, // template data
									$this->config->item('myProjectDetailsURL')."/".$projectDetails['project']->project_url, // link (without base_url)
									$projectDetails['project_owner']->member_id, // notification to,
									$this->member_id // notification_from
								);
							}
							
						}
						
					}
				}
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'error';
				$msg['errors'][$i]['message'] = 'Invalid ';
				$i++;
			}
		unset($_POST);
		echo json_encode($msg);	
		}
	}
	public function application($project_id='',$application_id='')
	{	
		
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;	
			if($member_id){
				$this->data['projects']=getProjectDetails($project_id,array('project_owner','project_settings'));
				$this->data['projects']['project_id']=$project_id;
				$owner_organization_id=$this->data['projects']['project_owner']->organization_id;
				$owner_member_id=$this->data['projects']['project_owner']->member_id;
				$bidder_id=getFieldData('member_id','project_bids','bid_id',$application_id);
				if(($owner_organization_id && $owner_organization_id==$organization_id) || ($bidder_id=$member_id)){
					$this->data['proposaldetails']=getProposalDetails($application_id);
					
					$memberDataBasic=getData(array(
						'select'=>'m.member_name,m_b.member_heading,m_b.member_overview,m_b.member_hourly_rate,c_n.country_name,c.country_code_short,m_l.logo,m_b.available_per_week,m_b.not_available_until,m_s.avg_rating,m_s.total_earning,m_s.no_of_reviews,m_s.total_working_hour,m_s.success_rate',
						'table'=>'member as m',
						'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left'),array('table'=>'member_statistics m_s','on'=>'m.member_id=m_s.member_id','position'=>'left'),array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>'c.country_code=c_n.country_code','position'=>'left'),array('table'=>'member_logo as m_l','on'=>'(m.member_id=m_l.member_id and m_l.status=1)','position'=>'left'),),
						'where'=>array('m.member_id'=>$bidder_id,'c_n.country_lang'=>get_active_lang()),
						'single_row'=>true,
					));
					$this->data['member_id']=$bidder_id;
					$this->data['memberInfo']=$memberDataBasic;
					$this->data['memberInfo']->badges=getData(array(
						'select'=>'b.icon_image,b_n.name,b_n.description',
						'table'=>'member_badges as m',
						'join'=>array(array('table'=>'badges as b','on'=>'m.badge_id=b.badge_id','position'=>'left'),array('table'=>'badges_names as b_n','on'=>"(b.badge_id=b_n.badge_id and b_n.lang='".get_active_lang()."')",'position'=>'left')),
						'where'=>array('m.member_id'=>$bidder_id,'b.status'=>1),
						'order'=>array(array('b.display_order','asc')),
					));
					
					$this->data['memberInfo']->total_jobs=$this->db->where(array('c.contractor_id'=>$bidder_id,'c.contract_status'=>1))->from('project_contract as c')->count_all_results();;
					
					
					
					
					$arr=array(
						'select'=>'q.question_id,q.question_title,a.question_answer',
						'table'=>'project_question as p_q',
						'join'=>array(
							array('table'=>'question as q','on'=>'p_q.question_id=q.question_id','position'=>'left'),
							array('table'=>'project_bid_answer as a','on'=>"(q.question_id=a.question_id and a.bid_id='".$application_id."')",'position'=>'left'),
						),
						'where'=>array('p_q.project_id'=>$project_id,'p_q.project_question_status'=>1),
						'order'=>array(array('p_q.project_question_id','asc'))
					);
					
					
					$this->data['proposaldetails']['project_question']=getData($arr);
					$this->layout->view('proposal-details', $this->data);
				}
				
			}
		}
	}
	public function hire($pid='',$mid='')
	{
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
				'bootbox_custom.js',
				'upload-drag-file.js',
				'moment-with-locales.js',
				'bootstrap-datetimepicker.min.js'
			));
		$this->layout->set_css(array(
		'bootstrap-datetimepicker.css'
		));
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			$project_id=getFieldData('project_id','project','md5(project_id)',$pid);
			$bidder_id=getFieldData('member_id','member','md5(member_id)',$mid);
			$this->data['projects']=getProjectDetails($project_id,array('project','project_owner','project_settings'));
			if($this->data['projects']){
				$owner_organization_id=$this->data['projects']['project_owner']->organization_id;
				$owner_member_id=$this->data['projects']['project_owner']->member_id;
				if(($owner_organization_id && $owner_organization_id==$organization_id) || ($bidder_id=$member_id)){
					
					$memberDataBasic=getData(array(
						'select'=>'m.member_name,m_b.member_heading,m_b.member_overview,m_b.member_hourly_rate,c_n.country_name,c.country_code_short,m_l.logo',
						'table'=>'member as m',
						'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left'),array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>'c.country_code=c_n.country_code','position'=>'left'),array('table'=>'member_logo as m_l','on'=>'(m.member_id=m_l.member_id and m_l.status=1)','position'=>'left'),),
						'where'=>array('m.member_id'=>$bidder_id,'c_n.country_lang'=>get_active_lang()),
						'single_row'=>true,
					));
					$this->data['memberInfo']=$memberDataBasic;
					
					$this->data['pid']=$project_id;
					$this->data['bid']=$bidder_id;
					$this->data['getBidDetails']=getData(array(
					'select'=>'bid_id,bid_amount,bid_site_fee,bid_by_project,bid_duration,bid_details,bid_attachment,is_hired',
					'table'=>'project_bids',
					'where'=>array('project_id'=>$project_id,'member_id'=>$bidder_id),
					'single_row'=>TRUE
					));
					$this->layout->view('hire-member', $this->data);
				}
			}
			
				
			
		}
		
	}
	public function hire_project_form_check(){
		$this->load->library('form_validation');
		checkrequestajax();
		if($this->access_member_type!='C'){
			redirect(get_link('dashboardURL'));
		}
		$i=0;
		$msg=array();
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			$pid=post('pid');
			$bid=post('bid');
			$is_edited=0;
			$all_milestone=array();
			$projectDetails=getProjectDetails($pid,array('project','project_owner'));
			if($projectDetails){
				if($projectDetails['project_owner']->member_id==$member_id){

				}else{
					show_403();
				}
				if($this->input->post()){
					$is_hourly=post('is_hourly');
					$this->form_validation->set_rules('pid', 'pid', 'required|trim|xss_clean|is_numeric');
					$this->form_validation->set_rules('bid', 'bid', 'required|trim|xss_clean|is_numeric');
					$this->form_validation->set_rules('title', 'title', 'required|trim|xss_clean');
					$this->form_validation->set_rules('is_hourly', 'paymode', 'required|trim|xss_clean|is_numeric');
					if($is_hourly==1){
						$this->form_validation->set_rules('bid_amount_hourly', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
					}else{
						$this->form_validation->set_rules('bid_by_project', 'bid_by_project', 'required|trim|xss_clean');
						$this->form_validation->set_rules('bid_amount', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
						if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
							$this->form_validation->set_rules('milestone_due_date', 'due date', 'required|trim|xss_clean|valid_date');
							$milestone=array(
							'title'=>post('title'),
							'due_date'=>post('milestone_due_date'),
							'amount'=>post('bid_amount'),
							);
							$all_milestone[]=$milestone;
						}else{
							$this->form_validation->set_rules('milestone_id[]', 'milestone_id', 'required|trim|xss_clean');
							if($this->input->post('milestone_id[]')){
								$all_milestone_count=post('milestone_id');
								foreach($all_milestone_count as $mid){
									$this->form_validation->set_rules('milestone_title_'.$mid, 'title', 'required|trim|xss_clean');
									$this->form_validation->set_rules('milestone_due_date_'.$mid, 'due date', 'required|trim|xss_clean|valid_date');
									$this->form_validation->set_rules('milestone_amount_'.$mid, 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
									$milestone=array(
									'title'=>post('milestone_title_'.$mid),
									'due_date'=>post('milestone_due_date_'.$mid),
									'amount'=>post('milestone_amount_'.$mid),
									);
									$all_milestone[]=$milestone;
								}	
							}
						}
					}
					$this->form_validation->set_rules('i_agree', 'agree', 'required|trim|xss_clean');
					$this->form_validation->set_rules('bid_details', 'details', 'trim|xss_clean');	
					
					if ($this->form_validation->run() == FALSE){
						$error=validation_errors_array();
						if($error){
							foreach($error as $key=>$val){
								$msg['status'] = 'FAIL';
				    			$msg['errors'][$i]['id'] = $key;
								$msg['errors'][$i]['message'] = $val;
				   				$i++;
							}
						}
					}
					if($i==0){
						$pay_amount=0;
						if($is_hourly==1){
							//$pay_amount=post('bid_amount_hourly');
						}else{
							if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
								$pay_amount=post('bid_amount');
							}else{
								if($all_milestone){
									$pay_amount=$all_milestone[0]['amount'];
								}
							}
						}
						$wallet_balance=getFieldData('balance','wallet','user_id',$this->member_id);
						if($wallet_balance>=$pay_amount){
							
						}else{
							$msg['status'] = 'FAIL';
							$msg['popup'] = 'fund';
							/*$msg['balance'] = $wallet_balance;*/
			    			$msg['errors'][$i]['id'] = 'fund';
							$msg['errors'][$i]['message'] = 'Insufficient funds';
			   				$i++;	
						}
						
					}
					
					if($i==0){
					
						$bid_site_fee=0;
						$bid_amount=0;
						$bid_id=NULL;
						$getBidDetails=getData(array(
						'select'=>'bid_id,is_hired',
						'table'=>'project_bids',
						'where'=>array('project_id'=>$pid,'member_id'=>$bid),
						'single_row'=>TRUE
						));
						if($getBidDetails){
							$bid_id=$getBidDetails->bid_id;
							$bid_site_fee=getSiteCommissionFee($bid);
						}
						
						
						
						$project_contract=array(
							'contractor_id'=>$bid,
							'project_id'=>$pid,
							'bid_id'=>$bid_id,
							'contract_title'=>post('title'),
							'offer_by'=>$member_id,
							'contract_status'=>0,
							'is_hourly'=>0,
							'contract_date'=>date('Y-m-d H:i:s'),
						);
						$project_contract_offer=array(
						'contract_details'=>NULL,
						'contract_attachment'=>NULL,
						'max_hour_limit'=>0,
						'allow_manual_hour'=>0,
						);
						if($is_hourly==1){
							$project_contract['is_hourly']=1;
							if(post('max_hour_limit')){
								$project_contract_offer['max_hour_limit']=post('max_hour_limit');
							}
							if(post('allow_manual_hour')){
								$project_contract_offer['allow_manual_hour']=1;
							}
						}
						/*if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
							$project_bids['bid_by_project']=1;
						}
						*/
						if($this->input->post('bid_details')){
							$project_contract_offer['contract_details']=post('bid_details');
						}
						if($is_hourly==1){
							$bid_amount=post('bid_amount_hourly');
						}else{
							if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
								$bid_amount=post('bid_amount');
							}else{
								if($all_milestone){
									foreach($all_milestone as $mv){
										$bid_amount=$bid_amount+$mv['amount'];
									}
								}
							}
						}
						$project_contract['contract_amount']=$bid_amount;
						
						$attahment=array();
						if(post('projectfile')){
							$projectfiles=post('projectfile');
							foreach($projectfiles as $file){
								$file_data=json_decode($file);
								if($file_data){
									if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
										rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."projects-files/projects-contract/".$file_data->file_name);
										$attahment[]=array(
										'name'=>$file_data->original_name,
										'file'=>$file_data->file_name,
										);
									}
								}
							}
						}
						if($attahment){
							$project_contract_offer['contract_attachment']=json_encode($attahment);
						}
						$contract_id=insert_record('project_contract',$project_contract,TRUE);
						if($contract_id){
							$project_contract_offer['contract_id']=$contract_id;
							insert_record('project_contract_offer',$project_contract_offer);
							if($bid_id){
								updateTable('project_bids',array('is_hired'=>1,'is_archive'=>NULL,'is_shortlisted'=>'NULL','is_interview'=>NULL),array('bid_id'=>$bid_id));
							}
								if($all_milestone){
									foreach($all_milestone as $m=>$milestone){
										if($m==0){
											$is_escrow=1;
										}else{
											$is_escrow=0;
										}
										$project_contract_milestone=array(
										'contract_id'=>$contract_id,
										'milestone_title'=>$milestone['title'],
										'milestone_due_date'=>$milestone['due_date'],
										'milestone_amount'=>$milestone['amount'],
										'milestone_create_date'=>date('Y-m-d H:i:s'),
										'is_escrow'=>$is_escrow,
										);
										insert_record('project_contract_milestone',$project_contract_milestone);
										
									}
								}
							if($pay_amount){
								$this->load->model('contract/contract_model');
								$this->contract_model->addFundToEscrow($pid,$this->member_id,$contract_id,$pay_amount);
							}
							
							$template="new-project-offer";
							$to=getFieldData('member_email','member','member_id',$bid);
							$RECEIVER_NAME=getFieldData('member_name','member','member_id',$bid);
							$SENDER_NAME=$projectDetails['project_owner']->member_name;
							if($projectDetails['project_owner']->organization_name){
								$SENDER_NAME=$projectDetails['project_owner']->organization_name;
							}
							$data_parse = array(
								'SENDER_NAME' =>$SENDER_NAME,
								'RECEIVER_NAME' =>$RECEIVER_NAME,
								'TITLE' =>$projectDetails['project']->project_title,
								'OFFER_URL' =>get_link('OfferDetails').'/'.md5($contract_id),
							);
							SendMail($to,$template,$data_parse);
							
							$this->notification_model->log(
								$template, // template key
								$data_parse, // template data
								$this->config->item('OfferDetails').'/'.md5($contract_id), // link (without base_url)
								$bid, // notification to,
								$this->member_id // notification_from
							);
	
							
							$msg['status'] = 'OK';
						}
						
						
						
					}
				}
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'error';
				$msg['errors'][$i]['message'] = 'Invalid ';
				$i++;
			}
		unset($_POST);
		echo json_encode($msg);	
		}
	}
}
