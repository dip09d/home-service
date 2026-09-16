<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}else{
			redirect(URL::get_link('loginURL').'?ref=dashboardURL');
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();	
		$this->load->library('form_validation');
			parent::__construct();
	}
	public function security_password()
	{
		
		
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
			));
		if($this->access_member_type=='F'){
			$this->data['left_panel']=load_view('inc/freelancer-setting-left','',TRUE);
		}
		if($this->access_member_type=='C'){
			$this->data['left_panel']=load_view('inc/client-setting-left','',TRUE);
		}
		$this->layout->view('security-password', $this->data);
	}
	public function change_password_form()
	{
		checkrequestajax();
		
		if($this->loggedUser){
			$this->layout->view('change-password', $this->data,TRUE);
		}
	}
	public function change_password_form_check()
	{
		$this->load->library('bcrypt');
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		$this->form_validation->set_rules('old_password', 'Old password', 'required|trim|xss_clean');
		$this->form_validation->set_rules('new_password', 'New password', 'required|trim|xss_clean|max_length[15]|min_length[6]|alpha_numeric');
		$this->form_validation->set_rules('confirm_password', 'Confirm password', 'required|trim|xss_clean|max_length[15]|min_length[6]|alpha_numeric|matches[new_password]');
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}else{
			$member_id="";
			$organization_id="";
			if($this->loggedUser){
				$memberData=getData(array(
					'select'=>'a.access_user_id,a.access_user_password',
					'table'=>'access_panel as a',
					'where'=>array('a.access_user_id'=>$this->access_user_id),
					'single_row'=>true,
				));	
				if($memberData){
					$password=post('old_password');
					$mdpass= md5($password);
					$old_password=$memberData->access_user_password;
					
					if($old_password==$mdpass || $this->bcrypt->check_password($password, $old_password)){
						
					}else{
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = 'old_password';
						$msg['errors'][$i]['message'] = 'Old password not match ';
						$i++;
					}
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'old_password';
					$msg['errors'][$i]['message'] = 'Invalid ';
					$i++;
				}
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'old_password';
				$msg['errors'][$i]['message'] = 'Invalid ';
			}


			if($i==0){
				$hash = $this->bcrypt->hash_password(post('new_password'));
				$up=update(array('table'=>'access_panel','where'=>array('access_user_id'=>$this->access_user_id),'data'=>array('access_user_password'=>$hash)));
				if($up){
					$msg['status'] = 'OK';
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'email';
					$msg['errors'][$i]['message'] = 'Invalid ';
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);		
	}
	
}
