<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends MX_Controller {
	
	private $data;
	function __construct(){
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
			$this->organization_id=$this->loggedUser['OID'];
		}elseif($this->router->fetch_method()=='update_service'){

		}else{
			redirect(URL::get_link('loginURL').'?ref=dashboardURL');
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		$this->load->model('message_model');
		parent::__construct();
	}
	
	public function index($selected_conversation_id=''){
		$this->layout->set_js(array(
			'jquery.nicescroll.min.js',
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',			
		));
		
		if($this->access_member_type=='F'){
			$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
		}else{
			
			$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
		}
		$this->data['login_member'] = $this->message_model->getMessageUser($this->member_id);
		if($selected_conversation_id){
			$this->data['active_chat'] = $this->message_model->getConversationUserById($selected_conversation_id, $this->member_id);
		}else{
			$this->data['active_chat'] = null;
		}
		
		$this->layout->view('message', $this->data);
	}
	
	public function load_chat(){
		$json['status'] = 1;
		$json['chat_list'] = $this->message_model->getChatList($this->member_id);
		$this->_reset_message_file($this->member_id);
		echo json_encode($json);
	}
	
	public function chat_list_htm(){
		$data=array();
		$get = get();

		$limit = !empty($get['per_page']) ? $get['per_page'] : 0;
		$offset = 10;
		$next_limit = $limit + $offset;
		
		
		$data['chat_list'] = $this->message_model->getChatList($this->member_id,$limit, $offset);
		$data['chat_list_count'] = $this->message_model->getChatList($this->member_id,'','', FALSE);
		
		$json['chat_list'] = $data['chat_list'];
		$json['chat_list_count'] = $data['chat_list_count'];
		
		if($data['chat_list_count'] > $next_limit){
			$json['next'] = base_url('message/chat_list_htm?per_page='.$next_limit);
		}else{
			$json['next'] = null;
		}
		
		$json['html'] = $this->layout->view('chat_list_htm',$data, TRUE, TRUE);
		
		$json['status'] = 1;
		
		echo json_encode($json);
	}
	
	public function load_chat_message($conversation_id=''){
		$login_member = $this->member_id;
		$json['status'] = 1;
		$limit = get('limit') > 0 ? get('limit') : 0;
		$offset = 10;
		$json['chat_message'] = $this->message_model->getChatMessage($conversation_id, $login_member, $limit, $offset);
		$json['chat_message_total'] = $this->message_model->getChatMessage($conversation_id, $login_member, $limit, $offset, FALSE);
		$json['next_limit'] = ($limit+$offset);
		echo json_encode($json);
	}
	
	public function load_attachments($conversation_id=''){
		$login_member = $this->member_id;
		$json['status'] = 1;
		$limit = get('limit') > 0 ? get('limit') : 0;
		$offset = 10;
		$json['attachments'] = $this->message_model->getConversationAttachments($conversation_id, $limit, $offset);
		$json['attachment_total'] = $this->message_model->getConversationAttachments($conversation_id, $limit, $offset, FALSE);
		$json['next_limit'] = ($limit+$offset);
		echo json_encode($json);
	}
	
	public function send_msg(){
		$json['status'] = 1;
		if(post() && $this->input->is_ajax_request()){
			$message = post('message');
			$reply_to = post('reply_to');
			$attachment = post('attachment');
			$conversations_id = post('conversations_id');
			$message = array(
				'sender_id' => $this->member_id,
				'conversations_id' => $conversations_id,
				'message' => $message,
				'sending_date' => date('Y-m-d H:i:s'),
			);
			if($reply_to){
				$message['reply_to']=$reply_to;
			}
			if($attachment){
				$message['attachment']=$attachment;
			}
			$json['last_message_id'] = $this->message_model->send_message($message);
			$message['message_id'] = $json['last_message_id'];
			$message['parent'] = $this->message_model->get_parent_msg($reply_to);
			if($attachment){
				$att=json_decode($attachment);
				$att->display_name=basename($att->org_file_name,$att->file_ext);
				$att->display_ext=strtoupper(str_replace('.','',$att->file_ext));
				$message['attachment']=$att;
			}
			$json['message_data'] = $message;
			
			
			
		}
		echo json_encode($json);
	}
	
	public function send_attachment(){
		$json['status'] = 1;
		if(post() && !empty($_FILES['file']['name'])){
			$config['upload_path'] = UPLOAD_PATH.'message-attachments';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xl|xls|zip|txt';
			$config['encrypt_name']  = TRUE;
			if(!is_dir($config['upload_path'])){
				mkdir($config['upload_path']);
			}
			$this->load->library('upload', $config);
			
				if ( ! $this->upload->do_upload('file')){
					$json['error'] = 'Error: '.$this->upload->display_errors();
					$json['status'] = 0;
                }else {
					$upload_data = $this->upload->data();
					$attachment = array(
						'file_name' => $upload_data['file_name'],
						'file_url' => UPLOAD_HTTP_PATH.'message-attachments/'.$upload_data['file_name'],
						'org_file_name' => $upload_data['orig_name'],
						'is_image' => $upload_data['is_image'],
						'file_size' => $upload_data['file_size'],
						'file_ext' => $upload_data['file_ext'],
						'display_name' => basename($upload_data['orig_name'],$upload_data['file_ext']),
						'display_ext' => strtoupper(str_replace('.','',$upload_data['file_ext'])),
						
					);
					$conversations_id = post('conversations_id');
					$reply_to = post('reply_to');
					$message = array(
						'sender_id' => $this->member_id,
						'conversations_id' => $conversations_id,
						'message' => '',
						'attachment' => json_encode($attachment),
						'sending_date' => date('Y-m-d H:i:s'),
						
					);
					if($reply_to){
						$message['reply_to']=$reply_to;
					}
					$json['last_message_id'] = $this->message_model->send_message($message);
					$json['message_data'] = $message;
			        $message['parent'] = $this->message_model->get_parent_msg($reply_to);
					$json['message'] = $message;
					
					$json['attachment'] = $attachment;
			
				}
				
		}
		echo json_encode($json);
	}
	public function send_attachment_temp(){
		$json['status'] = 1;
		if(post() && !empty($_FILES['file']['name'])){
			$config['upload_path'] = UPLOAD_PATH.'message-attachments';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xl|xls|zip|txt';
			$config['encrypt_name']  = TRUE;
			if(!is_dir($config['upload_path'])){
				mkdir($config['upload_path']);
			}
			$this->load->library('upload', $config);
			
				if ( ! $this->upload->do_upload('file')){
					$json['error'] = 'Error: '.$this->upload->display_errors();
					$json['status'] = 0;
                }else {
					$upload_data = $this->upload->data();
					$attachment = array(
						'file_name' => $upload_data['file_name'],
						'file_url' => UPLOAD_HTTP_PATH.'message-attachments/'.$upload_data['file_name'],
						'org_file_name' => $upload_data['orig_name'],
						'is_image' => $upload_data['is_image'],
						'file_size' => $upload_data['file_size'],
						'file_ext' => $upload_data['file_ext'],
						/* 'display_name' => basename($upload_data['orig_name'],$upload_data['file_ext']),
						'display_ext' => strtoupper(str_replace('.','',$upload_data['file_ext'])), */
						
					);
					$json['attachment'] = $attachment;
					$json['attachment_data'] = json_encode($attachment);
			
				}
				
		}
		echo json_encode($json);
	}
	private function _update_file($ukey=''){
		/*
			Update the user update file
		*/
		if(!is_dir(UPLOAD_PATH.'updates')){
			mkdir(UPLOAD_PATH.'updates');
		}
		$u_file = UPLOAD_PATH.'updates/user_'.$ukey.'.update'; 
		if(!file_exists($u_file)){
			$data['new_message'] = 1; 
			file_put_contents($u_file, json_encode($data));
		}else{
			$data = file_get_contents($u_file);
			
			$data = (array) json_decode($data);
			$data['new_message'] = $data['new_message'] + 1;
			file_put_contents($u_file, json_encode($data));
		}
	}
	public function online_uer_up(){
		$is_process=false;
		if(!$this->session->userdata('lastupdate')){
			$is_process=true;
			$this->session->set_userdata('lastupdate',time());
		}else{
			$lasttime=$this->session->userdata('lastupdate');
			if(time() > $lasttime+30){
				$is_process=true;	
				$this->session->set_userdata('lastupdate',time());
			}
		}
		if($is_process){
			$newtime=date('Y-m-d H:i:s',strtotime('-30 second'));
			$wh=" (last_active < '".$newtime."' or user_id='".$this->member_id."')";
			$this->db->where($wh)->delete('online_user');
			$this->db->insert('online_user',array('user_id'=>$this->member_id,'last_active'=>date('y-m-d H:i:s')));
		}
	}
	public function update_service(){
		if($this->loggedUser){
			$this->online_uer_up();
			if(!is_dir(UPLOAD_PATH.'updates')){
				mkdir(UPLOAD_PATH.'updates');
			}
			$member_id = $this->member_id;
			$u_file = UPLOAD_PATH.'updates/user_'.$member_id.'.update'; 
			if(file_exists($u_file)){
				$content = file_get_contents($u_file);
			}else{
				$content = '0';
			}
		}else{
			$content = '0';
		}
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		//echo "id: ".time() . PHP_EOL;
		//echo 'event: update'. PHP_EOL;
		echo "data: $content " . PHP_EOL;
		echo PHP_EOL;
		flush();
		
	}
	
	public function load_new_message($conversation_id=''){
		$member_id = $this->member_id;
		$json = array();
		$json['status'] = 1;
		$json['new_message'] = $this->message_model->getNewMessage($conversation_id, $member_id);
		
		$this->_reset_message_file($member_id);
		
		echo json_encode($json);
	}
	
	public function reset_msg_seen(){
		$member_id = $this->member_id;
		if(!is_dir(UPLOAD_PATH.'updates')){
			mkdir(UPLOAD_PATH.'updates');
		}
		$u_file =UPLOAD_PATH.'updates/user_'.$member_id.'.update';
		if(file_exists($u_file)){
			$data = file_get_contents($u_file);
			$data = (array) json_decode($data);
			$data['msg_seen_update'] = array();
		}else{
			$data['msg_seen_update'] = array();
		}
		
		
		file_put_contents($u_file, json_encode($data));
	}
	
	private function _reset_message_file($member_id=''){
		if(!is_dir(UPLOAD_PATH.'updates')){
			mkdir(UPLOAD_PATH.'updates');
		}
		$u_file =UPLOAD_PATH.'updates/user_'.$member_id.'.update';
		if(file_exists($u_file)){
			$data = file_get_contents($u_file);
			$data = (array) json_decode($data);
			$data['new_message'] = 0;
		}else{
			$data['new_message'] = 0;
		}
		
		
		file_put_contents($u_file, json_encode($data));
	
	}
	
	public function createnewroom($pid='',$mid='')
	{
		$is_valid=0;
		if($this->loggedUser){
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;
			$project_id=getFieldData('project_id','project','md5(project_id)',$pid);
			$bidder_id=getFieldData('member_id','project_bids','md5(member_id)',$mid);
			$this->data['projects']=getProjectDetails($project_id,array('project_owner'));
			if($this->data['projects']){
				$owner_organization_id=$this->data['projects']['project_owner']->organization_id;
				$owner_member_id=$this->data['projects']['project_owner']->member_id;
				//if(($owner_member_id==$member_id) || ($bidder_id=$member_id)){
				if(($owner_member_id==$member_id)){
					$member_ids=array($owner_member_id,$bidder_id);
					$selected_conversation_id=$this->message_model->getConversationID($project_id,$member_ids,0);
					if($selected_conversation_id){
						$is_valid=1;
					}
				}
			}
		}
		if($is_valid){
			redirect(get_link('MessageRoomURL').'/'.$selected_conversation_id);
		}else{
			redirect(get_link('dashboardURL'));
		}
	}
	public function star_toggle(){
        $id = post('ID');
        $type = post('type'); // message
        $user_id = $this->member_id;
        $table = 'conversations_message_favorite';

        $cond = [
            'member_id' => $user_id,
            'message_id' => $id
        ];
        $check = $this->db->where($cond)->count_all_results($table);
        if($check > 0){
            $this->db->where($cond)->delete($table);
            $action = 'removed';
        }else{
            $this->db->insert($table, $cond);
            $action = 'added';
        }
		$json['action']=$action;
		$json['status']=1;
		echo json_encode($json);
    }
	public function delete_msg($msg_id){
        $this->db->where(['sender_id' => $this->member_id, 'message_id' => $msg_id])->update('conversations_message', array('is_deleted' => date('Y-m-d H:i:s')));
        echo json_encode(array(
            'status' => 1,
            'deleted' => date('Y-m-d H:i:s'),
            'msg_txt' => 'This message is deleted ('.date('d M, Y h:i A').')'
		));
        die;
    }
	public function edit_ajax(){
		$edit_date=date('Y-m-d H:i:s');
        $ID = post('ID');
        $message = filter_data(post('message'));
		$message_org=getFieldData('message','conversations_message','','',array('sender_id' =>$this->member_id, 'message_id' => $ID));
        $up=$this->db->where(['sender_id' =>$this->member_id, 'message_id' => $ID])->update('conversations_message', ['is_edited' => $edit_date, 'message' => $message]);
		if($up){
			$this->db->insert('conversations_message_edited',array('mesage_id'=>$ID,'message_org'=>$message_org,'edit_date'=>$edit_date));
		}
        echo json_encode([
            'status' => 1,
            'edited' =>$edit_date,
            'edited_display_date' => date('d M, Y h:i A',strtotime($edit_date)),
            'msg_txt' =>  nl2br($message)
        ]);
        die;
    }
	
	
}
