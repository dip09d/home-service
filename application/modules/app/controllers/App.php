<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("access-control-allow-origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header('content-type: application/json; charset=utf-8');

require APPPATH.'libraries/MX_Rest.php';
class App extends MX_Rest{
	private $worker_id;
	private $member_id;
	private $ReturnCode;
	private $ReturnStatus;
	private $ResponseData;
	function __construct() {
		if($this->input->get('originatesoft_lang')){
			$langS=get('originatesoft_lang');
			$alllanguage=explode(',',get_setting('language'));
			if(in_array($langS,$alllanguage)){
				$this->session->set_userdata('current_lang',$langS);
			} 
		}
		if($this->input->get('member_id') && $this->input->get('member_id')>0){
			$this->member_id=$this->input->get('member_id');
            // $is_employer=$this->auto_model->getFeild('is_employer','member','member_id',$this->member_id);
			// $this->account_type=($is_employer==1?'E':'F');
			$result[]=(object)[
				'member_id'=>$this->member_id,
				// 'account_type'=>$this->account_type,
			];
			$this->session->set_userdata('user', $result);
		}
		if($this->input->get('worker_id') && $this->input->get('worker_id')>0){
			$this->worker_id=$this->input->get('worker_id');
			$result[]=(object)[
				'worker_id'=>$this->worker_id,
			];
			$this->session->set_userdata('provider', $result);
		}
		$this->load->library('form_validation');
	 	$this->ReturnStatus=0;
	 	$this->ReturnCode=200;
	 	$this->ResponseData=array();
        parent::__construct();
	}	
	public function get_index(){
     $this->response(array(
        "status" => 1,
        "message" => "Some demo test message only for testing purpose"
      ) , 200);
    }

	public function post_login(){ // user/customer login
		$i=0;
		$msg=array();
	
		$this->form_validation->set_rules('step', 'step', 'required');
		if(post('step') == 1 || post('step') == 2){
			$this->form_validation->set_rules('mobile', 'mobile', 'required');
		}
		if(post('step') == 2){
			$this->form_validation->set_rules('otp', 'otp', 'required');
		}
		
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}
		if($i==0){
    		$this->load->model('app_model','app');
			if(post('step') == 1){
				$result = $this->app->send_otp(post('mobile'));
				if($result['status'] == 1){
					$this->ReturnStatus = 1;
					$msg['message'] = "OTP sent successfully";
					// otp for test
					$msg['otp'] = $result['otp']; 

				}else{
					$msg['errors'] = $result['errors'];
					$msg['otp'] = $result['otp'];
				}
			}

			if(post('step') == 2){
				$postdata=array(
				'mobile'=>post('mobile'),
				'otp'=>post('otp'),
				);
				// $checkLogin=$this->app->login($postdata);
				// Static OTP bypass for Play Store review (test number only)
				if(post('mobile') == '8436500454' && post('otp') == '8888'){
					$checkLogin = $this->app->login_with_static_otp(post('mobile'));
				} else {
					$checkLogin = $this->app->login($postdata);
				}
				if($checkLogin && $checkLogin['status']==1){
					$this->ReturnStatus=1;
					$msg['data']=$checkLogin['data'];
				}else{
					$msg['errors']=$checkLogin['errors'];
				}
			}


    		
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }

	public function post_login_serviceProvider(){ // provider login
		$i=0;
		$msg=array();
	
		$this->form_validation->set_rules('step', 'step', 'required');
		if(post('step') == 1 || post('step') == 2){
			$this->form_validation->set_rules('mobile', 'mobile', 'required');
		}
		if(post('step') == 2){
			$this->form_validation->set_rules('otp', 'otp', 'required');
		}
		
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}
		if($i==0){
    		$this->load->model('app_model','app');
			if(post('step') == 1){
				$result = $this->app->send_otp_P(post('mobile'));
				if($result['status'] == 1){
					$this->ReturnStatus = 1;
					$msg['message'] = "OTP sent successfully";
					// otp for test
					$msg['otp'] = $result['otp']; 

				}else{
					$msg['errors'] = $result['errors'];
					$msg['otp'] = $result['otp'];
				}
			}

			if(post('step') == 2){
				$postdata=array(
				'mobile'=>post('mobile'),
				'otp'=>post('otp'),
				);
				// Static OTP bypass for Play Store review (test provider number only)
				if(post('mobile') == '8927426099' && post('otp') == '9999'){
					$checkLogin = $this->app->login_P_with_static_otp(post('mobile'));
				} else {
					$checkLogin=$this->app->login_P($postdata);
				}
				if($checkLogin && $checkLogin['status']==1){
					$this->ReturnStatus=1;
					$msg['data']=$checkLogin['data'];
				}else{
					$msg['errors']=$checkLogin['errors'];
				}
			}


    		
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }

	// public function post_signupUser(){
	// 	$i=0;
	// 	$msg=array();
	// 	$this->form_validation->set_rules('first_name', 'First name', 'required|trim|xss_clean');
	// 	$this->form_validation->set_rules('last_name', 'Last name', 'required|trim|xss_clean');
	// 	$this->form_validation->set_rules('number', 'Number', 'required|trim|xss_clean|is_unique[member.member_phone]',array('is_unique' => 'This number already exists.'));
		
	// 	if($this->form_validation->run( )== FALSE){
	// 		$error=validation_errors_array();
	// 		if($error){
	// 			foreach($error as $key=>$val){
	// 				$msg['status'] = 'FAIL';
	//     			$msg['errors'][$i]['id'] = $key;
	// 				$msg['errors'][$i]['message'] = $val;
	//    				$i++;
	// 			}
	// 		}
	// 	}
	// 	if($i==0){
    // 		$this->load->model('app_model','app');
    // 		$checkLogin=$this->app->signup();
    // 		if($checkLogin && $checkLogin['status']==1){
    // 			$this->ReturnStatus=1;
	// 			$msg['data']=$checkLogin['data'];
	// 			$msg['code']=$checkLogin['otp'];
	// 		}else{
	// 			$msg['errors']=$checkLogin['errors'];
	// 		}
    		
    // 	}
	// 	$this->ResponseData=$msg;
	// 	$this->response(array(
	// 		"status" =>$this->ReturnStatus,
	// 		"response" =>$this->ResponseData
	// 	) , $this->ReturnCode);
    // }

	public function get_home(){
		$msg=array();
		$this->load->model('app_model','app');
		$all_service= $this->app->getServices();
		$aya_service= $this->app->getAyaServices();
		$categories = $this->app->getParentCategories();
		// echo '<pre>'; print_R($aya_service); die;
		$booking_list = [];
		$status_map = [];
		if($this->member_id){
			$status_map = [
				1 => 'Pending',
				2 => 'Accepted',
				3 => 'Progress',
				4 => 'Completed',
				5 => 'Cancelled',
			];
			
			$srch['member_id']=$this->member_id;
			$booking_list=$this->app->booking_list($srch, 0, 5);
			if($booking_list){
				foreach($booking_list as $k=>$row){
					$row['logo']=getWorkerLogo($row['provider_id']);
					if($row['invoice_status'] == 1 ){
						$row['is_payment']=1;
					}else{
						$row['is_payment']=0;
					}
					$booking_list[$k]=$row;
				}
			}
		}
		

		$msg['data'] = array(
			'categories'=> $categories,
			'aya_services'=> $aya_service,
			'services'=> $all_service,
			'status_map'=> $status_map,
			'booking_list'=> $booking_list,
			);
		$this->ReturnStatus =1;
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}

	public function get_subcategories(){
		$cat_id = $this->input->get('cat_id');
		if(empty($cat_id)){
			$this->ReturnStatus = 0;
			$this->ResponseData = ['message' => 'Category ID is required'];
		} else {
			$this->load->model('app_model','app');
			$subcategories = $this->app->getSubCategoriesByParent($cat_id);
			
			$this->ReturnStatus = 1;
			$this->ResponseData = [
				'subcategories' => $subcategories
			];
		}
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}

	public function post_serviceDetails(){
		$msg=array();
		$category_subchild_id=$this->input->post('category_subchild_id');
		$this->load->model('app_model','app');
		$details= $this->app->getSubCategoryDetails($category_subchild_id);
		$details['workerCount'] = $this->app->getWorkerCount($category_subchild_id);
		$msg['data'] = array('details'=> $details);
		$this->ReturnStatus =1;
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}

	public function post_sendSignupOtp(){
		$i=0;
		$msg=array();
		// $this->form_validation->set_rules('first_name', 'First name', 'required|trim|xss_clean');
		// $this->form_validation->set_rules('last_name', 'Last name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('number', 'Number', 'required|trim|xss_clean|is_unique[member.member_phone]',array('is_unique' => 'This number already exists.'));
		
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}
		if($i==0){
    		$this->load->model('app_model','app');
    		$checkLogin=$this->app->signupotp();
			// echo '<pre>'; print_r($checkLogin); die;
    		if($checkLogin && $checkLogin['status']==1){
    			$this->ReturnStatus=1;
				$msg['data']=$checkLogin['data'];
				$msg['code']=$checkLogin['otp'];
			}else{
				$msg['errors']=$checkLogin['errors'];
			}
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function post_verify_otp(){
		$i=0;
		$msg=array();
		$this->form_validation->set_rules('first_name', 'First name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('otp', 'otp', 'required|trim|xss_clean');
		$this->form_validation->set_rules('number', 'Number', 'required|trim|xss_clean|is_unique[member.member_phone]',array('is_unique' => 'This number already exists.'));
		
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
					$i++;
				}
			}
		}
		if($i==0){
			$this->load->model('app_model','app');
			$checkLogin=$this->app->verify_otp(post('otp'),post('number'));
			if($checkLogin && $checkLogin['status']==1){
				$this->ReturnStatus=1;
				$msg['data']=$checkLogin['data'];
			}else{
				$msg['errors']=$checkLogin['errors'];
			}
			
		}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function post_saveAddress(){
		$this->load->library('form_validation');
		$i=0;
		$msg=array();
		if($this->member_id){
			$member_id=$this->member_id;
			if($this->input->post()){
				$is_manual=post('is_manual');
				$this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
				$this->form_validation->set_rules('address', 'Address', 'required|trim|xss_clean');
				$this->form_validation->set_rules('latitude', 'Latitude', 'required|trim|xss_clean');
				$this->form_validation->set_rules('longitude', 'Longitude', 'required|trim|xss_clean');
				//$this->form_validation->set_rules('house_number', 'House No', 'required|trim|xss_clean');
				$this->form_validation->set_rules('location_type', 'Address type', 'required|trim|xss_clean');
				$this->form_validation->set_rules('city', 'city', 'required|trim|xss_clean');
				$this->form_validation->set_rules('state', 'state', 'required|trim|xss_clean');
				$this->form_validation->set_rules('pincode', 'pincode', 'required|trim|xss_clean');
				
				
				
				if ($this->form_validation->run() == FALSE){
					$error=validation_errors_array();
					if($error){
						foreach($error as $key=>$val){
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = $key;
							$msg['errors'][$i]['message'] = $val;
							$i++;
						}
					}
				}
				if($i==0){
					$customer_name=getFieldData('member_name','member','member_id',$member_id);
					$insdata = array(
						'member_id' => $member_id,
						'name' => (post('name') ? post('name'):$customer_name),
						'member_address_1' => post('address'),
						'member_address_2' => post('house_number'),
						'member_address_type' => post('location_type'),
						'member_landmark' => post('landmark'),
						'member_city' => post('city'),
						'member_state' => post('state'),
						'member_pincode' => post('pincode'),
						'member_lat' => (post('latitude') ? post('latitude'):''),
						'member_lng' => (post('longitude') ? post('longitude'):''),
						'address_status' => 1,
						
					);
					$ins = insert_record('member_address',$insdata,TRUE);
					
					if($ins){
						$this->ReturnStatus = 1;
						$msg['message'] = "Save successfully";

					}else{
						$this->ReturnStatus = 0;
						$msg['errors']['message'] = 'Cannot save';
						$i++;
					}
					
				}
				
				
			}
			
			$this->ResponseData=$msg;
			$this->response(array(
				"status" =>$this->ReturnStatus,
				"response" =>$this->ResponseData
			) , $this->ReturnCode);
		}
	}
	public function get_address(){
		$msg=array();
		if($this->member_id){
			$address_id = $this->input->get('address_id');
			$this->load->model('app_model','app');
			$address= $this->app->getAddress($address_id);
			$msg['data'] = array(
				'address_det'=> $address,
				);
			$this->ReturnStatus =1;
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function post_update_address(){
		$this->load->library('form_validation');
		$i=0;
		$msg=array();
		if($this->member_id){
			$address_id = $this->input->post('member_address_id');
			if($this->input->post()){
				$is_manual=post('is_manual');
				$this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
				$this->form_validation->set_rules('address', 'Address', 'required|trim|xss_clean');
				$this->form_validation->set_rules('latitude', 'Latitude', 'required|trim|xss_clean');
				$this->form_validation->set_rules('longitude', 'Longitude', 'required|trim|xss_clean');
				//$this->form_validation->set_rules('house_number', 'House No', 'required|trim|xss_clean');
				$this->form_validation->set_rules('location_type', 'Address type', 'required|trim|xss_clean');
				$this->form_validation->set_rules('city', 'city', 'required|trim|xss_clean');
				$this->form_validation->set_rules('state', 'state', 'required|trim|xss_clean');
				$this->form_validation->set_rules('pincode', 'pincode', 'required|trim|xss_clean');
				
				
				
				if ($this->form_validation->run() == FALSE){
					$error=validation_errors_array();
					if($error){
						foreach($error as $key=>$val){
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = $key;
							$msg['errors'][$i]['message'] = $val;
							$i++;
						}
					}
				}
				if($i==0){
					$updtdata = array(
						'name' => post('name'),
						'member_address_1' => post('address'),
						'member_address_2' => post('house_number'),
						'member_address_type' => post('location_type'),
						'member_city' => post('city'),
						'member_state' => post('state'),
						'member_landmark' => post('landmark'),
						'member_pincode' => post('pincode'),
						'member_lat' => post('latitude'),
						'member_lng' => post('longitude'),
						'address_status' => 1,
						
					);
					$this->db->where('member_address_id', $address_id);
					$updated = $this->db->update('member_address', $updtdata);
					// echo $this->db->last_query(); die;
					
					$this->ReturnStatus = 1;
					$msg['message'] = "Save successfully";

				}
				
				
			}
			
			$this->ResponseData=$msg;
			$this->response(array(
				"status" =>$this->ReturnStatus,
				"response" =>$this->ResponseData
			) , $this->ReturnCode);
		}
	}
	public function post_remove_setDefault_address(){
		$this->load->library('form_validation');
		$i=0;
		$msg=array();
		if($this->member_id){
			if($this->input->post()){
				$this->form_validation->set_rules('member_address_id', 'Address id', 'required|trim|xss_clean');
				$this->form_validation->set_rules('status', 'Status', 'required|trim|xss_clean');
				
				if ($this->form_validation->run() == FALSE){
					$error=validation_errors_array();
					if($error){
						foreach($error as $key=>$val){
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = $key;
							$msg['errors'][$i]['message'] = $val;
							$i++;
						}
					}
				}
				if($i==0){
					$address_id = $this->input->post('member_address_id');
					$status = $this->input->post('status');
					$updtdata = array(
						'address_status' => $status,
					);
					$this->db->where('member_address_id', $address_id);
					$updated = $this->db->update('member_address', $updtdata);
					
					$this->ReturnStatus = 1;
					$msg['message'] = "Status Updated successfully";
				}
			}
			
			$this->ResponseData=$msg;
			$this->response(array(
				"status" =>$this->ReturnStatus,
				"response" =>$this->ResponseData
			) , $this->ReturnCode);
		}
	}
	public function post_bookingService(){
		$i=0;
		$msg=array();
		$this->form_validation->set_rules('cat_id', 'Category', 'required|trim|xss_clean');
		$this->form_validation->set_rules('sub_cat_id', 'Sub category', 'required|trim|xss_clean');
		// $this->form_validation->set_rules('provider_caste', 'Provider caste', 'required|trim|xss_clean');
		$this->form_validation->set_rules('booking_date', 'Date', 'required|trim|xss_clean');
		$this->form_validation->set_rules('booking_time', 'Time', 'required|trim|xss_clean');
		$this->form_validation->set_rules('duration_hours', 'Duration', 'required|trim|xss_clean');
		// $this->form_validation->set_rules('special_instructions', 'Instruction', 'required|trim|xss_clean');
		$this->form_validation->set_rules('address_id', 'Address', 'required|trim|xss_clean');
		// $this->form_validation->set_rules('pref_gender', 'Preferred Gender', 'required|trim|xss_clean');
		
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}
		if($i==0){
    		$this->load->model('app_model','app');
    		$booking=$this->app->book_service($this->member_id);
			// echo '<pre>'; print_r($booking); die;
    		if($booking && $booking['status']==1){
				$booking_date = post('booking_date');
				$booking_time = post('booking_time');
				$duration_hours = post('duration_hours');
				$startDateTime = date(
					'Y-m-d H:i:s',
					strtotime($booking_date . ' ' . $booking_time)
				);

				$endDateTime = date(
					'Y-m-d H:i:s',
					strtotime("+$duration_hours hours", strtotime($startDateTime))
				);
				$lat_lng = $this->app->get_lat_lng(post('address_id'));
				if (empty($lat_lng)) {
					$lat_lng = $this->db->select('name,member_landmark,member_address_1,member_address_2,member_address_type,member_mobile,member_lat as lat,member_lng as lng')
						->from('member_address')
						->where('member_address_id', post('address_id'))
						->get()->row_array();
				}
				if (empty($lat_lng)) {
					$lat_lng = [
						'name' => '',
						'member_landmark' => '',
						'member_address_1' => '',
						'member_address_2' => '',
						'member_address_type' => '',
						'member_mobile' => '',
						'lat' => 0.0,
						'lng' => 0.0
					];
				}
				$worker_dist = $worker_ids = $this->app->getWorkerList(post('sub_cat_id'),$startDateTime,$endDateTime,$lat_lng['lat'],$lat_lng['lng'],post('pref_gender'));
				//   if($worker_dist){
				// 	$worker_dist[] = [
				// 		'worker_id' => 20,
				// 		'distance'  => 0
				// 	];
				// } 
				//echo '<pre>'; print_r($worker_ids); 
				// echo $this->db->last_query(); die;
				// Add static worker with distance 0
				
				


				$ids = ($worker_ids  ? array_column($worker_ids, 'worker_id'):[]);
				// $ids[]="20";
				$this->load->library('pusher');
				$pusher=$this->pusher->load();
				$customer_name=getFieldData('member_name','member','member_id',$this->member_id);
				$price=getFieldData('price','category_subchild','category_subchild_id',post('sub_cat_id'));
				$get_cat_name = $this->app->get_subchild_name(post('sub_cat_id'));
 				//echo $this->db->last_query(); die;
				
				$pusherData=array(
					'worker_ids'=>$ids,
					'worker_ids_dist'=>$worker_dist,
					'order_id'=>$booking['id'],
					'order_details'=>[
						'customer_name'=>$customer_name,
						'booking_date'=>$startDateTime,
						'order_id'=>$booking['id'],
						'duration'=>$duration_hours,
						'addressee_name' => $lat_lng['name'] ?? '',
						'address_1' => $lat_lng['member_address_1'] ?? '',
						'address_2' => $lat_lng['member_address_2'] ?? '',
						'landmark' => $lat_lng['member_landmark'] ?? '',
						'address_type' => $lat_lng['member_address_type'] ?? '',
						'address_phone' => $lat_lng['member_mobile'] ?? '',
						'member_lat' => $lat_lng['lat'] ?? '',
						'member_lng' => $lat_lng['lng'] ?? '',
						'price' => $price,
						'book_date'=>$booking_date,
						'book_time'=>$booking_time,
						'category'=> $get_cat_name['category_subchild_name'],
						'special_instructions' => post('special_instructions'),
					]
				);

				// Trigger on individual worker channels for clean routing
				if (!empty($ids)) {
					foreach ($ids as $wid) {
						$pusher->trigger('worker-' . $wid, 'booking_service', $pusherData);
					}
				}

				// Also trigger on 'booking_request' channel with filtered worker_ids for existing client app builds
				$pusher->trigger('booking_request', 'booking_service', $pusherData);

				$oneSignalData= array(
					'heading' => 'New Booking',
					'content' => 'You have a new booking request',
					'data' => [
						'screen' => 'new_booking',
						'channel_id' => $booking['id'],
						'order_id'   => $booking['id'],
					]
				);
 				$msg['onesignal']=$this->app->send_notification_booking($oneSignalData,$ids);
    			$this->ReturnStatus=1;
				$msg['message'] = 'Successfully booked';
				$msg['booking_id']       = $booking['id'];
				$msg['provider_id']      = 0; // Remains 0 until a matched worker accepts the booking
				$msg['worker_ids']       = $ids;
				$msg['worker_count']     = count($ids);
				$msg['data']             = $pusherData;
			}

    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	/*public function sendSignupOtpPro(){
		$i=0;
		$msg=array();
		// $this->form_validation->set_rules('first_name', 'First name', 'required|trim|xss_clean');
		// $this->form_validation->set_rules('last_name', 'Last name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('number', 'Number', 'required|trim|xss_clean|is_unique[member.member_phone]',array('is_unique' => 'This number already exists.'));
		
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}
		if($i==0){
    		$this->load->model('app_model','app');
    		$checkLogin=$this->app->signupotp();
			// echo '<pre>'; print_r($checkLogin); die;
    		if($checkLogin && $checkLogin['status']==1){
    			$this->ReturnStatus=1;
				$msg['data']=$checkLogin['data'];
				$msg['code']=$checkLogin['otp'];
			}else{
				$msg['errors']=$checkLogin['errors'];
			}
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}*/
	public function post_signupPro(){
		$this->load->library('form_validation');
		$this->load->library('bcrypt');
		$i=0;
		$msg=array();
		$step=1;
		if($this->input->post()){
			$this->form_validation->set_rules('step', 'Step', 'required|trim|xss_clean');
			if(post('step')){
				$step=post('step');
			}
			if($step>=1){
				$this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
				$this->form_validation->set_rules('gender', 'gender', 'required|trim|xss_clean');
				$this->form_validation->set_rules('dob', 'dob', 'required|trim|xss_clean');
				$this->form_validation->set_rules('religion', 'religion', 'required|trim|xss_clean');
				$this->form_validation->set_rules('phone', 'phone', 'required|trim|xss_clean|xss_clean|regex_match[/^[0-9]{10}$/]|is_unique[worker.worker_phone]',array('is_unique' => 'This phone already exists.'));
				$this->form_validation->set_rules('alt_phone', 'alternative phone', 'trim|xss_clean|xss_clean|regex_match[/^[0-9]{10}$/]');
				
				$this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
				$this->form_validation->set_rules('whatsapp', 'whatsapp', 'trim|xss_clean|xss_clean|regex_match[/^[0-9]{10}$/]');
			}
			if($step==2){
				$this->form_validation->set_rules('worker_address', 'address', 'required|trim|xss_clean');
				$this->form_validation->set_rules('worker_city', 'city', 'required|trim|xss_clean');
				$this->form_validation->set_rules('worker_state', 'state', 'required|trim|xss_clean');
				$this->form_validation->set_rules('worker_pincode', 'pincode', 'required|trim|xss_clean|regex_match[/^[0-9]{6}$/]');
				$this->form_validation->set_rules('worker_landmark', 'landmark', 'required|trim|xss_clean');
				// $this->form_validation->set_rules('agree_term', 'term', 'required|trim|xss_clean');
			}
			
			
			if($this->form_validation->run( )== FALSE){
				$error=validation_errors_array();
				if($error){
					foreach($error as $key=>$val){
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = $key;
						$msg['errors'][$i]['message'] = $val;
						$i++;
					}
				}
			}
			if($i==0){
				if($step==1){
					$msg['status'] = 'OK';
					$msg['calback'] = 'nextstep';
					$msg['calbackdata'] = array('phone'=>post('phone'));
					
				}elseif($step==2){
					$email=trim(post('email'));
					$name=trim(post('name'));
					$dataPost=array(
						'worker_name'=>$name,
						'worker_email'=>$email,
						'worker_register_date'=>gmdate('Y-m-d H:i:s'),
						'worker_phone'=>trim(post('phone')),
						'worker_alt_phone'=>trim(post('alt_phone')),
						'worker_whatsapp'=>trim(post('whatsapp')),
						'worker_gender'=>trim(post('gender')),
						'worker_dob'=>trim(post('dob')),
						'worker_religion'=>trim(post('religion')),
					);

					
					
					$worker_id=insert_record('worker',$dataPost,TRUE);
					if($worker_id){
						$worker_address=[
							'worker_id'=>$worker_id,
							'worker_state'=>post('worker_state'),
							'worker_city'=>post('worker_city'),
							'worker_address'=>post('worker_address'),
							'worker_flat'=>post('worker_flat'),
							'worker_street'=>post('worker_street'),
							'worker_pincode'=>post('worker_pincode'),
							'worker_landmark'=>post('worker_landmark'),
						];
						if(post('lat') && post('lng')){
							$worker_address['worker_lat']=post('lat');
							$worker_address['worker_lng']=post('lng');
							
						}

						insert_record('worker_address',$worker_address,FALSE);
						$profile_name=$dataPost['worker_name'];
						insert_record('wallet',array('worker_id'=>$worker_id,'title'=>$profile_name,'balance'=>0),FALSE);
						if(trim(post('logo'))){
							$dataimg=$this->input->post("logo",FALSE);
							$formatdata=explode(';base64,',$dataimg);
							$image = base64_decode($formatdata[1]);
							$image_name = md5($worker_id.'-'.time());
							$filename = $image_name . '.' . 'png';
							$path = UPLOAD_PATH.'worker-logo/';
							@file_put_contents($path.$filename, $image);


							insert(array('table'=>'worker_logo','data'=>array('worker_id'=>$worker_id,'logo'=>$filename,'status'=>1,'reg_date'=>date('Y-m-d H:i:s'))),TRUE);
						}
						$services=post('service_id');
						// echo '<pre>'; print_R($services); die;
						// insert_record('worker_service',['worker_id'=>$worker_id,'category_subchild_id'=>$services,'category_subchild_order'=>0],FALSE);
						if($services){
							foreach($services as $k=>$item){
								insert_record('worker_service',['worker_id'=>$worker_id,'category_subchild_id'=>$item,'category_subchild_order'=>$k],FALSE);
							}
						}
						
						$template='new-registration-worker';
						$data_parse=array(
						'MEMBER_URL'=>ADMIN_URL.'worker/list_record',
						);
						//SendMail(get_setting('admin_email'),$template,$data_parse);
						$data_pase = array(
							'FULL_NAME' => $profile_name
						);
						// $this->admin_notification_model->parse('admin-worker-signup', $data_pase, 'worker/list_record');
						$this->ReturnStatus=1;
						$msg['name'] = $profile_name;
						$msg['message'] = 'Successfully saved';
						// $msg['redirect'] =URL::get_link('registerSuccessURL');
						
					}else{
						$msg['errors'][$i]['id'] = 'email';
						$msg['errors'][$i]['message'] = 'Error in process';
					}
				}
					
			}		
		}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);	
	}
	public function get_signForm(){
		$msg=array();
		$this->load->model('app_model','app');
		$this->load->model('user/user_model','user');
		$states = get_state('IND');
		$religion=get_all_religion();
		
		$all_service= $this->user->getData();
		$msg['data'] = array(
				'states'=> $states,
				'religion'=> $religion,
				'all_service'=> $all_service,
				);
		$this->ReturnStatus = 1;
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);

	}
	public function get_booking_details(){
		$msg=array();
		// if($this->member_id){
			$booking_id = $this->input->get('booking_id');
			$this->load->model('app_model','app');
			$booking= $this->app->getBookingDetails($booking_id);
			$start = strtotime($booking['booking_date'].' '.$booking['booking_time']);
			$end = strtotime("+{$booking['duration_hours']} hours", $start);
			$booking_date_time = date('D, M d at h:i A', $start);
			$rate = $booking['price'].'/ Hour';
			$status_map = [
				1 => 'Pending',
				2 => 'Accepted',
				3 => 'Progress',
				4 => 'Completed',
				5 => 'Cancelled',
			];

			$status_text = $status_map[$booking['status']] ?? 'Pending';
			if($booking['invoice_status'] == 1){
				$is_payment = 1;
			}else{
				$is_payment = 0;
			}
			if($booking['provider_gender'] == 'any' || $booking['provider_gender']== ''){
				$pref_gender = '-1';
			}else{
				$pref_gender = $booking['provider_gender'];
			}
			$response = [
				"booking_id" => $booking['booking_id'],
				"status" => $booking['status'],
				// "status_text" => $status_text,
				// "booking_datetime" => $booking_date_time,
				"booking_date" => $booking['booking_date'],
				"booking_time" => $booking['booking_time'],
				"pref_gender" => $pref_gender,
				"updated_at" => $booking['updated_at'],
				"cancel_charge" => $booking['cancel_charge'],
				"duration_hours" => $booking['duration_hours'],
				"member_address_1" => $booking['member_address_1'],
				"member_address_2" => $booking['member_address_2'],
				"member_landmark" => $booking['member_landmark'],
				"member_lat" => $booking['member_lat'],
				"member_lng" => $booking['member_lng'],
				// "address" => implode(' - ', array_filter([implode(', ', array_filter([$booking['member_address_1'],$booking['member_address_2']])),$booking['member_landmark']])),
				"customer_name" => $booking['member_name'],
				"rate" => $booking['price'],
				'next_hour_price'=>$booking['next_hour_price'],
				'late_night_price'=>$booking['late_night_price'],
				"instructions" => $booking['special_instructions'],
				"category_name" => $booking['category_name'],
				"category_subchild_name" => $booking['category_subchild_name'],
				"category_subchild_thumb" => $booking['category_subchild_thumb'],
				"start_otp" => $booking['start_otp'],
				"end_otp" => $booking['end_otp'],
				"is_payment" => $is_payment,
				
			];
			// echo '<pre>'; print_r($response); die;
			$pro_det = [];
			if($booking['provider_id'] > 0){
				$start_time_db = '';
				$end_time='';
				if($booking['status'] >= 3){
					$start_time_db = getFieldData('start_time','worker_time_log','order_id',$booking_id);
					if($booking['status'] == 4){
						$end_time = getFieldData('end_time','worker_time_log','order_id',$booking_id);
					}
				}
				
				$lat = $booking['member_lat'];
				$lng = $booking['member_lng'];
				$worker_id = $booking['provider_id'];
				$lat = (float)$lat;
				$lng = (float)$lng;

				/* $member_cancel_charges=0;
				$cancel_data=$this->db->select('SUM(amount) as total')->from('member_cancel_charges')
				->where('member_id',$booking['member_id'])->where('status',0)->group_by('member_id')->get()->row();
				if($cancel_data && $cancel_data->total){
					$member_cancel_charges=$cancel_data->total;
				} */

				$this->db->select("
					worker_id,
					worker_lat,
					worker_lng,
					(
						6371 * ACOS(
							COS(RADIANS($lat))
							* COS(RADIANS(CAST(worker_lat AS DECIMAL(10,8))))
							* COS(
								RADIANS(CAST(worker_lng AS DECIMAL(10,8))) 
								- RADIANS($lng)
							)
							+ SIN(RADIANS($lat))
							* SIN(RADIANS(CAST(worker_lat AS DECIMAL(10,8))))
						)
					) AS distance
				", FALSE);

				$this->db->from('pref_worker_address');
				$this->db->where('worker_id', $worker_id);
				$this->db->where('worker_lat IS NOT NULL', NULL, FALSE);
				$this->db->where('worker_lng IS NOT NULL', NULL, FALSE);

				$query = $this->db->get();
				$row = $query->row();   

				$distance = isset($row->distance) ? round($row->distance, 2) : 0;

				$providerDetails=getData(array(
					'select'=>'a.worker_name',
					'table'=>'worker a',
					'where'=>array('a.worker_id'=>$booking['provider_id']),
					'single_row'=>true,
				));
				$providerDetailsIcard=getData(array(
					'select'=>'a.worker_name,a.date_of_joining,a.employee_id,a.icard_logo,a.designation',
					'table'=>'worker_icard a',
					'where'=>array('a.worker_id'=>$booking['provider_id'],'status'=>1),
					'single_row'=>true,
				));
				//print_r($providerDetailsIcard);

				if($providerDetailsIcard && $providerDetailsIcard->icard_logo){
					$logo=UPLOAD_HTTP_PATH.'worker-icard/'.$providerDetailsIcard->icard_logo;
				}else{
					$logo=getWorkerLogo($booking['provider_id']);
				}
				

				// echo $distance . " KM";
				$pro_det = [
					"name" => ($providerDetailsIcard ? $providerDetailsIcard->worker_name:$providerDetails->worker_name),
					"logo" => $logo,
					//"number" => getFieldData('worker_phone','worker','worker_id',$booking['provider_id']),
					"employee_id" => ($providerDetailsIcard ? $providerDetailsIcard->employee_id:''),
					"designation" => ($providerDetailsIcard ? $providerDetailsIcard->designation:''),
					"joining_date"=>($providerDetailsIcard ? date('d-M-Y',strtotime($providerDetailsIcard->date_of_joining)):''),
					"expired_date"=>($providerDetailsIcard ? date('d-M-Y',strtotime('+5 years',strtotime($providerDetailsIcard->date_of_joining))):''),
					"card_phone"   => get_setting('icard_phone') ?? '',
					"card_email"   => get_setting('icard_email') ?? '',
					"card_address" => get_setting('icard_address') ?? '',
					// "schedule" => date("M d, h:i A", strtotime($booking['booking_date'].' '.$booking['booking_time'])),
					// "price_per_hour" => "₹".$booking['price']."/h",
					// "start_time" => !empty($start_time_db)
					// 	? date("h:i A", strtotime($start_time_db))
					// 	: "",
					// "working_time" => $working_minutes." min",
					// "current_amount" => "₹".$current_amount
				
					//"price" => $booking['price'],
					"start_time" => $start_time_db,
					"end_time" => $end_time,
					//"earning_till_now" => $current_amount,
					"distance" => $distance,
					//"surge_price" => 20,
					"provider_cancel_charge" => get_setting('pro_cancel_charge'),
				];
			}else{
				$lat = $booking['member_lat'];
				$lng = $booking['member_lng'];
				$worker_id = $this->worker_id;
				$lat = (float)$lat;
				$lng = (float)$lng;

				$this->db->select("
					worker_id,
					worker_lat,
					worker_lng,
					(
						6371 * ACOS(
							COS(RADIANS($lat))
							* COS(RADIANS(CAST(worker_lat AS DECIMAL(10,8))))
							* COS(
								RADIANS(CAST(worker_lng AS DECIMAL(10,8))) 
								- RADIANS($lng)
							)
							+ SIN(RADIANS($lat))
							* SIN(RADIANS(CAST(worker_lat AS DECIMAL(10,8))))
						)
					) AS distance
				", FALSE);

				$this->db->from('worker_address');
				$this->db->where('worker_id', $worker_id);
				$this->db->where('worker_lat IS NOT NULL', NULL, FALSE);
				$this->db->where('worker_lng IS NOT NULL', NULL, FALSE);

				$query = $this->db->get();
				$row = $query->row();   

				$distance = isset($row->distance) ? round($row->distance, 2) : 0;
			}
			$inv_det = [];
			if(!empty($booking['invoice_id'])){
				$token=md5(date('Y-m-d').'-ORGUP');
        		$invoice_url=SITE_URL.'invoice/invoice_details/'.md5($booking['invoice_id']).'?auth='.$token;
				if($this->worker_id){
					$invoice_url.='&worker_id='.$this->worker_id;
				}
				$inv_det = [
					'invoice_url' => $invoice_url,
				];
			}
			$review=$this->db->select('*')->from('contract_reviews')->where('project_id',$booking_id)->get()->row();
			$calculation=[];
			if(!empty($booking['invoice_id'])){
				$payment_data=getFieldData('payment_data','booking_calculation_data','invoice_id',$booking['invoice_id']);
				if($payment_data){
					$calculation=json_decode($payment_data);
				}
			}
			$msg['data'] = array(
				'booking-det'=> $response,
				'provider-det'=> $pro_det,
				'inv_det'=> $inv_det,
				'status_map'=>$status_map,
				'review'=>$review,
				'distance'=>$distance,
				'calculation'=>$calculation
				);
			$this->ReturnStatus =1;
		// }
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function post_reschedulebooking(){
		$msg=array();
		if($this->member_id){
			$booking_id = $this->input->get('booking_id');
			$this->load->model('app_model','app');
			$booking= $this->app->save_reschedule_book($booking_id);
			$this->ReturnStatus = 1;
			// echo '<pre>'; print_r($response); die;
			$msg['message'] = 'Successfully Reschedule';
			$status = getField('status','booking_services','booking_id',$booking_id);
			if($status == 2){
				$provider_id = getField('provider_id','booking_services','booking_id',$booking_id);
				$oneSignalData= array(
					'heading' => 'Booking Reschedule Successfully',
					'content' => 'Your booking has been rescheduled',
					'data' => [
						'screen' => 'booking_rescheduled',
						'channel_id' => $booking_id,
					]
				);
				$msg['onesignal']=$this->app->sendpush_OneSignal($oneSignalData,$provider_id,'W');
			}
			$this->ReturnStatus =1;
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function get_religion(){
		$msg=array();
		$religion=get_all_religion();
		$msg['data'] = array(
				'religion'=> $religion,
				);
		$this->ReturnStatus = 1;
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function get_cancel_booking(){
		$this->load->model('app_model','app');
		$msg=array();
		if($this->member_id){
			$booking_id = $this->input->get('booking_id');
			$status = getField('status','booking_services','booking_id',$booking_id);
			$updated = $this->app->update_booking_cancel($booking_id);
			// echo $status; die;
			// echo '<pre>'; print_r($updated); die;
			// updateTable('booking_services',array('status'=>'cancelled'),array('booking_id'=>$booking_id));
			if($updated['status'] == 1){
				$this->ReturnStatus = 1;
				$msg['message'] = 'Successfully Cancelled';
				$this->load->library('pusher');
				$pusherexp=$this->pusher->load();
				$pusherExpireData=array(
					'order_id'=>$booking_id,
				);
				$channel_id = 'expire_booking_request';
				$res=$pusherexp->trigger($channel_id, 'booking-accepted',$pusherExpireData);
				// onesignal for cancel to worker
				if($status == 2){
					$oneSignalData= array(
						'heading' => 'Booking Cancelled',
						'content' => 'Your booking has been cancelled',
						'data' => [
							'screen' => 'booking_cancelled',
							'channel_id' => $booking_id,
						]
					);
					$msg['onesignal']=$this->app->sendpush_OneSignal($oneSignalData,$updated['provider_id'],'W');
					// clear unavailability table as now provider available
					delete(array('table'=>'providers_unavailablity','where'=>array('worker_id'=>$updated['provider_id'],'booking_id'=>$booking_id)));
				}
				
			}
			// echo 's'; die;
		
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function post_profile_update(){
		$this->load->library('form_validation');
		$i=0;
		$msg=array();
		if($this->member_id){
			if($this->input->post()){
				$old_number= $this->post('pre_number');
				$number= $this->post('number');
				$this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
				if($old_number != $number){
					$this->form_validation->set_rules('number', 'Number', 'required|trim|xss_clean|is_unique[member.member_phone]',array('is_unique' => 'This number already exists.'));
				}else{
					$this->form_validation->set_rules('number', 'Number', 'required|trim|xss_clean');
				}
				
				// $this->form_validation->set_rules('dob', 'DOB', 'required|trim|xss_clean');
				// $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean');
				
				
				
				
				if ($this->form_validation->run() == FALSE){
					$error=validation_errors_array();
					if($error){
						foreach($error as $key=>$val){
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = $key;
							$msg['errors'][$i]['message'] = $val;
							$i++;
						}
					}
				}
				if($i==0){
					$updtdata = array(
						'member_name' => post('name'),
						'member_phone' => post('number'),
						'member_email' => post('email'),
						'member_wanum' => post('wa_num'),
						'member_dob' => post('dob'),
						'member_gender' => post('gender'),
						
					);
					$this->db->where('member_id', $this->member_id);
					$updated = $this->db->update('member', $updtdata);
					// echo $this->db->last_query(); die;
					$this->ReturnStatus = 1;
					$msg['message'] = "Updated successfully";
				}	
			}
			
			$this->ResponseData=$msg;
			$this->response(array(
				"status" =>$this->ReturnStatus,
				"response" =>$this->ResponseData
			) , $this->ReturnCode);
		}
	}
	public function get_profile(){
		$msg=array();
		if($this->member_id){
			$this->load->model('app_model','app');
			$profile= $this->app->getProfile($this->member_id);
			$msg['data'] = array(
				'profile_det'=> $profile,
				);
			$this->ReturnStatus =1;
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	

	// provider/worker
	public function get_accept_job(){
		$msg=array();
		if($this->worker_id){
			$provider_balance = getFieldData('balance', 'wallet', 'worker_id', $this->worker_id);
			if (empty($provider_balance) || $provider_balance < 200) {
				$this->ReturnStatus = 0;
				$this->ResponseData = array('message' => 'Minimum Rs. 200 balance required in your wallet to accept the job.');
				$this->response(array("status" => $this->ReturnStatus, "response" => $this->ResponseData), $this->ReturnCode);
				return;
			}

			$booking_id = $this->input->get('booking_id');
			$this->load->model('app_model','app');
			$updated = $this->app->update_booking_status($this->worker_id,$booking_id);
			if($updated['status'] == 1){

				
				
				$bookingData=getData(array(
					'select'=>'a.booking_date,a.booking_time,a.duration_hours,a.member_id,a.sub_cat_id',
					'table'=>'booking_services a',
					'where'=>array('a.booking_id'=>$booking_id),
					'single_row'=>true,
				));


				// otp for start job
				$otpstart = rand(1000,9999);
				$otpData = array(
					'order_id'=> $booking_id,
					'member_id'=> $bookingData->member_id,
					'provider_id'=> $this->worker_id,
					'otp'=> $otpstart,
					'otp_type'=> 'orderotp_start'
				);
				$id=insert_record('worker_otp',$otpData,TRUE);
				$otpend = rand(1000,9999);
				$otpDataEnd = array(
					'order_id'=> $booking_id,
					'member_id'=> $bookingData->member_id,
					'provider_id'=> $this->worker_id,
					'otp'=> $otpend,
					'otp_type'=> 'orderotp_end'
				);
				$id=insert_record('worker_otp',$otpDataEnd,TRUE);

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

				$insUnavilable = array(
					'worker_id' => $this->worker_id,
					'booking_id' => $booking_id,
					'start_time' => $startDateTime,
					'end_time' => $endDateTime,
				);
				// echo '<pre>'; print_r($insUnavilable); die;
				$id=insert_record('providers_unavailablity',$insUnavilable,TRUE);
				// for accepted
				$this->load->library('pusher');
				$pusher=$this->pusher->load();
				$pusherData=array(
					'order_id'=>$booking_id,
					'provider_id'=>$this->worker_id,
				);
				$channel_id = 'booking_accepted_'.$booking_id;
				$res=$pusher->trigger($channel_id, 'booking-accepted',$pusherData);
				// for expire
				$pusherexp=$this->pusher->load();
				$pusherExpireData=array(
					'order_id'=>$booking_id,
				);
				$channel_id = 'expire_booking_request';
				$res=$pusherexp->trigger($channel_id, 'booking-accepted',$pusherExpireData);


				$mobile=getFieldData('member_phone','member','member_id',$bookingData->member_id);
				if($mobile){
					$worker_name=getFieldData('worker_name','worker','worker_id',$this->worker_id);
					$service_name=getFieldData('category_subchild_name','category_subchild_names','','',['category_subchild_id'=>$bookingData->sub_cat_id,'category_subchild_lang'=>'en']);
					$booking_date=date('d/m/Y',strtotime($bookingData->booking_date));
					$booking_time=date('H:i A',strtotime($bookingData->booking_time));

					$smstext='Your booking is confirmed. Worker: '.$worker_name.', Service: '.$service_name.', Date: '.$booking_date.', Time: '.$booking_time.' . Thank you! -SNAPHIVE';
					$sendSms=sendSMS($mobile,'1707177390234435834',$smstext);
				}
				
				

				
				$this->ReturnStatus = 1;
				$msg['data'] = array(
					'acceptPusherData' => $pusherData,
					'expirePusherData' => $pusherExpireData,
				);
				// echo '<pre>'; print_r($response); die;
				$msg['message'] = 'Job Accepted';
				$this->ReturnStatus =1;
			}
			
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	
	// this will request from provider
	public function post_verify_start_otp(){
		$msg=array();
		if($this->worker_id){
			$otp = $this->post('otp');
			$order_id = $this->post('order_id');
			$verifyData=getData(array(
				'select'=>'*',
				'table'=>'worker_otp a',
				'where'=>array('a.otp'=>$otp,'order_id'=>$order_id,'provider_id'=> $this->worker_id,'otp_type'=>'orderotp_start'),
				'single_row'=>true,
			));
			
			if($verifyData){
				$this->load->model('app_model','app');
				
				$check_status = getFieldData('status', 'booking_services', 'booking_id', $order_id);
				if ($check_status == 3) {
					$msg['otp'] = $otp;
					$msg['message'] = 'Job already started';
					$this->ReturnStatus = 1;
				} else {
					$updated = $this->app->update_booking_progress($order_id);
					if($updated['status'] == 1){
						// storing time log
						$bookingData=getData(array(
							'select'=>'a.booking_date,a.booking_time,a.member_id,a.booking_id',
							'table'=>'booking_services a',
							'where'=>array('a.booking_id'=>$order_id),
							'single_row'=>true,
						));
						// Merge date + time
						$startDateTime = date('Y-m-d H:i:s');

						$timeLog = array(
							'order_id'=> $order_id,
							'start_time'=> $startDateTime,
							'end_time'=> $startDateTime,
						);
						$id=insert_record('worker_time_log',$timeLog,TRUE);
						// to notify user thatjob started
						$this->load->library('pusher');
						$pusher=$this->pusher->load();
						$pusherData=array(
							'order_id'=>$order_id,
							'provider_id'=>$this->worker_id,
						);
						if ($bookingData) {
							$channel_id = 'job_started_'.$bookingData->booking_id;
							$res=$pusher->trigger($channel_id, 'job-started',$pusherData);
						}

						$msg['otp'] = $otp;
						$msg['message'] = 'Job started successfully';
						$this->ReturnStatus = 1;
					} else {
						$this->ReturnStatus = 0;
						$msg['message'] = $updated['message'] ?? 'Failed to update booking progress';
					}
				}
			} else {
				$this->ReturnStatus = 0;
				$msg['message'] = 'Invalid OTP or Order ID';
			}
		} else {
			$this->ReturnStatus = 0;
			$msg['message'] = 'Unauthorized access';
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function post_verify_end_otp(){
		$endDateTime = date('Y-m-d H:i:s');
		$msg=array();
		if($this->worker_id){
			$otp = $this->post('otp');
			$order_id = $this->post('order_id');
			$verifyData=getData(array(
				'select'=>'*',
				'table'=>'worker_otp a',
				'where'=>array('a.otp'=>$otp,'order_id'=>$order_id,'provider_id'=> $this->worker_id,'otp_type'=>'orderotp_end'),
				'single_row'=>true,
			));
			// echo $this->db->last_query(); die;
			if($verifyData){
				// delete(array('table'=>'worker_otp','where'=>array('otp'=>$otp,'order_id'=>$order_id,'member_id'=>$verifyData->member_id,'otp_type'=>'orderotp_end')));
				$this->load->model('app_model','app');
				
				$check_status = getFieldData('status', 'booking_services', 'booking_id', $order_id);
				if ($check_status == 4) {
					$msg['message'] = 'Job already completed';
					$this->ReturnStatus = 1;
				} else {
					$updated = $this->app->update_booking_completed($order_id);
					if($updated['status'] == 1){
					// storing time log
					$bookingData=getData(array(
						'select'=>'a.booking_date,a.booking_time,a.member_id,a.sub_cat_id,a.address_id,duration_hours',
						'table'=>'booking_services a',
						'where'=>array('a.booking_id'=>$order_id),
						'single_row'=>true,
					));
					if (empty($bookingData)) {
						$this->ReturnStatus = 0;
						$msg['message'] = 'Booking not found';
						$this->ResponseData=$msg;
						$this->response(array(
							"status" =>$this->ReturnStatus,
							"response" =>$this->ResponseData
						) , $this->ReturnCode);
						return;
					}
					// Merge date + time
					

					$timeLog = array(
						'end_time'=> $endDateTime,
					);
					updateTable('worker_time_log',$timeLog,array('order_id'=>$order_id));

					// clear unavailability table as now provider available
					delete(array('table'=>'providers_unavailablity','where'=>array('worker_id'=>$this->worker_id,'booking_id'=>$order_id)));
					// creating invoice
					$worker_id = $this->worker_id;
					$subcategoryDetails=getData(array(
						'select'=>'a.price,a.next_hour_price,a.late_night_price',
						'table'=>'category_subchild a',
						'where'=>array('a.category_subchild_id'=>$bookingData->sub_cat_id),
						'single_row'=>true,
					));
					if (empty($subcategoryDetails)) {
						$this->ReturnStatus = 0;
						$msg['message'] = 'Subcategory details not found';
						$this->ResponseData=$msg;
						$this->response(array(
							"status" =>$this->ReturnStatus,
							"response" =>$this->ResponseData
						) , $this->ReturnCode);
						return;
					}
					$first_hour_price = $subcategoryDetails->price;
					$next_hour_price = $subcategoryDetails->next_hour_price;
					$late_night_price = $subcategoryDetails->late_night_price;
					$fees=[
						'first_hour_charges'=>$first_hour_price,
						'next_hour_charges'=>$next_hour_price,
						'late_night_price'=>$late_night_price
					];
					$booking_ids=$cancel_items=[];
					$cancel_total=0;
					$cancel_data=$this->db->select('amount,booking_id')->from('member_cancel_charges')
					->where('member_id',$bookingData->member_id)->where('status',0)->get()->result();
					if($cancel_data){
						foreach($cancel_data as $k=>$r){
							$booking_ids[]=$r->booking_id;
							$cancel_items[]=[
								'name'=>'Cancel Booking Charges : #'.$r->booking_id,
								'qty'=>1,
								'unit'=>'pcs',
								'price'=>$r->amount
							];
							$cancel_total=$cancel_total+$r->amount;
						}
						$fees['cancle_charge']=$fees['cancel_total']=$cancel_total;
					}


					$start_time = getFielddata('start_time','worker_time_log','order_id', $order_id);
					$charges_data=calculate_charge_final($start_time, $endDateTime, $bookingData->duration_hours, $fees);
					if($charges_data){
						$items = [];
						if (!empty($charges_data['first_hour_charge']) && $charges_data['first_hour_charge'] > 0) {
							$first_hour_qty = $charges_data['first_hour_charge'] / $charges_data['first_hour_rate'];
							$items[] = [
								//'invoice_id' => $invoice_id,
								'name'=> 'First Hour',
								'qty'   => $first_hour_qty,
								'price' => $charges_data['first_hour_rate'],
								'total_amount'=> $charges_data['first_hour_charge'] ,
								'unit'=>'hour'
							];
						}
						
						if (!empty($charges_data['next_hours_charge']) && $charges_data['next_hours_charge'] > 0) {
							$next_hours_qty = $charges_data['next_hours_charge'] / $charges_data['next_hour_rate'];
							$items[] = [
								//'invoice_id' => $invoice_id,
								'name'=> 'Additional Hours',
								'qty'   => $next_hours_qty,
								'price' => $charges_data['next_hour_rate'],
								'total_amount'=> $charges_data['next_hours_charge'] ,
								'unit'=>'hour'
							];
						}

						if (!empty($charges_data['late_night_charge']) && $charges_data['late_night_charge'] > 0) {
							$latelight_qty = $charges_data['late_night_charge'] / $charges_data['late_night_rate'];

							$items[] = [
								//'invoice_id' => $invoice_id,
								'name'=> 'Late Night Charges',
								'qty'   => $latelight_qty,
								'price' => $charges_data['late_night_rate'],
								'total_amount'=> $charges_data['late_night_charge'],
								'unit'=>'hour'
							];
						}
						if($cancel_items){
							foreach($cancel_items as $it){
								$items[]=$it;
							}
						}

						$platform_fee=$charges_data['platform_fee'];
						$tax_amount=$charges_data['tax_amount'];
						$total = $charges_data['total'];
						


						$invoice_number=generate_invoice_number();
						$invoice_type_id=getFieldData('invoice_type_id','invoice_type','name_tkey','invoice');

						// $invoice_type_id='order_completed';
						if($invoice_type_id){
							$round_up_val=round($total,2);
							$recipient_email=getFieldData('member_email','member','member_id',$bookingData->member_id);
							$invoice=array(
								'invoice_type_id'=>$invoice_type_id,
								'invoice_number'=>$invoice_number,
								'issuer_member_id'=>0,
								'issuer_organization_id'=>NULL,
								'recipient_member_id'=>$bookingData->member_id,
								'recipient_organization_id'=>NULL,
								'invoice_date'=>date('Y-m-d H:i:s'),
								'recipient_email'=>$recipient_email,
								'round_up_amount'=>$round_up_val,
								'invoice_status'=>0,
								'invoice_order_id'=>$order_id,
								'cancel_charges'=>$cancel_total,
								'platform_fee'=>$platform_fee,
								'tax_amount'=>$tax_amount,
							);
							$invoice_id=insert_record('invoice',$invoice,TRUE);
							if($invoice_id){
								$issuer_information_arr['I_name']=get_setting('website_name');
								$issuer_information_arr['I_addr']='48 / 93 / 115, KRISHNA NAGAR';
								$issuer_information_arr['I_flat']='';
								$issuer_information_arr['I_street']='';
								$issuer_information_arr['I_landmark']='';
								$issuer_information_arr['I_GST']='19PKJPS5988D1Z0';
								$issuer_information_arr['I_phone']='+91 79033 71185';
								$issuer_information_arr['I_email']='moin.knockonce@gmail.com';
								$issuer_information_arr['I_state']='West Bengal';
								$issuer_information_arr['I_city']='KOLKATA';
								$issuer_information_arr['I_pin']='700104';


								$addressCustomer=getData(array(
									'select'=>'m.member_name,m_a.member_address_1,m_a.member_address_2,m_a.member_landmark,m_a.member_mobile,m_a.member_address_type,m.member_phone',
									'table'=>'member as m',
									'join'=>array(
										array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),
										array('table'=>'state_names as c_n','on'=>"(m_a.member_state=c_n.state_id and c_n.state_lang='".get_default_lang()."')",'position'=>'left')
									),
									'where'=>array('m_a.member_address_id'=>$bookingData->address_id),
									'single_row'=>true,
								));

								if (empty($addressCustomer)) {
									$addressCustomer = (object)[
										'member_name' => '',
										'member_address_1' => '',
										'member_address_2' => '',
										'member_landmark' => '',
										'member_mobile' => '',
										'member_address_type' => '',
										'member_phone' => ''
									];
								}

								$recipient_information_arr['R_name']=$addressCustomer->member_name;
								$recipient_information_arr['R_addr']=$addressCustomer->member_address_1;
								$recipient_information_arr['R_flat']=$addressCustomer->member_address_2;
								$recipient_information_arr['R_landmark']=$addressCustomer->member_landmark;
								$recipient_information_arr['R_phone']=$addressCustomer->member_mobile;
								$recipient_information_arr['R_type']=$addressCustomer->member_address_type;
								$recipient_information_arr['R_email']=$recipient_email;
								if($issuer_information_arr){
									$issuer_information=serialize($issuer_information_arr);
								}else{
									$issuer_information="";
								}
								if($recipient_information_arr){
									$recipient_information=serialize($recipient_information_arr);
								}else{
									$recipient_information="";
								}
								$invoice_reference=array(
									'invoice_id'=>$invoice_id,
									'issuer_information'=>$issuer_information,
									'recipient_information'=>$recipient_information,
								);
								insert_record('invoice_reference',$invoice_reference);
								if($items){
									foreach($items as $invoiceitem){
										$invoice_row=[
											'invoice_id'=>$invoice_id,
											'invoice_row_text'=>$invoiceitem['name'],
											'invoice_row_amount'=>$invoiceitem['qty'],
											'invoice_row_unit'=>$invoiceitem['unit'],
											'invoice_row_unit_price'=>$invoiceitem['price']
										];
										insert_record('invoice_row',$invoice_row);
									}
								}
								if($booking_ids){
									$this->db->where_in('booking_id',$booking_ids)->update('member_cancel_charges',['status'=>1,'invoice_id'=>$invoice_id]);
								}
								$payment_data=$charges_data;
								$booking_calculation_data=[
									'invoice_id'=>$invoice_id,
									'booking_id'=>$order_id,
									'payment_data'=>json_encode($payment_data)
								];
								$this->db->insert('booking_calculation_data',$booking_calculation_data);
								

								$this->load->library('pusher');
								$pusher=$this->pusher->load();
								$pusherData=array(
									'order_id'=>$order_id,
									'provider_id'=>$this->worker_id,
								);
								$channel_id = 'job_completed_'.$order_id;
								$res=$pusher->trigger($channel_id, 'job-completed',$pusherData);

								$mobile=$addressCustomer->member_phone;
								if($mobile){
									$bill_amount=$round_up_val;
									$bill_number=$invoice_number;
									$smstext='Your Manpower service is completed. Bill Amount: Rs. '.$bill_amount.'. Bill Number: '.$bill_number.'. Thank you for choosing us! For support, contact us anytime. SNAPHIVE';
									$sendSms=sendSMS($mobile,'1707177390479483060',$smstext);
								}

								$this->ReturnStatus = 1;
								$msg['message'] = 'success';
								$msg['data'] = [
									'booking_id'=>$order_id,
									'invoice_id'=>$invoice_id
								];

							}else{
								$msg['message'] = 'failed to create invoice';
							}

						}else{
							$msg['message'] = 'Invalid invoice type';
						}
						// end invoice


					}



					// to notify user thatjob started
					
					} else {
						$this->ReturnStatus = 0;
						$msg['message'] = $updated['message'] ?? 'Failed to complete booking';
					}
				}
			} else {
				$this->ReturnStatus = 0;
				$msg['message'] = 'Invalid OTP or Order ID';
			}
		} else {
			$this->ReturnStatus = 0;
			$msg['message'] = 'Unauthorized access';
		}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function post_upload_attach_old(){
		$msg=array();
		if($this->worker_id){
			$dataimg=$this->input->post("logo_data",FALSE);
			if($this->config->item('global_xss_filtering')){
				$image = base64_decode(str_replace('[removed]', '', $dataimg));
			}else{
				$formatdata=explode(';base64,',$dataimg);
				$image = base64_decode($formatdata[1]);
			}
			$image_name = md5($this->worker_id.'-'.time());
			$filename = $image_name . '.' . 'png';
			$path = UPLOAD_PATH."worker-logo/";
			@file_put_contents($path.$filename, $image);

			$filepathFullpath=UPLOAD_PATH."worker-logo/".$filename;
			$filepathshow=UPLOAD_HTTP_PATH."worker-logo/".$filename;
			$this->load->library('image_lib');
			$configer =  array(
				'image_library'   => 'gd2',
				'source_image'    =>  $filepathFullpath,
				'maintain_ratio'  =>  TRUE,
				'width'           =>  150,
				'height'          =>  150,
			);
			$this->image_lib->clear();
			$this->image_lib->initialize($configer);
			$this->image_lib->resize();

			if($filename){
				$attachment=[
					'filename'=>$filename,
					// 'logo'=>getWorkerLogo($this->worker_id),
					'logo'=>$filepathshow,
					
				];
				$msg['data']['attachment']=$attachment;
				$msg['data']['message']='Success';
				$this->ReturnStatus=1;
			}else{
				$msg['data']['message']='Failed to generate thumb';
			}  
		}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	
	public function get_list_address(){
		$msg=array();
		if($this->member_id){
			$this->load->model('app_model','app');

			$offset = 15;
			$page = (get('page') ? get('page') : 1);
			$limit = ($page - 1) * $offset;

			$address = $this->app->getAllAddress($this->member_id, $limit, $offset);
			$total = $this->app->getAllAddress($this->member_id, $limit, $offset, FALSE);

			$msg['data'] = array(
				'address_list' => $address,
				'total' => $total,
				'total_page' => ceil($total / $offset),
				'current_page' => $page,
			);

			$this->ReturnStatus =1;
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function post_upload_attach(){
		$msg = array();
		$this->ReturnStatus = 0;

		if (!$this->worker_id) {
			$msg['data']['message'] = 'Invalid worker';
			$this->ResponseData = $msg;
			return $this->response([
				"status" => $this->ReturnStatus,
				"response" => $this->ResponseData
			], $this->ReturnCode);
		}

		$dataimg = $this->input->post("logo_data", false);

		if (!$dataimg) {
			$msg['data']['message'] = 'No image data received';
		} 
		else {

			// Validate and extract base64 header
			if (preg_match('/^data:image\/(\w+);base64,/', $dataimg, $type)) {

				$dataimg = substr($dataimg, strpos($dataimg, ',') + 1);
				$extension = strtolower($type[1]);

				// Allow only safe formats
				if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
					$msg['data']['message'] = 'Unsupported image type';
					$this->ResponseData = $msg;
					return $this->response([
						"status" => 0,
						"response" => $msg
					], $this->ReturnCode);
				}

			} else {
				$msg['data']['message'] = 'Invalid image format';
				$this->ResponseData = $msg;
				return $this->response([
					"status" => 0,
					"response" => $msg
				], $this->ReturnCode);
			}

			// Fix base64 spacing issues
			$dataimg = str_replace(' ', '+', $dataimg);

			// Decode image
			$image = base64_decode($dataimg);

			if ($image === false) {
				$msg['data']['message'] = 'Base64 decode failed';
			}
			else {

				// Validate decoded image
				$image_info = getimagesizefromstring($image);
				if ($image_info === false) {
					$msg['data']['message'] = 'Decoded data is not a valid image';
				}
				else {

					// Generate file name
					$image_name = md5($this->worker_id . '-' . time());
					$filename   = $image_name . '.' . $extension;
					$path       = UPLOAD_PATH . "worker-logo/";

					if (!is_dir($path)) {
						mkdir($path, 0777, true);
					}

					// Save original image
					file_put_contents($path . $filename, $image);

					$full_path = $path . $filename;
					$http_path = UPLOAD_HTTP_PATH . "worker-logo/" . $filename;

					// Confirm saved file is valid
					if (!getimagesize($full_path)) {
						$msg['data']['message'] = 'Saved file is not a valid image';
					}
					else {

						// Resize image
						$this->load->library('image_lib');
						$this->image_lib->clear();

						$config = array(
							'image_library'  => 'gd2',
							'source_image'   => $full_path,
							'maintain_ratio' => TRUE,
							'width'          => 150,
							'height'         => 150,
						);

						$this->image_lib->initialize($config);

						if (!$this->image_lib->resize()) {

							$msg['data']['message'] = strip_tags($this->image_lib->display_errors());

						} else {

							$attachment = [
								'filename' => $filename,
								'logo'     => $http_path,
							];

							$msg['data']['attachment'] = $attachment;
							$msg['data']['message']    = 'Success';
							$this->ReturnStatus        = 1;
						}
					}
				}
			}
		}

		$this->ResponseData = $msg;

		return $this->response([
			"status"   => $this->ReturnStatus,
			"response" => $this->ResponseData
		], $this->ReturnCode);
	}
	public function get_booking_member(){
		$status_map = [
				1 => 'Pending',
				2 => 'Accepted',
				3 => 'Progress',
				4 => 'Completed',
				5 => 'Cancelled',
		];
        $this->load->model('app_model','app');
        $srch = get();
        $srch['member_id']=$this->member_id;
        $offset = 10;
        $page=(get('page')? get('page'):1);
        $limit=($page-1)*$offset;
        $all=$this->app->booking_list($srch, $limit, $offset);
        if($all){
            foreach($all as $k=>$row){
                $row['logo']=getWorkerLogo($row['provider_id']);
				if($row['invoice_status'] == 1 ){
					$row['is_payment']=1;
				}else{
					$row['is_payment']=0;
				}

				$row['total_amount']=0;
				if($row['status'] == 4){
					$amt = getField('round_up_amount','invoice','invoice_order_id',$row['booking_id']);
					$row['total_amount']=$amt;
				}

                $all[$k]=$row;
            }
        }
        $data = array(
            'list' => $all,
            'total' =>  $this->app->booking_list($srch, $limit, $offset, FALSE),
            'status_map' =>  $status_map,
        );
        $data['total_page']=ceil($data['total']/$offset);
        $data['current_page']=$page;
        
        $result['data'] = $data;
        $result['status'] = 1;
        $this->ReturnStatus=1;
    
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
    }
	public function get_booking_worker(){
		$status_map = [
				1 => 'Pending',
				2 => 'Accepted',
				3 => 'Progress',
				4 => 'Completed',
				5 => 'Cancelled',
		];
        $this->load->model('app_model','app');
        $srch = get();
        $srch['worker_id']=$this->worker_id;
        // $srch['status']=[2,4];
        $offset = 10;
        $page=(get('page')? get('page'):1);
        $limit=($page-1)*$offset;
        $all=$this->app->booking_list($srch, $limit, $offset);
        if($all){
            foreach($all as $k=>$row){
                $row['logo']=getWorkerLogo($row['provider_id']);
				if($row['invoice_status'] == 1 ){
					$row['is_payment']=1;
				}else{
					$row['is_payment']=0;
				}

				$row['total_amount']=0;
				if($row['status'] == 4){
					$amt = getField('round_up_amount','invoice','invoice_order_id',$row['booking_id']);
					$row['total_amount']=$amt;
				}
				
                $all[$k]=$row;
            }
        }
        $data = array(
            'list' => $all,
            'total' =>  $this->app->booking_list($srch, $limit, $offset, FALSE),
            'status_map' =>  $status_map,
        );
        $data['total_page']=ceil($data['total']/$offset);
        $data['current_page']=$page;
        
        $result['data'] = $data;
        $result['status'] = 1;
        $this->ReturnStatus=1;
    
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
    }
	public function post_mark_as_paid(){
		$msg=array();
		if($this->worker_id){
			$booking_id = $this->input->post('order_id');
			$updtdata = array(
				'invoice_status' => 1,
				'payment_type' => '',
				
			);
			$this->db->where('invoice_order_id', $booking_id);
			$updated = $this->db->update('invoice', $updtdata);
			
			$InvoiceData=getData(array(
				'select'=>'a.invoice_id,a.round_up_amount,a.cancel_charges,a.tax_amount,a.platform_fee',
				'table'=>'invoice a',
				'where'=>array('invoice_order_id'=>$booking_id),
				'single_row'=>true,
			));

			if (empty($InvoiceData)) {
				$this->ReturnStatus = 0;
				$msg['message'] = "Invoice not found for this order";
				$this->ResponseData=$msg;
				$this->response(array(
					"status" =>$this->ReturnStatus,
					"response" =>$this->ResponseData
				) , $this->ReturnCode);
				return;
			}

			$invoice_id = $InvoiceData->invoice_id;
			$round_up_amount = $InvoiceData->round_up_amount;
			$cancel_charges = $InvoiceData->cancel_charges;
			$tax_amount = $InvoiceData->tax_amount;
			$platform_fee = $InvoiceData->platform_fee;

			$total_amount=$round_up_amount-$cancel_charges-$tax_amount-$platform_fee;

			/** Site commission start **/
			$this->load->model('app_model','app');
			//$sub_cat_id = getField('sub_cat_id','booking_services','booking_id',$booking_id);
			//$price = getField('price','category_subchild','category_subchild_id',$sub_cat_id);

			/*$work_time_det = $this->app->get_time($booking_id);
			// echo $this->db->last_query(); die;
			// print_r($work_time_det); die;
			 $total_seconds = 0;
			$total_hours   = 0;
			$total_amount  = 0;

			if(!empty($work_time_det) && 
			!empty($work_time_det->start_time) && 
			!empty($work_time_det->end_time)){

				$start = strtotime($work_time_det->start_time);
				$end   = strtotime($work_time_det->end_time);

				if($start && $end && $end > $start){
					$total_seconds = $end - $start;
					$total_hours   = round($total_seconds / 3600, 2);
					$total_amount  = round($total_hours * $price, 2);
				}
			} */


			// Commission
			$sitecommission = get_setting('site_commision');
			if (empty($sitecommission) || floatval($sitecommission) <= 0) {
				$sitecommission = 5; // default fallback 5%
			}

			if ($total_amount <= 0) {
				$total_amount = $round_up_amount;
			}
			$sitecommission_fee_amount = round(($total_amount * floatval($sitecommission)) / 100, 2);

			$profit_wallet_setting = get_setting('SITE_PROFIT_WALLET');
			$profit_details = !empty($profit_wallet_setting) ? getWallet($profit_wallet_setting) : null;
			if (empty($profit_details)) {
				$profit_details = getWallet(17);
			}
			if (empty($profit_details)) {
				$profit_details = getData(array(
					'select' => 'w.wallet_id,w.balance,w.user_id,w.title',
					'table' => 'wallet as w',
					'where' => array('w.title' => 'Site Profit Wallet'),
					'single_row' => true,
				));
			}

			$workerWalletDetails = getWalletWorker($this->worker_id); 
			if (empty($workerWalletDetails)) {
				$this->ReturnStatus = 0;
				$msg['message'] = "Worker wallet not found";
				$this->ResponseData=$msg;
				$this->response(array(
					"status" =>$this->ReturnStatus,
					"response" =>$this->ResponseData
				) , $this->ReturnCode);
				return;
			}

			// Check if commission already deducted for this booking
			$already_deducted = $this->db->select('wallet_transaction_row_id')
				->from('wallet_transaction_row')
				->where('wallet_id', $workerWalletDetails->wallet_id)
				->where('description_tkey', 'Project_Commision_')
				->like('ref_data_cell', '"PID":"' . $booking_id . '"')
				->get()->row();

			$wallet_transaction_id = 0;
			if (!$already_deducted) {
				$wallet_transaction_type_id = get_setting('COMMISION_RELEASE');
				if (empty($wallet_transaction_type_id)) {
					$txn_type = getFieldData('wallet_transaction_type_id', 'wallet_transaction_type', 'title_tkey', 'COMMISION_RELEASE');
					$wallet_transaction_type_id = !empty($txn_type) ? $txn_type : 13;
				}

				$current_datetime = date('Y-m-d H:i:s');
				$wallet_transaction_id = insert_record('wallet_transaction', array(
					'wallet_transaction_type_id' => $wallet_transaction_type_id,
					'status' => 1,
					'created_date' => $current_datetime,
					'transaction_date' => $current_datetime
				), TRUE);

				if ($sitecommission_fee_amount > 0) {
					if ($wallet_transaction_id) {
						// 1. Debit Provider Wallet
						$insert_wallet_transaction_row = array(
							'wallet_transaction_id' => $wallet_transaction_id,
							'wallet_id' => $workerWalletDetails->wallet_id,
							'debit' => $sitecommission_fee_amount,
							'description_tkey' => 'Project_Commision_',
							'relational_data' => $sitecommission . '%'
						);
						$insert_wallet_transaction_row['ref_data_cell'] = json_encode(array(
							'FW' => $workerWalletDetails->worker_name . ' wallet',
							'TW' => ($profit_details ? $profit_details->title : 'Site Profit Wallet'),
							'TP' => 'Commission_Payment',
							'PID' => $booking_id,
						));
						insert_record('wallet_transaction_row', $insert_wallet_transaction_row);

						// 2. Credit Profit Wallet
						if (!empty($profit_details)) {
							$insert_wallet_transaction_row = array(
								'wallet_transaction_id' => $wallet_transaction_id,
								'wallet_id' => $profit_details->wallet_id,
								'credit' => $sitecommission_fee_amount,
								'description_tkey' => 'Project_Commision',
								'relational_data' => $sitecommission . '%'
							);
							$insert_wallet_transaction_row['ref_data_cell'] = json_encode(array(
								'FW' => $workerWalletDetails->worker_name . ' wallet',
								'TW' => $profit_details->title,
								'TP' => 'Commission_Payment',
								'PID' => $booking_id,
							));
							insert_record('wallet_transaction_row', $insert_wallet_transaction_row);

							$new_profit_balance = displayamount($profit_details->balance, 2) + displayamount($sitecommission_fee_amount, 2);
							updateTable('wallet', ['balance' => $new_profit_balance], ['wallet_id' => $profit_details->wallet_id]);
							wallet_balance_check($profit_details->wallet_id, ['transaction_id' => $wallet_transaction_id]);
						}
					}

					// 3. Update Provider Wallet Balance (Deduct commission)
					$new_member_balance = displayamount($workerWalletDetails->balance, 2) - displayamount($sitecommission_fee_amount, 2);
					updateTable('wallet', ['balance' => $new_member_balance], ['wallet_id' => $workerWalletDetails->wallet_id]);
					if ($wallet_transaction_id) {
						wallet_balance_check($workerWalletDetails->wallet_id, ['transaction_id' => $wallet_transaction_id]);
					}
				}
			}
			/** Site commission start end **/
			$cancel_txn_id=0;
			if($cancel_charges){
				$cancel_txn_id=$this->app->release_cancel_charges($booking_id,$invoice_id);
			}
			$tax_txn_id=0;
			if($tax_amount){
				$tax_txn_id=$this->app->release_tax_charges($booking_id,$invoice_id);
			}
			
			if($wallet_transaction_id){
				$bookingData=getData(array(
					'select'=>'a.member_id,m.member_phone',
					'table'=>'booking_services a',
					'join'=>[
						['table'=>'member as m','on'=>'a.member_id=m.member_id','position'=>'left']
					],
					'where'=>array('a.booking_id'=>$booking_id),
					'single_row'=>true,
				));
				$mobile=$bookingData->member_phone;
				if($mobile){
					$bill_amount=$round_up_amount;
					$smstext='Payment received successfully. manpower service Amount: Rs. '.$bill_amount.'. Thank you for choosing our service! -SNAPHIVE';
					$sendSms=sendSMS($mobile,'1707177390474228474',$smstext);
				}
			}


			$this->ReturnStatus =1;
			$msg['message']="Success";
			$msg['data']['txn_id']=$wallet_transaction_id;
			$msg['data']['cancel_txn_id']=$cancel_txn_id;
			$msg['data']['tax_txn_id']=$tax_txn_id;
			$msg['data']['booking_id']=$booking_id;
			$msg['data']['invoice_id']=$invoice_id;
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function get_start_booking_worker(){
		$status_map = [
				1 => 'Pending',
				2 => 'Accepted',
				3 => 'Progress',
				4 => 'Completed',
				5 => 'Cancelled',
		];
        $this->load->model('app_model','app');
        $srch = get();
        $srch['worker_id']=$this->worker_id;
        // $srch['status']=[2,4];
        $offset = 1;
        // $page=(get('page')? get('page'):1);
        // $limit=($page-1)*$offset;
		$srch['status']= 2;
        $all=$this->app->booking_list($srch, 0, $offset);
        if($all){
            foreach($all as $k=>$row){
                $row['logo']=getWorkerLogo($row['provider_id']);
				if($row['invoice_status'] == 1 ){
					$row['is_payment']=1;
				}else{
					$row['is_payment']=0;
				}
				
                $all[$k]=$row;
            }
        }
        $data = array(
            'list' => $all,
            // 'total' =>  $this->app->booking_list($srch, $limit, $offset, FALSE),
            'status_map' =>  $status_map,
        );
        // $data['total_page']=ceil($data['total']/$offset);
        // $data['current_page']=$page;
        
        $result['data'] = $data;
        $result['status'] = 1;
        $this->ReturnStatus=1;
    
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
    }
	public function get_check_invoice(){
		$msg=array();
		if($this->worker_id){
		
			$order_id = $this->input->get('order_id');
			$verifyData=getData(array(
				'select'=>'*',
				'table'=>'worker_otp a',
				'where'=>array('order_id'=>$order_id),
				'single_row'=>true,
			));
			// echo $this->db->last_query(); die;
			if($verifyData){
				
				$this->load->model('app_model','app');

					// storing time log
					$bookingData=getData(array(
						'select'=>'a.booking_date,a.booking_time,a.member_id,a.sub_cat_id,a.address_id,duration_hours',
						'table'=>'booking_services a',
						'where'=>array('a.booking_id'=>$order_id),
						'single_row'=>true,
					));
					// Merge date + time
					
					$msg['bookingData']=$bookingData;
					
					$worker_id = $this->worker_id;
					$subcategoryDetails=getData(array(
						'select'=>'a.price,a.next_hour_price,a.late_night_price',
						'table'=>'category_subchild a',
						'where'=>array('a.category_subchild_id'=>$bookingData->sub_cat_id),
						'single_row'=>true,
					));
					$first_hour_price = $subcategoryDetails->price;
					$next_hour_price = $subcategoryDetails->next_hour_price;
					$late_night_price = $subcategoryDetails->late_night_price;
					$fees=[
						'first_hour_charges'=>$first_hour_price,
						'next_hour_charges'=>$next_hour_price,
						'late_night_price'=>$late_night_price
					];
					$booking_ids=$cancel_items=[];
					$cancel_total=0;
					$cancel_data=$this->db->select('amount,booking_id')->from('member_cancel_charges')
					->where('member_id',$bookingData->member_id)->where('status',0)->get()->result();
					if($cancel_data){
						foreach($cancel_data as $k=>$r){
							$booking_ids[]=$r->booking_id;
							$cancel_items[]=[
								'name'=>'Cancel Booking Charges : #'.$r->booking_id,
								'qty'=>1,
								'unit'=>'pcs',
								'price'=>$r->amount
							];
							$cancel_total=$cancel_total+$r->amount;
						}
						$fees['cancle_charge']=$fees['cancel_total']=$cancel_total;
					}


					$start_time = getFielddata('start_time','worker_time_log','order_id', $order_id);
					$endDateTime = getFielddata('end_time','worker_time_log','order_id', $order_id);
					$msg['start_time']=$start_time;
					$msg['end_time']=$endDateTime;
					$msg['charges_data']=$charges_data=calculate_charge_final($start_time, $endDateTime, $bookingData->duration_hours, $fees);
					if($charges_data){
						$items = [];
						if (!empty($charges_data['first_hour_charge']) && $charges_data['first_hour_charge'] > 0) {
							$first_hour_qty = $charges_data['first_hour_charge'] / $charges_data['first_hour_rate'];
							$items[] = [
								//'invoice_id' => $invoice_id,
								'name'=> 'First Hour',
								'qty'   => $first_hour_qty,
								'price' => $charges_data['first_hour_rate'],
								'total_amount'=> $charges_data['first_hour_charge'] ,
								'unit'=>'hour'
							];
						}
						
						if (!empty($charges_data['next_hours_charge']) && $charges_data['next_hours_charge'] > 0) {
							$next_hours_qty = $charges_data['next_hours_charge'] / $charges_data['next_hour_rate'];
							$items[] = [
								//'invoice_id' => $invoice_id,
								'name'=> 'Additional Hours',
								'qty'   => $next_hours_qty,
								'price' => $charges_data['next_hour_rate'],
								'total_amount'=> $charges_data['next_hours_charge'] ,
								'unit'=>'hour'
							];
						}

						if (!empty($charges_data['late_night_charge']) && $charges_data['late_night_charge'] > 0) {
							$latelight_qty = $charges_data['late_night_charge'] / $charges_data['late_night_rate'];

							$items[] = [
								//'invoice_id' => $invoice_id,
								'name'=> 'Late Night Charges',
								'qty'   => $latelight_qty,
								'price' => $charges_data['late_night_rate'],
								'total_amount'=> $charges_data['late_night_charge'],
								'unit'=>'hour'
							];
						}
						if($cancel_items){
							foreach($cancel_items as $it){
								$items[]=$it;
							}
						}

						$platform_fee=$charges_data['platform_fee'];
						$tax_amount=$charges_data['tax_amount'];
						$total = $charges_data['total'];
						
						$msg['items']=$items;
						


					}


					// to notify user thatjob started
					
				
			}
		}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
	public function get_provider_cancel_booking(){
		$this->load->model('app_model','app');
		$msg=array();
		if($this->worker_id){
			$booking_id = $this->input->get('booking_id');
			$member_id = getField('member_id','booking_services','booking_id',$booking_id);
			$updated = $this->app->update_provider_booking_cancel($booking_id);
			if($updated['status'] == 1){
				$this->ReturnStatus = 1;
				$msg['message'] = 'Successfully Cancelled';
				// onesignal for cancel to worker
				$oneSignalData= array(
					'heading' => 'BOOKING CANCELLED',
					'content' => 'Your booking was cancelled by the worker as they are currently unable to proceed with the job.',
					'data' => [
						'screen' => 'booking_cancelled',
						'channel_id' => $booking_id,
					]
				);
				$msg['onesignal']=$this->app->sendpush_OneSignal($oneSignalData,$member_id,'');
				// clear unavailability table as now provider available
				delete(array('table'=>'providers_unavailablity','where'=>array('worker_id'=>$updated['provider_id'],'booking_id'=>$booking_id)));
				
			}
			// echo 's'; die;
		
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
