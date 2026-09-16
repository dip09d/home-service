<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'wallet';
		$this->primary_key = $this->table.'_id';
        return parent::__construct();
	}
	public function addRecordWallet(){
		$id=post('ID');
		$amount=post('amount');
		$reason=post('reason');
		$user_id=getField('user_id', 'wallet', 'wallet_id', $id);
		if($user_id>0){
			
			$bank_details=getWallet(get_setting('BANK_WALLET'));
			$bank_wallet_id=$bank_details->wallet_id;
			$bank_wallet_balance=$bank_details->balance;
					
			$member_details=getWalletMember($user_id);
			$member_wallet_id=$member_details->wallet_id;
			$member_wallet_balance=$member_details->balance;
			$wallet_transaction_type_id=get_setting('ADD_FUND_BY_ADMIN');
			$current_datetime=date('Y-m-d H:i:s');
			$admin_message='';
			if($reason){
				$admin_message=$reason;
			}
			$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime,'admin_message'=>$admin_message),TRUE);
			if($wallet_transaction_id){
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$bank_wallet_id,'debit'=>$amount,'description_tkey'=>'Payment_from','relational_data'=>'Bank');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$bank_details->title,
				'TW'=>$member_details->name.' wallet',	
				'TP'=>'Payment_Payment',
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$member_wallet_id,'credit'=>$amount,'description_tkey'=>'Payment_from','relational_data'=>'Bank');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$bank_details->title,
					'TW'=>$member_details->name.' wallet',	
					'TP'=>'Wallet_Topup',
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						
				$new_balance=displayamount($member_wallet_balance,2)+displayamount($amount,2);
				updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$member_wallet_id));
				wallet_balance_check($member_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				$new_balance_bank=displayamount($bank_wallet_balance,2)-displayamount($amount,2);
				updateTable('wallet',array('balance'=>$new_balance_bank),array('wallet_id'=>$bank_wallet_id));
				wallet_balance_check($bank_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				
				return $wallet_transaction_id;
			}
		}
	}
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('*')
			->from($this->table);
		
		if($srch){
			if(array_key_exists('member_id',$srch)){
				$this->db->where('user_id', $srch['member_id']);
			}
		}
		if($srch){
			if(array_key_exists('worker_id',$srch)){
				$this->db->where('worker_id', $srch['worker_id']);
			}
		}
		
		if(!empty($srch['term'])){
			$this->db->like('title', $srch['term']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by($this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	
	public function getTxnDetail($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		if(!empty($srch['is_list_transaction_details'])){
			$this->db->select('t.*,t_type.title_tkey,t_type.description_tkey as type_description_tkey,sum(r.credit) as credit,sum(r.debit) as debit,w.wallet_id,w.user_id,w.title as wallet_title');
		}else{
			$this->db->select('t.*,t_type.title_tkey,t_type.description_tkey as type_description_tkey,r.*,w.wallet_id,w.user_id,w.title as wallet_title');
		}
		$this->db->from('wallet_transaction t')
			->join('wallet_transaction_type t_type', 't_type.wallet_transaction_type_id=t.wallet_transaction_type_id', 'LEFT')
			->join('wallet_transaction_row r', 'r.wallet_transaction_id=t.wallet_transaction_id')
			->join('wallet w', 'r.wallet_id=w.wallet_id');
		
		if(!empty($srch['term'])){
			$this->db->like('t_type.description_tkey', $srch['term']);
		}
		
		if(!empty($srch['wallet_id'])){
			$this->db->where('r.wallet_id', $srch['wallet_id']);
		}
		if(!empty($srch['fromdate']) && !empty($srch['enddate'])){
			$this->db->where('t.transaction_date >=', $srch['fromdate']);
			$this->db->where('t.transaction_date <=', $srch['enddate']);
		}
		if(!empty($srch['txn_id'])){
			$this->db->where('r.wallet_transaction_id', $srch['txn_id']);
		}
		if(!empty($srch['is_list_transaction_details'])){
		$this->db->where('t_type.description_tkey <>', 'Online_payment_from');
		$this->db->group_by('t.wallet_transaction_id');
		}
		if($for_list){
			$this->db->limit($offset, $limit);
			if(!empty($srch['order_asc']) && $srch['order_asc']==1){
				$this->db->order_by('t.wallet_transaction_id', 'ASC')->order_by('r.wallet_transaction_row_id', 'ASC');
			}else{
				$this->db->order_by('t.wallet_transaction_id', 'DESC')->order_by('r.wallet_transaction_row_id', 'ASC');
			}
			$result = $this->db->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}
	
	public function getTxnList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('t.*,t_type.title_tkey,t_type.description_tkey as type_description_tkey')
			->from('wallet_transaction t')
			->join('wallet_transaction_type t_type', 't_type.wallet_transaction_type_id=t.wallet_transaction_type_id', 'LEFT');
			
		
		if(!empty($srch['term'])){
			$this->db->like('t_type.description_tkey', $srch['term']);
		}
		
		if(isset($srch['status']) && $srch['status'] != ''){
			$this->db->where('t.status', $srch['status']);
		}
		
		$this->db->group_by('t.wallet_transaction_id');
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('t.wallet_transaction_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	public function getWithdrawnList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$wallet_transaction_type_id=get_setting('WITHDRAW');
		$this->db->select('t.*,r.debit as amount,r.description_tkey,r.relational_data,m.member_name,w.balance')
			->from('wallet_transaction t')
			->join('wallet_transaction_row r', 'r.wallet_transaction_id=t.wallet_transaction_id','left')
			->join('wallet as w', 'r.wallet_id=w.wallet_id', 'LEFT')
			->join('member  as m', 'w.user_id=m.member_id', 'LEFT');
		
		
		$this->db->where('t.wallet_transaction_type_id',$wallet_transaction_type_id);
		$this->db->group_by('t.wallet_transaction_id');
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('t.wallet_transaction_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'title' => !empty($data['name']) ? $data['name'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
		return  update($ins);
	}
	
	public function deleteRecord($id=''){
		if($id && is_array($id)){
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('status' => DELETE_STATUS));
		}else{
			$ins['data'] = array('status' => DELETE_STATUS);
			$ins['table'] = $this->table;
			$ins['where'] = array($this->primary_key => $id);
			return  update($ins);
		}
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
		return $result;
	}
	
	public function wallet_debit_balance($wallet_id=''){
		$wallet_transaction_type_id=get_setting('WITHDRAW');
		$res = $this->db->select("sum(tr.debit) as debit")
				->from('wallet_transaction_row tr')
				->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
				->where('tr.wallet_id', $wallet_id)
				//->where('t.status', 1)
				->where("IF(t.wallet_transaction_type_id='".$wallet_transaction_type_id."' , t.status!='2',t.status='1')")
				->get()
				->row_array();
		
		return $res['debit'];
	}
	
	public function wallet_credit_balance($wallet_id=''){
		$res = $this->db->select("sum(tr.credit) as credit")
				->from('wallet_transaction_row tr')
				->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
				->where('tr.wallet_id', $wallet_id)
				->where('t.status', 1)
				->get()
				->row_array();
				
			
		
		return $res['credit'];
	}
	
	public function getOnlineTxnDataList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('*')
			->from('online_transaction_data t_d');
			
		if(!empty($srch['ref_key'])){
			$this->db->where('t_d.content_key', $srch['ref_key']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('t_d.	online_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	public function getTxnRow($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select("r.*,t_type.description_tkey,t.status,t.transaction_date");
		$this->db->from('wallet_transaction_row r')
			->join('wallet_transaction t', 't.wallet_transaction_id=r.wallet_transaction_id', 'LEFT')
			->join('wallet_transaction_type t_type', 't_type.wallet_transaction_type_id=t.wallet_transaction_type_id', 'LEFT');
			
		
		
		if(!empty($srch['fromdate']) && !empty($srch['enddate'])){
			$this->db->where('t.transaction_date >=', $srch['fromdate']);
			$this->db->where('t.transaction_date <=', $srch['enddate']);
		}
		if(!empty($srch['txn_id'])){
			$this->db->where('r.wallet_transaction_id', $srch['txn_id']);
		}
		
		if($for_list){
			$this->db->limit($offset, $limit);
			$this->db->order_by('r.wallet_transaction_row_id', 'DESC');
			$result = $this->db->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}
	public function addRecordWalletWorker(){
		$id=post('ID');
		$amount=post('amount');
		$reason=post('reason');
		$worker_id=getField('worker_id', 'wallet', 'wallet_id', $id);
		if($worker_id>0){
			$bank_details=getWallet(get_setting('BANK_WALLET'));
			$bank_wallet_id=$bank_details->wallet_id;
			$bank_wallet_balance=$bank_details->balance;
			$worker_details=getWalletWorker($worker_id);
			$worker_wallet_id=$worker_details->wallet_id;
			$worker_wallet_balance=$worker_details->balance;
			$wallet_transaction_type_id=get_setting('ADD_FUND_BY_ADMIN');
			$current_datetime=date('Y-m-d H:i:s');
			$admin_message='';
			if($reason){
				$admin_message=$reason;
			}
			$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime,'admin_message'=>$admin_message),TRUE);
			if($wallet_transaction_id){
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$bank_wallet_id,'debit'=>$amount,'description_tkey'=>'Payment_from','relational_data'=>'Bank');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$bank_details->title,
				'TW'=>$worker_details->name.' wallet',	
				'TP'=>'Payment_Payment',
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$worker_wallet_id,'credit'=>$amount,'description_tkey'=>'Payment_from','relational_data'=>'Bank');
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$bank_details->title,
					'TW'=>$worker_details->name.' wallet',	
					'TP'=>'Wallet_Topup',
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
						
						
				$new_balance=displayamount($worker_wallet_balance,2)+displayamount($amount,2);
				updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$worker_wallet_id));
				wallet_balance_check($worker_wallet_id,array('transaction_id'=>$wallet_transaction_id));
				$new_balance_bank=displayamount($bank_wallet_balance,2)-displayamount($amount,2);
				updateTable('wallet',array('balance'=>$new_balance_bank),array('wallet_id'=>$bank_wallet_id));
				wallet_balance_check($bank_wallet_id,array('transaction_id'=>$wallet_transaction_id));

				
				$unique_id=$worker_id.'-'.time();	
				$data['formdata']=array(
					'amount'=>$amount,
					'org_amt'=>$amount,
					'fee'=>0,
					//'return_url'=>base_url('app/user/payment_success/'.$this->worker_id),
					//'cancel_url'=>base_url('app/user/payment_failed/'.$this->worker_id),
					//'notify_url'=>get_link('PaypalNotify').'addfund/'.$unique_id,
					'custom'=>md5('PPAY-'.$unique_id),
					'worker_id'=>$worker_id,
				);
				$transansaction_data=array('payment_type'=>'ADD_FUND_BY_ADMIN','content_key'=> $data['formdata']['custom']);

				$data['formdata']['amount_converted']=$amount;
				$transansaction_data['request_value']=json_encode( $data['formdata']);
				insert_record('online_transaction_data',$transansaction_data);
				// new balance by pusher
				$this->load->library('pusher');
				$pusher=$this->pusher->load();
				$pusherData=array(
					'new_balance'=>getField('balance','wallet','wallet_id',$id),
				);
				$channel_id = 'new_balance_'.$worker_id;
				$res=$pusher->trigger($channel_id, 'booking-accepted',$pusherData);
				
				return $wallet_transaction_id;
			}
		}
	}
	
}


