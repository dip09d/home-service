<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonial extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		
		$this->load->model('testimonial_model', 'testimonial');
		$this->data['table'] = 'testimonial';
		$this->data['lang_table'] = 'testimonial_names';
		$this->data['primary_key'] = 'testimonial_id';
		
		
		$model_configuration = array(
			'table' => $this->data['table'],
			'lang_table' => $this->data['lang_table'],
			'primary_key' => $this->data['primary_key'],
		);
		
		$this->testimonial->configure($model_configuration);
		
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
		$this->data['main_title'] = 'Testimonial Management';
		$this->data['second_title'] = 'All Testimonial List';
		$this->data['title'] = 'Testimonial';
		$breadcrumb = array(
			array(
				'name' => 'Testimonial',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->testimonial->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->testimonial->getList($srch, $limit, $offset, FALSE);
		
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
		$this->data['add_btn'] = 'Add testimonial';
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Testimonial';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->testimonial->getDetail($id);
			$this->data['title'] = 'Edit Testimonial';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[name]['.$v.']', "name $v", 'required|trim');
				$this->form_validation->set_rules('lang[company_name]['.$v.']', "company_name $v", 'required|trim');
				$this->form_validation->set_rules('lang[description]['.$v.']', "description $v", 'required|trim');
			}
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->testimonial->addRecord($post);
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
				$this->form_validation->set_rules('lang[name]['.$v.']', "name $v", 'required|trim');
				$this->form_validation->set_rules('lang[company_name]['.$v.']', "company_name $v", 'required|trim');
				$this->form_validation->set_rules('lang[description]['.$v.']', "description $v", 'required|trim');
			}
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->testimonial->updateRecord($post, $ID);
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
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('testimonial_status' => $sts));
			}else{
				$upd['data'] = array('testimonial_status' => $sts);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
			}
			
			if($action_type == 'multiple'){
				$this->api->cmd('reload');
			}else{
				
				$html = '';
				if($sts == ACTIVE_STATUS){
					$html = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, \''.$ID.'\', this)"><span class="badge badge-success">Active</span></a>';
				}else{
					$html = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, \''.$ID.'\', this)"><span class="badge badge-danger">Inactive</span></a>';
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
			$this->testimonial->deleteRecord($id);
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
	
	
	public function upload_file($type=''){
		if($_FILES && $this->input->is_ajax_request()){
			$upload_dir = LC_PATH.'testimonial-icon/';
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
				$this->api->data('file_url', UPLOAD_HTTP_PATH.'testimonial-icon/'.$pathupload.$this->upload->data('file_name'));
			}
			

		}
		
		$this->api->out();
	}
	
}





