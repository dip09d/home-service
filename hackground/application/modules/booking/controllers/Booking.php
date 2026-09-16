<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('Booking_model', 'booking_model');
		$this->data['table'] = 'booking_services';
		$this->data['primary_key'] = 'id';
		parent::__construct();
		
		// admin_log_check();
	}

	public function list_record(){
		$this->load->library('pagination');
		$per_page = 20;
		$offset = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
		$total_rows = $this->booking_model->getList(0, 0, FALSE);
		$this->data['main_title'] = 'Booking Management';
		$this->data['second_title'] = 'All Booking List';
		$this->data['title'] = 'Booking List';
		$breadcrumb = array(
			array(
				'name' => 'Booking List',
				'path' => '',
			),
		);
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['bookings'] = $this->booking_model->getList($offset, $per_page, TRUE);
		// echo "<pre>";
		// print_r($this->data['bookings']);
		// exit;
		$this->data['subcategory'] = $this->booking_model->getSubCategories();
		$this->data['members'] = $this->booking_model->getMember();
		
		$config['base_url'] = current_url(); 
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
	
		$this->pagination->initialize($config);
		$this->data['links'] = $this->pagination->create_links();
	
	
		$this->layout->view('booking-list', $this->data);
	}

	public function destroy() {
		$booking_id = $this->input->post('id');
		if ($this->booking_model->delete_booking($booking_id)) {
			echo json_encode(['success' => true, 'message' => 'Booking deleted successfully.']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Failed to delete booking.']);
		}
	}

	public function export_csv(){
		$this->load->helper('csv');
	
		$file_name = "Booking-List_" . date('d_M_Y') . ".csv";
	
		$array = array();
		$array[] = array(
			"ID", 
			"Member Name", 
			"Worker Name", 
			"Category Name", 
			"Sub Category Name",
			"Booking Date", 
			"Duration Hour", 
			"Address", 
			"Status"
		);
		$list = $this->booking_model->getList(0, 0, TRUE);
	
		$status_map = array(
			1 => 'Pending',
			2 => 'Accepted',
			3 => 'Progress',
			4 => 'Completed',
			5 => 'Cancelled'
		);
	
		if($list){
			foreach($list as $v){
				$status = isset($status_map[$v['status']]) ? $status_map[$v['status']] : 'Unknown';
				$address = isset($v['member_address_1']) ? $v['member_address_1'] : '';
	
				$array[] = array(
					$v['booking_id'],
					isset($v['member_name']) ? $v['member_name'] : '',
					isset($v['worker_name']) ? $v['worker_name'] : '',
					isset($v['category_name']) ? $v['category_name'] : '',
					isset($v['subcategory_name']) ? $v['subcategory_name'] : '',
					isset($v['booking_date']) ? $v['booking_date'] : '',
					isset($v['duration_hours']) ? $v['duration_hours'] : '',
					$address,
					$status
				);
			}
		}
	
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $file_name . '"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo array_to_csv($array, $file_name);
		exit; 
	}

	public function getDetails($booking_id){

		$breadcrumb = array(
			array(
				'name' => 'Booking List',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['booking'] = $this->booking_model->getBookingDetails($booking_id);
		$this->data['booking_start_end_time'] = $this->booking_model->getBookingStartEndTime($booking_id);
		$this->data['reviews'] = $this->booking_model->getreview($booking_id);
		// echo '<pre>'; print_r($this->data['reviews']); die;
		$this->data['main_title'] = 'Booking Details Management';
		$this->data['title'] = 'Booking Details';
		$this->data['cus_title'] = 'Customer Details';
		$this->data['wor_title'] = 'Worker Details';
		// echo "<pre>";
		// print_r($this->data['booking']);
		// die;
		$this->layout->view('booking-details', $this->data);
	}

	public function list_review(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Review Lists';
		$this->data['second_title'] = 'Reviews';
		$this->data['title'] = 'Reviews';
		$breadcrumb = array(
			array(
				'name' => 'Reviews',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->booking_model->getListReview($srch, $limit, $offset);
		// echo '<pre>'; print_r($this->data['list']); die;
		$this->data['list_total'] = $this->booking_model->getListReview($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_review');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->layout->view('list_reviews', $this->data);
       
	}

	public function load_ajax_page(){
		$booking_id = get('booking_id');
		$this->data['booking_id']= $booking_id;
		$this->data['form_action'] = base_url($this->data['curr_controller'].'assign_providers');
		$this->data['worker_list'] = $this->booking_model->getWorkerList($booking_id);
		// echo $this->db->last_query(); die;
		//print_r($this->data['detail']);
		$this->data['title'] = 'Assign Provider';
		$this->load->view('ajax_page', $this->data);
	}
	public function assign_providers(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('worker_id', 'worker id', 'required');
			if($this->form_validation->run()){
				$booking_id = $this->input->post('booking_id');
				$worker_id = $this->input->post('worker_id');
				$updated = $this->booking_model->update_booking_status($worker_id,$booking_id);
				if($updated['status'] == 1){
					
					$bookingData=getData(array(
						'select'=>'a.booking_date,a.booking_time,a.duration_hours,a.member_id',
						'table'=>'booking_services a',
						'where'=>array('a.booking_id'=>$booking_id),
						'single_row'=>true,
					));
					// echo $this->db->last_query(); die;
					// otp for start job
					delete_record('worker_otp',array('order_id'=>$booking_id));
					$otpstart = rand(1000,9999);
					$otpData = array(
						'order_id'=> $booking_id,
						'member_id'=> $bookingData->member_id,
						'provider_id'=> $worker_id,
						'otp'=> $otpstart,
						'otp_type'=> 'orderotp_start'
					);
					$id=insert_record('worker_otp',$otpData,TRUE);
					$otpend = rand(1000,9999);
					$otpDataEnd = array(
						'order_id'=> $booking_id,
						'member_id'=> $bookingData->member_id,
						'provider_id'=> $worker_id,
						'otp'=> $otpend,
						'otp_type'=> 'orderotp_end'
					);
					$id=insert_record('worker_otp',$otpDataEnd,TRUE);
					// echo $this->db->last_query(); die;
					// storing provider data for unavailable
					// Merge date + time
					$startDateTime = date(
						'Y-m-d H:i:s',
						strtotime($bookingData->booking_date . ' ' . $bookingData->booking_time)
					);
					// Calculate end time using duration
					$endDateTime = date(
						'Y-m-d H:i:s',
						strtotime($startDateTime . ' +' . $bookingData->duration_hours . ' hours')
					);
					delete_record('providers_unavailablity',array('booking_id'=>$booking_id));
					// echo $this->db->last_query(); die;
					$insUnavilable = array(
						'worker_id' => $worker_id,
						'booking_id' => $booking_id,
						'start_time' => $startDateTime,
						'end_time' => $endDateTime,
					);
					// echo '<pre>'; print_r($insUnavilable); die;
					$id=insert_record('providers_unavailablity',$insUnavilable,TRUE);
					// echo $this->db->last_query(); die;
					// for accepted
					$this->load->library('pusher');
					$pusher=$this->pusher->load();
					$pusherData=array(
						'order_id'=>$booking_id,
						'provider_id'=>$worker_id,
					);
					$channel_id = 'booking_accepted_'.$booking_id;
					$res=$pusher->trigger($channel_id, 'booking-accepted',$pusherData);
					// for expire
					// $pusherexp=$this->pusher->load();
					// $pusherExpireData=array(
					// 	'order_id'=>$booking_id,
					// );
					// $channel_id = 'expire_booking_request';
					// $res=$pusherexp->trigger($channel_id, 'booking-accepted',$pusherExpireData);
					
					// $this->ReturnStatus = 1;
					// $msg['data'] = array(
					// 	'acceptPusherData' => $pusherData,
					// 	'expirePusherData' => $pusherExpireData,
					// );
					// echo '<pre>'; print_r($response); die;
					
					$this->api->cmd('reload');
				}
				
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	public function cancel_booking() {
		$booking_id = $this->input->post('booking_id');
		if ($this->booking_model->cancel_booking($booking_id)) {
			$member_id = getField('member_id', 'booking_services', 'booking_id', $booking_id);	
			$oneSignalData= array(
				'heading' => 'BOOKING CANCELLED',
				'content' => 'Sorry your booking has been cancelled. No available providers at this moment.',
				'data' => [
					'screen' => 'booking_cancelled',
					'channel_id' => $booking_id,
				]
			);
			$msg['onesignal']=$this->booking_model->sendpush_OneSignal($oneSignalData,$member_id,'');

			echo json_encode(['success' => true, 'message' => 'Booking cancelled successfully.']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Failed to cancel booking.']);
		}
	}
	
}


