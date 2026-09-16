<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help_cms extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		
		$this->load->model('help_cms_model', 'help_cms');
		$this->data['table'] = 'cms_help';
		$this->data['lang_table'] = 'cms_help_names';
		$this->data['primary_key'] = $this->data['table'].'_id';
		
		
		$model_configuration = array(
			'table' => $this->data['table'],
			'lang_table' => $this->data['lang_table'],
			'primary_key' => $this->data['primary_key'],
		);
		
		$this->help_cms->configure($model_configuration);
		
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
		
		$this->data['main_title'] = 'Help CMS Management';
		$this->data['second_title'] = 'All Help List';
		$this->data['title'] = 'Help CMS';
		
		if(get('parent') > 0){
			
			$parent_id = get('parent');
			$parent_row = get_row(array(
				'select' => 'title',
				'from' => 'cms_help_names',
				'where' => array('lang' => admin_default_lang(), 'cms_help_id' => $parent_id),
			));
			$this->data['parent_id'] = $parent_id;
			$this->data['second_title'] = '<b>'.$parent_row['title'].'</b>';
			$this->data['title'] = $parent_row['title'].' CMS';
			
			$breadcrumb = array(
				array(
					'name' => 'Help CMS',
					'path' => base_url($this->data['curr_controller'].'list_record'),
				),
				array(
					'name' => $parent_row['title'],
					'path' => '',
				),
			);
		
		}else{
			$this->data['parent_id'] = 0;
			
			$breadcrumb = array(
				array(
					'name' => 'Help CMS',
					'path' => '',
				),
			);
		
		}
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->help_cms->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->help_cms->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->data['add_btn'] = 'Add Help Content';
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Help';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
			
			if(get('parent_id') > 0){
				$this->data['parent_id'] = get('parent_id');
				$this->data['parent_detail'] = get_row(array(
					'select' => 'title',
					'from' => 'cms_help_names',
					'where' => array('lang' => admin_default_lang(), 'cms_help_id' => $this->data['parent_id']),
				));
				
				
			}else{
				$this->data['parent_id'] = 0;
				$this->data['parent_detail'] = array();
			}
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->help_cms->getDetail($id);
			$this->data['title'] = 'Edit Help';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[title]['.$v.']', "title $v", 'required|trim|max_length[100]');
				/* $this->form_validation->set_rules('lang[description]['.$v.']', "description $v", 'required|trim'); */
			}
			
			/* $this->form_validation->set_rules('cms_help_slug', 'slug', 'required|regex_match[/^[a-z\-A-Z0-9]+$/]|is_unique[cms_help.cms_help_slug]'); */
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->help_cms->addRecord($post);
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
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[title]['.$v.']', "title $v", 'required|trim|max_length[100]');
				/* $this->form_validation->set_rules('lang[description]['.$v.']', "description $v", 'required|trim'); */
			}
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->help_cms->updateRecord($post, $ID);
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
	
	public function change_status(){
		if(post() && $this->input->is_ajax_request()){
			
			$ID = post('ID');
			$sts = post('status');
			$action_type = post('action_type');
			
			if(is_array($ID)){
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('status' => $sts));
			}else{
				$upd['data'] = array('status' => $sts);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
			}
			
			if($action_type == 'multiple'){
				$this->api->cmd('reload');
			}else{
				
				$html = '';
				if($sts == ACTIVE_STATUS){
					$html = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$ID.', this)"><span class="badge badge-success">Active</span></a>';
				}else{
					$html = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$ID.', this)"><span class="badge badge-danger">Inactive</span></a>';
				}
			
			
				$this->api->data('html', $html);
				$this->api->cmd('replace');
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
			$this->help_cms->deleteRecord($id);
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





