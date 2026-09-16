<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Api{
	private $CI;
	private $errors = array();
	private $data = array();
	private $cmd = null;
	private $cmd_params = array();
	
	public function __construct(){
		
	}
	
	public function set_error($key='', $val=''){
		if(is_array($key)){
			$this->errors = array_merge($key,$this->errors);
		}else{
			$this->errors[$key] = $val;
		}
		
	}
	
	public function set_data($key='', $val=''){
		if(is_array($key)){
			$this->data = array_merge($key,$this->data);
		}else{
			$this->data[$key] = $val;
		}
	}
	
	public function set_cmd($cmd=''){
		$this->cmd = $cmd;
	}
	
	public function set_cmd_params($key='', $val=''){
		if(is_array($key)){
			$this->cmd_params = array_merge($key,$this->cmd_params);
		}else{
			$this->cmd_params[$key] = $val;
		}
	}
	
	public function out(){
		$output = array(
			'errors' => $this->errors,
			'error_length' => count($this->errors),
			'data' => $this->data,
			'cmd' => $this->cmd,
			'cmd_params' => $this->cmd_params,
		);
		
		echo json_encode($output);
		die;
	}
	
	public function get_out(){
		$output = array(
			'errors' => $this->errors,
			'error_length' => count($this->errors),
			'data' => $this->data,
			'cmd' => $this->cmd,
			'cmd_params' => $this->cmd_params,
		);
		return $output;
	}
	
	
}
