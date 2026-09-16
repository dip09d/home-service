<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'orders';
		$this->primary_key = 'order_id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('*')
			->from($this->table);
		
		/* if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('status', DELETE_STATUS);	
		}else{
			$this->db->where('status <>', DELETE_STATUS);	
		} */
		
		if(!empty($srch['term'])){
			$this->db->like('order_number', $srch['term']);
		}
		
		if(!empty($srch['status'])){
			$this->db->like('order_status', $srch['status']);
		}
		
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by($this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}
	public function getOrderDetail($order_id){
		$this->db->select('o.order_id,o.order_number,o.order_duration,o.order_date,o.order_time,o.order_description,o.seller_id,o.buyer_id,o.proposal_id,o.order_price,o.order_qty,o.order_fee,o.order_active,o.complete_time,o.order_status,o.payment_method,o.transaction_id,p.proposal_image,p.proposal_title,p.proposal_url');
		$this->db->from('orders as o');
		$this->db->join('proposals as p','o.proposal_id=o.proposal_id','left');
		$this->db->where('o.order_id',$order_id);
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
	
}


