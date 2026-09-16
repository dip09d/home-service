<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membership_model extends MX_Controller {
    private $lang;
	function __construct()
	{
		$this->lang = get_active_lang();
		parent::__construct();
	}
    public function getMembership(){
		$this->db->select('b.membership_id,b.membership_bid,b.membership_portfolio,b.membership_skills,b.membership_commission_percent,b.price_per_month,b.price_per_year,l.name,l.description')
			->from('membership b')
			->join('membership_names l', "(l.membership_id=b.membership_id and l.lang='".$this->lang."')", 'LEFT');
		$this->db->where('b.membership_status', 1);
		$result = $this->db->order_by('b.display_order','asc')->get()->result();
		return $result;
		
	}
    public function getMembershipDetails($enc_membership_id){
		$this->db->select('b.membership_id,b.membership_bid,b.membership_portfolio,b.membership_skills,b.membership_commission_percent,b.price_per_month,b.price_per_year,l.name,l.description')
			->from('membership b')
			->join('membership_names l', "(l.membership_id=b.membership_id and l.lang='".$this->lang."')", 'LEFT');
		$this->db->where('b.membership_status', 1);
		$this->db->where('md5(b.membership_id)', $enc_membership_id);
		$result = $this->db->order_by('b.display_order','asc')->get()->row();
		return $result;
		
	}



}
?>