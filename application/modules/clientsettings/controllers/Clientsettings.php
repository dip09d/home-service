<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientsettings extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='F';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->organization_id=$this->loggedUser['OID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}else{
			redirect(URL::get_link('loginURL').'?ref=dashboardURL');
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();	
		$this->load->library('form_validation');	
		
		//print_r($this->loggedUser);
		parent::__construct();
	}
	public function index()
	{
		redirect(get_link('settingclientaccountInfoURL'));
	}
	public function client_contact_info()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
				'cropper.min.js',
				'main-editprofile.js',
			));
		$this->layout->set_css(array(
				'bootstrap-tagsinput.css',
				'cropper.min.css',
			));
		if($this->loggedUser){
			$member_id=$this->member_id;		
			$memberData=getData(array(
				'select'=>'o.organization_name,o.organization_email,m.member_name,m.member_email',
				'table'=>'member as m',
				'join'=>array(array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left')),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			));	
			if($memberData){
				$this->data['organizationInfo']=$memberData;
				$this->data['organization_id']=$this->organization_id;
				$this->data['member_id']=$this->member_id;
				$this->data['left_panel']=load_view('inc/client-setting-left','',TRUE);
				$this->layout->view('contact-info', $this->data);
			}
		}	
	}
	public function contact_info_account_data()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$member_id=$this->member_id;		
			$memberData=getData(array(
				'select'=>'o.organization_name,o.organization_email,m.member_name,m.member_email',
				'table'=>'member as m',
				'join'=>array(array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left')),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			));	
			if($memberData){
				$this->data['organizationInfo']=$memberData;
				$this->layout->view('ajax-contact-info-account-display', $this->data,TRUE);
			}
		}
	}
	public function contact_info_account_form()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$member_id=$this->member_id;		
			$memberData=getData(array(
				'select'=>'o.organization_name,o.organization_email,m.member_name,m.member_email',
				'table'=>'member as m',
				'join'=>array(array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left')),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			));	
			if($memberData){
				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-contact-info-account-form', $this->data,TRUE);
			}
		}
	}
	public function contact_info_account_form_check()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		$this->form_validation->set_rules('name', 'name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('company_name', 'comapny name', 'required|trim|xss_clean');
		/*$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email');
		$this->form_validation->set_rules('fname', 'First name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('lname', 'last name', 'required|trim|xss_clean');*/
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
		}else{
			$member_id="";
			$organization_id="";
			if($this->loggedUser){
				$member_id=$this->member_id;
				$organization_id=$this->organization_id;
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'email';
				$msg['errors'][$i]['message'] = 'Invalid ';
				$i++;
			}

			if($i==0){
				$update=array(
				'table'=>'member',
				'where'=>array('member_id'=>$member_id),
				'data'=>array('member_name'=>trim(post('name')))
				);
				$up=update($update);
				if($up){
					$update=array(
					'table'=>'wallet',
					'where'=>array('user_id'=>$member_id),
					'data'=>array('title'=>trim(post('company_name')))
					);
					update($update);
					$update=array(
					'table'=>'organization',
					'where'=>array('organization_id'=>$organization_id),
					'data'=>array('organization_name'=>trim(post('company_name')))
					);
					update($update);
					$msg['status'] = 'OK';
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'email';
					$msg['errors'][$i]['message'] = 'Invalid ';
					$i++;
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);		
	}
	public function contact_company_data()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$member_id=$this->member_id;	
			$organization_id=$this->organization_id;	
			$memberData=getData(array(
				'select'=>'o.organization_name,o.organization_website,o.organization_heading,o.organization_info',
				'table'=>'organization as o',
				'where'=>array('o.organization_id'=>$organization_id),
				'single_row'=>true,
			));	
			if($memberData){
				$this->data['organizationInfo']=$memberData;
				$this->layout->view('ajax-contact-company-display', $this->data,TRUE);
			}
		}
	}
	public function contact_company_form()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$this->data['country']=getAllCountry();
			$member_id=$this->member_id;	
			$organization_id=$this->organization_id;
			$memberData=getData(array(
				'select'=>'o.organization_name,o.organization_website,o.organization_heading,o.organization_info',
				'table'=>'organization as o',
				'where'=>array('o.organization_id'=>$organization_id),
				'single_row'=>true,
			));		
			if($memberData){
				$this->data['organizationInfo']=$memberData;
				$this->layout->view('ajax-contact-company-form', $this->data,TRUE);
			}
		}
	}
	public function contact_company_form_check()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		$this->form_validation->set_rules('name', 'Company name', 'required|trim|xss_clean');
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
		}else{
			$member_id="";
			if($this->loggedUser){
				$member_id=$this->member_id;
				$organization_id=$this->organization_id;
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'name';
				$msg['errors'][$i]['message'] = 'Invalid ';
				$i++;
			}


			if($i==0){
				$updatedata=array(
				'organization_name'=>NULL,
				'organization_website'=>NULL,
				'organization_heading'=>NULL,
				'organization_info'=>NULL,
				);
				if(post('name')){
					$updatedata['organization_name']=trim(post('name'));
				}
				if(post('website')){
					$updatedata['organization_website']=addhttp(trim(post('website')));
				}
				if(post('heading')){
					$updatedata['organization_heading']=trim(post('heading'));
				}
				if(post('info')){
					$updatedata['organization_info']=trim(post('info'));
				}
				
				$update=array(
				'table'=>'organization',
				'where'=>array('organization_id'=>$organization_id),
				'data'=>$updatedata
				);
				$up=update($update);
				if($up){
					$update=array(
					'table'=>'wallet',
					'where'=>array('user_id'=>$member_id),
					'data'=>array('title'=>trim(post('name')))
					);
					update($update);
					$msg['status'] = 'OK';
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'name';
					$msg['errors'][$i]['message'] = 'Invalid ';
					$i++;
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);		
	}
	public function contact_location_data()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$member_id=$this->member_id;	
			$organization_id=$this->organization_id;
			$memberData=getData(array(
				'select'=>'m.member_name,o.organization_name,o_a.organization_timezone,o_a.organization_city,o_a.organization_state,o_a.organization_address_1,o_a.organization_address_2,o_a.organization_pincode,o_a.organization_mobile,o_a.organization_vat_number,o_a.display_in_invoice,o_a.organization_mobile_code,c_n.country_name',
				'table'=>'member as m',
				'join'=>array(
				array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left'),
				array('table'=>'organization_address as o_a','on'=>'o.organization_id=o_a.organization_id','position'=>'left'),
				array('table'=>'country as c','on'=>'o_a.organization_country=c.country_code','position'=>'left'),
				array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
				),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			));	
			if($memberData){
				$this->data['organizationInfo']=$memberData;
				$this->layout->view('ajax-contact-location-display', $this->data,TRUE);
			}
		}
	}
	public function contact_location_form()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$member_id=$this->member_id;	
			$organization_id=$this->organization_id;
			$this->data['country']=getAllCountry();
			$memberData=getData(array(
				'select'=>'m.member_name,o.organization_name,o_a.organization_country,o_a.organization_timezone,o_a.organization_city,o_a.organization_state,o_a.organization_address_1,o_a.organization_address_2,o_a.organization_pincode,o_a.organization_mobile,o_a.organization_vat_number,o_a.display_in_invoice,o_a.organization_mobile_code,c_n.country_name',
				'table'=>'member as m',
				'join'=>array(
				array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left'),
				array('table'=>'organization_address as o_a','on'=>'o.organization_id=o_a.organization_id','position'=>'left'),
				array('table'=>'country as c','on'=>'o_a.organization_country=c.country_code','position'=>'left'),
				array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
				),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			));	
			if($memberData){
				$this->data['organizationInfo']=$memberData;
				$this->layout->view('ajax-contact-location-form', $this->data,TRUE);
			}
		}
	}
	public function contact_location_form_check()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		$this->form_validation->set_rules('country', 'Country', 'required|trim|xss_clean');
		$this->form_validation->set_rules('address_1', 'Address 1', 'required|trim|xss_clean');
		$this->form_validation->set_rules('pincode', 'Zip', 'required|trim|xss_clean');
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
		}else{
			$member_id="";
			if($this->loggedUser){
				$member_id=$this->member_id;
				$organization_id=$this->organization_id;
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'email';
				$msg['errors'][$i]['message'] = 'Invalid ';
				$i++;
			}

			if($i==0){
				$updatedata=array(
				'organization_timezone'=>NULL,
				'organization_country'=>NULL,
				'organization_city'=>NULL,
				'organization_state'=>NULL,
				'organization_address_1'=>NULL,
				'organization_address_2'=>NULL,
				'organization_pincode'=>NULL,
				'organization_mobile_code'=>NULL,
				'organization_mobile'=>NULL,
				'organization_vat_number'=>NULL,
				);
				if(post('timezone')){
					$updatedata['organization_timezone']=trim(post('timezone'));
				}
				if(post('country')){
					$updatedata['organization_country']=trim(post('country'));
				}
				if(post('city')){
					$updatedata['organization_city']=trim(post('city'));
				}
				if(post('state')){
					$updatedata['organization_state']=trim(post('state'));
				}
				if(post('address_1')){
					$updatedata['organization_address_1']=trim(post('address_1'));
				}
				if(post('address_2')){
					$updatedata['organization_address_2']=trim(post('address_2'));
				}
				if(post('pincode')){
					$updatedata['organization_pincode']=trim(post('pincode'));
				}
				if(post('mobile')){
					$updatedata['organization_mobile']=trim(post('mobile'));
				}
				if(post('mobile_code')){
					$updatedata['organization_mobile_code']=trim(post('mobile_code'));
				}
				if(post('vat_number')){
					$updatedata['organization_vat_number']=trim(post('vat_number'));
				}
				$update=array(
				'table'=>'organization_address',
				'where'=>array('organization_id'=>$organization_id),
				'data'=>$updatedata
				);
				$up=update($update);
				if($up){
					$msg['status'] = 'OK';
				}else{
					$msg['status'] = 'FAIL';
					$msg['errors'][$i]['id'] = 'address';
					$msg['errors'][$i]['message'] = 'Invalid ';
					$i++;
				}
			}	
		}		
	}
	unset($_POST);
	echo json_encode($msg);		
	}
	public function logo_form_check()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		$this->form_validation->set_rules('avatar_data', 'avatar file', 'required|trim|xss_clean');
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
		}else{
			$member_id="";
			if($this->loggedUser){
				$member_id=$this->member_id;
				$organization_id=$this->organization_id;
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'email';
				$msg['errors'][$i]['message'] = 'Invalid ';
				$i++;
			}


			if($i==0){
				$this->load->library('cropimage');
				$crop=$this->cropimage->cropimageP(UPLOAD_PATH."company-logo/".md5($organization_id)."-");
				$filepathFullpath=$crop -> getResult();
				$a=explode("/",$filepathFullpath);
				$filename=end($a);
				$response = array(
				  'state'  => 200,
				  'message' => $crop -> getMsg(),
				  'filename'=>$filename,
				  'fullpath'=>UPLOAD_HTTP_PATH."company-logo/".$filename,
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
					$profile_logo=getFieldData('logo','organization_logo','organization_id',$organization_id);
					if($profile_logo!=''){
						@unlink(UPLOAD_PATH."company-logo/".$profile_logo);
						$name=str_replace("_thumb",'',$profile_logo);
						$r=explode(".",$name);
						$fileext = end($r);
						$filename = basename(UPLOAD_PATH."company-logo/".$name,".".$fileext);
						if(file_exists(UPLOAD_PATH."company-logo/".$filename.".jpg")){
							@unlink(UPLOAD_PATH."company-logo/".$filename.".jpg");
						}if(file_exists(UPLOAD_PATH."company-logo/".$filename.".jpeg")){
							@unlink(UPLOAD_PATH."company-logo/".$filename.".jpeg");
						}elseif(file_exists(UPLOAD_PATH."company-logo/".$filename.".png")){
							@unlink(UPLOAD_PATH."company-logo/".$filename.".png");
						}elseif(file_exists(UPLOAD_PATH."company-logo/".$filename.".gif")){
							@unlink(UPLOAD_PATH."company-logo/".$filename.".gif");
						}
					}
					$memberDatacount=getData(array(
						'select'=>'o_l.organization_logo_id',
						'table'=>'organization_logo as o_l',
						'where'=>array('o_l.organization_id'=>$organization_id),
						'return_count'=>true,
					));
					if(!$memberDatacount){
						$up=insert(array('table'=>'organization_logo','data'=>array('organization_id'=>$organization_id,'logo'=>$response['filename'],'status'=>1,'reg_date'=>date('Y-m-d H:i:s'))),TRUE);
					}else{
						$up=update(array('table'=>'organization_logo','data'=>array('logo'=>$response['filename'],'status'=>1,'reg_date'=>date('Y-m-d H:i:s')),'where'=>array('organization_id'=>$organization_id)));
					}
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
