<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Message_model extends CI_Model
{

	private $table, $primary_key;

	public function __construct()
	{
		$this->table = 'conversations';
		$this->primary_key = 'conversations_id';
		return parent::__construct();
	}

	public function getList_old($srch = array(), $limit = 0, $offset = 20, $for_list = TRUE)
	{
		$this->db->select('c.*,p.project_title,c_m.message,c_m.attachment,c_m.sending_date')
			->from($this->table . ' as  c')
			->join('project as p', 'c.project_id=p.project_id', 'left')
			->join('conversations_message c_m', 'c_m.message_id=c.last_message_id', 'LEFT')
		;
		$this->db->where('c.status', 1)->order_by('c.conversations_id', 'desc');
		if ($for_list) {
			$result = $this->db->limit($offset, $limit)->order_by('c.last_message_id', 'DESC')->get()->result_array();
			if ($result) {
				foreach ($result as $k => $row) {
					$row['group'] = $this->db->select('m.member_name,r.user_id')->from('conversations_room as r')->join('member as m', 'r.user_id=m.member_id', 'left')->where('r.conversations_id', $row['conversations_id'])->get()->result();
					$row['sender_name'] = '';
					$row['receiver_name'] = '';
					$result[$k] = $row;
				}
			}
		} else {
			$result = $this->db->count_all_results();
		}

		return $result;
	}

	public function getList($srch = array(), $limit = 0, $offset = 20, $for_list = TRUE)
	{
		$this->db->select('
			pc.conversations_id,
			pc.booking_id,
			pc.member_id AS chat_member_id,
			pc.worker_id AS chat_worker_id,
			pc.last_message_id,
	
			pw.worker_name,
			pm.member_name,
	
			cm.message_id,
			cm.sending_date,
			cm.message
		');
		$this->db->from('chat pc');
		$this->db->join('worker pw', 'pc.worker_id = pw.worker_id', 'left');
		$this->db->join('booking_services pbs', 'pc.booking_id = pbs.booking_id', 'left');
		$this->db->join('member pm', 'pc.member_id = pm.member_id', 'left');
		$this->db->join('chat_message cm', 'pc.conversations_id = cm.conversations_id', 'left');

		if (!empty($srch['member_name'])) {
			$this->db->like('pm.member_name', $srch['member_name']);
		}
		if (!empty($srch['worker_name'])) {
			$this->db->like('pw.worker_name', $srch['worker_name']);
		}

		$this->db->group_by('pc.conversations_id');
		// $this->db->order_by('cm.sending_date', 'ASC');
		$this->db->order_by('pc.conversations_id', 'DESC');

		if ($for_list) {
			if ($limit > 0) {
				$this->db->limit($offset, $limit);
			}
			$result = $this->db->get()->result_array();

			if ($result) {
				foreach ($result as $k => $row) {
					$row['group'] = [];
					if (!empty($row['chat_member_id'])) {
						$row['group'][] = (object)[
							'member_name' => !empty($row['member_name']) ? $row['member_name'] : '',
							'user_id' => $row['chat_member_id']
						];
					}
					if (!empty($row['chat_worker_id'])) {
						$row['group'][] = (object)[
							'worker_name' => !empty($row['worker_name']) ? $row['worker_name'] : '',
							'worker_id' => $row['chat_worker_id']
						];
					}
					$result[$k] = $row;
				}
			}
		} else {
			$result = $this->db->count_all_results();
		}

		return $result;
	}

	public function getMessageChatList($room_id)
	{
		$this->db->select('c_m.message_id,c_m.is_deleted,c_m.is_edited,c_m.message,c_m.attachment,c_m.sender_id,c_m.sending_date,m.member_name as sender_name')
			->from('conversations_message as c_m')
			->join('member as m', 'c_m.sender_id=m.member_id', 'left');
		$this->db->where('c_m.conversations_id', $room_id);

		$result = $this->db->order_by('c_m.message_id', 'ASC')->get()->result();
		if ($result) {
			foreach ($result as $k => $row) {
				if ($row->is_edited) {
					$row->edited = $this->db->select('message_org,edit_date')->from('conversations_message_edited')->where('mesage_id', $row->message_id)->order_by('edit_id', 'desc')->get()->result();
				}
				$result[$k] = $row;
			}
		}
		return $result;
	}

	public function getConversationDetails($conversation_id)
	{
		$this->db->select('c.conversations_id, bs.cat_id, cn.category_name');
		$this->db->from('pref_chat as c');
		$this->db->join('booking_services as bs', 'bs.booking_id = c.booking_id', 'left');
		$this->db->join('category as cat', 'cat.category_id = bs.cat_id', 'left');
		$this->db->join("category_names as cn", "cn.category_id = cat.category_id AND cn.category_lang = 'en'", 'left');
		$this->db->where('c.conversations_id', $conversation_id);
		$conversation = $this->db->get()->row();
		if (!$conversation) {
			return null;
		}

		$conversation_data = new stdClass();
		$conversation_data->conversations_id = $conversation->conversations_id;
		$conversation_data->category = (object)[
			'category_name' => $conversation->category_name ?? 'No Category',
			'category_id'   => $conversation->cat_id ?? 0
		];
		$this->db->select('m.member_name, m.member_id, w.worker_name, w.worker_id');
		$this->db->from('pref_chat as c');
		$this->db->join('member as m', 'm.member_id = c.member_id', 'left');
		$this->db->join('worker as w', 'w.worker_id = c.worker_id', 'left');
		$this->db->where('c.conversations_id', $conversation_id);
		$group_result = $this->db->get()->row();
		$conversation_data->group = [];
		if (!empty($group_result->member_id)) {
			$conversation_data->group[] = (object)[
				'member_name' => $group_result->member_name,
				'member_id'   => $group_result->member_id
			];
		}

		if (!empty($group_result->worker_id)) {
			$conversation_data->group[] = (object)[
				'worker_name' => $group_result->worker_name,
				'worker_id'   => $group_result->worker_id
			];
		}
		$this->db->select('
			cm.message_id,
			cm.conversations_id,
			cm.member_id,
			m.member_name,
			cm.worker_id,
			w.worker_name,
			cm.message,
			cm.sending_date
		');
		$this->db->from('pref_chat_message as cm');
		$this->db->join('member as m', 'm.member_id = cm.member_id', 'left');
		$this->db->join('worker as w', 'w.worker_id = cm.worker_id', 'left');
		$this->db->where('cm.conversations_id', $conversation_id);
		$this->db->order_by('cm.sending_date', 'ASC');
		$messages = $this->db->get()->result();
		$conversation_data->conversations = [];
		foreach ($messages as $msg) {
			if (!empty($msg->member_id)) {
				$sender_name = $msg->member_name;
				$sender_type = 'member';
			} elseif (!empty($msg->worker_id)) {
				$sender_name = $msg->worker_name;
				$sender_type = 'worker';
			} else {
				$sender_name = 'Unknown';
				$sender_type = 'unknown';
			}
			$conversation_data->conversations[] = (object)[
				'message_id'       => $msg->message_id,
				'conversations_id' => $msg->conversations_id,
				'member_id'        => $msg->member_id,
				'sender_name'      => $sender_name,
				'sender_type'      => $sender_type,
				'message'          => $msg->message,
				'sending_date'     => $msg->sending_date
			];
			if (!empty($msg->worker_id)) {
				$conversation_data->conversations[] = (object)[
					'message_id'       => $msg->message_id,
					'conversations_id' => $msg->conversations_id,
					'worker_id'        => $msg->worker_id,
					'worker_name'      => $msg->worker_name,
					'message'          => $msg->message,
					'sending_date'     => $msg->sending_date,
					'sender_name'      => $msg->worker_name
				];
			}
		}

		return $conversation_data;
	}

	public function getConversationID($project_id = '', $member_ids = array(), $is_auth = 0)
	{
		$sender_id = $member_ids[0];
		$conversationData = getData(array(
			'select' => 'p_c.conversations_id, count(p_c_m.conversations_id) as total',
			'table' => 'conversations as p_c',
			'join' => array(array('table' => 'conversations_room as p_c_m', 'on' => 'p_c.conversations_id=p_c_m.conversations_id', 'position' => 'left')),
			'where' => array('p_c.project_id' => $project_id),
			'where_in' => array('p_c_m.user_id' => $member_ids),
			'single_row' => true,
			'group' => 'p_c_m.conversations_id',
			'having' => 'count(total)>1',

		));
		if ($conversationData) {
			$selected_conversation_id = $conversationData->conversations_id;
		} else {
			$project_conversation = array(
				'project_id' => $project_id,
				'status' => 1
			);
			$selected_conversation_id = insert_record('conversations', $project_conversation, TRUE);
			if ($selected_conversation_id) {
				$conversations_message = array(
					'conversations_id' => $selected_conversation_id,
					'sender_id' => $sender_id,
					'sending_date' => date('Y-m-d H:i:s'),
					'message' => 'Chat initiated',
				);
				$message_id = insert_record('conversations_message', $conversations_message, TRUE);
				if ($message_id) {
					$this->db->where('conversations_id', $selected_conversation_id)->update('conversations', array('last_message_id' => $message_id));
					if ($member_ids) {
						foreach ($member_ids as $member_id) {
							if ($sender_id == $member_id) {
								$is_auth_set = 1;
							} else {
								$is_auth_set = $is_auth;
							}
							$project_conversation_member = array(
								'conversations_id' => $selected_conversation_id,
								'user_id' => $member_id,
								'auth_status' => $is_auth_set,
								'last_seen_msg' => $message_id,
							);
							insert_record('conversations_room', $project_conversation_member, TRUE);
						}
					}
				}
			}
		}
		return $selected_conversation_id;
	}

	public function getConversationMessages_o($conversation_id){
		$this->db->select("
			pcm.message_id,
			pcm.conversations_id,
			pcm.member_id,
			pcm.worker_id,
			pcm.message,
			pcm.sending_date,

			CASE
				WHEN pcm.member_id IS NULL THEN 'worker'
				ELSE 'member'
			END AS sender_type
		");
		$this->db->from('pref_chat_message pcm');
		$this->db->where('pcm.conversations_id', $conversation_id);
		$this->db->order_by('pcm.sending_date', 'ASC');

		return $this->db->get()->result();
	}
	

	public function getConversationMessages($conversation_id){
		$this->db->select("
			pcm.message_id,
			pcm.conversations_id,
			pcm.member_id,
			pcm.worker_id,
			pcm.message,
			pcm.sending_date,

			IF(pcm.member_id IS NULL, 'worker', 'member') AS sender_type,
			IF(pcm.member_id IS NULL, w.worker_name, m.member_name) AS sender_name
		", FALSE);

		$this->db->from('pref_chat_message pcm');

		$this->db->join('pref_worker w', 'w.worker_id = pcm.worker_id', 'LEFT');
		$this->db->join('pref_member m', 'm.member_id = pcm.member_id', 'LEFT');

		// conversation filter
		$this->db->where('pcm.conversations_id', $conversation_id);

		// GROUPED condition
		$this->db->where("
			(
				(pcm.member_id IS NULL AND pcm.worker_id IS NOT NULL)
				OR
				(pcm.worker_id IS NULL AND pcm.member_id IS NOT NULL)
			)
		", NULL, FALSE);

		$this->db->order_by('pcm.sending_date', 'ASC');

		return $this->db->get()->result();
	}
}
