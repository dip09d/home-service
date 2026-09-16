<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workroom extends MX_Controller {
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
	public function details($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('workroom_model');
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
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'workroom_details','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				if($this->data['contractDetails']->contract_status!=1){
					redirect(get_link('OfferDetails').'/'.$contract_id_enc);
				}elseif($this->data['contractDetails']->is_hourly!=1){
					redirect(get_link('ContractDetails').'/'.$contract_id_enc);
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

				$this->data['contractDetails']->total_bill=$this->workroom_model->getTotalBillAmount($project_id,$contract_id);
				$this->data['contractDetails']->in_escrow=$this->workroom_model->getEscrowAmount($project_id,$contract_id);
				$this->data['contractDetails']->milestone_paid=$this->workroom_model->getMilestonePaid($project_id,$contract_id);
				$this->data['contractDetails']->balance_remain=$this->data['contractDetails']->total_bill-$this->data['contractDetails']->milestone_paid;
				
				$this->data['contractDetails']->total_hour=$this->workroom_model->getTotalHour($project_id,$contract_id,'all');
				$this->data['contractDetails']->total_approved=$this->workroom_model->getTotalHour($project_id,$contract_id,'approved');
				$this->data['contractDetails']->total_pending=$this->workroom_model->getTotalHour($project_id,$contract_id,'pending');
				$this->data['contractDetails']->yet_to_bill=$this->workroom_model->getTotalBillAmount($project_id,$contract_id,FALSE);
				
				
				
				
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
				
				
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
				
				$this->data['reviews']=get_contract_view($contract_id,$this->member_id);
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('workroom-details', $this->data);
		}
	}
	public function invoice($contract_id_enc='')
	{
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		if($this->loggedUser){
			$this->load->model('workroom_model');
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'upload-drag-file.js',
				'mycustom.js',
				'moment-with-locales.js',
				'bootstrap-datetimepicker.min.js'
			));
			$this->layout->set_css(array(
					'bootstrap-datetimepicker.css'
				));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'workroom_invoice','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				if($this->data['contractDetails']->contract_status!=1){
					redirect(get_link('OfferDetails').'/'.$contract_id_enc);
				}elseif($this->data['contractDetails']->is_hourly!=1){
					redirect(get_link('ContractDetails').'/'.$contract_id_enc);
				}
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				
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
				}
				$this->data['contractDetails']->worklog=getData($worklog);
				$this->data['contractDetails']->worklog=array();
				
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
				$this->data['contractDetails']->in_escrow=$this->workroom_model->getEscrowAmount($project_id,$contract_id);
				$this->data['contractDetails']->milestone_paid=$this->workroom_model->getMilestonePaid($project_id,$contract_id);
				$this->data['contractDetails']->balance_remain=$this->data['contractDetails']->contract_amount-$this->data['contractDetails']->milestone_paid;
				
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
			}else{
				redirect(get_link('dashboardURL'));
			}
			
			$this->data['show']=$show;
			$this->layout->view('workroom-invoice', $this->data);
		}
	}
	public function worklog($contract_id_enc='')
	{
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		if($this->loggedUser){
			$this->load->model('workroom_model');
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'upload-drag-file.js',
				'mycustom.js',
				'moment-with-locales.js',
				'bootstrap-datetimepicker.min.js'
			));
			$this->layout->set_css(array(
					'bootstrap-datetimepicker.css'
				));
			if($this->access_member_type=='F'){
				//$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				//$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'workroom_worklog','member_id'=>$this->member_id));
			if($this->data['contractDetails']){
				if($this->data['contractDetails']->contract_status!=1){
					redirect(get_link('OfferDetails').'/'.$contract_id_enc);
				}elseif($this->data['contractDetails']->is_hourly!=1){
					redirect(get_link('ContractDetails').'/'.$contract_id_enc);
				}
				$contract_id=$this->data['contractDetails']->contract_id;
				$project_id=$this->data['contractDetails']->project_id;
				
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
				}
				$this->data['contractDetails']->worklog=getData($worklog);
				$this->data['contractDetails']->worklog=array();
				
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
				
				$this->data['contractDetails']->in_escrow=$this->workroom_model->getEscrowAmount($project_id,$contract_id);
				$this->data['contractDetails']->milestone_paid=$this->workroom_model->getMilestonePaid($project_id,$contract_id);
				$this->data['contractDetails']->balance_remain=$this->data['contractDetails']->contract_amount-$this->data['contractDetails']->milestone_paid;
				
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
			}else{
				redirect(get_link('dashboardURL'));
			}
			
			$this->data['show']=$show;
			$this->layout->view('workroom-worklog', $this->data);
		}
	}
	public function load_worklog($contract_id_enc=''){
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		if($this->loggedUser){
			$this->load->model('workroom_model');
			$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'workroom_load_worklog','member_id'=>$this->member_id));
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
				
				
				$this->data['is_owner']=0;
				if($owner['project_owner']->member_id==$this->member_id){
					$this->data['is_owner']=1;
				}
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
				$this->data['list']=$this->layout->view('worklog-ajax',$this->data, TRUE, TRUE);
				$worklog['return_count']=true;
				$worklog_total=getData($worklog);
				$this->data['total_page']=ceil($worklog_total/$perpage);
				
			}	
		}
		//print_r($this->data);
		echo json_encode($this->data);
	}
	public function create_invoice_hourly(){
		$this->load->helper('invoice');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('plog[]', 'log', 'required|trim|xss_clean|is_numeric|greater_than[0]');
			$this->form_validation->set_rules('cid', 'cid', 'required|trim|xss_clean');
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
				$this->data['contractDetails'] = getData(array(
					'select'=>'c.contract_id,c.project_id,c.contract_title',
					'table'=>'project_contract c',
					'where'=>array('md5(c.contract_id)'=>$contract_id_enc,'c.contractor_id'=>$this->member_id),
					'single_row'=>TRUE
					));
					//echo $this->db->last_query();
				if($this->data['contractDetails']){
					$projectDetails=getProjectDetails($this->data['contractDetails']->project_id,array('project','project_owner'));
					$recipient_member_id=$projectDetails['project_owner']->member_id;
					$recipient_organization_id=$projectDetails['project_owner']->organization_id;
					$to=$recipient_email=getFieldData('member_email','member','member_id',$recipient_member_id);
					
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
					$alllog=post('plog');
					$totalhour=getData(array(
					'select'=>'l.log_id,l.log_title,l.total_time_worked,l.reg_date',
					'table'=>'project_contract_hour_log l',
					'where'=>array('l.contract_id'=>$contract_id,'l.log_status'=>1),
					'where_in'=>array('l.log_id'=>$alllog),
					));
					if($totalhour){
						$log_ids=array();
						$total_amount_to_bill=0;
						$invoice_row_unit='hrs';
						$invoice_row_unit_price=getFieldData('contract_amount','project_contract','contract_id',$contract_id);
						foreach($totalhour as $k=>$row){
							$invoice_row_amount=displayamount($row->total_time_worked/60,2);
							$invoicerow_array[]=array('invoice_row_text'=>$row->log_title.' - '.dateFormat($row->reg_date,'M d, Y').' '.date('H:i',strtotime($row->reg_date)),'invoice_row_amount'=>$invoice_row_amount,'invoice_row_unit'=>$invoice_row_unit,'invoice_row_unit_price'=>$invoice_row_unit_price);
							$amount=$invoice_row_amount * $invoice_row_unit_price;
							$total_amount_to_bill=$total_amount_to_bill+$amount;
							$log_ids[]=$row->log_id;
						}
						$total_amount_to_bill_round=round($total_amount_to_bill);
						$round_up_val=displayamount($total_amount_to_bill_round,2)-displayamount($total_amount_to_bill,2);
						
						$invdata=array(
						'invoice_type'=>'invoice',
						'round_up_val'=>$round_up_val,
						'recipient_organization_id'=>$recipient_organization_id,
						);
						$invoice_id=create_invoice_invoice($this->member_id,$recipient_member_id,$invdata);
						
						
						
						
						/*
						
						
						
						
						
						$invoice_number=generate_invoice_number();
						$invoice_type_id=getFieldData('invoice_type_id','invoice_type','name_tkey','invoice');
						$invoice=array(
						'invoice_type_id'=>$invoice_type_id,
						'invoice_number'=>$invoice_number,
						'issuer_member_id'=>$this->member_id,
						'issuer_organization_id'=>NULL,
						'recipient_member_id'=>$recipient_member_id,
						'recipient_organization_id'=>NULL,
						'invoice_date'=>date('Y-m-d H:i:s'),
						'recipient_email'=>$recipient_email,
						'round_up_amount'=>$round_up_val,
						'invoice_status'=>0,
						);
						if($recipient_organization_id){
							$invoice['recipient_organization_id']=$recipient_organization_id;
						}
						$invoice_id=insert_record('invoice',$invoice,TRUE);*/
						if($invoice_id){
							/*$memberInfo=getData(array(
								'select'=>'m.member_name,m_a.member_timezone,m_a.member_city,m_a.member_state,m_a.member_address_1,m_a.member_address_2,m_a.member_pincode,m_a.member_mobile,,m_a.member_mobile_code,c_n.country_name',
								'table'=>'member as m',
								'join'=>array(
									array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),
									array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),
									array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
								),
								'where'=>array('m.member_id'=>$this->member_id),
								'single_row'=>true,
							));
							
							$issuer_information_arr['I_name']=$memberInfo->member_name;
							$I_addr=$I_addr2=$I_country=$I_city=$I_state=$I_pin='';
							if($memberInfo->member_address_1){
								$I_addr=$memberInfo->member_address_1;
							}
							if($memberInfo->member_address_2){
								$I_addr2=$memberInfo->member_address_2;
							}
							if($memberInfo->member_city){
								$I_city=$memberInfo->member_city;
							}
							if($memberInfo->member_state){
								$I_state=$memberInfo->member_state;
							}
							if($memberInfo->member_pincode){
								$I_pin=$memberInfo->member_pincode;
							}
							if($memberInfo->country_name){
								$I_country=$memberInfo->country_name;
							}
							$issuer_information_arr['I_addr']=$I_addr;
							$issuer_information_arr['I_addr2']=$I_addr2;
							$issuer_information_arr['I_city']=$I_city;
							$issuer_information_arr['I_state']=$I_state;
							$issuer_information_arr['I_country']=$I_country;
							$issuer_information_arr['I_pin']=$I_pin;
						
							$organizationInfo=getData(array(
								'select'=>'m.member_name,o.organization_name,o_a.organization_timezone,o_a.organization_city,o_a.organization_state,o_a.organization_address_1,o_a.organization_address_2,o_a.organization_pincode,o_a.organization_mobile,o_a.organization_vat_number,o_a.display_in_invoice,o_a.organization_mobile_code,c_n.country_name',
								'table'=>'member as m',
								'join'=>array(
								array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left'),
								array('table'=>'organization_address as o_a','on'=>'o.organization_id=o_a.organization_id','position'=>'left'),
								array('table'=>'country as c','on'=>'o_a.organization_country=c.country_code','position'=>'left'),
								array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
								),
								'where'=>array('m.member_id'=>$recipient_member_id),
								'single_row'=>true,
							));
							if($organizationInfo->organization_name){
								$R_name=$organizationInfo->organization_name;
							}else{
								$R_name=$organizationInfo->member_name;
							}
							$recipient_information_arr['R_name']=$R_name;
							$R_addr=$R_addr2=$R_country=$R_city=$R_state=$R_pin=$R_vat='';
							if($organizationInfo->organization_address_1){
								$R_addr=$organizationInfo->organization_address_1;
							}
							if($organizationInfo->organization_address_2){
								$R_addr2=$organizationInfo->organization_address_2;
							}
							if($organizationInfo->organization_city){
								$R_city=$organizationInfo->organization_city;
							}
							if($organizationInfo->organization_state){
								$R_state=$organizationInfo->organization_state;
							}
							if($organizationInfo->organization_pincode){
								$R_pin=$organizationInfo->organization_pincode;
							}
							if($organizationInfo->organization_vat_number){
								$R_vat=$organizationInfo->organization_vat_number;
							}
							if($organizationInfo->country_name){
								$R_country=$organizationInfo->country_name;
							}
							$recipient_information_arr['R_addr']=$R_addr;
							$recipient_information_arr['R_addr2']=$R_addr2;
							$recipient_information_arr['R_city']=$R_city;
							$recipient_information_arr['R_state']=$R_state;
							$recipient_information_arr['R_country']=$R_country;
							$recipient_information_arr['R_pin']=$R_pin;
							$recipient_information_arr['R_vat']=$R_vat;
							
							if($issuer_information_arr){
								$issuer_information=serialize($issuer_information_arr);
							}else{
								$issuer_information="";
							}
							if($recipient_information_arr){
								$recipient_information=serialize($recipient_information_arr);
							}else{
								$recipient_information="";
							}
							
							$invoice_reference=array(
								'invoice_id'=>$invoice_id,
								'issuer_information'=>$issuer_information,
								'recipient_information'=>$recipient_information,
							);
							insert_record('invoice_reference',$invoice_reference);
							if($invoicerow_array){
								foreach($invoicerow_array as $invoice_row){
									$invoice_row['invoice_id']=$invoice_id;
									insert_record('invoice_row',$invoice_row);
								}
							}*/
							
							add_invoice_row($invoice_id,$invoicerow_array);
							
							if($log_ids){
								$this->db->where_in('log_id',$log_ids)->update('project_contract_hour_log',array('invoice_id'=>$invoice_id));
							}
							$project_contract_invoice=array(
							'project_id'=>$project_id,
							'invoice_id'=>$invoice_id,
							'contract_id'=>$contract_id,
							);
							insert_record('project_contract_invoice',$project_contract_invoice);
							
							$RECEIVER_NAME=$projectDetails['project_owner']->member_name;
							if($projectDetails['project_owner']->organization_name){
								$RECEIVER_NAME=$projectDetails['project_owner']->organization_name;
							}
							$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
							$template='new-invoice-request';
							$data_parse = array(
								'SENDER_NAME' =>$SENDER_NAME,
								'RECEIVER_NAME' =>$RECEIVER_NAME,
								'TITLE' =>$projectDetails['project']->project_title,
								'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
								'WORK_URL' =>get_link('ContractWorkLogHourly').'/'.md5($contract_id),
							);
							SendMail($to,$template,$data_parse);
							
							$this->notification_model->log(
								$template, // template key
								$data_parse, // template data
								$this->config->item('ContractWorkLogHourly').'/'.md5($contract_id), // link (without base_url)
								$recipient_member_id, // notification to,
								$this->member_id // notification_from
							);
								
							$msg['status'] = 'OK';
							
						}
					}else{
						$msg['status'] = 'FAIL';
		    			$msg['popup'] = 'log_error';
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
	
	public function submitwork_form_check(){
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('start_date', 'start_date', 'required|trim|xss_clean|valid_date');
			$this->form_validation->set_rules('end_date', 'end_date', 'required|trim|xss_clean|valid_date');
			$this->form_validation->set_rules('title', 'title', 'required|trim|xss_clean|max_length[250]');
			$this->form_validation->set_rules('duration_hour', 'duration_hour', 'trim|xss_clean');
			$this->form_validation->set_rules('duration_minutes', 'duration_minutes', 'trim|xss_clean');
			if($this->input->post('duration_hour') && post('duration_hour')>0){
				
			}elseif($this->input->post('duration_minutes') && post('duration_minutes')>0){
				
			}else{
				$this->form_validation->set_rules('duration_hour', 'duration_hour', 'required|trim|xss_clean|is_numeric|greater_than[0]');
				$this->form_validation->set_rules('duration_minutes', 'duration_minutes', 'required|trim|xss_clean|is_numeric|greater_than[0]');
			}
			$this->form_validation->set_rules('cid', 'cid', 'required|trim|xss_clean');
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
				$this->data['contractDetails'] = getData(array(
					'select'=>'c.contract_id,c.project_id,c.contract_title',
					'table'=>'project_contract c',
					'where'=>array('md5(c.contract_id)'=>$contract_id_enc,'c.contractor_id'=>$this->member_id),
					'single_row'=>TRUE
					));
					//echo $this->db->last_query();
				if($this->data['contractDetails']){
					$contract_id=$this->data['contractDetails']->contract_id;
					$total_time_worked=0;
					if(post('duration_hour') && post('duration_hour')>0){
						$total_time_worked=$total_time_worked+(post('duration_hour')*60);
					}
					if(post('duration_minutes') && post('duration_minutes')>0){
						$total_time_worked=$total_time_worked+post('duration_minutes');
					}
					$project_contract_hour_log=array(
					'contract_id'=>$contract_id,
					'log_title'=>post('title'),
					'start_time'=>post('start_date'),
					'end_time'=>post('end_date'),
					'reg_date'=>date('Y-m-d H:i:s'),
					'task_id'=>NULL,
					'total_time_worked'=>round($total_time_worked),
					'log_status'=>0,
					);
					$attahment=array();
					if(post('projectfile')){
						$projectfiles=post('projectfile');
						foreach($projectfiles as $file){
							$file_data=json_decode($file);
							if($file_data){
								if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
									rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."projects-files/workroom-submission/".$file_data->file_name);
									$attahment[]=array(
									'name'=>$file_data->original_name,
									'file'=>$file_data->file_name,
									);
								}
							}
						}
					}
					
					$log_id=insert_record('project_contract_hour_log',$project_contract_hour_log,TRUE);
					if($log_id){
						$project_contract_hour_log_info=array(
						'log_id'=>$log_id,
						'log_details'=>post('details'),
						'log_attachment'=>NULL,
						);
						if($attahment){
							$project_contract_hour_log_info['log_attachment']=json_encode($attahment);
						}
						insert_record('project_contract_hour_log_info',$project_contract_hour_log_info);
						$projectDetails=getProjectDetails($this->data['contractDetails']->project_id,array('project','project_owner'));
						$to=getFieldData('member_email','member','member_id',$projectDetails['project_owner']->member_id);
						$RECEIVER_NAME=$projectDetails['project_owner']->member_name;
						if($projectDetails['project_owner']->organization_name){
							$RECEIVER_NAME=$projectDetails['project_owner']->organization_name;
						}
						$SENDER_NAME=getFieldData('member_name','member','member_id',$this->member_id);
						$template='hourly-work-submission';
						$data_parse = array(
							'SENDER_NAME' =>$SENDER_NAME,
							'RECEIVER_NAME' =>$RECEIVER_NAME,
							'TITLE' =>$projectDetails['project']->project_title,
							'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
							'WORK_URL' =>get_link('ContractWorkLogHourly').'/'.md5($contract_id),
						);
						SendMail($to,$template,$data_parse);
						
						$this->notification_model->log(
							$template, // template key
							$data_parse, // template data
							$this->config->item('ContractWorkLogHourly').'/'.md5($contract_id), // link (without base_url)
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
		$this->load->model('workroom_model');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('cid', 'cid', 'required|trim|xss_clean');
			$this->form_validation->set_rules('action_type', 'action_type', 'required|trim|xss_clean');
			$this->form_validation->set_rules('reason', 'reason', 'trim|xss_clean');
			if(post('action_type')=='deny'){
				$this->form_validation->set_rules('reason', 'reason', 'required|trim|xss_clean');
			}
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
				$contract_id_enc=post('cid');
				$action_type=post('action_type');
				if(!in_array($action_type,array('accept','deny'))){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'action_type';
					$msg['errors'][$i]['message'] = 'invalid action';
	   				$i++;
				}
				$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'workroom_workaction','member_id'=>$this->member_id));

				if($this->data['contractDetails']){
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
						
					
					if($i==0){
						
					if($this->data['contractDetails']->owner_id==$this->member_id){
						
						if($action_type=='accept'){
							$contract_status=1;
							updateTable('project_contract_hour_log',array('log_status'=>1,'reject_reason'=>NULL),array('contract_id'=>$contract_id,'log_id'=>post('sid')));	
							updateMemberHour($this->data['contractDetails']->contractor_id);
						}else{
							$contract_status=2;
							updateTable('project_contract_hour_log',array('log_status'=>2,'reject_reason'=>post('reason')),array('contract_id'=>$contract_id,'log_id'=>post('sid')));
						}
						
						
						$projectDetails=getProjectDetails($project_id,array('project','project_owner'));
						if($action_type=='accept'){
							$template="hourly-work-submission-accepted";
						}else{
							$template="hourly-work-submission-rejected";
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
							'WORK_URL' =>get_link('ContractWorkLogHourly').'/'.md5($contract_id),
						);
						if($action_type=='deny'){
							$data_parse['REJECT_REASON']=post('reason');	
						}
						SendMail($to,$template,$data_parse);
					
						$this->notification_model->log(
							$template, // template key
							$data_parse, // template data
							$this->config->item('ContractWorkLogHourly').'/'.md5($contract_id), // link (without base_url)
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
	
	public function workactionpause(){
		$this->load->model('workroom_model');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('cid', 'cid', 'required|trim|xss_clean');
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
				$contract_id_enc=post('cid');
				$action_type=post('action_type');
				if(!in_array($action_type,array('pause','start'))){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'action_type';
					$msg['errors'][$i]['message'] = 'invalid action';
	   				$i++;
				}
				$this->data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'workroom_workaction','member_id'=>$this->member_id));

				if($this->data['contractDetails']){
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
						
					
					if($i==0){
						
					if($this->data['contractDetails']->owner_id==$this->member_id){
						
						if($action_type=='pause'){
							$contract_status=1;
							updateTable('project_contract',array('is_pause'=>1),array('contract_id'=>$contract_id));	
						}else{
							$contract_status=2;
							updateTable('project_contract',array('is_pause'=>0),array('contract_id'=>$contract_id));
						}
						
						$projectDetails=getProjectDetails($project_id,array('project','project_owner'));
						$to=getFieldData('member_email','member','member_id',$this->data['contractDetails']->contractor_id);
						$RECEIVER_NAME=getFieldData('member_name','member','member_id',$this->data['contractDetails']->contractor_id);
						$SENDER_NAME=$projectDetails['project_owner']->member_name;
						if($projectDetails['project_owner']->organization_name){
							$SENDER_NAME=$projectDetails['project_owner']->organization_name;
						}
						if($action_type=='pause'){
							$template='pause-contract';
						}else{
							$template='resume-contract';
						}
						$data_parse = array(
							'SENDER_NAME' =>$SENDER_NAME,
							'RECEIVER_NAME' =>$RECEIVER_NAME,
							'TITLE' =>$projectDetails['project']->project_title,
							'CONTRACT_TITLE' =>$this->data['contractDetails']->contract_title,
							'WORK_URL' =>get_link('ContractDetailsHourly').'/'.md5($contract_id),
						);
						SendMail($to,$template,$data_parse);
						
						$this->notification_model->log(
							$template, // template key
							$data_parse, // template data
							$this->config->item('ContractDetailsHourly').'/'.md5($contract_id), // link (without base_url)
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
	
	public function message($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('workroom_model');
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
			$this->data['login_member'] = $this->message_model->getMessageUser($this->member_id);
			$selected_conversation_id=$this->message_model->getConversationID($project_id,$member_ids,1);
			if($selected_conversation_id){
				$this->data['active_chat'] = $this->message_model->getConversationUserById($selected_conversation_id, $this->member_id);
			}else{
				$this->data['active_chat'] = null;
			}
			
			$this->layout->view('workroom-message', $this->data);
		}
	}
	public function term($contract_id_enc='')
	{
		if($this->loggedUser){
			$this->load->model('workroom_model');
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
				$this->data['reviews']=get_contract_view($contract_id,$this->member_id);
			}else{
				redirect(get_link('dashboardURL'));
			}
			$this->layout->view('workroom-term', $this->data);
		}
	}
	
	
	
	
	public function index()
	{
		if($this->loggedUser){
			$this->load->model('workroom_model');
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
			$this->data['list'] = $this->workroom_model->getContracts($srch, $limit, $offset);
			$this->data['list_total'] = $this->workroom_model->getContracts($srch, $limit, $offset, FALSE);
			
			/*Pagination Start*/
			$config['base_url'] = base_url('dashboard/favorite');
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


}
