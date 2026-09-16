<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
			$this->organization_id=$this->loggedUser['OID'];
		}else{
			$refer=uri_string();
			redirect(URL::get_link('loginURL').'?refer='.$refer);
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		parent::__construct();
	}
	public function index()
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->load->library('pagination');
			
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
			));
			if($this->access_member_type=='F'){
				$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			
			$srch = $this->input->get();
			$limit = !empty($srch['per_page']) ? $srch['per_page'] : 0;
			$offset = 10;
			if($this->access_member_type=='F'){
				$srch['contractor_id'] = $this->member_id;
			}else{
				$srch['owner_id'] = $this->member_id;
			}
			$srch['contract_status'] = 1;
			$show='all';
			if($this->input->get('show')){
				$show=$srch['show'];
			}
			$this->data['show']=$show;

			$this->data['list'] = $this->contract_model->getContracts($srch, $limit, $offset);
			$this->data['list_total'] = $this->contract_model->getContracts($srch, $limit, $offset, FALSE);
			
			/*Pagination Start*/
			$config['base_url'] = base_url('contract/index');
			$config['page_query_string'] = TRUE;
			$config['reuse_query_string'] = TRUE;
			$config['total_rows'] = $this->data['list_total'];
			$config['per_page'] = $offset;
			
			$config['full_tag_open'] = '<div class="pagination-container"><nav class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></nav></div>';
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<li class="waves-effect">';
			$config['first_tag_close'] = '</li>';
			$config['num_tag_open'] = '<li class="waves-effect">';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li><a class='current-page' href='javascript:void(0)'>";
			$config['cur_tag_close'] = '</a></li>';
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = "<li class='last waves-effect'>";
			$config['last_tag_close'] = '</li>';
			$config['next_link'] = '<i class="icon-material-outline-keyboard-arrow-right"></i>';
			$config['next_tag_open'] = '<li class="waves-effect">';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = '<i class="icon-material-outline-keyboard-arrow-left"></i>';
			$config['prev_tag_open'] = '<li class="waves-effect">';
			$config['prev_tag_close'] = '</li>';  
			
			$this->pagination->initialize($config);
			$this->data['links'] = $this->pagination->create_links();
			$this->layout->view('contract-list', $this->data);
		}
	}
	public function offerlist()
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->load->library('pagination');
			
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
			));
			if($this->access_member_type=='F'){
				$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			
			$srch = $this->input->get();
			$limit = !empty($srch['per_page']) ? $srch['per_page'] : 0;
			$offset = 10;
			if($this->access_member_type=='F'){
				$srch['contractor_id'] = $this->member_id;
			}else{
				$srch['owner_id'] = $this->member_id;
			}
			$this->data['list'] = $this->contract_model->getContracts($srch, $limit, $offset);
			$this->data['list_total'] = $this->contract_model->getContracts($srch, $limit, $offset, FALSE);
			
			/*Pagination Start*/
			$config['base_url'] = base_url('contract/offerlist');
			$config['page_query_string'] = TRUE;
			$config['reuse_query_string'] = TRUE;
			$config['total_rows'] = $this->data['list_total'];
			$config['per_page'] = $offset;
			
			$config['full_tag_open'] = '<div class="pagination-container"><nav class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></nav></div>';
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<li class="waves-effect">';
			$config['first_tag_close'] = '</li>';
			$config['num_tag_open'] = '<li class="waves-effect">';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li><a class='current-page' href='javascript:void(0)'>";
			$config['cur_tag_close'] = '</a></li>';
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = "<li class='last waves-effect'>";
			$config['last_tag_close'] = '</li>';
			$config['next_link'] = '<i class="icon-material-outline-keyboard-arrow-right"></i>';
			$config['next_tag_open'] = '<li class="waves-effect">';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = '<i class="icon-material-outline-keyboard-arrow-left"></i>';
			$config['prev_tag_open'] = '<li class="waves-effect">';
			$config['prev_tag_close'] = '</li>';  
			
			$this->pagination->initialize($config);
			$this->data['links'] = $this->pagination->create_links();
			$this->layout->view('offer-list', $this->data);
		}
	}
	public function offer($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
			));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'offer','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				$this->data['contractDetails']->milestone=getData(array(
					'select'=>'m.contract_milestone_id,m.milestone_title,m.milestone_amount,m.milestone_amount,m.milestone_due_date,m.is_approved,m.approved_date',
					'table'=>'project_contract_milestone m',
					'where'=>array('m.contract_id'=>$contract_id),
				));
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractDetails']->owner=$owner['project_owner'];
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name',
					'table'=>'member m',
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractDetails']->owner=$owner['project_owner'];
				
				$this->data['contractDetails']->owner->statistics=getData(array(
					'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
					'table'=>'member_statistics as m_s',
					'where'=>array('m_s.member_id'=>$owner['project_owner']->member_id),
					'single_row'=>TRUE
				));
				
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name,mb.member_heading,ms.avg_rating',
					'table'=>'member m',
					'join'=>array(
						array('table'=>'member_basic as mb','on'=>'m.member_id=mb.member_id','position'=>'left'),
						array('table'=>'member_statistics as ms','on'=>'m.member_id=ms.member_id','position'=>'left')
					),
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));				
				
				$this->data['current_member']=$this->member_id;
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('offer-details', $this->data);
		}
	}
	public function offeraction(){
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
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
				$contract_id_enc=post('offer_id');
				$action_type=post('action_type');
				if(!in_array($action_type,array('accept','deny'))){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'action_type';
					$msg['errors'][$i]['message'] = 'invalid action';
	   				$i++;
				}
				$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'offer_action'));
				if($this->data['contractDetails']){
					if($this->data['contractDetails']->owner_id==$this->member_id || $this->data['contractDetails']->contractor_id==$this->member_id){
						if($this->member_id!=$this->data['contractDetails']->offer_by){
							if($action_type=='accept'){
								$contract_status=1;
								$msg['redirect'] = get_link('ContractList');
								$template="project-offer-accepted";
							}else{
								$contract_status=2;
								$msg['redirect'] = get_link('OfferList');
								$template="project-offer-rejected";
							}
							updateTable('project_contract',array('contract_status'=>$contract_status),array('contract_id'=>$this->data['contractDetails']->contract_id));
							
							$projectDetails=getProjectDetails($this->data['contractDetails']->project_id,array('project','project_owner'));
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
								'OFFER_URL' =>get_link('OfferDetails').'/'.md5($this->data['contractDetails']->contract_id),
							);
							SendMail($to,$template,$data_parse);
							
							$this->notification_model->log(
								$template, // template key
								$data_parse, // template data
								$this->config->item('OfferDetails').'/'.md5($this->data['contractDetails']->contract_id), // link (without base_url)
								$projectDetails['project_owner']->member_id, // notification to,
								$this->member_id // notification_from
							);
							
							$msg['status'] = 'OK';
						}else{
							$msg['status'] = 'FAIL';
			    			$msg['errors'][$i]['id'] = 'offer_id';
							$msg['errors'][$i]['message'] = 'invalid offer id';
			   				$i++;
						}
						
					}else{
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = 'offer_id';
						$msg['errors'][$i]['message'] = 'invalid offer id';
		   				$i++;
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id';
	   				$i++;
				}
				
			}
			
			
			
		
		}
		unset($_POST);
		echo json_encode($msg);		
		}
	}
	public function details($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
			));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_details','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				if($this->data['contractDetails']->contract_status!=1){
					redirect(get_link('OfferDetails').'/'.$contract_id_enc);
				}elseif($this->data['contractDetails']->is_hourly==1){
					redirect(get_link('ContractDetailsHourly').'/'.$contract_id_enc);
				}
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				$this->data['contractDetails']->milestone=getData(array(
					'select'=>'m.contract_milestone_id,m.milestone_title,m.milestone_amount,m.milestone_amount,m.milestone_due_date,m.is_approved,m.approved_date,m.is_escrow',
					'table'=>'project_contract_milestone m',
					'where'=>array('m.contract_id'=>$contract_id),
				));
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractDetails']->owner=$owner['project_owner'];
				
				$this->data['contractDetails']->owner->statistics=getData(array(
					'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
					'table'=>'member_statistics as m_s',
					'where'=>array('m_s.member_id'=>$owner['project_owner']->member_id),
					'single_row'=>TRUE
				));
				
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name,mb.member_heading,ms.avg_rating',
					'table'=>'member m',
					'join'=>array(
						array('table'=>'member_basic as mb','on'=>'m.member_id=mb.member_id','position'=>'left'),
						array('table'=>'member_statistics as ms','on'=>'m.member_id=ms.member_id','position'=>'left')
					),
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));
				
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
				
				
				$this->data['contractDetails']->in_escrow=$this->contract_model->getEscrowAmount($project_id,$contract_id);
				$this->data['contractDetails']->disputed=$this->contract_model->getMilestoneDisputed($project_id,$contract_id);
				$this->data['contractDetails']->milestone_paid=$this->contract_model->getMilestonePaid($project_id,$contract_id)-$this->data['contractDetails']->disputed;
				$this->data['contractDetails']->not_started=$this->contract_model->getMilestoneNotStart($project_id,$contract_id);
				$this->data['contractDetails']->balance_remain=$this->data['contractDetails']->contract_amount-$this->data['contractDetails']->milestone_paid-$this->data['contractDetails']->disputed-$this->data['contractDetails']->not_started;

				$this->data['contractDetails']->refund_earn=$this->contract_model->getMilestoneDisputedRefund($project_id,$contract_id);
				$total_earn=$this->data['contractDetails']->milestone_paid;
				if($this->data['is_owner']){
					$total_earn=$total_earn+$this->data['contractDetails']->disputed-$this->data['contractDetails']->refund_earn;
				}else{
					if($this->data['contractDetails']->refund_earn>0){
						$total_earn=$total_earn+$this->data['contractDetails']->disputed-$this->data['contractDetails']->refund_earn;
					}
					
					
					
				}
				$this->data['contractDetails']->earning=$total_earn;
				
				$this->data['pending_contract']=$this->db->where('contract_id',$contract_id)->where('is_escrow',1)->where('is_approved <>',1)->from('project_contract_milestone')->count_all_results();
				$this->data['not_started_contract']=$this->db->where('contract_id',$contract_id)->where('is_escrow',0)->from('project_contract_milestone')->count_all_results();
				$this->data['reviews']=get_contract_view($contract_id,$this->member_id);

				
				$this->data['contractDetails']->disputed_milestone=getData(array(
					'select'=>'m.contract_milestone_id,m.milestone_title,m.milestone_amount,m.milestone_amount,m.milestone_due_date,m.is_approved,m.approved_date,m.is_escrow,d.project_contract_dispute_id,d.dispute_status,d.project_contract_dispute_id,d.dispute_date,d.commission_amount,d.owner_amount,d.contractor_amount',
					'table'=>'project_contract_milestone m',
					'join'=>array(
						array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left')
					),
					'where'=>array('d.contract_id'=>$contract_id),
					'group'=>'m.contract_milestone_id'
				));
				
				
				
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('contract-details', $this->data);
		}
	}
	public function message($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->layout->set_js(array(
				'jquery.nicescroll.min.js',
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
			));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_message','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				if($this->data['contractDetails']->contract_status!=1){
					redirect(get_link('OfferDetails').'/'.$contract_id_enc);
				}
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractDetails']->owner=$owner['project_owner'];
				$this->data['contractDetails']->owner->statistics=getData(array(
					'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
					'table'=>'member_statistics as m_s',
					'where'=>array('m_s.member_id'=>$owner['project_owner']->member_id),
					'single_row'=>TRUE
				));
				
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name,mb.member_heading,ms.avg_rating',
					'table'=>'member m',
					'join'=>array(
						array('table'=>'member_basic as mb','on'=>'m.member_id=mb.member_id','position'=>'left'),
						array('table'=>'member_statistics as ms','on'=>'m.member_id=ms.member_id','position'=>'left')
					),
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));
				
				
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
			}else{
				redirect(get_link('dashboardURL'));
			}
			
			
			if($this->data['is_owner']){
				$receiver_id=$this->data['contractDetails']->contractor_id;
			}else{
				$receiver_id=$owner['project_owner']->member_id;
			}
			$member_ids=array($this->member_id,$receiver_id);
			/* Message section */
			$this->load->model('message/message_model');
			$selected_conversation_id=$this->message_model->getConversationID($project_id,$member_ids,1);
			$this->data['login_member'] = $this->message_model->getMessageUser($this->member_id);
			//$selected_conversation_id = 3;
			if($selected_conversation_id){
				$this->data['active_chat'] = $this->message_model->getConversationUserById($selected_conversation_id, $this->member_id);
			}else{
				$this->data['active_chat'] = null;
			}
			
			$this->layout->view('contract-message', $this->data);
		}
	}
	public function term($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
			));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_term','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				if($this->data['contractDetails']->contract_status!=1){
					redirect(get_link('OfferDetails').'/'.$contract_id_enc);
				}
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractDetails']->owner=$owner['project_owner'];
				$this->data['contractDetails']->owner->statistics=getData(array(
					'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
					'table'=>'member_statistics as m_s',
					'where'=>array('m_s.member_id'=>$owner['project_owner']->member_id),
					'single_row'=>TRUE
				));
				
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name,mb.member_heading,ms.avg_rating',
					'table'=>'member m',
					'join'=>array(
						array('table'=>'member_basic as mb','on'=>'m.member_id=mb.member_id','position'=>'left'),
						array('table'=>'member_statistics as ms','on'=>'m.member_id=ms.member_id','position'=>'left')
					),
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));
				
				
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
				$this->data['pending_contract']=$this->db->where('contract_id',$contract_id)->where('is_escrow',1)->where('is_approved <>',1)->from('project_contract_milestone')->count_all_results();
				$this->data['reviews']=get_contract_view($contract_id,$this->member_id);
				
				
				
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('contract-term', $this->data);
		}
	}
	public function milestone($contract_milestone_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'upload-drag-file.js',
				'mycustom.js',
			));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractMilestoneDetails'] = getData(array(
			'select'=>'p.project_id,p.project_url,c.contract_id,c.contract_title,o.member_id as owner_id,c.contractor_id,m.contract_milestone_id,m.milestone_title,m.is_approved,m.milestone_due_date,m.approved_date,m.is_escrow',
			'table'=>'project_contract_milestone m',
			'join'=>array(
				array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
				array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
				array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
			),
			'where'=>array('md5(m.contract_milestone_id)'=>$contract_milestone_id_enc,'c.contract_status'=>1),
			'single_row'=>TRUE
			));
			if($this->data['contractMilestoneDetails']){
				$contract_id=$this->data['contractMilestoneDetails']->contract_id;
				$project_id=$this->data['contractMilestoneDetails']->project_id;
				$owner_id=$this->data['contractMilestoneDetails']->owner_id;
				$contractor_id=$this->data['contractMilestoneDetails']->contractor_id;
				$contract_milestone_id=$this->data['contractMilestoneDetails']->contract_milestone_id;
				if(!in_array($this->member_id,array($contractor_id,$owner_id))){
					redirect(get_link('ContractList'));
				}
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractMilestoneDetails']->owner=$owner['project_owner'];
				$this->data['contractMilestoneDetails']->owner->statistics=getData(array(
					'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
					'table'=>'member_statistics as m_s',
					'where'=>array('m_s.member_id'=>$owner['project_owner']->member_id),
					'single_row'=>TRUE
				));
				
				$this->data['contractMilestoneDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name,mb.member_heading,ms.avg_rating',
					'table'=>'member m',
					'join'=>array(
						array('table'=>'member_basic as mb','on'=>'m.member_id=mb.member_id','position'=>'left'),
						array('table'=>'member_statistics as ms','on'=>'m.member_id=ms.member_id','position'=>'left')
					),
					'where'=>array('m.member_id'=>$this->data['contractMilestoneDetails']->contractor_id),
					'single_row'=>true
				));

				
				$this->data['contractMilestoneDetails']->submission=getData(array(
					'select'=>'s.submission_id,s.contract_milestone_id,s.submission_description,s.submission_attachment,s.submission_date,s.is_approved,s.update_date,s.change_reason',
					'table'=>'project_contract_milestone_submission s',
					'where'=>array('s.contract_milestone_id'=>$contract_milestone_id),
					'order'=>array(array('s.submission_id','desc'))
				));
				
				$this->data['is_owner']=0;
				if($owner_id==$this->member_id){
					$this->data['is_owner']=1;
				}
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('milestone-details', $this->data);
		}
	}
	public function submitwork_form_check(){
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('details', 'details', 'required|trim|xss_clean');
			$this->form_validation->set_rules('mid', 'mid', 'required|trim|xss_clean');
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
				$contract_milestone_id_enc=post('mid');
				$this->data['contractDetails'] = getData(array(
					'select'=>'c.contract_id,m.contract_milestone_id,c.project_id,c.contract_title,m.milestone_title',
					'table'=>'project_contract_milestone m',
					'join'=>array(
						array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
					),
					'where'=>array('md5(m.contract_milestone_id)'=>$contract_milestone_id_enc,'c.contractor_id'=>$this->member_id),
					'single_row'=>TRUE
					));
					//echo $this->db->last_query();
				if($this->data['contractDetails']){
					$contract_milestone_id=$this->data['contractDetails']->contract_milestone_id;
					updateTable('project_contract_milestone_submission',array('is_approved'=>2,'update_date'=>date('Y-m-d H:i:s'),'change_reason'=>'auto cancelled'),array('contract_milestone_id'=>$contract_milestone_id,'is_approved'=>0));
					$project_contract_milestone_submission=array(
					'contract_milestone_id'=>$contract_milestone_id,
					'submission_description'=>post('details'),
					'submission_attachment'=>NULL,
					'submission_date'=>date('Y-m-d H:i:s'),
					);
					$attahment=array();
					if(post('projectfile')){
						$projectfiles=post('projectfile');
						foreach($projectfiles as $file){
							$file_data=json_decode($file);
							if($file_data){
								if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
									rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."projects-files/milestone-submission/".$file_data->file_name);
									$attahment[]=array(
									'name'=>$file_data->original_name,
									'file'=>$file_data->file_name,
									);
								}
							}
						}
					}
					if($attahment){
						$project_contract_milestone_submission['submission_attachment']=json_encode($attahment);
					}
					$submission_id=insert_record('project_contract_milestone_submission',$project_contract_milestone_submission,TRUE);
					if($submission_id){
						
						
						$projectDetails=getProjectDetails($this->data['contractDetails']->project_id,array('project','project_owner'));
						$to=getFieldData('member_email','member','member_id',$projectDetails['project_owner']->member_id);
						$RECEIVER_NAME=$projectDetails['project_owner']->member_name;
						if($projectDetails['project_owner']->organization_name){
							$RECEIVER_NAME=$projectDetails['project_owner']->organization_name;
						}
						$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
						$template='work-submission';
						$data_parse = array(
							'SENDER_NAME' =>$SENDER_NAME,
							'RECEIVER_NAME' =>$RECEIVER_NAME,
							'TITLE' =>$projectDetails['project']->project_title,
							'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
							'MILESTONE_TITLE' =>$this->data['contractDetails']->milestone_title,
							'WORK_URL' =>get_link('MilestoneDetails').'/'.md5($contract_milestone_id),
						);
						SendMail($to,$template,$data_parse);
						
						$this->notification_model->log(
							$template, // template key
							$data_parse, // template data
							$this->config->item('MilestoneDetails').'/'.md5($contract_milestone_id), // link (without base_url)
							$projectDetails['project_owner']->member_id, // notification to,
							$this->member_id // notification_from
						);
								
						$msg['status'] = 'OK';
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id';
	   				$i++;
				}
				
			}
			
			
			
		
		}
		unset($_POST);
		echo json_encode($msg);		
		}
	}
	public function workaction(){
		$this->load->model('contract_model');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('mid', 'mid', 'required|trim|xss_clean');
			$this->form_validation->set_rules('action_type', 'action_type', 'required|trim|xss_clean');
			$this->form_validation->set_rules('reason', 'reason', 'trim|xss_clean');
			$this->form_validation->set_rules('sid', 'sid', 'trim|xss_clean');
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
				$contract_milestone_id_enc=post('mid');
				$action_type=post('action_type');
				if(!in_array($action_type,array('accept','deny'))){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'action_type';
					$msg['errors'][$i]['message'] = 'invalid action';
	   				$i++;
				}
				$this->data['contractDetails'] = getData(array(
					'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,m.contract_milestone_id,m.milestone_title',
					'table'=>'project_contract_milestone m',
					'join'=>array(
						array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
						array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
						array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
					),
					'where'=>array('md5(m.contract_milestone_id)'=>$contract_milestone_id_enc,'c.contract_status'=>1),
					'single_row'=>TRUE
					));
				if($this->data['contractDetails']){
					$contract_milestone_id=$this->data['contractDetails']->contract_milestone_id;
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
						
					$in_escrow=$this->contract_model->getEscrowAmount($project_id,$contract_id);	
					$wallet_balance=getFieldData('balance','wallet','user_id',$this->member_id);
					$contract_amount=getFieldData('milestone_amount','project_contract_milestone','contract_milestone_id',$contract_milestone_id);
					$tranferto_escrow=$is_process_escrow=0;
					if($in_escrow>=$contract_amount){
						
					
					}else{
						$remain=$contract_amount-$in_escrow;
						if($wallet_balance>=$remain){
							$is_process_escrow=1;
							$tranferto_escrow=$remain;
						}else{
							$msg['status'] = 'FAIL';
							$msg['popup'] = 'fund';
							$msg['amount_due'] = $remain-$wallet_balance;
			    			$msg['errors'][$i]['id'] = 'fund';
							$msg['errors'][$i]['message'] = 'Insufficient funds';
			   				$i++;
						}	
					}
					if($i==0){
						
					if($this->data['contractDetails']->owner_id==$this->member_id){
						
						if($action_type=='accept'){
							$contract_status=1;
							updateTable('project_contract_milestone_submission',array('is_approved'=>1,'update_date'=>date('Y-m-d H:i:s'),'change_reason'=>NULL),array('contract_milestone_id'=>$contract_milestone_id,'submission_id'=>post('sid')));
							updateTable('project_contract_milestone',array('is_approved'=>1,'approved_date'=>date('Y-m-d H:i:s')),array('contract_milestone_id'=>$contract_milestone_id));
							
							if($is_process_escrow){
								$this->contract_model->addFundToEscrow($project_id,$this->member_id,$contract_id,$tranferto_escrow);
							}
							
							$this->contract_model->ReleaseFundForContract($contract_milestone_id);
							
							
							$this->load->helper('invoice');
							$total_amount_to_bill=$contract_amount;
							$total_amount_to_bill_round=round($total_amount_to_bill);
							$round_up_val=displayamount($total_amount_to_bill_round,2)-displayamount($total_amount_to_bill,2);
							$invdata=array(
							'invoice_type'=>'invoice',
							'round_up_val'=>$round_up_val,
							'recipient_organization_id'=>$this->organization_id,
							);
							$invoice_id=create_invoice_invoice($this->data['contractDetails']->contractor_id,$this->member_id,$invdata);
							if($invoice_id){
								$invoice_row_unit='pcs';
								$invoice_row_amount=1;
								$invoice_row_unit_price=$contract_amount;
								$invoicerow_array=array();
								$invoicerow_array[]=array('invoice_row_text'=>$this->data['contractDetails']->milestone_title,'invoice_row_amount'=>$invoice_row_amount,'invoice_row_unit'=>$invoice_row_unit,'invoice_row_unit_price'=>$invoice_row_unit_price);
								add_invoice_row($invoice_id,$invoicerow_array);
								
								$project_contract_invoice=array(
								'project_id'=>$project_id,
								'invoice_id'=>$invoice_id,
								'contract_id'=>$contract_id,
								);
								insert_record('project_contract_invoice',$project_contract_invoice);
								updateTable('invoice',array('invoice_status'=>1,'paid_amount'=>$contract_amount),array('invoice_id'=>$invoice_id));
							
							}
							
							
							
							
						}else{
							$contract_status=2;
							updateTable('project_contract_milestone_submission',array('is_approved'=>2,'update_date'=>date('Y-m-d H:i:s'),'change_reason'=>post('reason')),array('contract_milestone_id'=>$contract_milestone_id,'submission_id'=>post('sid')));
						}
						
						$projectDetails=getProjectDetails($project_id,array('project','project_owner'));
						if($action_type=='accept'){
							$template="work-submission-accepted";
						}else{
							$template="work-submission-rejected";
						}
						$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->contractor_id);
						$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->contractor_id);
						$SENDER_NAME=$projectDetails['project_owner']->member_name;
						if($projectDetails['project_owner']->organization_name){
							$SENDER_NAME=$projectDetails['project_owner']->organization_name;
						}
						$data_parse = array(
							'SENDER_NAME' =>$SENDER_NAME,
							'RECEIVER_NAME' =>$RECEIVER_NAME,
							'TITLE' =>$projectDetails['project']->project_title,
							'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
							'MILESTONE_TITLE' =>$this->data['contractDetails']->milestone_title,
							'WORK_URL' =>get_link('MilestoneDetails').'/'.md5($contract_milestone_id),
						);
						if($action_type=='deny'){
							$data_parse['REJECT_REASON']=post('reason');	
						}
						SendMail($to,$template,$data_parse);
					
						$this->notification_model->log(
							$template, // template key
							$data_parse, // template data
							$this->config->item('MilestoneDetails').'/'.md5($contract_milestone_id), // link (without base_url)
							$this->data['contractDetails']->contractor_id, // notification to,
							$this->member_id // notification_from
						);
						
						
						
						$msg['status'] = 'OK';

					}else{
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = 'offer_id';
						$msg['errors'][$i]['message'] = 'invalid offer id';
		   				$i++;
					}
					
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id';
	   				$i++;
				}
				
			}
			
			
			
		
		}
		unset($_POST);
		echo json_encode($msg);		
		}
	}
	public function workstart(){
		$this->load->model('contract_model');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('mid', 'mid', 'required|trim|xss_clean');
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
				$contract_milestone_id_enc=post('mid');
				$this->data['contractDetails'] = getData(array(
					'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,m.contract_milestone_id,m.milestone_title',
					'table'=>'project_contract_milestone m',
					'join'=>array(
						array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
						array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
						array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
					),
					'where'=>array('md5(m.contract_milestone_id)'=>$contract_milestone_id_enc,'c.contract_status'=>1),
					'single_row'=>TRUE
					));
				if($this->data['contractDetails']){
					$contract_milestone_id=$this->data['contractDetails']->contract_milestone_id;
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
						
					$wallet_balance=getFieldData('balance','wallet','user_id',$this->member_id);
					$contract_amount=getFieldData('milestone_amount','project_contract_milestone','contract_milestone_id',$contract_milestone_id);
					$tranferto_escrow=$is_process_escrow=0;
					
					$remain=$contract_amount;
					if($wallet_balance>=$remain){
						$is_process_escrow=1;
						$tranferto_escrow=$remain;
					}else{
						$msg['status'] = 'FAIL';
						$msg['popup'] = 'fund';
						$msg['amount_due'] = $remain-$wallet_balance;
		    			$msg['errors'][$i]['id'] = 'fund';
						$msg['errors'][$i]['message'] = 'Insufficient funds';
		   				$i++;
					}	
					if($i==0){
						if($this->data['contractDetails']->owner_id==$this->member_id){
							if($is_process_escrow){
								$this->contract_model->addFundToEscrow($project_id,$this->member_id,$contract_id,$tranferto_escrow);
								updateTable('project_contract_milestone',array('is_escrow'=>1),array('contract_milestone_id'=>$contract_milestone_id));
							}
							$projectDetails=getProjectDetails($this->data['contractDetails']->project_id,array('project','project_owner'));
							$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->contractor_id);
							$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->contractor_id);
							$SENDER_NAME=$projectDetails['project_owner']->member_name;
							if($projectDetails['project_owner']->organization_name){
								$SENDER_NAME=$projectDetails['project_owner']->organization_name;
							}
							$template='new-milestone-initiate';
							$data_parse=array(
							'SENDER_NAME' =>$SENDER_NAME,
							'RECEIVER_NAME' =>$RECEIVER_NAME,
							'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
							'MILESTONE_TITLE' =>$this->data['contractDetails']->milestone_title,
							'TITLE' =>$projectDetails['project']->project_title,
							'WORK_URL' =>get_link('ContractDetails').'/'.md5($contract_id),
							);
							SendMail($to,$template,$data_parse);
							$this->notification_model->log(
								$template, // template key
								$data_parse, // template data
								$this->config->item('ContractDetails').'/'.md5($contract_id), // link (without base_url)
								$this->data['contractDetails']->contractor_id, // notification to,
								$this->member_id // notification_from
							);
							$msg['status'] = 'OK';

						}else{
							$msg['status'] = 'FAIL';
			    			$msg['errors'][$i]['id'] = 'offer_id';
							$msg['errors'][$i]['message'] = 'invalid offer id';
			   				$i++;
						}
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id';
	   				$i++;
				}
			}
		}
		unset($_POST);
		echo json_encode($msg);		
		}
	}
	public function addfundtoescrow(){
		checkrequestajax();
		if($this->access_member_type!='C'){
			redirect(get_link('dashboardURL'));
		}
		$this->load->model('contract_model');
		$this->load->library('form_validation');
		$i=0;
		$msg=array();
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			if($this->input->post()){
			$this->form_validation->set_rules('cid', 'cid', 'required|trim|xss_clean');
			$this->form_validation->set_rules('amount', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
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
				$contract_id_enc=post('cid');
				$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_addfund','member_id'=>$this->member_id));
				if($this->data['contractDetails']){
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
					$wallet_balance=getFieldData('balance','wallet','user_id',$this->member_id);
					$add_amount=post('amount');	
					
					$tranferto_escrow=$is_process_escrow=0;
					if($wallet_balance>=$add_amount){
						$is_process_escrow=1;
						$tranferto_escrow=$add_amount;
					}else{
						$remain=$add_amount-$wallet_balance;
						$msg['status'] = 'FAIL';
						$msg['popup'] = 'fund';
						$msg['amount_due'] = $remain;
		    			$msg['errors'][$i]['id'] = 'fund';
						$msg['errors'][$i]['message'] = 'Insufficient funds';
		   				$i++;
					}	
					if($i==0){
						
					if($this->data['contractDetails']->owner_id==$this->member_id){
						if($is_process_escrow){
								$this->contract_model->addFundToEscrow($project_id,$this->member_id,$contract_id,$tranferto_escrow);
						}
						$msg['status'] = 'OK';

					}else{
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = 'offer_id';
						$msg['errors'][$i]['message'] = 'invalid offer id';
		   				$i++;
					}
					
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id';
	   				$i++;
				}	
			}
		}
			unset($_POST);
			echo json_encode($msg);		
		}
	}

	public function endcontract($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
				'rating/jquery.rateyo.js',
			));
			$this->layout->set_css(array(
			'rating/jquery.rateyo.css'
			));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_term','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				if($this->data['contractDetails']->contract_status!=1){
					redirect(get_link('OfferDetails').'/'.$contract_id_enc);
				}
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractDetails']->owner=$owner['project_owner'];
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name',
					'table'=>'member m',
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));
				
				
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
				if($this->data['contractDetails']->is_hourly){
					$unpaid_invoice=getData(array(
						'select'=>'i.invoice_id',
						'table'=>'project_contract_invoice p_i',
						'join'=>array(
							array('table'=>'invoice as i','on'=>'p_i.invoice_id=i.invoice_id','position'=>'left')
						),
						'where'=>array('p_i.contract_id'=>$contract_id,'i.invoice_status'=>0),
						'return_count'=>true
					));
					$unpaid_worklog=getData(array(
						'select'=>'l.log_id',
						'table'=>'project_contract_hour_log l',
						'where'=>array('l.contract_id'=>$contract_id,'l.log_status'=>1,'l.invoice_id'=>NULL),
						'return_count'=>true
					));
					$this->data['pending_contract']=$unpaid_worklog+$unpaid_invoice;
				}else{
				$this->data['pending_contract']=$this->db->where('contract_id',$contract_id)->where('is_escrow',1)->where('is_approved <>',1)->from('project_contract_milestone')->count_all_results();	
				}
				
				$this->data['reviews']=get_contract_view($contract_id,$this->member_id);
				if($this->data['pending_contract']){
					if($this->data['contractDetails']->is_hourly){
						redirect(get_link('ContractDetailsHourly').'/'.$contract_id_enc);
					}else{
						redirect(get_link('ContractDetails').'/'.$contract_id_enc);
					}
					
				}else{
					if($this->data['reviews']){
						if($this->data['reviews']['review_by_me']){
							if($this->data['contractDetails']->is_hourly){
								redirect(get_link('ContractDetailsHourly').'/'.$contract_id_enc);
							}else{
								redirect(get_link('ContractDetails').'/'.$contract_id_enc);
							}
						}
					}
				}
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('contract-end', $this->data);
		}
	}
	public function endcontractcheck(){
		checkrequestajax();
		$this->load->library('form_validation');
		$i=0;
		$msg=array();
		$contract_id_enc=post('c_id');
		$this->data['contractDetails'] = get_contract_details($contract_id_enc);
		if($this->data['contractDetails']){
			$project_id=$this->data['contractDetails']->project_id;
			$contract_id=$this->data['contractDetails']->contract_id;
			$owner_id=$this->data['contractDetails']->owner_id;
			$contractor_id=$this->data['contractDetails']->contractor_id;
			if($owner_id==$this->member_id){
				$review_to=$contractor_id;
				
			}else{
				$review_to=$owner_id;
			}
			if(in_array($this->member_id,array($owner_id,$contractor_id))){
				if($this->data['contractDetails']->is_hourly){
					$unpaid_invoice=getData(array(
						'select'=>'i.invoice_id',
						'table'=>'project_contract_invoice p_i',
						'join'=>array(
							array('table'=>'invoice as i','on'=>'p_i.invoice_id=i.invoice_id','position'=>'left')
						),
						'where'=>array('p_i.contract_id'=>$contract_id,'i.invoice_status'=>0),
						'return_count'=>true
					));
					$unpaid_worklog=getData(array(
						'select'=>'l.log_id',
						'table'=>'project_contract_hour_log l',
						'where'=>array('l.contract_id'=>$contract_id,'l.log_status'=>1,'l.invoice_id'=>NULL),
						'return_count'=>true
					));
					$this->data['pending_contract']=$unpaid_worklog+$unpaid_invoice;
				}else{
					$this->data['pending_contract']=$this->db->where('contract_id',$contract_id)->where('is_escrow',1)->where('is_approved <>',1)->from('project_contract_milestone')->count_all_results();	
				}
				
				
				
				
				$reviews=get_contract_view($contract_id,$this->member_id);
				if($this->input->post()){
					$this->form_validation->set_rules('c_id', 'cid', 'required|trim|xss_clean');
					$this->form_validation->set_rules('skills', 'skills', 'required|trim|xss_clean|is_natural|less_than_equal_to[5]');
					$this->form_validation->set_rules('quality', 'quality', 'required|trim|xss_clean|is_natural|less_than_equal_to[5]');
					$this->form_validation->set_rules('availability', 'availability','required|trim|xss_clean|is_natural|less_than_equal_to[5]');
					$this->form_validation->set_rules('deadlines', 'deadlines', 'required|trim|xss_clean|is_natural|less_than_equal_to[5]');
					$this->form_validation->set_rules('communication', 'communication', 'required|trim|xss_clean|is_natural|less_than_equal_to[5]');
					$this->form_validation->set_rules('cooperation', 'cooperation', 'required|trim|xss_clean|is_natural|less_than_equal_to[5]');
					$this->form_validation->set_rules('comment', 'comment', 'required|trim|xss_clean|max_length[1000]');
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
						if($reviews){
							if($reviews['review_by_me']){
								$msg['status'] = 'FAIL';
				    			$msg['popup'] = 'exists';
								$msg['errors']= 'Already Submited';
				   				$i++;
							}
						}
						if($this->data['pending_contract']){
							if($reviews['review_by_me']){
								$msg['status'] = 'FAIL';
				    			$msg['popup'] = 'unpaid';
								$msg['errors']= 'Unpaid Payment';
				   				$i++;
							}
						}
					}
					
					if($i==0){
						$contract_reviews=array(
						'project_id'=>$project_id,
						'contract_id'=>$contract_id,
						'review_by'=>$this->member_id,
						'review_to'=>$review_to,
						'for_skills'=>post('skills'),
						'for_quality'=>post('quality'),
						'for_availability'=>post('availability'),
						'for_deadlines'=>post('deadlines'),
						'for_communication'=>post('communication'),
						'for_cooperation'=>post('cooperation'),
						'review_status'=>1,
						'is_display_public'=>0,
						'review_comments'=>post('comment'),
						'review_date'=>date('Y-m-d H:i:s'),
						);
						$total_review=$contract_reviews['for_skills']+$contract_reviews['for_quality']+$contract_reviews['for_availability']+$contract_reviews['for_deadlines']+$contract_reviews['for_communication']+$contract_reviews['for_cooperation'];
						$average_review=displayamount($total_review/6,2);
						$contract_reviews['average_review']=$average_review;
						
						$review_id=insert_record('contract_reviews',$contract_reviews,TRUE);
						if($review_id){
							$msg['status'] = 'OK';
							if($reviews){
								if($reviews['review_to_me']){
									updateTable('contract_reviews',array('is_display_public'=>1),array('contract_id'=>$contract_id));
									updateMemberRatting($owner_id);
									updateMemberRatting($contractor_id);
								}
							}else{
								updateTable('project_contract',array('is_contract_ended'=>1,'contract_end_date'=>date('Y-m-d H:i:s')),array('contract_id'=>$contract_id));
							}
							
							
							
							$to=getFieldData('member_email','member','member_id',$review_to);
							
							if($owner_id==$this->member_id){
								$SENDER_NAME=getFieldData('organization_name','organization','member_id',$this->member_id);
								if(!$SENDER_NAME){
									$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
								}
								$RECEIVER_NAME=getFieldData('member_name','member','member_id',$review_to);
							}else{
								$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
								$RECEIVER_NAME=getFieldData('organization_name','organization','member_id',$review_to);;
								if(!$RECEIVER_NAME){
									$RECEIVER_NAME=getFieldData('member_name','member','member_id',$review_to);
								}
							}
							$template='end-contract';
							$data_parse = array(
								'SENDER_NAME' =>$SENDER_NAME,
								'RECEIVER_NAME' =>$RECEIVER_NAME,
								'TITLE' =>$this->data['contractDetails']->project_title,
								'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
								'WORK_URL' =>get_link('ContractTerm').'/'.md5($contract_id),
							);
							if($this->data['contractDetails']->is_hourly){
								$data_parse['WORK_URL']=get_link('ContractTermHourly').'/'.md5($contract_id);
							}
							SendMail($to,$template,$data_parse);
							
							$url_link=$this->config->item('ContractTerm').'/'.md5($contract_id);
							if($this->data['contractDetails']->is_hourly){
								$url_link=$this->config->item('ContractTermHourly').'/'.md5($contract_id);
							}
							$this->notification_model->log(
								$template, // template key
								$data_parse, // template data
								$url_link, // link (without base_url)
								$review_to, // notification to,
								$this->member_id // notification_from
							);
						}
						
						
					}
					
				}

			}
		}
		unset($_POST);
		echo json_encode($msg);		
		
		
	}
	
	public function createdispute($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
				'rating/jquery.rateyo.js',
			));
			$this->layout->set_css(array(
			'rating/jquery.rateyo.css'
			));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_term','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				if($this->data['contractDetails']->contract_status!=1){
					redirect(get_link('OfferDetails').'/'.$contract_id_enc);
				}
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractDetails']->owner=$owner['project_owner'];
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name',
					'table'=>'member m',
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));
				
				
				$this->data['is_owner']=0;
				$is_valid=FALSE;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
					$is_valid=TRUE;
				}
				$this->data['contractDetails']->milestone=getData(array(
					'select'=>'m.contract_milestone_id,m.milestone_title,m.milestone_amount,m.milestone_amount,m.milestone_due_date,m.is_approved,m.approved_date,m.is_escrow,d.project_contract_dispute_id,d.dispute_status,d.project_contract_dispute_id',
					'table'=>'project_contract_milestone m',
					'join'=>array(
						array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left')
					),
					'where'=>array('m.contract_id'=>$contract_id),
					'group'=>'m.contract_milestone_id'
				));
				
				
				/*
				if($this->data['contractDetails']->is_hourly){
					$unpaid_invoice=getData(array(
						'select'=>'i.invoice_id',
						'table'=>'project_contract_invoice p_i',
						'join'=>array(
							array('table'=>'invoice as i','on'=>'p_i.invoice_id=i.invoice_id','position'=>'left')
						),
						'where'=>array('p_i.contract_id'=>$contract_id,'i.invoice_status'=>0),
						'return_count'=>true
					));
					$unpaid_worklog=getData(array(
						'select'=>'l.log_id',
						'table'=>'project_contract_hour_log l',
						'where'=>array('l.contract_id'=>$contract_id,'l.log_status'=>1,'l.invoice_id'=>NULL),
						'return_count'=>true
					));
					$this->data['pending_contract']=$unpaid_worklog+$unpaid_invoice;
				}else{
				$this->data['pending_contract']=$this->db->where('contract_id',$contract_id)->where('is_approved <>',1)->from('project_contract_milestone')->count_all_results();	
				}
				
				$this->data['reviews']=get_contract_view($contract_id,$this->member_id);
				if($this->data['pending_contract']){
					if($this->data['contractDetails']->is_hourly){
						redirect(get_link('ContractDetailsHourly').'/'.$contract_id_enc);
					}else{
						redirect(get_link('ContractDetails').'/'.$contract_id_enc);
					}
					
				}else{
					if($this->data['reviews']){
						if($this->data['reviews']['review_by_me']){
							if($this->data['contractDetails']->is_hourly){
								redirect(get_link('ContractDetailsHourly').'/'.$contract_id_enc);
							}else{
								redirect(get_link('ContractDetails').'/'.$contract_id_enc);
							}
						}
					}
				}
				*/
				if(!$is_valid){
					if($this->data['contractDetails']->is_hourly){
						redirect(get_link('ContractDetailsHourly').'/'.$contract_id_enc);
					}else{
						redirect(get_link('ContractDetails').'/'.$contract_id_enc);
					}
				}
				
				
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('contract-new-dispute', $this->data);
		}
	}
	public function createdisputecheck(){
		checkrequestajax();
		$this->load->model('contract_model');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
			$this->form_validation->set_rules('c_id', 'cid', 'required|trim|xss_clean');
			$this->form_validation->set_rules('mid[]', 'mid', 'required|trim|xss_clean');
			if ($this->form_validation->run() == FALSE){
				$error=validation_errors_array();
				if($error){
					foreach($error as $key=>$val){
						if($key=='mid[]'){
							$key='mid';
						}
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = $key;
						$msg['errors'][$i]['message'] = $val;
		   				$i++;
					}
				}
			}
			if($i==0){	
				$contract_id_enc=post('c_id');
				$this->data['contractDetails'] = get_contract_details($contract_id_enc);
				$project_contract_dispute_id=0;
		
		
				if($this->data['contractDetails'] && $this->data['contractDetails']->is_contract_ended!=1){
					$project_id=$this->data['contractDetails']->project_id;
					$contract_id=$this->data['contractDetails']->contract_id;
					$owner_id=$this->data['contractDetails']->owner_id;
					$contractor_id=$this->data['contractDetails']->contractor_id;
					if($owner_id==$this->member_id){
						$mid=$this->input->post('mid');
						if($mid){
							foreach($mid as $contract_milestone_id_enc){
								$contractMilestoneDetails = getData(array(
								'select'=>'m.contract_milestone_id,m.milestone_title,m.is_approved,m.is_escrow,m.milestone_amount,d.dispute_status,d.project_contract_dispute_id',
								'table'=>'project_contract_milestone m',
								'join'=>array(
									array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
									array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left')
								),
								'where'=>array('md5(m.contract_milestone_id)'=>$contract_milestone_id_enc,'c.contract_status'=>1),
								'group'=>'m.contract_milestone_id',
								'single_row'=>TRUE
								));
								if($contractMilestoneDetails){
									if($contractMilestoneDetails->is_escrow && !$contractMilestoneDetails->is_approved && !$contractMilestoneDetails->project_contract_dispute_id){
										$project_contract_dispute=array(
											'contract_id'=>$contract_id,
											'contract_milestone_id'=>$contractMilestoneDetails->contract_milestone_id,
											'contract_id'=>$contract_id,
											'dispute_status'=>0,
											'dispute_date'=>date('Y-m-d H:i:s'),
											'disputed_amount'=>$contractMilestoneDetails->milestone_amount,
										);
										$project_contract_dispute_id=insert_record('project_contract_dispute',$project_contract_dispute,TRUE);
										if($project_contract_dispute_id){
											$this->contract_model->addFundToDispute($project_id,$owner_id,$contract_id,$contractMilestoneDetails->milestone_amount,$contractMilestoneDetails->contract_milestone_id);
										}
									}
									if($project_contract_dispute_id){
										$to=getFieldData('member_email','member','member_id',$contractor_id);
										$SENDER_NAME=getFieldData('organization_name','organization','member_id',$owner_id);
										if(!$SENDER_NAME){
											$SENDER_NAME=getFieldData('member_name','member','member_id',$owner_id);
										}
										$RECEIVER_NAME=getFieldData('member_name','member','member_id',$contractor_id);
										
										$template='new-dispute-contract';
										$data_parse = array(
											'SENDER_NAME' =>$SENDER_NAME,
											'RECEIVER_NAME' =>$RECEIVER_NAME,
											'TITLE' =>$this->data['contractDetails']->project_title,
											'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
											'MILESTONE_TITLE' =>$contractMilestoneDetails->milestone_title,
											'WORK_URL' =>get_link('ContractDetails').'/'.md5($contract_id),
										);
										SendMail($to,$template,$data_parse);
										
										$this->notification_model->log(
											$template, // template key
											$data_parse, // template data
											$this->config->item('ContractDetails').'/'.md5($contract_id), // link (without base_url)
											$contractor_id, // notification to,
											$this->member_id // notification_from
										);
						
										$msg['status'] = 'OK';
									}
									
								}
							}
						}
					}else{
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = 'mid';
						$msg['errors'][$i]['message'] = 'invalid request';
		   				$i++;
					}

				}
			}	
		}	
		unset($_POST);
		echo json_encode($msg);		
		
		
	}
	public function disputedetails($contract_milestone_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('contract_model');
			$this->layout->set_js(array(
				'jquery.nicescroll.min.js',
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
				'rating/jquery.rateyo.js',
				'upload-drag-file.js',
			));
			$this->layout->set_css(array(
			'rating/jquery.rateyo.css'
			));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = getData(array(
			'select'=>'p.project_id,p.project_url,c.contract_id,c.contract_title,o.member_id as owner_id,c.contractor_id,m.contract_milestone_id,m.milestone_title,m.milestone_amount,m.is_approved,m.milestone_due_date,m.approved_date,m.is_escrow,d.project_contract_dispute_id,d.dispute_status,d.project_contract_dispute_id,d.dispute_date,d.commission_amount,d.owner_amount,d.contractor_amount,d.is_send_to_admin',
			'table'=>'project_contract_milestone m',
			'join'=>array(
				array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
				array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
				array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
				array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left'),
			),
			'where'=>array('md5(m.contract_milestone_id)'=>$contract_milestone_id_enc,'c.contract_status'=>1,'d.project_contract_dispute_id >'=>0),
			'single_row'=>TRUE
			));
			if($this->data['contractDetails']){
				$this->data['site_fee_percent']=getSiteCommissionFee($this->data['contractDetails']->contractor_id);
				$this->data['site_fee_amount']= displayamount(($this->data['contractDetails']->milestone_amount*$this->data['site_fee_percent'])/100,2);
				$this->data['remain_amount']=displayamount($this->data['contractDetails']->milestone_amount,2)-displayamount($this->data['site_fee_amount'],2);
				$contract_dispute_id=$this->data['contractDetails']->project_contract_dispute_id;
				$contract_milestone_id=$this->data['contractDetails']->contract_milestone_id;
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				
				
				$owner=getProjectDetails($project_id,array('project_owner'));
				$this->data['contractDetails']->owner=$owner['project_owner'];
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name',
					'table'=>'member m',
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));
				
				$this->data['contractDetails']->owner->statistics=getData(array(
					'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
					'table'=>'member_statistics as m_s',
					'where'=>array('m_s.member_id'=>$owner['project_owner']->member_id),
					'single_row'=>TRUE
				));
				
				$this->data['contractDetails']->contractor=getData(array(
					'select'=>'m.member_id,m.member_name,mb.member_heading,ms.avg_rating',
					'table'=>'member m',
					'join'=>array(
						array('table'=>'member_basic as mb','on'=>'m.member_id=mb.member_id','position'=>'left'),
						array('table'=>'member_statistics as ms','on'=>'m.member_id=ms.member_id','position'=>'left')
					),
					'where'=>array('m.member_id'=>$this->data['contractDetails']->contractor_id),
					'single_row'=>true
				));	
				
				$this->data['is_owner']=0;
				$is_valid=FALSE;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
					$is_valid=TRUE;
				}elseif($this->data['contractDetails']->contractor_id==$this->member_id){
					$is_valid=TRUE;
				}

				
				if(!$is_valid){
					if($this->data['contractDetails']->is_hourly){
						redirect(get_link('ContractDetailsHourly').'/'.$contract_id_enc);
					}else{
						redirect(get_link('ContractDetails').'/'.$contract_id_enc);
					}
				}
				
				
				$this->data['contractDetails']->submission=getData(array(
					'select'=>'s.submission_id,s.contract_milestone_id,s.submission_description,s.submission_attachment,s.submission_date,s.is_approved,s.commission_amount,s.owner_amount,s.contractor_amount,s.submitted_by',
					'table'=>'project_contract_dispute_submission s',
					'where'=>array('s.contract_milestone_id'=>$contract_milestone_id,'s.project_contract_dispute_id'=>$contract_dispute_id),
					'order'=>array(array('s.submission_id','desc'))
				));
				$this->data['current_user_id']=$this->member_id;
				
				
				
				if($this->data['is_owner']){
					$receiver_id=$this->data['contractDetails']->contractor_id;
				}else{
					$receiver_id=$owner['project_owner']->member_id;
				}
				$member_ids=array($this->member_id,$receiver_id);
				/* Message section */
				$this->load->model('message/message_model');
				$selected_conversation_id=$this->message_model->getConversationID($project_id,$member_ids,1);
				$this->data['login_member'] = $this->message_model->getMessageUser($this->member_id);
				//$selected_conversation_id = 3;
				if($selected_conversation_id){
					$this->data['active_chat'] = $this->message_model->getConversationUserById($selected_conversation_id, $this->member_id);
				}else{
					$this->data['active_chat'] = null;
				}
				
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('dispute-details', $this->data);
		}
	}
	public function submitdisputeoffer_form_check(){
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('to_client', 'to_client', 'required|trim|xss_clean|is_numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('to_freelancer', 'to_freelancer', 'required|trim|xss_clean|is_numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('details', 'details', 'required|trim|xss_clean');
			$this->form_validation->set_rules('mid', 'mid', 'required|trim|xss_clean');
			$this->form_validation->set_rules('dis_id', 'dis_id', 'required|trim|xss_clean');
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
				$contract_milestone_id_enc=post('mid');
				$contract_dispute_id_enc=post('dis_id');
				$this->data['contractDetails'] = getData(array(
					'select'=>'c.contract_id,m.contract_milestone_id,c.project_id,c.contract_title,m.milestone_title,m.milestone_amount,o.member_id as owner_id,c.contractor_id,d.project_contract_dispute_id',
					'table'=>'project_contract_milestone m',
					'join'=>array(
						array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
						array('table'=>'project_owner o', 'on'=>'c.project_id=o.project_id', 'position'=>'left'),
						array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left'),
					),
					'where'=>array('md5(m.contract_milestone_id)'=>$contract_milestone_id_enc,'md5(d.project_contract_dispute_id)'=>$contract_dispute_id_enc),
					'single_row'=>TRUE
					));
					//echo $this->db->last_query();
				if($this->data['contractDetails']){
					$to_client=$this->input->post('to_client');
					$to_freelancer=$this->input->post('to_freelancer');
					$total_amt_without_commission=$to_client+$to_freelancer;
					$this->data['is_owner']=0;
					$is_valid=FALSE;
					if($this->data['contractDetails']->owner_id==$this->member_id){
						$this->data['is_owner']=1;
						$is_valid=TRUE;
					}elseif($this->data['contractDetails']->contractor_id==$this->member_id){
						$is_valid=TRUE;
					}
					$site_fee_percent=getSiteCommissionFee($this->data['contractDetails']->contractor_id);
					$site_fee_amount= displayamount(($this->data['contractDetails']->milestone_amount*$site_fee_percent)/100,2);
					$remain_amount=displayamount($this->data['contractDetails']->milestone_amount,2)-displayamount($site_fee_amount,2);
					if($total_amt_without_commission==$remain_amount){
						
					}else{
						$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = 'to_client';
						$msg['errors'][$i]['message'] = 'Amount error';
		   				$i++;
		   				$msg['status'] = 'FAIL';
		    			$msg['errors'][$i]['id'] = 'to_freelancer';
						$msg['errors'][$i]['message'] = 'Amount error';
		   				$i++;
						$is_valid=FALSE;
					}
					if($is_valid){

					$contract_milestone_id=$this->data['contractDetails']->contract_milestone_id;
					$contract_dispute_id=$this->data['contractDetails']->project_contract_dispute_id;
					updateTable('project_contract_dispute_submission',array('is_approved'=>2),array('project_contract_dispute_id'=>$contract_dispute_id,'is_approved'=>0));
					
					$project_contract_dispute_submission=array(
					'project_contract_dispute_id'=>$contract_dispute_id,
					'contract_milestone_id'=>$contract_milestone_id,
					'submitted_by'=>$this->member_id,
					'submission_description'=>post('details'),
					'submission_attachment'=>NULL,
					'submission_date'=>date('Y-m-d H:i:s'),
					'commission_amount'=>$site_fee_amount,
					'owner_amount'=>$to_client,
					'contractor_amount'=>$to_freelancer,
					);
					$attahment=array();
					if(post('projectfile')){
						$projectfiles=post('projectfile');
						foreach($projectfiles as $file){
							$file_data=json_decode($file);
							if($file_data){
								if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
									rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."projects-files/dispute-submission/".$file_data->file_name);
									$attahment[]=array(
									'name'=>$file_data->original_name,
									'file'=>$file_data->file_name,
									);
								}
							}
						}
					}
					if($attahment){
						$project_contract_dispute_submission['submission_attachment']=json_encode($attahment);
					}
					$submission_id=insert_record('project_contract_dispute_submission',$project_contract_dispute_submission,TRUE);
					if($submission_id){
						
						
						$projectDetails=getProjectDetails($this->data['contractDetails']->project_id,array('project','project_owner'));
						
						if($this->data['contractDetails']->owner_id==$this->member_id){
							$receiver_id=$this->data['contractDetails']->contractor_id;
							$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->contractor_id);	
							$SENDER_NAME=getFieldData('organization_name','organization','member_id',$this->member_id);
							if(!$SENDER_NAME){
								$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
							}
							$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->contractor_id);
						}else{
							$receiver_id=$this->data['contractDetails']->owner_id;
							$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->owner_id);
							$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
							$RECEIVER_NAME=getFieldData('organization_name','organization','member_id',$this->data['contractDetails']->owner_id);
							if(!$RECEIVER_NAME){
								$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->owner_id);
							}
						}
						$template='dispute-offer';
						$data_parse = array(
							'SENDER_NAME' =>$SENDER_NAME,
							'RECEIVER_NAME' =>$RECEIVER_NAME,
							'TITLE' =>$projectDetails['project']->project_title,
							'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
							'MILESTONE_TITLE' =>$this->data['contractDetails']->milestone_title,
							'DISPUTE_URL' =>get_link('DisputeDetails').'/'.md5($contract_milestone_id),
						);
						SendMail($to,$template,$data_parse);
						
						$this->notification_model->log(
							$template, // template key
							$data_parse, // template data
							$this->config->item('DisputeDetails').'/'.md5($contract_milestone_id), // link (without base_url)
							$receiver_id, // notification to,
							$this->member_id // notification_from
						);
											
						$msg['status'] = 'OK';
					}
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id';
	   				$i++;
				}
				
			}
			
			
			
		
		}
		unset($_POST);
		echo json_encode($msg);		
		}
	}
	public function disputeaction(){
		$this->load->model('contract_model');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('mid', 'mid', 'required|trim|xss_clean');
			$this->form_validation->set_rules('action_type', 'action_type', 'required|trim|xss_clean');
			$this->form_validation->set_rules('sid', 'sid', 'trim|xss_clean');
			$this->form_validation->set_rules('dis_id', 'dis_id', 'required|trim|xss_clean');
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
				$contract_milestone_id_enc=post('mid');
				$contract_dispute_id_enc=post('dis_id');
				$sid=post('sid');
				$action_type=post('action_type');
				if(!in_array($action_type,array('accept','send_to_admin'))){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'action_type';
					$msg['errors'][$i]['message'] = 'invalid action';
	   				$i++;
				}
				$this->data['contractDetails'] = getData(array(
					'select'=>'c.contract_id,m.contract_milestone_id,c.project_id,c.contract_title,m.milestone_title,m.milestone_amount,o.member_id as owner_id,o.organization_id,c.contractor_id,d.project_contract_dispute_id,s.submitted_by,s.commission_amount,s.owner_amount,s.contractor_amount',
					'table'=>'project_contract_milestone m',
					'join'=>array(
						array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
						array('table'=>'project_owner o', 'on'=>'c.project_id=o.project_id', 'position'=>'left'),
						array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left'),
						array('table'=>'project_contract_dispute_submission as s','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left'),
					),
					'where'=>array('md5(m.contract_milestone_id)'=>$contract_milestone_id_enc,'md5(d.project_contract_dispute_id)'=>$contract_dispute_id_enc,'s.submission_id'=>$sid,'m.is_approved <>'=>1),
					'single_row'=>TRUE
					));
				if($this->data['contractDetails']){

					
					$contract_dispute_id=$this->data['contractDetails']->project_contract_dispute_id;
					$contract_milestone_id=$this->data['contractDetails']->contract_milestone_id;
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
					
					$is_valid=FALSE;
					if($this->data['contractDetails']->owner_id==$this->member_id){
						$this->data['is_owner']=1;
						$is_valid=TRUE;
					}elseif($this->data['contractDetails']->contractor_id==$this->member_id){
						$is_valid=TRUE;
					}
					if($is_valid){
						if($this->data['contractDetails']->submitted_by!=$this->member_id){

						}else{
							$i++;
						}
					}else{
						$i++;
					}
					
					if($i==0){
					$in_escrow=$this->contract_model->getEscrowAmount($project_id,$contract_id);	
					$wallet_balance=getFieldData('balance','wallet','user_id',$this->member_id);
					$contract_amount=$this->data['contractDetails']->contractor_amount;
					$tranferto_escrow=$is_process_escrow=0;	
						
					if($action_type=='accept'){
						$contract_status=1;
						updateTable('project_contract_dispute_submission',array('is_approved'=>1),array('submission_id'=>post('sid')));
						updateTable('project_contract_dispute',array('dispute_status'=>1,'commission_amount'=>$this->data['contractDetails']->commission_amount,'owner_amount'=>$this->data['contractDetails']->owner_amount,'contractor_amount'=>$this->data['contractDetails']->contractor_amount),array('project_contract_dispute_id'=>$contract_dispute_id));
						updateTable('project_contract_milestone',array('is_approved'=>1,'approved_date'=>date('Y-m-d H:i:s')),array('contract_milestone_id'=>$contract_milestone_id));
						
						
						
						$this->contract_model->ReleaseFundForContractDispute($contract_milestone_id);
						
						
						$this->load->helper('invoice');
						$total_amount_to_bill=$this->data['contractDetails']->contractor_amount;
						$total_amount_to_bill_round=round($total_amount_to_bill);
						$round_up_val=displayamount($total_amount_to_bill_round,2)-displayamount($total_amount_to_bill,2);
						$invdata=array(
						'invoice_type'=>'invoice',
						'round_up_val'=>$round_up_val,
						'recipient_organization_id'=>$this->data['contractDetails']->organization_id,
						);
						$invoice_id=create_invoice_invoice($this->data['contractDetails']->contractor_id,$this->data['contractDetails']->owner_id,$invdata);
						if($invoice_id){
							$invoice_row_unit='pcs';
							$invoice_row_amount=1;
							$invoice_row_unit_price=$contract_amount;
							$invoicerow_array=array();
							$invoicerow_array[]=array('invoice_row_text'=>$this->data['contractDetails']->milestone_title,'invoice_row_amount'=>$invoice_row_amount,'invoice_row_unit'=>$invoice_row_unit,'invoice_row_unit_price'=>$invoice_row_unit_price);
							add_invoice_row($invoice_id,$invoicerow_array);
							
							$project_contract_invoice=array(
							'project_id'=>$project_id,
							'invoice_id'=>$invoice_id,
							'contract_id'=>$contract_id,
							);
							insert_record('project_contract_invoice',$project_contract_invoice);
							updateTable('invoice',array('invoice_status'=>1,'paid_amount'=>$contract_amount),array('invoice_id'=>$invoice_id));
							
							
							
							
							$projectDetails=getProjectDetails($project_id,array('project','project_owner'));
							if($this->data['contractDetails']->owner_id==$this->member_id){
								$receiver_id=$this->data['contractDetails']->contractor_id;
								$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->contractor_id);	
								$SENDER_NAME=getFieldData('organization_name','organization','member_id',$this->member_id);
								if(!$SENDER_NAME){
									$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
								}
								$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->contractor_id);
							}else{
								$receiver_id=$this->data['contractDetails']->owner_id;
								$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->owner_id);
								$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
								$RECEIVER_NAME=getFieldData('organization_name','organization','member_id',$this->data['contractDetails']->owner_id);
								if(!$RECEIVER_NAME){
									$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->owner_id);
								}
							}
						$template='dispute-resolve';
						$data_parse = array(
							'SENDER_NAME' =>$SENDER_NAME,
							'RECEIVER_NAME' =>$RECEIVER_NAME,
							'TITLE' =>$projectDetails['project']->project_title,
							'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
							'MILESTONE_TITLE' =>$this->data['contractDetails']->milestone_title,
							'DISPUTE_URL' =>get_link('DisputeDetails').'/'.md5($contract_milestone_id),
						);
						SendMail($to,$template,$data_parse);
						
						$this->notification_model->log(
							$template, // template key
							$data_parse, // template data
							$this->config->item('DisputeDetails').'/'.md5($contract_milestone_id), // link (without base_url)
							$receiver_id, // notification to,
							$this->member_id // notification_from
						);			
						
						}

					}elseif($action_type=='send_to_admin'){
						updateTable('project_contract_dispute',array('is_send_to_admin'=>1),array('project_contract_dispute_id'=>$contract_dispute_id));
						$projectDetails=getProjectDetails($project_id,array('project','project_owner'));
						if($this->data['contractDetails']->owner_id==$this->member_id){
							$receiver_id=$this->data['contractDetails']->contractor_id;
							$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->contractor_id);	
							$SENDER_NAME=getFieldData('organization_name','organization','member_id',$this->member_id);
							if(!$SENDER_NAME){
								$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
							}
							$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->contractor_id);
						}else{
							$receiver_id=$this->data['contractDetails']->owner_id;
							$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->owner_id);
							$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
							$RECEIVER_NAME=getFieldData('organization_name','organization','member_id',$this->data['contractDetails']->owner_id);
							if(!$RECEIVER_NAME){
								$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->owner_id);
							}
						}
						$template='dispute-send-to-admin';
						$data_parse = array(
							'SENDER_NAME' =>$SENDER_NAME,
							'RECEIVER_NAME' =>$RECEIVER_NAME,
							'TITLE' =>$projectDetails['project']->project_title,
							'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
							'MILESTONE_TITLE' =>$this->data['contractDetails']->milestone_title,
							'DISPUTE_URL' =>get_link('DisputeDetails').'/'.md5($contract_milestone_id),
						);
						SendMail($to,$template,$data_parse);
						
						$this->notification_model->log(
							$template, // template key
							$data_parse, // template data
							$this->config->item('DisputeDetails').'/'.md5($contract_milestone_id), // link (without base_url)
							$receiver_id, // notification to,
							$this->member_id // notification_from
						);	

					}

					$msg['status'] = 'OK';

					
					
					}
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id';
	   				$i++;
				}
				
			}
			
			
			
		
		}
		unset($_POST);
		echo json_encode($msg);		
		}
	}

	public function close_project(){
		$this->load->model('contract_model');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('pid', 'pid', 'required|trim|xss_clean');
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
				$member_id=$this->member_id;
				$organization_id=$this->organization_id;
				$p_id_enc=post('pid');
				$arr=array(
					'select'=>'p.project_id,p_o.organization_id,p_o.member_id',
					'table'=>'project as p',
					'join'=>array(
						array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left')
					),
					'where'=>array('p.project_status <>'=>PROJECT_DELETED,'md5(p.project_id)'=>$p_id_enc),
					'single_row'=>true,
				);
				$ProjectDataBasic=getData($arr);
				$is_owner=0;
				if($ProjectDataBasic){
					if($ProjectDataBasic->organization_id && $ProjectDataBasic->organization_id==$organization_id){
						$is_owner=1;
					}elseif($ProjectDataBasic->member_id==$member_id){
						$is_owner=1;
					}
				}
				if($is_owner){
					$this->db->where('project_id',$ProjectDataBasic->project_id)->update('project',array('project_status'=>PROJECT_CLOSED));
					$msg['status'] = 'OK';

				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'offer_id';
					$msg['errors'][$i]['message'] = 'invalid offer id';
	   				$i++;
				}
			}
		}
		unset($_POST);
		echo json_encode($msg);		
		}
	}
}
