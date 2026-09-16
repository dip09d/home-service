<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model{
	
	public function __construct(){
	
        return parent::__construct();
	}
	
	public function log($template, $data, $link='', $to, $from='0'){
		if(!$template || !$to){
			throw new Exception("Incorrect paramters: [Notification Log]");
		}
		if($data){
			$data_json = json_encode($data);
		}else{
			$data_json = '';
		}
		$dbdata = array(
			'notification_from' => $from,
			'notification_to' => $to,
			'notification_template_key' => $template,
			'template_data' => $data_json ,
			'link' => $link ,
			'read_status' => '0',
			'sent_date' => date('Y-m-d H:i:s'),
		);
		$this->db->insert('member_notifications', $dbdata);
		$notification_id = $this->db->insert_id();
		$this->notify_user($to, 'new_notification');
		
		
	}
	
	public function notify_user($member_id='', $notify_for='new_notification'){
		$u_file = UPLOAD_PATH.'updates/user_'.$member_id.'.update'; 
		if(!file_exists($u_file)){
			$data[$notify_for] = 1; 
			file_put_contents($u_file, json_encode($data));
		}else{
			$data = file_get_contents($u_file);
			
			$data = (array) json_decode($data);
			if(array_key_exists($notify_for, $data)){
				$data[$notify_for] = $data[$notify_for] + 1;
			}else{
				$data[$notify_for] = 1; 
			}
			
			file_put_contents($u_file, json_encode($data));
		}
	}
	
	public function notify_unset($member_id=''){
		$notify_for = 'new_notification';
		$u_file = UPLOAD_PATH.'updates/user_'.$member_id.'.update'; 
		if(!file_exists($u_file)){
			$data[$notify_for] = 0; 
			file_put_contents($u_file, json_encode($data));
		}else{
			$data = file_get_contents($u_file);
			
			$data = (array) json_decode($data);
			$data[$notify_for] = 0; 
			
			file_put_contents($u_file, json_encode($data));
		}
	}
	
	public function parse_notification($template='', $parse_data=array()){
		
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
		if($result->template_content){
			$notification_string_template = $result->template_content;
		}else{
			$notification_string_template = '';
		}
		
		$notification = $this->parser->parse_string($notification_string_template, $parse_data, TRUE);
		return $notification;
		
	}
	
	public function getNotificationList($member_id='', $limit=0, $offset=20, $for_list=TRUE){
		$this->load->library('parser');
		$active_lang = get_active_lang();
		$this->db->select('m_n.*,n.template_content')
				->from('member_notifications m_n')
				->join('notifications_template n_t', 'n_t.template_key=m_n.notification_template_key', 'LEFT')
				->join('notifications_template_names n', 'n.notification_template_id=n_t.notification_template_id', 'LEFT');
				
		$this->db->where('n.lang', $active_lang);
		$this->db->where('m_n.notification_to', $member_id);
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('notification_id', 'DESC')->get()->result();
			if($result){
				foreach($result as $k => $v){
					if($v->template_content){
						$notification_string_template = $v->template_content;
					}else{
						$notification_string_template = '';
					}
					$parse_data = !empty($v->template_data) ? (array) json_decode($v->template_data) : array();
					$result[$k]->notification = $this->parser->parse_string($notification_string_template, $parse_data, TRUE);
					$result[$k]->time_ago = get_time_ago($v->sent_date);
				}
			}
		}else{
			$result = $this->db->get()->num_rows();
		}
		
		return $result;
	}
}