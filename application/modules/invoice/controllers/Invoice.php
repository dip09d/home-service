<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends MX_Controller {
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
		}elseif($this->input->get('auth') && $this->input->get('auth')==md5(date('Y-m-d').'-ORGUP')){

		}else{
			$refer=uri_string();
			redirect(URL::get_link('loginURL').'?refer='.$refer);
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		$this->load->model('invoice_model');
		parent::__construct();
	}
	public function listdata()
	{
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		if($this->loggedUser){
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
				$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}


			
			$this->data['show']=$show;
			$this->layout->view('invoice-list', $this->data);
		}
	}
	public function icarddetails($icard_id_enc=''){
		$this->data['InvoiceDetails'] = getData(array(
			'select'=>'i.*',
			'table'=>'worker_icard i',
			'where'=>array('md5(i.icard_id)'=>$icard_id_enc),
			'single_row'=>TRUE,
			'group'=>'i.icard_id',
			));
		if($this->data['InvoiceDetails']){
			$this->layout->view('icard_pdf',$this->data,TRUE);
		}
	}
	
	public function details($invoice_id_enc=''){
		$this->data['InvoiceDetails'] = getData(array(
			'select'=>'i.invoice_id,i.invoice_number,i.issuer_member_id,i.issuer_organization_id,i.recipient_member_id,i.recipient_organization_id,i.invoice_date,iref.issuer_information,iref.recipient_information,it.name_tkey as type,,i.invoice_type_id,i.round_up_amount,i.invoice_status,sum((ir.invoice_row_amount*ir.invoice_row_unit_price)) as total,i.tax_amount,i.platform_fee',
			'table'=>'invoice i',
			'join'=>array(
				array('table'=>'invoice_reference iref', 'on'=>'i.invoice_id=iref.invoice_id', 'position'=>'left'),
				array('table'=>'invoice_type as it', 'on'=>'it.invoice_type_id=i.invoice_type_id', 'position'=>'left'),
				array('table'=>'invoice_row as ir', 'on'=>'i.invoice_id=ir.invoice_id', 'position'=>'left'),
			),
			'where'=>array('md5(i.invoice_id)'=>$invoice_id_enc),
			'single_row'=>TRUE,
			'group'=>'i.invoice_id',
			));
		if($this->data['InvoiceDetails']){
			$invoice_id=$this->data['InvoiceDetails']->invoice_id;
			$this->data['InvoiceDetails']->invoice_row=getData(array(
			'select'=>'ir.invoice_row_text,ir.invoice_row_amount,ir.invoice_row_unit,ir.invoice_row_unit_price,ir.invoice_row_id,(ir.invoice_row_amount*ir.invoice_row_unit_price) as Invoice_Amount_Without_VAT',
			'table'=>'invoice_row ir',
			'where'=>array('ir.invoice_id'=>$invoice_id),
			'order'=>array(array('ir.invoice_row_id','asc')),
			));
			$download=$this->input->get('is_download');
			

			if($download){
				set_time_limit(0); // Prevent PHP timeout when generating PDF
				$this->layout->view('pdf_html2',$this->data,TRUE);
			}else{
				$this->layout->view('invoice-details',$this->data,TRUE);
			}
		}
	}
	public function load_invoice(){
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		$contract_id_enc=$this->input->get('cid');
		$get_all_invoice=$this->input->get('get_all_invoice');
		$filter=array();
		if($this->loggedUser){
			$show_invoice=FALSE;
			if($get_all_invoice){
				$show_invoice=TRUE;
				$filter['invoice_for']='all_invoice';
				$filter['invoice_for_member']=$this->member_id;
			}
			elseif($contract_id_enc){
				$this->data['contractDetails'] = getData(array(
				'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,co.max_hour_limit,co.allow_manual_hour',
				'table'=>'project_contract c',
				'join'=>array(
					array('table'=>'project_contract_offer co', 'on'=>'c.contract_id=co.contract_id', 'position'=>'left'),
					array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
					array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
				),
				'where'=>array('md5(c.contract_id)'=>$contract_id_enc),
				'single_row'=>TRUE
				));
				if($this->data['contractDetails']){
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
					$contractor_id=$this->data['contractDetails']->contractor_id;
					$filter['project_id']=$project_id;
					$filter['contract_id']=$contract_id;
					$filter['invoice_for']='contract';
					$show_invoice=TRUE;
				}
			}
			if($show=='pending'){
				$filter['invoice_status']='0';
			}elseif($show=='rejected'){
				$filter['invoice_status']='2';
			}elseif($show=='completed'){
				$filter['invoice_status']='1';
			}
				
			$this->data['invoiceData']=array();
			$invoice_total=0;
			$from=0;
			$perpage=25;
			if(get('from')){
				$from=(get('from')-1)*$perpage;
			}
			if($show_invoice){
				$this->data['invoiceData']=$this->invoice_model->getInvoice($filter,$perpage,$from);
				$invoice_total=$this->invoice_model->getInvoice($filter,$perpage,$from,TRUE);
			}
			$this->data['total_page']=ceil($invoice_total/$perpage);
			
			$this->data['show']=$show;
			$this->data['member_id']=$this->member_id;
			$this->data['list']=$this->layout->view('invoice-ajax',$this->data, TRUE, TRUE);
			
		}
		//print_r($this->data);
		echo json_encode($this->data);
	}
	
	public function action_invoice(){
		$this->load->model('contract/contract_model');
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
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
				$invoiec_id_enc=post('sid');
				$action_type=post('action_type');
				if(!in_array($action_type,array('accept','deny'))){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'action_type';
					$msg['errors'][$i]['message'] = 'invalid action';
	   				$i++;
				}
				
				$this->data['contractDetails'] = getData(array(
					'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,pci.invoice_id,sum((ir.invoice_row_amount*ir.invoice_row_unit_price)) as total',
					'table'=>'project_contract_invoice as pci',
					'join'=>array(
						array('table'=>'project_contract c', 'on'=>'pci.contract_id=c.contract_id', 'position'=>'left'),
						array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
						array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
						array('table'=>'invoice_row ir', 'on'=>'pci.invoice_id=ir.invoice_id', 'position'=>'left'),
					),
					'where'=>array('md5(pci.invoice_id)'=>$invoiec_id_enc,'o.member_id'=>$this->member_id),
					'single_row'=>TRUE,
					'group'=>'pci.invoice_id',
					));
				if($this->data['contractDetails']){
					$invoice_id=$this->data['contractDetails']->invoice_id;
					$contract_id=$this->data['contractDetails']->contract_id;
					$project_id=$this->data['contractDetails']->project_id;
						
					$in_escrow=$this->contract_model->getEscrowAmount($project_id,$contract_id);	
					$wallet_balance=getFieldData('balance','wallet','user_id',$this->member_id);
					
					if($action_type=='accept'){
						$contract_amount=displayamount($this->data['contractDetails']->total,2);
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
					}
					if($i==0){
					
					if($this->data['contractDetails']->owner_id==$this->member_id){
						
						if($action_type=='accept'){
							
							if($is_process_escrow){
								$this->contract_model->addFundToEscrow($project_id,$this->member_id,$contract_id,$tranferto_escrow);
							}
							$this->contract_model->ReleaseFundForContractInvoice($invoice_id);
							updateTable('invoice',array('invoice_status'=>1,'paid_amount'=>$contract_amount),array('invoice_id'=>$invoice_id));
							
						}else{
							updateTable('invoice',array('invoice_status'=>2,'change_reason'=>post('reason')),array('invoice_id'=>$invoice_id));
							updateTable('project_contract_hour_log',array('invoice_id'=>NULL),array('invoice_id'=>$invoice_id));
						}
						
						
						$projectDetails=getProjectDetails($project_id,array('project','project_owner'));
						if($action_type=='accept'){
							$template="hourly-invoice-accepted";
						}else{
							$template="hourly-invoice-rejected";
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
							'INVOICE_URL' =>get_link('InvoiceDetailsURL').'/'.md5($invoice_id),
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
	public function invoice_details($invoice_id_enc=''){
		$this->data['InvoiceDetails'] = getData(array(
			'select'=>'i.invoice_id,i.invoice_number,i.issuer_member_id,i.issuer_organization_id,i.recipient_member_id,i.recipient_organization_id,i.invoice_date,iref.issuer_information,iref.recipient_information,it.name_tkey as type,,i.invoice_type_id,i.round_up_amount,i.invoice_status,sum((ir.invoice_row_amount*ir.invoice_row_unit_price)) as total,,i.tax_amount,i.platform_fee',
			'table'=>'invoice i',
			'join'=>array(
				array('table'=>'invoice_reference iref', 'on'=>'i.invoice_id=iref.invoice_id', 'position'=>'left'),
				array('table'=>'invoice_type as it', 'on'=>'it.invoice_type_id=i.invoice_type_id', 'position'=>'left'),
				array('table'=>'invoice_row as ir', 'on'=>'i.invoice_id=ir.invoice_id', 'position'=>'left'),
			),
			'where'=>array('md5(i.invoice_id)'=>$invoice_id_enc),
			'single_row'=>TRUE,
			'group'=>'i.invoice_id',
			));
		if($this->data['InvoiceDetails']){
			$invoice_id=$this->data['InvoiceDetails']->invoice_id;
			$this->data['InvoiceDetails']->invoice_row=getData(array(
			'select'=>'ir.invoice_row_text,ir.invoice_row_amount,ir.invoice_row_unit,ir.invoice_row_unit_price,ir.invoice_row_id,(ir.invoice_row_amount*ir.invoice_row_unit_price) as Invoice_Amount_Without_VAT',
			'table'=>'invoice_row ir',
			'where'=>array('ir.invoice_id'=>$invoice_id),
			'order'=>array(array('ir.invoice_row_id','asc')),
			));
			$this->data['worker_id']=$this->input->get('worker_id');
			// echo '<pre>'; print_r($this->data); die;
			$this->layout->view('pdf_html2_order',$this->data,TRUE);
		}
	}

}
