<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function stripe_create_transaction($data){
		if($data){
			$msg = json_encode($data);
			$verify_token=$data->client_reference_id;
			updateTable('online_transaction_data',array('response_value'=>$msg),array('content_key'=>$verify_token));
			$is_valid=0;
			$transaction_data=getData(array(
				'select'=>'request_value,status',
				'table'=>'online_transaction_data',
				'where'=>array('payment_type'=>'STRIPE','content_key'=>$verify_token),
				'single_row'=>TRUE
			));
			if($transaction_data){
				$payment_request=json_decode($transaction_data->request_value);
				//updateTable('organization',array('is_payment_verified'=>1),array('member_id'=>$payment_request->member_id));
				$stripe_payment=$payment_request->amount;
				$total=$payment_request->org_amt;
				$order_fee=$payment_request->fee;
				$type=$payment_request->type;
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
					$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
					if($wallet_transaction_id){
						updateTable('online_transaction_data',array('status'=>0,'tran_id'=>$wallet_transaction_id),array('content_key'=>$verify_token));
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$stripe_wallet_id,'debit'=>$stripe_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Stripe');
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$stripe_details->title,
						'TW'=>$member_details->name.' wallet',	
						'TP'=>'Payment_Payment',
						));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);

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

						$stripe_details=getWallet(get_setting('STRIPE_WALLET'));
						$stripe_wallet_id=$stripe_details->wallet_id;
						$stripe_wallet_balance=$stripe_details->balance;

						$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;

						$wallet_transaction_type_id=get_setting('ADD_FUND_STRIPE');
						//$wallet_transaction_type_id=get_setting('MEMBERSHIP_PAYMENT_STRIPE');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							updateTable('online_transaction_data',array('status'=>0,'tran_id'=>$wallet_transaction_id),array('content_key'=>$verify_token));
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$stripe_wallet_id,'debit'=>$stripe_payment,'description_tkey'=>'Online_payment_from','relational_data'=>'Stripe');
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$stripe_details->title,
							'TW'=>$member_details->name.' wallet',	
							'TP'=>'Payment_Payment',
							));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						}	
					}
				}
			}
		}
	}
	public function stripe_paid_transaction($data){
		if($data){
			$msg = json_encode($data);
			$verify_token=$data->client_reference_id;
			updateTable('online_transaction_data',array('response_value'=>$msg),array('content_key'=>$verify_token));
			$is_valid=0;
			$transaction_data=getData(array(
				'select'=>'request_value,status,tran_id',
				'table'=>'online_transaction_data',
				'where'=>array('payment_type'=>'STRIPE','content_key'=>$verify_token),
				'single_row'=>TRUE
			));
			if($transaction_data){
				$payment_request=json_decode($transaction_data->request_value);
				updateTable('organization',array('is_payment_verified'=>1),array('member_id'=>$payment_request->member_id));
				$stripe_payment=$payment_request->amount;
				$total=$payment_request->org_amt;
				$order_fee=$payment_request->fee;
				$type=$payment_request->type;
				$wallet_transaction_id=$transaction_data->tran_id;
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
					if($wallet_transaction_id){
						updateTable('online_transaction_data',array('status'=>1),array('content_key'=>$verify_token));
						updateTable('wallet_transaction',array('status'=>1),array('wallet_transaction_id'=>$wallet_transaction_id));
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

						$stripe_details=getWallet(get_setting('STRIPE_WALLET'));
						$stripe_wallet_id=$stripe_details->wallet_id;
						$stripe_wallet_balance=$stripe_details->balance;

						$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
						$fee_wallet_id=$fee_wallet_details->wallet_id;
						$fee_wallet_balance=$fee_wallet_details->balance;


						$wallet_transaction_type_id=get_setting('ADD_FUND_STRIPE');
						//$wallet_transaction_type_id=get_setting('MEMBERSHIP_PAYMENT_STRIPE');
						$current_datetime=date('Y-m-d H:i:s');
	
						if($wallet_transaction_id){
						updateTable('wallet_transaction',array('status'=>1),array('wallet_transaction_id'=>$wallet_transaction_id));
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
						
						updateTable('online_transaction_data',array('status'=>1),array('content_key'=>$verify_token));
				
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
						
						$new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
						updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
						wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance_stripe=displayamount($stripe_wallet_balance,2)-displayamount($stripe_payment,2);
						updateTable('wallet',array('balance'=>$new_balance_stripe),array('wallet_id'=>$stripe_wallet_id));
						wallet_balance_check($stripe_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						


						$wallet_transaction_type_id=get_setting('MEMBERSHIP_PAYMENT_STRIPE');
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){

						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'debit'=>$total,'description_tkey'=>'MSID','relational_data'=>$membership_id);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details->name.' wallet',
							'TW'=>$site_details->title,
							'TP'=>'Membership_Payment',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'MSID','relational_data'=>$membership_id);
						$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$member_details->name.' wallet',
							'TW'=>$site_details->title,
							'TP'=>'Membership_Payment',
							));
						insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						}

						
						$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
						wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						}	
					}
				}
			}
		}
	}
	public function stripe_failed_transaction($data){
		if($data){
			$msg = json_encode($data);
			$verify_token=$data->client_reference_id;
			updateTable('online_transaction_data',array('response_value'=>$msg,'status'=>1),array('content_key'=>$verify_token));
			$is_valid=0;
			$transaction_data=getData(array(
				'select'=>'request_value,status,tran_id',
				'table'=>'online_transaction_data',
				'where'=>array('payment_type'=>'STRIPE','content_key'=>$verify_token),
				'single_row'=>TRUE
			));
			if($transaction_data){
				$payment_request=json_decode($transaction_data->request_value);
				$wallet_transaction_id=$transaction_data->tran_id;
				$type=$payment_request->type;
				if($wallet_transaction_id){
					updateTable('wallet_transaction',array('status'=>2),array('wallet_transaction_id'=>$wallet_transaction_id));	
				}
				if($type=='addfund'){

				}
				elseif($type=='membership'){
					
				}
			}
		}
	}
}
