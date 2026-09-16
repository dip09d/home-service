<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='C';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
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
		redirect(get_link('settingaccountInfoURL'));
	}
	public function contact_info()
	{
		if($this->access_member_type=='C'){
			redirect(get_link('settingclientaccountInfoURL'));
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
		$this->data['member_id']=$this->member_id;
		$this->data['left_panel']=load_view('inc/freelancer-setting-left',$this->data,TRUE);
		$this->layout->view('contact-info', $this->data);
	}
	public function contact_info_account_data()
	{
		if($this->access_member_type=='C'){
			redirect(get_link('settingclientaccountInfoURL'));
		}
		if($this->loggedUser){
			$member_id=$this->member_id;	
			$memberData=get_row(array(
				'select'=>'m.member_username,m.member_name,m.member_email',
				'from'=>'member as m',
				'where'=>array('m.member_id'=>$member_id),
			),'object');	
			if($memberData){
				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-contact-info-account-display', $this->data,TRUE);
			}
		}
	}
	public function contact_info_account_form()
	{
		if($this->access_member_type=='C'){
			redirect(get_link('settingclientaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$member_id=$this->member_id;	
			$memberData=get_row(array(
				'select'=>'m.member_username,m.member_name,m.member_email',
				'from'=>'member as m',
				'where'=>array('m.member_id'=>$member_id),
			),'object');
			
			if($memberData){
				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-contact-info-account-form', $this->data,TRUE);
			}
		}
	}
	public function contact_info_account_form_check()
	{
		if($this->access_member_type=='C'){
			redirect(get_link('settingclientaccountInfoURL'));
		}
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		/*$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email');
		$this->form_validation->set_rules('fname', 'First name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('lname', 'last name', 'required|trim|xss_clean');*/
		$this->form_validation->set_rules('name', 'name', 'required|trim|xss_clean');
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
					'data'=>array('title'=>trim(post('name')))
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
	
	public function contact_location_data()
	{
		
		if($this->access_member_type=='C'){
			redirect(get_link('settingclientaccountInfoURL'));
		}
		if($this->loggedUser){
			$member_id=$this->member_id;	
			$memberData=get_row(array(
				'select'=>'m_a.member_timezone,m_a.member_city,m_a.member_state,m_a.member_address_1,m_a.member_address_2,m_a.member_pincode,m_a.member_mobile,,m_a.member_mobile_code,c_n.country_name',
				'from'=>'member as m',
				'join'=>array(
					array('member_address as m_a','m.member_id=m_a.member_id','left'),
					array('country as c','m_a.member_country=c.country_code','left'),
					array('country_names as c_n',"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'left')
				),
				'where'=>array('m.member_id'=>$member_id),
			),'object');	
			if($memberData){
				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-contact-location-display', $this->data,TRUE);
			}
		}
	}
	public function contact_location_form()
	{
		if($this->access_member_type=='C'){
			redirect(get_link('settingclientaccountInfoURL'));
		}
		
		if($this->loggedUser){
			$this->data['country']=getAllCountry();
			$member_id=$this->member_id;	
			$memberData=get_row(array(
				'select'=>'m_a.member_timezone,m_a.member_country,m_a.member_city,m_a.member_state,m_a.member_address_1,m_a.member_address_2,m_a.member_pincode,m_a.member_mobile,,m_a.member_mobile_code,c_n.country_name',
				'from'=>'member as m',
				'join'=>array(
					array('member_address as m_a','m.member_id=m_a.member_id','left'),
					array('country as c','m_a.member_country=c.country_code','left'),
					array('country_names as c_n',"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'left')
				),
				'where'=>array('m.member_id'=>$member_id),
			),'object');	
			
			if($memberData){
				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-contact-location-form', $this->data,TRUE);
			}
		}
	}
	public function contact_location_form_check()
	{
		if($this->access_member_type=='C'){
			redirect(get_link('settingclientaccountInfoURL'));
		}
		checkrequestajax();
		$i=0;
		$msg=array();
		if($this->input->post()){
		$this->form_validation->set_rules('country', 'Country', 'required|trim|xss_clean');
		$this->form_validation->set_rules('address_1', 'Address 1', 'required|trim|xss_clean');
		$this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|xss_clean');
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
			}else{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'email';
				$msg['errors'][$i]['message'] = 'Invalid ';
				$i++;
			}

			if($i==0){
				$updatedata=array(
				'member_timezone'=>NULL,
				'member_country'=>NULL,
				'member_city'=>NULL,
				'member_state'=>NULL,
				'member_address_1'=>NULL,
				'member_address_2'=>NULL,
				'member_pincode'=>NULL,
				'member_mobile_code'=>NULL,
				'member_mobile'=>NULL,
				);
				if(post('timezone')){
					$updatedata['member_timezone']=trim(post('timezone'));
				}
				if(post('country')){
					$updatedata['member_country']=trim(post('country'));
				}
				if(post('city')){
					$updatedata['member_city']=trim(post('city'));
				}
				if(post('state')){
					$updatedata['member_state']=trim(post('state'));
				}
				if(post('address_1')){
					$updatedata['member_address_1']=trim(post('address_1'));
				}
				if(post('address_2')){
					$updatedata['member_address_2']=trim(post('address_2'));
				}
				if(post('pincode')){
					$updatedata['member_pincode']=trim(post('pincode'));
				}
				if(post('mobile')){
					$updatedata['member_mobile']=trim(post('mobile'));
				}
				if(post('mobile_code')){
					$updatedata['member_mobile_code']=trim(post('mobile_code'));
				}
				$update=array(
					'table'=>'member_address',
					'where'=>array('member_id'=>$member_id),
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
	
	public function client_contact_info()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('settingaccountInfoURL'));
		}
		
		$data['load_js']=load_js(array('mycustom.js'));
		$data['left_panel']=load_view('inc/freelancer-setting-left',$data,TRUE);
		$templateLayout=array('view'=>'contact-info','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$data);
	}
}
