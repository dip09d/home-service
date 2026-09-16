<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membership extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}else{
			redirect(URL::get_link('loginURL').'?ref=membershipURL');
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		$this->load->model('membership_model');
		parent::__construct();
	}
	public function index()
	{
		if($this->access_member_type=='E'){
			redirect(URL::get_link('dashboardURL'));
		}
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
		));
		$this->layout->set_title('Membership');
		$this->layout->set_meta('author', 'Dev Sharma');
		$this->layout->set_meta('keywords', 'Freelancer Script, Freelancer, New Flance');
		$this->layout->set_meta('description', 'Freelancer Clone Script');
		$this->data['membership']=$this->membership_model->getMembership();
		$this->data['selected_membership']=getData(array(
			'select'=>'m.membership_id,m.membership_expire_date,m.is_free',
			'table'=>'member_membership m',
			'where'=>array('m.member_id'=>$this->member_id),
			'single_row'=>TRUE
			));
		if($this->data['selected_membership']){
			$this->data['selected_membership']->details=$this->membership_model->getMembershipDetails(md5($this->data['selected_membership']->membership_id));
		}
		$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
		$this->layout->view('membership',$this->data);
	}
	public function select_membership($enc_membership_id='',$duration='')
	{
		if($this->access_member_type=='E'){
			redirect(URL::get_link('dashboardURL'));
		}
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
		));
		$this->layout->set_title('Membership');
		$this->layout->set_meta('author', 'Dev Sharma');
		$this->layout->set_meta('keywords', 'Freelancer Script, Freelancer, New Flance');
		$this->layout->set_meta('description', 'Freelancer Clone Script');
		$this->data['membership']=$this->membership_model->getMembershipDetails($enc_membership_id);
		$this->data['duration']=$duration;

		$this->data['member_details']=getWalletMember($this->member_id);
	
		$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
		$this->layout->view('selected-membership',$this->data);
	}
	public function processmembership($enc_membership_id='',$duration=''){
		checkrequestajax();
		$msg=array();
		$method=post('method');
		$okey=post('okey');
		$membership=$this->membership_model->getMembershipDetails($enc_membership_id);
		if($membership){
			if($method=='wallet'){
				$membership_id=$membership->membership_id;
				$membership_duraion=$duration;

				$amount=($duration=='month' ? $membership->price_per_month:$membership->price_per_year);
				$processing_fee=0;
				$total=$amount+$processing_fee;
				$member_details=getWalletMember($this->member_id);
				$member_wallet_id=$member_details->wallet_id;
				$member_wallet_balance=$member_details->balance;

				$site_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
				$reciver_wallet_id=$site_details->wallet_id;
				$reciver_wallet_balance=$site_details->balance;


				if($member_details && $member_wallet_balance>=$total){
					$wallet_transaction_type_id=get_setting('MEMBERSHIP_PAYMENT_WALLET');
					$current_datetime=date('Y-m-d H:i:s');
					$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
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
						
						$member_new_balance=displayamount($member_wallet_balance,2)-displayamount($total,2);
						updateTable('wallet',array('balance'=>$member_new_balance),array('wallet_id'=>$member_wallet_id));
						wallet_balance_check($member_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$new_balance=displayamount($reciver_wallet_balance,2)+displayamount($total,2);
						updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$reciver_wallet_id));
						wallet_balance_check($reciver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
						
						$member_membership_log=array(
							'member_id'=>$this->member_id,
							'membership_id'=>$membership_id,
							'membership_duration'=>$membership_duraion,
							'reg_date'=>date('Y-m-d H:i:s'),
						);
						insert_record('member_membership_log',$member_membership_log);
						$check=$this->db->select('is_free,membership_expire_date,membership_id')->where('member_id',$this->member_id)->from('member_membership')->get()->row();
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
							updateTable('member_membership',$member_membership,array('member_id'=>$this->member_id));
						}else{
							$membership_expire_date=date('Y-m-d',strtotime($dura));
							$member_membership['membership_expire_date']=$membership_expire_date;
							$member_membership['member_id']=$this->member_id;
							insert_record('member_membership',$member_membership);
						}
	
						$msg['status'] = 'OK';
						$msg['redirect'] =get_link('membershipURL').'?refer=paymentsuccess';
					}else{
						$msg['status'] = 'FAIL';
						$msg['message'] = 'transaction error';
					}
				}else{
					$msg['status'] = 'FAIL';
					$msg['message'] = 'Insufficient fund';
				}					
					
					
				
			}
			elseif($method=='paypal'){
				$this->session->set_userdata('add_fund_amt',$okey);
				$msg['status'] = 'OK';
				$msg['redirect'] =get_link('PaypalCheckOut').'membership/'.$enc_membership_id.'-'.$duration;
			}
			elseif($method=='stripe'){
				$this->session->set_userdata('add_fund_amt',$okey);
				$msg['status'] = 'OK';
				$msg['redirect'] =get_link('StripeCheckOut').'membership/'.$enc_membership_id.'-'.$duration;
			}
		}
		else{
			$msg['status'] = 'FAIL';
			$msg['error'] = 'Invalid payment option';
		}
		
		unset($_POST);
		echo json_encode($msg);	
	}
	
}
