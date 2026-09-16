<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MX_Controller {
	
	private $data;
	
    public function __construct() {
		parent::__construct();
		$this->loggedUser=$this->session->userdata('loggedUser');
        $this->load->model('user_model');
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		if($this->loggedUser && in_array($this->data['curr_method'],array('login','signup','forgot'))){
			redirect(get_link('dashboardURL'));
		}
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
		));
    }

	public function login() {
		$breadcrumb = array(
			array(
				'title'=>'Login',
				'path'=>''
			)
		);
		$this->data['breadcrumb']=breadcrumb($breadcrumb,'Login');
		$this->layout->view('login', $this->data);
	}

	public function forgot() {
		$breadcrumb = array(
			array(
				'title'=>'Forgot Password',
				'path'=>''
			)
		);
		$this->data['breadcrumb']=breadcrumb($breadcrumb,'Forgot Password');
		$this->layout->view('forgot', $this->data);
	}

	public function signup() {
		redirect(base_url());
		$breadcrumb = array(
			array(
				'title'=>'Signup',
				'path'=>''
			)
		);
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
			'cropper.min.js',
			'main-editprofile.js',
			'bootstrap-tagsinput.min.js',
			'typeahead.bundle.min.js',
			'upload-drag-file.js'
		));
		$this->layout->set_css(array(
			'bootstrap-tagsinput.css',
			'cropper.min.css',
		));
		$this->data['country']=getAllCountry();
		$this->data['religion']=get_all_religion();
		$this->data['all_service']= $this->user_model->getData();
		$this->data['breadcrumb']=breadcrumb($breadcrumb,'Signup');
		$this->layout->view('signup', $this->data);
	}
	public function usersignupCheckAjax()
	{
		$this->load->library('form_validation');
		$this->load->library('bcrypt');
		checkrequestajax();
		$i=0;
		$msg=array();
		$step=1;
		$is_employer=0;
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
				$this->form_validation->set_rules('agree_term', 'term', 'required|trim|xss_clean');
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

						insert_record('worker_address',$worker_address,FALSE);
						$profile_name=$dataPost['worker_name'];
						insert_record('wallet',array('worker_id'=>$worker_id,'title'=>$profile_name,'balance'=>0),FALSE);
						if(trim(post('logo'))){
							insert(array('table'=>'worker_logo','data'=>array('worker_id'=>$worker_id,'logo'=>trim(post('logo')),'status'=>1,'reg_date'=>date('Y-m-d H:i:s'))),TRUE);
						}
						$services=post('service');
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
						$this->admin_notification_model->parse('admin-worker-signup', $data_pase, 'worker/list_record');
						$msg['status'] = 'OK';
						$msg['name'] = $profile_name;
						$msg['redirect'] =URL::get_link('registerSuccessURL');
						
					}else{
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = 'email';
						$msg['errors'][$i]['message'] = 'Error in process';
					}
				}
					
			}		
		}
		unset($_POST);
		echo json_encode($msg);		
	}

	public function signup_agency() {
		redirect(base_url());
		$breadcrumb = array(
			array(
				'title'=>'Signup',
				'path'=>''
			)
		);
		$this->layout->set_js(array(
			'utils/helper.js',
			'bootbox_custom.js',
			'mycustom.js',
			'cropper.min.js',
			'main-editprofile.js',
			'bootstrap-tagsinput.min.js',
			'typeahead.bundle.min.js',
			'upload-drag-file.js'
		));
		$this->layout->set_css(array(
			'bootstrap-tagsinput.css',
			'cropper.min.css',
		));
		$this->data['country']=getAllCountry();
		$this->data['religion']=get_all_religion();
		$this->data['all_service']= $this->user_model->getData();
		$this->data['breadcrumb']=breadcrumb($breadcrumb,'Signup');
		$this->layout->view('signup-agency', $this->data);
	}
	public function usersignupAgencyCheckAjax()
	{
		$this->load->library('form_validation');
		$this->load->library('bcrypt');
		checkrequestajax();
		$i=0;
		$msg=array();
		$step=1;
		$is_employer=0;
		if($this->input->post()){
			$this->form_validation->set_rules('step', 'Step', 'required|trim|xss_clean');
			if(post('step')){
				$step=post('step');
			}
			if($step>=1){
				$this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
				$this->form_validation->set_rules('agency_name', 'Agency Name', 'required|trim|xss_clean');
				$this->form_validation->set_rules('phone', 'phone', 'required|trim|xss_clean|xss_clean|regex_match[/^[0-9]{10}$/]|is_unique[agency.agency_phone]',array('is_unique' => 'This phone already exists.'));
				
				$this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
				$this->form_validation->set_rules('whatsapp', 'whatsapp', 'trim|xss_clean|xss_clean|regex_match[/^[0-9]{10}$/]');
			}
			if($step==2){
				$this->form_validation->set_rules('agency_address', 'address', 'required|trim|xss_clean');
				$this->form_validation->set_rules('agency_city', 'city', 'required|trim|xss_clean');
				$this->form_validation->set_rules('agency_state', 'state', 'required|trim|xss_clean');
				$this->form_validation->set_rules('agency_pincode', 'pincode', 'required|trim|xss_clean|regex_match[/^[0-9]{6}$/]');
				$this->form_validation->set_rules('agency_landmark', 'landmark', 'required|trim|xss_clean');
				$this->form_validation->set_rules('agree_term', 'term', 'required|trim|xss_clean');
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
					$name=trim(post('agency_name'));
					$dataPost=array(
						'agency_name'=>$name,
						'agency_member_name'=>trim(post('name')),
						'agency_email'=>$email,
						'agency_register_date'=>date('Y-m-d H:i:s'),
						'agency_phone'=>trim(post('phone')),
						'agency_whatsapp'=>trim(post('whatsapp')),
					);

					$worker_id=insert_record('agency',$dataPost,TRUE);
					if($worker_id){
						$worker_address=[
							'agency_id'=>$worker_id,
							'agency_state'=>post('agency_state'),
							'agency_city'=>post('agency_city'),
							'agency_address'=>post('agency_address'),
							'agency_pincode'=>post('agency_pincode'),
							'agency_landmark'=>post('agency_landmark'),
						];

						insert_record('agency_address',$worker_address,FALSE);
						$profile_name=$dataPost['agency_name'];
						
						
						$template='new-registration-agency';
						$data_parse=array(
						'MEMBER_URL'=>ADMIN_URL.'agency/list_record',
						);
						//SendMail(get_setting('admin_email'),$template,$data_parse);
						$data_pase = array(
							'FULL_NAME' => $profile_name
						);
						$this->admin_notification_model->parse('admin-agency-signup', $data_pase, 'agency/list_record');
						$msg['status'] = 'OK';
						$msg['name'] = $profile_name;
						$msg['redirect'] =URL::get_link('registerSuccessURL');
						
					}else{
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = 'email';
						$msg['errors'][$i]['message'] = 'Error in process';
					}
				}
					
			}		
		}
		unset($_POST);
		echo json_encode($msg);		
	}
	public function success(){
		$this->data['is_valid']=0;
		$this->layout->view('register-success', $this->data);
	}
	
	
	public function userverify($verifycode=''){
		
		$this->data['verifycode']=$verifycode;
		$this->data['is_valid']=0;
		if($verifycode){
			$time=date('Y-m-d H:i:s',strtotime('-1 hours'));
			$verifyData=getData(array(
				'select'=>'a.member_id',
				'table'=>'profile_verify_token a',
				'where'=>array('a.token_value'=>$verifycode,'token_type'=>'REGISTER','sent_date >='=>$time),
				'single_row'=>true,
				));
			if($verifyData){
				$this->data['is_valid']=1;
				updateTable('member',array('is_email_verified'=>1),array('member_id'=>$verifyData->member_id));
				delete(array('table'=>'profile_verify_token','where'=>array('member_id'=>$verifyData->member_id,'token_type'=>'REGISTER')));
				$member_id=$verifyData->member_id;
				$RECEIVER_EMAIL=getFieldData('member_email','member','member_id',$member_id);
				$data_parse=array(
				'MEMBER_NAME'=>getFieldData('member_name','member','member_id',$member_id),
				);
				$template='user-email-verified';
				SendMail($RECEIVER_EMAIL,$template,$data_parse);
				
			/*	loadModel('notifications/notification_model');
				$notificationData=array(
				'sender_id'=>0,
				'receiver_id'=>$member_id,
				'template'=>'welcome_notification_verified',
				'url'=>$this->config->item('dashboardURL'),
				'content'=>json_encode(array('MID'=>$member_id)),
				);
				$this->notification_model->savenotification($notificationData);*/
			}
		}
		$this->layout->view('verify-user', $this->data);
	}
	public function resendemail(){
		checkrequestajax();
		$msg=array();
		$this->loggedUser=$this->session->userdata('loggedUser');
		if($this->loggedUser){
			$LID=$this->loggedUser['LID'];
			$MID=$this->loggedUser['MID'];
			$token = md5(time().'-'.$LID);
			$insdataToken=array('access_user_id'=>$LID,'member_id'=>$MID,'token_value'=>$token,'sent_date'=>date('Y-m-d H:i:s'),'access_ip'=>$this->input->ip_address(),'token_type'=>'REGISTER');
			delete(array('table'=>'profile_verify_token','where'=>array('access_user_id'=>$LID,'token_type'=>'REGISTER')));
			$id=insert_record('profile_verify_token',$insdataToken,TRUE);
			$url=URL::get_link('VerifyURL').$token;
			$template='email-verification';
			$data_parse=array(
			'MEMBER_NAME'=>getFieldData('member_name','member','member_id',$MID),
			'VERIFICATION_URL'=>$url,
			);
			$to=getFieldData('access_user_email','access_panel','access_user_id',$LID);
			//$to='asish9735@gmail.com';
			SendMail($to,$template,$data_parse);
			$msg['status']='OK';
		}else{
			$msg['status']='FAIL';
		}
		echo json_encode($msg);		
	}
	
	public function get_save_logo_check(){
		$this->load->library('form_validation');
		
		$i=0;
		$msg=array();

		if($this->input->post()){
			$form_type=post('formtype');
			$dataid=post('dataid');
			if($form_type=='logo')
			{
				$member_id=time().'-'.rand(0,999);
				$up=0;
				$i=0;
				if($i==0){
					$this->load->library('cropimage');
					$crop=$this->cropimage->cropimageP(UPLOAD_PATH."worker-logo/".md5($member_id)."-");
					$filepathFullpath=$crop -> getResult();
					$a=explode("/",$filepathFullpath);
					$filename=end($a);
					$response = array(
					'state'  => 200,
					'message' => $crop -> getMsg(),
					'filename'=>$filename,
					'fullpath'=>UPLOAD_HTTP_PATH."worker-logo/".$filename,
					);
					if($response['filename']){
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
						$up=1;
					}
				if($up){
					$msg =$response;
					$msg['status'] = 'OK';
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'logo';
					$msg['errors'][$i]['message'] = 'Invalid ';
					$i++;
				}
				}	
			}
		}
		
		unset($_POST);
		echo json_encode($msg);
		
	}

}
