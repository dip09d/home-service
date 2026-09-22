<?php

class app_model extends CI_Model
{
	private $response;
	private $lang;
    public function __construct() {
		$this->lang = get_active_lang();
        return parent::__construct();
    }

	public function send_otp($mobile){
		$response = array();
		// Generate OTP
		$otp = rand(1000,9999);
		$sent_date = date('Y-m-d H:i:s');
		$member_id=getFieldData('member_id','member','member_phone',$mobile);
		// echo $member_id; die;
		if($member_id){
			$access_user_id=getFieldData('access_user_id','member','member_id',$member_id);
			// Save OTP
			$insdataToken = array(
				'member_id' => $member_id,
				'access_user_id' => $access_user_id,
				'token_value' => $otp, 
				'sent_date' => $sent_date,
				// 'expire_at' => date('Y-m-d H:i:s', strtotime('+1 minutes')),
				'access_ip'=>$this->input->ip_address(),
				'token_type' => 'LOGIN',
			);
			delete(array('table'=>'profile_verify_token','where'=>array('member_id'=>$member_id,'token_type'=>'LOGIN')));
			$id=insert_record('profile_verify_token',$insdataToken,TRUE);
			
			$smstext='Your OTP for mobile number authentication is '.$otp.' Do not Share With Anyone. -SNAPHIVE';
			$sendSms=sendSMS($mobile,'1707176968531906520',$smstext);
			if($sendSms && $sendSms['status']==1){
				$response['status'] = 1;
				$response['otp'] = $otp;
			}else{
				$response['otp'] = $otp;
				$response['status'] = 0;
				$response['errors'] = 'SMS sending failed';
			}

		}else{
			$response['status'] = 0;
			$response['errors'] = 'Account does not exists';
		}
		return $response;
	}
	public function login($inputdata=array()){
		$i=0;
		$msg=array();
		$mobile=$inputdata['mobile'];
		$otp=$inputdata['otp'];
		$customerData=getData(array(
			'select'=>'a.access_user_id,login_status',
			'table'=>'access_panel a',
			'where'=>array('a.access_user_email'=>post('mobile')),
			'single_row'=>true,
			));
		
		if($customerData){
			
			$verifyData=getData(array(
				'select'=>'a.member_id',
				'table'=>'profile_verify_token a',
				'where'=>array('a.token_value'=>$otp,'access_user_id'=> $customerData->access_user_id,'token_type'=>'LOGIN','sent_date >=' => date('Y-m-d H:i:s', strtotime('-1 minutes'))),
				'single_row'=>true,
			));
			// echo $this->db->last_query(); die;
			if($verifyData){
				$result=[];
				delete(array('table'=>'profile_verify_token','where'=>array('member_id'=>$verifyData->member_id,'token_type'=>'LOGIN')));
				// $this->load->model('user/user_model');
				// $LAST_PCI=$this->user_model->getLastActive($customerData->access_user_id);
				// $result=array('member_type'=>$LAST_PCI['TYP'],'organization_id'=>$LAST_PCI['OID'],'member_id'=>$LAST_PCI['MID']);
				// $member_name=getFieldData('member_name','member','member_id',$LAST_PCI['MID']);
				// if($LAST_PCI['TYP']=='C'){
				// 	$result['logo']= getCompanyLogo($LAST_PCI['OID']);
				// 	$organization_name=getFieldData('organization_name','organization','member_id',$LAST_PCI['MID']);
				// 	$profile_name=($organization_name  ? $organization_name:$member_name);
				// }else{
				// 	$result['logo']= getMemberLogo($LAST_PCI['MID']);
				// 	$profile_name=$member_name;
				// }
				// $result['profile_name']=$profile_name;
				$member_address=getData(array(
					'select'=>'member_address_id',
					'table'=>'member_address',
					'where'=>array('member_id'=>$verifyData->member_id,'address_status'=> 1),
				));
				if(!empty($member_address)){
					$if_available_address = "1";
				} else {
					$if_available_address = "0";
				}
				// echo '<pre>'; print_r($member_address); die;
				$member_name=getFieldData('member_name','member','member_id',$verifyData->member_id);
				$result['profile_name']=$member_name;
				$result['logo']= getMemberLogo($verifyData->member_id);
				$result['member_id']= $verifyData->member_id;
				$result['member_phone']= $mobile;
				$result['login_status']= $customerData->login_status;
				$result['if_available_address']= $if_available_address;
				$msg['status'] = 1;
				$msg['data'] =$result;

				// $member_id=$verifyData->member_id;
				// $result=array(
				// 	'member_id'=>$member_id
				// );
				// $msg['status']=1;
				
			}else{
				$msg['status']=0;
				$msg['errors'][$i]['id']='otp';
				$msg['errors'][$i]['message']= 'Invalid otp / otp expired';
			}
			
			
		
		
		
			//$memberDevice=$this->getmemberDevice($result->member_id);
			$android=$ios=array();
			/* if($memberDevice){
				foreach($memberDevice as $d=>$device){
					if($device->device_type=='android'){
						$android[]=$device->device_token;
					}elseif($device->device_type=='ios'){
						$ios[]=$device->device_token;
					}
				}
			} */
			$device_token['android']=$android;
			$device_token['ios']=$ios;
			$result['device_token']=$device_token;
			$msg['data'] =$result;	
				
		}else{
			$msg['status'] = 'FAIL';
			$msg['errors'][$i]['id'] = 'otp';
			$msg['errors'][$i]['message'] = 'Invalid otp or password';
		}
		$this->response=$msg;
		return $this->response;
	}
	public function login_with_static_otp($mobile){
		$i=0;
		$msg=array();
		$customerData=getData(array(
			'select'=>'a.access_user_id,login_status',
			'table'=>'access_panel a',
			'where'=>array('a.access_user_email'=>$mobile),
			'single_row'=>true,
		));
		if(!$customerData){
			$msg['status']=0;
			$msg['errors'][$i]['id']='otp';
			$msg['errors'][$i]['message']='Account does not exist';
			return $msg;
		}
		$member_id=getFieldData('member_id','member','member_phone',$mobile);
		// Clear any existing OTP token (same as normal login flow)
		delete(array('table'=>'profile_verify_token','where'=>array('member_id'=>$member_id,'token_type'=>'LOGIN')));
		$member_address=getData(array(
			'select'=>'member_address_id',
			'table'=>'member_address',
			'where'=>array('member_id'=>$member_id,'address_status'=>1),
		));
		$result=array();
		$result['profile_name']=getFieldData('member_name','member','member_id',$member_id);
		$result['logo']=getMemberLogo($member_id);
		$result['member_id']=$member_id;
		$result['member_phone']=$mobile;
		$result['login_status']=$customerData->login_status;
		$result['if_available_address']=(!empty($member_address) ? '1' : '0');
		$result['device_token']=array('android'=>[],'ios'=>[]);
		$msg['status']=1;
		$msg['data']=$result;
		return $msg;
	}
	public function login_P_with_static_otp($mobile){
		$i=0;
		$msg=array();
		$workerData=getData(array(
			'select'=>'a.worker_id,a.worker_name,a.login_status,a.worker_register_date,a.is_offline',
			'table'=>'worker a',
			'where'=>array('a.worker_phone'=>$mobile),
			'single_row'=>true,
		));
		if(!$workerData){
			$msg['status']=0;
			$msg['errors'][$i]['id']='otp';
			$msg['errors'][$i]['message']='Account does not exist';
			return $msg;
		}
		// Clear any existing OTP token (same as normal login_P flow)
		delete(array('table'=>'profile_verify_token','where'=>array('member_id'=>$workerData->worker_id,'token_type'=>'LOGIN_P')));
		$result=array();
		$result['logo']=getWorkerLogo($workerData->worker_id);
		$result['profile_name']=$workerData->worker_name;
		$result['member_id']=$workerData->worker_id;
		$result['login_status']=$workerData->login_status;
		$result['register_date']=$workerData->worker_register_date;
		$result['is_offline']=$workerData->is_offline;
		$result['device_token']=array('android'=>[],'ios'=>[]);
		$msg['status']=1;
		$msg['data']=$result;
		return $msg;
	}
	public function send_otp_P($mobile){
		$response = array();
		// Generate OTP
		$otp = rand(1000,9999);
		$sent_date = date('Y-m-d H:i:s');
		$worker_id=getFieldData('worker_id','worker','worker_phone',$mobile);
		if($worker_id){
			// Save OTP
			$insdataToken = array(
				'member_id' => $worker_id,
				'token_value' => $otp, 
				'sent_date' => $sent_date,
				// 'expire_at' => date('Y-m-d H:i:s', strtotime('+1 minutes')),
				'access_ip'=>$this->input->ip_address(),
				'token_type' => 'LOGIN_P',
			);
			delete(array('table'=>'profile_verify_token','where'=>array('member_id'=>$worker_id,'token_type'=>'LOGIN_P')));
			$id=insert_record('profile_verify_token',$insdataToken,TRUE);
			

			$smstext='Your OTP for mobile number authentication is '.$otp.' Do not Share With Anyone. -SNAPHIVE';
			$sendSms=sendSMS($mobile,'1707176968531906520',$smstext);
			if($sendSms && $sendSms['status']==1){
				$response['status'] = 1;
				$response['otp'] = $otp;
			}else{
				$response['otp'] = $otp;
				$response['status'] = 0;
				$response['errors'] = 'SMS sending failed';
			}		

			
		}else{
			$response['status'] = 0;
			$response['errors'] = 'Account does not exists';
		}
		return $response;
	}
	public function login_P($inputdata=array()){
		$i=0;
		$msg=array();
		$mobile=$inputdata['mobile'];
		$otp=$inputdata['otp'];
		$customerData=getData(array(
			'select'=>'a.worker_id,a.worker_name,a.login_status,a.worker_register_date,a.is_offline',
			'table'=>'worker a',
			'where'=>array('a.worker_phone'=>$mobile),
			'single_row'=>true,
			));
		
		if($customerData){
			
			$verifyData=getData(array(
				'select'=>'a.member_id',
				'table'=>'profile_verify_token a',
				'where'=>array('a.token_value'=>$otp,'member_id'=> $customerData->worker_id,'token_type'=>'LOGIN_P','sent_date >=' => date('Y-m-d H:i:s', strtotime('-1 minutes'))),
				'single_row'=>true,
			));
			if($verifyData){
				$result=[];
				delete(array('table'=>'profile_verify_token','where'=>array('member_id'=>$verifyData->member_id,'token_type'=>'LOGIN')));
				// $this->load->model('user/user_model');
				// $LAST_PCI=$this->user_model->getLastActive($customerData->access_user_id);
				// $result=array('member_type'=>$LAST_PCI['TYP'],'organization_id'=>$LAST_PCI['OID'],'member_id'=>$LAST_PCI['MID']);
				// $member_name=getFieldData('member_name','member','member_id',$LAST_PCI['MID']);
				// if($LAST_PCI['TYP']=='C'){
				// 	$result['logo']= getCompanyLogo($LAST_PCI['OID']);
				// 	$organization_name=getFieldData('organization_name','organization','member_id',$LAST_PCI['MID']);
				// 	$profile_name=($organization_name  ? $organization_name:$member_name);
				// }else{
				// 	$result['logo']= getMemberLogo($LAST_PCI['MID']);
				// 	$profile_name=$member_name;
				// }
				// $result['profile_name']=$profile_name;
				$result['logo']= getWorkerLogo($verifyData->member_id);
				$result['profile_name']=$customerData->worker_name;
				$result['member_id']= $verifyData->member_id;
				$result['login_status']= $customerData->login_status;
				$result['register_date']= $customerData->worker_register_date;
				$result['is_offline']= $customerData->is_offline;
				
				$msg['status'] = 1;
				$msg['data'] =$result;


				// $member_id=$verifyData->member_id;
				// $result=array(
				// 	'member_id'=>$member_id
				// );
				// $msg['status']=1;
				
			}else{
				$msg['status']=0;
				$msg['errors'][$i]['id']='otp';
				$msg['errors'][$i]['message']= 'Invalid otp / otp expired';
			}
			
			
		
		
		
			//$memberDevice=$this->getmemberDevice($result->member_id);
			$android=$ios=array();
			/* if($memberDevice){
				foreach($memberDevice as $d=>$device){
					if($device->device_type=='android'){
						$android[]=$device->device_token;
					}elseif($device->device_type=='ios'){
						$ios[]=$device->device_token;
					}
				}
			} */
			$device_token['android']=$android;
			$device_token['ios']=$ios;
			$result['device_token']=$device_token;
			$msg['data'] =$result;	
				
		}else{
			$msg['status'] = 'FAIL';
			$msg['errors'][$i]['id'] = 'otp';
			$msg['errors'][$i]['message'] = 'Invalid otp or password';
		}
		$this->response=$msg;
		return $this->response;
	}
	// public function getServices(){

	// 	$admin_default_lang = $this->lang;

	// 	$this->db->select('
	// 		c.category_id,
	// 		cn.category_name,
	// 		sc.category_subchild_id,
	// 		scn.category_subchild_name
	// 	')
	// 	->from('pref_category c')
	// 	->join('pref_category_names cn','c.category_id = cn.category_id')
	// 	->join('category_subchild sc','c.category_id = sc.category_id')
	// 	->join('category_subchild_names scn','sc.category_subchild_id = scn.category_subchild_id')

	// 	->where('c.category_status',1)
	// 	->where('sc.category_subchild_status',1)
	// 	->where('cn.category_lang',$admin_default_lang)
	// 	->where('scn.category_subchild_lang',$admin_default_lang)

	// 	->order_by('c.category_id','ASC');

	// 	$result = $this->db->get()->result_array();


	// 	// GROUPING START
	// 	$final = [];

	// 	foreach($result as $row){

	// 		$cat_id = $row['category_id'];

	// 		if(!isset($final[$cat_id])){
	// 			$final[$cat_id] = [
	// 				'category_id' => $cat_id,
	// 				'category_name' => $row['category_name'],
	// 				'subcategories' => []
	// 			];
	// 		}

	// 		$final[$cat_id]['subcategories'][] = [
	// 			'category_subchild_id' => $row['category_subchild_id'],
	// 			'category_subchild_name' => $row['category_subchild_name']
	// 		];
	// 	}

	// 	return array_values($final);
	// }
	public function getServices(){
		$this->db->select("
			sc.category_subchild_id,
			scn.category_subchild_name,
			CASE 
				WHEN category_subchild_thumb = '' OR category_subchild_thumb IS NULL 
				THEN CONCAT('".base_url('assets/default/images/default/noimage.jpg')."')
				ELSE CONCAT('".base_url('user_uploads/category_icons/thumb/')."', category_subchild_thumb)
			END as category_subchild_thumb
		")
		->from('category_subchild sc')

		->join('pref_category c', 
			'sc.category_id = c.category_id', 
			'INNER')

		->join(
			'category_subchild_names scn',
			'sc.category_subchild_id = scn.category_subchild_id 
			AND scn.category_subchild_lang = "'.$this->lang.'"',
			'INNER'
		)

		->where('sc.category_subchild_status',1)
		->where('c.category_key !=','aya'); // excluding aya

		return $this->db->get()->result_array();
	}
	public function getAyaServices(){
		$this->db->select("
			sc.category_subchild_id,   
			scn.category_subchild_name,
			CASE 
				WHEN category_subchild_thumb = '' OR category_subchild_thumb IS NULL 
				THEN CONCAT('".base_url('assets/default/images/default/noimage.jpg')."')
				ELSE CONCAT('".base_url('user_uploads/category_icons/thumb/')."', category_subchild_thumb)
			END as category_subchild_thumb
		")
		->from('category_subchild sc')

		->join('pref_category c', 
			'sc.category_id = c.category_id', 
			'INNER')

		->join(
			'category_subchild_names scn',
			'sc.category_subchild_id = scn.category_subchild_id 
			AND scn.category_subchild_lang = "'.$this->lang.'"',
			'INNER'
		)

		->where('sc.category_subchild_status',1)
		->where('c.category_key','aya'); // only aya

		return $this->db->get()->result_array();
	}
	public function getSubCategoryDetails($id=''){
		$this->db->select("a.*,CASE 
				WHEN category_subchild_thumb = '' OR category_subchild_thumb IS NULL 
				THEN CONCAT('".base_url('assets/default/images/default/noimage.jpg')."')
				ELSE CONCAT('".base_url('user_uploads/category_icons/thumb/')."', category_subchild_thumb)
			END as category_subchild_thumb, b.category_subchild_name, b.description")
			->from('category_subchild a')
			->join('category_subchild_names b', 'a.category_subchild_id = b.category_subchild_id')
			->where('a.category_subchild_id', $id)
			->where('b.category_subchild_lang', $this->lang);

		return $this->db->get()->row_array();
	}
	public function getParentCategories(){
		$this->db->select("
			c.category_id,
			cn.category_name,
			c.category_key,
			CASE 
				WHEN c.category_icon = '' OR c.category_icon IS NULL 
				THEN CONCAT('".base_url('assets/default/images/default/noimage.jpg')."')
				ELSE CONCAT('".base_url('user_uploads/category_icons/thumb/')."', c.category_icon)
			END as category_icon,
			CASE 
				WHEN c.category_thumb = '' OR c.category_thumb IS NULL 
				THEN CONCAT('".base_url('assets/default/images/default/noimage.jpg')."')
				ELSE CONCAT('".base_url('user_uploads/category_icons/thumb/')."', c.category_thumb)
			END as category_thumb
		")
		->from('pref_category c')
		->join(
			'pref_category_names cn',
			'c.category_id = cn.category_id 
			AND cn.category_lang = "'.$this->lang.'"',
			'INNER'
		)
		->where('c.category_status',1)
		->order_by('c.category_order', 'ASC');

		return $this->db->get()->result_array();
	}

	public function getSubCategoriesByParent($category_id){
		$this->db->select("
			sc.category_subchild_id,   
			scn.category_subchild_name,
			CASE 
				WHEN category_subchild_thumb = '' OR category_subchild_thumb IS NULL 
				THEN CONCAT('".base_url('assets/default/images/default/noimage.jpg')."')
				ELSE CONCAT('".base_url('user_uploads/category_icons/thumb/')."', category_subchild_thumb)
			END as category_subchild_thumb
		")
		->from('category_subchild sc')
		->join(
			'category_subchild_names scn',
			'sc.category_subchild_id = scn.category_subchild_id 
			AND scn.category_subchild_lang = "'.$this->lang.'"',
			'INNER'
		)
		->where('sc.category_subchild_status',1)
		->where('sc.category_id', $category_id);

		return $this->db->get()->result_array();
	}
	public function getWorkerCount($subchild_id){
		return $this->db
			->where('category_subchild_id', $subchild_id)
			->from('pref_worker_service')
			->count_all_results();
	}
	public function signupotp(){
		$i=0;
		$msg=array();
		$number=trim(post('number'));
		$token_type = 'R_'.$number;
		$otp = $token = rand(1111,9999);
		$insdataToken=array('access_user_id' => 0,'member_id'=>0,'token_value'=>$token,'sent_date'=>date('Y-m-d H:i:s'),'access_ip'=>$this->input->ip_address(),'token_type'=>$token_type);
		$id=insert_record('profile_verify_token',$insdataToken,TRUE);
		if($number){
			$smstext='Your OTP for mobile number authentication is '.$otp.' Do not Share With Anyone. -SNAPHIVE';
			$sendSms=sendSMS($number,'1707176968531906520',$smstext);
			if($sendSms && $sendSms['status']==1){
				$response['status'] = 1;
				$response['otp'] = $otp;
			}else{
				$response['otp'] = $otp;
				$response['status'] = 0;
				$response['errors'] = 'SMS sending failed';
			}
		}
		
		return $response;
	}
	public function verify_otp($otp,$number){
		$i=0;
		$msg=array();
		$token_type = 'R_'.$number;
		$verifyData=getData(array(
			'select'=>'member_id',
			'table'=>'profile_verify_token',
			'where'=>array('token_value'=>$otp,'token_type'=>$token_type,'sent_date >=' => date('Y-m-d H:i:s', strtotime('-1 minutes'))),
			'order_by'   => array('sent_date' => 'DESC'), // fetch latest OTP
    		'limit'      => 1,
			'single_row'=>true,
		));
		if($verifyData){
			delete(array('table'=>'profile_verify_token','where'=>array('token_type'=>$token_type)));
			$last_name=trim(post('last_name'));
			$first_name=trim(post('first_name'));
			$full_name = $first_name.' '.$last_name;
			$dataPost=array(
				'login_status'=>1,
				'access_user_email'=>$number,
			);
			$LID=insert_record('access_panel',$dataPost,TRUE);
			if($LID){
				$insdata=array('access_user_id'=>$LID,'member_name'=>$full_name,'member_phone'=>$number,'member_register_date'=>date('Y-m-d H:i:s'));
				$member_id=insert_record('member',$insdata,TRUE);
				
				if($member_id){
					$template='new-registration';
					$data_parse=array(
					'MEMBER_URL'=>ADMIN_URL.'member/list_record',
					);
					// SendMail(get_setting('admin_email'),$template,$data_parse);
					$data_pase = array(
						'FULL_NAME' => $full_name,
					);
					// $this->admin_notification_model->parse('admin-user-signup', $data_pase, 'member/list_record');
					
					$msg['status'] = 1;
					if($number){
						$smstext='Congratulations! Your account has been created successfully. Log in now to book our on-demand services. -SNAPHIVE';
						$sendSms=sendSMS($number,'1707177390286335778',$smstext);
						if($sendSms && $sendSms['status']==1){
							$response['status'] = 1;
							$response['otp'] = $otp;
						}else{
							$response['otp'] = $otp;
							$response['status'] = 0;
							$response['errors'] = 'SMS sending failed';
						}
					}
					
				}

				
				$result=array(
					'member_id'=>$member_id,
					'member_phone'=>$number,
				);
				$result['logo']= getMemberLogo($member_id);
				$result['profile_name']=$full_name;
				$device_token['android']=[];
				$device_token['ios']=[];
				$result['device_token']=$device_token;
				$result['login_status']=($dataPost['login_status'] ? "1":"0");
				$msg['data'] =$result;	
			}else{
				$msg['status'] = 0;
				$msg['errors'][$i]['id']='invalid';
				$msg['errors'][$i]['message']= 'invalid request';
			}	
			$result['member_id']= $member_id;
			$msg['status'] = 1;
			$msg['data'] =$result;
			
		}else{
			$msg['status'] = 0;
			$msg['errors'][$i]['id']='otp';
			$msg['errors'][$i]['message']= 'Invalid otp / otp expired';
		}
		
		
	
	
	
		//$memberDevice=$this->getmemberDevice($result->member_id);
		$android=$ios=array();
		/* if($memberDevice){
			foreach($memberDevice as $d=>$device){
				if($device->device_type=='android'){
					$android[]=$device->device_token;
				}elseif($device->device_type=='ios'){
					$ios[]=$device->device_token;
				}
			}
		} */
		$device_token['android']=$android;
		$device_token['ios']=$ios;
		$result['device_token']=$device_token;
		$msg['data'] =$result;	
		return $msg;

	}
	public function getAddress($id=''){
		$this->db->select('member_address_id as id,name,member_address_1 as address,member_landmark as landmark,member_address_2 as house_number,member_address_type as location_type,member_lat as lat,member_lng as lng,member_city as city,member_pincode as pincode, member_state as state')
			->from('member_address')
			// ->where('member_id', $id)
			->where('member_address_id', $id)
			->where('address_status', 1);

		return $this->db->get()->result_array();
	}
	public function book_service($member_id){
		$provider_religion = post('provider_religion');
		if ($provider_religion === 'any' || empty($provider_religion)) {
			$provider_religion = 0;
		}

		$provider_gender = post('pref_gender');
		if (empty($provider_gender)) {
			$provider_gender = 'any';
		}

		$insdata = array(
			'member_id' => $member_id,
			'cat_id' => post('cat_id'),
			'sub_cat_id' => post('sub_cat_id'),
			'provider_religion' => $provider_religion,
			'provider_gender' => $provider_gender,
			'booking_date' => post('booking_date'),
			'booking_time' => post('booking_time'),
			'duration_hours' => post('duration_hours'),
			'address_id' => post('address_id'),
			'status' => 1,
			'special_instructions' => post('special_instructions') ? post('special_instructions') : '',
			'created_at' => date('Y-m-d H:i:s'),
			'provider_id' => 0,
		);
		$ins = insert_record('booking_services',$insdata,TRUE);
		if($ins){
			$msg['status'] = 1;
			$msg['id'] = $ins;
		}else{
			$msg['status'] = 0;
		}
		return $msg;
	}
	// public function getWorkerList($subchild_id,$startDateTime,$endDateTime){
	// 	// return $this->db
    //     // ->select('worker_id')
    //     // ->from('pref_worker_service')
    //     // ->where('category_subchild_id', $subchild_id)
    //     // ->get()
    //     // ->result_array(); 
	// 	return $this->db
	// 		->select('ps.worker_id')
	// 		->from('pref_worker_service ps')
	// 		->where('ps.category_subchild_id', $subchild_id)

	// 		->where("NOT EXISTS (
	// 			SELECT 1 
	// 			FROM pref_providers_unavailablity pu
	// 			WHERE pu.worker_id = ps.worker_id
	// 			AND pu.start_time < ".$this->db->escape($endDateTime)."
	// 			AND pu.end_time > ".$this->db->escape($startDateTime)."
	// 		)", NULL, FALSE)

	// 		->where("NOT EXISTS (
	// 			SELECT 1 
	// 			FROM pref_booking_services bs
	// 			WHERE bs.provider_id = ps.worker_id
	// 			AND bs.status IN (1,2,3)
	// 			AND bs.booking_date IS NOT NULL
				
	// 			AND TIMESTAMP(bs.booking_date, bs.booking_time) < ".$this->db->escape($endDateTime)."
				
	// 			AND DATE_ADD(
	// 				TIMESTAMP(bs.booking_date, bs.booking_time),
	// 				INTERVAL bs.duration_hours HOUR
	// 			) > ".$this->db->escape($startDateTime)."
	// 		)", NULL, FALSE)

	// 		->get()
	// 		->result_array();
	// }	
	public function getWorkerList($subchild_id,$startDateTime,$endDateTime,$lat=0,$lng=0,$pref_gender='any'){
		$radius = 10;
		$worker_religion = $this->input->post('provider_religion');
		$lat = is_numeric($lat) ? (float)$lat : 0.0;
		$lng = is_numeric($lng) ? (float)$lng : 0.0;
		$has_coords = ($lat != 0.0 && $lng != 0.0);

		$res = $this->_fetchWorkerList($subchild_id, $startDateTime, $endDateTime, $lat, $lng, $pref_gender, $worker_religion, $has_coords ? $radius : null);

		// If no workers found within 10 km, fallback to wider radius or without distance limit so requests reach workers
		if (empty($res) && $has_coords) {
			$res = $this->_fetchWorkerList($subchild_id, $startDateTime, $endDateTime, $lat, $lng, $pref_gender, $worker_religion, 50);
			if (empty($res)) {
				$res = $this->_fetchWorkerList($subchild_id, $startDateTime, $endDateTime, $lat, $lng, $pref_gender, $worker_religion, null);
			}
		}

		return $res;
	}

	private function _fetchWorkerList($subchild_id, $startDateTime, $endDateTime, $lat, $lng, $pref_gender, $worker_religion, $radius = null){
		$has_coords = ($lat != 0.0 && $lng != 0.0);

		if ($has_coords) {
			$dist_select = "(
				CASE 
					WHEN wa.worker_lat IS NULL OR wa.worker_lng IS NULL OR wa.worker_lat = 0 OR wa.worker_lng = 0 
					THEN 0
					ELSE ROUND(
						6371 * ACOS(
							GREATEST(-1, LEAST(1,
								COS(RADIANS(".$this->db->escape($lat)."))
								* COS(RADIANS(wa.worker_lat))
								* COS(RADIANS(wa.worker_lng) - RADIANS(".$this->db->escape($lng)."))
								+ SIN(RADIANS(".$this->db->escape($lat)."))
								* SIN(RADIANS(wa.worker_lat))
							))
						), 2
					)
				END
			) AS distance";
		} else {
			$dist_select = "0 AS distance";
		}

		$this->db
			->select("ps.worker_id, " . $dist_select, FALSE)
			->from('pref_worker_service ps')
			->join('pref_worker_address wa', 'wa.worker_id = ps.worker_id', 'left')
			->join('pref_worker wr', 'wr.worker_id = ps.worker_id', 'left')
			->group_start()
				->where('wr.is_offline', 0)
				->or_where('wr.is_offline IS NULL', NULL, FALSE)
			->group_end()
			->where('ps.category_subchild_id', $subchild_id);

		// Religion filter
		if (!empty($worker_religion) && !in_array(strtolower(trim((string)$worker_religion)), ['any', 'all', '0'])) {
			if ($worker_religion == 1) {
				$this->db->where('wr.worker_religion', 1);
			} else {
				$this->db->where('wr.worker_religion !=', 1);
			}
		}

		// Gender filter (normalizes male/female/m/f/any)
		if (!empty($pref_gender)) {
			$gender = strtolower(trim((string)$pref_gender));
			if (!in_array($gender, ['any', 'all', '', '-1', '0'])) {
				if (in_array($gender, ['male', 'm'])) {
					$this->db->where('wr.worker_gender', 'M');
				} elseif (in_array($gender, ['female', 'f'])) {
					$this->db->where('wr.worker_gender', 'F');
				} else {
					$this->db->where('wr.worker_gender', $pref_gender);
				}
			}
		}

		// Worker unavailable time check (only if table exists in db)
		if ($this->db->table_exists('providers_unavailablity')) {
			$table_unavail = $this->db->dbprefix('providers_unavailablity');
			$this->db->where("NOT EXISTS (
				SELECT 1 
				FROM {$table_unavail} pu
				WHERE pu.worker_id = ps.worker_id
				AND pu.start_time < ".$this->db->escape($endDateTime)."
				AND pu.end_time > ".$this->db->escape($startDateTime)."
			)", NULL, FALSE);
		}

		// Already booked workers check
		$table_booking = $this->db->dbprefix('booking_services');
		$this->db->where("NOT EXISTS (
			SELECT 1 
			FROM {$table_booking} bs
			WHERE bs.provider_id = ps.worker_id
			AND bs.status IN (2,3)
			AND bs.booking_date IS NOT NULL
			AND bs.booking_date != '0000-00-00'
			AND TIMESTAMP(bs.booking_date, bs.booking_time) < ".$this->db->escape($endDateTime)."
			AND DATE_ADD(
				TIMESTAMP(bs.booking_date, bs.booking_time),
				INTERVAL bs.duration_hours HOUR
			) > ".$this->db->escape($startDateTime)."
		)", NULL, FALSE);

		$this->db->group_by('ps.worker_id');

		if ($radius !== null && $has_coords) {
			$this->db->having('distance <= ' . (float)$radius, NULL, FALSE);
		}

		$this->db->order_by('distance', 'ASC');

		return $this->db->get()->result_array();
	}


	
	public function getBookingDetails($booking_id){

		return $this->db
			->select("
				b.booking_id,
				b.booking_date,
				b.booking_time,
				b.updated_at,
				b.duration_hours,
				b.status,
				b.special_instructions,
				b.provider_id,
				b.provider_gender,
				COALESCE(mcc.amount, 0) AS cancel_charge,

				c.category_name,
				sc.category_subchild_name,
				cs.price,
				cs.next_hour_price,
				cs.late_night_price,
				CASE 
				WHEN cs.category_subchild_thumb = '' OR cs.category_subchild_thumb IS NULL 
				THEN CONCAT('".base_url('assets/default/images/default/noimage.jpg')."')
				ELSE CONCAT('".base_url('user_uploads/category_icons/thumb/')."', cs.category_subchild_thumb)
			END as category_subchild_thumb,
				b.member_id,
				m.member_name,
				a.member_address_1,
				a.member_address_2,
				a.member_landmark,
				a.member_lat,
				a.member_lng,

				COALESCE(MAX(CASE 
					WHEN o.otp_type='orderotp_start' THEN o.otp 
				END), '') as start_otp,

				COALESCE(MAX(CASE 
					WHEN o.otp_type='orderotp_end' THEN o.otp 
				END), '') as end_otp,

				i.invoice_id,
				i.invoice_number,
				i.paid_amount,
				i.invoice_date,
				i.invoice_status,
			")
			->from('pref_booking_services b')

			->join('pref_worker_otp o','o.order_id = b.booking_id','left')
			->join('pref_category_names c','c.category_id = b.cat_id AND c.category_lang = "'.$this->lang.'"','left')
			->join('pref_category_subchild cs','cs.category_subchild_id = b.sub_cat_id','left')
			->join('pref_category_subchild_names sc','sc.category_subchild_id = b.sub_cat_id AND sc.category_subchild_lang = "'.$this->lang.'"','left')
			->join('pref_member m','m.member_id=b.member_id','left')
			->join('pref_member_address a','a.member_address_id=b.address_id','left')
			// Invoice 
        	->join('pref_invoice i','i.invoice_order_id = b.booking_id','left')
			// MEMBER CANCEL CHARGES
			->join('pref_member_cancel_charges mcc', 'mcc.booking_id = b.booking_id', 'left')

			->where('b.booking_id', $booking_id)
			->get()
			->row_array(); 
	}
	public function save_reschedule_book($booking_id){
		$insdata = array(
			'booking_date' => post('booking_date'),
			'booking_time' => post('booking_time'),
			// 'duration_hours' => post('duration_hours'),
			
		);
		updateTable('booking_services',$insdata,array('booking_id'=>$booking_id));
		$msg['status'] = 1;
		return $msg;
	}
	public function update_booking_status($worker_id,$booking_id){
		$updata = array(
			'status' => 2,
			'provider_id' => $worker_id,
		);

		$this->db->where('booking_id', $booking_id);
		$this->db->where('status', 1);
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
	public function update_booking_progress($booking_id){
		$updata = array(
			'status' => 3,
			// 'provider_id' => $worker_id,
		);

		$this->db->where('booking_id', $booking_id);
		$this->db->where('status', 2);
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
	public function update_booking_cancel($booking_id){
		$msg=[];
		$msg['data']['booking_id']=$booking_id;
		$check=$this->db->select('status,booking_date,booking_time,member_id,provider_id')->from('booking_services')->where('booking_id', $booking_id)->where_in('status', [0,1,2])->get()->row();
		if($check){
			if($check->status==1){
				$updata = array(
					'status' => 5,
					'updated_at' => date('Y-m-d H:i:s'),
				);
				$this->db->where('booking_id', $booking_id);
				$this->db->where('status', 1);
				$updated = $this->db->update('booking_services', $updata);
				$msg['status']=1;
				$msg['data']['is_charge']=0;
				$msg['message']='Booking updated successfully';
			}elseif($check->status==2){
				$updata = array(
					'status' => 5,
					'updated_at' => date('Y-m-d H:i:s'),
				);
				$this->db->where('booking_id', $booking_id);
				$this->db->where('status', 2);
				$updated = $this->db->update('booking_services', $updata);
				$start_time=$check->booking_date.' '.$check->booking_time;
				$pre_cancel_time=date('Y-m-d H:i:s',strtotime('-30 minutes',strtotime($check->booking_date.' '.$check->booking_time)));
				$post_cancel_time=date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($check->booking_date.' '.$check->booking_time)));
				$msg['status']=1;
				$msg['data']['is_charge']=0;
				$msg['message']='No changes made';
				$msg['provider_id'] = $check->provider_id;
				if(time() > strtotime($start_time)){
					if(time() > strtotime($post_cancel_time)){
						// charge to worker
						$msg['data']['is_charge']=2;
						$penalty_fee_amount=get_setting('pro_cancel_charge');
						$profit_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
						if (empty($profit_details)) {
							$profit_details = getWallet(17);
						}
						$profit_wallet_id=$profit_details ? $profit_details->wallet_id : 17;
						$profit_wallet_balance=$profit_details ? $profit_details->balance : 0;
						$workerWalletDetails = getWalletWorker($check->provider_id); 
						$wallet_transaction_type_id=get_setting('PENALTY_PAYMENT');
						if (empty($wallet_transaction_type_id)) {
							$txn_type = getFieldData('wallet_transaction_type_id', 'wallet_transaction_type', 'title_tkey', 'PENALTY_PAYMENT');
							$wallet_transaction_type_id = !empty($txn_type) ? $txn_type : 16;
						}
						$current_datetime=date('Y-m-d H:i:s');
						$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
						if($wallet_transaction_id){
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$workerWalletDetails->wallet_id,'debit'=>$penalty_fee_amount,'description_tkey'=>'Penalty_Payment','relational_data'=>$booking_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
							'FW'=>$workerWalletDetails->worker_name.' wallet',
							'TW'=>$profit_details->title,	
							'TP'=>'Penalty_Payment',
							'PID'=>$booking_id,
							// 'CID'=>$contract_id,
							// 'CMID'=>$contract_milestone_id,
							));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
							
							$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$penalty_fee_amount,'description_tkey'=>'Penalty_Payment','relational_data'=>$booking_id);
							$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
								'FW'=>$workerWalletDetails->worker_name.' wallet',
								'TW'=>$profit_details->title,	
								'TP'=>'Penalty_Payment',
								'PID'=>$booking_id,
								// 'CID'=>$contract_id,
								// 'CMID'=>$contract_milestone_id,
								));
							insert_record('wallet_transaction_row',$insert_wallet_transaction_row);

							
							/*  Update Member Balance */
							$new_member_balance = displayamount($workerWalletDetails->balance,2) - displayamount($penalty_fee_amount,2);
							updateTable('wallet',['balance'=>$new_member_balance],['wallet_id'=>$workerWalletDetails->wallet_id]);
							wallet_balance_check($workerWalletDetails->wallet_id,['transaction_id'=>$wallet_transaction_id]);

							/*  Commission Balance */
							$new_profit_balance = displayamount($profit_details->balance,2) + displayamount($penalty_fee_amount,2);
							updateTable('wallet',['balance'=>$new_profit_balance],['wallet_id'=>$profit_details->wallet_id]);
							wallet_balance_check($profit_details->wallet_id,['transaction_id'=>$wallet_transaction_id]);

							$msg['data']['txn_id']=$wallet_transaction_id;
						}
						$msg['message']='Charge apply to worker';
					}
				}else{
					if(time() > strtotime($pre_cancel_time)){
						// charge to customer
						$msg['data']['is_charge']=1;
						$member_cancel_charges=[
							'member_id'=>$check->member_id,
							'booking_id'=>$booking_id,
							'amount'=>50,
							'status'=>0,
							'reg_date'=>date('Y-m-d H:i:s'),
						];
						$this->db->insert('member_cancel_charges',$member_cancel_charges);
						$msg['message']='Charge apply to customer';
					}
				}
			}
			
		}else{
			$msg['status']=0;
			$msg['message']='Update failed';
		}
		return $msg;
	}
	public function update_booking_completed($booking_id){
		$updata = array(
			'status' => 4,
			// 'provider_id' => $worker_id,
		);

		$this->db->where('booking_id', $booking_id);
		$this->db->where('status', 3);
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
	public function get_lat_lng($member_address_id){
		$this->db->select('name,member_landmark,member_address_1,member_address_2,member_address_type,member_mobile,member_lat as lat,member_lng as lng')
			->from('member_address')
			->where('member_address_id', $member_address_id)
			->where('address_status !=', 0);

		return $this->db->get()->row_array();
	}
	public function getAllAddress($id='',$limit = 0, $offset = 40, $for_list = TRUE){
		$this->db->select('member_address_id as id,name,member_address_1 as address,member_landmark as landmark,member_address_2 as house_number,member_address_type as location_type,member_lat as lat,member_lng as lng,address_status as status,member_city as city,member_pincode as pincode')
			->from('member_address')
			->where('member_id', $id)
			->where('address_status', 1);

		if ($for_list) {
			$result = $this->db
				->limit($offset, $limit)
				->get()
				->result_array();
		} else {
			$result = $this->db->get()->num_rows();
		}

		return $result;
	}
	// public function booking_list($srch_param=array() , $limit=0 , $offset=40 , $for_list=TRUE){
		
		
	// 	$this->db->select("b.booking_id,b.status,b.booking_date,
	// 			b.booking_time,
	// 			b.duration_hours,
	// 			b.status,
	// 			b.address_id,
	// 			b.special_instructions,cs.price as price_per_unit,
	// 			sc.category_subchild_name,m.worker_name,b.provider_id,i.invoice_status,ROUND(b.duration_hours * cs.price, 2) as total_amount",FALSE)
	// 	->from('booking_services b')
	// 	->join('worker m','m.worker_id=b.provider_id','left')
	// 	->join('pref_category_subchild cs','cs.category_subchild_id = b.sub_cat_id','left')
	// 	->join('pref_category_subchild_names sc','sc.category_subchild_id = b.sub_cat_id AND sc.category_subchild_lang = "'.$this->lang.'"','left')
	// 	->join('pref_invoice i','i.invoice_order_id = b.booking_id','left');
		
	// 	if(array_key_exists('member_id', $srch_param)){
	// 	   $this->db->where('b.member_id',$srch_param['member_id']);
	// 	}
		
	// 	if(array_key_exists('worker_id', $srch_param)){
	// 	   $this->db->where('b.provider_id',$srch_param['worker_id']);
	// 	}
	// 	if(array_key_exists('status', $srch_param)){
	// 	   $this->db->where('b.status',$srch_param['status']);
	// 	}
		
		
	// 	if($for_list){
	// 		$result = $this->db->limit($offset , $limit)->order_by("b.booking_id" , "DESC")->get()->result_array();
			
	// 	}else{
	// 		$result = $this->db->get()->num_rows();
	// 	}
		
	// 	return $result;
		
	// }
	public function getProfile($id=''){
		$this->db->select('member_name,member_gender,member_email,member_phone,member_wanum,member_dob')
			->from('member')
			// ->where('member_id', $id)
			->where('member_id', $id);

		return $this->db->get()->result_array();
	}
	public function booking_list($srch_param = array(), $limit = 0, $offset = 40, $for_list = TRUE){
		$this->db->select("
			b.booking_id,
			b.status,
			b.booking_date,
			b.booking_time,
			b.duration_hours,
			b.address_id,
			b.special_instructions,
			b.updated_at,
			COALESCE(mcc.amount, 0) AS cancel_charge,

			cs.price AS price_per_unit,
			cs.next_hour_price,
			cs.late_night_price,

			sc.category_subchild_name,
			m.worker_name,
			b.provider_id,
			i.invoice_status,

			ma.name,
			ma.member_address_1,
			ma.member_address_2,
			ma.member_landmark,
			ma.member_mobile,
			ma.member_lat,
			ma.member_lng,
			me.member_name,
			

		

			ROUND(
		IF(
			ma.member_lat IS NOT NULL 
			AND ma.member_lng IS NOT NULL
			AND wa.worker_lat IS NOT NULL 
			AND wa.worker_lng IS NOT NULL,

			6371 * ACOS(
				COS(RADIANS(CAST(ma.member_lat AS DECIMAL(10,8))))
				* COS(RADIANS(CAST(wa.worker_lat AS DECIMAL(10,8))))
				* COS(
					RADIANS(CAST(wa.worker_lng AS DECIMAL(10,8))) -
					RADIANS(CAST(ma.member_lng AS DECIMAL(10,8)))
				)
				+ SIN(RADIANS(CAST(ma.member_lat AS DECIMAL(10,8))))
				* SIN(RADIANS(CAST(wa.worker_lat AS DECIMAL(10,8))))
			),

			0
		)
		, 2) AS distance
		", FALSE)

		->from('booking_services b')
		->join('worker m', 'm.worker_id = b.provider_id', 'left')
		->join('pref_category_subchild cs', 'cs.category_subchild_id = b.sub_cat_id', 'left')
		->join('pref_category_subchild_names sc', 'sc.category_subchild_id = b.sub_cat_id AND sc.category_subchild_lang = "'.$this->lang.'"', 'left')
		->join('pref_invoice i', 'i.invoice_order_id = b.booking_id', 'left')

		// MEMBER
		->join('pref_member me', 'me.member_id = b.member_id', 'left')
		// MEMBER ADDRESS
		->join('pref_member_address ma', 'ma.member_address_id = b.address_id', 'left')

		// WORKER ADDRESS
		->join('pref_worker_address wa', 'wa.worker_id = b.provider_id', 'left')
		// MEMBER CANCEL CHARGES
		->join('pref_member_cancel_charges mcc', 'mcc.booking_id = b.booking_id', 'left');

		


		// Filters
		if (!empty($srch_param['member_id'])) {
			$this->db->where('b.member_id', $srch_param['member_id']);
		}

		if (!empty($srch_param['worker_id'])) {
			$this->db->where('b.provider_id', $srch_param['worker_id']);
		}

		if (!empty($srch_param['status'])) {
			$this->db->where('b.status', $srch_param['status']);
		}


		//  Result
		if ($for_list) {
			$result = $this->db
				->limit($offset, $limit)
				->order_by("b.booking_id", "DESC")
				->get()
				->result_array();
		} else {
			$result = $this->db->get()->num_rows();
		}

		return $result;
	}
	public function get_subchild_name($id){
		$this->db->select('category_subchild_name')
			->from('category_subchild_names')
			->where('category_subchild_lang', $this->lang)
			->where('category_subchild_id', $id);

		return $this->db->get()->row_array();
	}
	

	public function get_time($id=''){
		$this->db->select('start_time,end_time')
			->from('worker_time_log')
			->where('order_id', $id);

		return $this->db->get()->row();
	}
	public function release_cancel_charges($booking_id,$invoice_id){
		$worker_id=getField('provider_id','booking_services','booking_id',$booking_id);
		$cancel_charges = getField('cancel_charges','invoice','invoice_id',$invoice_id);
		$cancel_data=$this->db->select('amount,booking_id')->from('member_cancel_charges')
					->where('invoice_id',$invoice_id)->where('status',1)->get()->result();
		if($cancel_data){
			$profit_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
			if (empty($profit_details)) {
				$profit_details = getWallet(17);
			}
			$profit_wallet_id=$profit_details ? $profit_details->wallet_id : 17;
			$profit_wallet_balance=$profit_details ? $profit_details->balance : 0;

			$workerWalletDetails = getWalletWorker($worker_id); 
			if (empty($workerWalletDetails)) {
				return 0;
			}

			$mainwallet_details = getWallet(get_setting('SITE_MAIN_WALLET'));
			if (empty($mainwallet_details)) {
				$mainwallet_details = getWallet(16);
			}
			$wallet_transaction_type_id=get_setting('CANCEL_PAID');
			if (empty($wallet_transaction_type_id)) {
				$txn_type = getFieldData('wallet_transaction_type_id', 'wallet_transaction_type', 'title_tkey', 'CANCEL_PAID');
				$wallet_transaction_type_id = !empty($txn_type) ? $txn_type : 14;
			}
			$current_datetime=date('Y-m-d H:i:s');
			$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
			if($wallet_transaction_id){
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$workerWalletDetails->wallet_id,'debit'=>$cancel_charges,'description_tkey'=>'Cancel_Booking','relational_data'=>'');
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$workerWalletDetails->worker_name.' wallet',
					'TW'=>$mainwallet_details ? $mainwallet_details->title : 'Site Main Wallet',	
					'TP'=>'Cancel_Payment',
					'PID'=>$booking_id,
					// 'CID'=>$contract_id,
					// 'CMID'=>$contract_milestone_id,
					));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);

					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$mainwallet_details ? $mainwallet_details->wallet_id : 16,'credit'=>$cancel_charges,'description_tkey'=>'Cancel_Booking','relational_data'=>'');
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$workerWalletDetails->worker_name.' wallet',
						'TW'=>$mainwallet_details ? $mainwallet_details->title : 'Site Main Wallet',	
						'TP'=>'Cancel_Payment',
						'PID'=>$booking_id,
						// 'CID'=>$contract_id,
						// 'CMID'=>$contract_milestone_id,
						));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
					$total_profit_amount=0;
					foreach($cancel_data as $k=>$row){
					$b_amount=$row->amount;
					$workerWalletDetails_cancel = getWalletWorker($this->worker_id); 
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$mainwallet_details ? $mainwallet_details->wallet_id : 16,'debit'=>$b_amount,'description_tkey'=>'Cancel_Booking','relational_data'=>'Booking Id:'.$row->booking_id);
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$mainwallet_details ? $mainwallet_details->title : 'Site Main Wallet',
						'TW'=>$profit_details ? $profit_details->title : 'Site Profit Wallet',	
						'TP'=>'Cancel_Payment',
						'PID'=>$row->booking_id,
						));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);

					$amount=$b_amount/2;
					$total_profit_amount=$total_profit_amount+$amount;
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$amount,'description_tkey'=>'Cancel_Booking','relational_data'=>'Booking Id:'.$row->booking_id);
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$mainwallet_details ? $mainwallet_details->title : 'Site Main Wallet',
						'TW'=>$profit_details ? $profit_details->title : 'Site Profit Wallet',		
						'TP'=>'Cancel_Payment',
						'PID'=>$row->booking_id,
						));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);

					$cancel_worker_id=getField('provider_id','booking_services','booking_id',$row->booking_id);
					$cancel_workerWalletDetails = getWalletWorker($worker_id); 

					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$amount,'description_tkey'=>'Cancel_Booking','relational_data'=>'Booking Id:'.$row->booking_id);
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>($mainwallet_details ? $mainwallet_details->title : 'Site Main') . ' wallet',
						'TW'=>($cancel_workerWalletDetails ? $cancel_workerWalletDetails->worker_name : 'Worker') . ' wallet',
						'TP'=>'Cancel_Payment',
						'PID'=>$row->booking_id,
						));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);


					/*  Update Member Balance */
					if ($cancel_workerWalletDetails) {
						$new_worker_balance = displayamount($cancel_workerWalletDetails->balance,2) - displayamount($amount,2);
						updateTable('wallet',['balance'=>$new_worker_balance],['wallet_id'=>$cancel_workerWalletDetails->wallet_id]);
						wallet_balance_check($cancel_workerWalletDetails->wallet_id,['transaction_id'=>$wallet_transaction_id]);
					}

					}

					/*  Update Member Balance */
				$new_member_balance = displayamount($workerWalletDetails->balance,2) - displayamount($cancel_charges,2);
				updateTable('wallet',['balance'=>$new_member_balance],['wallet_id'=>$workerWalletDetails->wallet_id]);
				wallet_balance_check($workerWalletDetails->wallet_id,['transaction_id'=>$wallet_transaction_id]);


				/*  Commission Balance */
				if ($profit_details) {
					$new_profit_balance = displayamount($profit_details->balance,2) + displayamount($total_profit_amount,2);
					updateTable('wallet',['balance'=>$new_profit_balance],['wallet_id'=>$profit_details->wallet_id]);
					wallet_balance_check($profit_details->wallet_id,['transaction_id'=>$wallet_transaction_id]);
				}

			}
		}
		return $wallet_transaction_id;
	}
	public function release_tax_charges($booking_id,$invoice_id){
		$worker_id=getField('provider_id','booking_services','booking_id',$booking_id);
		$tax_amount = getField('tax_amount','invoice','invoice_id',$invoice_id);
		
		if($tax_amount){
			$profit_details=getWallet(get_setting('TAX_PAYMENT_WALLET'));
			if (empty($profit_details)) {
				$profit_details = getWallet(get_setting('SITE_PROFIT_WALLET'));
			}
			if (empty($profit_details)) {
				$profit_details = getWallet(17);
			}
			$profit_wallet_id=$profit_details ? $profit_details->wallet_id : 17;
			$profit_wallet_balance=$profit_details ? $profit_details->balance : 0;

			$workerWalletDetails = getWalletWorker($worker_id); 
			if (empty($workerWalletDetails)) {
				return 0;
			}
			
			$wallet_transaction_type_id=get_setting('TAX_PAYMENT');
			if (empty($wallet_transaction_type_id)) {
				$txn_type = getFieldData('wallet_transaction_type_id', 'wallet_transaction_type', 'title_tkey', 'TAX_PAYMENT');
				$wallet_transaction_type_id = !empty($txn_type) ? $txn_type : 15;
			}
			$current_datetime=date('Y-m-d H:i:s');
			$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
			if($wallet_transaction_id){
					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$workerWalletDetails->wallet_id,'debit'=>$tax_amount,'description_tkey'=>'Tax_payment','relational_data'=>'');
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$workerWalletDetails->worker_name.' wallet',
					'TW'=>$profit_details ? $profit_details->title : 'Tax Wallet',	
					'TP'=>'Tax_payment',
					'PID'=>$booking_id,
					// 'CID'=>$contract_id,
					// 'CMID'=>$contract_milestone_id,
					));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);

					$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$tax_amount,'description_tkey'=>'Cancel_Booking','relational_data'=>'');
					$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
						'FW'=>$workerWalletDetails->worker_name.' wallet',
						'TW'=>$profit_details ? $profit_details->title : 'Tax Wallet',	
						'TP'=>'Tax_payment',
						'PID'=>$booking_id,
						// 'CID'=>$contract_id,
						// 'CMID'=>$contract_milestone_id,
						));
					insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
					

					/*  Update Member Balance */
				$new_member_balance = displayamount($workerWalletDetails->balance,2) - displayamount($tax_amount,2);
				updateTable('wallet',['balance'=>$new_member_balance],['wallet_id'=>$workerWalletDetails->wallet_id]);
				wallet_balance_check($workerWalletDetails->wallet_id,['transaction_id'=>$wallet_transaction_id]);


				/*  Tax Balance */
				if ($profit_details) {
					$new_profit_balance = displayamount($profit_details->balance,2) + displayamount($tax_amount,2);
					updateTable('wallet',['balance'=>$new_profit_balance],['wallet_id'=>$profit_details->wallet_id]);
					wallet_balance_check($profit_details->wallet_id,['transaction_id'=>$wallet_transaction_id]);
				}

			}
		}
		return $wallet_transaction_id;
	}

	public function send_notification_booking($oneSignalData,$ids){
		if($ids){
			foreach($ids as $worker_id){

				$this->sendpush_OneSignal($oneSignalData,$worker_id,'W');
			}
		}
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
	public function update_provider_booking_cancel($booking_id){
		$msg=[];
		$msg['data']['booking_id']=$booking_id;
		$check=$this->db->select('status,booking_date,booking_time,member_id,provider_id')->from('booking_services')->where('booking_id', $booking_id)->where_in('status', [0,1,2])->get()->row();
		// echo $this->db->last_query(); die;
		if($check){
			$updata = array(
				'status' => 5,
				'updated_at' => date('Y-m-d H:i:s'),
				'cancelled_by' => 'P',
			);
			$this->db->where('booking_id', $booking_id);
			// $this->db->where('status', 2);
			$updated = $this->db->update('booking_services', $updata);

			$msg['status']=1;
			$msg['data']['is_charge']=0;
			$msg['message']='No changes made';
			$msg['provider_id'] = $check->provider_id;
			
			// charge to worker
			$msg['data']['is_charge']=2;
			$penalty_fee_amount=get_setting('pro_cancel_charge');
			$profit_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
			if (empty($profit_details)) {
				$profit_details = getWallet(17);
			}
			$profit_wallet_id=$profit_details ? $profit_details->wallet_id : 17;
			$profit_wallet_balance=$profit_details ? $profit_details->balance : 0;
			$workerWalletDetails = getWalletWorker($check->provider_id); 
			$wallet_transaction_type_id=get_setting('PENALTY_PAYMENT');
			if (empty($wallet_transaction_type_id)) {
				$txn_type = getFieldData('wallet_transaction_type_id', 'wallet_transaction_type', 'title_tkey', 'PENALTY_PAYMENT');
				$wallet_transaction_type_id = !empty($txn_type) ? $txn_type : 16;
			}
			$current_datetime=date('Y-m-d H:i:s');
			$wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
			if($wallet_transaction_id){
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$workerWalletDetails->wallet_id,'debit'=>$penalty_fee_amount,'description_tkey'=>'Penalty_Payment','relational_data'=>$booking_id);
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
				'FW'=>$workerWalletDetails->worker_name.' wallet',
				'TW'=>$profit_details->title,	
				'TP'=>'Penalty_Payment',
				'PID'=>$booking_id,
				// 'CID'=>$contract_id,
				// 'CMID'=>$contract_milestone_id,
				));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
				
				$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$profit_wallet_id,'credit'=>$penalty_fee_amount,'description_tkey'=>'Penalty_Payment','relational_data'=>$booking_id);
				$insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
					'FW'=>$workerWalletDetails->worker_name.' wallet',
					'TW'=>$profit_details->title,	
					'TP'=>'Penalty_Payment',
					'PID'=>$booking_id,
					// 'CID'=>$contract_id,
					// 'CMID'=>$contract_milestone_id,
					));
				insert_record('wallet_transaction_row',$insert_wallet_transaction_row);

				
				/*  Update Member Balance */
				$new_member_balance = displayamount($workerWalletDetails->balance,2) - displayamount($penalty_fee_amount,2);
				updateTable('wallet',['balance'=>$new_member_balance],['wallet_id'=>$workerWalletDetails->wallet_id]);
				wallet_balance_check($workerWalletDetails->wallet_id,['transaction_id'=>$wallet_transaction_id]);

				/*  Commission Balance */
				$new_profit_balance = displayamount($profit_details->balance,2) + displayamount($penalty_fee_amount,2);
				updateTable('wallet',['balance'=>$new_profit_balance],['wallet_id'=>$profit_details->wallet_id]);
				wallet_balance_check($profit_details->wallet_id,['transaction_id'=>$wallet_transaction_id]);

				$msg['data']['txn_id']=$wallet_transaction_id;
			}
			$msg['message']='Charge apply to worker';
				
		}else{
			$msg['status']=0;
			$msg['message']='Update failed';
		}
		return $msg;
	}
	
}
