<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispute_model extends CI_Model{
	
	
	public function __construct(){
        return parent::__construct();
	}
	public function getDispute($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('d.project_contract_dispute_id,d.contract_id,d.contract_milestone_id,p_c.contract_title,p.project_id,p.project_title,p.project_url,d.disputed_amount,d.dispute_date,d.dispute_status,c_m.milestone_title')
			->from('project_contract_dispute d')
			->join('project_contract as p_c','d.contract_id=p_c.contract_id','left')
			->join('project as p','p_c.project_id=p.project_id','left')
			->join('project_contract_milestone as c_m','d.contract_milestone_id=c_m.contract_milestone_id','left')
			;
			
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('d.project_contract_dispute_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
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


