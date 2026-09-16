<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Worker extends MX_Controller {

   

   private $data;

   

	public function __construct(){

		$this->data['curr_controller'] = $this->router->fetch_class()."/";

		$this->data['curr_method'] = $this->router->fetch_method()."/";

		$this->load->model('worker_model', 'worker');

		$this->data['table'] = 'worker';

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

		$this->data['main_title'] = 'Worker Management';

		$this->data['second_title'] = 'All Worker List';

		$this->data['title'] = 'Worker';

		$breadcrumb = array(

			array(

				'name' => 'Worker',

				'path' => '',

			),

		);

		$this->data['breadcrumb'] = breadcrumb($breadcrumb);

		$this->data['list'] = $this->worker->getList($srch, $limit, $offset);
		// echo $this->db->last_query(); die;
		// echo '<pre>'; print_r($this->data['list']); die;
		$this->data['list_total'] = $this->worker->getList($srch, $limit, $offset, FALSE);

		

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
		$this->data['sub_categories'] = $this->worker->getData();
		$this->layout->view('list', $this->data);

       

	}
	public function export_csv(){
		$this->load->helper('csv');
		$srch = get();
		$file_name = "Worker-List-".date('d M Y').".csv";
		if(!empty($srch['export']) && $srch['export'] == 1){
			$array = array();
			$array[] = array("ID", "Worker Name", "Email", "Phone", "Status",'Services','Invoice Status','Address','House/Flat No.','Street/Area','City','State','Postal Code','Landmark');
			$list = $this->worker->getList($srch, 0, 5000);
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
					
					
					$array[] = array($v['worker_id'], $v['worker_name'], $v['worker_email'], $v['worker_phone'], $status,$v['sub_cat_names'],$v['worker_payment_status'],$v['worker_address'],$v['worker_flat'],$v['worker_street'],$v['city'],$v['state_name'],$v['worker_pincode'],$v['worker_landmark']);
				}
			}
			echo array_to_csv($array, $file_name);
		}
	}
	

	public function load_ajax_page(){

		$page = get('page');

		$this->data['page'] = $page;
		if($page == 'generateicard'){
			$id = get('id');
			$this->data['title'] = 'Generate i-card';
			$this->data['ID']= $id;
			$this->data['detail'] = $this->worker->getDetail($id);
			$this->data['detail']['worker_logo']=getFieldData('logo', 'worker_logo', '', '', array('worker_id' => $id, 'status' =>1));
			$addres = $this->db->where('worker_id', $id)->get('worker_address')->row_array();
			$worker_kyc_list = get_results(array(
				'select' => '*',
				'from'   => 'worker_kyc',
				'where'  => array(
					'worker_id'  => $id,
					'kyc_type'   => 'A',
					'kyc_status' => 1,
				),
				'order_by' => array('kyc_id', 'DESC'),
				'limit'    => 0,  // OFFSET
				'offset'   => 1   // LIMIT
			));

			$worker_kyc = !empty($worker_kyc_list) ? $worker_kyc_list[0] : [];
			// print_R($worker_kyc); die;
			$this->data['detail']['employee_code']='';
			if($worker_kyc){
				$last4 = substr((string)$worker_kyc['kyc_title'], -4);
				$this->data['detail']['employee_code'] = $last4.'0000'.$id;
			}
			$address_data=[
				$addres['worker_flat'],
				$addres['worker_street'],
				$addres['worker_address'],
				$addres['worker_city'],
				$addres['worker_pincode']
			];
			if($addres['worker_state']){
				$address_data[]=getFieldData('state_name', 'state_names', '', '', array('state_id' => $addres['worker_state'], 'state_lang' => admin_default_lang()));
			}
			$address_data=array_filter($address_data);
			$this->data['detail']['address']=implode(',',$address_data);
			
			$this->data['form_action'] = base_url($this->data['curr_controller'].'generateicard');
		}elseif($page == 'add'){

			$this->data['title'] = 'Add Worker';

			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');

		}else if($page == 'edit'){

			$id = get('id');

			$this->data['ID']= $id;

			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');

			$this->data['detail'] = $this->worker->getDetail($id);
			$this->data['title'] = 'Edit Worker';
			
		}else if($page == 'user_badge'){
			$this->load->model('badge/badge_model');
			$id = get('id');

			$this->data['ID']= $id;

			$this->data['form_action'] = base_url($this->data['curr_controller'].'save_user_badge');

			$this->data['detail'] = $this->worker->getDetail($id);
			$this->data['badges'] = $this->badge_model->getAllBadges();
			$this->data['user_badge'] = $this->worker->getUserBadge($id);
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

				$insert = $this->worker->addRecord($post);

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

			$this->form_validation->set_rules('worker_name', 'name', 'required|trim|max_length[100]');

			$this->form_validation->set_rules('worker_email', 'email', 'trim|max_length[100]|valid_email');
			$this->form_validation->set_rules(
				'worker_phone',
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
				$update = $this->worker->updateRecord($post, $ID);
				
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
	public function generateicard(){

		if(post() && $this->input->is_ajax_request()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('employee_id', 'employee id', 'required|trim');
			$this->form_validation->set_rules('date_of_joining', 'date of joining', 'required|trim');
			// $this->form_validation->set_rules('designation', 'designation', 'required|trim');
			$this->form_validation->set_rules('worker_name', 'name', 'required|trim|max_length[100]');
			//$this->form_validation->set_rules('worker_email', 'email', 'trim|max_length[100]|valid_email');
			/* $this->form_validation->set_rules(
				'worker_phone',
				'Phone Number',
				'required|trim|regex_match[/^[6-9]\d{9}$/]'
			,['regex_match'=>'Please enter a valid 10-digit Indian mobile number']);

			$this->form_validation->set_rules('worker_address', 'address', 'required|trim'); */
			
			$this->form_validation->set_rules('ID', 'id', 'required');

			if($this->form_validation->run()){

				$post = post();

				$ID = post('ID');
				$this->db->where('worker_id',$ID)->update('worker_icard',['status'=>DELETE_STATUS]);
				$ins=[
					'worker_id'=>$ID,
					'employee_id'=>post('employee_id'),
					'date_of_joining'=>post('date_of_joining'),
					'designation'=>post('designation'),
					'worker_name'=>post('worker_name'),
					'worker_email'=>post('worker_email'),
					'worker_phone'=>post('worker_phone'),
					'worker_address'=>post('worker_address'),
					'icard_logo'=>NULL,
					'status'=>1,
					'reg_date'=>date('Y-m-d H:i:s'),
				];
				if(post('previous_icard_logo')){
					$ins['icard_logo']=post('previous_icard_logo');
					if(!file_exists(LC_PATH.'worker-icard/'.post('previous_icard_logo'))){
						copy(LC_PATH.'worker-logo/'.post('previous_icard_logo'),LC_PATH.'worker-icard/'.post('previous_icard_logo'));
					}
				}elseif(post('icard_logo')){
					$ins['icard_logo']=post('icard_logo');
				}
				$this->db->insert('worker_icard', $ins);

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
				$upd['where'] = array('worker_id' => $ID);
				$upd['table'] = 'worker';
				update($upd);

				if($sts==0){
					$worker_id=$ID;
					$RECEIVER_EMAIL=getField('worker_email','worker','worker_id',$worker_id);
					$data_parse=array(
					'MEMBER_NAME'=>getField('worker_name','worker','worker_id',$worker_id),
					'CUSTOMER_SUPPORT_URL'=>SITE_URL.'cms/support',
					);
					$template='worker-blocked-by-admin';
					SendMail($RECEIVER_EMAIL,$template,$data_parse);
				}elseif($sts==1){
					$worker_id=$ID;
					$mobile=getField('worker_phone','worker','worker_id',$worker_id);
					if($mobile){
						$smstext='Congratulations! Your profile is now active. Login to your account and begin accepting bookings to earn. Wishing you success! -SNAPHIVE';
						$sendSms=sendSMS($mobile,'1707177390433850891',$smstext);
						/* if($sendSms && $sendSms['status']==1){
							$response['status'] = 1;
							$response['otp'] = $otp;
						}else{
							$response['otp'] = $otp;
							$response['status'] = 0;
							$response['errors'] = 'SMS sending failed';
						} */
					}
					
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

			$this->worker->deleteRecord($id);

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
	
	public function view_edit($module='', $worker_id=''){
		
		$this->data['detail'] = $this->worker->getAllDetail($worker_id);
		

		$this->data['detail']['icard']=$this->db->select('icard_id')->from('worker_icard')->where(['worker_id'=>$worker_id,'status'=>1])->get()->row_array();
		$this->data['main_title'] = 'Worker Detail ';
		$this->data['second_title'] =  $this->data['detail']['worker_name'] ;
		
		
		$this->data['module'] = $module;
		$this->data['worker_id'] = $worker_id;
		$this->data['agency_id'] = $this->data['detail']['agency_id'];
		if($module == 'basic_info'){
			$this->_worker_basic_info($worker_id);
		}else if($module == 'location'){
			$this->_worker_location($worker_id);
		}else if($module == 'service'){
			$this->_worker_service($worker_id);
		}else if($module == 'kyc'){
			$this->_worker_kyc($worker_id);
		}else if($module == 'bank'){
			$this->_worker_bank($worker_id);
		}else if($module == 'invoice'){
			$this->_worker_invoice($worker_id);
		}else if($module == 'invoice_add'){
			$this->_worker_invoice_add($worker_id);
		}else if($module == 'resume'){
			$this->_worker_resume($worker_id);
		}else if($module == 'industry'){
			$this->_worker_industry($worker_id);
		
		}else if($module == 'profile_detail'){
			$this->_worker_profile_detail($worker_id);
		}else if($module == 'skills'){
			$this->_worker_skills($worker_id);
		}else if($module == 'language'){
			$this->_worker_language($worker_id);
		}else if($module == 'employment'){
			$this->_worker_employment($worker_id);
		}else if($module == 'education'){
			$this->_worker_education($worker_id);
		}else if($module == 'portfolio'){
			$this->_worker_portfolio($worker_id);
		}else if($module == 'organization_location'){
			$this->_organization_location($worker_id);
		}else if($module == 'organization_details'){
			$this->_organization_details($worker_id);
		}else{
			show_404();
			return;
		}
		$this->layout->view('worker-detail', $this->data);
       
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
	private function _worker_skills($worker_id=''){
		$this->data['title'] = 'Skills';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->load->model('skills/skill_model');
		$this->data['page'] = 'worker-skills';
		$this->data['all_skills']= $this->skill_model->getAllSkill();
	}
	
	private function _worker_language($worker_id=''){
		$this->data['title'] = 'Language';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-language';
	}
	
	private function _worker_employment($worker_id=''){
		$this->data['title'] = 'Employment';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-employment';
	}
	
	private function _worker_portfolio($worker_id=''){
		$this->data['title'] = 'Portfolio';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-portfolio';
	}
	
	private function _worker_education($worker_id=''){
		$this->data['title'] = 'Education';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-education';
	}
	
	
	private function _worker_basic_info($worker_id=''){
		$this->data['title'] = 'Basic Info';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-basic-info';
	}
	
	private function _worker_profile_detail($worker_id=''){
		$this->data['title'] = 'Detail';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-profile-detail';
	}
	
	
	private function _worker_professional_info($worker_id=''){
		$this->data['title'] = 'Professional Info';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['options']['career_level'] = $this->worker->getOption('career_level');
		$this->data['options']['current_position'] = $this->worker->getOption('current_position');
		$this->data['options']['salary_expectation'] = $this->worker->getOption('salary_expectation');
		$this->data['options']['commitment'] = $this->worker->getOption('commitment');
		$this->data['options']['notice_period'] = $this->worker->getOption('notice_period');
		$this->data['options']['visa_status'] = $this->worker->getOption('visa_status');
		//get_print($this->data, false);
		$this->data['page'] = 'worker-professional-info';
	}
	
	private function _worker_resume($worker_id=''){
		$this->data['title'] = 'Resume';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['options']['academy'] = $this->worker->getOption('academy');
		$this->data['page'] = 'worker-resume';
	}
	
	private function _worker_industry($worker_id=''){
		$this->data['title'] = 'Industry';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		/* $this->data['options']['industry'] = $this->worker->getOption('experience_area'); */
		$this->data['options']['industry'] = $this->worker->getAllIndustry();
		$this->data['experience'] = array(
			'0' => '0-1 Years',
			'1' => '1-2 Years',
			'2' => '2-5 Years',
			'5' => '5-10 Years',
			'10' => '10-15 Years',
			'15' => '15+ Years',
		);
		$this->data['page'] = 'worker-industry';
	}
	
	private function _worker_location($worker_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-location';
	}
	private function _worker_service($worker_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		$this->load->model('sub_category/sub_category_model');
		$this->data['all_service']= $this->sub_category_model->getData();
		/* Attributes */
		$this->data['page'] = 'worker-service';
	}
	private function _worker_kyc($worker_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-kyc';
	}
	private function _worker_bank($worker_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'worker-bank';
	}
	private function _worker_invoice($worker_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		$this->load->model('invoice/invoice_model', 'invoice');
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$srch['worker_id']=$worker_id;
		$srch['invoice_for']='worker';
		$this->data['list'] = $this->invoice->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->invoice->getList($srch, $limit, $offset, FALSE);
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'view_edit/invoice/'.$worker_id);
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();



		
		/* Attributes */
		$this->data['page'] = 'worker-invoice';
	}
	private function _worker_invoice_add($worker_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		$this->data['invoice_type']=$this->db->select('name_tkey,description_tkey')->from('invoice_type')->get()->result_array();
		$this->data['invoice_pre_item']=[
			['name'=>'Registration Fees','qty'=>'1','price'=>'300'],
			['name'=>'Dress Fees','qty'=>'1','price'=>'500'],
			['name'=>'ID Card  Fees','qty'=>'1','price'=>'200'],
			['name'=>'Others Fess','qty'=>'1','price'=>'100']
		];
		/* Attributes */
		$this->data['page'] = 'worker-invoice-add';
	}
	private function _organization_location($worker_id=''){
		$this->data['title'] = 'Location';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'organization-location';
	}
	private function _organization_details($worker_id=''){
		$this->data['title'] = 'Detail';
		$breadcrumb = array(
			array(
				'name' => 'Worker',
				'path' => base_url('worker/list_record'),
			),
			array(
				'name' => $this->data['detail']['worker_name'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('worker/edit_worker_info');
		
		/* Attributes */
		$this->data['page'] = 'organization-details';
	}
	public function edit_worker_info(){
		if(post() && $this->input->is_ajax_request()){
			$page = post('page');
			
			if($page == 'worker-basic-info'){
				$this->load->library('form_validation');
			 	$this->form_validation->set_rules('worker[worker_gender]', 'gender', 'required|trim');
			 	$this->form_validation->set_rules('worker[worker_religion]', 'gender', 'required|trim');
				//$this->form_validation->set_rules('worker_basic[worker_nationality]', 'nationality', 'required|trim');
				$this->form_validation->set_rules('worker[worker_dob]', 'dob', 'required|trim');
				$this->form_validation->set_rules('worker[worker_name]', 'name', 'required|trim');
				
				if($this->form_validation->run()){
					$worker_id = post('ID');
					$post = post();
					$update = $this->worker->saveWorkerInfo($post, $worker_id);
					
					$worker_logo = post('worker_logo');
					if(!empty($worker_logo)){
						$this->worker->updateWorkerLogo($worker_logo, $worker_id);
					}
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'worker-professional-info'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('worker_professional[worker_career_level]', 'career level', 'required');
				$this->form_validation->set_rules('worker_address[worker_current_location]', 'current location', 'required');
				$this->form_validation->set_rules('worker_professional[worker_current_position]', 'current position', 'required');
				$this->form_validation->set_rules('worker_professional[worker_current_company]', 'current company', 'required');
				$this->form_validation->set_rules('worker_professional[worker_salary_expectation]', 'salary expectation', 'required');
				$this->form_validation->set_rules('worker_professional[worker_commitment]', 'commitment', 'required');
				$this->form_validation->set_rules('worker_professional[worker_notice_period]', 'notice period', 'required');
				$this->form_validation->set_rules('worker_professional[worker_visa_status]', 'visa status', 'required');
				
				if($this->form_validation->run()){
					$worker_id = post('ID');
					$post = post();
					$update = $this->worker->saveWorkerInfo($post, $worker_id);
					
					$this->api->cmd('reload');
				
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'worker-location'){
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('worker_address[worker_address]', 'address', 'required');
				//$this->form_validation->set_rules('worker_address[worker_address_2]', 'address line 2', 'required');
				//$this->form_validation->set_rules('worker_address[city_id]', 'city', 'required');
				$this->form_validation->set_rules('worker_address[worker_city]', 'city', 'required');
				$this->form_validation->set_rules('worker_address[worker_state]', 'state', 'required');
				$this->form_validation->set_rules('worker_address[worker_pincode]', 'pincode', 'required');
				
				if($this->form_validation->run()){
					
					$worker_id = post('ID');
					$post = post();
					$update = $this->worker->saveWorkerInfo($post, $worker_id);
					
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
					$update = $this->worker->saveOrganizationInfo($post, $organization_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			
				
			}else if($page == 'worker-industry'){
				
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
					$worker_id = post('ID');
					$post = post();
					$update = $this->worker->saveWorkerIndustry($post, $worker_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'worker-resume'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('worker_basic[worker_cv_summary]', 'cv summary', 'required');
				
				if($this->form_validation->run()){
					$worker_id = post('ID');
					$post = post();
					$update = $this->worker->saveWorkerInfo($post, $worker_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'worker-profile-detail'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('worker_basic[worker_overview]', 'overview', 'required');
				$this->form_validation->set_rules('worker_basic[worker_heading]', 'heading', 'required');
				$this->form_validation->set_rules('worker_basic[worker_hourly_rate]', 'hourly rate', 'required');
				if(post('is_available') == '0'){
					$this->form_validation->set_rules('worker_basic[not_available_until]', 'availablity', 'required');
				}else{
					$this->form_validation->set_rules('worker_basic[available_per_week]', 'availablity', 'required');
				}
				
				if($this->form_validation->run()){
					$worker_id = post('ID');
					$post = post();
					if($post['is_available'] == '1'){
						$post['worker_basic']['not_available_until'] = NULL;
					}else{
						$post['worker_basic']['available_per_week'] = NULL;
					}
					$update = $this->worker->saveWorkerInfo($post, $worker_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
			}else if($page == 'worker-service'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('skills', 'overview', 'required');
				
				if($this->form_validation->run()){
					$worker_id = post('ID');
					$all_skill=post('skills');
					delete_record('worker_service',array('worker_id'=>$worker_id));
					if($all_skill){
						$sk=explode(',',$all_skill);
						foreach($sk as $ord=>$skill_id){
							insert_record('worker_service',array('worker_id'=>$worker_id,'category_subchild_id'=>$skill_id,'category_subchild_order'=>$ord),TRUE);
						}
					}
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}	
			}else if($page == 'worker-skills'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('skills', 'overview', 'required');
				
				if($this->form_validation->run()){
					$worker_id = post('ID');
					$all_skill=post('skills');
					delete_record('worker_skills',array('worker_id'=>$worker_id));
					if($all_skill){
						$sk=explode(',',$all_skill);
						foreach($sk as $ord=>$skill_id){
							insert_record('worker_skills',array('worker_id'=>$worker_id,'skill_id'=>$skill_id,'worker_skills_order'=>$ord),TRUE);
						}
					}
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'worker-kyc'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'number', 'required|trim');
				
					
				if($this->form_validation->run()){
					$worker_id = post('ID');
					$dataid = post('kyc_id');
					
						$workerDatacount=getData(array(
							'select'=>'m_p.kyc_id',
							'table'=>'worker_kyc as m_p',
							'where'=>array('m_p.worker_id'=>$worker_id,'m_p.kyc_id'=>$dataid),
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
						if($workerDatacount){
							$up=updateTable('worker_kyc',$data_ins,array('worker_id'=>$worker_id,'kyc_id'=>$workerDatacount->kyc_id));
						}else{
							$data_ins['worker_id']=$worker_id;
							$up=insert_record('worker_kyc',$data_ins,TRUE);
						}
						
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'worker-bank'){
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('name', 'Account Holder Name', 'required|trim');
				$this->form_validation->set_rules('bank_name', 'Bank Name', 'required|trim');
				$this->form_validation->set_rules('account_number', 'Account Number', 'required|trim');
				$this->form_validation->set_rules('ifsc', 'IFSC Code', 'required|trim');
				$this->form_validation->set_rules('upi_id', 'UPI ID', 'trim');
				
					
				if($this->form_validation->run()){
					$worker_id = post('ID');
					$dataid = post('bank_id');
					
						$workerDatacount=getData(array(
							'select'=>'m_p.bank_id',
							'table'=>'worker_bank as m_p',
							'where'=>array('m_p.worker_id'=>$worker_id,'m_p.bank_id'=>$dataid),
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
						
						if($workerDatacount){
							$up=updateTable('worker_bank',$data_ins,array('worker_id'=>$worker_id,'bank_id'=>$workerDatacount->bank_id));
						}else{
							$data_ins['worker_id']=$worker_id;
							$up=insert_record('worker_bank',$data_ins,TRUE);
						}
						
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
			}else if($page == 'worker-invoice-add'){
				$worker_id = post('ID');
				$this->load->library('form_validation');

				$this->form_validation->set_rules('invoice_type', 'invoice type', 'required|trim');
				$item=$this->input->post('itemname');
				$total=0;
				$invoide_row_item=[];
				if($item){
					foreach($item as $k=>$row){
						$this->form_validation->set_rules('itemname['.$k.']', 'name', 'required|trim');
						$this->form_validation->set_rules('qty['.$k.']', 'quantity', 'required|trim|greater_than[0]');
						$this->form_validation->set_rules('price['.$k.']', 'price', 'required|trim');
						$row_item=[
							'name'=>$this->input->post('itemname['.$k.']'),
							'qty'=>$this->input->post('qty['.$k.']'),
							'price'=>$this->input->post('price['.$k.']')
						];
						$invoide_row_item[]=$row_item;
						$row_total=$row_item['qty']*$row_item['price'];
						$total =$total+$row_total;
					}
				}
			
				if($this->form_validation->run()){
					$invoice_number=generate_invoice_number();
					$invoice_type_id=getFieldData('invoice_type_id','invoice_type','name_tkey',$this->input->post('invoice_type'));
					if($invoice_type_id){
						$round_up_val=round($total,2);
						$recipient_email=getFieldData('worker_email','worker','worker_id',$worker_id);
						$invoice=array(
							'invoice_type_id'=>$invoice_type_id,
							'invoice_number'=>$invoice_number,
							'issuer_member_id'=>0,
							'issuer_organization_id'=>NULL,
							'recipient_member_id'=>$worker_id,
							'recipient_organization_id'=>NULL,
							'invoice_date'=>date('Y-m-d H:i:s'),
							'recipient_email'=>$recipient_email,
							'round_up_amount'=>$round_up_val,
							'invoice_status'=>0,
						);
						$invoice_id=insert_record('invoice',$invoice,TRUE);
						if($invoice_id){
							$issuer_information_arr['I_name']=get_setting('website_name');
							$issuer_information_arr['I_addr']='48 / 93 / 115, KRISHNA NAGAR';
							$issuer_information_arr['I_flat']='';
							$issuer_information_arr['I_street']='';
							$issuer_information_arr['I_landmark']='';
							$issuer_information_arr['I_GST']='19PKJPS5988D1Z0';
							$issuer_information_arr['I_phone']='+91 79033 71185';
							$issuer_information_arr['I_email']='moin.knockonce@gmail.com';
							$issuer_information_arr['I_state']='West Bengal';
							$issuer_information_arr['I_city']='KOLKATA';
							$issuer_information_arr['I_pin']='700104';


							$addressWorker=getData(array(
								'select'=>'m.worker_name,m.worker_phone,m.worker_email,m_a.worker_city,m_a.worker_state,m_a.worker_address,m_a.worker_flat,m_a.worker_pincode,m_a.worker_street,,m_a.worker_landmark,c_n.state_name,',
								'table'=>'worker as m',
								'join'=>array(
									array('table'=>'worker_address as m_a','on'=>'m.worker_id=m_a.worker_id','position'=>'left'),
									array('table'=>'state_names as c_n','on'=>"(m_a.worker_state=c_n.state_id and c_n.state_lang='".get_default_lang()."')",'position'=>'left')
								),
								'where'=>array('m.worker_id'=>$worker_id),
								'single_row'=>true,
							));

							$recipient_information_arr['R_name']=$addressWorker->worker_name;
							$recipient_information_arr['R_addr']=$addressWorker->worker_address;
							$recipient_information_arr['R_flat']=$addressWorker->worker_flat;
							$recipient_information_arr['R_street']=$addressWorker->worker_street;
							$recipient_information_arr['R_landmark']=$addressWorker->worker_landmark;
							$recipient_information_arr['R_city']=$addressWorker->worker_city;
							$recipient_information_arr['R_state']=$addressWorker->state_name;	
							$recipient_information_arr['R_phone']=$addressWorker->worker_phone;
							$recipient_information_arr['R_email']=$addressWorker->worker_email;
							$recipient_information_arr['R_pin']=$addressWorker->worker_pincode;
							if($issuer_information_arr){
								$issuer_information=serialize($issuer_information_arr);
							}else{
								$issuer_information="";
							}
							if($recipient_information_arr){
								$recipient_information=serialize($recipient_information_arr);
							}else{
								$recipient_information="";
							}
							$invoice_reference=array(
								'invoice_id'=>$invoice_id,
								'issuer_information'=>$issuer_information,
								'recipient_information'=>$recipient_information,
							);
							insert_record('invoice_reference',$invoice_reference);
							if($invoide_row_item){
								$invoice_row_unit='pcs';
								foreach($invoide_row_item as $invoiceitem){
									$invoice_row=[
										'invoice_id'=>$invoice_id,
										'invoice_row_text'=>$invoiceitem['name'],
										'invoice_row_amount'=>$invoiceitem['qty'],
										'invoice_row_unit'=>$invoice_row_unit,
										'invoice_row_unit_price'=>$invoiceitem['price']
									];
									insert_record('invoice_row',$invoice_row);
								}
							}

						}else{
							$this->api->set_error('invalid_request', 'failed to create invoice');
						}

					}else{
						$this->api->set_error('invalid_request', 'Invalid invoice type');
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
			$upload_dir = LC_PATH.'worker-logo/';
			if($this->input->get('type') && $this->input->get('type')=='kyc'){
				$upload_dir = LC_PATH.'worker-kyc/';
			}elseif($this->input->get('type') && $this->input->get('type')=='bank'){
				$upload_dir = LC_PATH.'worker-bank/';
			}elseif($this->input->get('type') && $this->input->get('type')=='icard'){
				$upload_dir = LC_PATH.'worker-icard/';
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
					$this->api->data('file_url', UPLOAD_HTTP_PATH.'worker-kyc/'.$this->upload->data('file_name'));
				}elseif($this->input->get('type') && $this->input->get('type')=='bank'){
					$this->api->data('file_url', UPLOAD_HTTP_PATH.'worker-bank/'.$this->upload->data('file_name'));
				}elseif($this->input->get('type') && $this->input->get('type')=='icard'){
					$this->api->data('file_url', UPLOAD_HTTP_PATH.'worker-icard/'.$this->upload->data('file_name'));
				}else{
					$this->api->data('file_url', UPLOAD_HTTP_PATH.'worker-logo/'.$this->upload->data('file_name'));
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
		$this->data['action'] = base_url('worker/edit_worker_info');
		if($page == 'worker-kyc'){
		
			
			$this->data['kyc_id'] = get('kyc_id');
			$this->data['ID'] = get('ID');
			if($this->data['kyc_id']){
				$this->data['detail'] = get_row(array(
					'select' => '*',
					'from' => 'worker_kyc',
					'where' => array(
						'kyc_id' => $this->data['kyc_id']
					),
				));
			}else{
				$this->data['detail'] = array();
			}
			
		}elseif($page == 'worker-bank'){
		
			
			$this->data['bank_id'] = get('bank_id');
			$this->data['ID'] = get('ID');
			if($this->data['bank_id']){
				$this->data['detail'] = get_row(array(
					'select' => '*',
					'from' => 'worker_bank',
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
		$worker_id=post('Mkey');
		$up='';
		if($formtype=='kyc'){
			$workerkyc=getData(array(
				'select'=>'m_p.kyc_id',
				'table'=>'worker_kyc as m_p',
				'where'=>array('m_p.worker_id'=>$worker_id,'m_p.kyc_id'=>$dataid),
				'single_row'=>true,
				));
			if($workerkyc){
				$up=updateTable('worker_kyc',array('kyc_status'=>0),array('kyc_id'=>$workerkyc->kyc_id));
			}
		}elseif($formtype=='bank'){
			$workerbank=getData(array(
				'select'=>'m_p.bank_id',
				'table'=>'worker_bank as m_p',
				'where'=>array('m_p.worker_id'=>$worker_id,'m_p.bank_id'=>$dataid),
				'single_row'=>true,
				));
			if($workerbank){
				$up=updateTable('worker_bank',array('bank_status'=>0),array('bank_id'=>$workerbank->bank_id));
			}
		}
		$this->api->cmd('reload');
		$this->api->out();
		
	}
	
	public function create_account(){

		$this->data['main_title'] = 'Worker Management';

		$this->data['second_title'] = 'Create Account';

		$this->data['title'] = 'Create Account';

		$breadcrumb = array(

			array(

				'name' => 'Worker',

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
			$this->db->where('worker_id', $ID)->delete('worker_badges');
			
			if($badge && count($badge) > 0){
				$user_badge = array();
				foreach($badge as $b){
					$user_badge[] = array(
						'worker_id' => $ID,
						'badge_id' => $b,
					);
					
				}
				
				$this->db->insert_batch('worker_badges', $user_badge);
			}
			
			
			$this->api->cmd('reload');

		}else{

			$this->api->set_error('invalid_request', 'Invalid Request');

		}

		

		$this->api->out();

	}
	
	
	
}











