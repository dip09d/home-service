<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skills extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		
		$this->load->model('skill_model', 'skill');
		$this->data['table'] = 'skills';
		$this->data['lang_table'] = 'skill_names';
		$this->data['primary_key'] = 'skill_id';
		
		
		$model_configuration = array(
			'table' => $this->data['table'],
			'lang_table' => $this->data['lang_table'],
			'primary_key' => $this->data['primary_key'],
		);
		
		$this->skill->configure($model_configuration);
		
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
		$this->data['main_title'] = 'Skills Management';
		$this->data['second_title'] = 'All Skills List';
		$this->data['title'] = 'Skills';
		$breadcrumb = array(
			array(
				'name' => 'Skills',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->skill->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->skill->getList($srch, $limit, $offset, FALSE);
		
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
		$this->data['add_btn'] = 'Add Skill';
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$this->load->model('sub_category/sub_category_model');
		$page = get('page');
		$this->data['page'] = $page;
		$this->data['speciality'] = $this->sub_category_model->getData();
		if($page == 'add'){
			$this->data['title'] = 'Add Skill';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->skill->getDetail($id);
			$this->data['title'] = 'Edit Skill';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[skill_name]['.$v.']', "name $v", 'required|trim|max_length[100]');
			}
			
			$this->form_validation->set_rules('skill_key', 'skill_key', 'required|is_unique[skills.skill_key]');
			$this->form_validation->set_rules('skill_status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$skill_speciality = post('speciality');
				$insert = $this->skill->addRecord($post);
				
				$this->db->where('skill_id', $insert)->delete('skill_speciality');
				if($skill_speciality){
					$ins = array();
					foreach($skill_speciality as $k => $v){
						$ins[] = array(
							'skill_id' => $insert,
							'speciality_id' => $v,
						);
					}
					$this->db->insert_batch('skill_speciality', $ins);
				}
				
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
				$this->form_validation->set_rules('lang[skill_name]['.$v.']', "name $v", 'required|trim|max_length[100]');
			}
			if(post('skill_key_old') !== post('skill_key')){
				$this->form_validation->set_rules('skill_key', 'skill_key', 'required|is_unique[skills.skill_key]');
			}else{
				$this->form_validation->set_rules('skill_key', 'skill_key', 'required');
			}
			
			$this->form_validation->set_rules('skill_status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$skill_speciality = post('speciality');
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->skill->updateRecord($post, $ID);
				
				$this->db->where('skill_id', $ID)->delete('skill_speciality');
				if($skill_speciality){
					$ins = array();
					foreach($skill_speciality as $k => $v){
						$ins[] = array(
							'skill_id' => $ID,
							'speciality_id' => $v,
						);
					}
					$this->db->insert_batch('skill_speciality', $ins);
				}
				
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
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('skill_status' => $sts));
			}else{
				$upd['data'] = array('skill_status' => $sts);
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
			$this->skill->deleteRecord($id);
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





