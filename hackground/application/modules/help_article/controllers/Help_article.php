<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help_article extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		
		$this->load->model('help_article_model', 'help_article');
		$this->data['table'] = 'cms_help_article';
		$this->data['lang_table'] = 'cms_help_article_names';
		$this->data['primary_key'] = $this->data['table'].'_id';
		
		
		$model_configuration = array(
			'table' => $this->data['table'],
			'lang_table' => $this->data['lang_table'],
			'primary_key' => $this->data['primary_key'],
		);
		
		$this->help_article->configure($model_configuration);
		
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	
	public function list_record_old($cms_help_id=''){
		if(!$cms_help_id){
			show_404();
			return false;
		}
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['cms_help_id'] = $cms_help_id;
		$srch['cms_help_id'] = $cms_help_id;
		$cms_help_parent = getField('parent_id', 'cms_help', 'cms_help_id',  $cms_help_id);
		$parent_detail =  get_row(array(
			'select' => 'title',
			'from' => 'cms_help_names',
			'where' => array('lang' => admin_default_lang(), 'cms_help_id' => $cms_help_parent),
		));
		$this->data['cms_help_detail'] = get_row(array(
			'select' => 'title',
			'from' => 'cms_help_names',
			'where' => array('lang' => admin_default_lang(), 'cms_help_id' => $this->data['cms_help_id']),
		));
		$this->data['main_title'] = 'Article Management';
		$this->data['second_title'] = "<b>{$this->data['cms_help_detail']['title']}</b>";
		$this->data['title'] = 'Article List';
		
		$breadcrumb = array(
			array(
				'name' => 'Help CMS',
				'path' => base_url('help_cms/list_record'),
			),
			array(
				'name' => $parent_detail['title'],
				'path' => base_url('help_cms/list_record?parent='.$cms_help_parent),
			),
		
			array(
				'name' => "Article",
				'path' => '',
			),
		);
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->help_article->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->help_article->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record/'.$cms_help_id);
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->data['add_btn'] = 'Add Article';
		$this->layout->view('list', $this->data);
       
	}

	public function list_record(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Article Management';
		$this->data['second_title'] = "<b>lorem</b>";
		$this->data['title'] = 'Article List';
		
		$breadcrumb = array(
			array(
				'name' => 'Home',
				'path' => base_url(),
			),
			array(
				'name' => "Article Management",
				'path' => base_url($this->data['curr_controller'].'list_record'),
			),
		);
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->help_article->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->help_article->getList($srch, $limit, $offset, FALSE);
		
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
		$this->data['add_btn'] = 'Add Article';
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page_old(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Article';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
			
			if(get('cms_help_id') > 0){
				
				$this->data['cms_help_id'] = get('cms_help_id');
				$this->data['cms_help_detail'] = get_row(array(
					'select' => 'title',
					'from' => 'cms_help_names',
					'where' => array('lang' => admin_default_lang(), 'cms_help_id' => $this->data['cms_help_id']),
				));
				
				
			}else{
				$this->data['cms_help_id'] = 0;
				$this->data['cms_help_detail'] = array();
			}
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->help_article->getDetail($id);
			$this->data['title'] = 'Edit Article';
		}
		$this->load->view('ajax_page', $this->data);
	}

	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Article';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
			
			if(get('cms_help_id') > 0){
				
				$this->data['cms_help_id'] = get('cms_help_id');
				$this->data['cms_help_detail'] = get_row(array(
					'select' => 'title',
					'from' => 'cms_help_names',
					'where' => array('lang' => admin_default_lang(), 'cms_help_id' => $this->data['cms_help_id']),
				));
				
				
			}else{
				$this->data['cms_help_id'] = 0;
				$this->data['cms_help_detail'] = array();
			}
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->help_article->getDetail($id);
			$this->data['title'] = 'Edit Article';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[title]['.$v.']', "title $v", 'required|trim|max_length[100]');
				$this->form_validation->set_rules('lang[description]['.$v.']', "description $v", 'required|trim');
			}
			$this->form_validation->set_rules('slug', 'slug', 'required|regex_match[/^[a-z\-A-Z0-9]+$/]|is_unique[cms_help_article.slug]');
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->help_article->addRecord($post);
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
				$this->form_validation->set_rules('lang[description]['.$v.']', "description $v", 'required|trim');
			}
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->help_article->updateRecord($post, $ID);
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
			$this->help_article->deleteRecord($id);
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





