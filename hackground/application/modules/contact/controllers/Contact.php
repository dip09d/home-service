<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('contact_model', 'contact');
		$this->data['table'] = 'contact';
		$this->data['primary_key'] = $this->data['table'].'_id';
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
		$this->data['main_title'] = 'Contact Management';
		$this->data['second_title'] = 'All User Contact List';
		$this->data['title'] = 'User Contact List';
		$breadcrumb = array(
			array(
				'name' => 'User Contact',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->contact->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->contact->getList($srch, $limit, $offset, FALSE);
		
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
		
		/* search parameter */
		$this->load->model('category/category_model');
		$this->data['category'] = $this->category_model->get_all_category();
		
		$this->layout->view('list', $this->data);
       
	}
	
	public function export_csv(){
		$this->load->helper('csv');
		$srch = get();
		$file_name = "Contact Request List - ".date('d M Y').".csv";
		if(!empty($srch['export']) && $srch['export'] == 1){
			$array = array();
			$array[] = array("ID", "Email", "Enquiry For", "Date", "Status");
			$list = $this->contact->getList($srch, 0, 5000);
			$this->load->model('category/category_model');
			if($list){
				foreach($list as $k => $v){
					$status = '';
					if($v['replied'] == '1'){
						$status = "Replied";
					}else if($v['replied'] == '0'){
						$status = "Not Replied";
					}
					$enquiry = "";
					if($v['inquiry'] == 'request-advertisement'){
						$enquiry = "Advertisement Request";
					}else{
						$enquiry = $this->category_model->get_category_name_by_key($v['inquiry']);
					}
				
					$array[] = array($v['contact_id'], $v['email'], $enquiry, $v['date'], $status);
				}
			}
			echo array_to_csv($array, $file_name);
		}
	}
	
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'reply'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'reply');
			$this->data['detail'] = $this->contact->getDetail($id);
			// echo '<pre>';
			// print_r($this->data['detail']);
			// echo '</pre>';
			// die;
			$this->data['title'] = 'Reply';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function reply(){
		$this->load->model('category/category_model');
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'email', 'required');
			$this->form_validation->set_rules('reply_message', 'reply_message', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				$insert = $this->contact->replyUser($post, $ID);
				$detail = $this->contact->getDetail($ID);
				$subject = 'Reply For '. $detail['inquiry'];
				
				$to = post('email');
				$content = post('reply_message');
				$data_parse=array(
					'MESSAGE'=>$content,
					'SUBJECT'=>$subject,
				);
				$template='contact-reply';
				SendMail($to,$template,$data_parse);
				$this->api->cmd('reload');
				
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function delete_record($id=''){
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		if($id){
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}else{
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
}





