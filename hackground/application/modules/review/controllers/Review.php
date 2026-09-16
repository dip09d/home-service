<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('review_model', 'review');
		
		parent::__construct();
		
		admin_log_check();
	}
	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	public function list_record(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Review Management';
		$this->data['second_title'] = 'All Review List';
		$this->data['title'] = 'Review';
		$breadcrumb = array(
			array(
				'name' => 'Review',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->review->getReview($srch, $limit, $offset);
		$this->data['list_total'] = $this->review->getReview($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		/* get_print($this->data['list']); */
		$this->layout->view('list', $this->data);
		
	}
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		
		$this->data['all_setting_group'] = get_results(array(
			'select' => '*',
			'from' => 'setting_group',
			'where' => array('status' => ACTIVE_STATUS),
		));
		
		if($page == 'add'){
			$this->data['title'] = 'Add Setting';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
			$this->data['setting_group'] = get('setting_group');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->setting->getDetail($id);
			$this->data['title'] = 'Edit Setting';
		}else if($page == 'view'){
			$id = get('id');
			$this->data['ID']= $id;
			$contract_id=getFieldData('contract_id','contract_reviews','','',array('review_id'=>$id));
			if($contract_id){
				$contract_id_enc=md5($contract_id);
				$review=getData(array(
					'select'=>'r.*,m.member_name',
					'table'=>'contract_reviews r',
					'join'=>array(
						array('table'=>'member as m', 'on'=>'r.review_by=m.member_id', 'position'=>'left'),
					),
					'where'=>array('r.contract_id'=>$contract_id),
					));
				$reviews_list=array();
				if($review){
					foreach($review as $row){
						$reviews_list['review_'.$row->review_id]=$row;
					}
				}
				$this->data['reviews']=$reviews_list;
				$this->data['contractDetails'] =getData(array(
					'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,co.contract_details,c.bid_id,co.contract_attachment,co.max_hour_limit,co.allow_manual_hour,c.is_contract_ended,c.contract_end_date,c.is_pause',
					'table'=>'project_contract c',
					'join'=>array(
						array('table'=>'project_contract_offer co', 'on'=>'c.contract_id=co.contract_id', 'position'=>'left'),
						array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
						array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
					),
					'where'=>array('md5(c.contract_id)'=>$contract_id_enc),
					'single_row'=>TRUE
					));
	
			}
			//$this->data['detail'] = $this->setting->getDetail($id);
			$this->data['title'] = 'View review';
		}
		$this->layout->view('ajax_page', $this->data, TRUE);
	}
	
	
	
	
	
}





