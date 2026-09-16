<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_notification_global_model extends CI_Model{
	
	public function __construct(){
	
        return parent::__construct();
	}
	
	public function log_notification($data=array()){
		$ins = array(
			'message' => !empty($data['message']) ? $data['message'] : '',
			'template_key' => !empty($data['template_key']) ? $data['template_key'] : '',
			'link' => !empty($data['link']) ? $data['link'] : '',
			'created_date' =>date('Y-m-d H:i:s'),
		);
		
		return $this->db->insert('admin_notifications', $ins);
	}
	
	public function parse($template='', $parse_data=array(), $link=''){
		$this->load->library('parser');
		$admin_default_lang = get_setting('admin_default_lang');
		if(!$admin_default_lang){
			$admin_default_lang = 'en';
		}
		$this->db->select('n.*')
				->from('notifications_template n_t')
				->join('notifications_template_names n', 'n.notification_template_id=n_t.notification_template_id', 'LEFT');
		
		$this->db->where('n.lang', $admin_default_lang);
		$this->db->where('n_t.template_key', $template);
		
		$result = $this->db->get()->row();
		if(!empty($result->template_content)){
			$notification_string_template = $result->template_content;
		}else{
			$notification_string_template = '';
		}
		if($notification_string_template){
			$notification =  $this->parser->parse_string($notification_string_template, $parse_data, TRUE);
		}else{
			$notification = '';
		}
		
		return $this->log_notification(array(
			'message' => $notification,
			'template_key' => $template,
			'link' => $link,
		));
	}
	
	
	
	
}


