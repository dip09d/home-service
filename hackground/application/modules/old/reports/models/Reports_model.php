<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'reports';
		$this->primary_key = 'id';
        return parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('r.*,m.member_name')
			->from($this->table . ' r')
			->join('member m', 'm.member_id=r.reporter_id', 'LEFT');
		if(!empty($srch['content_type'])){
			if($srch['content_type']=='order'){
				$this->db->select('o.order_number');
				$this->db->join('orders as o','r.content_id=o.order_id','left');
				$this->db->where('r.content_type', 'order');	
			}elseif($srch['content_type']=='message'){
				$this->db->where('r.content_type', 'message');
				
			}elseif($srch['content_type']=='proposal'){
				$this->db->select('p.proposal_title,p.proposal_url');
				$this->db->join('proposals as p','r.content_id=p.proposal_id','left');
				$this->db->where('r.content_type', 'proposal');
			}elseif($srch['content_type']=='project'){
				$this->db->select('p.project_title,p.project_url');
				$this->db->join('project as p','r.content_id=p.project_id','left');
				$this->db->where('r.content_type', 'project');
			}
		}
		
		
		if(!empty($srch['term'])){
			$this->db->like('p.project_title', $srch['term']);
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by($this->primary_key, 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
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
	
	
	public function get_active_proposal(){
		$this->db->select('p.*')
			->from($this->table . ' p');
			
		
		$this->db->where('p.proposal_status', PROPOSAL_ACTIVE);	
		
		$result = $this->db->order_by($this->primary_key, 'DESC')->get()->result_array();
		
		return $result;
	}
	
	public function getReferralList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$this->db->select('p_r.*,s.member_name as seller,r.member_name as referrer,b.member_name as buyer,p.proposal_title as proposal')
			->from('proposals_referrals p_r')
			->join('member s', 's.member_id=p_r.seller_id', 'LEFT')
			->join('member r', 'r.member_id=p_r.referrer_id', 'LEFT')
			->join('member b', 'b.member_id=p_r.buyer_id', 'LEFT')
			->join('proposals p', 'p.proposal_id=p_r.proposal_id', 'LEFT');
		
		if(!empty($srch['show']) && $srch['show'] == 'trash'){
			$this->db->where('p_r.status', PROPOSAL_DELETED);	
		}else{
			$this->db->where('p_r.status <>', PROPOSAL_DELETED);	
		}
		
		/* if(!empty($srch['term'])){
			$this->db->like('p.proposal_title', $srch['term']);
		}
		 */
		 
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('p_r.referral_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	
	}
	
	
}


