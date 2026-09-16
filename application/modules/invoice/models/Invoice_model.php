<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	
	public function getInvoice($filter=array(),$limit = '', $start = '',$count=FALSE){ 
	
	 	$this->db->select('i.invoice_id,i.invoice_number,i.issuer_member_id,i.issuer_organization_id,i.recipient_member_id,i.recipient_organization_id,i.invoice_date,iref.issuer_information,iref.recipient_information,ir.invoice_row_amount,ir.invoice_row_unit_price,sum((ir.invoice_row_amount*ir.invoice_row_unit_price)) as total,it.name_tkey as type,,i.invoice_type_id,i.round_up_amount,i.invoice_status,i.change_reason');
				 
		 $this->db->from('invoice as i');
		if($filter){
		 	if(array_key_exists('invoice_for',$filter) && $filter['invoice_for']=='contract'){
		 		$this->db->join('project_contract_invoice as p_c_i','i.invoice_id=p_c_i.invoice_id','left');
			}
		}
		$this->db->join('invoice_reference as iref','i.invoice_id=iref.invoice_id','left');
		
		$this->db->join('invoice_row as ir','i.invoice_id=ir.invoice_id','left');
		$this->db->join('invoice_type as it','it.invoice_type_id=i.invoice_type_id','left');
		if($filter){
			if(array_key_exists('invoice_status',$filter)){
				$this->db->where('i.invoice_status',$filter['invoice_status']);
			}
			if(array_key_exists('invoice_for',$filter) && $filter['invoice_for']=='contract'){
				$this->db->where('p_c_i.contract_id',$filter['contract_id']);
			}
			if(array_key_exists('invoice_for',$filter) && $filter['invoice_for']=='all_invoice'){
				$wh="(i.issuer_member_id='".$filter['invoice_for_member']."' or i.recipient_member_id='".$filter['invoice_for_member']."')";
				$this->db->where($wh);
			}
		}
		$this->db->group_by('i.invoice_id');
		$this->db->order_by('i.invoice_id','desc');
		if($count==TRUE){
			$data=$this->db->get()->num_rows();
		}else{
			$this->db->limit($limit, $start);
			$data=$this->db->get()->result();
		}
		return $data;
	}
}
