<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Finance_model extends CI_Model {

	private $lang;
	
    public function __construct() {
		$this->lang = get_active_lang();
        return parent::__construct();
    }
    public function getTransaction($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('w.*, wr.wallet_id,(sum(wr.credit) - sum(wr.debit)) as Amount , wt.title_tkey as name, wt.description_tkey as description')
		->from('wallet_transaction w')
		->join('wallet_transaction_type wt', 'wt.wallet_transaction_type_id = w.wallet_transaction_type_id', 'INNER')
		->join('wallet_transaction_row wr', 'wr.wallet_transaction_id = w.wallet_transaction_id', 'LEFT');
		
		if(!empty($srch['wallet_id'])){
			$this->db->where('wr.wallet_id', $srch['wallet_id']);
		}
		//$this->db->where_in('w.status',array('0','1'));
		
		
		/* if(isset($srch['pending'])){
			$this->db->where('w.pending', $srch['pending']);
			if($srch['pending'] == 0){
				$this->db->where('w.transaction_date <>', '0000-00-00 00:00:00');
			}
		} */
		if(!empty($srch['srachamount'])){
			$this->db->having("ABS(Amount) ={$srch['srachamount']}",false);	
		}
		
		
		if(!empty($srch['txn_from'])){
			$this->db->where('DATE(w.transaction_date) >=', $srch['txn_from']);
		}
	
		if(!empty($srch['txn_to'])){
			$this->db->where('DATE(w.transaction_date) <=', $srch['txn_to']);
		}

		
		if($for_list){
			
			$result = $this->db->limit($offset , $limit)->group_by('wr.wallet_transaction_id,wr.wallet_id')->order_by('w.wallet_transaction_id', 'DESC')->get()->result_array();
			
			
		}else{
			$result = $this->db->group_by('wr.wallet_transaction_id,wr.wallet_id')->get()->num_rows();
		}
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
	
	



	
}
