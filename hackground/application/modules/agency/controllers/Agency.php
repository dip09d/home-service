<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Agency extends MX_Controller {

   

   private $data;

   

	public function __construct(){

		$this->data['curr_controller'] = $this->router->fetch_class()."/";

		$this->data['curr_method'] = $this->router->fetch_method()."/";

		$this->load->model('agency_model', 'agency');

		$this->data['table'] = 'agency';

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

		$this->data['main_title'] = 'Agency Management';

		$this->data['second_title'] = 'All Agency List';

		$this->data['title'] = 'Agency';

		$breadcrumb = array(

			array(

				'name' => 'Agency',

				'path' => '',

			),

		);

		$this->data['breadcrumb'] = breadcrumb($breadcrumb);

		$this->data['list'] = $this->agency->getList($srch, $limit, $offset);

		$this->data['list_total'] = $this->agency->getList($srch, $limit, $offset, FALSE);

		

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
	public function export_csv(){
		$this->load->helper('csv');
		$srch = get();
		$file_name = "Agency List -".date('d M Y').".csv";
		if(!empty($srch['export']) && $srch['export'] == 1){
			$array = array();
			$array[] = array("ID", "Agency Name", "Email", "Phone", "Status");
			$list = $this->agency->getList($srch, 0, 5000);
			// echo '<pre>'; print_r($list); die;
			if($list){
				foreach($list as $k => $v){
					$status = '';
					if($v['login_status'] == '1'){
						$status = "Active";
					}else if($v['login_status'] == '0'){
						$status = "Not Active";
					}else{
						$status = "Deleted";
					}
					
					$array[] = array($v['agency_id'], $v['agency_name'], $v['agency_email'], $v['agency_phone'], $status);
				}
			}
			echo array_to_csv($array, $file_name);
		}
	}

	

	public function load_ajax_page(){

		$page = get('page');

		$this->data['page'] = $page;

		if($page == 'add'){

			$this->data['title'] = 'Add Agency';

			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');

		}else if($page == 'edit'){

			$id = get('id');

			$this->data['ID']= $id;

			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');

			$this->data['detail'] = $this->agency->getDetail($id);
			$this->data['title'] = 'Edit Agency';
			
		}else if($page == 'user_badge'){
			$this->load->model('badge/badge_model');
			$id = get('id');

			$this->data['ID']= $id;

			$this->data['form_action'] = base_url($this->data['curr_controller'].'save_user_badge');

			$this->data['detail'] = $this->agency->getDetail($id);
			$this->data['badges'] = $this->badge_model->getAllBadges();
			$this->data['user_badge'] = $this->agency->getUserBadge($id);
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

				$insert = $this->agency->addRecord($post);

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

			$this->form_validation->set_rules('agency_name', 'name', 'required|trim|max_length[100]');

			$this->form_validation->set_rules('agency_email', 'email', 'trim|max_length[100]|valid_email');
			$this->form_validation->set_rules(
				'agency_phone',
				'Phone Number',
				'required|trim|regex_match[/^[6-9]\d{9}$/]'
			,['regex_match'=>'Please enter a valid 10-digit Indian mobile number']);

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
				
				unset($post['new_pass_again']);
				unset($post['new_pass']);
				$update = $this->agency->updateRecord($post, $ID);
				
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
				$upd['data'] = array('login_status' => $sts);
				$upd['where'] = array('agency_id' => $ID);
				$upd['table'] = 'agency';
				update($upd);

				if($sts==0){
					$agency_id=$ID;
					$RECEIVER_EMAIL=getField('agency_email','agency','agency_id',$agency_id);
					$data_parse=array(
					'MEMBER_NAME'=>getField('agency_name','agency','agency_id',$agency_id),
					'CUSTOMER_SUPPORT_URL'=>SITE_URL.'cms/support',
					);
					$template='agency-blocked-by-admin';
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

			$this->agency->deleteRecord($id);

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
	
	public function view_edit($module='', $agency_id=''){
		
		$this->data['detail'] = $this->agency->getAllDetail($agency_id);
		$this->data['main_title'] = 'Agency Detail ';
		$this->data['second_title'] =  $this->data['detail']['agency_name'] ;
		
		
		$this->data['module'] = $module;
		$this->data['agency_id'] = $agency_id;
		$this->data['agency_id'] = $this->data['detail']['agency_id'];
		if($module == 'basic_info'){
			$this->_agency_basic_info($agency_id);
		}else if($module == 'location'){
			$this->_agency_location($agency_id);
		}else if($module == 'service'){
			$this->_agency_service($agency_id);
		}else if($module == 'kyc'){
			$this->_agency_kyc($agency_id);
		}else if($module == 'bank'){
			$this->_agency_bank($agency_id);
		}else if($module == 'resume'){
			$this->_agency_resume($agency_id);
		}else if($module == 'industry'){
			$this->_agency_industry($agency_id);
		
		}else if($module == 'profile_detail'){
			$this->_agency_profile_detail($agency_id);
		}else if($module == 'skills'){
			$this->_agency_skills($agency_id);
		}else if($module == 'language'){
			$this->_agency_language($agency_id);
		}else if($module == 'employment'){
			$this->_agency_employment($agency_id);
		}else if($module == 'education'){
			$this->_agency_education($agency_id);
		}else if($module == 'portfolio'){
			$this->_agency_portfolio($agency_id);
		}else if($module == 'organization_location'){
			$this->_organization_location($agency_id);
		}else if($module == 'organization_details'){
			$this->_organization_details($agency_id);
		}else{
			show_404();
			return;
		}
		$this->layout->view('agency-detail', $this->data);
       
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
	private function _agency_skills($agency_id=''){
		$this->data['title'] = 'Skills';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->load->model('skills/skill_model');
		$this->data['page'] = 'agency-skills';
		$this->data['all_skills']= $this->skill_model->getAllSkill();
	}
	
	private function _agency_language($agency_id=''){
		$this->data['title'] = 'Language';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-language';
	}
	
	private function _agency_employment($agency_id=''){
		$this->data['title'] = 'Employment';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-employment';
	}
	
	private function _agency_portfolio($agency_id=''){
		$this->data['title'] = 'Portfolio';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-portfolio';
	}
	
	private function _agency_education($agency_id=''){
		$this->data['title'] = 'Education';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-education';
	}
	
	
	private function _agency_basic_info($agency_id=''){
		$this->data['title'] = 'Basic Info';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-basic-info';
	}
	
	private function _agency_profile_detail($agency_id=''){
		$this->data['title'] = 'Detail';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-profile-detail';
	}
	
	
	private function _agency_professional_info($agency_id=''){
		$this->data['title'] = 'Professional Info';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['options']['career_level'] = $this->agency->getOption('career_level');
		$this->data['options']['current_position'] = $this->agency->getOption('current_position');
		$this->data['options']['salary_expectation'] = $this->agency->getOption('salary_expectation');
		$this->data['options']['commitment'] = $this->agency->getOption('commitment');
		$this->data['options']['notice_period'] = $this->agency->getOption('notice_period');
		$this->data['options']['visa_status'] = $this->agency->getOption('visa_status');
		//get_print($this->data, false);
		$this->data['page'] = 'agency-professional-info';
	}
	
	private function _agency_resume($agency_id=''){
		$this->data['title'] = 'Resume';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['options']['academy'] = $this->agency->getOption('academy');
		$this->data['page'] = 'agency-resume';
	}
	
	private function _agency_industry($agency_id=''){
		$this->data['title'] = 'Industry';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		/* $this->data['options']['industry'] = $this->agency->getOption('experience_area'); */
		$this->data['options']['industry'] = $this->agency->getAllIndustry();
		$this->data['experience'] = array(
			'0' => '0-1 Years',
			'1' => '1-2 Years',
			'2' => '2-5 Years',
			'5' => '5-10 Years',
			'10' => '10-15 Years',
			'15' => '15+ Years',
		);
		$this->data['page'] = 'agency-industry';
	}
	
	private function _agency_location($agency_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-location';
	}
	private function _agency_service($agency_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		$this->load->model('sub_category/sub_category_model');
		$this->data['all_service']= $this->sub_category_model->getData();
		/* Attributes */
		$this->data['page'] = 'agency-service';
	}
	private function _agency_kyc($agency_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-kyc';
	}
	private function _agency_bank($agency_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'agency-bank';
	}
	private function _organization_location($agency_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'organization-location';
	}
	private function _organization_details($agency_id=''){
		$this->data['title'] = 'Detail';
		$breadcrumb = array(
			array(
				'name' => 'Agency',
				'path' => base_url('agency/list_record'),
			),
			array(
				'name' => $this->data['detail']['agency_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('agency/edit_agency_info');
		
		/* Attributes */
		$this->data['page'] = 'organization-details';
	}
	public function edit_agency_info(){
		if(post() && $this->input->is_ajax_request()){
			$page = post('page');
			
			if($page == 'agency-basic-info'){
				$this->load->library('form_validation');
			 	
				$this->form_validation->set_rules('agency[agency_name]', 'name', 'required|trim');
				$this->form_validation->set_rules('agency[agency_member_name]', 'name', 'required|trim');
				
				if($this->form_validation->run()){
					$agency_id = post('ID');
					$post = post();
					$update = $this->agency->saveAgencyInfo($post, $agency_id);
					
					
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'agency-location'){
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('agency_address[agency_address]', 'address', 'required');
				//$this->form_validation->set_rules('agency_address[agency_address_2]', 'address line 2', 'required');
				//$this->form_validation->set_rules('agency_address[city_id]', 'city', 'required');
				$this->form_validation->set_rules('agency_address[agency_city]', 'city', 'required');
				$this->form_validation->set_rules('agency_address[agency_state]', 'state', 'required');
				$this->form_validation->set_rules('agency_address[agency_pincode]', 'pincode', 'required');
				
				if($this->form_validation->run()){
					
					$agency_id = post('ID');
					$post = post();
					$update = $this->agency->saveAgencyInfo($post, $agency_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'agency-service'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('skills', 'overview', 'required');
				
				if($this->form_validation->run()){
					$agency_id = post('ID');
					$all_skill=post('skills');
					delete_record('agency_service',array('agency_id'=>$agency_id));
					if($all_skill){
						$sk=explode(',',$all_skill);
						foreach($sk as $ord=>$skill_id){
							insert_record('agency_service',array('agency_id'=>$agency_id,'category_subchild_id'=>$skill_id,'category_subchild_order'=>$ord),TRUE);
						}
					}
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}	
			}else if($page == 'agency-kyc'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'number', 'required|trim');
				
					
				if($this->form_validation->run()){
					$agency_id = post('ID');
					$dataid = post('kyc_id');
					
						$agencyDatacount=getData(array(
							'select'=>'m_p.kyc_id',
							'table'=>'agency_kyc as m_p',
							'where'=>array('m_p.agency_id'=>$agency_id,'m_p.kyc_id'=>$dataid),
							'single_row'=>true,
						));
						$data_ins=array(
							'kyc_title'=>post('title'),
							'kyc_type'=>post('kyc_type'),
						
							'kyc_complete_date'=>date('Y-m-d H:i:s'),
							'kyc_status'=>1,
							'kyc_image_front'=>NULL,
							'kyc_image_back'=>NULL,
						);
						
						if(post('kyc_image_front')){
							$data_ins['kyc_image_front']=json_encode(array('name'=>post('kyc_image_front'),'file'=>post('kyc_image_front')));
						}elseif(post('pre_kyc_image_front')){
							$data_ins['kyc_image_front']=post('pre_kyc_image_front');
						}
						if(post('kyc_image_back')){
							$data_ins['kyc_image_back']=json_encode(array('name'=>post('kyc_image_back'),'file'=>post('kyc_image_back')));
						}elseif(post('pre_kyc_image_back')){
							$data_ins['kyc_image_back']=post('pre_kyc_image_back');
						}
						if($agencyDatacount){
							$up=updateTable('agency_kyc',$data_ins,array('agency_id'=>$agency_id,'kyc_id'=>$agencyDatacount->kyc_id));
						}else{
							$data_ins['agency_id']=$agency_id;
							$up=insert_record('agency_kyc',$data_ins,TRUE);
						}
						
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'agency-bank'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('name', 'Account Holder Name', 'required|trim');
				$this->form_validation->set_rules('bank_name', 'Bank Name', 'required|trim');
				$this->form_validation->set_rules('account_number', 'Account Number', 'required|trim');
				$this->form_validation->set_rules('ifsc', 'IFSC Code', 'required|trim');
				$this->form_validation->set_rules('upi_id', 'UPI ID', 'trim');
				
					
				if($this->form_validation->run()){
					$agency_id = post('ID');
					$dataid = post('bank_id');
					
						$agencyDatacount=getData(array(
							'select'=>'m_p.bank_id',
							'table'=>'agency_bank as m_p',
							'where'=>array('m_p.agency_id'=>$agency_id,'m_p.bank_id'=>$dataid),
							'single_row'=>true,
						));
						$data_ins=array(
							'account_number'=>post('account_number'),
							'name'=>post('name'),
							'bank_name'=>post('bank_name'),
							'ifsc'=>post('ifsc'),
							'upi_id'=>post('upi_id'),
							'bank_complete_date'=>date('Y-m-d H:i:s'),
							'bank_status'=>1,
							'bank_image_front'=>NULL,
						);
						
						if(post('bank_image_front')){
							$data_ins['bank_image_front']=json_encode(array('name'=>post('bank_image_front'),'file'=>post('bank_image_front')));
						}elseif(post('pre_bank_image_front')){
							$data_ins['bank_image_front']=post('pre_bank_image_front');
						}
						
						if($agencyDatacount){
							$up=updateTable('agency_bank',$data_ins,array('agency_id'=>$agency_id,'bank_id'=>$agencyDatacount->bank_id));
						}else{
							$data_ins['agency_id']=$agency_id;
							$up=insert_record('agency_bank',$data_ins,TRUE);
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
			$upload_dir = LC_PATH.'agency-logo/';
			if($this->input->get('type') && $this->input->get('type')=='kyc'){
				$upload_dir = LC_PATH.'agency-kyc/';
			}elseif($this->input->get('type') && $this->input->get('type')=='bank'){
				$upload_dir = LC_PATH.'agency-bank/';
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
				if($this->input->get('type') && $this->input->get('type')=='kyc'){
					$this->api->data('file_url', UPLOAD_HTTP_PATH.'agency-kyc/'.$this->upload->data('file_name'));
				}elseif($this->input->get('type') && $this->input->get('type')=='bank'){
					$this->api->data('file_url', UPLOAD_HTTP_PATH.'agency-bank/'.$this->upload->data('file_name'));
				}else{
					$this->api->data('file_url', UPLOAD_HTTP_PATH.'agency-logo/'.$this->upload->data('file_name'));
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
		$this->data['action'] = base_url('agency/edit_agency_info');
		if($page == 'agency-kyc'){
		
			
			$this->data['kyc_id'] = get('kyc_id');
			$this->data['ID'] = get('ID');
			if($this->data['kyc_id']){
				$this->data['detail'] = get_row(array(
					'select' => '*',
					'from' => 'agency_kyc',
					'where' => array(
						'kyc_id' => $this->data['kyc_id']
					),
				));
			}else{
				$this->data['detail'] = array();
			}
			
		}elseif($page == 'agency-bank'){
		
			
			$this->data['bank_id'] = get('bank_id');
			$this->data['ID'] = get('ID');
			if($this->data['bank_id']){
				$this->data['detail'] = get_row(array(
					'select' => '*',
					'from' => 'agency_bank',
					'where' => array(
						'bank_id' => $this->data['bank_id']
					),
				));
			}else{
				$this->data['detail'] = array();
			}
			
		}
		
		$this->load->view($modal_page, $this->data);
	}
	
	public function delete_data(){
		$data=array();
		$formtype=post('formtype');
		$dataid=post('Okey');
		$agency_id=post('Mkey');
		$up='';
		if($formtype=='kyc'){
			$agencykyc=getData(array(
				'select'=>'m_p.kyc_id',
				'table'=>'agency_kyc as m_p',
				'where'=>array('m_p.agency_id'=>$agency_id,'m_p.kyc_id'=>$dataid),
				'single_row'=>true,
				));
			if($agencykyc){
				$up=updateTable('agency_kyc',array('kyc_status'=>0),array('kyc_id'=>$agencykyc->kyc_id));
			}
		}elseif($formtype=='bank'){
			$agencybank=getData(array(
				'select'=>'m_p.bank_id',
				'table'=>'agency_bank as m_p',
				'where'=>array('m_p.agency_id'=>$agency_id,'m_p.bank_id'=>$dataid),
				'single_row'=>true,
				));
			if($agencybank){
				$up=updateTable('agency_bank',array('bank_status'=>0),array('bank_id'=>$agencybank->bank_id));
			}
		}
		$this->api->cmd('reload');
		$this->api->out();
		
	}
	
	public function create_account(){

		$this->data['main_title'] = 'Agency Management';

		$this->data['second_title'] = 'Create Account';

		$this->data['title'] = 'Create Account';

		$breadcrumb = array(

			array(

				'name' => 'Agency',

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
			$this->db->where('agency_id', $ID)->delete('agency_badges');
			
			if($badge && count($badge) > 0){
				$user_badge = array();
				foreach($badge as $b){
					$user_badge[] = array(
						'agency_id' => $ID,
						'badge_id' => $b,
					);
					
				}
				
				$this->db->insert_batch('agency_badges', $user_badge);
			}
			
			
			$this->api->cmd('reload');

		}else{

			$this->api->set_error('invalid_request', 'Invalid Request');

		}

		

		$this->api->out();

	}
	
	
	
}











