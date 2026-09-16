<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
    public function getBidInvitation($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
        $this->db->select('p.project_id,p.project_url,p.project_title,c.invite_date,b.bid_id,,p.project_posted_date,p.project_expired_date,p.project_status,p_s.is_hourly,p_s.is_fixed,p_s.budget,p_s.is_visible_anyone')
        ->from('project_bid_invitation c')
        ->join('project p', 'c.project_id=p.project_id', 'INNER')
        ->join('project_owner o', 'p.project_id=o.project_id', 'left')
        ->join('project_settings as p_s', 'p.project_id=p_s.project_id', 'left')
        ->join('project_bids b', '(c.project_id=b.project_id and c.user_id=b.member_id)', 'left');
        if($srch){
            if(array_key_exists('contractor_id', $srch)){
                $this->db->where('c.user_id', $srch['contractor_id']);
            }
        }
        $this->db->group_by('c.project_id');
        $this->db->order_by('c.invitation_id','desc');
        
        if($for_list){
            $result = $this->db->limit($offset, $limit)->get()->result_array();
        }else{
            $result = $this->db->get()->num_rows();
        }
        return $result;
    }
    public function getEarningGraph($wallet_id){
        $result=array();
        $result['label']= $result['data']=array();
        for ($i = 0; $i < 12; $i++) {
            $month=date('m', strtotime(-$i . 'month'));
            $year=date('Y', strtotime(-$i . 'month'));
            $result['label'][]=date('M (y)', strtotime(-$i . 'month'));
            $total=0;
            $res = $this->db->select("sum(tr.credit) as credit")
            ->from('wallet_transaction_row tr')
            ->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
            ->where('tr.wallet_id', $wallet_id)
            ->where('YEAR(t.transaction_date)', $year)
            ->where('MONTH(t.transaction_date)', $month)
            ->where('t.status', 1)
            ->get()
            ->row_array();
            if($res && $res['credit']>0){
                $total=$res['credit'];
            }
            $result['data'][]=$total;
        }
        $result['label']=array_reverse($result['label']);
        $result['data']=array_reverse($result['data']);

        return $result;
    }
    public function getSpentGraph($wallet_id){
        $result=array();
        $result['label']= $result['data']=array();
        for ($i = 0; $i < 12; $i++) {
            $month=date('m', strtotime(-$i . 'month'));
            $year=date('Y', strtotime(-$i . 'month'));
            $result['label'][]=date('M (y)', strtotime(-$i . 'month'));
            $total=0;
            $res = $this->db->select("sum(tr.debit) as debit")
            ->from('wallet_transaction_row tr')
            ->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
            ->where('tr.wallet_id', $wallet_id)
            ->where('YEAR(t.transaction_date)', $year)
            ->where('MONTH(t.transaction_date)', $month)
            ->where('t.status', 1)
            ->get()
            ->row_array();
            if($res && $res['debit']>0){
                $total=$res['debit'];
            }
            $result['data'][]=$total;
        }
        $result['label']=array_reverse($result['label']);
        $result['data']=array_reverse($result['data']);

        return $result;
    }
    public function getprojectGraphFreelancer($member_id){
        $this->load->model('contract/contract_model','contract_model');
        $result=$srch=array();
        $result['label']=array('Pending Offer','Processing Contract','Completed Contract');
        $result['color']=array('rgb(255, 205, 86)','#007bff','#28a745');
        $srch['contractor_id'] = $member_id;
        $srch['contract_status'] = 0;
        $pending_offer=$this->contract_model->getContracts($srch, '', '',false);
        $srch['contract_status'] =1;
        $srch['show'] = 'pending';
        $processing_contract=$this->contract_model->getContracts($srch, '', '',false);
        $srch['show'] = 'completed';
        $completed_contract=$this->contract_model->getContracts($srch, '', '',false);

        $result['data']=array($pending_offer,$processing_contract,$completed_contract);
        return $result;
    }
    public function getprojectGraphEmployer($member_id){
        $this->load->model('contract/contract_model','contract_model');
        $result=$srch=array();
        $result['label']=array('Pending Offer','Processing Contract','Completed Contract');
        $result['color']=array('rgb(255, 205, 86)','#007bff','#28a745');
        $srch['owner_id'] = $member_id;
        $srch['contract_status'] = 0;
        $pending_offer=$this->contract_model->getContracts($srch, '', '',false);
        $srch['contract_status'] =1;
        $srch['show'] = 'pending';
        $processing_contract=$this->contract_model->getContracts($srch, '', '',false);
        $srch['show'] = 'completed';
        $completed_contract=$this->contract_model->getContracts($srch, '', '',false);

        $result['data']=array($pending_offer,$processing_contract,$completed_contract);
        return $result;
    }

    public function get_user_verfication_doc_by_doc_type($doc_type_id) {
        $ci = &get_instance();
        $member_id = isset($ci->member_id) ? $ci->member_id : 0;
        
        $row = $this->db->where([
            'doc_type' => $doc_type_id,
            'member_id' => $member_id
        ])
        ->get('profile_verification_user_documents')
        ->row();

        if ($row) {
            $row->documents = json_decode($row->documents);
        }
        return $row;
    }

}
?>