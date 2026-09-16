<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('setting_model', 'setting');
		$this->data['table'] = 'settings';
		$this->data['primary_key'] = 'id';
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url('setting/general'));
	}
	
	public function general(){
		$this->data['main_title'] = 'Settings';
		$this->data['second_title'] = 'General Setting';
		$this->data['title'] = 'General Setting';
		$srch = get();
		
		$breadcrumb = array(
			array(
				'name' => 'Settings',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
		$this->data['list'] = $this->setting->getSettings('GENERAL', $srch);
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->data['tab'] = 'general';
		
		$this->layout->view('setting', $this->data);
	}
	
	public function custom(){
		$this->data['main_title'] = 'Settings';
		$this->data['second_title'] = 'Custom Setting';
		$this->data['title'] = 'Custom Setting';
		$srch = get();
		
		$breadcrumb = array(
			array(
				'name' => 'Settings',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
		$this->data['list'] = $this->setting->getSettings('CUSTOM', $srch);
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->data['tab'] = 'custom';
		
		$this->layout->view('setting', $this->data);
	}
	
	public function default_setting(){
		$this->data['main_title'] = 'Settings';
		$this->data['second_title'] = 'Default Setting';
		$this->data['title'] = 'Default Setting';
		$srch = get();
		$this->data['setting_group'] = '';
		$breadcrumb = array(
			array(
				'name' => 'Settings',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
		$this->data['list'] = $this->setting->getGroupSettings('', $srch);
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->data['tab'] = 'default_setting';
		$this->data['all_setting_group'] = get_results(array(
			'select' => '*',
			'from' => 'setting_group',
			'where' => array('status' => ACTIVE_STATUS),
		));
		
		$this->layout->view('setting', $this->data);
	}
	
	public function main($key=''){
		if(!$key){
			$key = 'general';
		}
		$this->data['current_setting_group'] = get_row(array(
			'select' => '*',
			'from' => 'setting_group',
			'where' => array('group_key' => $key),
		));
		$this->data['setting_group'] = $key;
		$this->data['main_title'] = 'Settings';
		$this->data['second_title'] = "{$this->data['current_setting_group']['group_name']} Setting";
		$this->data['title'] =  "{$this->data['current_setting_group']['group_name']} Setting";
		$srch = get();
		
		$breadcrumb = array(
			array(
				'name' => 'Settings',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
		$this->data['list'] = $this->setting->getGroupSettings($key, $srch);
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->data['tab'] = $key;
		$this->data['all_setting_group'] = get_results(array(
			'select' => '*',
			'from' => 'setting_group',
			'where' => array('status' => ACTIVE_STATUS),
		));
		$this->layout->view('setting', $this->data);
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
		}
		$this->layout->view('ajax_page', $this->data, TRUE);
	}
	
	public function add(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'title', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('setting_key', 'setting key', 'required|trim|regex_match[/^[a-zA-Z_]+$/]|is_unique[settings.setting_key]');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->setting->addRecord($post);
				if(post('add_more') && post('add_more') == '1'){
					$this->api->cmd('reset_form');
				}else{
					$this->api->cmd('reload');
				}
				
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function edit(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'title', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('setting_key', 'setting key', 'required|trim|regex_match[/^[a-zA-Z_]+$/]');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->setting->updateRecord($post, $ID);
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
			/* $this->setting->deleteRecord($id); */
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


