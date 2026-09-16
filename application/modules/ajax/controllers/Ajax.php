<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends MX_Controller {
	
    public function __construct() {
       
        parent::__construct();
    }
	
	public function get_listing_type(){
		if(get()){
			$this->load->model('listing/listing_model', 'listing');
			$property_type_id = get('property_type_id');
			$result = $this->listing->getListingType($property_type_id);
			echo json_encode($result);
		}
	}
	
	public function get_property_type(){
		if(get()){
			$this->load->model('listing/listing_model', 'listing');
			$place_type_id = get('place_type_id');
			$result = $this->listing->getPropertyType($place_type_id);
			echo json_encode($result);
		}
	}
	
	public function get_state(){
		$country = get('country');
		$states = get_results(array('select' => '*', 'from' => 'states', 'where' => array('country_id' => $country, 'status' => 'Y')), 'json');
		
		echo $states;
	}
	
	public function upload_image(){
		$json = array();
		$this->load->library('upload');
		
		if(!is_dir(TMP_UPLOAD_PATH)){
			mkdir(TMP_UPLOAD_PATH);
		}
		
		$config['upload_path']          = TMP_UPLOAD_PATH;
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['file_ext_tolower']     = TRUE;
		$config['encrypt_name']         = TRUE;
		
		$this->upload->initialize($config);
		
		if ( ! $this->upload->do_upload('file')){
			$json['error'] = $this->upload->display_errors();
			$json['status'] = 0;
		} else {
			$data = $this->upload->data();
			$json['status'] = 1;
			$json['file_name'] = $data['file_name'];
			$json['file_full_path'] = TMP_UPLOAD_HTTP_PATH.$data['file_name'];

         }
		 
		 echo json_encode($json);
	}
	
	public function upload_file(){
		$json = array();
		$this->load->library('upload');
		
		if(!is_dir(TMP_UPLOAD_PATH)){
			mkdir(TMP_UPLOAD_PATH);
		}
		
		$config['upload_path']          = TMP_UPLOAD_PATH;
		$config['allowed_types']        = 'gif|jpg|png|jpeg|doc|pdf';
		$config['file_ext_tolower']     = TRUE;
		$config['encrypt_name']         = TRUE;
		
		$this->upload->initialize($config);
		
		if ( ! $this->upload->do_upload('file')){
			$json['error'] = $this->upload->display_errors();
			$json['status'] = 0;
		} else {
			$data = $this->upload->data();
			$json['status'] = 1;
			$json['file_name'] = $data['file_name'];
			$json['file_full_path'] = TMP_UPLOAD_HTTP_PATH.$data['file_name'];

         }
		 
		 echo json_encode($json);
	}
	
	public function del_file(){
		$json = array();
		if(post() && $this->input->is_ajax_request()){
			$file = post('file');
			@unlink(UPLOAD_PATH.$file);
			$json['status'] = 1;
			echo json_encode($json);
		}
	}
	
	public function get_user_update(){
		$this->load->helper('match');
		$json = array();
		$user_id = get('user_id');
	
		$json['result'] = get_user_file_content($user_id);
		
		echo json_encode($json);
	}
	
	public function get_all_update(){
		$json = array();
		$user_id = get_session('uid');
		$json['unread_msg'] = 0;
		$json['act']['interested'] = 0;
		$json['act']['viewed_profile'] = 0;
		$json['act']['fav'] = 0;
		
		if($user_id > 0){
			$json['unread_msg'] = $this->db->where(array('receiver_id' => $user_id, 'read_status' => 'N'))->count_all_results('messages');
			
			$json['act']['interested'] = 0;
			
			$json['act']['viewed_profile'] = 0;
				
				
			
			$json['act']['fav'] = 0;
		}
		
		echo json_encode($json);
	}
	
	
	/*---------------------- Classified API Start ------------------------------------------*/
	
	public function validate_input(){
		$type = get('type');
		$validate = $this->_validateForm($type);
	}
	
	private function _validateForm($type='', $return=FALSE){
		
		$this->load->library('form_validation');
		
		if($type == 'signup'){
			
			$this->form_validation->set_rules('name', 'name', 'required|alpha_numeric_spaces|max_length[100]');
			$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('password', 'password', 'required');
			$this->form_validation->set_rules('cpassword', 'confirm password', 'required|matches[password]');
			$this->form_validation->set_rules('agree', 'agree', 'required');
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
				
			}
			
		}else if($type == 'login'){
			
			$this->form_validation->set_rules('email', 'email', 'required');
			$this->form_validation->set_rules('password', 'password', 'required');
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
				
			}
			
		}else if($type == 'post_ad'){
			
			$this->form_validation->set_rules('title', 'title', 'required');
			$this->form_validation->set_rules('parent_category_id', 'parent category id', 'required');
			$this->form_validation->set_rules('category_id', 'category id', 'required');
			$this->form_validation->set_rules('description', 'description', 'required');
			
			$this->form_validation->set_rules('country_id', 'country', 'required');
			
			$this->form_validation->set_rules('seller_name', 'seller name', 'required');
			$this->form_validation->set_rules('seller_email', 'seller email', 'required');
			$this->form_validation->set_rules('seller_mobile', 'seller mobile', 'required');
			/* $this->form_validation->set_rules('state_id', 'state', 'required');
			$this->form_validation->set_rules('city_id', 'city', 'required'); */
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
				
			} 
			
		}else if($type == 'save_edit_ad'){
			
			$this->form_validation->set_rules('title', 'title', 'required');
	
			$this->form_validation->set_rules('description', 'description', 'required');
			
			$this->form_validation->set_rules('seller_name', 'seller name', 'required');
			$this->form_validation->set_rules('seller_email', 'seller email', 'required');
			$this->form_validation->set_rules('seller_mobile', 'seller mobile', 'required');
			
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
				
			} 
			
		}else if($type == 'update_profile'){
			
			$this->form_validation->set_rules('name', 'name', 'required|max_length[100]');
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
				
			}
			
		}else if($type == 'contact_seller'){
			
			$this->form_validation->set_rules('name', 'name', 'required|max_length[100]');
			$this->form_validation->set_rules('email', 'email', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'phone', 'required');
			$this->form_validation->set_rules('message', 'message', 'required');
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
			}
			
		}else if($type == 'change_password'){
			
			$this->form_validation->set_rules('old_password', 'old password', 'required');
			$this->form_validation->set_rules('password', 'password', 'required');
			$this->form_validation->set_rules('c_password', 'confirm password', 'required|matches[password]');
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
			}
			
		}else if($type == 'book_rental'){
			
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'phone', 'required');
			$this->form_validation->set_rules('address', 'address', 'required');
			$this->form_validation->set_rules('zip_code', 'zip code', 'required|numeric|max_length[8]');
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
			}
			
		}else if($type == 'apply_job'){
			
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'phone', 'required');
			$this->form_validation->set_rules('user_cv', 'user cv', 'required');
			$this->form_validation->set_rules('job_id', 'job_id', 'required');
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
			}
			
		}else if($type == 'contact_us'){
			
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required|valid_email');
			$this->form_validation->set_rules('subject', 'subject', 'required');
			$this->form_validation->set_rules('message', 'message', 'required');
			
			if($this->form_validation->run()){
				
				$this->api->set_data('form_validation_status', API_SUCCESS);
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
			}
			
		}
		
		
		
		if( $return){
			return $this->api->get_out();
		}else{
			$this->api->out();
		}
		
	}
	
	public function register(){
		if(post()){
			$validate = $this->_validateForm('signup', TRUE);
			
			if($validate['error_length'] == 0){
				
				$this->load->model('home/home_model');
				
				$post = filter_data(post());
				$user_id = $this->home_model->registerUser($post);
				$user_data = array(
					'user_id' => $user_id,
					'name' => $post['name'],
					'profile_pic' => null,
				);
				if($user_id){
					
					/* Setting session */
					
					set_session('uid' , $user_id);
					set_session('user' , $user_data);
					
					$this->api->set_cmd('redirectToDashboard');
					$this->api->out();
				}
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	
	public function login(){
		
		if(is_login_user()){
			$this->api->set_cmd('redirectToDashboard');
			$this->api->out();
		}
		
		if(post()){
			
			$validate = $this->_validateForm('login', TRUE);
			
			if($validate['error_length'] == 0){
				$ref = post('refer');
				$this->load->model('home/home_model');
				
				$post = filter_data(post());
				$login = $this->home_model->check_login($post['email'] , $post['password']);
				
				if($login){
					
					/* Setting session */
					
					set_session('uid' , $login['uid']);
					set_session('user' , $login['user_data']);
					
					if(!empty($ref)){
						$res['redirect'] = $ref;
						$this->api->set_cmd('redirect');
						$this->api->set_cmd_params('url', $ref);
					}else{
						$this->api->set_cmd('redirectToDashboard');
					}
				
					
					$this->api->out(); 
					
				}else{
					
					$this->api->set_error('login','Login Failed : Please enter valid email and password');
					$this->api->out();
					
				}
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	
	public function set_password_ajax(){
		
		if(is_login_user()){
			$this->api->set_cmd('redirectToDashboard');
			$this->api->out();
		}
		$this->load->library('form_validation');
		$this->load->library('auth');
		$this->load->model('home/home_model');
		
		if(post() && $this->input->is_ajax_request()){
			
			
			$this->form_validation->set_rules('password', 'password', 'required');
			$this->form_validation->set_rules('c_password', 'confirm password', 'required|matches[password]');
			$this->form_validation->set_rules('token', 'token', 'required');
			if($this->form_validation->run()){
				$token =  filter_data(post('token'));
				$password = $this->auth->hash_pass(filter_data(post('password')));
				if($this->home_model->isValidToken($token)){
					$this->home_model->setPassword($password, $token);
					$this->home_model->resetToken($token);
				}
				
				$reset_password_success = base_url('success-page?page=set_password');
				$this->api->set_cmd('redirect');
				$this->api->set_cmd_params('url', $reset_password_success);
				$this->api->out();
				
			}else{
				$error = validation_errors_array();
				$this->api->set_error($error);
				$this->api->out();
			}
			echo json_encode($res);
		}
		
	}
	
	public function getCategory(){
		$this->load->model('project/project_model');
		$parent = get('parent_id');
		$data = $this->project_model->getCategory($parent);
		$this->api->set_data('category', $data);
		$this->api->out();
	}
	
	public function post_ad(){
		if(post()){
			
			$validate = $this->_validateForm('post_ad', TRUE);
			
			if($validate['error_length'] == 0){
				
				$this->load->model('project/project_model');
				$post = filter_data(post());
				/* get_print($post); */
				$ad_id = $this->project_model->postAd($post);
				if($ad_id){
					$this->api->set_cmd('redirect');
					$this->api->set_cmd_params('url', base_url('success-page?page=post_ad'));
					$this->api->out();
				}
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	
	public function save_edit_ad(){
		if(post()){
			
			$validate = $this->_validateForm('save_edit_ad', TRUE);
			
			if($validate['error_length'] == 0){
				
				$this->load->model('project/project_model');
				$post = filter_data(post());
				$ad_id = post('AD_ID');
				/* get_print($post); */
				$update = $this->project_model->saveEditAd($post, $ad_id);
				if($update){
					$this->api->set_cmd('redirect');
					$this->api->set_cmd_params('url', base_url('success-page?page=post_ad'));
					$this->api->out();
				}
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	
	public function set_lang(){
		if(post('lang')){
			$lang = post('lang');
			set_lang($lang);
			$this->api->set_cmd('reload');
			$this->api->out();
			
		}
	}
	
	public function update_profile(){
		if(post()){
			$validate = $this->_validateForm('update_profile', TRUE);
			
			if($validate['error_length'] == 0){
				
				$this->load->model('account/account_model');
				$post = filter_data(post());
				$user_id = get_session('uid');
				$update = $this->account_model->updateProfile($post, $user_id);
				if($update){
					set_session('succ_msg', __('account_profile_updated', 'Profile successfully updated'));
					$this->api->set_cmd('reload');
					$this->api->out();
				}
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	
	public function contact_seller(){
		if(post()){
			$validate = $this->_validateForm('contact_seller', TRUE);
			if($validate['error_length'] == 0){
				$post = filter_data(post());
				$post['datetime'] = date('Y-m-d H:i:s');
				$ins['data'] = $post;
				$ins['table'] = 'ad_enquiry';
				insert($ins);
				set_session('succ_msg', __('project_contact_seller_success', 'Message successfully send'));
				$this->api->set_cmd('reload');
				$this->api->out();
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	
	public function location(){
		$type = get('type');
		if($type == 'country'){
			$data = get_country();
			$this->api->set_data('country', $data);
		}else if($type == 'state'){
			$country_id = get('country_id');
			$data = get_state($country_id);
			$this->api->set_data('state', $data);
		}else if($type == 'city'){
			$state_id = get('state_id');
			$data = get_city($state_id);
			$this->api->set_data('city', $data);
		}
		$this->api->out();
	}
	
	
	public function crop_image(){
		$uid = get_session('uid');
		if(post() && $uid){
			$post = post();
			$update = $this->db->where(array('user_id' => $uid))->update('users_info', array('profile_pic' => $post['image']));
			if(!is_dir(UPLOAD_PATH)){
				mkdir(UPLOAD_PATH);
			}
			$config['image_library'] = 'gd2';
			$config['source_image'] = TMP_UPLOAD_PATH.$post['image'];
			
			$config['maintain_ratio'] = TRUE;
			$config['width']         = $post['width'];
			$config['height']       = $post['height'];
			$config['x_axis']       = $post['x_pos'];
			$config['y_axis']       = $post['y_pos'];
			$config['maintain_ratio']  = FALSE;
			$config['new_image']  = UPLOAD_PATH.'cropped_'.$post['image'];
			
			$this->load->library('image_lib', $config);
			if ( ! $this->image_lib->crop()){
				$res['result'] = 0;
				$res['error'] = $this->image_lib->display_errors();
			}else{
				$res['result'] = 1;
			}
			
			@unlink(TMP_UPLOAD_PATH.$post['image']);
			echo json_encode($res);
		}
		

	}
	
	public function crop_image_bg(){
		if(post()){
			$uid = get_session('uid');
			$post = post();
			$update = $this->db->where(array('id' => $uid))->update('users', array('profile_bg_pic' => $post['image']));
			$config['image_library'] = 'gd2';
			$config['source_image'] = TMP_UPLOAD_PATH.$post['image'];
			
			$config['maintain_ratio'] = TRUE;
			$config['width']         = $post['width'];
			$config['height']       = $post['height'];
			$config['x_axis']       = $post['x_pos'];
			$config['y_axis']       = $post['y_pos'];
			$config['maintain_ratio']  = FALSE;
			$config['new_image']  = UPLOAD_PATH.'bgcropped_'.$post['image'];
			
			$this->load->library('image_lib', $config);
			if ( ! $this->image_lib->crop()){
				$res['result'] = 0;
				$res['error'] = $this->image_lib->display_errors();
			}else{
				$res['result'] = 1;
			}
			echo json_encode($res);
		}
		

	}
	
	public function save_setting(){
		$uid = get_session('uid');
		if(post() && $uid){
			$this->load->model('account/account_model', 'account');
			$post = post();
			$update = $this->account->saveSetting($post, $uid);
			$this->api->set_cmd('reload');
			
			
		}else{
			$this->api->set_cmd('redirectToLogin');
		}
		$this->api->out();
	}
	
		public function change_password(){
		$uid = get_session('uid');
		
		if(post() && $uid){
			
			$validate = $this->_validateForm('change_password', TRUE);
			
			if($validate['error_length'] == 0){
				$this->load->model('account/account_model', 'account');
				$this->load->library('auth');
				$old_password = post('old_password');
				$old_pass_hash = $this->auth->hash_pass($old_password);
				
				$count = $this->db->where(array('user_id' => $uid, 'password' => $old_pass_hash))->count_all_results('users');
				if($count > 0){
					$password = post('password');
					$update = $this->account->changePassword($password, $uid);
					
				}else{
					$this->api->set_error('info', '<div class="info danger">You entered the wrong password</div>');
				}
				
				$this->api->set_cmd('reload');
				
			}else{
				
				echo json_encode($validate);
				die;
				
			}
			
			
		}else{
			$this->api->set_cmd('redirectToLogin');
		}
		
		$this->api->out();
	}
	
	public function book_rental(){
		if(post()){
			$validate = $this->_validateForm('book_rental', TRUE);
			if($validate['error_length'] == 0){
				$post = filter_data(post());
				$post['date'] = date('Y-m-d H:i:s');
				$ins['data'] = $post;
				$ins['table'] = 'rental_bookings';
				insert($ins);
				set_session('succ_msg', __('project_contact_seller_success', 'Booking Successfully Done'));
				$this->api->set_cmd('show_msg');
				$this->api->set_cmd_params('show_msg',  '<div class="alert alert-success" role="alert"><strong>Success!</strong> Your Booking Is Successfully Done . We Will Contact You Soon.</div>');
				$this->api->out();
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	
	public function apply_job(){
		if(post()){
			$validate = $this->_validateForm('apply_job', TRUE);
			if($validate['error_length'] == 0){
				$post = filter_data(post());
				$user_cv = post('user_cv');
				
				if($user_cv){
					move_temp_files($user_cv);
				}
				
				$post['date'] = date('Y-m-d H:i:s');
				$post['status'] = STATUS_ACTIVE;
				$ins['data'] = $post;
				$ins['table'] = 'jobs_application';
				insert($ins);
				set_session('succ_msg', __('career_apply_job_success', 'You have successfully applied for the job'));
				$this->api->set_cmd('show_msg');
				$this->api->set_cmd_params('show_msg',  '<div class="alert alert-success" role="alert"><strong>Success!</strong>'. __('career_apply_job_success', 'You have successfully applied for the job').'</div>');
				$this->api->out();
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	
	
	public function contact_us(){
		if(post()){
			$validate = $this->_validateForm('contact_us', TRUE);
			if($validate['error_length'] == 0){
				$post = filter_data(post());
				
				$post['date'] = date('Y-m-d H:i:s');
				$ins['data'] = $post;
				$ins['table'] = 'contact_us';
				insert($ins);
				set_session('succ_msg', __('home_contact_success_msg', 'Thanku for contacting us. We will contact you soon'));
				$this->api->set_cmd('show_msg');
				$this->api->set_cmd_params('show_msg',  '<div class="alert alert-success" role="alert"><strong>Success! </strong> '. __('home_contact_success_msg', 'Thanku for contacting us. We will contact you soon').'</div>');
				$this->api->out();
				
			}else{
				echo json_encode($validate);
			}
			
		}
	}
	

}
