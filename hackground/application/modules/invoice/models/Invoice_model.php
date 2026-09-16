<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'invoice';
		$this->primary_key = 'invoice_id';
        return parent::__construct();
	}
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('i.invoice_id,i.invoice_number,i.issuer_member_id,i.issuer_organization_id,i.recipient_member_id,i.recipient_organization_id,i.invoice_date,iref.issuer_information,iref.recipient_information,ir.invoice_row_amount,ir.invoice_row_unit_price,sum((ir.invoice_row_amount*ir.invoice_row_unit_price)) as total,it.name_tkey as type,it.description_tkey,i.invoice_type_id,i.round_up_amount,i.invoice_status,i.change_reason,i.attachment')
				->from('invoice i')
				->join('invoice_type it', 'i.invoice_type_id=it.invoice_type_id', 'LEFT')
				->join('project_contract_invoice as p_c_i', 'i.invoice_id=p_c_i.invoice_id', 'LEFT');
		$this->db->join('invoice_reference as iref','i.invoice_id=iref.invoice_id','left');
		$this->db->join('invoice_row as ir','i.invoice_id=ir.invoice_id','left');
		if(!empty($srch['project_id'])){
			$this->db->where('p_c_i.project_id', $srch['project_id']);
		}
		
		if(!empty($srch['invoice_type'])){
			$this->db->where('i.invoice_type', $srch['invoice_type']);
		}
		
		if(!empty($srch['invoice_number'])){
			$this->db->where('i.invoice_number', $srch['invoice_number']);
		}
		if($srch){
			if(array_key_exists('invoice_status',$srch)){
				$this->db->where('i.invoice_status',$srch['invoice_status']);
			}
			if(array_key_exists('invoice_for',$srch) && $srch['invoice_for']=='contract'){
				$this->db->where('p_c_i.contract_id',$srch['contract_id']);
			}
			if(array_key_exists('invoice_for',$srch) && $srch['invoice_for']=='all_invoice'){
				$wh="(i.issuer_member_id='".$srch['invoice_for_member']."' or i.recipient_member_id='".$srch['invoice_for_member']."')";
				$this->db->where($wh);
			}
			if(array_key_exists('invoice_for',$srch) && $srch['invoice_for']=='worker'){
				$wh="(i.issuer_member_id='".$srch['worker_id']."' or i.recipient_member_id='".$srch['worker_id']."')";
				$this->db->where($wh);
			}
		}
		$this->db->group_by('i.invoice_id');
		$this->db->order_by('i.invoice_id','desc');

		
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by($this->primary_key, 'DESC')->get()->result_array();
			//echo $this->db->last_query();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	public function getinvoiceDetail($invoice_id){
		$this->db->select('o.invoice_id,o.invoice_number,o.invoice_duration,o.invoice_date,o.invoice_time,o.invoice_description,o.seller_id,o.buyer_id,o.proposal_id,o.invoice_price,o.invoice_qty,o.invoice_fee,o.invoice_active,o.complete_time,o.invoice_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url');
		$this->db->from('invoice as o');
		$this->db->join('proposals as p','o.proposal_id=o.proposal_id','left');
		$this->db->where('o.invoice_id',$invoice_id);
		return $this->db->get()->row();
	}
	public function getUserName($member_id){
		$this->db->select('a.access_username');
		$this->db->from('profile_connection as p_c');
		$this->db->join('access_panel as a','p_c.access_user_id=a.access_user_id','left');
		$this->db->where(array('p_c.member_id'=>$member_id,'p_c.organization_id'=>NULL));
		$data=$this->db->get()->row();
		if($data){
			return $data->access_username;
		}else{
			return $member_id;
		}
	}
	/* public function addRecord($data=array()){
		$structure = array(
			'name' => !empty($data['name']) ? $data['name'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$insert_id = insert($ins, TRUE);
		return $insert_id;
	}

	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'name' => !empty($data['name']) ? $data['name'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '0',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
		return  update($ins);
	} */
	
	public function deleteRecord($id=''){
		if($id && is_array($id)){
			return $this->db->where_in($this->primary_key, $id)->update($this->table, array('invoice_status' => DELETE_STATUS));
		}else{
			$ins['data'] = array('invoice_status' => DELETE_STATUS);
			$ins['table'] = $this->table;
			$ins['where'] = array($this->primary_key => $id);
			return  update($ins);
		}
		
	}
	
	public function getDetail($id=''){
		$result = $this->db->select('i.*,sum((ir.invoice_row_amount*ir.invoice_row_unit_price)) as total')
		->from($this->table .' as i')
		->join('invoice_row as ir','i.invoice_id=ir.invoice_id','left')->where('i.invoice_id', $id)->group_by('i.invoice_id')->get()->row_array();
		return $result;
	}
	public function updateRecord($data=array(), $id=''){
		$structure = array(
			'change_reason' => !empty($data['note']) ? $data['note'] : '',
			'attachment' => !empty($data['attachment']) ? $data['attachment'] : NULL,
			'invoice_status' => 1,
			'payment_type' => !empty($data['payment_type']) ? $data['payment_type'] : '',
		);
		$ins['data'] = $structure;
		$ins['table'] = $this->table;
		$ins['where'] = array($this->primary_key => $id);
		return  update($ins);
	}
	
}


