<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends MX_Controller {

	function __construct()
	{
		
			parent::__construct();
	}
	public function savenotification($data=array()){
		$notifications=array(
		'sender_id'=>$data['sender_id'],
		'receiver_id'=>$data['receiver_id'],
		'notification_template'=>$data['template'],
		'notification_url'=>$data['url'],
		'notification_content'=>json_encode($data['content']),
		'notification_date'=>date('Y-m-d H:i:s'),
		'is_read'=>0,
		);
		$this->db->insert('notifications',$notifications);
		$nid=$this->db->insert_id();
		$this->insert_notification_file($data['receiver_id'],$nid);
	}
	public function insert_notification_file($id,$nid){
		
	  $this->load->helper('file');
	  if($id>0){
		$count_notic=$this->db->where('receiver_id',$id)->where('is_deleted <>',1)->where('is_read',0)->from('notifications')->count_all_results();
		$array=array(
		'unread'=>$count_notic,
		'poupmessage'=>array(),
		);
		$filename=LC_PATH."ECnote/".$id.".echo";
		if(!file_exists($filename)){
			$array['poupmessage'][]=$nid;
			if ( !write_file($filename, json_encode($array), 'w')){
				 echo 'Unable to write the file';
			}
		}else{
			$data=json_decode(file_get_contents($filename));
			if($data && $data->poupmessage){
				$data->poupmessage[]=$nid;
			}else{
				$data->poupmessage=array();
			}
			$array['poupmessage']=$data->poupmessage;
			
			if ( !write_file($filename, json_encode($array), 'w')){
				 echo 'Unable to write the file';
			}
		}
	  }
	}
	
}
