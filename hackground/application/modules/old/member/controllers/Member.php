<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Member extends MX_Controller {

   

   private $data;

   

	public function __construct(){

		$this->data['curr_controller'] = $this->router->fetch_class()."/";

		$this->data['curr_method'] = $this->router->fetch_method()."/";

		$this->load->model('member_model', 'member');

		$this->data['table'] = 'member';

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

		$this->data['main_title'] = 'Member Management';

		$this->data['second_title'] = 'All Member List';

		$this->data['title'] = 'Member';

		$breadcrumb = array(

			array(

				'name' => 'Member',

				'path' => '',

			),

		);

		$this->data['breadcrumb'] = breadcrumb($breadcrumb);

		$this->data['list'] = $this->member->getList($srch, $limit, $offset);

		$this->data['list_total'] = $this->member->getList($srch, $limit, $offset, FALSE);

		

		$this->load->library('pagination');

		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');

		$config['total_rows'] =$this->data['list_total'];

		$config['per_page'] = $offset;

		$config['page_query_string'] = TRUE;

		$config['reuse_query_string'] = TRUE;

		

		$this->pagination->initialize($config);

		

		$this->data['links'] = $this->pagination->create_links();

		$this->data['add_command'] = null;

		$this->data['edit_command'] = 'edit';

		$this->layout->view('list', $this->data);

       

	}

	

	public function load_ajax_page(){

		$page = get('page');

		$this->data['page'] = $page;

		if($page == 'add'){

			$this->data['title'] = 'Add Member';

			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');

		}else if($page == 'edit'){

			$id = get('id');

			$this->data['ID']= $id;

			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');

			$this->data['detail'] = $this->member->getDetail($id);
			$this->data['detail']['is_login']=0;
			$this->db->select('a.login_status');
			$this->db->from('access_panel a');
			$this->db->join('member as m','m.access_user_id=a.access_user_id','left');
			$this->db->where(array('m.member_id'=>$id));
			$dataD=$this->db->get()->row();
			if($dataD){
				$this->data['detail']['is_login']=$dataD->login_status;
			}
			$this->data['title'] = 'Edit Member';
			
		}else if($page == 'user_badge'){
			$this->load->model('badge/badge_model');
			$id = get('id');

			$this->data['ID']= $id;

			$this->data['form_action'] = base_url($this->data['curr_controller'].'save_user_badge');

			$this->data['detail'] = $this->member->getDetail($id);
			$this->data['badges'] = $this->badge_model->getAllBadges();
			$this->data['user_badge'] = $this->member->getUserBadge($id);
			$this->data['user_badge_array'] = get_k_value_from_array($this->data['user_badge'], 'ID');
			$this->data['title'] = 'User Badge';
		}

		$this->load->view('ajax_page', $this->data);

	}

	

	public function add(){

		if(post() && $this->input->is_ajax_request()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');

			$this->form_validation->set_rules('status', 'status', '');

			if($this->form_validation->run()){

				$post = post();

				$insert = $this->member->addRecord($post);

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

			$this->form_validation->set_rules('member_name', 'name', 'required|trim|max_length[100]');

			$this->form_validation->set_rules('member_email', 'email', 'required|trim|max_length[100]|valid_email');

			if($this->input->post('new_pass')){
				$this->form_validation->set_rules('new_pass', 'New Password', 'required|trim');
				$this->form_validation->set_rules('new_pass_again', 'Confirm Password', 'required|trim|matches[new_pass]');
			}

			$this->form_validation->set_rules('ID', 'id', 'required');

			if($this->form_validation->run()){

				$post = post();

				$ID = post('ID');
				$login_status=post('is_login');
				unset($post['ID']);
				unset($post['is_login']);
				
				
				unset($post['new_pass_again']);
				unset($post['new_pass']);
				$update = $this->member->updateRecord($post, $ID);
				$profile_connection=$this->db->select('access_user_id')->where('member_id',$ID)->from('member')->get()->row();
				$this->db->where('access_user_id',$profile_connection->access_user_id)->update('access_panel',array('login_status'=>$login_status));
				if($this->input->post('new_pass')){
					if($profile_connection){
						$password=md5($this->input->post('new_pass'));
						$this->db->where('access_user_id',$profile_connection->access_user_id)->update('access_panel',array('access_user_password'=>$password));
					}
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

				//$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('status' => $sts));

			}else{
				$profile_connection=$this->db->select('access_user_id')->where('member_id',$ID)->from('member')->get()->row();
				$upd['data'] = array('login_status' => $sts);
				$upd['where'] = array('access_user_id' => $profile_connection->access_user_id);
				$upd['table'] = 'access_panel';
				update($upd);

				if($sts==0){
					$member_id=$ID;
					$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
					$data_parse=array(
					'MEMBER_NAME'=>getField('member_name','member','member_id',$member_id),
					'CUSTOMER_SUPPORT_URL'=>SITE_URL.'cms/support',
					);
					$template='member-blocked-by-admin';
					SendMail($RECEIVER_EMAIL,$template,$data_parse);
				}
				

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

			$this->member->deleteRecord($id);

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
	
	public function view_edit($module='', $member_id=''){
		
		$this->data['detail'] = $this->member->getAllDetail($member_id);
		$this->data['main_title'] = 'Member Detail ';
		$this->data['second_title'] =  $this->data['detail']['member_name'] ;
		
		
		$this->data['module'] = $module;
		$this->data['member_id'] = $member_id;
		$this->data['organization_id'] = $this->data['detail']['organization_id'];
		if($module == 'basic_info'){
			$this->_member_basic_info($member_id);
		}else if($module == 'professional_info'){
			$this->_member_professional_info($member_id);
		}else if($module == 'resume'){
			$this->_member_resume($member_id);
		}else if($module == 'industry'){
			$this->_member_industry($member_id);
		}else if($module == 'location'){
			$this->_member_location($member_id);
		}else if($module == 'profile_detail'){
			$this->_member_profile_detail($member_id);
		}else if($module == 'skills'){
			$this->_member_skills($member_id);
		}else if($module == 'language'){
			$this->_member_language($member_id);
		}else if($module == 'employment'){
			$this->_member_employment($member_id);
		}else if($module == 'education'){
			$this->_member_education($member_id);
		}else if($module == 'portfolio'){
			$this->_member_portfolio($member_id);
		}else if($module == 'organization_location'){
			$this->_organization_location($member_id);
		}else if($module == 'organization_details'){
			$this->_organization_details($member_id);
		}else{
			show_404();
			return;
		}
		$this->layout->view('member-detail', $this->data);
       
	}
	public function getcity(){
		$country_code=$this->input->post('country_code');
		$all_city=getAllCity(array('country_code'=>$country_code));
		echo '<select class="form-control" name="organization_address[city_id]"><option value="">-Select-</option>';
		if($all_city){
			foreach($all_city as $k=>$city){
				echo '<option value="'.$city->city_id.'">'.$city->city_name.'</option>';
			}
		}
		
		echo '</select>';
	}
	private function _member_skills($member_id=''){
		$this->data['title'] = 'Skills';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->load->model('skills/skill_model');
		$this->data['page'] = 'member-skills';
		$this->data['all_skills']= $this->skill_model->getAllSkill();
	}
	
	private function _member_language($member_id=''){
		$this->data['title'] = 'Language';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'member-language';
	}
	
	private function _member_employment($member_id=''){
		$this->data['title'] = 'Employment';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'member-employment';
	}
	
	private function _member_portfolio($member_id=''){
		$this->data['title'] = 'Portfolio';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'member-portfolio';
	}
	
	private function _member_education($member_id=''){
		$this->data['title'] = 'Education';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'member-education';
	}
	
	
	private function _member_basic_info($member_id=''){
		$this->data['title'] = 'Basic Info';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'member-basic-info';
	}
	
	private function _member_profile_detail($member_id=''){
		$this->data['title'] = 'Detail';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'member-profile-detail';
	}
	
	
	private function _member_professional_info($member_id=''){
		$this->data['title'] = 'Professional Info';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['options']['career_level'] = $this->member->getOption('career_level');
		$this->data['options']['current_position'] = $this->member->getOption('current_position');
		$this->data['options']['salary_expectation'] = $this->member->getOption('salary_expectation');
		$this->data['options']['commitment'] = $this->member->getOption('commitment');
		$this->data['options']['notice_period'] = $this->member->getOption('notice_period');
		$this->data['options']['visa_status'] = $this->member->getOption('visa_status');
		//get_print($this->data, false);
		$this->data['page'] = 'member-professional-info';
	}
	
	private function _member_resume($member_id=''){
		$this->data['title'] = 'Resume';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['options']['academy'] = $this->member->getOption('academy');
		$this->data['page'] = 'member-resume';
	}
	
	private function _member_industry($member_id=''){
		$this->data['title'] = 'Industry';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		/* $this->data['options']['industry'] = $this->member->getOption('experience_area'); */
		$this->data['options']['industry'] = $this->member->getAllIndustry();
		$this->data['experience'] = array(
			'0' => '0-1 Years',
			'1' => '1-2 Years',
			'2' => '2-5 Years',
			'5' => '5-10 Years',
			'10' => '10-15 Years',
			'15' => '15+ Years',
		);
		$this->data['page'] = 'member-industry';
	}
	
	private function _member_location($member_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'member-location';
	}
	private function _organization_location($member_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'organization-location';
	}
	private function _organization_details($member_id=''){
		$this->data['title'] = 'Detail';
		$breadcrumb = array(
			array(
				'name' => 'Member',
				'path' => base_url('member/list_record'),
			),
			array(
				'name' => $this->data['detail']['member_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('member/edit_member_info');
		
		/* Attributes */
		$this->data['page'] = 'organization-details';
	}
	public function edit_member_info(){
		if(post() && $this->input->is_ajax_request()){
			$page = post('page');
			
			if($page == 'member-basic-info'){
				$this->load->library('form_validation');
				/* $this->form_validation->set_rules('member_basic[gender]', 'gender', 'required|trim');
				$this->form_validation->set_rules('member_basic[member_nationality]', 'nationality', 'required|trim');
				$this->form_validation->set_rules('member_basic[dob]', 'dob', 'required|trim'); */
				$this->form_validation->set_rules('member[member_name]', 'name', 'required|trim');
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$post = post();
					$update = $this->member->saveMemberInfo($post, $member_id);
					
					$member_logo = post('member_logo');
					if(!empty($member_logo)){
						$this->member->updateMemberLogo($member_logo, $member_id);
					}
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-professional-info'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('member_professional[member_career_level]', 'career level', 'required');
				$this->form_validation->set_rules('member_address[member_current_location]', 'current location', 'required');
				$this->form_validation->set_rules('member_professional[member_current_position]', 'current position', 'required');
				$this->form_validation->set_rules('member_professional[member_current_company]', 'current company', 'required');
				$this->form_validation->set_rules('member_professional[member_salary_expectation]', 'salary expectation', 'required');
				$this->form_validation->set_rules('member_professional[member_commitment]', 'commitment', 'required');
				$this->form_validation->set_rules('member_professional[member_notice_period]', 'notice period', 'required');
				$this->form_validation->set_rules('member_professional[member_visa_status]', 'visa status', 'required');
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$post = post();
					$update = $this->member->saveMemberInfo($post, $member_id);
					
					$this->api->cmd('reload');
				
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-location'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('member_address[member_timezone]', 'timezone', 'required');
				$this->form_validation->set_rules('member_address[member_country]', 'country', 'required');
				$this->form_validation->set_rules('member_address[member_address_1]', 'address line 1', 'required');
				//$this->form_validation->set_rules('member_address[member_address_2]', 'address line 2', 'required');
				//$this->form_validation->set_rules('member_address[city_id]', 'city', 'required');
				//$this->form_validation->set_rules('member_address[member_city]', 'city', 'required');
				$this->form_validation->set_rules('member_address[member_state]', 'state', 'required');
				$this->form_validation->set_rules('member_address[member_pincode]', 'pincode', 'required');
				
				if($this->form_validation->run()){
					
					$member_id = post('ID');
					$post = post();
					$update = $this->member->saveMemberInfo($post, $member_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'organization-location'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('organization_address[organization_timezone]', 'timezone', 'required');
				$this->form_validation->set_rules('organization_address[organization_country]', 'country', 'required');
				$this->form_validation->set_rules('organization_address[organization_address_1]', 'address line 1', 'required');
				//$this->form_validation->set_rules('organization_address[organization_address_2]', 'address line 2', 'required');
				//$this->form_validation->set_rules('organization_address[city_id]', 'city', 'required');
				//$this->form_validation->set_rules('organization_address[organization_city]', 'city', 'required');
				$this->form_validation->set_rules('organization_address[organization_state]', 'state', 'required');
				$this->form_validation->set_rules('organization_address[organization_pincode]', 'pincode', 'required');
				
				if($this->form_validation->run()){
					
					$organization_id = post('ID');
					$post = post();
					$update = $this->member->saveOrganizationInfo($post, $organization_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'organization-details'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('organization_basic[organization_name]', 'organization_name', 'required');
				$this->form_validation->set_rules('organization_basic[organization_website]', 'organization_website', 'required');
				$this->form_validation->set_rules('organization_basic[organization_heading]', 'organization_heading', 'required');
				$this->form_validation->set_rules('organization_basic[organization_info]', 'organization_info', 'required');
				
				if($this->form_validation->run()){
					
					$organization_id = post('ID');
					$post = post();
					$update = $this->member->saveOrganizationInfo($post, $organization_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-industry'){
				
				$this->load->library('form_validation');
				$experience = $this->input->post('experience');
				if($experience){
					foreach($experience as $k => $v){
						$this->form_validation->set_rules('industry['.$k.']', 'industry', 'required');
					}
				}else{
					$this->form_validation->set_rules('no_industry', 'industry', 'required');
				}
				
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$post = post();
					$update = $this->member->saveMemberIndustry($post, $member_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-resume'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('member_basic[member_cv_summary]', 'cv summary', 'required');
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$post = post();
					$update = $this->member->saveMemberInfo($post, $member_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-profile-detail'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('member_basic[member_overview]', 'overview', 'required');
				$this->form_validation->set_rules('member_basic[member_heading]', 'heading', 'required');
				$this->form_validation->set_rules('member_basic[member_hourly_rate]', 'hourly rate', 'required');
				if(post('is_available') == '0'){
					$this->form_validation->set_rules('member_basic[not_available_until]', 'availablity', 'required');
				}else{
					$this->form_validation->set_rules('member_basic[available_per_week]', 'availablity', 'required');
				}
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$post = post();
					if($post['is_available'] == '1'){
						$post['member_basic']['not_available_until'] = NULL;
					}else{
						$post['member_basic']['available_per_week'] = NULL;
					}
					$update = $this->member->saveMemberInfo($post, $member_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-skills'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('skills', 'overview', 'required');
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$all_skill=post('skills');
					delete_record('member_skills',array('member_id'=>$member_id));
					if($all_skill){
						$sk=explode(',',$all_skill);
						foreach($sk as $ord=>$skill_id){
							insert_record('member_skills',array('member_id'=>$member_id,'skill_id'=>$skill_id,'member_skills_order'=>$ord),TRUE);
						}
					}
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-language'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('language', 'language', 'required');
				$this->form_validation->set_rules('language_preference', 'language_preference', 'required');
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$member_language_id = post('member_language_id');
					
					$memberDatacount=getData(array(
						'select'=>'m_l.member_language_id',
						'table'=>'member_language as m_l',
						'join'=>array(array('table'=>'language as l','on'=>'m_l.language_id=l.language_id','position'=>'left')),
						'where'=>array('m_l.member_id'=>$member_id,'l.language_id'=>post('language')),
						'return_count'=>true,
					));
					if(!$memberDatacount){
						$up=insert_record('member_language',array('member_id'=>$member_id,'language_id'=>post('language'),'language_preference_id'=>post('language_preference'),'language_status'=>1),TRUE);
					}else{
						$up=updateTable('member_language',array('language_preference_id'=>post('language_preference'),'language_status'=>1),array('member_id'=>$member_id,'language_id'=>post('language')));
					}
						
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-employment'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('company', 'Company', 'required|trim');
				$this->form_validation->set_rules('city', 'City', 'required|trim');
				$this->form_validation->set_rules('country', 'Country', 'required|trim');
				$this->form_validation->set_rules('title', 'Title', 'required|trim');
				$this->form_validation->set_rules('role', 'Role', 'required|trim');
				$this->form_validation->set_rules('frommonth', 'Month', 'required|trim');
				$this->form_validation->set_rules('fromyear', 'year', 'required|trim');
				if(post('employment_is_working_on')){
					
				}else{
					$this->form_validation->set_rules('tomonth', 'Month', 'required|trim');
					$this->form_validation->set_rules('toyear', 'year', 'required|trim|greater_than_equal_to['.post('fromyear').']');
				}
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$dataid = post('employment_id');
					
					$memberDatacount=getData(array(
						'select'=>'m_e.employment_id',
						'table'=>'member_employment as m_e',
						'where'=>array('m_e.member_id'=>$member_id,'m_e.employment_id'=>$dataid),
						'single_row'=>true,
					));
						$employment_is_working_on=NULL;
						$employment_to=NULL;
						$f_year=post('fromyear');
						$f_month=post('frommonth');
						if(strlen($f_month)==1){
							$f_month="0".$f_month;
						}
						$employment_from=$f_year."-".$f_month."-01";
						if(post('employment_is_working_on')){
							$employment_is_working_on=1;
						}else{
							$t_year=post('toyear');
							$t_month=post('tomonth');
							if(strlen($t_month)==1){
								$t_month="0".$t_month;
							}
							$employment_to=$t_year."-".$t_month."-01";
						}
						
						if($memberDatacount){
							$up=updateTable('member_employment',array('employment_company'=>post('company'),'employment_city'=>post('city'),'employment_country_code'=>post('country'),'employment_title'=>post('title'),'employment_role'=>post('role'),'employment_from'=>$employment_from,'employment_to'=>$employment_to,'employment_is_working_on'=>$employment_is_working_on,'employment_description'=>post('description'),'employment_status'=>1),array('member_id'=>$member_id,'employment_id'=>$memberDatacount->employment_id));
						}else{
							$up=insert_record('member_employment',array('member_id'=>$member_id,'employment_company'=>post('company'),'employment_city'=>post('city'),'employment_country_code'=>post('country'),'employment_title'=>post('title'),'employment_role'=>post('role'),'employment_from'=>$employment_from,'employment_to'=>$employment_to,'employment_is_working_on'=>$employment_is_working_on,'employment_description'=>post('description'),'employment_status'=>1),TRUE);
						}
						
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-education'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('school', 'School', 'required|trim');
				$this->form_validation->set_rules('from_year', 'From year', 'required|trim');
				$this->form_validation->set_rules('end_year', 'To year', 'required|trim|greater_than_equal_to['.post('from_year').']');
				
				if($this->form_validation->run()){
					$member_id = post('ID');
					$dataid = post('education_id');
					
						$memberDatacount=getData(array(
							'select'=>'m_e.education_id',
							'table'=>'member_education as m_e',
							'where'=>array('m_e.member_id'=>$member_id,'m_e.education_id'=>$dataid),
							'single_row'=>true,
						));
						$data_ins=array(
							'education_school'=>post('school'),
							'education_from_year'=>post('from_year'),
							'education_end_year'=>post('end_year'),
							'education_degree'=>NULL,
							'education_area_of_study'=>NULL,
							'education_description'=>NULL,
							'education_status'=>1,
						);
						if(post('degree')){
							$data_ins['education_degree']=post('degree');
						}
						if(post('area_of_study')){
							$data_ins['education_area_of_study']=post('area_of_study');
						}
						if(post('description')){
							$data_ins['education_description']=post('description');
						}

						if($memberDatacount){
							$up=updateTable('member_education',$data_ins,array('member_id'=>$member_id,'education_id'=>$memberDatacount->education_id));
						}else{
							$data_ins['member_id']=$member_id;
							$up=insert_record('member_education',$data_ins,TRUE);
						}
						
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'member-portfolio'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Project Title', 'required|trim');
				$this->form_validation->set_rules('description', 'Project Overview', 'required|trim');
				$this->form_validation->set_rules('category', 'Category', 'required|trim');
				if(post('category')){
					$this->form_validation->set_rules('sub_category', 'Sub Category', 'required|trim');
				}
				if(post('complete_date')){  
					$this->form_validation->set_rules('complete_date', 'Date', 'required|trim');
					
				}
					
				if($this->form_validation->run()){
					$member_id = post('ID');
					$dataid = post('portfolio_id');
					
						$memberDatacount=getData(array(
							'select'=>'m_p.portfolio_id',
							'table'=>'member_portfolio as m_p',
							'where'=>array('m_p.member_id'=>$member_id,'m_p.portfolio_id'=>$dataid),
							'single_row'=>true,
						));
						$data_ins=array(
							'portfolio_title'=>post('title'),
							'category_id'=>post('category'),
							'category_subchild_id'=>post('sub_category'),
							'portfolio_description'=>post('description'),
							'portfolio_url'=>NULL,
							'portfolio_complete_date'=>NULL,
							'portfolio_status'=>1,
							'portfolio_image'=>NULL,
						);
						if(post('url')){
							$data_ins['portfolio_url']=addhttp(trim(strtolower(post('url'))));
						}
						if(post('complete_date')){
							$data_ins['portfolio_complete_date']=date('Y-m-d',strtotime(post('complete_date')));
						}
						if(post('portfolio_image')){
							$data_ins['portfolio_image']=json_encode(array('name'=>post('portfolio_image'),'file'=>post('portfolio_image')));
						}elseif(post('pre_portfolio_image')){
							$data_ins['portfolio_image']=post('pre_portfolio_image');
						}
						if($memberDatacount){
							$up=updateTable('member_portfolio',$data_ins,array('member_id'=>$member_id,'portfolio_id'=>$memberDatacount->portfolio_id));
						}else{
							$data_ins['member_id']=$member_id;
							$up=insert_record('member_portfolio',$data_ins,TRUE);
						}
						
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function upload_file(){
		if($_FILES && $this->input->is_ajax_request()){
			$upload_dir = LC_PATH.'member-logo/';
			if($this->input->get('type') && $this->input->get('type')=='portfolio'){
				$upload_dir = LC_PATH.'member-portfolio/';
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
				if($this->input->get('type') && $this->input->get('type')=='portfolio'){
				$this->api->data('file_url', UPLOAD_HTTP_PATH.'member-portfolio/'.$this->upload->data('file_name'));
				}else{
					$this->api->data('file_url', UPLOAD_HTTP_PATH.'member-logo/'.$this->upload->data('file_name'));
				}
				
			}
			

		}
		
		$this->api->out();
	}
	
	public function ajax_modal(){
		$page = get('page');
		$modal_page = $page.'-ajax';
		$this->data['page'] = $page;
		
		/*action url to handle modal forms */
		$this->data['action'] = base_url('member/edit_member_info');
		if($page == 'member-language'){
			$this->load->model('language/language_model');
			$this->load->model('language_preference/language_preference_model');
			$this->data['language'] = $this->language_model->getAllLanguage();
			$this->data['language_preference'] = $this->language_preference_model->getAllLanguagePreference();
			$this->data['member_language_id'] = get('member_language_id');
			$this->data['ID'] = get('ID');
			if($this->data['member_language_id']){
				$this->data['detail'] = get_row(array(
					'select' => '*',
					'from' => 'member_language',
					'where' => array(
						'member_language_id' => $this->data['member_language_id']
					),
				));
			}else{
				$this->data['detail'] = array();
			}
		}else if($page == 'member-employment'){
			$this->data['country']=get_all_country();
			$this->data['role']=getRoleUserEmployemnt();
			$this->data['month']=getMonth();
			$this->data['employment_id'] = get('employment_id');
			$this->data['ID'] = get('ID');
			if($this->data['employment_id']){
				$this->data['detail'] = get_row(array(
					'select' => '*',
					'from' => 'member_employment',
					'where' => array(
						'employment_id' => $this->data['employment_id']
					),
				));
			}else{
				$this->data['detail'] = array();
			}
		}else if($page == 'member-education'){
			$this->data['country']=get_all_country();
			$this->data['role']=getRoleUserEmployemnt();
			$this->data['month']=getMonth();
			$this->data['education_id'] = get('education_id');
			$this->data['ID'] = get('ID');
			if($this->data['education_id']){
				$this->data['detail'] = get_row(array(
					'select' => '*',
					'from' => 'member_education',
					'where' => array(
						'education_id' => $this->data['education_id']
					),
				));
			}else{
				$this->data['detail'] = array();
			}
		}else if($page == 'member-portfolio'){
			$this->load->model('category/category_model');
			$this->load->model('sub_category/sub_category_model');
			
			$this->data['portfolio_id'] = get('portfolio_id');
			$this->data['ID'] = get('ID');
			if($this->data['portfolio_id']){
				$this->data['detail'] = get_row(array(
					'select' => '*',
					'from' => 'member_portfolio',
					'where' => array(
						'portfolio_id' => $this->data['portfolio_id']
					),
				));
			}else{
				$this->data['detail'] = array();
			}
			
			$this->data['category'] = $this->category_model->get_all_category();
			
			$this->sub_category_model->configure(array(
				'table' => 'category_subchild',
				'lang_table' => 'category_subchild_names',
				'primary_key' => 'category_subchild_id',
			));
			
			$srch = array();
			if(!empty($this->data['detail']['category_id'])){
				$srch['category'] = $this->data['detail']['category_id'];
			}else{
				$srch['category'] = 'abcd';
			}
			
			$this->data['sub_category'] = $this->sub_category_model->getList($srch, 0, 300);
			
			
		}
		
		$this->load->view($modal_page, $this->data);
	}
	
	public function delete_data(){
		$data=array();
		$formtype=post('formtype');
		$dataid=post('Okey');
		$member_id=post('Mkey');
		$up='';
		if($formtype=='language'){
			$memberlanguage=getData(array(
				'select'=>'m_l.member_language_id',
				'table'=>'member_language as m_l',
				'where'=>array('m_l.member_id'=>$member_id,'m_l.member_language_id'=>$dataid),
				'single_row'=>true,
				));
			if($memberlanguage){
				$up=updateTable('member_language',array('language_status'=>0),array('member_language_id'=>$memberlanguage->member_language_id));
			}
		}elseif($formtype=='employment'){
			$memberemployment=getData(array(
				'select'=>'m_e.employment_id',
				'table'=>'member_employment as m_e',
				'where'=>array('m_e.member_id'=>$member_id,'m_e.employment_id'=>$dataid),
				'single_row'=>true,
				));
			if($memberemployment){
				$up=updateTable('member_employment',array('employment_status'=>0),array('employment_id'=>$memberemployment->employment_id));
			}
		}elseif($formtype=='education'){
			$membereducation=getData(array(
				'select'=>'m_e.education_id',
				'table'=>'member_education as m_e',
				'where'=>array('m_e.member_id'=>$member_id,'m_e.education_id'=>$dataid),
				'single_row'=>true,
				));
			if($membereducation){
				$up=updateTable('member_education',array('education_status'=>0),array('education_id'=>$membereducation->education_id));
			}
		}elseif($formtype=='portfolio'){
			$memberportfolio=getData(array(
				'select'=>'m_p.portfolio_id',
				'table'=>'member_portfolio as m_p',
				'where'=>array('m_p.member_id'=>$member_id,'m_p.portfolio_id'=>$dataid),
				'single_row'=>true,
				));
			if($memberportfolio){
				$up=updateTable('member_portfolio',array('portfolio_status'=>0),array('portfolio_id'=>$memberportfolio->portfolio_id));
			}
		}
		$this->api->cmd('reload');
		$this->api->out();
		
	}
	
	public function create_account(){

		$this->data['main_title'] = 'Member Management';

		$this->data['second_title'] = 'Create Account';

		$this->data['title'] = 'Create Account';

		$breadcrumb = array(

			array(

				'name' => 'Member',

				'path' => '',

			),

		);

		$this->data['breadcrumb'] = breadcrumb($breadcrumb);

		$this->data['country'] = get_all_country();
		$this->layout->view('create-account', $this->data);

       
	}
	
	public function save_user_badge(){

		if(post() && $this->input->is_ajax_request()){

			$ID = post('ID');
			$badge = post('user_badge');
			$this->db->where('member_id', $ID)->delete('member_badges');
			
			if($badge && count($badge) > 0){
				$user_badge = array();
				foreach($badge as $b){
					$user_badge[] = array(
						'member_id' => $ID,
						'badge_id' => $b,
					);
					
				}
				
				$this->db->insert_batch('member_badges', $user_badge);
			}
			
			
			$this->api->cmd('reload');

		}else{

			$this->api->set_error('invalid_request', 'Invalid Request');

		}

		

		$this->api->out();

	}
	
	
	
}











