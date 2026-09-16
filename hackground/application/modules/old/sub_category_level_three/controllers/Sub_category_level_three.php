<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_category_level_three extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		
		$this->load->model('sub_category_level_three_model', 'sub_category_level_three');
		$this->data['table'] = 'category_subchild_level_3';
		$this->data['lang_table'] = 'category_subchild_level_3_names';
		$this->data['primary_key'] = $this->data['table'].'_id';
		
		
		$model_configuration = array(
			'table' => $this->data['table'],
			'lang_table' => $this->data['lang_table'],
			'primary_key' => $this->data['primary_key'],
		);
		
		$this->sub_category_level_three->configure($model_configuration);
		
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	
	public function list_record($category_id=''){
		if(!$category_id){
			return false;
		}
		$this->load->model('sub_category/sub_category_model');
		$this->load->model('category/category_model');
		$category_subchild_id = $category_id;
		$parent_category_id = getField('category_id', 'category_subchild', 'category_subchild_id', $category_subchild_id);
		
		$this->data['category_id'] = $category_id;
		$this->data['category_name'] = $category_name = $this->sub_category_model->get_category_name($category_id);
		$this->data['parent_category_name'] = $parent_category_name = $this->category_model->get_category_name($parent_category_id);
		
		$srch = get();
		$srch['category'] = $category_id;
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = "$category_name Management";
		$this->data['second_title'] = $category_name;
		$this->data['title'] = 'Category';
		$breadcrumb = array(
			array(
				'name' => "Category",
				'path' => base_url('category/list_record'),
			),
			array(
				'name' => "$parent_category_name",
				'path' => base_url('sub_category/list_record/'.$parent_category_id),
			),
			array(
				'name' => "$category_name",
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->sub_category_level_three->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->sub_category_level_three->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record/'.$category_id);
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->data['add_btn'] = 'Add Category';
		
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		$this->load->model('sub_category/sub_category_model');
		
		if($page == 'add'){
			$this->data['category_subchild_id'] = get('category_subchild_id');
			if($this->data['category_subchild_id']){
				$this->data['category_name'] =$this->sub_category_model->get_category_name($this->data['category_subchild_id']);
			}else{
				$this->data['category_name'] ='';
			}
			
			$this->data['title'] = 'Add Category';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->sub_category_level_three->getDetail($id);
			$this->data['title'] = 'Edit Category';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[category_subchild_name]['.$v.']', "name $v", 'required|trim|max_length[100]');
			}
			
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('category_subchild_key', 'category key', 'required|regex_match[/^[a-z\-A-Z]+$/]|is_unique[category_subchild_level_3.category_subchild_key]');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->sub_category_level_three->addRecord($post);
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
				$this->form_validation->set_rules('lang[category_subchild_name]['.$v.']', "name $v", 'required|trim|max_length[100]');
			}
			$this->form_validation->set_rules('status', 'status', '');
			
			if(post('category_subchild_key_old') !== post('category_subchild_key')){
				$this->form_validation->set_rules('category_subchild_key', 'category key', 'required|regex_match[/^[a-z\-A-Z]+$/]|is_unique[category_subchild_level_3.category_subchild_key]');
			}else{
				$this->form_validation->set_rules('category_subchild_key', 'category key', 'required|regex_match[/^[a-z\-A-Z]+$/]');
			}
			
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->sub_category_level_three->updateRecord($post, $ID);
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
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('category_subchild_status' => $sts));
			}else{
				$upd['data'] = array('category_subchild_status' => $sts);
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
			$this->sub_category_level_three->deleteRecord($id);
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





