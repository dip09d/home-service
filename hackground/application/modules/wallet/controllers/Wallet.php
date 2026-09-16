<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('wallet_model', 'wallet');
		$this->data['table'] = 'wallet';
		$this->data['primary_key'] = $this->data['table'].'_id';
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
		$this->data['main_title'] = 'Wallet Management';
		$this->data['second_title'] = 'All Wallet List';
		$this->data['title'] = 'Wallet';
		$breadcrumb = array(
			array(
				'name' => 'Wallet',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->wallet->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->wallet->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = 'edit';
		$this->layout->view('list', $this->data);
       
	}
	
	public function txn_detail($wallet_id=''){
		if(!$wallet_id){
			show_404(); return;
		}
		$this->data['wallet_title'] = $wallet_title = getField('title', 'wallet', 'wallet_id', $wallet_id);
		$this->data['wallet_id'] = $wallet_id;
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Transactions of ';
		$this->data['second_title'] = $wallet_title;
		$this->data['title'] = 'Transaction History';
		
		$breadcrumb = array(
			array(
				'name' => 'Wallet',
				'path' => base_url($this->data['curr_controller'].'list_record'),
			),
			array(
				'name' => $wallet_title,
				'path' => '',
			),
		);
		$srch['wallet_id'] = $wallet_id;
		$srch['is_list_transaction_details'] = $wallet_id;
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->wallet->getTxnDetail($srch, $limit, $offset);
		$this->data['list_total'] = $this->wallet->getTxnDetail($srch, $limit, $offset, FALSE);
		
		$this->data['debit_total'] = $this->wallet->wallet_debit_balance($wallet_id);
		$this->data['credit_total'] = $this->wallet->wallet_credit_balance($wallet_id);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'txn_detail/'.$wallet_id);
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('txn_detail', $this->data);
       
	}
	
	public function txn_list(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Transactions List';
		$this->data['second_title'] = 'All Transactions List';
		$this->data['title'] = 'Transaction History';
		
		$breadcrumb = array(
			array(
				'name' => 'Transactions',
				'path' => '',
			)
		);
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->wallet->getTxnList($srch, $limit, $offset);
		$this->data['list_total'] = $this->wallet->getTxnList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'txn_list');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('txn_list', $this->data);
       
	}
	
	public function withdrawn_list(){
		$this->data['primary_key'] = 'wallet_transaction_id';
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Withdrawn  List';
		$this->data['second_title'] = 'All Withdrawn List';
		$this->data['title'] = 'Withdrawn History';
		
		$breadcrumb = array(
			array(
				'name' => 'Withdrawn',
				'path' => '',
			)
		);
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->wallet->getWithdrawnList($srch, $limit, $offset);
		$this->data['list_total'] = $this->wallet->getWithdrawnList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'withdrawn_list');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('withdrawn_list', $this->data);
       
	}
	
	public function csv(){
		$limit=0;
		$srch=array();
		$this->load->helper('csv');	
		$type=$this->input->get('type');
		if($type=='transaction'){
			$daterange=$this->input->get('daterange');
			if($daterange){
				$date=explode(' - ',$daterange);
				$fromdate=$date[0];
				$enddate=$date[1];
				$srch['fromdate']=$fromdate;
				$srch['enddate']=$enddate;
			}
			
			$srch['order_asc']='1';
			//$csvarr[]=array('Transaction ID','Row ID','Transaction Date','From Wallet','To Wallet','Debit','Credit','Type','Order ID','Proposal ID','Status');
			$csvarr[]=array('Transaction ID','Row ID', 'Detail', 'Transaction Date','Debit', 'Credit', 'Member Email','Status');
			$offset = $this->wallet->getTxnRow($srch, '', '', FALSE);
			$list = $this->wallet->getTxnRow($srch, $limit, $offset);
			
			if($list){
				foreach($list as $k=>$v){
					$OID=$PID='';
					
					$status = '';
					if($v['status'] == '1'){
						$status = 'Active';
					}else if($v['status'] == '0'){
						$status = 'Pending';
					}else if($v['status'] == '2'){
						$status = 'Deleted';
					}
					$member_name=$member_email=$project_id="";
					$ref_data_cell = null;
					if($v['ref_data_cell']){
						$ref_data_cell=json_decode($v['ref_data_cell']);
						if(is_object($ref_data_cell)){
							$member_name=!empty($ref_data_cell->MN) ? $ref_data_cell->MN : '-';
							$member_email=!empty($ref_data_cell->ME) ? $ref_data_cell->ME : '-';
							$project_id=!empty($ref_data_cell->PID) ? $ref_data_cell->PID : '-';
							
						}
					}
					
					$csvarr[]=array($v['wallet_transaction_id'],$v['wallet_transaction_row_id'],$v['description_tkey'], format_date_time($v['transaction_date']),$v['debit'], $v['credit'],$member_email,$status);
				}
			}
			
			$file_name='Transaction-List-'.date("dmY").'.csv';
		}elseif($type=='transaction_details'){
			
			$csvarr[]=array('Transaction ID','Row ID','Transaction Date','Account','Amount','Type','Order ID','Proposal ID','Wallet Name','Wallet_id','User ID','Status');
			$wallet_id=$this->input->get('wallet_id');
			$wallet_title = getField('title', 'wallet', 'wallet_id', $wallet_id);
			$srch['wallet_id']=$wallet_id;
			$srch['order_asc']='1';
			$offset = $this->wallet->getTxnDetail($srch, '', '', FALSE);
			$list = $this->wallet->getTxnDetail($srch, $limit, $offset);
			if($list){
				foreach($list as $k=>$v){
					$OID=$PID='';
					if(get_setting('FEATURED_PAYMENT_PAYPAL')==$v['wallet_transaction_type_id'] || get_setting('FEATURED_PAYMENT_WALLET')==$v['wallet_transaction_type_id'] ){
						
						$rowDTL=$this->db->select('relational_data')->where('wallet_transaction_id',$v['wallet_transaction_id'])->where('description_tkey','PID')->from('wallet_transaction_row')->get()->row();
						$PID=$rowDTL->relational_data;
					}elseif(get_setting('ORDER_SITE_COMMISSION')==$v['wallet_transaction_type_id'] || get_setting('REFERRAL_COMMISSION')==$v['wallet_transaction_type_id']){
						if($v['description_tkey']=='Referral'){
							$PID=$v['relational_data'];
						}else{
							$OID=$v['relational_data'];
						}
					}else{
						$OID=getField('order_id','orders_transaction','transaction_id',$v['wallet_transaction_id']);
					}
					$status = '';
					if($v['status'] == '1'){
						$status = 'Active';
					}else if($v['status'] == '0'){
						$status = 'Pending';
					}else if($v['status'] == '2'){
						$status = 'Deleted';
					}
					$from_wallet=$to_wallet=$type="";
					if($v['ref_data_cell']){
						$ref_data_cell=json_decode($v['ref_data_cell']);
						if(is_object($ref_data_cell)){
							$from_wallet=$ref_data_cell->FW;
							$to_wallet=$ref_data_cell->TW;
							$type=str_replace('_',' ',$ref_data_cell->TP);
						}
					}
					$amount=$v['credit']-$v['debit'];
					$account="";
					if($amount>0){
						$account=$to_wallet;
					}else{
						$account=$from_wallet;
					}
					//$csvarr[]=array($v['wallet_transaction_id'],$v['wallet_transaction_row_id'],format_date_time($v['transaction_date']),$from_wallet,$to_wallet,format_money($v['debit']),format_money($v['credit']),$type,$OID,$PID,$status);
					$csvarr[]=array($v['wallet_transaction_id'],$v['wallet_transaction_row_id'],format_date_time($v['transaction_date']),$account,format_money($amount),$type,$OID,$PID,$v['wallet_title'],$v['wallet_id'],$v['user_id'],$status);
				}
			}
			$file_name='Wallet-of-'.$wallet_title.'-'.date("dmY").'.csv';
		}elseif($type=='wallet'){
			$csvarr[]=array('ID','Name','Point','Balance');
			$offset = $this->wallet->getList($srch,'', '', FALSE);
			$list = $this->wallet->getList($srch, $limit, $offset);
			if($list){
				foreach($list as $k=>$v){	
					$csvarr[]=array($v['wallet_id'],$v['title'],$v['point'],format_money($v['balance']));
				}
			}
			$file_name='Wallet-List-'.date("dmY").'.csv';
		}elseif($type=='withdrawn'){
			$csvarr[]=array('Trn ID','Name','Current Balance','Amount','Details','Created Date','Status');
			$offset = $this->wallet->getWithdrawnList($srch, '', '', FALSE);
			$list = $this->wallet->getWithdrawnList($srch, $limit, $offset);
			if($list){
				foreach($list as $k=>$v){	
					$status =$details= '';
					if($v['status'] == '1'){
						$status = 'Active';
					}else if($v['status'] == '0'){
						$status = 'Pending';
					}else if($v['status'] == '2'){
						$status = 'Deleted';
					}
					$details.='Method: '.str_replace('_',' ',$v['description_tkey'])."\t\n";
					$relational_data=json_decode($v['relational_data']);
					if(is_object($relational_data)){
					  	foreach($relational_data as $r=>$rval){
					  		$details.= ucwords(str_replace('_',' ',$r)).': '.$rval."\t\n";
						}
					  }
					
					$csvarr[]=array($v['wallet_transaction_id'],$v['member_name'],format_money($v['balance']),format_money($v['amount']),$details,format_date_time($v['created_date']),$status);
				}
			}
			
			$file_name='Withdrawn-List-'.date("dmY").'.csv';
		}else{
			redirect(base_url($this->data['curr_controller'].'list_record'));
		}
		
		
		
		array_to_csv($csvarr, $file_name);
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Wallet';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->wallet->getDetail($id);
			$this->data['title'] = 'Edit Wallet';
		}else if($page == 'single_txn_detail'){
			$id = get('id');
			$wallet_id = get('wallet_id');
			$this->data['wallet_id']= $wallet_id;
			$this->data['ID']= $id;
			$this->data['detail'] = $this->wallet->getTxnDetail(array('txn_id' => $id));
			$this->data['org_ref'] = getField('content_key', 'online_transaction_data', 'tran_id',$id);
		}else if($page == 'online_txn_data'){
			$id = get('id');
			$this->data['type'] = get('type');
			$this->data['ID']= $id;
			$this->data['detail'] = get_row(array(
				'select' => '*',
				'from' => 'online_transaction_data',
				'where' => array('online_id' => $id)
			));
		}else if($page == 'add_fund'){
			$id = get('id');
			$this->data['ID']= $id;
			// $user_id=getField('user_id', 'wallet', 'wallet_id', $id);
			$worker_id=getField('worker_id', 'wallet', 'wallet_id', $id);
			$this->data['wallet_title'] = getField('title', 'wallet', 'wallet_id', $id);
			$this->data['title'] = 'Add Fund To Wallet - '.$this->data['wallet_title'];
			// if($user_id>0){
			if($worker_id>0){
				// $this->data['info'] = getField('member_email', 'member', 'member_id', $user_id);	
				$this->data['info'] = getField('worker_email', 'worker', 'worker_email', $worker_id);	
			}else{
				$this->data['info'] = '';
			}
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add_fund');
			$this->data['current_balance'] = getField('balance', 'wallet', 'wallet_id', $id);
		}
		$this->load->view('ajax_page', $this->data);
	}
	public function add_fund(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('ID', 'id', 'required');
			$this->form_validation->set_rules('amount', 'amount', 'required|trim|is_numeric');
			$this->form_validation->set_rules('reason', 'reason', '');
			if($this->form_validation->run()){
				$post = post();
				// $insert = $this->wallet->addRecordWallet($post);
				$insert = $this->wallet->addRecordWalletWorker($post);
				if($insert > 0){
					$worker_id=getField('worker_id', 'wallet', 'wallet_id', post('ID'));
					$mobile=getField('worker_phone','worker','worker_id',$worker_id);
					// echo $mobile; die;
					$amount = post('amount');
					$currency = ($amount == 1) ? 'Re.' : 'Rs.';
					$amt_var = $currency.' '.$amount;
					if($mobile && $amount > 0){
						$smstext='Your service provider wallet has been successfully recharged with '.$amt_var.'. You can now accept jobs and start earning more. Thank you for choosing SNAPHIVE.';
						$sendSms=sendSMS($mobile,'1707177633556818028',$smstext);
						/* if($sendSms && $sendSms['status']==1){
							$response['status'] = 1;
							$response['otp'] = $otp;
						}else{
							$response['otp'] = $otp;
							$response['status'] = 0;
							$response['errors'] = 'SMS sending failed';
						} */
					}
				}
				if(post('add_more') && post('add_more') == '1'){
					$this->api->cmd('reset_form');
				}else{
					$this->api->cmd('reload');
				}
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	public function add(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->wallet->addRecord($post);
				if(post('add_more') && post('add_more') == '1'){
					$this->api->cmd('reset_form');
				}else{
					$this->api->cmd('reload');
				}
				
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function edit(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->wallet->updateRecord($post, $ID);
				$this->api->cmd('reload');
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function change_status(){
		if(post() && $this->input->is_ajax_request()){
			
			$ID = post('ID');
			$sts = post('status');
			$action_type = post('action_type');
			
			if(is_array($ID)){
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('status' => $sts));
			}else{
				$upd['data'] = array('status' => $sts);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
			}
			
			if($action_type == 'multiple'){
				$this->api->cmd('reload');
			}else{
				
				$html = '';
				if($sts == ACTIVE_STATUS){
					$html = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$ID.', this)"><span class="badge badge-success">Active</span></a>';
				}else{
					$html = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$ID.', this)"><span class="badge badge-danger">Inactive</span></a>';
				}
			
			
				$this->api->data('html', $html);
				$this->api->cmd('replace');
			}
			
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	public function change_status_withdrawn(){
		if(post() && $this->input->is_ajax_request()){
			$this->data['table'] = 'wallet_transaction';
			$this->data['primary_key'] = $this->data['table'].'_id';
			$ID = post('ID');
			$sts = post('status');
			$action_type = post('action_type');
			
			if(is_array($ID)){
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('status' => $sts));
			}else{
				$this->db->select('w.wallet_id,m.member_name,m.member_id,r.debit')
					->from('wallet_transaction_row as r')
					->join('wallet as w', 'r.wallet_id=w.wallet_id', 'LEFT')
					->join('member  as m', 'w.user_id=m.member_id', 'LEFT');
				$this->db->where('r.description_tkey <>', 'Transfer_from');
				$this->db->where('r.wallet_transaction_id',$ID);
				$result=$this->db->get()->row();
				$upd['data'] = array('status' => $sts,'transaction_date'=>date('Y-m-d H:i:s'));
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
				$member_id=getField('user_id','wallet','wallet_id',$result->wallet_id);
				$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
				$data_parse=array(
				'MEMBER_NAME'=>getField('member_name','member','member_id',$member_id),
				'TRANSACTION_URL'=>SITE_URL.'finance/transaction',
				);
				
				if($sts==2){
					$wallet_id=$result->wallet_id;
					$total_debit = $this->wallet->wallet_debit_balance($wallet_id);
					$total_credit = $this->wallet->wallet_credit_balance($wallet_id);
					$org_balance = $total_credit - $total_debit;
					update_wallet_balance($wallet_id, $org_balance);
					
					$wallet_id=get_setting('WITHDRAW_WALLET');
					$total_debit = $this->wallet->wallet_debit_balance($wallet_id);
					$total_credit = $this->wallet->wallet_credit_balance($wallet_id);
					$org_balance = $total_credit + $total_debit;
					update_wallet_balance($wallet_id, $org_balance);
					
					$template='withdrawal-request-approved-by-admin';
					SendMail($RECEIVER_EMAIL,$template,$data_parse);

					
				}elseif($sts==1){
					$total=$result->debit;
					$this->db->set('withdrawn','withdrawn+'.$total,FALSE)->where('wallet_id',$result->wallet_id)->update('wallet');	
					$template='withdrawal-request-rejected-by-admin';
					SendMail($RECEIVER_EMAIL,$template,$data_parse);
				}else{

				}
				
				
			}
			
			$this->api->cmd('reload');
			
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function delete_record($id=''){
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		if($id){
			$this->wallet->deleteRecord($id);
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}else{
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
	public function update_wallet(){
		$json = array();
		
		$wallet_id = $this->input->post('wallet_id');
		$cmd = $this->input->post('cmd');
		if($cmd == 'update_origional'){
			
			$total_debit = $this->wallet->wallet_debit_balance($wallet_id);
			$total_credit = $this->wallet->wallet_credit_balance($wallet_id);
			$org_balance = $total_credit - $total_debit;
			
			update_wallet_balance($wallet_id, $org_balance);
			
		}
		
		$json['status'] = 1;
		
		echo json_encode($json);
	}
	
	public function online_txn_data(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Online Transactions Data';
		$this->data['second_title'] = 'All Online Transactions';
		$this->data['title'] = 'Online Transactions Data';
		
		$breadcrumb = array(
			array(
				'name' => 'Transactions Data',
				'path' => '',
			)
		);
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->wallet->getOnlineTxnDataList($srch, $limit, $offset);
		$this->data['list_total'] = $this->wallet->getOnlineTxnDataList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'txn_list');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('txn_data_list', $this->data);
       
	}
	
	
}





