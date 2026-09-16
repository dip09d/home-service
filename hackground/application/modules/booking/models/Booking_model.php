<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model{
	
	private $table , $primary_key;
	
	public function __construct(){
		$this->table = 'booking_services';
		$this->primary_key = 'id';
        return parent::__construct();
	}

	public function getList($offset = 0, $limit = 20, $for_list = TRUE)
	{   
		$admin_default_lang = admin_default_lang();
		$this->db->select('b.booking_id, b.duration_hours, b.booking_date, b.status, b.cancelled_by,b.booking_time,b.provider_religion,b.provider_gender,
		rn.religion_name AS provider_religion_name,
		m.member_name,m.member_id, w.worker_name, n.category_name AS category_name,
		scn.category_subchild_name AS subcategory_name, a.member_address_1');
		$this->db->from('booking_services b');
		$this->db->join('member m', 'b.member_id = m.member_id', 'left');
		$this->db->join('worker w', 'b.provider_id = w.worker_id', 'left');
		$this->db->join('category c', 'b.cat_id = c.category_id', 'left');
		$this->db->join('category_names n', "c.category_id = n.category_id AND n.category_lang = '".$admin_default_lang."'", 'left');
		$this->db->join('category_subchild sc', 'b.sub_cat_id = sc.category_subchild_id', 'left');
		$this->db->join('category_subchild_names scn', "sc.category_subchild_id = scn.category_subchild_id AND scn.category_subchild_lang = '".$admin_default_lang."'", 'left');
		$this->db->join('member_address a', 'b.address_id = a.member_address_id', 'left');
		$this->db->join('religion_names rn', "b.provider_religion = rn.religion_id AND rn.religion_lang = 'en'", 'left');
		$this->db->where('b.status !=', -1);
		$status = $this->input->get('status');         
		$term = $this->input->get('term');            
		$sub_category = $this->input->get('sub_category'); 
		$member_id = $this->input->get('member_id'); 
		$booking_id = $this->input->get('booking_id'); 
		$daterange = $this->input->get('daterange'); 
		if (!empty($daterange)) {
			$daterange_arr = explode(' - ', $daterange);
			if (count($daterange_arr) == 2) {
				$this->db->where('b.booking_date >=', $daterange_arr[0]);
				$this->db->where('b.booking_date <=', $daterange_arr[1]);
			}
		}
		if (!empty($member_id)) {
			$this->db->where('b.member_id', $member_id);
		}
		if (!empty($booking_id)) {
			$this->db->where('b.booking_id', $booking_id);
		}
		
		if (!empty($status) && $status != 'all') {
			$this->db->where('b.status', $status);
		}
		if (!empty($sub_category)) {
			$this->db->where('b.sub_cat_id', $sub_category);
		}
		if (!empty($term)) {
			$this->db->group_start();
			$this->db->like('m.member_name', $term);
			$this->db->or_like('w.worker_name', $term);
			$this->db->group_end();
		}
		if ($for_list) {
			$this->db->order_by('b.booking_id', 'DESC');
			if($limit > 0){
				$this->db->limit($limit, $offset);  
			}
			return $this->db->get()->result_array(); 
		} else {
			return $this->db->count_all_results();
		}
	}


	public function delete_booking($booking_id)
	{
		$this->db->set('status', -1);
		$this->db->where('booking_id', $booking_id);
		return $this->db->update('booking_services');
	}

	public function getSubCategories(){
		$this->db->select('sc.*, scn.category_subchild_name');
		$this->db->from('pref_category_subchild sc');
		$this->db->join('pref_category_subchild_names scn', 'sc.category_subchild_id = scn.category_subchild_id AND scn.category_subchild_lang = "en"', 'left');
		$this->db->order_by('sc.category_subchild_order', 'ASC');
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getMember()
	{
		$this->db->select('member_id, member_name');
		$this->db->from('member'); 
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getBookingDetails($booking_id) {
		$admin_default_lang = admin_default_lang();
		$this->db->select("
			b.booking_id,
			b.member_id,
			b.provider_id,
			b.cat_id,
			b.sub_cat_id,
			b.booking_date,
			b.booking_time,
			b.duration_hours,
			b.address_id,
			b.status,
			b.cancelled_by,
			m.member_name,
			m.member_email,
			m.member_phone,
			c.category_name AS category_name,
			sc.category_subchild_name AS subcategory_name,
			a.member_landmark,
			a.member_address_1,
			a.member_address_2,
			a.member_lat,
			a.member_lng,
			a.member_address_type,
			w.worker_name,
			w.worker_email,
			w.worker_phone,
			w.worker_alt_phone,
			w.worker_whatsapp,
			w.worker_register_date,
			wa.worker_address,
			wa.worker_pincode,
			wa.worker_landmark,
			wa.worker_lat,
			wa.worker_lng,
			cs.next_hour_price,
			cs.late_night_price,
			cs.price,
			i.invoice_id,
			i.invoice_number,
			i.paid_amount,
			i.invoice_date,
			i.invoice_status,
			calc.payment_data
		");
	
		$this->db->from('booking_services b');
		$this->db->join('member m', 'm.member_id = b.member_id', 'left');
		$this->db->join('worker w', 'w.worker_id = b.provider_id', 'left');
		
		$this->db->join('worker_address wa', 'wa.worker_id = w.worker_id', 'left'); 
		$this->db->join('category_names c', "c.category_id = b.cat_id AND c.category_lang = '".$admin_default_lang."'", 'left');
		$this->db->join('category_subchild cs', 'cs.category_subchild_id = b.sub_cat_id', 'left'); 
		$this->db->join('category_subchild_names sc', "sc.category_subchild_id = b.sub_cat_id AND sc.category_subchild_lang = '".$admin_default_lang."'", 'left');
		// $this->db->join('member_address a', 'a.member_id = b.member_id', 'left');
		$this->db->join('member_address a', 'a.member_address_id = b.address_id', 'left');
		// Invoice 
        $this->db->join('pref_invoice i','i.invoice_order_id = b.booking_id','left');
		$this->db->join('booking_calculation_data calc', 'i.invoice_id = calc.invoice_id', 'left');
		$this->db->where('b.booking_id', $booking_id);
	
		$query = $this->db->get();
		if (!$query) {
			$error = $this->db->error();
			die("DATABASE ERROR: " . $error['message'] . "<br>QUERY: " . $this->db->last_query());
		}
		return $query->row(); 
	}
	public function getBookingStartEndTime($order_id){
		$this->db->select("*");
		$this->db->from('worker_time_log');
		$this->db->where('order_id', $order_id);
		$query = $this->db->get();
		return $query->row(); 
	}
	public function getListReview($srch=array(), $limit=0, $offset=20, $for_list=TRUE){
		$admin_default_lang = admin_default_lang();
		$this->db->select('cr.review_id,cr.project_id,cr.review_by,cr.review_to,cr.for_quality,cr.for_deadlines,cr.for_communication,cr.review_status,cr.is_display_public,cr.review_comments,cr.review_date,cr.average_review,b.member_name,
        w.worker_name,')
			->from('contract_reviews cr')
			->join('member b', 'b.member_id = cr.review_by', 'left')
			->join('worker w', 'w.worker_id = cr.review_to', 'left');
	
		
		$this->db->where('review_status', 1);	
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('review_id', 'DESC')->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		
		return $result;
	}

	public function getreview($booking_id){ 
	
	 	$this->db->select('c_r.review_id,c_r.average_review');		 
		$this->db->from('contract_reviews as c_r');
		$this->db->where('c_r.review_status',1);
		$this->db->where('c_r.project_id',$booking_id);
		$result = $this->db->get()->row();
		return $result;
	}
	public function getWorkerList($booking_id){
		$sub_cat_id = getField('sub_cat_id','booking_services','booking_id',$booking_id);
		$this->db->select('ws.worker_id,w.worker_name');		 
		$this->db->from('pref_worker_service as ws');
		$this->db->join('worker w', 'w.worker_id = ws.worker_id', 'left');
		$this->db->where('ws.category_subchild_id',$sub_cat_id);
		$this->db->where('w.login_status',1);
		$this->db->group_by('ws.worker_id');
		$result = $this->db->get()->result_array();
		return $result;
	}
	public function update_booking_status($worker_id,$booking_id){
		$updata = array(
			'status' => 2,
			'provider_id' => $worker_id,
		);

		$this->db->where('booking_id', $booking_id);
		$this->db->where('status', 1);
		$this->db->or_where('status', 2);
		$updated = $this->db->update('booking_services', $updata);
		if($updated){
			// check if actually changed rows
			if ($this->db->affected_rows() > 0) {
				return [
					'status' => 1,
					'message' => 'Booking updated successfully'
				];
			} else {
				return [
					'status' => 0,
					'message' => 'No changes made'
				];
			}
		} else {
			return [
				'status' => 0,
				'message' => 'Update failed'
			];
		}
	}
	public function cancel_booking($booking_id)
	{
		$data = array(
			'status'       => 5,
			'updated_at'   => date('Y-m-d H:i:s'),
			'cancelled_by' => 'A',
		);
		$this->db->where('booking_id', $booking_id);
		return $this->db->update('booking_services', $data);
	}
	public function sendpush_OneSignal($fields,$user_id='',$type=''){
        $player_ids=[];
		if($type=='W'){
			$memberDevice=getData(array(
				'select'=>'m_d.device_type,m_d.device_token',
				'table'=>'member_device m_d',
				'where'=>array('m_d.worker_id'=>$user_id),
			));
		}else{
			$memberDevice=getData(array(
					'select'=>'m_d.device_type,m_d.device_token',
					'table'=>'member_device m_d',
					'where'=>array('m_d.member_id'=>$user_id),
				));
				
		}
        		
        if($memberDevice){
            foreach($memberDevice as $k=>$t){
                $player_ids[]=$t->device_token;
            }
        }
		
        if($player_ids){
            //$fields['player_ids']=$player_ids;
            $fields['player_ids'] = array_filter($player_ids);
            if($fields['player_ids']){
                return $this->sendOneSignalNotification($fields);
            }else{
                return [];
            }
            
        }
        return [];
    }
	public function sendOneSignalNotification($oneSignalData){
		$onesignal_app_id = get_setting('onesignal_app_id');
    	$rest_api_key =  get_setting('onesignal_rest_api_key');
		// $onesignal_app_id = get_option_value('onesignal_app_id');
		// $rest_api_key = get_option_value('rest_api_key');
		$content = [
            "en" => $oneSignalData['content']
        ];

        $headings = [
            "en" => $oneSignalData['heading']
        ];
		
		$fields = [
            'app_id' => $onesignal_app_id,
            'include_subscription_ids' => $oneSignalData['player_ids'], // Array of OneSignal user IDs (device tokens)
            'headings' => $headings,
            'contents' => $content,
			'data' => $oneSignalData['data'],
        ];
        // print_r($fields);

        $fields = json_encode($fields);
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Bearer ' . $rest_api_key
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
	}

}


