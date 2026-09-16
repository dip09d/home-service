<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth{
	private $CI;
	private $salt_start = 'FLANC&^%@#4leSS&&8@STRONG';
	private $salt_end = '@$*&#SKlskJH478&&**^';
	
	public function __construct(){
		$this->CI = & get_instance();
	}
	
	public function hash_pass($pswd=''){
		return md5(md5($this->salt_start.$pswd.$this->salt_end));
	}

	
	public function authenticate($password='', $hash=''){
		$pass_hash = md5(md5($this->salt_start.$password.$this->salt_end));
		return $password === $hash;
	}
	
	
}
