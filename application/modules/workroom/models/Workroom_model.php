<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workroom_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	
	public function getContracts($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date')
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
		if($amountbal){
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
		if($amountbal){
			$amount=$amountbal['balance'];
		}
		return $amount;
	}
	public function getTotalBillAmount($project_id='',$contract_id='',$invoiced=TRUE){
		$amount=0;
		$this->db->select('sum((l.total_time_worked/60)*c.contract_amount) as balance')->from('project_contract as c');
		$this->db->join('project_contract_hour_log as l','c.contract_id=l.contract_id','left');
		$this->db->where('c.project_id',$project_id);
		$this->db->where('c.contract_id',$contract_id);
		$this->db->where('l.log_status',1);
		if($invoiced){
			$this->db->where('l.invoice_id <>',NULL);
		}else{
			$this->db->where('l.invoice_id',NULL);
		}
		$amountbal=$this->db->group_by('c.contract_id')->get()->row_array();
		if($amountbal){
			$amount=$amountbal['balance'];
		}
		return $amount;
	}
	public function getTotalHour($project_id='',$contract_id='',$type=''){
		$amount=0;
		$this->db->select('sum(total_time_worked) as totalmin');
		if($contract_id){
			$this->db->where('contract_id',$contract_id);
		}
		if($type=='all'){
			$this->db->where_in('log_status',array(0,1));
		}elseif($type=='pending'){
			$this->db->where('log_status',0);
		}elseif($type=='approved'){
			$this->db->where('log_status',1);
		}
		$amountbal=$this->db->get('project_contract_hour_log')->row_array();
		if($amountbal){
			$amount=$amountbal['totalmin'];
		}
		return $amount;
	}
	
}
