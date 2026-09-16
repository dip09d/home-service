<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		
		$this->load->model('blog_model', 'blog');
		$this->data['table'] = 'blog';
		$this->data['lang_table'] = 'blog_names';
		$this->data['category_table'] = 'blog_category';
		$this->data['images_table'] = 'blog_images';
		$this->data['tags_table'] = 'blog_tags';
		$this->data['primary_key'] = $this->data['table'].'_id';
		
		
		$model_configuration = array(
			'table' => $this->data['table'],
			'lang_table' => $this->data['lang_table'],
			'category_table' => $this->data['category_table'],
			'images_table' => $this->data['images_table'],
			'tags_table' => $this->data['tags_table'],
			'primary_key' => $this->data['primary_key'],
		);
		
		$this->blog->configure($model_configuration);
		
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
		$this->data['main_title'] = 'Blog Management';
		$this->data['second_title'] = 'Blog List';
		$this->data['title'] = 'Blog';
		$breadcrumb = array(
			array(
				'name' => 'Blog',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->blog->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->blog->getList($srch, $limit, $offset, FALSE);
		
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
		$this->data['add_btn'] = 'Add Blog';
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$this->load->model('category/category_model');
		$page = get('page');
		$this->data['page'] = $page;
		$this->data['category'] = $this->category_model->get_all_category();
		if($page == 'add'){
			$this->data['title'] = 'Add Blog';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->blog->getDetail($id);
			//print_r($this->data['detail']);
			$this->data['title'] = 'Edit Blog';
		}
		$this->data['all_blog']=$this->blog->get_all_blog();
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[blog_title]['.$v.']', "name $v", 'required|trim|max_length[300]');
				$this->form_validation->set_rules('lang[blog_description]['.$v.']', "content $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_title]['.$v.']', "meta title $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_keys]['.$v.']', "meta keys $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_description]['.$v.']', "meta description $v", 'required|trim');
			}
			
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('blog_slug', 'blog key', 'required|regex_match[/^[a-z\-A-Z]+$/]|is_unique[blog.blog_slug]');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->blog->addRecord($post);
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
				$this->form_validation->set_rules('lang[blog_title]['.$v.']', "name $v", 'required|trim|max_length[300]');
				$this->form_validation->set_rules('lang[blog_description]['.$v.']', "content $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_title]['.$v.']', "meta title $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_keys]['.$v.']', "meta keys $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_description]['.$v.']', "meta description $v", 'required|trim');
			}
			$this->form_validation->set_rules('status', 'status', '');
			
			if(post('blog_slug_old') !== post('blog_slug')){
				$this->form_validation->set_rules('blog_slug', 'blog key', 'required|regex_match[/^[a-z\-A-Z]+$/]|is_unique[blog.blog_slug]');
			}else{
				$this->form_validation->set_rules('blog_slug', 'blog key', 'required|regex_match[/^[a-z\-A-Z]+$/]');
			}
			
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->blog->updateRecord($post, $ID);
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
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('blog_status' => $sts));
			}else{
				$upd['data'] = array('blog_status' => $sts);
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
			$this->blog->deleteRecord($id);
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['table']);
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['lang_table']);
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['category_table']);
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['tags_table']);
				}else{
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['table']);
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['lang_table']);
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['category_table']);
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['tags_table']);
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
	public function upload_file($type=''){
		if($_FILES && $this->input->is_ajax_request()){
			$upload_dir = LC_PATH.'blog/';
			$pathupload='';
			if($type=='banner'){
				$upload_dir.=$pathupload='banner/';
			}elseif($type=='thumb'){
				$upload_dir.=$pathupload='thumb/';
			}
			if(!is_dir($upload_dir)){
				mkdir($upload_dir);
			}
			$config['upload_path']          = $upload_dir;
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['file_ext_tolower']        = TRUE;
			$config['encrypt_name']        = TRUE;
			
			$this->load->library('upload', $config);
			
			if(! $this->upload->do_upload('file')){
				
				$this->api->set_error('upload_error', $this->upload->display_errors());
				
			}else{
				
				$this->api->data('upload_data', $this->upload->data());
				
				$this->api->data('file_url', UPLOAD_HTTP_PATH.'blog/'.$pathupload.$this->upload->data('file_name'));
				
			}
			

		}
		
		$this->api->out();
	}
}





