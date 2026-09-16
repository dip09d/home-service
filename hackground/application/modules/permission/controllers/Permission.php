<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


 
class Permission extends MX_Controller {
	
	private $data;
	 
	public function __construct() {
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->data['primary_key'] = 'id';
		$this->data['table'] = 'adminmenu';
		
		
        $this->load->model('permission_model');
		$this->load->library('form_validation');
		
        parent::__construct();
		
		admin_log_check();
		
		
		
    }
    public function index() {
		redirect(base_url('permission/list_menu'));
    }
	
	public function list_menu(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Menu Management';
		$this->data['second_title'] = 'All Menu List';
		$this->data['title'] = 'Menu Management';
		$breadcrumb = array(
			array(
				'name' => $this->data['title'] ,
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] =  $this->permission_model->get_menu_for_list();
		
		$this->data['links'] = null;
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		
		
		$this->layout->view('list_menu', $this->data);
       
	}
	
	public function give_permission(){
		if(post()){
			$this->form_validation->set_rules('admin_role', 'admin role', 'required');
			if($this->form_validation->run()){
				$post = post();
				$admin_role = $post['admin_role'] ? $post['admin_role'] : 0;
				$menus = !empty($post['menu_code']) ? $post['menu_code'] : array();
				$update = $this->permission_model->update_permission($admin_role , $menus);
				if($update){
					set_flash('succ_msg', 'Successfully saved');
				}else{
					set_flash('error_msg', 'Unable to save data');
				}
				redirect('permission/give_permission');
			}
		}
		
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Menu Permission';
		$this->data['second_title'] = 'Give Menu Permission';
		$this->data['title'] = 'Menu Permission';
		$breadcrumb = array(
			array(
				'name' => $this->data['title'] ,
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] =  $this->permission_model->get_active_menu_for_list();
		
		$this->data['links'] = null;
		
		$this->data['user_permission'] = array();
		if(!empty($srch['role'])){
			$user_permission_arr= get_results(array('select' => 'menu_code', 'from' => 'adminmenu_permission', 'where' => array('role_id' =>  $srch['role']), 'offset' => 'all'));
			if($user_permission_arr){
				foreach($user_permission_arr as $k => $v){
					$this->data['user_permission'][] = $v['menu_code'];
				}
			}
			$role_name = getField('name', 'admin_role', 'role_id', $srch['role']);
			$this->data['title'] .= ' &nbsp; &nbsp; <span class="badge badge-secondary">'.$role_name.'</span>';
		}
		$this->data['user_type'] = get_results(array('select' => '*', 'from' => 'admin_role', 'where' => array('status' => ACTIVE_STATUS), 'offset' => 'all'));
		$this->data['srch'] = $srch;
		
		$this->layout->view('give_permission', $this->data);
       
	}
	
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Menu';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
			
			if(get('parent_id')){
				
				$this->data['parent'] = get_row(array('select' => '*', 'from' => 'adminmenu', 'where' => array('id' => get('parent_id'))));
			}else{
				$this->data['parent'] = array();
			}
			
		
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = get_row(array('select' => '*', 'from' => 'adminmenu', 'where' => array('id' => $id)));
			$this->data['title'] = 'Edit Menu';
			
			if(get('parent_id')){
				$this->data['parent'] = get_row(array('select' => '*', 'from' => 'adminmenu', 'where' => array('id' => get('parent_id'))));
			}else{
				$this->data['parent'] = array();
			}
			
		}else if($page == 'add_role'){
			
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add_role');
			$this->data['title'] = 'Add Role';
		
		}else if($page == 'edit_role'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit_role');
			$this->data['detail'] = get_row(array('select' => '*', 'from' => 'admin_role', 'where' => array('role_id' => $id)));
			$this->data['title'] = 'Edit Menu';
			
		}
		
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		
		if(post() && $this->input->is_ajax_request()){
			
			$this->form_validation->set_rules('name','menu name','required');
			$this->form_validation->set_rules('url','url','required');
			
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->permission_model->addMenu($post);
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
			
			$this->form_validation->set_rules('name','menu name','required');
			$this->form_validation->set_rules('url','url','required');
			
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update =  $this->permission_model->updateMenu($post,$ID);
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
	
	public function add_role(){
		
		if(post() && $this->input->is_ajax_request()){
			
			$this->form_validation->set_rules('name','name','required');
			$this->form_validation->set_rules('status', 'status', 'required');
			
			if($this->form_validation->run()){
				$post = post();
				$record['table'] = 'admin_role';
				$record['data'] = $post;
				$insert = insert($record, TRUE);
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
	
	public function edit_role(){
		
		if(post() && $this->input->is_ajax_request()){
			
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('status', 'status', 'required');
			
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$record['table'] = 'admin_role';
				$record['data'] = $post;
				$record['where'] = array('role_id' => $ID);
				$update =  update($record);
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
	
	public function role_list(){
		$this->data['main_title'] = 'Role Management';
		$this->data['second_title'] = 'All Role List';
		$this->data['title'] = 'Role Management';
		$breadcrumb = array(
			array(
				'name' => $this->data['title'] ,
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
		$this->data['list'] =  get_table('admin_role', array('status <>' =>  DELETE_STATUS));
		$this->data['add_command'] = 'add_role';
		$this->data['edit_command'] = 'edit_role';
		$this->data['add_btn'] = 'Add Role';
		$this->layout->view('role_list',$this->data);
		
	}
	
	
	public function delete_role_record($id=''){
		$this->data['primary_key'] = 'role_id';
		$this->data['table'] = 'admin_role';
		
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		
		
		if($id){
			
			if(is_array($id)){
				$this->db->where_in($this->data['primary_key'], $id)->update($this->data['table'], array('status' => DELETE_STATUS));
			}else{
				$ins['data'] = array('status' => DELETE_STATUS);
				$ins['table'] = $this->data['table'];
				$ins['where'] = array($this->data['primary_key'] => $id);
				update($ins);
			}
		
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
	
	public function change_role_status(){
		if(post() && $this->input->is_ajax_request()){
			
			$ID = post('ID');
			$sts = post('status');
			$action_type = post('action_type');
			
			if(is_array($ID)){
				$this->db->where_in('role_id', $ID)->update('admin_role', array('status' => $sts));
			}else{
				$upd['data'] = array('status' => $sts);
				$upd['where'] = array('role_id' => $ID);
				$upd['table'] = 'admin_role';
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
	
	public function delete_menu($menu_id=''){
		$this->db->where('id', $menu_id)->delete('adminmenu');
		$this->db->where('parent_id', $menu_id)->delete('adminmenu');
		$this->db->where('menu_id', $menu_id)->delete('adminmenu_permission');
		
		set_flash('succ_msg', 'Successfully deleted');
		$this->api->cmd('reload');
		//redirect('permission/list_menu');
		$this->api->out();
	}
	public function delete_record($id=''){
		//$this->data['primary_key'] = 'role_id';
		//$this->data['table'] = 'admin_role';
		
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		
		
		if($id){
			
			if(is_array($id)){
				$this->db->where_in('id', $id)->delete('adminmenu');
				$this->db->where_in('parent_id', $id)->delete('adminmenu');
				$this->db->where_in('menu_id', $id)->delete('adminmenu_permission');
			}else{
				$this->db->where('id', $id)->delete('adminmenu');
				$this->db->where('parent_id', $id)->delete('adminmenu');
				$this->db->where('menu_id', $id)->delete('adminmenu_permission');
			}
		
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in('id', $id)->delete('adminmenu');
					$this->db->where_in('parent_id', $id)->delete('adminmenu');
					$this->db->where_in('menu_id', $id)->delete('adminmenu_permission');
				}else{
					$this->db->where('id', $id)->delete('adminmenu');
					$this->db->where('parent_id', $id)->delete('adminmenu');
					$this->db->where('menu_id', $id)->delete('adminmenu_permission');
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}

}
