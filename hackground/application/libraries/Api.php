<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api {
	
  
	private $error;
	private $data;
	private $cmd = null;
	
    public function __construct(){
       $this->error = $this->data = array();
    }
    
	public function set_error($key='', $val=''){
		if(is_array($key)){
			$this->error = array_merge($this->error, $key);
		}else{
			$this->error[$key] = $val;
		}
		
	}
	
	public function data($key='', $val=''){
		if(is_array($key)){
			$this->data = array_merge($this->data, $key);
		}else{
			$this->data[$key] = $val;
		}
	}
	
	public function set_data($key='', $val=''){
		$this->data($key, $val);
	}
	
	public function cmd($cmd=''){
		$this->cmd = $cmd;
	}
	
	public function set_cmd($cmd=''){
		$this->cmd($cmd);
	}
	
	public function out(){
		$response = array(
			'errors' => $this->error,
			'error_count' => count($this->error),
			'err_length' => count($this->error),
			'data' => $this->data,
			'cmd' => $this->cmd,
		);
		
		echo json_encode($response);
		die();
	}
	
	public function get_out(){
		$response = array(
			'errors' => $this->error,
			'error_count' => count($this->error),
			'err_length' => count($this->error),
			'data' => $this->data,
			'cmd' => $this->cmd,
		);
		return $response;
	}
    
}