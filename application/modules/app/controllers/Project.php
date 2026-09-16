<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("access-control-allow-origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header('content-type: application/json; charset=utf-8');

require APPPATH.'libraries/MX_Rest.php';
class Project extends MX_Rest{
	private $ReturnCode;
	private $ReturnStatus;
	private $ResponseData;
	function __construct() {
		if($this->input->get('originatesoft_lang')){
			$langS=get('originatesoft_lang');
			$alllanguage=explode(',',get_setting('language'));
			if(in_array($langS,$alllanguage)){
				$this->session->set_userdata('current_lang',$langS);
			} 
		}
		if($this->input->get('member_id') && $this->input->get('member_id')>0){
			$this->member_id=$this->input->get('member_id');
            $is_employer=$this->auto_model->getFeild('is_employer','member','member_id',$this->member_id);
			$this->organization_id=0;
			if($is_employer){
				$this->organization_id=$this->auto_model->getFeild('organization_id','organization','member_id',$this->member_id);
			}
			$this->account_type=($is_employer==1?'E':'F');
			$result[]=(object)[
					'member_id'=>$this->member_id,
					'account_type'=>$this->account_type,
			];
			$this->session->set_userdata('user', $result);
		}

	 	$this->ReturnStatus=0;
	 	$this->ReturnCode=200;
	 	$this->ResponseData=array();
        parent::__construct();
	}
	
	public function get_index(){
     $this->response(array(
        "status" => 1,
        "message" => "Welcome to project module"
      ) , 200);
    }
	
	public function get_list(){
		
		//$this->load->model('project_model', 'project');
		$this->load->model('job/job_model', 'job_model');
		$member_id = $this->uri->segment(4);
		$srch = get();
		$srch['member_id'] = $member_id;
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$list=$this->job_model->getJobList($srch,$start,$limit);
		if($list){
			foreach($list as $k=>$v){
				$budget = !empty($v['budget']) ? $v['budget'] : 0;
				if($v['is_hourly']){
					$duration=getAllProjectDuration($v['hourly_duration']);
					$durationtime=getAllProjectDurationTime($v['hourly_time_required']);
					$v['hourly_duration']=$duration['name'];
					$v['hourly_time_required']=$durationtime['name'];
				}else{

				}
				if($v['project_type_code']){
					$project_type = getAllProjectType($v['project_type_code']);
					$v['project_type_name']=$project_type['name'];
				}
				$list[$k]=$v;
			}
		}
		$data = array(
			'list' => $list,
			'total' => $this->job_model->getJobList($srch,'','', false),
		);
		$data['total_page']=ceil($data['total']/$limit);
		$data['current_page']=$page;
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
    }
	public function get_filter(){
		$this->load->model('job/job_model', 'job_model');
		$data=array();
		$data['category']=$this->job_model->get_all_category();
		if($data['category']){
			foreach($data['category'] as $k=>$category){
				$data['category'][$k]['sub_category']=$this->job_model->get_sub_category($category['category_id']);
			}
		}
		$data['is_hourly']=array(
			array('name'=>__('job_findjobs_hourly','Hourly'),'value'=>'1'),
			array('name'=>__('job_findjobs_fixed','Fixed'),'value'=>'0'),
		);
		$data['job_type']=array(
			array('name'=>__('job_findjobs_one_time_project','One Time Poject'),'value'=>'OneTime'),
			array('name'=>__('job_findjobs_ongoing_project','Ongoing Project'),'value'=>'Ongoing'),
			array('name'=>__('job_findjobs_not_sure','Not Sure'),'value'=>'NotSure'),
		);
		
		$data['experience_level'] = $this->job_model->get_experience_level();

	

		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}

/** Employer start **/
	
	public function get_myproject(){
		$srch = get();
		$member_id=$this->member_id;
		$organization_id=$this->organization_id;
		if(!$member_id || !$organization_id){
			 $this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
			  ) , 
		  $this->ReturnCode);
		} 
		
		$where = array();
		$this->load->model('projectclient/projectclient_model', 'projectclient_model');
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$all=$this->projectclient_model->getProjects($organization_id,$member_id,$start,$limit,'',$where);
		if($all){
			foreach($all as $i=>$projectL){
				$all[$i]->bids=getBids($projectL->project_id,array(),true);
				$all[$i]->hired=getBids($projectL->project_id,array('is_hired'=>TRUE),true);
				$all[$i]->message=0;
			}
		}
		$data = array(
			'list' => $all,
			'total' => $this->projectclient_model->getProjects($organization_id,$member_id,$start,$limit,TRUE,$where),
		);
		$data['total_page']=ceil($data['total']/$limit);
		$data['current_page']=$page;
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
    }
	
	public function get_offer_client(){
		$srch = get();
		$member_id = $this->input->get('member_id');
		if(!$member_id){
			 $this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
			  ) , 
		  $this->ReturnCode);
		} 
		
		
		$this->load->model('contract/contract_model', 'contract_model');
		$srch['member_id'] = $member_id;
		$srch['owner_id'] = $this->member_id;
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$list=$this->contract_model->getContracts($srch,$start,$limit);
		$data = array(
			'list' => $list,
			'total' => $this->contract_model->getContracts($srch,'','', false),
		);
		$data['total_page']=ceil($data['total']/$limit);
		$data['current_page']=$page;
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
    }
	public function get_contract_client(){
		$srch = get();
		$member_id = $this->input->get('member_id');
		if(!$member_id){
			 $this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
			  ) , 
		  $this->ReturnCode);
		} 
		
		
		$this->load->model('contract/contract_model', 'contract_model');
		$srch['member_id'] = $member_id;
		$srch['owner_id'] = $this->member_id;
		$srch['contract_status'] = 1;
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$list=$this->contract_model->getContracts($srch,$start,$limit);
		$data = array(
			'list' => $list,
			'total' => $this->contract_model->getContracts($srch,'','', false),
		);
		$data['total_page']=ceil($data['total']/$limit);
		$data['current_page']=$page;
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
    }
	public function post_hire(){
		$this->load->library('form_validation');
		$i=0;
		$msg=array();
		if($this->member_id){
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
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'error';
					$msg['errors'][$i]['message'] = 'Invalid ';
					$i++;
					$this->ResponseData=$msg;
					$this->response(array(
						"status" =>$this->ReturnStatus,
						"response" =>$this->ResponseData
					) , $this->ReturnCode);
					die;

				}
				if($this->input->post()){
					$is_hourly=post('is_hourly');
					$this->form_validation->set_rules('pid', 'pid', 'required|trim|xss_clean|is_numeric');
					$this->form_validation->set_rules('bid', 'bid', 'required|trim|xss_clean|is_numeric');
					$this->form_validation->set_rules('title', 'title', 'required|trim|xss_clean');
					$this->form_validation->set_rules('is_hourly', 'paymode', 'required|trim|xss_clean|is_numeric');
					if($is_hourly==1){
						$this->form_validation->set_rules('bid_amount', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
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
							$this->form_validation->set_rules('milestone_data_json', 'milestone', 'required|trim|xss_clean');
							if($this->input->post('milestone_data_json')){
								$all_milestones_data=json_decode($this->input->post('milestone_data_json'),true);
								foreach($all_milestones_data as $k=>$row){
									if(!$row['milestone_title']){
										$msg['status'] = 'FAIL';
										$msg['errors'][$i]['id'] = 'milestone_title_'.($k+1);
										$msg['errors'][$i]['message'] = 'milestone title required';
										$i++;
									}
									if(!$row['milestone_amount'] || $row['milestone_amount'] <0){
										$msg['status'] = 'FAIL';
										$msg['errors'][$i]['id'] = 'milestone_amount_'.($k+1);
										$msg['errors'][$i]['message'] = 'milestone amount required';
										$i++;
									}
							   
									$milestone=array(
									'title'=>$row['milestone_title'],
									'due_date'=>$row['milestone_due_date'],
									'amount'=>$row['milestone_amount'],
									);
									$all_milestone[]=$milestone;
								}	
								
							}
							//$this->form_validation->set_rules('milestone_due_date', 'due date', 'required|trim|xss_clean|valid_date');
							
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
							$bid_amount=post('bid_amount');
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
							$projectfiles=json_decode(post('projectfile'));
							foreach($projectfiles as $file){
								$file_data=$file;
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
	
							
							$this->ReturnStatus=1;
							$msg['data']=[
								'pid'=>$pid,
								'contract_id'=>$contract_id,
							];
						}
						
						
						
					}
				}
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'error';
				$msg['errors'][$i]['message'] = 'Invalid ';
				$i++;
			}
			$this->ResponseData=$msg;
			$this->response(array(
				"status" =>$this->ReturnStatus,
				"response" =>$this->ResponseData
			) , $this->ReturnCode);
		}
	}

	public function get_postproject_option(){
		$data=array();
		$data['all_category']=getAllCategory();
		$data['all_projectType']=getAllProjectType();
		$data['all_projectExperienceLevel']=getAllExperienceLevel();
		$data['all_projectDuration']=getAllProjectDuration();
		$data['all_projectDurationTime']=getAllProjectDurationTime();
		$data['all_skills']=getAllSkills();
		if($data['all_category']){
			foreach($data['all_category'] as $k=>$category){
				$data['all_category'][$k]->subcategory=getAllSubCategory($category->category_id);
			}
		}
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}
	public function post_postproject(){
		$this->load->library('form_validation');
		$i=0;
		$member_id=$this->member_id;	
		$organization_id=$this->organization_id;
		$msg=array();
		$this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
		$this->form_validation->set_rules('category', 'Category', 'required|trim|xss_clean|is_natural_no_zero');
		$this->form_validation->set_rules('sub_category', 'Speciality', 'required|trim|xss_clean|is_natural_no_zero');

		$this->form_validation->set_rules('description', 'Description', 'required|trim|xss_clean');
		$this->form_validation->set_rules('projectType', 'Project Type', 'required|trim|xss_clean');
		$this->form_validation->set_rules('skills', 'Skills', 'required|trim|xss_clean');
        
        $this->form_validation->set_rules('projectVisibility', 'Project Visibility', 'required|trim|xss_clean|in_list[public,private,invite]');
		$this->form_validation->set_rules('member_required', 'member required', 'required|trim|xss_clean|in_list[S,M]');
		if(post('member_required') && post('member_required')=='M'){
			$this->form_validation->set_rules('no_of_freelancer', 'no of freelancer', 'required|trim|xss_clean|is_natural_no_zero');
		}
		$this->form_validation->set_rules('projectPaymentType', 'Project pay type', 'required|trim|xss_clean|in_list[fixed,hourly]');
		$this->form_validation->set_rules('experience_level', 'Experience level required', 'required|trim|xss_clean');
		if(post('projectPaymentType') && post('projectPaymentType')=='fixed'){
			$this->form_validation->set_rules('fixed_budget', 'budget', 'required|trim|xss_clean|numeric');
		}
		if(post('projectPaymentType') && post('projectPaymentType')=='hourly'){
			$this->form_validation->set_rules('hourly_duration', 'Duration', 'required|trim|xss_clean');
			$this->form_validation->set_rules('hourly_duration_time', 'Duration time', 'required|trim|xss_clean');
		}
		$dataid=post('dataid');
		if($dataid){
			if($member_id){
				$arr=array(
					'select'=>'p.project_id',
					'table'=>'project as p',
					'join'=>array(
						array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left'),
					),
					'where'=>array('p.project_status <>'=>PROJECT_DELETED),
					'single_row'=>true,
					);
				$arr['where']['p.project_id']=$dataid;
				$arr['where']['p_o.member_id']=$member_id;
				$ProjectDataBasic=getData($arr);
				if($ProjectDataBasic){
					$project_id=$ProjectDataBasic->project_id;
					$is_edited=1;
				}else{
					$msg['status'] = 'FAIL';
                    $msg['errors'][$i]['id'] = 'account';
                    $msg['errors'][$i]['message'] = 'Invalid account details';
                    $i++;
				}
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'account';
				$msg['errors'][$i]['message'] = 'Invalid account details';
				$i++;
			}
		}

		if($this->form_validation->run( )== FALSE){
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
    		$project_title=generateProjectSlug(post('title'));
			$project=array(
				'project_title'=>post('title'),
				'project_short_info'=>substr(strip_tags(post('description')),0,150),
				'project_member_required'=>1,
				'project_posted_date'=>date('Y-m-d H:i:s'),
				'project_expired_date'=>date('Y-m-d H:i:s',strtotime('+1 month')),
				'project_status'=>PROJECT_OPEN,
				'project_edit_id'=>NULL,
				'project_url'=>$project_title,
			);
    		if(post('member_required') && post('member_required')=='M'){
				$project['project_member_required']=post('no_of_freelancer');
			}
			if($is_edited){
				unset($project['project_url']);
				updateTable('project',$project,array('project_id'=>$project_id));
			}else{
				$project_id=insert_record('project',$project,TRUE);
			}
    		if($project_id){
				if($is_edited){}else{
					$project_owner=array(
					'project_id'=>$project_id,
					'member_id'=>$member_id,
					'organization_id'=>$organization_id,
					);
					insert_record('project_owner',$project_owner);
				}
				$project_category=array(
				'project_id'=>$project_id,
				'category_id'=>post('category'),
				'category_subchild_id'=>post('sub_category'),
				);
				if($is_edited){
					unset($project_category['project_id']);
					updateTable('project_category',$project_category,array('project_id'=>$project_id));
				}else{
					insert_record('project_category',$project_category);
				}
				
				$project_additional=array(
				'project_id'=>$project_id,
				'project_description'=>post('description'),
				'project_is_cover_required'=>NULL,
				);
				if(post('question')){
					if(post('is_cover_required')){
						$project_additional['project_is_cover_required']=1;
					}
				}else{
					$project_additional['project_is_cover_required']=1;
				}
				if($is_edited){
					unset($project_additional['project_id']);
					updateTable('project_additional',$project_additional,array('project_id'=>$project_id));
				}else{
					insert_record('project_additional',$project_additional);
				}
				if($is_edited==1){
					$previous_file=array();
					if(post('projectfileprevious')){
						$projectfileprevious=post('projectfileprevious');
						foreach($projectfileprevious as $file){
							$file_data_p=json_decode($file);
							if($file_data_p){
								$previous_file[]=$file_data_p->file_id;
								$is_primary=0;
								$file_order[]=array('file_id'=>$file_data_p->file_id);
							}
						}
					}
					if($previous_file){
						$this->db->where_not_in('file_id',$previous_file)->where('project_id',$project_id)->delete('project_files');
					}else{
						$this->db->where('project_id',$project_id)->delete('project_files');
					}
				}
				if(post('projectfile')){
					$projectfiles=post('projectfile');
					foreach($projectfiles as $file){
						$file_data=json_decode($file);
						if($file_data){
							if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
								rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."projects-files/projects-requirement/".$file_data->file_name);
								$ext=explode('.',$file_data->file_name);
								$files=array(
								'original_name'=>$file_data->original_name,
								'server_name'=>$file_data->file_name,
								'upload_time'=>date('Y-m-d H:i:s'),
								'file_ext'=>strtolower(end($ext)),
								);
								$file_id=insert_record('files',$files,TRUE);
								if($file_id){
									$project_files=array(
									'project_id'=>$project_id,
									'file_id'=>$file_id,
									);
									insert_record('project_files',$project_files);
								}
							}
						}
					}
				}
				if($is_edited==1){
					$previous_question=array();
					if(post('pre_question')){
						$projectpre_question=post('pre_question');
						foreach($projectpre_question as $question_id=>$question){
							if(trim($question)!=''){
							if($question_id){
								$previous_question[]=$question_id;
							}
							$questionDatacount=getData(array(
								'select'=>'q.question_id',
								'table'=>'question as q',
								'where'=>array('LOWER(q.question_title)'=>trim(strtolower($question))),
								'single_row'=>true,
							)
							);
							$newquestion_id=0;
							if($questionDatacount){
								$newquestion_id=$questionDatacount->question_id;
								updateTable('question',array('question_status'=>1),array('question_id'=>$question_id));
							}else{
								$question=array(
								'question_title'=>$question,
								'category_subchild_id'=>$project_category['category_subchild_id'],
								'question_status'=>1,
								'is_manual'=>1,
								);
								$newquestion_id=insert_record('question',$question,TRUE);
							}
							if($newquestion_id){
								$questionDatacount_p=getData(array(
								'select'=>'q.question_id',
								'table'=>'project_question as q',
								'where'=>array('project_id'=>$project_id,'question_id'=>$newquestion_id),
								'single_row'=>true,
								)
								);
								if(!$questionDatacount_p){
									$previous_question[]=$newquestion_id;
									$project_question=array(
									'project_id'=>$project_id,
									'question_id'=>$newquestion_id,
									'project_question_status'=>1,
									);
									insert_record('project_question',$project_question);
								}
									
							}
							}
						}
					}
					if($previous_question){
						$this->db->where_not_in('question_id',$previous_question)->where('project_id',$project_id)->delete('project_question');
					}else{
						$this->db->where('project_id',$project_id)->delete('project_question');
					}
					
				}
				if(post('question')){
					if($is_edited==1){}else{
						$this->db->where('project_id', $project_id)->delete('project_question');
					}
					$projectquestion=post('question');
					foreach($projectquestion as $question){
					if(trim($question)!=''){
						$questionDatacount=getData(array(
							'select'=>'q.question_id',
							'table'=>'question as q',
							'where'=>array('LOWER(q.question_title)'=>trim(strtolower($question))),
							'single_row'=>true,
						)
						);
						if($questionDatacount){
							$question_id=$questionDatacount->question_id;
							updateTable('question',array('question_status'=>1),array('question_id'=>$question_id));
						}else{
							$question=array(
							'question_title'=>$question,
							'category_subchild_id'=>$project_category['category_subchild_id'],
							'question_status'=>1,
							'is_manual'=>1,
							);
							$question_id=insert_record('question',$question,TRUE);
							
						}
						if($question_id){
							$project_question=array(
							'project_id'=>$project_id,
							'question_id'=>$question_id,
							'project_question_status'=>1,
							);
							insert_record('project_question',$project_question);
						}
					}
					}
				}
				if(post('skills')){
					$all_skill=post('skills');
					updateTable('project_skills',array('project_skill_status'=>0),array('project_id'=>$project_id));
					if($all_skill){
						$sk=explode(',',$all_skill);
						foreach($sk as $ord=>$skill_id){
							$skillDatacount=getData(array(
								'select'=>'p_s.project_skill_id',
								'table'=>'project as p',
								'join'=>array(array('table'=>'project_skills as p_s','on'=>'p.project_id=p_s.project_id','position'=>'left')),
								'where'=>array('p.project_id'=>$project_id,'p_s.skill_id'=>$skill_id),
								'single_row'=>true,
							));
							if($skillDatacount){
								updateTable('project_skills',array('project_skill_status'=>1),array('project_skill_id'=>$skillDatacount->project_skill_id));
							}else{
								insert_record('project_skills',array('project_id'=>$project_id,'skill_id'=>$skill_id,'project_skill_status'=>1),TRUE);
							}
						}
					}
				}
				$project_settings=array(
					'project_id'=>$project_id,
					'is_visible_anyone'=>NULL,
					'is_visible_private'=>NULL,
					'is_visible_invite'=>NULL,
					'is_hourly'=>NULL,
					'is_fixed'=>NULL,
					'budget'=>NULL,
					'experience_level'=>NULL,
					'hourly_duration'=>NULL,
					'hourly_time_required'=>NULL,
					'project_type_code'=>NULL,
				);

				if(post('projectVisibility') && post('projectVisibility')=='public'){
					$project_settings['is_visible_anyone']=1;
				}elseif(post('projectVisibility') && post('projectVisibility')=='private'){
					$project_settings['is_visible_private']=1;
				}elseif(post('projectVisibility') && post('projectVisibility')=='invite'){
					$project_settings['is_visible_invite']=1;
				}
				if(post('projectPaymentType') && post('projectPaymentType')=='hourly'){
					$project_settings['is_hourly']=1;
					if(post('hourly_duration')){
						$project_settings['hourly_duration']=post('hourly_duration');
					}
					if(post('hourly_duration_time')){
						$project_settings['hourly_time_required']=post('hourly_duration_time');
					}
				}
				if(post('projectPaymentType') && post('projectPaymentType')=='fixed'){
					$project_settings['is_fixed']=1;
					$project_settings['budget']=post('fixed_budget');
					
				}
				if(post('experience_level')){
					$project_settings['experience_level']=post('experience_level');
				}
				if(post('experience_level')){
					$project_settings['experience_level']=post('experience_level');
				}
				if(post('projectType')){
					$project_settings['project_type_code']=post('projectType');
				}
				if($is_edited==1){
					unset($project_settings['project_id']);
					updateTable('project_settings',$project_settings,array('project_id'=>$project_id));
				}else{
					insert_record('project_settings',$project_settings);	
				}

				$data_parse = array(
					'TITLE' => post('title')
				);
				if($is_edited==1){
					
				}else{
					$this->admin_notification_model->parse('admin-ad-post', $data_parse, 'proposal/list_record?ID='.$project_id);
					SendMail(get_setting('admin_email'),'admin-ad-post',$data_parse);
				}

    			$this->ReturnStatus=1;
				$msg['status'] = 1;
				$msg['data']=[
					'project_id'=>$project_id
				];
			}else{
				$msg['errors']=$checkLogin['errors'];
			}
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
		
	
	}

/** Employer End **/
	
	
/** Freelancer Start **/
	

	public function get_bid_freelancer(){
		$member_id = $this->input->get('member_id');
		$organization_id='';
		if(!$member_id){
			 $this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
			  ) , 
		  $this->ReturnCode);
		} 
		
		$this->load->model('projectfreelancer/projectfreelancer_model', 'projectfreelancer_model');
		$srch['member_id'] = $member_id;
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$list=$this->projectfreelancer_model->getProjects($organization_id,$member_id,$start,$limit,'',$srch);

		$data = array(
			'list' => $list,
			'total' => $this->projectfreelancer_model->getProjects($organization_id,$member_id,'','',true,$srch),
		);
		
		$data['total_page']=ceil($data['total']/$limit);
		$data['current_page']=$page;
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
		
		
	}
	public function get_offer_freelancer(){
		$srch = get();
		$member_id = $this->input->get('member_id');
		if(!$member_id){
			 $this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
			  ) , 
		  $this->ReturnCode);
		} 
		
		
		$this->load->model('contract/contract_model', 'contract_model');
		$srch['member_id'] = $member_id;
		$srch['contractor_id'] = $this->member_id;
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$list=$this->contract_model->getContracts($srch,$start,$limit);
		$data = array(
			'list' => $list,
			'total' => $this->contract_model->getContracts($srch,'','', false),
		);
		$data['total_page']=ceil($data['total']/$limit);
		$data['current_page']=$page;
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
    }
	public function get_contract_freelancer(){
		$srch = get();
		$member_id = $this->input->get('member_id');
		if(!$member_id){
			 $this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
			  ) , 
		  $this->ReturnCode);
		} 
		
		
		$this->load->model('contract/contract_model', 'contract_model');
		$srch['member_id'] = $member_id;
		$srch['contractor_id'] = $this->member_id;
		$srch['contract_status'] = 1;
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$list=$this->contract_model->getContracts($srch,$start,$limit);
		$data = array(
			'list' => $list,
			'total' => $this->contract_model->getContracts($srch,'','', false),
		);
		$data['total_page']=ceil($data['total']/$limit);
		$data['current_page']=$page;
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
    }
	public function get_contest_freelancer(){
		$srch = get();
		$member_id = $this->input->get('member_id');
		if(!$member_id){
			 $this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
			  ) , 
		  $this->ReturnCode);
		} 
		
		
		$this->load->model('project_model', 'project');
		$srch['member_id'] = $member_id;
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		
		$data = array(
			'all_projects' => $this->project->getMyContestsEntryNew($srch,$start,$limit),
			'total_projects' => $this->project->getMyContestsEntryNew($srch,'','', false),
		);
		$data['total_page']=ceil($data['total_projects']/$limit);
		$data['current_page']=$page;
		
		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
    }
/** Freelancer End **/

	protected function category(){
	
		$result['data'] = get_results(array(
			'select' => 'cat_id,cat_name,parent_id,description', 
			'from' => 'categories', 
			'where' => array('status' => 'Y' ,'parent_id' => 0), 
			'offset' => 'all'
			));
		
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}
	
	protected function sector(){
	
		$result['data'] = get_results(array(
			'select' => '*',
			'from' => 'sectors',
			'where' => array('sector_status' => 1),
			'offset' => 'all'
		)); 
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}
	
	protected function project_type(){
	
		$result['data'] = array(
			array(
				'value' => 'F',
				'text' => 'Fixed',
			),
			array(
				'value' => 'H',
				'text' => 'Hourly',
			),
			
		);
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}
	
	protected function experience_level(){
	
		$result['data'] = get_results(array(
			'select' => 'id,name,description' , 
			'from' => 'experience_level' , 
			'where' => array('status' => 'Y')
		)); 
		
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}
	
	protected function posted_within(){
	
		$result['data'] = array(
			array(
				'value' => 1,
				'text' => 'Posted within 24 hours',
			),
			array(
				'value' => 3,
				'text' => 'Posted within 3 days',
			),
			array(
				'value' => 7,
				'text' => 'Posted within 7 days',
			),
			
		);
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}
	
	
	public function get_delete(){
		$member_id = get('member_id');
		$id = get('project_id');
		$this->load->model('postjob/postjob_model');
		if($id!=""){
			$project_id=  $this->auto_model->getFeild("project_id","projects","id",$id);
			$project_title=  $this->auto_model->getFeild("title","projects","id",$id);
  
			$all_bidder=$this->postjob_model->getBidder($project_id);
			if($all_bidder){
				$from=ADMIN_EMAIL;
				foreach($all_bidder as $key=>$val)
				{
					$to=$this->auto_model->getFeild('email','user','member_id',$val['bidder_id']);
					$fname=$this->auto_model->getFeild('fname','user','member_id',$val['bidder_id']);
					$lname=$this->auto_model->getFeild('lname','user','member_id',$val['bidder_id']);
					$display_name=$this->auto_model->getFeild('display_name','user','member_id',$val['bidder_id']);
					$template='close_job_notification';
					$data_parse=array('name'=>$display_name,
										'title'=>$project_title
										);
					$this->auto_model->send_email($from,$to,$template,$data_parse);
				}  
			}
			$this->db->delete('projects', array('id' => $id,'member_id' => $member_id));
		}	
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
		
	}

	
	public function post_accept_reject_offer(){
		$this->load->library('form_validation');
        $i=0;
		if($this->input->post()){
			$this->form_validation->set_rules('offer_id', 'offer_id', 'required|trim|xss_clean');
			$this->form_validation->set_rules('action_type', 'action_type', 'required|trim|xss_clean');
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
				$contract_id_get=post('offer_id');
				$contract_id_enc=md5($contract_id_get);
				$action_type=post('action_type');
				if(!in_array($action_type,array('accept','deny'))){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'action_type';
					$msg['errors'][$i]['message'] = 'invalid action';
	   				$i++;
				}
				$data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'offer_action'));
				if($data['contractDetails']){
					if($data['contractDetails']->owner_id==$this->member_id || $data['contractDetails']->contractor_id==$this->member_id){
						if($this->member_id!=$data['contractDetails']->offer_by){
							if($action_type=='accept'){
								$contract_status=1;
								//$msg['redirect'] = get_link('ContractList');
								$template="project-offer-accepted";
							}else{
								$contract_status=2;
								//$msg['redirect'] = get_link('OfferList');
								$template="project-offer-rejected";
							}
							updateTable('project_contract',array('contract_status'=>$contract_status),array('contract_id'=>$data['contractDetails']->contract_id));
							
							$projectDetails=getProjectDetails($data['contractDetails']->project_id,array('project','project_owner'));
							$to=getFieldData('member_email','member','member_id',$projectDetails['project_owner']->member_id);
							$RECEIVER_NAME=$projectDetails['project_owner']->member_name;
							if($projectDetails['project_owner']->organization_name){
								$RECEIVER_NAME=$projectDetails['project_owner']->organization_name;
							}
							$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
							$data_parse = array(
								'SENDER_NAME' =>$SENDER_NAME,
								'RECEIVER_NAME' =>$RECEIVER_NAME,
								'TITLE' =>$projectDetails['project']->project_title,
								'OFFER_URL' =>get_link('OfferDetails').'/'.md5($data['contractDetails']->contract_id),
							);
							SendMail($to,$template,$data_parse);
							
							$this->notification_model->log(
								$template, // template key
								$data_parse, // template data
								$this->config->item('OfferDetails').'/'.md5($data['contractDetails']->contract_id), // link (without base_url)
								$projectDetails['project_owner']->member_id, // notification to,
								$this->member_id // notification_from
							);
							$this->ReturnStatus=1;
							$msg['status'] = 'OK';
							$msg['data']=[
								'offer_id'=>$contract_id_get
							];
						}else{
							$msg['status'] = 'FAIL';
			    			$msg['errors'][$i]['id'] = 'offer_id';
							$msg['errors'][$i]['message'] = 'invalid offer id1';
			   				$i++;
						}
						
					}else{
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = 'offer_id';
						$msg['errors'][$i]['message'] = 'invalid offer id2';
		   				$i++;
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id3';
	   				$i++;
				}
				
			}
			
			
			
		
		}
        $this->ResponseData=$msg;
		
        $this->response(array(
           "status" =>$this->ReturnStatus,
           "response" =>$this->ResponseData
         ) , 
         $this->ReturnCode);

    }
	public function get_offer_details(){
		$srch = get();
		$member_id = $this->input->get('member_id');
		$contract_id_get = $this->input->get('contract_id');
		if(!$member_id || !$contract_id_get){
			 $this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
			  ) , 
		  $this->ReturnCode);
		} 
		$contract_id_enc=md5($contract_id_get);
		$data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'offer','member_id'=>$this->member_id));
		if($data['contractDetails']){
			$attachment=[];
			$attacch=json_decode($data['contractDetails']->contract_attachment);
			if($attacch){
				foreach($attacch as $k=>$val){
					if($val->file && file_exists(UPLOAD_PATH.'projects-files/projects-contract/'.$val->file)){
						$path_parts = pathinfo($val->name);
						$path_parts['full_url']= UPLOAD_HTTP_PATH.'projects-files/projects-contract/'.$val->file;
						$attachment[]=$path_parts;
					}
				}
			}
			$data['contractDetails']->contract_attachment_data=$attachment;
			

			$contract_id=$data['contractDetails']->contract_id;
			$project_id=$data['contractDetails']->project_id;
			$data['contractDetails']->milestone=getData(array(
				'select'=>'m.contract_milestone_id,m.milestone_title,m.milestone_amount,m.milestone_amount,m.milestone_due_date,m.is_approved,m.approved_date',
				'table'=>'project_contract_milestone m',
				'where'=>array('m.contract_id'=>$contract_id),
			));
			$owner=getProjectDetails($project_id,array('project_owner'));
			$data['contractDetails']->owner=$owner['project_owner'];
			$data['contractDetails']->contractor=getData(array(
				'select'=>'m.member_id,m.member_name',
				'table'=>'member m',
				'where'=>array('m.member_id'=>$data['contractDetails']->contractor_id),
				'single_row'=>true
			));
			$owner=getProjectDetails($project_id,array('project_owner'));
			$data['contractDetails']->owner=$owner['project_owner'];
			
			$data['contractDetails']->owner->statistics=getData(array(
				'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
				'table'=>'member_statistics as m_s',
				'where'=>array('m_s.member_id'=>$owner['project_owner']->member_id),
				'single_row'=>TRUE
			));
			
			$data['contractDetails']->contractor=getData(array(
				'select'=>'m.member_id,m.member_name,mb.member_heading,ms.avg_rating',
				'table'=>'member m',
				'join'=>array(
					array('table'=>'member_basic as mb','on'=>'m.member_id=mb.member_id','position'=>'left'),
					array('table'=>'member_statistics as ms','on'=>'m.member_id=ms.member_id','position'=>'left')
				),
				'where'=>array('m.member_id'=>$data['contractDetails']->contractor_id),
				'single_row'=>true
			));				
			
			$data['current_member']=$this->member_id;
			$data['is_owner']=0;
			if($owner['project_owner']->member_id==$this->member_id){
				$data['is_owner']=1;
			}
		}else{
			$this->response(array(
				"status" =>$this->ReturnStatus,
				"response" => array(
					'errors' => array(
						array(
							'id' => 'user',
							'message' => 'Unknown User',
						)
					)
				)
				) , 
			$this->ReturnCode);
		}



		$result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
    }
}
