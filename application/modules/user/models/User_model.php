<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {
	
	private $lang;
	
    public function __construct() {
		$this->lang = get_active_lang();
        return parent::__construct();
    }
	
	
	
	
	
	

	
	
	public function _generate_referal_key($fname=''){
		do{
			$fname = trim($fname);
			$time = time();
			$unique_key = $fname.'_'.$time;
		} while($this->referal_link_exist($unique_key));
		
		return $unique_key;
	
	}
	
	public function referal_link_exist($link=''){
		$count = $this->db->where("referal_code" , $link)->count_all_results('users');
		if($count > 0){
			return TRUE;
		}
		return FALSE;
		
	}
	
	public function forgot($email='') {
		$response = array();
		$this->db->select('user_id,name');
		$query = $this->db->get_where("users", array("email" => $email));
		$result = $query->row();
		if (count($result) == 0) {
			$this->api->set_error('forget', 'Email not exists');
		} else {
			
		   //update pass send mail;
		   $user_id = getField('user_id' , 'users' , 'email' , $email);
			$pass = md5(time().'_'.rand(1111111, 9999999).'_'.$user_id);
			$data = array(
			'user_id' =>  $user_id,
			'token' => $pass,
			'added_on' => date('Y-m-d H:i:s'),
			'expired_on' => date('Y-m-d H:i:s', strtotime("+1 days")),
			);
			
			$to=$email;
			$template='user_forget_password';
			$data_parse=array(
				'USER' => $result->name,
				'RESET_A_LINK' => '<a href="'.base_url('set-password/'.$pass).'">click here </a>',
				'RESET_LINK' => base_url('set-password/'.$pass),
			);
			/* $this->db->update('users', $data,array('user_id' => $user_id,"email" => $email)); */
			$now = date('Y-m-d H:i:s');
			$this->db->where('expired_on <', $now)->delete('tokens');
			$this->db->insert('tokens', $data);
			
			$mail = SendMail($to,$template , $data_parse);
			if($mail){
				
				$this->api->set_cmd('redirect');
				
				$this->api->set_cmd_params('url', base_url('success-page?type=forget_password'));
				
			}else{
				$this->api->set_error('forget', 'Sorry , Something went wrong. Please try again later');
			}
			
		}
		$this->api->out();
	}
	
	public function isValidToken($token=''){
		if($token == ''){
			return false;
		}
		$count = (bool) $this->db->where('token', $token)->count_all_results('tokens');
		return $count;
		
	}
	
	public function setPassword($password='', $token=''){
		if($token == ''){
			return false;
		}
		$user_id = getField('user_id', 'tokens', 'token', $token);
		$this->db->where('user_id', $user_id)->update('users', array('password' => $password));
		
	}
	
	public function resetToken($token){
		$this->db->where('token', $token)->delete('tokens');
	}
	
	public function registerUser($data=array()){
		$password = null;
		$this->load->library('auth');
		if(!empty($data['password'])){
			$password = $this->auth->hash_pass($data['password']);
		}
		
		
		$structure = array(
			'name' => !empty($data['name']) ? $data['name'] : '',
			'email' => !empty($data['email']) ? $data['email'] : '',
			'password' => $password,
			'registered_on' => date('Y-m-d H:i:s'),
			'status' => STATUS_ACTIVE,
		
		);
		
		$this->db->insert('users', $structure);
		$user_id = $this->db->insert_id();
		
		$str_2 =  array(
			'user_id' => $user_id
		);
		$this->db->insert('users_info', $str_2);
		
		return $user_id;
	}
	

	public function getData(){
		$admin_default_lang = $this->lang;
		$this->table = 'category_subchild';
		$this->lang_table = 'category_subchild_names';
		$this->primary_key = 'category_subchild_id';
		
		$this->db->select('a.category_subchild_id,b.category_subchild_name')
			->from($this->table. ' a')
			->join($this->lang_table. ' b', 'a.'.$this->primary_key.'='.'b.'.$this->primary_key);
		
			$this->db->where('a.category_subchild_status', 1);
		if(!empty($srch['category'])){
			$this->db->where('a.category_id', $srch['category']);	
			
		}
		
		
		$this->db->where('b.category_subchild_lang', $admin_default_lang);	
		$result = $this->db->order_by('a.'.$this->primary_key, 'DESC')->get()->result_array();
		
		return $result;
	}
}
