<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membership extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		
		$this->load->model('membership_model', 'membership');
		$this->data['table'] = 'membership';
		$this->data['lang_table'] = 'membership_names';
		$this->data['primary_key'] = 'membership_id';
		
		
		$model_configuration = array(
			'table' => $this->data['table'],
			'lang_table' => $this->data['lang_table'],
			'primary_key' => $this->data['primary_key'],
		);
		
		$this->membership->configure($model_configuration);
		
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
		$this->data['main_title'] = 'Membership Management';
		$this->data['second_title'] = 'All Membership List';
		$this->data['title'] = 'Membership';
		$breadcrumb = array(
			array(
				'name' => 'Membership',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->membership->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->membership->getList($srch, $limit, $offset, FALSE);
		
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
		$this->data['add_btn'] = 'Add membership';
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Membership';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->membership->getDetail($id);
			$this->data['title'] = 'Edit Membership';
		}else if($page == 'user_badge'){
			$this->load->model('badge/badge_model');
			$id = get('id');

			$this->data['ID']= $id;

			$this->data['form_action'] = base_url($this->data['curr_controller'].'save_user_badge');

			$this->data['detail'] = $this->membership->getDetail($id);
			$this->data['badges'] = $this->badge_model->getAllBadges();
			$this->data['user_badge'] = $this->membership->getUserBadge($id);
			$this->data['user_badge_array'] = get_k_value_from_array($this->data['user_badge'], 'ID');
			$this->data['title'] = 'Membership Badge';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[name]['.$v.']', "name $v", 'required|trim');
				$this->form_validation->set_rules('lang[description]['.$v.']', "description $v", 'required|trim');
			}
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->membership->addRecord($post);
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
				$this->form_validation->set_rules('lang[description]['.$v.']', "description $v", 'required|trim');
			}
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->membership->updateRecord($post, $ID);
				if($update){
					$member_membership=array(
						'max_bid' => !empty($post['membership_bid']) ? $post['membership_bid'] : '0',
						'max_portfolio' => !empty($post['membership_portfolio']) ? $post['membership_portfolio'] : '0',
						'max_skills' => !empty($post['membership_skills']) ? $post['membership_skills'] : '0',
						'commission_percent' => !empty($post['membership_commission_percent']) ? $post['membership_commission_percent'] : '0',
					);
					$this->db->where('membership_id',$ID)->update('member_membership',$member_membership);
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
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('membership_status' => $sts));
			}else{
				$upd['data'] = array('membership_status' => $sts);
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
			$this->membership->deleteRecord($id);
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
			$upload_dir = LC_PATH.'membership-icon/';
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
				$this->api->data('file_url', UPLOAD_HTTP_PATH.'membership-icon/'.$pathupload.$this->upload->data('file_name'));
			}
			

		}
		
		$this->api->out();
	}
	public function save_user_badge(){

		if(post() && $this->input->is_ajax_request()){

			$ID = post('ID');
			$badge = post('user_badge');
			$this->db->where('membership_id', $ID)->delete('membership_badge');
			
			if($badge && count($badge) > 0){
				$user_badge = array();
				foreach($badge as $b){
					$user_badge[] = array(
						'membership_id' => $ID,
						'badge_id' => $b,
					);	
				}
				$this->db->insert_batch('membership_badge', $user_badge);
			}
			$this->api->cmd('reload');

		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
}





