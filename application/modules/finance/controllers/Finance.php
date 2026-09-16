<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Finance extends MX_Controller {
	private $lang;
	private $data;
    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
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
		$this->lang = get_active_lang();
        parent::__construct();
    }
	public function addfund()
	{
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
		
		$this->layout->view('add-fund', $this->data);
		
	}
	public function stripe()
	{
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
		
		$this->layout->view('stripe', $this->data);
		
	}
	public function transaction()
	{
		$this->load->model('finance_model');
		$this->load->library('pagination');
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
			'moment-with-locales.js',
			'daterangepicker.js'
		));
		$this->layout->set_css(array(
			'daterangepicker.css'
		));
		$wallet_id=$balance=0;
		$srch = $this->input->get();
		$limit = !empty($srch['per_page']) ? $srch['per_page'] : 0;
		$offset = 10;
		$this->data['member_wallet']=getWalletMember($this->member_id);
		if($this->data['member_wallet']){
			$wallet_id=$this->data['member_wallet']->wallet_id;
			$balance=$this->data['member_wallet']->balance;
		}
		$this->data['current_balance']=$balance;
		$this->data['total_debit']=$this->finance_model->wallet_debit_balance($wallet_id);
		$this->data['total_credit']=$this->finance_model->wallet_credit_balance($wallet_id);
		$this->data['current_balance']=$balance;
		$srch['wallet_id'] = $wallet_id;
		if($this->input->get('searchdate')){
			$searchdates=explode(' - ',$this->input->get('searchdate'));
			$srch['txn_from']=$searchdates[0];
			$srch['txn_to']=$searchdates[1];
			
		}
		


		$this->data['list'] = $this->finance_model->getTransaction($srch, $limit, $offset);
		$this->data['list_total'] = $this->finance_model->getTransaction($srch, $limit, $offset, FALSE);

		$CSV=$this->input->get('CSV');
		if($CSV==1){
			$this->load->helper('csv');	
			$csvarr[]=array('Transaction ID', 'Detail', 'Create Date', 'Transaction Date','Debit', 'Credit','Status');
			$list=$this->data['list'];
			if($list){
				foreach($list as $k=>$v){
					//print_r($v);
					$status = '';
					$debit=$credit=0;
					if($v['status'] == '1'){
						$status = 'Active';
					}else if($v['status'] == '0'){
						$status = 'Pending';
					}else if($v['status'] == '2'){
						$status = 'Deleted';
					}
					if($v['Amount']>0){
						$credit=abs($v['Amount']);
					}else{
						$debit=abs($v['Amount']);
					}
					$csvarr[]=array($v['wallet_transaction_id'],$v['description'], $v['created_date'],$v['transaction_date'],$debit, $credit,$status);
				}
			}
			$file_name='Transaction-List-'.date("dmY").'.csv';
			array_to_csv($csvarr, $file_name);
			die;
		}
		
		/*Pagination Start*/
		$config['base_url'] = get_link('TransactionHistoryURL');
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



		if($this->access_member_type=='F'){
			$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
		}else{
			$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
		}
		
		$this->layout->view('transaction', $this->data);
		
	}
	public function withdraw()
	{
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
		));
		$this->data['list']=getData(array(
			'select'=>'m.account_id,m.member_id,m.payment_type,m.account_heading,m.acount_details',
			'table'=>'member_withdraw_account as m',
			'where'=>array('m.member_id'=>$this->member_id,'m.account_status'=>1),
		));
		if($this->access_member_type=='F'){
			$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
		}else{
			$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
		}
		
		$this->layout->view('withdraw', $this->data);
		
	}
	public function processfund(){
		checkrequestajax();
		$i=0;
		$msg=array();
		$method=post('method');
		$okey=post('okey');
		$payfor=post('payfor');
		if($okey>0){
		if($payfor==1){
			if($method=='paypal'){
				$this->session->set_userdata('add_fund_amt',$okey);
				$msg['status'] = 'OK';
				$msg['redirect'] =get_link('PaypalCheckOut').'addfund';
			}
			elseif($method=='stripe'){
				$this->session->set_userdata('add_fund_amt',$okey);
				$msg['status'] = 'OK';
				$msg['redirect'] =get_link('StripeCheckOut').'addfund';
			}
			elseif($method=='telr'){
				$featured_fee=get_setting('featured_fee');
				$feeCalculation=generateProcessingFee('telr',$featured_fee);
				$processing_fee=$feeCalculation['processing_fee'];
				$amount=$featured_fee+$processing_fee;
				$cart_desc='Feature payment';
				$cart_id=$proposal_id.'-'.time();
				$post_data = Array(
					'ivp_method'		=> 'create',
					'ivp_authkey'		=> get_setting('telr_authentication_code'),
					'ivp_store'		=> get_setting('telr_store_id'),
					'ivp_lang'		=> 'en',
					'ivp_cart'		=> $cart_id,
					'ivp_amount'		=> $amount,
					'ivp_currency'		=> trim(CurrencyCode()),
					'ivp_test'		=> get_setting('telr_is_sandbox'),
					'ivp_desc'		=> trim($cart_desc),
					'return_auth'		=> 	get_link('TelrNotify').'featured/'.$proposal_id,
					'return_can'		=>  get_link('homeURL'),
					'return_decl'		=>  get_link('homeURL'),
					/*'ivp_update_url'	=>  get_link('TelrNotify').'featured/'.$proposal_id,*/
				);
			$curl_telr=curl_telr($post_data);
			if($curl_telr){
				if(isset($curl_telr['order'])) {
					$transansaction_data=array('payment_type'=>'TELR','content_key'=>$cart_id);
					$transansaction_data['request_value']=json_encode($post_data);
					insert_record('online_transaction_data',$transansaction_data);
		
					$jobj = $curl_telr['order'];
					$ref=$jobj['ref'];
					$this->session->set_userdata('Tref',$ref);
					$redirurl=$jobj['url'];
					$msg['status'] = 'OK';
					$msg['redirect'] =$redirurl;
				}else{
					$jobj = $returnData['error'];
					$msg['status'] = 'FAIL';
					$msg['error'] = $jobj['message'].' :: '.$jobj['note'];
				}
			}
			//print_r($post_data);
			//dd($curl_telr);	
				
			}
			elseif($method=='ngenius'){
				$featured_fee=get_setting('featured_fee');
				$feeCalculation=generateProcessingFee('ngenius',$featured_fee);
				$processing_fee=$feeCalculation['processing_fee'];
				$amount=$featured_fee+$processing_fee;
				$cart_desc='Feature payment';
				$cart_id=$proposal_id.'-'.time();
				$post_data = array(
					'grant_type'		=> 'client_credentials',
				);
				$curl_ngenius=curl_ngenius($post_data,'token',$this->member_id);
				if($curl_ngenius){
					$access_token = $curl_ngenius['access_token'];
					if($access_token){
						$postData = array(); 
						$postData['action'] = 'SALE'; 
						$postData['amount'] =array();
						$postData['merchantAttributes '] =array();
						$postData['merchantAttributes']['redirectUrl'] = get_link('manageproposalURL').'?ref_p=paymentsuccess'; 
						$postData['merchantAttributes']['cancelUrl'] = get_link('homeURL'); 
						$postData['amount']['currencyCode'] = trim(CurrencyCode()); 
						$postData['amount']['value'] = round($amount*100); 
						$postData['token'] = $access_token; 
						$curl_ngenius_order=curl_ngenius($postData,'order',$this->member_id);
						if($curl_ngenius_order){
							//print_r($curl_ngenius_order);
							if($curl_ngenius_order['_links']['payment']['href']){
								$ref = $curl_ngenius_order['reference'];
								$transansaction_data=array('payment_type'=>'NGENIUS','content_key'=>$ref);
								unset($postData['token']);
								$postData['cart_id']=$cart_id;
								$postData['payment_type']='featured';
								$transansaction_data['request_value']=json_encode($postData);
								insert_record('online_transaction_data',$transansaction_data);
								
								
								//$this->session->set_userdata('Nref',$ref);
								$redirurl=$curl_ngenius_order['_links']['payment']['href'];
								$msg['status'] = 'OK';
								$msg['method'] = $method;
								$msg['redirect'] =$redirurl;
							}else{
								$jobj = $returnData['error'];
								$msg['status'] = 'FAIL';
								$msg['error'] = $jobj['message'].' :: '.$jobj['note'];
							}
						}
						
					}
				}
			}
		}
		else if($payfor==2){
			$this->load->library('form_validation');
			$member_details=getWalletMember($this->member_id);
			$current_balance=$member_details->balance;
			$wallet_id=$member_details->wallet_id;
			$site_details=getWallet(get_setting('WITHDRAW_WALLET'));
			$receiver_wallet_id=$site_details->wallet_id;
			$receiver_wallet_balance=$site_details->balance;
			$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
			$fee_wallet_id=$fee_wallet_details->wallet_id;
			$fee_wallet_balance=$fee_wallet_details->balance;
			$accountDetails=array();
			if($method=='paypal'){
				if($this->input->post()){
					$this->form_validation->set_rules('okey', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
					$this->form_validation->set_rules('account_id', 'account id', 'required|trim|xss_clean|is_numeric|greater_than[0]');
					if ($this->form_validation->run() == FALSE){
						$error=validation_errors_array();
						if($error){
							foreach($error as $key=>$val){
								$msg['status'] = 'FAIL';
								$msg['error'] = $val;
								$i++;
							}
						}
					}
					$account_id=post('account_id');
					$total=post('okey');
					if($account_id){
						$accountDetails=getData(array(
							'select'=>'ma.account_id,ma.member_id,ma.payment_type,ma.account_heading,ma.acount_details,m.member_name',
							'table'=>'member_withdraw_account as ma',
							'join'=>array(
								array('table'=>'member as m','on'=>'ma.member_id=m.member_id','position'=>'left'),
							),
							'where'=>array('ma.member_id'=>$this->member_id,'ma.account_status'=>1,'ma.account_id'=>$account_id),
							'single_row'=>true
						));
					}
					if(!$accountDetails){
						$msg['status'] = 'FAIL';
						$msg['error'] = 'Invalid account details';
						$i++;	
					}else{
						if($current_balance>=$total){

						}else{
							$msg['status'] = 'FAIL';
							$msg['error'] = 'Insufficient funds';
							$i++;
						}

					}
					
					if($i==0){
						
						$acount_details_data=json_decode($accountDetails->acount_details);
						$paypal_email=$acount_details_data->id;
						
						$feeCalculation=generateProcessingFee('withdrawal_paypal',$total);
						$order_fee=$feeCalculation['processing_fee'];

						$relational_data=json_encode(array('method'=>'Paypal','to'=>$paypal_email));
						$wallet_transaction_type_id=get_setting('WITHDRAW');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$wallet_id,'debit'=>$total,'description_tkey'=>'Paypal_Transfer','relational_data'=>$relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$accountDetails->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							$w_payment=$total-$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$receiver_wallet_id,'credit'=>$w_payment,'description_tkey'=>'Transfer_from','relational_data'=>$accountDetails->member_name);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$accountDetails->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							if($order_fee>0){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Paypel_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$accountDetails->member_name.' wallet',
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							}
							$new_balance=displayamount($current_balance,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$wallet_id));
							wallet_balance_check($wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance=displayamount($receiver_wallet_balance,2)+displayamount($w_payment,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$receiver_wallet_id));
							wallet_balance_check($receiver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							if($order_fee>0){
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							}
							$template='withdrawn-request';
							$data_parse=array(
								'WITHDRAWN_URL'=>ADMIN_URL.'wallet/withdrawn_list',
							);
							SendMail(get_setting('admin_email'),$template,$data_parse);
							$msg['status'] = 'OK';
						}else{
							$msg['status'] = 'FAIL';
							$msg['error'] = 'Invalid request';
						}						
					}
				}
			}
			elseif($method=='bank'){
				if($this->input->post()){
					$this->form_validation->set_rules('okey', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
					$this->form_validation->set_rules('account_id', 'account id', 'required|trim|xss_clean|is_numeric|greater_than[0]');
					if ($this->form_validation->run() == FALSE){
						$error=validation_errors_array();
						if($error){
							foreach($error as $key=>$val){
								$msg['status'] = 'FAIL';
								$msg['error'] = $val;
								$i++;
							}
						}
					}
					$account_id=post('account_id');
					$total=post('okey');
					if($account_id){
						$accountDetails=getData(array(
							'select'=>'ma.account_id,ma.member_id,ma.payment_type,ma.account_heading,ma.acount_details,m.member_name',
							'table'=>'member_withdraw_account as ma',
							'join'=>array(
								array('table'=>'member as m','on'=>'ma.member_id=m.member_id','position'=>'left'),
							),
							'where'=>array('ma.member_id'=>$this->member_id,'ma.account_status'=>1,'ma.account_id'=>$account_id),
							'single_row'=>true
						));
					}
					if(!$accountDetails){
						$msg['status'] = 'FAIL';
						$msg['error'] = 'Invalid account details';
						$i++;	
					}else{
						if($current_balance>=$total){

						}else{
							$msg['status'] = 'FAIL';
							$msg['error'] = 'Insufficient funds';
							$i++;
						}

					}
					
					if($i==0){
						
						$acount_details_data=json_decode($accountDetails->acount_details);
						$bank_account_number=$acount_details_data->id;
						
						$feeCalculation=generateProcessingFee('withdrawal_bank',$total);
						$order_fee=$feeCalculation['processing_fee'];

						$relational_data=json_encode(array('method'=>'Bank','to'=>$bank_account_number,'name'=>$acount_details_data->name,'swift'=>$acount_details_data->swift,'iban'=>$acount_details_data->iban));
						$wallet_transaction_type_id=get_setting('WITHDRAW');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$wallet_id,'debit'=>$total,'description_tkey'=>'Bank_Transfer','relational_data'=>$relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$accountDetails->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							$w_payment=$total-$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$receiver_wallet_id,'credit'=>$w_payment,'description_tkey'=>'Transfer_from','relational_data'=>$accountDetails->member_name);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$accountDetails->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Withdraw',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							if($order_fee>0){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Bank_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$accountDetails->member_name.' wallet',
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							}
							$new_balance=displayamount($current_balance,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$wallet_id));
							wallet_balance_check($wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance=displayamount($receiver_wallet_balance,2)+displayamount($w_payment,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$receiver_wallet_id));
							wallet_balance_check($receiver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							if($order_fee>0){
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							}
							$template='withdrawn-request';
							$data_parse=array(
								'WITHDRAWN_URL'=>ADMIN_URL.'wallet/withdrawn_list',
							);
							SendMail(get_setting('admin_email'),$template,$data_parse);
							$msg['status'] = 'OK';
						}else{
							$msg['status'] = 'FAIL';
							$msg['error'] = 'Invalid request';
						}						
					}
				}
			}
		}else{
			$msg['status'] = 'FAIL';
			$msg['error'] = 'Invalid payment option';
		}
		}else{
			$msg['status'] = 'FAIL';
			$msg['error'] = 'Invalid amount';
		}
		unset($_POST);
		echo json_encode($msg);	
	}
	public function withdrawmethod(){
		checkrequestajax();
		
		$this->layout->view('ajax-add-edit-payment-method', $this->data,TRUE);

	}
	public function processwithdrawmethod(){
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$account_heading=$acount_details=NULL;
		$msg=array();
		if($this->loggedUser){
		if($this->input->post()){
			$this->form_validation->set_rules('payment_method', 'payment method', 'required|trim|xss_clean');
			if(post('payment_method')=='paypal'){
				$this->form_validation->set_rules('paypal_address', 'address', 'required|trim|xss_clean|valid_email');
				$account_heading=post('paypal_address');
				$acount_details=json_encode(array('id'=>post('paypal_address')));
			}
			elseif(post('payment_method')=='stripe'){
				$this->form_validation->set_rules('stripe_address', 'address', 'required|trim|xss_clean|valid_email');
				$account_heading=post('stripe_address');
				$acount_details=json_encode(array('id'=>post('stripe_address')));
			}elseif(post('payment_method')=='bank'){
				$this->form_validation->set_rules('bank_name', 'bank name', 'required|trim|xss_clean');
				$this->form_validation->set_rules('bank_ac_number', 'account number', 'required|trim|xss_clean');
				$this->form_validation->set_rules('bank_swift_code', 'swift code', 'required|trim|xss_clean');
				$this->form_validation->set_rules('bank_iban', 'IBAN', 'required|trim|xss_clean');
				$account_heading=post('bank_ac_number');
				$acount_details=json_encode(array('id'=>post('bank_ac_number'),'name'=>post('bank_name'),'swift'=>post('bank_swift_code'),'iban'=>post('bank_iban')));
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
			if($i==0 && !in_array(post('payment_method'),array('paypal','bank'))){
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'payment_method';
				$msg['errors'][$i]['message'] = 'Invalid payment method';
				$i++;

			}
			if($i==0){
				$member_withdraw_account=array(
					'member_id'=>$this->member_id,
					'payment_type'=>post('payment_method'),
					'account_heading'=>$account_heading,
					'acount_details'=>$acount_details,
					'account_status'=>1,
					'reg_date'=>date('Y-m-d H:i:s')
				);
				$acount_id=insert_record('member_withdraw_account',$member_withdraw_account,true);
				if($acount_id){			
					$msg['status'] = 'OK';
				}else{
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'payment_method';
					$msg['errors'][$i]['message'] = 'invalid data';
	   				$i++;
				}
			}
		}
		unset($_POST);
		echo json_encode($msg);		
		}
	}
	public function removewithdrawmethod(){
		checkrequestajax();
		if($this->loggedUser){
			$cmd='';
			$account_id_md5=post('aid');
			if($account_id_md5){
				$account_id=getFieldData('account_id','member_withdraw_account','md5(account_id)',$account_id_md5);
				if($account_id){
					$this->db->where('account_id',$account_id)->where('member_id',$this->member_id)->update('member_withdraw_account',array('account_status'=>2));
				}
			}
			$json['status']='OK';
		}else{
			$json['status']='FAIL';
			$json['popup']='login';
		}
		echo json_encode($json);
	}
	
}
