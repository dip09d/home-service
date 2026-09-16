<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MX_Controller {
	
	private $data;
	function __construct(){
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
			$this->organization_id=$this->loggedUser['OID'];
		}else{
			redirect(URL::get_link('loginURL').'?ref=dashboardURL');
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		parent::__construct();
	}
	
	public function index(){
		$this->load->library('pagination');
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
		));
		
		if($this->access_member_type=='F'){
			$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
		}else{
			
			$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
		}
		$srch = $this->input->get();
			$limit = !empty($srch['per_page']) ? $srch['per_page'] : 0;
			$offset = 10;
			if($this->access_member_type=='F'){
				$srch['contractor_id'] = $this->member_id;
			}else{
				$srch['owner_id'] = $this->member_id;
			}
			$srch['contract_status'] = 1;
			$show='all';
			if($this->input->get('show')){
				$show=$srch['show'];
			}
			$this->data['show']=$show;

			$this->data['notification_list'] = $this->notification_model->getNotificationList($this->member_id, $limit, $offset);
			$this->data['list_total'] = $this->notification_model->getNotificationList($this->member_id, $limit, $offset, FALSE);
			
			/*Pagination Start*/
			$config['base_url'] = get_link('NotificationURL');
			$config['page_query_string'] = TRUE;
			$config['reuse_query_string'] = TRUE;
			$config['total_rows'] = $this->data['list_total'];
			$config['per_page'] = $offset;
			
			$config['full_tag_open'] = '<div class="pagination-container mt-4"><nav class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></nav></div>';
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<li class="waves-effect">';
			$config['first_tag_close'] = '</li>';
			$config['num_tag_open'] = '<li class="waves-effect">';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li><a class='current-page' href='javascript:void(0)'>";
			$config['cur_tag_close'] = '</a></li>';
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = "<li class='last waves-effect'>";
			$config['last_tag_close'] = '</li>';
			$config['next_link'] = '<i class="icon-material-outline-keyboard-arrow-right"></i>';
			$config['next_tag_open'] = '<li class="waves-effect">';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = '<i class="icon-material-outline-keyboard-arrow-left"></i>';
			$config['prev_tag_open'] = '<li class="waves-effect">';
			$config['prev_tag_close'] = '</li>';  
			
			$this->pagination->initialize($config);
			$this->data['links'] = $this->pagination->create_links();
		
		$this->layout->view('notification', $this->data);
	}
	
	public function notification_list_htm(){
		$data=array();
		$get = get();

		$limit = !empty($get['per_page']) ? $get['per_page'] : 0;
		$offset = 10;
		$next_limit = $limit + $offset;
		
		$data['notification_list'] = $this->notification_model->getNotificationList($this->member_id,$limit, $offset);
		$data['notification_list_count'] = $this->notification_model->getNotificationList($this->member_id,'','', FALSE);
		$json['notification_list'] = $data['notification_list'];
		$json['notification_list_count'] = $data['notification_list_count'];
		
		if($data['notification_list_count'] > $next_limit){
			$json['next'] = base_url('notification/notification_list_htm?per_page='.$next_limit);
		}else{
			$json['next'] = null;
		}
		$json['html'] = $this->layout->view('notification_list_htm',$data, TRUE, TRUE);
		
		$json['status'] = 1;
		$this->_reset_seen_notification($this->member_id);
		echo json_encode($json);
	}
	
	private function _reset_seen_notification(){
		$member_id = $this->member_id;
		$this->notification_model->notify_unset($member_id);
	}
	
	public function seen(){
		$ID = get('id');
		$link = get('link');
		$this->db->where('notification_id', $ID)->update('member_notifications', array('read_status' => 1));
		if($link){
			$next = base_url(urldecode($link));
		}else{
			$next = base_url();
		}
		redirect($next);
	}
	
}
