<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	
	public function getContracts($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,c.is_contract_ended,c.contract_end_date')
			->from('project_contract c')
			->join('project p', 'c.project_id=p.project_id', 'INNER')
			->join('project_owner o', 'p.project_id=o.project_id', 'left');
		if($srch){
			if(array_key_exists('contractor_id', $srch)){
				$this->db->where('c.contractor_id', $srch['contractor_id']);
			}
			if(array_key_exists('owner_id', $srch)){
				$this->db->where('o.member_id', $srch['owner_id']);
			}
			if(array_key_exists('contract_status', $srch)){
				$this->db->where('c.contract_status', $srch['contract_status']);
			}
			if(array_key_exists('show', $srch)){
				if($srch['show']=='pending'){
					$this->db->where('c.is_contract_ended <>', 1);
				}elseif($srch['show']=='completed'){
					$this->db->where('c.is_contract_ended', 1);
				}
				
			}
		}
		$this->db->group_by('c.contract_id');
		$this->db->order_by('c.contract_id','desc');
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}
	public function getEscrowAmount($project_id='',$contract_id=''){
		$amount=0;
		$this->db->select('(sum(credit)-sum(debit)) as balance')->where('project_id',$project_id);
		if($contract_id){
			$this->db->where('contract_id',$contract_id);
		}
		$amountbal=$this->db->where('status',1)->get('project_payment_escrow')->row_array();
		if($amountbal && $amountbal['balance']){
			$amount=$amountbal['balance'];
		}
		return $amount;
	}
	public function getMilestonePaid($project_id='',$contract_id=''){
		$amount=0;
		$this->db->select('sum(debit) as balance')->where('project_id',$project_id);
		if($contract_id){
			$this->db->where('contract_id',$contract_id);
		}
		$amountbal=$this->db->where('status',1)->get('project_payment_escrow')->row_array();
		if($amountbal && $amountbal['balance']){
			$amount=$amountbal['balance'];
		}
		return $amount;
	}
	public function getMilestoneDisputed($project_id='',$contract_id=''){
		$amount=0;
		$this->db->select('sum(disputed_amount) as balance');
		if($contract_id){
			$this->db->where('contract_id',$contract_id);
		}
		$amountbal=$this->db->get('project_contract_dispute')->row_array();
		if($amountbal && $amountbal['balance']){
			$amount=$amountbal['balance'];
		}
		return $amount;
	}
	public function getMilestoneNotStart($project_id='',$contract_id=''){
		$amount=0;
		$this->db->select('sum(milestone_amount) as balance');
		if($contract_id){
			$this->db->where('contract_id',$contract_id);
		}
		$this->db->where('is_escrow',0);
		$amountbal=$this->db->get('project_contract_milestone')->row_array();
		if($amountbal && $amountbal['balance']){
			$amount=$amountbal['balance'];
		}
		return $amount;
	}
	public function getMilestoneDisputedRefund($project_id='',$contract_id=''){
		$amount=0;
		$this->db->select('sum(owner_amount) as balance');
		if($contract_id){
			$this->db->where('contract_id',$contract_id);
		}
		$amountbal=$this->db->get('project_contract_dispute')->row_array();
		if($amountbal && $amountbal['balance']){
			$amount=$amountbal['balance'];
		}
		return $amount;
	}
	
	
	public function addFundToEscrow($pid='',$member_id='',$contract_id='',$pay_amount=0){
		$member_details=getWalletMember($member_id);
		$member_wallet_id=$member_details->wallet_id;
		$member_wallet_balance=$member_details->balance;
		$escrow_details=getWallet(get_setting('ESCROW_WALLET'));
		$escrow_wallet_id=$escrow_details->wallet_id;
		$escrow_wallet_balance=$escrow_details->balance;
		
		$wallet_transaction_type_id=get_setting('ESCROW_DEPOSIT');
		$current_datetime=date('Y-m-d H:i:s');
		$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
		if($wallet_transaction_id){
			
			$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'debit'=>$pay_amount,'description_tkey'=>'Project_Payment','relational_data'=>'Escrow Deposit');
			$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
			'FW'=>$member_details->name.' wallet',
			'TW'=>$escrow_details->title,	
			'TP'=>'Ecrow_Payment',
			'PID'=>$pid,
			'CID'=>$contract_id,
			));
			insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
			
			$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$escrow_wallet_id,'credit'=>$pay_amount,'description_tkey'=>'Project_Payment','relational_data'=>'Escrow Deposit');
			$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$member_details->name.' wallet',
				'TW'=>$escrow_details->title,	
				'TP'=>'Ecrow_Payment',
				'PID'=>$pid,
				'CID'=>$contract_id,
				));
			insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
			
			$new_balance=displayamount($member_wallet_balance,2)-displayamount($pay_amount,2);
			updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$member_wallet_id));
			wallet_balance_check($member_wallet_id,array('transaction_id'=>$wallet_transaction_id));
			
			$new_balance_escrow=displayamount($escrow_wallet_balance,2)+displayamount($pay_amount,2);
			updateTable('wallet',array('balance'=>$new_balance_escrow),array('wallet_id'=>$escrow_wallet_id));
			wallet_balance_check($escrow_wallet_id,array('transaction_id'=>$wallet_transaction_id));
			
			insert_record('project_payment_escrow',array('project_id'=>$pid,'credit'=>$pay_amount,'trn_id'=>$wallet_transaction_id,'status'=>1,'contract_id'=>$contract_id));
		}
		return $wallet_transaction_id;
	}
	public function ReleaseFundForContract($contract_milestone_id){
		$contractDetails=getData(array(
			'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,m.contract_milestone_id,m.milestone_title,m.milestone_amount',
			'table'=>'project_contract_milestone m',
			'join'=>array(
				array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
				array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
				array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
			),
			'where'=>array('m.contract_milestone_id'=>$contract_milestone_id,'c.contract_status'=>1),
			'single_row'=>TRUE
		));
		if($contractDetails){
			$pid=$contractDetails->project_id;
			$contract_id=$contractDetails->contract_id;
			$worker_details=getWalletMember($contractDetails->contractor_id);
			$worker_wallet_id=$worker_details->wallet_id;
			$worker_wallet_balance=$worker_details->balance;
			
			$escrow_details=getWallet(get_setting('ESCROW_WALLET'));
			$escrow_wallet_id=$escrow_details->wallet_id;
			$escrow_wallet_balance=$escrow_details->balance;
			
			$profit_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
			$profit_wallet_id=$profit_details->wallet_id;
			$profit_wallet_balance=$profit_details->balance;
			
			$milestone_amount=$contractDetails->milestone_amount;
			$sitecommission_fee_amount=0;
			$sitecommission=getSiteCommissionFee($contractDetails->contractor_id,$pid);
			if($sitecommission>0){
				$sitecommission_fee_amount=displayamount(($milestone_amount*$sitecommission)/100);
			}
			
			$wallet_transaction_type_id=get_setting('ESCROW_RELEASE');
			$current_datetime=date('Y-m-d H:i:s');
			$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
			if($wallet_transaction_id){
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$escrow_wallet_id,'debit'=>$milestone_amount,'description_tkey'=>'Project_Payment','relational_data'=>'Escrow Release');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$escrow_details->title,
				'TW'=>$worker_details->name.' wallet',	
				'TP'=>'Ecrow_Payment',
				'PID'=>$pid,
				'CID'=>$contract_id,
				'CMID'=>$contract_milestone_id,
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$worker_wallet_id,'credit'=>$milestone_amount,'description_tkey'=>'Project_Payment','relational_data'=>'Escrow Release');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$escrow_details->title,
					'TW'=>$worker_details->name.' wallet',	
					'TP'=>'Ecrow_Payment',
					'PID'=>$pid,
					'CID'=>$contract_id,
					'CMID'=>$contract_milestone_id,
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				/** Site commission start **/
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$worker_wallet_id,'debit'=>$sitecommission_fee_amount,'description_tkey'=>'Project_Commision_','relational_data'=>$sitecommission.'%');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$worker_details->name.' wallet',
				'TW'=>$profit_details->title,	
				'TP'=>'Commission_Payment',
				'PID'=>$pid,
				'CID'=>$contract_id,
				'CMID'=>$contract_milestone_id,
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$sitecommission_fee_amount,'description_tkey'=>'Project_Commision','relational_data'=>$sitecommission.'%');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$worker_details->name.' wallet',
					'TW'=>$profit_details->title,	
					'TP'=>'Commission_Payment',
					'PID'=>$pid,
					'CID'=>$contract_id,
					'CMID'=>$contract_milestone_id,
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				/** Site commission start end **/
				
				$new_balance_escrow=displayamount($escrow_wallet_balance,2)-displayamount($milestone_amount,2);
				updateTable('wallet',array('balance'=>$new_balance_escrow),array('wallet_id'=>$escrow_wallet_id));
				wallet_balance_check($escrow_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				
				$new_balance=displayamount($worker_wallet_balance,2)+displayamount($milestone_amount,2)-displayamount($sitecommission_fee_amount,2);
				updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$worker_wallet_id));
				wallet_balance_check($worker_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				$new_balance_profit=displayamount($profit_wallet_balance,2)+displayamount($sitecommission_fee_amount,2);
				updateTable('wallet',array('balance'=>$new_balance_profit),array('wallet_id'=>$profit_wallet_id));
				wallet_balance_check($profit_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				insert_record('project_payment_escrow',array('project_id'=>$pid,'debit'=>$milestone_amount,'trn_id'=>$wallet_transaction_id,'status'=>1,'contract_id'=>$contract_id));
				
				updateMemberEarning($contractDetails->contractor_id);
				updateMemberSpent($contractDetails->owner_id);
				return $wallet_transaction_id;
			}
		}
	}
	public function ReleaseFundForContractInvoice($invoice_id){
		$contractDetails=getData(array(
			'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,pci.invoice_id,sum((ir.invoice_row_amount*ir.invoice_row_unit_price)) as milestone_amount',
			'table'=>'project_contract_invoice as pci',
			'join'=>array(
				array('table'=>'project_contract c', 'on'=>'pci.contract_id=c.contract_id', 'position'=>'left'),
				array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
				array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
				array('table'=>'invoice_row ir', 'on'=>'pci.invoice_id=ir.invoice_id', 'position'=>'left'),
			),
			'where'=>array('pci.invoice_id'=>$invoice_id),
			'single_row'=>TRUE,
			'group'=>'pci.invoice_id',
		));
		if($contractDetails){
			$pid=$contractDetails->project_id;
			$contract_id=$contractDetails->contract_id;
			$worker_details=getWalletMember($contractDetails->contractor_id);
			$worker_wallet_id=$worker_details->wallet_id;
			$worker_wallet_balance=$worker_details->balance;
			
			$escrow_details=getWallet(get_setting('ESCROW_WALLET'));
			$escrow_wallet_id=$escrow_details->wallet_id;
			$escrow_wallet_balance=$escrow_details->balance;
			
			$profit_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
			$profit_wallet_id=$profit_details->wallet_id;
			$profit_wallet_balance=$profit_details->balance;
			
			$milestone_amount=$contractDetails->milestone_amount;
			$sitecommission_fee_amount=0;
			$sitecommission=getSiteCommissionFee($contractDetails->contractor_id,$pid);
			if($sitecommission>0){
				$sitecommission_fee_amount=displayamount(($milestone_amount*$sitecommission)/100);
			}
			
			$wallet_transaction_type_id=get_setting('ESCROW_RELEASE');
			$current_datetime=date('Y-m-d H:i:s');
			$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
			if($wallet_transaction_id){
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$escrow_wallet_id,'debit'=>$milestone_amount,'description_tkey'=>'Project_Payment','relational_data'=>'Escrow Release');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$escrow_details->title,
				'TW'=>$worker_details->name.' wallet',	
				'TP'=>'Ecrow_Payment',
				'PID'=>$pid,
				'CID'=>$contract_id,

				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$worker_wallet_id,'credit'=>$milestone_amount,'description_tkey'=>'Project_Payment','relational_data'=>'Escrow Release');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$escrow_details->title,
					'TW'=>$worker_details->name.' wallet',	
					'TP'=>'Ecrow_Payment',
					'PID'=>$pid,
					'CID'=>$contract_id,
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				/** Site commission start **/
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$worker_wallet_id,'debit'=>$sitecommission_fee_amount,'description_tkey'=>'Project_Commision_','relational_data'=>$sitecommission.'%');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$worker_details->name.' wallet',
				'TW'=>$profit_details->title,	
				'TP'=>'Commission_Payment',
				'PID'=>$pid,
				'CID'=>$contract_id,
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$sitecommission_fee_amount,'description_tkey'=>'Project_Commision','relational_data'=>$sitecommission.'%');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$worker_details->name.' wallet',
					'TW'=>$profit_details->title,	
					'TP'=>'Commission_Payment',
					'PID'=>$pid,
					'CID'=>$contract_id,
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				/** Site commission start end **/
				
				$new_balance_escrow=displayamount($escrow_wallet_balance,2)-displayamount($milestone_amount,2);
				updateTable('wallet',array('balance'=>$new_balance_escrow),array('wallet_id'=>$escrow_wallet_id));
				wallet_balance_check($escrow_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				
				$new_balance=displayamount($worker_wallet_balance,2)+displayamount($milestone_amount,2)-displayamount($sitecommission_fee_amount,2);
				updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$worker_wallet_id));
				wallet_balance_check($worker_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				$new_balance_profit=displayamount($profit_wallet_balance,2)+displayamount($sitecommission_fee_amount,2);
				updateTable('wallet',array('balance'=>$new_balance_profit),array('wallet_id'=>$profit_wallet_id));
				wallet_balance_check($profit_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				insert_record('project_payment_escrow',array('project_id'=>$pid,'debit'=>$milestone_amount,'trn_id'=>$wallet_transaction_id,'status'=>1,'contract_id'=>$contract_id));
				
				updateMemberEarning($contractDetails->contractor_id);
				updateMemberSpent($contractDetails->owner_id);
				return $wallet_transaction_id;
			}
		}
	}
	public function addFundToDispute($pid='',$member_id='',$contract_id='',$pay_amount=0,$contract_milestone_id=''){
		$escrow_details=getWallet(get_setting('ESCROW_WALLET'));
		$escrow_wallet_id=$escrow_details->wallet_id;
		$escrow_wallet_balance=$escrow_details->balance;
		
		$dispute_details=getWallet(get_setting('DISPUTE_WALLET'));
		$dispute_wallet_id=$dispute_details->wallet_id;
		$dispute_wallet_balance=$dispute_details->balance;
		
		
		$wallet_transaction_type_id=get_setting('ESCROW_DISPUTE');
		$current_datetime=date('Y-m-d H:i:s');
		$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
		if($wallet_transaction_id){
			
			$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$escrow_wallet_id,'debit'=>$pay_amount,'description_tkey'=>'Escrow_Payment','relational_data'=>'Dispute Deposit');
			$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
			'FW'=>$escrow_details->title,
			'TW'=>$dispute_details->title,	
			'TP'=>'Escrow_Dispute',
			'PID'=>$pid,
			'CID'=>$contract_id,
			'MID'=>$contract_milestone_id,
			));
			insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
			
			$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$dispute_wallet_id,'credit'=>$pay_amount,'description_tkey'=>'Dispute_Payment','relational_data'=>'Dispute Deposit');
			$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$escrow_details->title,
				'TW'=>$dispute_details->title,	
				'TP'=>'Escrow_Dispute',
				'PID'=>$pid,
				'CID'=>$contract_id,
				'MID'=>$contract_milestone_id,
				));
			insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
			
			$new_balance_escrow=displayamount($escrow_wallet_balance,2)-displayamount($pay_amount,2);
			updateTable('wallet',array('balance'=>$new_balance_escrow),array('wallet_id'=>$escrow_wallet_id));
			wallet_balance_check($escrow_wallet_id,array('transaction_id'=>$wallet_transaction_id));
			
			$new_balance_dispute=displayamount($dispute_wallet_balance,2)+displayamount($pay_amount,2);
			updateTable('wallet',array('balance'=>$new_balance_dispute),array('wallet_id'=>$dispute_wallet_id));
			wallet_balance_check($dispute_wallet_id,array('transaction_id'=>$wallet_transaction_id));
			insert_record('project_payment_escrow',array('project_id'=>$pid,'debit'=>$pay_amount,'trn_id'=>$wallet_transaction_id,'status'=>1,'contract_id'=>$contract_id));
		}
		return $wallet_transaction_id;
	}
	public function ReleaseFundForContractDispute($contract_milestone_id){
		$contractDetails=getData(array(
			'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,m.contract_milestone_id,m.milestone_title,m.milestone_amount,d.commission_amount,d.owner_amount,d.contractor_amount',
			'table'=>'project_contract_milestone m',
			'join'=>array(
				array('table'=>'project_contract c', 'on'=>'m.contract_id=c.contract_id', 'position'=>'left'),
				array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
				array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
				array('table'=>'project_contract_dispute as d','on'=>'m.contract_milestone_id=d.contract_milestone_id','position'=>'left'),
			),
			'where'=>array('m.contract_milestone_id'=>$contract_milestone_id,'c.contract_status'=>1),
			'single_row'=>TRUE
		));
		if($contractDetails){
			$pid=$contractDetails->project_id;
			$contract_id=$contractDetails->contract_id;
			$worker_details=getWalletMember($contractDetails->contractor_id);
			$worker_wallet_id=$worker_details->wallet_id;
			$worker_wallet_balance=$worker_details->balance;
			
			$owner_details=getWalletMember($contractDetails->owner_id);
			$owner_wallet_id=$owner_details->wallet_id;
			$owner_wallet_balance=$owner_details->balance;
			
			$dispute_details=getWallet(get_setting('DISPUTE_WALLET'));
			$dispute_wallet_id=$dispute_details->wallet_id;
			$dispute_wallet_balance=$dispute_details->balance;
			
			$profit_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
			$profit_wallet_id=$profit_details->wallet_id;
			$profit_wallet_balance=$profit_details->balance;
			
			$milestone_amount=$contractDetails->milestone_amount;
			$owner_amount=$contractDetails->owner_amount;
			$sitecommission_fee_amount=$contractDetails->commission_amount;
			$sitecommission=displayamount(($sitecommission_fee_amount*100)/$milestone_amount,2);
			
			$wallet_transaction_type_id=get_setting('DISPUTE_RESOLVE');
			$current_datetime=date('Y-m-d H:i:s');
			$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
			if($wallet_transaction_id){
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$dispute_wallet_id,'debit'=>$milestone_amount,'description_tkey'=>'Project_Payment','relational_data'=>'Dispute Release');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$dispute_details->title,
				'TW'=>$worker_details->name.' wallet',	
				'TP'=>'Ecrow_Payment',
				'PID'=>$pid,
				'CID'=>$contract_id,
				'CMID'=>$contract_milestone_id,
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$worker_wallet_id,'credit'=>$milestone_amount,'description_tkey'=>'Project_Payment','relational_data'=>'Dispute Release');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$dispute_details->title,
					'TW'=>$worker_details->name.' wallet',	
					'TP'=>'Ecrow_Payment',
					'PID'=>$pid,
					'CID'=>$contract_id,
					'CMID'=>$contract_milestone_id,
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				/** Site commission start **/
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$worker_wallet_id,'debit'=>$sitecommission_fee_amount,'description_tkey'=>'Project_Commision','relational_data'=>$sitecommission.'%');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$worker_details->name.' wallet',
				'TW'=>$profit_details->title,	
				'TP'=>'Commission_Payment',
				'PID'=>$pid,
				'CID'=>$contract_id,
				'CMID'=>$contract_milestone_id,
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$sitecommission_fee_amount,'description_tkey'=>'Project_Commision','relational_data'=>$sitecommission.'%');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$worker_details->name.' wallet',
					'TW'=>$profit_details->title,	
					'TP'=>'Commission_Payment',
					'PID'=>$pid,
					'CID'=>$contract_id,
					'CMID'=>$contract_milestone_id,
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				/** Site commission start end **/
				
				/** Refund client start **/
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$worker_wallet_id,'debit'=>$owner_amount,'description_tkey'=>'Project_Dispute','relational_data'=>'Refund');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$worker_details->name.' wallet',
				'TW'=>$owner_details->name.' wallet',	
				'TP'=>'Dispute_Refund',
				'PID'=>$pid,
				'CID'=>$contract_id,
				'CMID'=>$contract_milestone_id,
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$owner_wallet_id,'credit'=>$owner_amount,'description_tkey'=>'Project_Dispute','relational_data'=>'Refund');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$worker_details->name.' wallet',
					'TW'=>$owner_details->name.' wallet',		
					'TP'=>'Dispute_Refund',
					'PID'=>$pid,
					'CID'=>$contract_id,
					'CMID'=>$contract_milestone_id,
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				/** Refund client  end **/
				
				
				$new_balance_dispute=displayamount($dispute_wallet_balance,2)-displayamount($milestone_amount,2);
				updateTable('wallet',array('balance'=>$new_balance_dispute),array('wallet_id'=>$dispute_wallet_id));
				wallet_balance_check($dispute_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				
				$new_balance=displayamount($worker_wallet_balance,2)+displayamount($milestone_amount,2)-displayamount($sitecommission_fee_amount,2)-displayamount($owner_amount,2);
				updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$worker_wallet_id));
				wallet_balance_check($worker_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				$new_balance_owner=displayamount($owner_wallet_balance,2)+displayamount($owner_amount,2);
				updateTable('wallet',array('balance'=>$new_balance_owner),array('wallet_id'=>$owner_wallet_id));
				wallet_balance_check($owner_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				$new_balance_profit=displayamount($profit_wallet_balance,2)+displayamount($sitecommission_fee_amount,2);
				updateTable('wallet',array('balance'=>$new_balance_profit),array('wallet_id'=>$profit_wallet_id));
				wallet_balance_check($profit_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				
				
				updateMemberEarning($contractDetails->contractor_id);
				updateMemberSpent($contractDetails->owner_id);
				return $wallet_transaction_id;
			}
		}
	}
}
