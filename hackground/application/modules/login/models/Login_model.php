<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Login_model extends CI_Model{

	

	public function __construct(){

        return parent::__construct();

	}



	

	public function check_login($username='' , $pswd=''){

		

		$user = get_row(array('select' => 'admin_id , username , password' , 'from' => 'admin', 'where' =>  array('username' => $username, 'password' => md5($pswd), 'status' => ACTIVE_STATUS)));

		

		if(!empty($user['admin_id'])){

			

			return $user['admin_id'];

		   



		}

		

		return FALSE;

	}

	

	public function get_admin_info($admin_id=''){

		

		$res = get_row(array('select' => 'full_name,profile_pic,admin_id,role_id', 'from' => 'admin', 'where' => array('admin_id' => $admin_id, 'status' => ACTIVE_STATUS)));

		

		return $res;

	}



	/* forgot password */

	

	public function check_email($email=''){

		$count = (bool) $this->db->where('email', $email)->count_all_results('admin');

		return $count;

	}

	

	public function send_reset_link($email=''){
		$admin_name = getField('full_name', 'admin', 'email', $email);
		$reset_token = md5(time().'@@!##SkKfivERR**&^'.'<=>');
		$reset_link = base_url('login/reset_password/'.$reset_token);
		$data_parse = array(
			'USER' => $admin_name,
			'RESET_LINK' => $reset_link,
		);
		$template = 'admin-forgot-password';
		
		$this->db->where('email', $email)->update('admin', array('token' => $reset_token));
		
		return SendMail($email, $template, $data_parse);
	}

	public function get_user_by_token($token=''){
		if(!$token){
			return FALSE;
		}
		return $this->db->where('token', $token)->get('admin')->row_array();
	}
	
	public function reset_pasword($token='', $password=''){
		$data = array(
			'token' => '',
			'password' => $password,
		);
		return $this->db->where('token', $token)->update('admin', $data);
	}

}





