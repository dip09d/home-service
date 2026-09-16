<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MX_Controller {
	private $lang;
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
		}elseif($this->router->fetch_method()=='paypalnotify' || $this->router->fetch_method()=='stripenotify' || $this->router->fetch_method()=='ngeniusnotify'){
			
		}else{
			redirect(get_link('loginURL'));
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		$this->lang = get_active_lang();
		//loadModel('notifications/notification_model');
		parent::__construct();
	}
	public function check_payment_telr($order_ref,$type,$id='') {
		$post_data = Array(
		'ivp_method'	=> 'check',
		'ivp_authkey'	=> get_setting('telr_authentication_code'),
		'ivp_store'	=>  get_setting('telr_store_id'),
		'order_ref'	=> $order_ref,
		);
		$returnData = curl_telr($post_data);
		$msg = json_encode($returnData);
		//file_put_contents(ABS_USERUPLOAD_PATH.'telr.log', $msg);
		//dd($returnData);
		$objOrder='';
		$objError='';
		if (isset($returnData['order'])) { $objOrder = $returnData['order']; }
		if (isset($returnData['error'])) { $objError = $returnData['error']; }
		if (is_array($objError)) { // Failed
			return false;
		}
		if (!isset(
			$objOrder['cartid'],
			$objOrder['status']['code'],
			$objOrder['transaction']['status'],
			$objOrder['transaction']['ref'])) {
			// Missing fields
			return false;
		}
		$new_tx=$objOrder['transaction']['Tref'];
		$ordStatus=$objOrder['status']['code'];
		$txStatus=$objOrder['transaction']['status'];
		if (($ordStatus==-1) || ($ordStatus==-2)) {
			// Order status EXPIRED (-1) or CANCELLED (-2)
			//$this->payment_cancelled($order_id,$new_tx);
			return false;
		}
		if (($ordStatus==2) || ($ordStatus==4)) {
			// Order status AUTH (2) or PAYMENT_REQUESTED (4)
			//$this->payment_pending($order_id,$new_tx);
			//return true;
			return false;
		}
		if ($ordStatus==3) {
			// Order status PAID (3)
			if (($txStatus=='P') || ($txStatus=='H')) {
				// Transaction status of pending or held
				//$this->payment_pending($order_id,$new_tx);
				//return true;
				return false;
			}
			if ($txStatus=='A') {
				$verify_token=$objOrder['cartid'];
				updateTable('online_transaction_data',array('response_value'=>$msg),array('content_key'=>$verify_token));
				// Transaction status = authorised
				$this->payment_authorised_telr($verify_token,$type,$id);
				return true;
			}
		}
		return false;	
	}
	public function payment_authorised_telr($verify_token,$type,$id=''){
		$is_valid=0;
		$transaction_data=getData(array(
		'select'=>'request_value',
		'table'=>'online_transaction_data',
		'where'=>array('payment_type'=>'TELR','content_key'=>$verify_token),
		'single_row'=>TRUE
		));
		if($transaction_data){
			if($type=='checkout'){
				$order_status=ORDER_PROCESSING;
				$arr=array(
					'select'=>'o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction',
					'table'=>'orders as o',
					'join'=>array(
					array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','position'=>'left'),
					array('table'=>'proposal_additional as p_a','on'=>'o.proposal_id=p_a.proposal_id','position'=>'left'),
					),
					'where'=>array('o.order_id'=>$id,'o.order_status'=>0),
					'single_row'=>TRUE
				);
				$orderDetails=getData($arr);
				if($orderDetails){
					$total=$orderDetails->order_price;
					$order_id=$orderDetails->order_id;
					if(!empty($orderDetails->buyer_instruction)){
						$order_status=ORDER_PENDING;
					}
						$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
						$buyer_wallet_id=$buyer_details['member']->wallet_id;
						$buyer_wallet_balance=$buyer_details['member']->balance;
						$site_details=getWallet(get_setting('SITE_MAIN_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$buyer_details['member']->member_name;
						$telr_details=getWallet(get_setting('TELR_WALLET'));
						$telr_wallet_id=$telr_details->wallet_id;
						$telr_wallet_balance=$telr_details->balance;
						$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;
						$order_fee=$orderDetails->order_fee;
						
						$wallet_transaction_type_id=get_setting('ORDER_PAYMENT_TELR');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
							insert_record('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
							$telr_payment=$total+$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$telr_wallet_id,'debit'=>$telr_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Telr_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$paypal_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);*/	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_Topup',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Telr_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,	
								'TP'=>'Order_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,	
								'TP'=>'Order_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
							wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
							updateTable('conversations_messages_offers',array('status'=>1),array('order_id'=>$order_id));
							updateTable('send_offers',array('status'=>1),array('order_id'=>$order_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_telr=displayamount($telr_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance_telr),array('wallet_id'=>$telr_wallet_id));
							wallet_balance_check($telr_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('OrderDetailsURL').$order_id;
							$template='new-order';
							$data_parse=array(
							'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
							'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
							'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
							'QTY'=>$orderDetails->order_qty,
							'DELIVERY_TIME'=>$orderDetails->order_duration,
							'ORDER_PRICE'=>$orderDetails->order_price,
							'ORDER_DETAILS_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							SendMail('',get_setting('admin_email'),$template,$data_parse);
							
							$notificationData=array(
							'sender_id'=>$orderDetails->buyer_id,
							'receiver_id'=>$orderDetails->seller_id,
							'template'=>'order',
							'url'=>$this->config->item('OrderDetailsURL').$order_id,
							'content'=>json_encode(array('OID'=>$order_id)),
							);
							$this->notification_model->savenotification($notificationData);
						}
					}
			}
			elseif($type=='featured'){
				$featured_fee=get_setting('featured_fee');
				$feeCalculation=generateProcessingFee('telr',$featured_fee);
				$order_fee=$feeCalculation['processing_fee'];
				$featured_duration=get_setting('featured_duration');
				$total=$featured_fee;
				$arr=array(
					'select'=>'p.proposal_id,p.proposal_seller_id',
					'table'=>'proposals p',
					'where'=>array('p.proposal_id'=>$id),
					'single_row'=>true,
				);
				$check_proposal=getData($arr);
				IF($check_proposal){
					$proposal_id=$check_proposal->proposal_id;
					$seller_details=getMemberDetails($check_proposal->proposal_seller_id,array('main'=>1));
					$seller_wallet_id=$seller_details['member']->wallet_id;
					$seller_wallet_balance=$seller_details['member']->balance;
					$site_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
					$reciver_wallet_id=$site_details->wallet_id;
					$reciver_wallet_balance=$site_details->balance;
					$recipient_relational_data=$seller_details['member']->member_name;
					$telr_details=getWallet(get_setting('TELR_WALLET'));
					$telr_wallet_id=$telr_details->wallet_id;
					$telr_wallet_balance=$telr_details->balance;
					$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
					$fee_wallet_id=$fee_wallet_details->wallet_id;
					$fee_wallet_balance=$fee_wallet_details->balance;
					
					
					$wallet_transaction_type_id=get_setting('FEATURED_PAYMENT_TELR');
					$current_datetime=date('Y-m-d H:i:s');
					$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
					if($wallet_transaction_id){
						$featured_end_date=date('Y-m-d H:i:s',strtotime('+'.$featured_duration.' days'));
						updateTable('proposal_settings',array('proposal_featured'=>1,'featured_end_date'=>$featured_end_date),array('proposal_id'=>$proposal_id));
						$telr_payment=$total+$order_fee;
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$telr_wallet_id,'debit'=>$telr_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$telr_details->title,
							'TW'=>$seller_details['member']->member_name.' wallet',	
							'TP'=>'Telr_Payment',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$paypal_details->title,
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);*/	
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$telr_details->title,
							'TW'=>$seller_details['member']->member_name.' wallet',	
							'TP'=>'Wallet_Topup',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Telr_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$telr_details->title,
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'debit'=>$total,'description_tkey'=>'PID','relational_data'=>$proposal_id);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$seller_details['member']->member_name.' wallet',
							'TW'=>$site_details->title,
							'TP'=>'Featured_Payment',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$seller_details['member']->member_name.' wallet',
							'TW'=>$site_details->title,
							'TP'=>'Featured_Payment',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
						wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
						updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
						wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance_telr=displayamount($telr_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance_telr),array('wallet_id'=>$telr_wallet_id));
						wallet_balance_check($telr_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$RECEIVER_EMAIL=$seller_details['member']->member_email;
						$url=get_link('manageproposalURL');
						$template='featured';
						$data_parse=array(
						'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
						'PROPOSAL_URL'=>$url,
						);
						SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
						
						$notificationData=array(
						'sender_id'=>0,
						'receiver_id'=>$seller_details['member']->member_id,
						'template'=>'featured',
						'url'=>$this->config->item('manageproposalURL'),
						'content'=>json_encode(array('PID'=>$proposal_id)),
						);
						$this->notification_model->savenotification($notificationData);
					}
				}	
			}
			elseif($type=='cart'){
					$allids=explode('-',$id);
					$allorder=$this->db->select('o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction')
					->from('orders as o')
					->join('proposals as p','o.proposal_id=p.proposal_id','left')
					->join('proposal_additional as p_a','o.proposal_id=p_a.proposal_id','left')
					->where_in('order_id',$allids)
					->where('o.order_status',0);
					$allorder=$this->db->get()->result();
					if($allorder){
						foreach($allorder as $orderDetails){
							$total=$orderDetails->order_price;
							$order_id=$orderDetails->order_id;
							if(!empty($orderDetails->buyer_instruction)){
								$order_status=ORDER_PENDING;
							}
							$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
							$buyer_wallet_id=$buyer_details['member']->wallet_id;
							$buyer_wallet_balance=$buyer_details['member']->balance;
							$telr_details=getWallet(get_setting('TELR_WALLET'));
							$telr_wallet_id=$telr_details->wallet_id;
							$telr_wallet_balance=$telr_details->balance;
							$site_details=getWallet(get_setting('SITE_MAIN_WALLET'));
							$reciver_wallet_id=$site_details->wallet_id;
							$reciver_wallet_balance=$site_details->balance;
							$recipient_relational_data=$buyer_details['member']->member_name;
							$wallet_transaction_type_id=get_setting('ORDER_PAYMENT_TELR');
							$current_datetime=date('Y-m-d H:i:s');
							$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
							if($wallet_transaction_id){
								updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
								insert_record('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$telr_wallet_id,'debit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Telr_Payment',
								));
								insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Telr');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$telr_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_topup',
								));
								insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
								insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
								insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
								updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
								wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
								$new_balance=displayamount($telr_wallet_balance,2)-displayamount($total,2);
								updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$telr_wallet_id));
								wallet_balance_check($telr_wallet_id,array('transaction_id'=>$wallet_transaction_id));
								
								$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
								$RECEIVER_EMAIL=$seller_details['member']->member_email;
								$url=get_link('OrderDetailsURL').$order_id;
								$template='new-order';
								$data_parse=array(
								'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
								'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
								'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
								'QTY'=>$orderDetails->order_qty,
								'DELIVERY_TIME'=>$orderDetails->order_duration,
								'ORDER_PRICE'=>$orderDetails->order_price,
								'ORDER_DETAILS_URL'=>$url,
								);
								SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
								SendMail('',get_setting('admin_email'),$template,$data_parse);
								
								$notificationData=array(
								'sender_id'=>$orderDetails->buyer_id,
								'receiver_id'=>$orderDetails->seller_id,
								'template'=>'order',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								$this->notification_model->savenotification($notificationData);
							}
						}
					}
				}
			
			
			
		}
	}
	public function telrnotify($type,$id=''){
		$data=array();
		$order_ref=$this->session->userdata('Tref');
		if($order_ref){
		$checkpyment=$this->check_payment_telr($order_ref,$type,$id);
		if($type=='checkout'){
			if($checkpyment){
				$data['redirect']=get_link('OrderDetailsURL').$id.'?ref=paymentsuccess';
			}else{
				$data['redirect']=get_link('buyingOrderURL');
			}
			
		}elseif($type=='featured'){
			if($checkpyment){
				$data['redirect']=get_link('manageproposalURL').'?ref=paymentsuccess';
			}else{
				$data['redirect']=get_link('manageproposalURL').'?ref=paymentfailed';
			}
		}
		$this->session->set_userdata('Tref','');
		$templateLayout=array('view'=>'telr-form','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
		}else{
			redirect(get_link('homeURL'));
		}
	}

	public function stripe($type,$id=''){
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
		));
		$amount=$this->session->userdata('add_fund_amt');
		if($type=='addfund' && $amount>0){
			$site_currency_code=CurrencyCode();
			$unique_id=$this->member_id.'-'.time();
			$feeCalculation=generateProcessingFee('stripe',$amount);
			$processing_fee=$feeCalculation['processing_fee'];
			$this->data['formdata']=array(
			'amount'=>$amount+$processing_fee,
			'org_amt'=>$amount,
			'fee'=>$processing_fee,
			//'notify_url'=>get_link('StripeNotify').$type.'/'.$unique_id,
			'custom'=>md5('PPAY-'.$unique_id),
			'member_id'=>$this->member_id,
			'type'=>$type
			);
			$this->data['formdata']['amount_converted']=$this->data['formdata']['amount'];
			$transansaction_data=array('payment_type'=>'STRIPE','content_key'=> $this->data['formdata']['custom']);
			$transansaction_data['request_value']=json_encode( $this->data['formdata']);
			
			$amount=$this->data['formdata']['amount'];
			$this->load->library('stripe');
			$stripe = $this->stripe->load();
			$checkout_session = \Stripe\Checkout\Session::create([
				'payment_method_types' => ['card'], //alipay, card, ideal, fpx, bacs_debit, bancontact, giropay, p24, eps, sofort, sepa_debit, grabpay, or afterpay_clearpay
				'line_items' => [[
				  'price_data' => [
					'currency' => CurrencyCode(),
					'unit_amount' => $amount*100,
					'product_data' => [
					  'name' => 'Add Fund From '.get_setting('website_name'),
					  //'images' => [LOGO],
					],
				  ],
				  'quantity' => 1,
				]],
				'client_reference_id'=>$this->data['formdata']['custom'],
				'mode' => 'payment',
				'success_url' => get_link('AddFundURL').'?refer=paymentsuccess',
				'cancel_url' => get_link('AddFundURL').'?refer=paymenterror',
			  ]);
			  $this->data['checkout_session']=$checkout_session;
			$ins=insert_record('online_transaction_data',$transansaction_data,TRUE);
			$this->layout->view('stripe-form', $this->data);
		}
		elseif($type=='membership' && $id){
			list($enc_membership_id,$duration)=explode('-',$id);
			$site_currency_code=CurrencyCode();
			$unique_id=$this->member_id.'-'.time();
			$this->load->model('membership/membership_model');
			$membership=$this->membership_model->getMembershipDetails($enc_membership_id);
			if($membership){
				$this->session->set_userdata('enc_membership_id_duration',$id);
				$amount=($duration=='month' ? $membership->price_per_month:$membership->price_per_year);
				$feeCalculation=generateProcessingFee('stripe',$amount);
				$processing_fee=$feeCalculation['processing_fee'];
				$this->data['formdata']=array(
					'amount'=>$amount+$processing_fee,
					'org_amt'=>$amount,
					'fee'=>$processing_fee,
					//'notify_url'=>get_link('StripeNotify').$type.'/'.$unique_id,
					'custom'=>md5('PPAY-'.$unique_id),
					'member_id'=>$this->member_id,
					'type'=>$type,
					'membership_id'=>$membership->membership_id,
					'membership_duration'=>$duration,
				);
				$this->data['formdata']['amount_converted']=$this->data['formdata']['amount'];
				$transansaction_data=array('payment_type'=>'STRIPE','content_key'=> $this->data['formdata']['custom']);
				$transansaction_data['request_value']=json_encode( $this->data['formdata']);
				
				$amount=$this->data['formdata']['amount'];
				$this->load->library('stripe');
				$stripe = $this->stripe->load();
				$checkout_session = \Stripe\Checkout\Session::create([
					'payment_method_types' => ['card'], //alipay, card, ideal, fpx, bacs_debit, bancontact, giropay, p24, eps, sofort, sepa_debit, grabpay, or afterpay_clearpay
					'line_items' => [[
					'price_data' => [
						'currency' => CurrencyCode(),
						'unit_amount' => $amount*100,
						'product_data' => [
						'name' => 'Membership: '.$membership->name.' From '.get_setting('website_name'),
						//'images' => [LOGO],
						],
					],
					'quantity' => 1,
					]],
					'client_reference_id'=>$this->data['formdata']['custom'],
					'mode' => 'payment',
					'success_url' => get_link('membershipURL').'?refer=paymentsuccess',
					'cancel_url' => get_link('membershipURL').'?refer=paymenterror',
				]);
				$this->data['checkout_session']=$checkout_session;
				$ins=insert_record('online_transaction_data',$transansaction_data,TRUE);
				$this->layout->view('stripe-form', $this->data);
			}
			else{
				redirect(get_link('membershipURL'));
			}
		}
		else{
			redirect(get_link('AddFundURL'));
		}

	}
	public function stripenotify(){
		$this->load->library('stripe');
		$stripe = $this->stripe->load();
		// You can find your endpoint's secret in your webhook settings
		$endpoint_secret=get_setting('stripe_endpoint_secret');
		$payload = @file_get_contents('php://input');
		$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
		$event = null;
		file_put_contents(UPLOAD_PATH.'stripe.log', json_encode($payload));
		try {
		  $event = \Stripe\Webhook::constructEvent(
			$payload, $sig_header, $endpoint_secret
		  );
		} catch(\UnexpectedValueException $e) {
		  // Invalid payload
		  http_response_code(400);
		  exit();
		} catch(\Stripe\Exception\SignatureVerificationException $e) {
		  // Invalid signature
		  http_response_code(400);
		  exit();
		}
		$this->load->model('payment_model','payment');
		switch ($event->type) {
			case 'checkout.session.completed':
			  $session = $event->data->object;
		  
			  // Save an order in your database, marked as 'awaiting payment'
			  $this->payment->stripe_create_transaction($session);
		  
			  // Check if the order is paid (e.g., from a card payment)
			  //
			  // A delayed notification payment will have an `unpaid` status, as
			  // you're still waiting for funds to be transferred from the customer's
			  // account.
			  if ($session->payment_status == 'paid') {
				// Fulfill the purchase
				$this->payment->stripe_paid_transaction($session);
			  }
		  
			  break;
		  
			case 'checkout.session.async_payment_succeeded':
			  $session = $event->data->object;
		  
			  // Fulfill the purchase
			  $this->payment->stripe_paid_transaction($session);
		  
			  break;
		  
			case 'checkout.session.async_payment_failed':
			  $session = $event->data->object;
		  
			  // Send an email to the customer asking them to retry their order
			  $this->payment->stripe_failed_transaction($session);
		  
			  break;
		  }
		
		  http_response_code(200);
	}

	public function request_payment_stripe(){
		checkrequestajax();
		$amount=$this->session->userdata('add_fund_amt');
		$type=$this->input->post('pay_for');
		$method=$this->input->post('method');
		if($type=='addfund' && $amount>0){
			$site_currency_code=CurrencyCode();
			$unique_id=$this->member_id.'-'.time();
			$feeCalculation=generateProcessingFee('stripe',$amount);
			$processing_fee=$feeCalculation['processing_fee'];
			$this->data['formdata']=array(
			'amount'=>$amount+$processing_fee,
			'org_amt'=>$amount,
			'fee'=>$processing_fee,
			'notify_url'=>get_link('StripeNotify').$type.'/'.$unique_id,
			'custom'=>md5('PPAY-'.$unique_id),
			'member_id'=>$this->member_id,
			);
			$this->data['formdata']['amount_converted']=$amount;
			$transansaction_data=array('payment_type'=>'STRIPE','content_key'=> $this->data['formdata']['custom']);
			$transansaction_data['request_value']=json_encode( $this->data['formdata']);
			$ins=insert_record('online_transaction_data',$transansaction_data,TRUE);
			if($ins){
				$msg['status'] = 'OK';
				$msg['amount'] = $this->data['formdata']['amount'];
				$msg['custom'] = $this->data['formdata']['custom'];
				$msg['id'] = $unique_id;
			}
		}
		elseif($type=='membership' && $amount>0){
			$id=$this->session->userdata('enc_membership_id_duration');
			if($id){
				list($enc_membership_id,$duration)=explode('-',$id);
				$this->load->model('membership/membership_model');
				$membership=$this->membership_model->getMembershipDetails($enc_membership_id);
				if($membership){
					$site_currency_code=CurrencyCode();
					$unique_id=$this->member_id.'-'.time();
					$feeCalculation=generateProcessingFee('stripe',$amount);
					$processing_fee=$feeCalculation['processing_fee'];
					$this->data['formdata']=array(
					'amount'=>$amount+$processing_fee,
					'org_amt'=>$amount,
					'fee'=>$processing_fee,
					'notify_url'=>get_link('StripeNotify').$type.'/'.$unique_id,
					'custom'=>md5('PPAY-'.$unique_id),
					'member_id'=>$this->member_id,
					'membership_id'=>$membership->membership_id,
					'membership_duration'=>$duration,
					);
					$this->data['formdata']['amount_converted']=$amount;
					$transansaction_data=array('payment_type'=>'STRIPE','content_key'=> $this->data['formdata']['custom']);
					$transansaction_data['request_value']=json_encode( $this->data['formdata']);
					$ins=insert_record('online_transaction_data',$transansaction_data,TRUE);
					if($ins){
						$msg['status'] = 'OK';
						$msg['amount'] = $this->data['formdata']['amount'];
						$msg['custom'] = $this->data['formdata']['custom'];
						$msg['id'] = $unique_id;
					}
				}
				else{
					$msg['status'] = 'FAIL';
					$msg['error'] = 'Invalid amount';	
				}
			}
		}
		else{
			$msg['status'] = 'FAIL';
			$msg['error'] = 'Invalid amount';
		}
		unset($_POST);
		echo json_encode($msg);	
	}
	public function make_payment_stripe(){
		checkrequestajax();
		$i=0;
		$msg=array();
		$secret_key = get_setting('stripe_secret_key'); 
		
		$type=$this->input->post('pay_for');
		$token  = $this->input->post('token');
		$payamount = $this->input->post('amount');
		$method=post('method');
		
		$id=post('custom');
		$verify_token=post('custom');
		//updateTable('online_transaction_data',array('response_value'=>$msg),array('content_key'=>$verify_token));
		$is_valid=0;
		$transaction_data=getData(array(
		'select'=>'request_value,status',
		'table'=>'online_transaction_data',
		'where'=>array('payment_type'=>'STRIPE','content_key'=>$verify_token),
		'single_row'=>TRUE
		));
		if($transaction_data){
			$payment_gross=$payamount;
			$payment_request=json_decode($transaction_data->request_value);
			if($payment_request && $payment_gross>=$payment_request->org_amt && $transaction_data->status==0){
				$is_valid=1;
			}
			
		}
		if($payamount>0 && $is_valid==1){
			$this->load->helper('stripe');
			$api=\Stripe\Stripe::setApiKey($secret_key);
			$charge=array();
			try {
			  $charge = \Stripe\Charge::create(array(
				  'source' => $token,
				  'amount'   => $payamount*100,
				  'currency' => CurrencyCode(),
				   "expand" => array("balance_transaction")
			  ));
			}catch (Exception $e) {
				$error = $e->getMessage();
			}
			if($charge && $charge['paid']){
				$msg['status'] = 'OK';
				$response = json_encode($charge);
				updateTable('online_transaction_data',array('response_value'=>$response),array('content_key'=>$verify_token));
				updateTable('organization',array('is_payment_verified'=>1),array('member_id'=>$payment_request->member_id));
				$stripe_payment=$payment_gross;
				$total=$payment_request->org_amt;
				$order_fee=$payment_request->fee;
				if($type=='addfund'){

				$stripe_details=getWallet(get_setting('STRIPE_WALLET'));
				$stripe_wallet_id=$stripe_details->wallet_id;
				$stripe_wallet_balance=$stripe_details->balance;
				$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
				$fee_wallet_id=$fee_wallet_details->wallet_id;
				$fee_wallet_balance=$fee_wallet_details->balance;	
				
				$member_details=getWalletMember($payment_request->member_id);
				$member_wallet_id=$member_details->wallet_id;
				$member_wallet_balance=$member_details->balance;
				$wallet_transaction_type_id=get_setting('ADD_FUND_STRIPE');
				$current_datetime=date('Y-m-d H:i:s');
				$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
				if($wallet_transaction_id){
					updateTable('online_transaction_data',array('status'=>1,'tran_id'=>$wallet_transaction_id),array('content_key'=>$verify_token));
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$stripe_wallet_id,'debit'=>$stripe_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Stripe');
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$stripe_details->title,
					'TW'=>$member_details->name.' wallet',	
					'TP'=>'Payment_Payment',
					));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
					
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'credit'=>$stripe_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Stripe');
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$stripe_details->title,
						'TW'=>$member_details->name.' wallet',	
						'TP'=>'Wallet_Topup',
						));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
					
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Stripe_fee','relational_data'=>$order_fee);
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$member_details->name.' wallet',
						'TW'=>$fee_wallet_details->title,	
						'TP'=>'Processing_Fee',
						));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
					
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Stripe_fee','relational_data'=>$order_fee);
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$stripe_details->title,
						'TW'=>$fee_wallet_details->title,	
						'TP'=>'Processing_Fee',
						));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
					$new_balance=displayamount($member_wallet_balance,2)+displayamount($total,2);
					updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$member_wallet_id));
					wallet_balance_check($member_wallet_id,array('transaction_id'=>$wallet_transaction_id));
					
					$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
					updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
					wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
					
					$new_balance_stripe=displayamount($stripe_wallet_balance,2)-displayamount($stripe_payment,2);
					updateTable('wallet',array('balance'=>$new_balance_stripe),array('wallet_id'=>$stripe_wallet_id));
					wallet_balance_check($stripe_wallet_id,array('transaction_id'=>$wallet_transaction_id));
					
					/*$RECEIVER_EMAIL=$member_details->member_email;
					$url=get_link('manageproposalURL');
					$template='featured';
					$data_parse=array(
					'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
					'PROPOSAL_URL'=>$url,
					);
					SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
					
					$notificationData=array(
					'sender_id'=>0,
					'receiver_id'=>$seller_details['member']->member_id,
					'template'=>'featured',
					'url'=>$this->config->item('manageproposalURL'),
					'content'=>json_encode(array('PID'=>$proposal_id)),
					);
					$this->notification_model->savenotification($notificationData);*/	
				}
				}	
				elseif($type=='membership'){

				}
				
				
				
				
			}else{
				$msg['status'] = 'FAIL';
				$msg['error'] = $error;
			}
		}else{
			$msg['status'] = 'FAIL';
			$msg['error'] = 'Invalid amount';
		}
		unset($_POST);
		echo json_encode($msg);	
	}
	
	
	public function paypal($type,$id=''){
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
		));
		$amount=$this->session->userdata('add_fund_amt');
		if($type=='addfund' && $amount>0){
			$unique_id=$this->member_id.'-'.time();
			
			$feeCalculation=generateProcessingFee('paypal',$amount);
			$processing_fee=$feeCalculation['processing_fee'];
			$this->data['formdata']=array(
			'amount'=>$amount+$processing_fee,
			'org_amt'=>$amount,
			'fee'=>$processing_fee,
			'return_url'=>get_link('AddFundURL').'?refer=paymentsuccess',
			'cancel_url'=>get_link('AddFundURL').'?refer=paymenterror',
			'notify_url'=>get_link('PaypalNotify').$type.'/'.$unique_id,
			'custom'=>md5('PPAY-'.$unique_id),
			'member_id'=>$this->member_id,
			);
		}
		elseif($type=='membership' && $id){
			$unique_id=$this->member_id.'-'.time();
			list($enc_membership_id,$duration)=explode('-',$id);
			$this->load->model('membership/membership_model');
			$membership=$this->membership_model->getMembershipDetails($enc_membership_id);
			if($membership){
				$amount=($duration=='month' ? $membership->price_per_month:$membership->price_per_year);
				$feeCalculation=generateProcessingFee('paypal',$amount);
				$processing_fee=$feeCalculation['processing_fee'];
				$this->data['formdata']=array(
				'amount'=>$amount+$processing_fee,
				'org_amt'=>$amount,
				'fee'=>$processing_fee,
				'return_url'=>get_link('membershipURL').'?refer=paymentsuccess',
				'cancel_url'=>get_link('membershipURL').'?refer=paymenterror',
				'notify_url'=>get_link('PaypalNotify').$type.'/'.$unique_id,
				'custom'=>md5('PPAY-'.$unique_id),
				'member_id'=>$this->member_id,
				'membership_id'=>$membership->membership_id,
				'membership_duration'=>$duration,
				);
			}
			else{
				redirect(get_link('membershipURL'));
			}
		}
		else{
			redirect(get_link('AddFundURL'));
		}
		
		$transansaction_data=array('payment_type'=>'PAYPAL','content_key'=> $this->data['formdata']['custom']);
		
		$amount= $this->data['formdata']['amount'];
		$site_currency_code=CurrencyCode();
		if($site_currency_code=='AED'){
			$conversion=get_setting('AED_TO_USD');
			$site_currency_code='USD';
			$amount=displayamount( $this->data['formdata']['amount']*$conversion);
		}
		$this->data['formdata']['amount_converted']=$amount;
		
		$transansaction_data['request_value']=json_encode( $this->data['formdata']);
		
		
		insert_record('online_transaction_data',$transansaction_data);
		$this->data['formdata']['currency_code']=$site_currency_code;
		$this->data['formdata']['url']='https://www.paypal.com/cgi-bin/webscr';
		$this->data['formdata']['item_name']='Add Fund From';
		$is_sandbox=get_setting('is_sandbox');
		if($is_sandbox){
			 $this->data['formdata']['url']='https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		$this->layout->view('paypal-form', $this->data);

	}
	public function paypalnotify($type,$id=''){
		
		$msg = json_encode($this->input->post());
		//file_put_contents(UPLOAD_PATH.'paypal.log', $msg);
		require(APPPATH.'third_party/PaypalIPN.php');

		$ipn = new PaypalIPN();
		// Use the sandbox endpoint during testing.
		$is_sandbox=get_setting('is_sandbox');
		if($is_sandbox){
			$ipn->useSandbox();
		}
		$verified = $ipn->verifyIPN();
		if ($verified) {
			$verify_token=post('custom');
			updateTable('online_transaction_data',array('response_value'=>$msg),array('content_key'=>$verify_token));
			$token=md5('PPAY-'.$id);
			$is_valid=0;
			$transaction_data=getData(array(
			'select'=>'request_value,status',
			'table'=>'online_transaction_data',
			'where'=>array('payment_type'=>'PAYPAL','content_key'=>$verify_token),
			'single_row'=>TRUE
			));
			if($transaction_data){
				$payment_gross=post('payment_gross');
				$payment_request=json_decode($transaction_data->request_value);
				if($payment_request && $payment_gross>=$payment_request->org_amt && $transaction_data->status==0){
					$is_valid=1;
				}
				
			}
			if($verify_token==$token && $is_valid==1){
				updateTable('organization',array('is_payment_verified'=>1),array('member_id'=>$payment_request->member_id));
				$paypal_payment=$payment_gross;
				$total=$payment_request->org_amt;
				$order_fee=$payment_request->fee;
				if($type=='addfund'){
					$paypal_details=getWallet(get_setting('PAYPAL_WALLET'));
					$paypal_wallet_id=$paypal_details->wallet_id;
					$paypal_wallet_balance=$paypal_details->balance;
					$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
					$fee_wallet_id=$fee_wallet_details->wallet_id;
					$fee_wallet_balance=$fee_wallet_details->balance;	
					
					$member_details=getWalletMember($payment_request->member_id);
					$member_wallet_id=$member_details->wallet_id;
					$member_wallet_balance=$member_details->balance;
						
						
					/*$site_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
					$reciver_wallet_id=$site_details->wallet_id;
					$reciver_wallet_balance=$site_details->balance;*/
					
					$wallet_transaction_type_id=get_setting('ADD_FUND_PAYPAL');
					$current_datetime=date('Y-m-d H:i:s');
					$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
					if($wallet_transaction_id){
						updateTable('online_transaction_data',array('status'=>1,'tran_id'=>$wallet_transaction_id),array('content_key'=>$verify_token));
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$paypal_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$paypal_details->title,
						'TW'=>$member_details->name.' wallet',	
						'TP'=>'Payment_Payment',
						));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'credit'=>$paypal_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$paypal_details->title,
							'TW'=>$member_details->name.' wallet',	
							'TP'=>'Wallet_Topup',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details->name.' wallet',
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$paypal_details->title,
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
						$new_balance=displayamount($member_wallet_balance,2)+displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$member_wallet_id));
						wallet_balance_check($member_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
						updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
						wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance_paypal=displayamount($paypal_wallet_balance,2)-displayamount($paypal_payment,2);
						updateTable('wallet',array('balance'=>$new_balance_paypal),array('wallet_id'=>$paypal_wallet_id));
						wallet_balance_check($paypal_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						/*$RECEIVER_EMAIL=$member_details->member_email;
						$url=get_link('manageproposalURL');
						$template='featured';
						$data_parse=array(
						'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
						'PROPOSAL_URL'=>$url,
						);
						SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
						
						$notificationData=array(
						'sender_id'=>0,
						'receiver_id'=>$seller_details['member']->member_id,
						'template'=>'featured',
						'url'=>$this->config->item('manageproposalURL'),
						'content'=>json_encode(array('PID'=>$proposal_id)),
						);
						$this->notification_model->savenotification($notificationData);*/	
					}
				}
				elseif($type=='membership'){
					$membership_id=$payment_request->membership_id;
					$membership_duraion=$payment_request->membership_duration;
					$this->load->model('membership/membership_model');
					$membership=$this->membership_model->getMembershipDetails(md5($membership_id));
					if($membership){
						$member_details=getWalletMember($payment_request->member_id);
						$member_wallet_id=$member_details->wallet_id;
						$member_wallet_balance=$member_details->balance;

						$site_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;

						$paypal_details=getWallet(get_setting('PAYPAL_WALLET'));
						$paypal_wallet_id=$paypal_details->wallet_id;
						$paypal_wallet_balance=$paypal_details->balance;

						$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;



						//$wallet_transaction_type_id=get_setting('MEMBERSHIP_PAYMENT_PAYPAL');
						$wallet_transaction_type_id=get_setting('ADD_FUND_PAYPAL');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
						$member_membership_log=array(
							'member_id'=>$payment_request->member_id,
							'membership_id'=>$membership_id,
							'membership_duration'=>$membership_duraion,
							'reg_date'=>date('Y-m-d H:i:s'),
						);
						insert_record('member_membership_log',$member_membership_log);
						$check=$this->db->select('is_free,membership_expire_date,membership_id')->where('member_id',$payment_request->member_id)->from('member_membership')->get()->row();
						$member_membership=array(
							'membership_id'=>$membership_id,
							'is_free'=>0,
							//'membership_expire_date'=>$membership_expire_date,
							'max_bid'=>$membership->membership_bid,
							'max_portfolio'=>$membership->membership_portfolio,
							'max_skills'=>$membership->membership_skills,
							'commission_percent'=>$membership->membership_commission_percent,
						);
						if($membership_duraion=='year'){
							$dura='+ 1 year';
						}else{
							$dura='+ 1 month';
						}
						if($check){
							if($check->is_free){
								$membership_expire_date=date('Y-m-d',strtotime($dura));
							}elseif($membership_id==$check->membership_id){
								$membership_expire_date=date('Y-m-d',strtotime($dura,strtotime($check->membership_expire_date)));
							}else{
								$membership_expire_date=date('Y-m-d',strtotime($dura));
							}
							$member_membership['membership_expire_date']=$membership_expire_date;
							updateTable('member_membership',$member_membership,array('member_id'=>$payment_request->member_id));
						}else{
							$membership_expire_date=date('Y-m-d',strtotime($dura));
							$member_membership['membership_expire_date']=$membership_expire_date;
							$member_membership['member_id']=$payment_request->member_id;
							insert_record('member_membership',$member_membership);
						}
						
						updateTable('online_transaction_data',array('status'=>1,'tran_id'=>$wallet_transaction_id),array('content_key'=>$verify_token));
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$paypal_wallet_id,'debit'=>$paypal_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$paypal_details->title,
						'TW'=>$member_details->name.' wallet',	
						'TP'=>'Payment_Payment',
						));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'credit'=>$paypal_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Paypal');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$paypal_details->title,
							'TW'=>$member_details->name.' wallet',	
							'TP'=>'Wallet_Topup',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details->name.' wallet',
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Paypal_fee','relational_data'=>$order_fee);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$paypal_details->title,
							'TW'=>$fee_wallet_details->title,	
							'TP'=>'Processing_Fee',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);

						$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
						updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
						wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance_paypal=displayamount($paypal_wallet_balance,2)-displayamount($paypal_payment,2);
						updateTable('wallet',array('balance'=>$new_balance_paypal),array('wallet_id'=>$paypal_wallet_id));
						wallet_balance_check($paypal_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						



						$wallet_transaction_type_id=get_setting('MEMBERSHIP_PAYMENT_PAYPAL');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){

						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'debit'=>$total,'description_tkey'=>'MSID','relational_data'=>$membership_id);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details->name.' wallet',
							'TW'=>$site_details->title,
							'TP'=>'Membership_Payment',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'MSID','relational_data'=>$membership_id);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details->name.' wallet',
							'TW'=>$site_details->title,
							'TP'=>'Membership_Payment',
							));
						insertTable('wallet_transaction_row',$insert_wallet_transaction_row);
						}
						
						
						$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
						wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						
						}	


					}

				}
			}else{
					error_log('invalid request log for token '.$verify_token);
			}
		}
		// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
		header("HTTP/1.1 200 OK");
	}
	public function ngeniusnotify(){
	    $json = file_get_contents("php://input");
	    //file_put_contents(ABS_USERUPLOAD_PATH.'ngenius.log', $json);
	    $order = json_decode($json);
	    if($order){
	    	$verify_token=$ref=$order->order->reference;
	    	$eventName=$order->eventName;
	    	$transaction_data=getData(array(
			'select'=>'request_value',
			'table'=>'online_transaction_data',
			'where'=>array('payment_type'=>'NGENIUS','content_key'=>$verify_token),
			'single_row'=>TRUE
			));
			if($transaction_data && $eventName=='CAPTURED'){
				updateTable('online_transaction_data',array('response_value'=>$json),array('content_key'=>$verify_token));
				$payment_request=json_decode($transaction_data->request_value);
				$type=$payment_request->payment_type;
				$cart_id=$payment_request->cart_id;
				if($type=='checkout'){
					$id=$cart_id;
					$order_status=ORDER_PROCESSING;
					
					$arr=array(
						'select'=>'o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction',
						'table'=>'orders as o',
						'join'=>array(
						array('table'=>'proposals as p','on'=>'o.proposal_id=p.proposal_id','position'=>'left'),
						array('table'=>'proposal_additional as p_a','on'=>'o.proposal_id=p_a.proposal_id','position'=>'left'),
						),
						'where'=>array('o.order_id'=>$id,'o.order_status'=>0),
						'single_row'=>TRUE
					);
					$orderDetails=getData($arr);
					if($orderDetails){
						$total=$orderDetails->order_price;
						$order_id=$orderDetails->order_id;
						if(!empty($orderDetails->buyer_instruction)){
							$order_status=ORDER_PENDING;
						}
						$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
						$buyer_wallet_id=$buyer_details['member']->wallet_id;
						$buyer_wallet_balance=$buyer_details['member']->balance;
						$site_details=getWallet(get_setting('SITE_MAIN_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$buyer_details['member']->member_name;
						$ngenius_details=getWallet(get_setting('NGENIUS_WALLET'));
						$ngenius_wallet_id=$ngenius_details->wallet_id;
						$ngenius_wallet_balance=$ngenius_details->balance;
						$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;
						$order_fee=$orderDetails->order_fee;
						
						$wallet_transaction_type_id=get_setting('ORDER_PAYMENT_NGENIUS');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
							insert_record('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
							$ngenius_payment=$total+$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$ngenius_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Ngenius_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Ngenius_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);*/	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_Topup',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Ngenius_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,	
								'TP'=>'Order_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,	
								'TP'=>'Order_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
							wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
							updateTable('conversations_messages_offers',array('status'=>1),array('order_id'=>$order_id));
							updateTable('send_offers',array('status'=>1),array('order_id'=>$order_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_ngenius=displayamount($ngenius_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance_ngenius),array('wallet_id'=>$ngenius_wallet_id));
							wallet_balance_check($ngenius_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('OrderDetailsURL').$order_id;
							$template='new-order';
							$data_parse=array(
							'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
							'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
							'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
							'QTY'=>$orderDetails->order_qty,
							'DELIVERY_TIME'=>$orderDetails->order_duration,
							'ORDER_PRICE'=>$orderDetails->order_price,
							'ORDER_DETAILS_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							SendMail('',get_setting('admin_email'),$template,$data_parse);
							
							$notificationData=array(
							'sender_id'=>$orderDetails->buyer_id,
							'receiver_id'=>$orderDetails->seller_id,
							'template'=>'order',
							'url'=>$this->config->item('OrderDetailsURL').$order_id,
							'content'=>json_encode(array('OID'=>$order_id)),
							);
							$this->notification_model->savenotification($notificationData);
						}
					}
				}elseif($type=='featured'){
					$ids=explode('-',$cart_id);
					$id=$ids[0];
					$featured_fee=get_setting('featured_fee');
					$feeCalculation=generateProcessingFee('ngenius',$featured_fee);
					$order_fee=$feeCalculation['processing_fee'];
					$featured_duration=get_setting('featured_duration');
					$total=$featured_fee;
					$arr=array(
						'select'=>'p.proposal_id,p.proposal_seller_id',
						'table'=>'proposals p',
						'where'=>array('p.proposal_id'=>$id),
						'single_row'=>true,
					);
					$check_proposal=getData($arr);
					IF($check_proposal){
						$proposal_id=$check_proposal->proposal_id;
						$seller_details=getMemberDetails($check_proposal->proposal_seller_id,array('main'=>1));
						$seller_wallet_id=$seller_details['member']->wallet_id;
						$seller_wallet_balance=$seller_details['member']->balance;
						$site_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
						$reciver_wallet_id=$site_details->wallet_id;
						$reciver_wallet_balance=$site_details->balance;
						$recipient_relational_data=$seller_details['member']->member_name;
						$ngenius_details=getWallet(get_setting('NGENIUS_WALLET'));
						$ngenius_wallet_id=$ngenius_details->wallet_id;
						$ngenius_wallet_balance=$ngenius_details->balance;
						$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;
						
						
						$wallet_transaction_type_id=get_setting('FEATURED_PAYMENT_NGENIUS');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$featured_end_date=date('Y-m-d H:i:s',strtotime('+'.$featured_duration.' days'));
							updateTable('proposal_settings',array('proposal_featured'=>1,'featured_end_date'=>$featured_end_date),array('proposal_id'=>$proposal_id));
							$ngenius_payment=$total+$order_fee;
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$ngenius_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$seller_details['member']->member_name.' wallet',	
								'TP'=>'Payment_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							/*$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$order_fee,'description_tkey'=>'Ngenius_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);*/	
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$seller_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_Topup',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Ngenius_fee','relational_data'=>$order_fee);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$fee_wallet_details->title,	
								'TP'=>'Processing_Fee',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$seller_wallet_id,'debit'=>$total,'description_tkey'=>'PID','relational_data'=>$proposal_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$seller_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Featured_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$seller_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Featured_Payment',
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
							wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
							updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
							wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							$new_balance_ngenius=displayamount($ngenius_wallet_balance,2)-displayamount($order_fee,2)-displayamount($total,2);
							updateTable('wallet',array('balance'=>$new_balance_ngenius),array('wallet_id'=>$ngenius_wallet_id));
							wallet_balance_check($ngenius_wallet_id,array('transaction_id'=>$wallet_transaction_id));
							
							
							$RECEIVER_EMAIL=$seller_details['member']->member_email;
							$url=get_link('manageproposalURL');
							$template='featured';
							$data_parse=array(
							'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
							'PROPOSAL_URL'=>$url,
							);
							SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
							
							$notificationData=array(
							'sender_id'=>0,
							'receiver_id'=>$seller_details['member']->member_id,
							'template'=>'featured',
							'url'=>$this->config->item('manageproposalURL'),
							'content'=>json_encode(array('PID'=>$proposal_id)),
							);
							$this->notification_model->savenotification($notificationData);
						
							
						}
					}	
				}elseif($type=='cart'){
					$allids=explode('-',$id);
					$allorder=$this->db->select('o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url,p_a.buyer_instruction')
					->from('orders as o')
					->join('proposals as p','o.proposal_id=p.proposal_id','left')
					->join('proposal_additional as p_a','o.proposal_id=p_a.proposal_id','left')
					->where_in('order_id',$allids)
					->where('o.order_status',0);
					$allorder=$this->db->get()->result();
					if($allorder){
						foreach($allorder as $orderDetails){
							$total=$orderDetails->order_price;
							$order_id=$orderDetails->order_id;
							if(!empty($orderDetails->buyer_instruction)){
								$order_status=ORDER_PENDING;
							}
							$buyer_details=getMemberDetails($orderDetails->buyer_id,array('main'=>1));
							$buyer_wallet_id=$buyer_details['member']->wallet_id;
							$buyer_wallet_balance=$buyer_details['member']->balance;
							$ngenius_details=getWallet(get_setting('NGENIUS_WALLET'));
							$ngenius_wallet_id=$ngenius_details->wallet_id;
							$ngenius_wallet_balance=$ngenius_details->balance;
							$site_details=getWallet(get_setting('SITE_MAIN_WALLET'));
							$reciver_wallet_id=$site_details->wallet_id;
							$reciver_wallet_balance=$site_details->balance;
							$recipient_relational_data=$buyer_details['member']->member_name;
							$wallet_transaction_type_id=get_setting('ORDER_PAYMENT_NGENIUS');
							$current_datetime=date('Y-m-d H:i:s');
							$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
							if($wallet_transaction_id){
								updateTable('orders',array('order_active'=>1,'order_status'=>$order_status),array('order_id'=>$order_id));
								insert_record('orders_transaction',array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id));
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$ngenius_wallet_id,'debit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Ngenius_Payment',
								));
								insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'credit'=>$total,'description_tkey'=>'Online_payment_from','relational_data'=>'Ngenius');
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$ngenius_details->title,
								'TW'=>$buyer_details['member']->member_name.' wallet',	
								'TP'=>'Wallet_topup',
								));
								insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$buyer_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
								insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
								$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$buyer_details['member']->member_name.' wallet',
								'TW'=>$site_details->title,
								'TP'=>'Order_Payment',
								));
								insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
								
								$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
								updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
								wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));	
								$new_balance=displayamount($ngenius_wallet_balance,2)-displayamount($total,2);
								updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$ngenius_wallet_id));
								wallet_balance_check($ngenius_wallet_id,array('transaction_id'=>$wallet_transaction_id));
								
								$seller_details=getMemberDetails($orderDetails->seller_id,array('main'=>1));
								$RECEIVER_EMAIL=$seller_details['member']->member_email;
								$url=get_link('OrderDetailsURL').$order_id;
								$template='new-order';
								$data_parse=array(
								'BUYER_NAME'=>getUserName($buyer_details['member']->member_id),
								'SELLER_NAME'=>getUserName($seller_details['member']->member_id),
								'PROPOSAL_TITLE'=>$orderDetails->proposal_title,
								'QTY'=>$orderDetails->order_qty,
								'DELIVERY_TIME'=>$orderDetails->order_duration,
								'ORDER_PRICE'=>$orderDetails->order_price,
								'ORDER_DETAILS_URL'=>$url,
								);
								SendMail('',$RECEIVER_EMAIL,$template,$data_parse);
								SendMail('',get_setting('admin_email'),$template,$data_parse);
								
								$notificationData=array(
								'sender_id'=>$orderDetails->buyer_id,
								'receiver_id'=>$orderDetails->seller_id,
								'template'=>'order',
								'url'=>$this->config->item('OrderDetailsURL').$order_id,
								'content'=>json_encode(array('OID'=>$order_id)),
								);
								$this->notification_model->savenotification($notificationData);
							}
						}
					}
				}
				
				
				
			}
		}
 		echo '{"success": true}';
	}
}
