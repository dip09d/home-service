<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model{
	
	public function __construct(){
        return parent::__construct();
	}
	
	public function get_project_count(){
		return $this->db->count_all_results('project');
	}
	
	public function get_support_request_count(){
		return 0;
	}
	public function get_unread_notification_count(){
		return $this->db->where('read_status', 0)->count_all_results('admin_notifications');
	}
	public function get_withdrawn_count(){
		$wallet_transaction_type_id=get_setting('WITHDRAW');
		return $this->db->where('wallet_transaction_type_id', $wallet_transaction_type_id)->where('status', 0)->count_all_results('wallet_transaction');
	}
	
	public function get_contract_count(){
		return $this->db->where('contract_status', 1)->count_all_results('project_contract');
	}
	public function get_offer_count(){
		return $this->db->count_all_results('project_contract');
	}
	
	public function get_user_count($type=''){
		if($type=='freelancer'){
			return $this->db->count_all_results('member');
		}
		elseif($type=='employer'){
			return $this->db->count_all_results('worker');
		}
		else{
			$f=$this->db->count_all_results('member');
			$w=$this->db->count_all_results('worker');
			return $f+$w;
		}
	}
	public function get_contact_request_count(){
		return $this->db->count_all_results('contact');
	}
	public function get_dispute_count(){
		return $this->db->count_all_results('project_contract_dispute');
	}
	public function get_milestone_count(){
		return $this->db->count_all_results('project_contract_milestone');
	}
	public function get_bid_count(){
		return $this->db->count_all_results('project_bids');
	}
	public function get_review_count(){
		return $this->db->count_all_results('contract_reviews');
	}
	public function get_invoice_count(){
		return $this->db->count_all_results('invoice');
	}
	public function get_message_count(){
		return $this->db->count_all_results('conversations');
	}
	public function get_escrow_count(){
		return $this->db->count_all_results('project_payment_escrow');
	}

	public function project_statics(){
		$date = date('Y-m-d');
		$records = array();
		for($i=0; $i <= 12; $i++){
			$date_key = date('m', strtotime("-$i month"));
			$date_year = date('Y', strtotime("-$i month"));
			$res1 = $this->db->where("MONTH(project_posted_date) = $date_key and YEAR(project_posted_date) = $date_year")->count_all_results('project');
			$records[] = array(
				'item1' => $res1,
				'y' => date('Y-m', strtotime("-$i month")),
			);
		}
		
		return $records;
	}
	
	public function member_statics(){
		$date = date('Y-m-d');
		$records = array();
		for($i=0; $i <= 12; $i++){
			$date_key = date('m', strtotime("-$i month"));
			$date_year = date('Y', strtotime("-$i month"));
			$res1 = $this->db->where("MONTH(worker_register_date) = $date_key and YEAR(worker_register_date) = $date_year")->count_all_results('worker');
			$res2 = $this->db->where("MONTH(member_register_date) = $date_key and YEAR(member_register_date) = $date_year")->count_all_results('member');
			
			$records[] = array(
				'item1' => $res1,
				'item2' => $res2,
				'y' => date('Y-m', strtotime("-$i month")),
			);
		}
		
		return $records;
	}
	public function transaction_statics($type=''){
		 $date = date('Y-m-d');
		$records = array();
		for($i=0; $i <= 12; $i++){
			$date_key = date('m', strtotime("-$i month"));
			$date_year = date('Y', strtotime("-$i month"));
			if($type=='addfund'){
				$amount=0;
				$wallet_ids=array(get_setting('PAYPAL_WALLET'),get_setting('STRIPE_WALLET'),get_setting('BANK_WALLET'));
	
				$res = $this->db->select("sum(tr.debit) as debit")
				->from('wallet_transaction_row tr')
				->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
				->where_in('tr.wallet_id', $wallet_ids)
				->where('MONTH(t.transaction_date)', $date_key)
				->where('YEAR(t.transaction_date)', $date_year)
				->where('t.status', 1)
				->get()
				->row_array();
				if($res){
					$amount=$res['debit'];
				}

			}elseif($type=='profit'){
				$amount=0;
				$wallet_id=get_setting('SITE_PROFIT_WALLET');
				$res = $this->db->select("sum(tr.credit) as credit")
				->from('wallet_transaction_row tr')
				->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
				->where('tr.wallet_id', $wallet_id)
				->where('MONTH(t.transaction_date)', $date_key)
				->where('YEAR(t.transaction_date)', $date_year)
				->where('t.status', 1)
				->get()
				->row_array();
				if($res){
					$amount=$res['credit'];
				}
			}
			
			$records[] = array(
				'item1' => ($amount ? $amount:0),
				'y' => date('Y-m', strtotime("-$i month")),
			);
		}
		
		return $records; 
	}
	
	
	/* public function getWorkRecords(){
		$date = date('Y-m-d');
		$records = array();
		for($i=0; $i <= 30; $i++){
			$date_key = date('Y-m-d', strtotime("-$i days"));
			$res1 = $this->db->where("DATE(posted_datetime) = DATE('$date_key') ")->count_all_results('works');
			$res2 = $this->db->where("DATE(datetime) = DATE('$date_key') ")->count_all_results('works_bids');
			$records[] = array(
				'date' => $date_key,
				'total_work' => $res1,
				'total_bids' => $res2,
			);
		}
		
		return $records;
	} */
	

}


