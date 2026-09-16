<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispute extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('dispute_model', 'dispute');
		$this->load->model('notification_model');
		
		parent::__construct();
		
		admin_log_check();
	}
	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	public function list_record(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Dispute Management';
		$this->data['second_title'] = 'All Dispute List';
		$this->data['title'] = 'Dispute';
		$breadcrumb = array(
			array(
				'name' => 'Dispute',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->dispute->getDispute($srch, $limit, $offset);
		$this->data['list_total'] = $this->dispute->getDispute($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		/* get_print($this->data['list']); */
		$this->layout->view('list', $this->data);
		
	}
	public function details($contract_milestone_id=''){
		
		$this->data['main_title'] = 'Dispute';
		$this->data['second_title'] = 'Dispute Details';
		$this->data['title'] = 'Dispute';
		$breadcrumb = array(
			array(
				'name' => 'Dispute',
				'path' => base_url('dispute/list_record'),
			),
			
			array(
				'name' => 'Dispute Details',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['contractDetails'] = getData(array(
			'select'=>'p.project_id,p.project_url,c.contract_id,c.contract_title,o.member_id as owner_id,c.contractor_id,m.contract_milestone_id,m.milestone_title,m.milestone_amount,m.is_approved,m.milestone_due_date,m.approved_date,m.is_escrow,d.project_contract_dispute_id,d.dispute_status,d.project_contract_dispute_id,d.dispute_date,d.commission_amount,d.owner_amount,d.contractor_amount,d.is_send_to_admin',
			'table'=>'project_contract_milestone m',
			'join'=>array(
				array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
				array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
				array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
				array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left'),
			),
			'where'=>array('m.contract_milestone_id'=>$contract_milestone_id,'c.contract_status'=>1,'d.project_contract_dispute_id >'=>0),
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
				
				$this->data['is_owner']=1;
				$is_valid=FALSE;
				
				
				$this->data['contractDetails']->submission=getData(array(
					'select'=>'s.submission_id,s.contract_milestone_id,s.submission_description,s.submission_attachment,s.submission_date,s.is_approved,s.commission_amount,s.owner_amount,s.contractor_amount,s.submitted_by as sender_id,m.member_name as sender_name',
					'table'=>'project_contract_dispute_submission s',
					'join'=>array(
						array('table'=>'member as m','on'=>'m.member_id=s.submitted_by','position'=>'left'),
					),
					'where'=>array('s.contract_milestone_id'=>$contract_milestone_id,'s.project_contract_dispute_id'=>$contract_dispute_id),
					'order'=>array(array('s.submission_id','desc'))
				));
				
				
			}
		$this->layout->view('dispute-details', $this->data);
	}
	public function message($contract_milestone_id=''){
		
		$this->data['main_title'] = 'Dispute';
		$this->data['second_title'] = 'Dispute Details';
		$this->data['title'] = 'Dispute';
		$breadcrumb = array(
			array(
				'name' => 'Dispute',
				'path' => base_url('dispute/list_record'),
			),
			
			array(
				'name' => 'Dispute Details',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['contractDetails'] = getData(array(
			'select'=>'p.project_id,p.project_url,c.contract_id,c.contract_title,o.member_id as owner_id,c.contractor_id,m.contract_milestone_id,m.milestone_title,m.milestone_amount,m.is_approved,m.milestone_due_date,m.approved_date,m.is_escrow,d.project_contract_dispute_id,d.dispute_status,d.project_contract_dispute_id,d.dispute_date,d.commission_amount,d.owner_amount,d.contractor_amount,d.is_send_to_admin',
			'table'=>'project_contract_milestone m',
			'join'=>array(
				array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
				array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
				array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
				array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left'),
			),
			'where'=>array('m.contract_milestone_id'=>$contract_milestone_id,'c.contract_status'=>1,'d.project_contract_dispute_id >'=>0),
			'single_row'=>TRUE
			));
			if($this->data['contractDetails']){
				$this->data['is_owner']=true;
				$freelancer_id=$this->data['contractDetails']->contractor_id;
				$owner_id=$this->data['contractDetails']->owner_id;
				$project_id=$this->data['contractDetails']->project_id;
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
				
		
				
				
			}
		$this->layout->view('dispute-message', $this->data);
	}
	public function resolve(){
		$this->load->library('form_validation');
		$i=0;
		$msg=array();
		if($this->input->post()){
			$this->form_validation->set_rules('to_client', 'to_client', 'required|trim|is_numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('to_freelancer', 'to_freelancer', 'required|trim|is_numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('mid', 'mid', 'required|trim');
			$this->form_validation->set_rules('dis_id', 'dis_id', 'required|trim');
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
					'select'=>'c.contract_id,m.contract_milestone_id,c.project_id,c.contract_title,m.milestone_title,m.milestone_amount,o.member_id as owner_id,o.organization_id,c.contractor_id,d.project_contract_dispute_id',
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
					$contract_dispute_id=$this->data['contractDetails']->project_contract_dispute_id;
					$contract_milestone_id=$this->data['contractDetails']->contract_milestone_id;
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;


					$to_client=$this->input->post('to_client');
					$to_freelancer=$this->input->post('to_freelancer');
					$total_amt_without_commission=$to_client+$to_freelancer;
					$site_fee_percent=getSiteCommissionFee($this->data['contractDetails']->contractor_id);
					$site_fee_amount= displayamount(($this->data['contractDetails']->milestone_amount*$site_fee_percent)/100,2);
					$remain_amount=displayamount($this->data['contractDetails']->milestone_amount,2)-displayamount($site_fee_amount,2);
					if($total_amt_without_commission==$remain_amount){
						$contract_status=1;
						updateTable('project_contract_dispute',array('dispute_status'=>1,'owner_amount'=>$to_client,'contractor_amount'=>$to_freelancer,'commission_amount'=>$site_fee_amount),array('project_contract_dispute_id'=>$contract_dispute_id));
						updateTable('project_contract_milestone',array('is_approved'=>1,'approved_date'=>date('Y-m-d H:i:s')),array('contract_milestone_id'=>$contract_milestone_id));
						
						$this->dispute->ReleaseFundForContractDispute($contract_milestone_id);
						
						
						$this->load->helper('invoice');
						$total_amount_to_bill=$to_freelancer;
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
							$invoice_row_unit_price=$to_freelancer;
							$invoicerow_array=array();
							$invoicerow_array[]=array('invoice_row_text'=>$this->data['contractDetails']->milestone_title,'invoice_row_amount'=>$invoice_row_amount,'invoice_row_unit'=>$invoice_row_unit,'invoice_row_unit_price'=>$invoice_row_unit_price);
							add_invoice_row($invoice_id,$invoicerow_array);
							
							$project_contract_invoice=array(
							'project_id'=>$project_id,
							'invoice_id'=>$invoice_id,
							'contract_id'=>$contract_id,
							);
							insert_record('project_contract_invoice',$project_contract_invoice);
							updateTable('invoice',array('invoice_status'=>1,'paid_amount'=>$to_freelancer),array('invoice_id'=>$invoice_id));
							
							
							
							
							$projectDetails=getProjectDetails($project_id,array('project','project_owner'));
							
							$receiver_id=$this->data['contractDetails']->contractor_id;
							$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->contractor_id);	
							$SENDER_NAME=getFieldData('organization_name','organization','member_id',$this->data['contractDetails']->owner_id);
							if(!$SENDER_NAME){
								$SENDER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->owner_id);
							}
							$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->contractor_id);
						
								
							
							$template='dispute-resolve';
							$data_parse = array(
								'SENDER_NAME' =>$SENDER_NAME,
								'RECEIVER_NAME' =>$RECEIVER_NAME,
								'TITLE' =>$projectDetails['project']->project_title,
								'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
								'MILESTONE_TITLE' =>$this->data['contractDetails']->milestone_title,
								'DISPUTE_URL' =>SITE_URL.('contract/disputedetails').'/'.md5($contract_milestone_id),
							);
							SendMail($to,$template,$data_parse);
							$this->notification_model->log(
								$template, // template key
								$data_parse, // template data
								'contract/disputedetails/'.md5($contract_milestone_id), // link (without base_url)
								$receiver_id, // notification to,
								$this->data['contractDetails']->owner_id // notification_from
							);	
							
							
							$receiver_id=$this->data['contractDetails']->owner_id;
							$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->owner_id);
							$SENDER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->contractor_id);
							$RECEIVER_NAME=getFieldData('organization_name','organization','member_id',$this->data['contractDetails']->owner_id);
							if(!$RECEIVER_NAME){
								$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->owner_id);
							}
							$data_parse = array(
								'SENDER_NAME' =>$SENDER_NAME,
								'RECEIVER_NAME' =>$RECEIVER_NAME,
								'TITLE' =>$projectDetails['project']->project_title,
								'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
								'MILESTONE_TITLE' =>$this->data['contractDetails']->milestone_title,
								'DISPUTE_URL' =>SITE_URL.('contract/disputedetails').'/'.md5($contract_milestone_id),
							);
							SendMail($to,$template,$data_parse);
							$this->notification_model->log(
								$template, // template key
								$data_parse, // template data
								'contract/disputedetails/'.md5($contract_milestone_id), // link (without base_url)
								$receiver_id, // notification to,
								$this->data['contractDetails']->contractor_id // notification_from
							);


						
						}
						$msg['status'] = 'OK';
						
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





