<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workroom extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('offers/offer_model', 'offer');
		$this->data['table'] = 'project_contract';
		$this->data['primary_key'] = 'contract_id';
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	

	public function details($contract_id_enc=''){
		
		$this->data['main_title'] = 'Contract Detail';
		$this->data['second_title'] = 'Contract Detail';
		$this->data['title'] = 'Contract Detail';
		$breadcrumb = array(
			array(
				'name' => 'Contract',
				'path' => base_url('offers/contracts'),
			),
			
			array(
				'name' => 'Contract Detail',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
			
		$this->load->model('workroom_model');	
		$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_details'));
		if($this->data['contractDetails']){
			if($this->data['contractDetails']->contract_status!=1){
				
				redirect(base_url('offer/offer_detail/'.$contract_id_enc));
			}elseif($this->data['contractDetails']->is_hourly==0){
				redirect(base_url('offer/contract_details/'.$contract_id_enc));
			}
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
			$this->data['contractDetails']->total_bill=$this->workroom_model->getTotalBillAmount($project_id,$contract_id);
			$this->data['contractDetails']->in_escrow=$this->workroom_model->getEscrowAmount($project_id,$contract_id);
			$this->data['contractDetails']->milestone_paid=$this->workroom_model->getMilestonePaid($project_id,$contract_id);
			$this->data['contractDetails']->balance_remain=$this->data['contractDetails']->total_bill-$this->data['contractDetails']->milestone_paid;
			
			$this->data['contractDetails']->total_hour=$this->workroom_model->getTotalHour($project_id,$contract_id,'all');
			$this->data['contractDetails']->total_approved=$this->workroom_model->getTotalHour($project_id,$contract_id,'approved');
			$this->data['contractDetails']->total_pending=$this->workroom_model->getTotalHour($project_id,$contract_id,'pending');
			$this->data['contractDetails']->yet_to_bill=$this->workroom_model->getTotalBillAmount($project_id,$contract_id,FALSE);
			
			



			
			$this->data['is_owner']=1;
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

			$this->data['reviews']=get_contract_view($contract_id,$owner['project_owner']->member_id);

			$this->layout->view('contract/contract-details', $this->data);
		}
		
	}

	public function worklog($contract_id_enc=''){
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		$this->data['main_title'] = 'Contract Detail';
		$this->data['second_title'] = 'Contract Detail';
		$this->data['title'] = 'Contract Detail';
		$breadcrumb = array(
			array(
				'name' => 'Contract',
				'path' => base_url('offers/contracts'),
			),
			
			array(
				'name' => 'Contract Detail',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
			
		$this->load->model('workroom_model');	
		$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_details'));
		if($this->data['contractDetails']){
			if($this->data['contractDetails']->contract_status!=1){
				
				redirect(base_url('offer/offer_detail/'.$contract_id_enc));
			}elseif($this->data['contractDetails']->is_hourly==0){
				redirect(base_url('offer/contract_details/'.$contract_id_enc));
			}
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
			


			
			$this->data['is_owner']=1;
			$this->data['show']=$show;
			$this->layout->view('contract/contract-worklog', $this->data);
		}
		
	}
	public function load_worklog($contract_id_enc=''){
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
			$this->load->model('workroom_model');
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'workroom_load_worklog'));
			if($this->data['contractDetails']){
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
				
				
				$this->data['is_owner']=1;
				
				$worklog=array(
					'select'=>'l.log_id,l.log_title,l.start_time,l.end_time,l.reg_date,l.total_time_worked,l.reject_reason,l.log_status,i.log_attachment,i.log_details,l.invoice_id',
					'table'=>'project_contract_hour_log l',
					'join'=>array(
						array('table'=>'project_contract_hour_log_info i', 'on'=>'l.log_id=i.log_id', 'position'=>'left'),
					),
					'where'=>array('l.contract_id'=>$contract_id),
					'order'=>array(array('l.log_id','desc')),
				);
				if($show=='pending'){
					$worklog['where']['l.log_status']='0';
				}elseif($show=='rejected'){
					$worklog['where']['l.log_status']='2';
				}elseif($show=='completed'){
					$worklog['where']['l.log_status']='1';
					$worklog['where']['l.invoice_id']=NULL;
				}
				$from=0;
				$perpage=25;
				if(get('from')){
					$from=(get('from')-1)*$perpage;
				}
				
				$worklog['limit']=array($perpage,$from);
				$this->data['contractDetails']->worklog=getData($worklog);
				$this->data['show']=$show;
				$this->data['list']=$this->load->view('contract/worklog-ajax',$this->data, TRUE, TRUE);
				$worklog['return_count']=true;
				$worklog_total=getData($worklog);
				$this->data['total_page']=ceil($worklog_total/$perpage);
				
			}	
	
		//print_r($this->data);
		echo json_encode($this->data);
	}
	public function invoice($contract_id_enc=''){
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		$this->data['main_title'] = 'Contract Detail';
		$this->data['second_title'] = 'Contract Detail';
		$this->data['title'] = 'Contract Detail';
		$breadcrumb = array(
			array(
				'name' => 'Contract',
				'path' => base_url('offers/contracts'),
			),
			
			array(
				'name' => 'Contract Detail',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
			
		$this->load->model('workroom_model');	
		$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_details'));
		if($this->data['contractDetails']){
			if($this->data['contractDetails']->contract_status!=1){
				
				redirect(base_url('offer/offer_detail/'.$contract_id_enc));
			}elseif($this->data['contractDetails']->is_hourly==0){
				redirect(base_url('offer/contract_details/'.$contract_id_enc));
			}
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
			$this->data['show']=$show;
			$this->data['is_owner']=1;
			$this->layout->view('contract/contract-invoice', $this->data);
		}
		
	}
	
	
	public function message($contract_id_enc='')
	{
		
			$this->data['main_title'] = 'Contract Detail';
			$this->data['second_title'] = 'Contract Detail';
			$this->data['title'] = 'Contract Detail';
			$breadcrumb = array(
				array(
					'name' => 'Contract',
					'path' => base_url('offers/contracts'),
				),
				
				array(
					'name' => 'Contract Detail',
					'path' => '',
				),
			);
			$this->data['breadcrumb'] = breadcrumb($breadcrumb);
			$this->load->model('offers/contract_model');
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_message'));
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
				
			}else{
				redirect(get_link('dashboardURL'));
			}
			$freelancer_id=$this->data['contractDetails']->contractor_id;
			$owner_id=$owner['project_owner']->member_id;

			
			$member_ids=array($freelancer_id,$owner_id);
			$this->load->model('message/message_model','message');
			$this->data['conversation_details']=new stdClass();
			$room_id=$this->message->getConversationID($project_id,$member_ids,1);
			$this->data['conversation_details']->conversations_id=$room_id;
			$this->data['conversation_details']->group=$this->db->select('m.member_name,r.user_id')->from('conversations_room as r')->join('member as m','r.user_id=m.member_id','left')->where('r.conversations_id',$room_id)->get()->result();
			if(!$this->data['conversation_details']->group){
				show_404(); return;
			}
			$this->data['conversation_details']->conversations=$this->message->getMessageChatList($room_id);
			

			
			$this->layout->view('contract/contract-message', $this->data);
		
	}
	
	public function contract_term($contract_id_enc=''){
		$this->data['main_title'] = 'Contract Term';
		$this->data['second_title'] = 'Contract Term';
		$this->data['title'] = 'Contract Term';
		$breadcrumb = array(
			array(
				'name' => 'Contract',
				'path' => base_url('offers/contracts'),
			),
			
			array(
				'name' => 'Contract Term',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
	
			
		$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_term'));
		if($this->data['contractDetails']){
			if($this->data['contractDetails']->contract_status!=1){
				redirect(base_url('offer/offer_detail/'.$contract_id_enc));
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
			$this->data['pending_contract']=$this->db->where('contract_id',$contract_id)->where('is_approved <>',1)->from('project_contract_milestone')->count_all_results();
			$this->data['reviews']=get_contract_view($contract_id,$owner['project_owner']->member_id);
			$this->layout->view('contract/contract-term', $this->data);
		}
		
	}
	
	/* hourly [workroom] */
	
}





