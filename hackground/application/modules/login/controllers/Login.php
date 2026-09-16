<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {
    private $data;
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('login_model', 'login');
		parent::__construct();
		
		
	}

	public function index(){
		if(get_session('admin_id')){
			redirect(base_url('dashboard'));
		}
		$this->layout->view('login', $this->data, TRUE);
	}
	
	public function login_ajax(){
		
		$this->load->library('form_validation');
		
		if($this->input->post()){
			$json = array();
			$this->form_validation->set_rules('username' , 'username', 'required');
			$this->form_validation->set_rules('password' , 'password', 'required');
			if($this->form_validation->run()){
				// handle the form
				$post = filter_data($this->input->post());
				$auth = $this->login->check_login($post['username'], $post['password']);
				if($auth){
					
					$admin_info = $this->login->get_admin_info($auth);
					
					set_session('admin_id', $auth);
					
					set_session('admin_detail', $admin_info);
					
					if(!empty($post['remember_me']) && $post['remember_me'] == '1'){
						$detail = array(
							'uname' => $post['username'],
							'pwd' => $post['password'],
						);
						$detail_str = serialize($detail);
						set_cookie('l_info', $detail_str, (60*60*24*30));
					}else{
						delete_cookie('l_info');
					}
					
					$json['status'] = 1;
					$json['next'] = base_url('dashboard');
				}else{
					$json['status'] = 0;
					$json['errors']['login'] = 'Invalid username and password';
				}
				
			}else{
				$json['errors'] = validation_errors_array();
				$json['status'] = 0;
			}
			echo json_encode($json);
		}
	}
	
	public function forgot_password_ajax(){
		
		$this->load->library('form_validation');
		
		if($this->input->post()){
			$json = array();
			$this->form_validation->set_rules('email' , 'email', 'required|valid_email');
			if($this->form_validation->run()){
				// handle the form
				$post = filter_data($this->input->post());
				$email_check = $this->login->check_email($post['email']);
				if($email_check){
					
					// send email
					$this->login->send_reset_link($post['email']);
					
					$json['status'] = 1;
					$json['msg'] ='<div class="alert alert-success"><strong>Success!</strong> Reset link has been send to your email .</div>';
				}else{
					$json['status'] = 0;
					$json['msg'] ='<div class="alert alert-danger"><strong>Error!</strong> Email Not Exist</div>';
				}
				
			}else{
				$json['errors'] = validation_errors_array();
				$json['status'] = 0;
			}
			echo json_encode($json);
		}
	}
	
	
	public function logout() {
        if (get_session('admin_id')) {
			
            delete_session('admin_id');
            delete_session('admin_detail');
            destroy_session();
			
            redirect(base_url('login'));
			
        } else {
            redirect(base_url('login'));
        }
    }
		public function reset_password($reset_token=''){		if(!$reset_token){			show_404();		}		$admin_row = get_row(			array(				'select' => '*',				'from' => 'admin',				'where' => array('token' => $reset_token)			)		);				if(!$admin_row){			show_404();		}				$this->data['reset_token'] = $reset_token;				$this->layout->view('reset_password', $this->data, TRUE);			}		public function reset_password_ajax(){		$this->load->library('form_validation');				if(post() && $this->input->is_ajax_request()){			$json = array();			$this->form_validation->set_rules('password' , 'password', 'required');			$this->form_validation->set_rules('c_password' , 'confirm password', 'required|matches[password]');			$this->form_validation->set_rules('token' , 'token', 'required');						if($this->form_validation->run()){								$token = post('token');				$password = md5(post('password'));								$token_user = $this->login->get_user_by_token($token);				if($token_user){					$this->login->reset_pasword($token, $password);					$json['status'] = 1;					$json['next'] = base_url('login');				}else{					$json['msg'] ='<div class="alert alert-danger"><strong>Error!</strong> Invalid Token </div>';				}							}else{				$json['errors'] = validation_errors_array();				$json['status'] = 0;			}						echo json_encode($json);					}			}	

}


