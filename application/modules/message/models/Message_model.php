<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model {
	
	
	function __construct(){
		parent::__construct();
	}
	
	public function getChatList($member_id='', $limit=0, $offset=100, $for_list=TRUE){
		$conversations_room = $this->db->dbprefix('conversations_room');
		$this->db->select("c.conversations_id,c_m.sender_id,c_m.message_id,c_m.sending_date,c_m.message,c_m.attachment,c_m.is_read,c_r_self.user_id as chat_user_id,c_r.last_seen_msg,c.project_id")
				->from('conversations c')
				->join('conversations_room c_r', 'c_r.conversations_id=c.conversations_id', 'LEFT')
				->join('conversations_room c_r_self', "(c_r.conversations_id=c_r_self.conversations_id and c_r_self.user_id <> '".$member_id."')", 'LEFT')
				->join('conversations_message c_m', 'c_m.message_id=c.last_message_id', 'LEFT');
		
		$this->db->where('c_r.user_id', $member_id);
		$this->db->where('c_r.auth_status', 1);
		$this->db->group_by('c.conversations_id');
		$this->db->order_by('c.last_message_id', 'DESC');
		$return_result = array();
		if( $for_list){
			$this->db->limit($offset, $limit);
			$result = $this->db->get()->result();
			//echo $this->db->last_query();
			if($result){
				foreach($result as $k => $v){
					$return_result[$k] = $this->getMessageUser($v->chat_user_id);
					$return_result[$k]->message = !empty($v->message) ? $v->message : (!empty($v->attachment) ? '<i class="icon-material-outline-attach-file"></i> Attachment' : '');
					$return_result[$k]->attachment = $v->attachment;
					$return_result[$k]->sending_date = $v->sending_date;
					$return_result[$k]->sender_id = $v->sender_id;
					$return_result[$k]->conversations_id = $v->conversations_id;
					$return_result[$k]->message_id = $v->message_id;
					$return_result[$k]->last_seen_msg = $this->getLastSeenMsg($v->chat_user_id, $v->conversations_id);
					$return_result[$k]->unread_msg_count = $this->getUnreadMsgCount($member_id, $v->conversations_id);
					$return_result[$k]->project_name = getField('project_title', 'project', 'project_id', $v->project_id);
					$return_result[$k]->project_url = get_link('myProjectDetailsURL')."/".getField('project_url', 'project', 'project_id', $v->project_id);
					$return_result[$k]->time_ago = get_time_ago($v->sending_date);
				}
			}
		}else{
			$return_result = $this->db->get()->num_rows();
		}
		
		
		return $return_result;
		
	}
	
	public function getUnreadMsgCount($user_id='', $conversations_id=''){
		$conversations_room = $this->db->dbprefix('conversations_room');
		$this->db->select("c_m.*,(select last_seen_msg from $conversations_room where conversations_id = $conversations_id AND user_id = '$user_id') as last_seen_msg_id", FALSE)
			->from('conversations_message c_m');
		$this->db->where('c_m.conversations_id', $conversations_id);
		$this->db->where('c_m.sender_id <>', $user_id);
		$this->db->having("c_m.message_id > ", "last_seen_msg_id", FALSE);
		$result = $this->db->get()->num_rows();
		return $result;
		
	}
	public function getLastSeenMsg($user_id='', $conversations_id=''){
		$result = $this->db->select('last_seen_msg')
			->from('conversations_room')
			->where(array(
				'user_id' => $user_id,
				'conversations_id' => $conversations_id,
			))
			->get()
			->row();
		if($result){
			return $result->last_seen_msg;
		}else{
			return '0';
		}
	}
	
	public function getConversationUserById($conversation_id='', $member_id=''){
		$conversations_room = $this->db->dbprefix('conversations_room');
		$this->db->select("c_m.*,(select user_id from $conversations_room where conversations_id=$conversation_id AND user_id <> '$member_id') as chat_user_id,c_r.last_seen_msg,c.project_id")
				->from('conversations c')
				->join('conversations_room c_r', 'c_r.conversations_id=c.conversations_id', 'LEFT')
				->join('conversations_message c_m', 'c_m.message_id=c.last_message_id', 'LEFT');
		
		$this->db->where('c_r.auth_status', 1);
		$this->db->where('c.conversations_id', $conversation_id);
		$this->db->group_by('c.conversations_id');
		$this->db->order_by('c.last_message_id', 'DESC');
		
		$result = $this->db->get()->row();
		if($result){
			$return_result = $this->getMessageUser($result->chat_user_id);
			$return_result->message = $result->message;
			$return_result->attachment = $result->attachment;
			$return_result->sending_date = $result->sending_date;
			$return_result->sender_id = $result->sender_id;
			$return_result->conversations_id = $result->conversations_id;
			$return_result->message_id = $result->message_id;
			$return_result->last_seen_msg = $this->getLastSeenMsg($result->chat_user_id, $result->conversations_id);
			$return_result->unread_msg_count = $this->getUnreadMsgCount($member_id, $result->conversations_id);
			$return_result->project_name = getField('project_title', 'project', 'project_id', $result->project_id);
			$return_result->project_url = get_link('myProjectDetailsURL')."/".getField('project_url', 'project', 'project_id', $result->project_id);
			$return_result->time_ago = get_time_ago($result->sending_date);
			return $return_result;
		}
		return null;
	}
	
	public function getMessageUser($member_id=''){
		$user = new StdClass();
		$organization_id=getField('organization_id', 'organization', 'member_id', $member_id);
		if($organization_id){
			$user->avatar = getCompanyLogo($organization_id);
			$user->name = getField('organization_name', 'organization', 'organization_id', $organization_id);
		}else{
			$user->avatar = getMemberLogo($member_id);
			$user->name = getField('member_name', 'member', 'member_id', $member_id);
		}
		
		$user->member_id = $member_id;
		$user->profile_url=get_link('viewprofileURL').'/'.md5($member_id);
		$user->online_status = (bool) $this->db->where('user_id', $member_id)->count_all_results('online_user');
		
		return $user;
	}
	
	public function getConversationAttachments($conversation_id='', $limit=0, $offset=30, $for_list=TRUE){
		$this->db->select("c_m.*")
			->from('conversations_message c_m');
		$this->db->where('c_m.conversations_id', $conversation_id);
		$this->db->where('c_m.attachment <>', NULL);
		$this->db->order_by('c_m.message_id', 'DESC');
		if($for_list){
			$this->db->limit($offset, $limit);
			$result = $this->db->get()->result();
			if($result){
				foreach($result as $k => $v){
					$result[$k]->attachment = json_decode($v->attachment);
				}
			}
		}else{
			$result =  $this->db->get()->num_rows();
		}
		
		return $result;
	}
	
	public function getChatMessage($conversation_id='', $login_member='', $limit=0, $offset=30, $for_list=TRUE){

		$this->db->select("c_m.*,c_m_f.message_id as starred")
			->from('conversations_message c_m')
			->join('conversations_message_favorite c_m_f', "(c_m.message_id=c_m_f.message_id and c_m_f.member_id='".$login_member."')", 'LEFT');
		$this->db->where('c_m.conversations_id', $conversation_id);
		$this->db->order_by('c_m.message_id', 'DESC');
		if($for_list){
			$this->db->limit($offset, $limit);
			$result = $this->db->get()->result();
			
			if(count($result) > 0){
				$this->markAsRead($conversation_id, $login_member);
				$this->updateSeenStatus($login_member, $conversation_id);

				foreach($result as $k => $v){
					$result[$k]->message = nl2br($v->message);
                    if($v->reply_to > 0){
                        $result[$k]->parent = $this->get_parent_msg($v->reply_to);
                    }
                    if(!empty($v->is_deleted)){
                        $result[$k]->message = 'This message is deleted ('.date('d M, Y h:i A', strtotime($v->is_deleted)).')';
                        $result[$k]->attachment = null;
                    }
                    if(!empty($v->is_edited)){
                        $result[$k]->edited_display_date = date('d M, Y h:i A', strtotime($v->is_edited));
                    }else{
                        $result[$k]->edited_display_date = null;
                    }
					if($v->attachment){
						$att=json_decode($v->attachment);
						$att->display_name=basename($att->org_file_name,$att->file_ext);
						$att->display_ext=strtoupper(str_replace('.','',$att->file_ext));
						$v->attachment=json_encode($att);
					}
                }
			}
			
		}else{
			$result =  $this->db->get()->num_rows();
		}
		
	
		return $result;
	}
	
	public function send_message($msg=array()){
		$this->db->insert('conversations_message', $msg);
		$last_message_id = $this->db->insert_id();
		$this->db->where('conversations_id', $msg['conversations_id'])->update('conversations', array('last_message_id' => $last_message_id));
		$this->markAsRead($msg['conversations_id'], $msg['sender_id']);
		$this->db->where('conversations_id', $msg['conversations_id'])->update('conversations_room',array('auth_status'=>1));
		$conversation_user = $this->message_model->conversation_user($msg['conversations_id']);
		if($conversation_user){
			foreach($conversation_user as $k => $member_id){
				if($member_id == $msg['sender_id']){
					continue;
				}
				$this->_update_file_msg($member_id);
			}
		}

		return $last_message_id;
	}
	private function _update_file_msg($ukey=''){
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
	
	public function conversation_user($conversation_id=''){
		$users = $this->db->select('user_id')->from('conversations_room')->where('conversations_id', $conversation_id)->get()->result_array();
		if($users){
			$users_array = get_k_value_from_array($users, 'user_id');
		}else{
			$users_array = array();
		}
		
		return $users_array;
	}
	
	public function getNewMessage($conversation_id='', $user_id=''){	
		$conversations_room = $this->db->dbprefix('conversations_room');
		$this->db->select("c_m.*,(select last_seen_msg from $conversations_room where conversations_id = c_m.conversations_id AND user_id = '$user_id') as last_seen_msg_id", FALSE)
			->from('conversations_message c_m');
		$this->db->where('c_m.conversations_id', $conversation_id);
		$this->db->where('c_m.sender_id <>', $user_id);
		$this->db->having("c_m.message_id > ", "last_seen_msg_id", FALSE);
		/* $this->db->where('c_m.is_read', '0'); */
		/* $this->db->order_by('c_m.message_id', 'DESC'); */
		$result = $this->db->get()->result();
		
		if(count($result) > 0){
			$this->markAsRead($conversation_id, $user_id);
			$this->updateSeenStatus($user_id, $conversation_id);
			
		}
		
		
		return $result;
	}
	
	public function updateSeenStatus($login_user='', $conversation_id=''){
		/*
			Update the user update file
		*/
		if(!is_dir(UPLOAD_PATH.'updates')){
			mkdir(UPLOAD_PATH.'updates');
		}
		
		$conversation_users = array_diff($this->conversation_user($conversation_id), array($login_user));
		$last_message_id = $this->getLastMessageId($conversation_id);
		if($conversation_users){
			foreach($conversation_users as $k => $member_id){
				$u_file = UPLOAD_PATH.'updates/user_'.$member_id.'.update'; 
				if(!file_exists($u_file)){
					$data['msg_seen_update'] = array(
						'conversations_id' => $conversation_id,
						'last_message_id' => $last_message_id,
					); 
					file_put_contents($u_file, json_encode($data));
				}else{
					$data = file_get_contents($u_file);
					
					$data = (array) json_decode($data);
					$data['msg_seen_update'] = array(
						'conversations_id' => $conversation_id,
						'last_message_id' => $last_message_id,
					); 
					file_put_contents($u_file, json_encode($data));
				}
			}
		}
		
		
	}
	
	public function markAsRead($conversation_id='', $login_member=''){
		/* $this->db->where('conversations_id', $conversation_id);
		$this->db->where('sender_id <>', $login_member);
		$this->db->update('conversations_message', array('is_read' => 1)); */
		
		$last_message_id = $this->getLastMessageId($conversation_id);
		$this->db->where(array('conversations_id' =>  $conversation_id, 'user_id' => $login_member))->update('conversations_room', array('last_seen_msg' => $last_message_id));
		
	}
	
	public function getLastMessageId($conversation_id=''){
		$this->db->select_max('message_id');
		$this->db->from('conversations_message');
		$result = $this->db->get()->row();
		if($result){
			$last_msg_id = $result->message_id;
		}else{
			$last_msg_id = 0;
		}
		
		return $last_msg_id;
	}
	
	public function getConversationID($project_id='',$member_ids=array(),$is_auth=0){
		$sender_id=$member_ids[0];
		$conversationData=getData(array(
			'select'=>'p_c.conversations_id, count(p_c_m.conversations_id) as total',
			'table'=>'conversations as p_c',
			'join'=>array(array('table'=>'conversations_room as p_c_m','on'=>'p_c.conversations_id=p_c_m.conversations_id','position'=>'left')),
			'where'=>array('p_c.project_id'=>$project_id),
			'where_in'=>array('p_c_m.user_id'=>$member_ids),
			'single_row'=>true,
			'group'=>'p_c_m.conversations_id',
			'having'=>'count(p_c_m.conversations_id)>1',
			
		));
		if($conversationData){
			$selected_conversation_id=$conversationData->conversations_id;
		}else{
			$project_conversation=array(
				'project_id'=>$project_id,
				'status'=>1
			);
			$selected_conversation_id=insert_record('conversations',$project_conversation,TRUE);
			if($selected_conversation_id){
				$conversations_message=array(
					'conversations_id'=>$selected_conversation_id,
					'sender_id'=>$sender_id,
					'sending_date'=>date('Y-m-d H:i:s'),
					'message'=>'Chat initiated',
				);
				$message_id=insert_record('conversations_message',$conversations_message,TRUE);
				if($message_id){
					$this->db->where('conversations_id',$selected_conversation_id)->update('conversations',array('last_message_id'=>$message_id));
					if($member_ids){
						foreach($member_ids as $member_id){
							if($sender_id==$member_id){
								$is_auth_set=1;
							}else{
								$is_auth_set=$is_auth;
							}
							$project_conversation_member=array(
							'conversations_id'=>$selected_conversation_id,
							'user_id'=>$member_id,
							'auth_status'=>$is_auth_set,
							'last_seen_msg'=>$message_id,
							);
							insert_record('conversations_room',$project_conversation_member,TRUE);
						}
					}
				}
			}
		}	
		return $selected_conversation_id;
	}
	public function get_parent_msg($msg_id){
        $result = $this->db->select("c_m.*")
                ->from('conversations_message c_m')
                ->where('c_m.message_id', $msg_id)
                ->get()->row();
        if(!empty($result->deleted)){
            $result->message = 'This message is deleted ('.date('d M, Y h:i A', strtotime($result->deleted)).')';
            $result->attachment = null;
        }
        return $result;
    }
	
}
