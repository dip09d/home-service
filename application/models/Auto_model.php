<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class auto_model extends CI_Model {
var $CI;
    public function __construct() {
	   $this->CI =& get_instance();
        return parent::__construct();
    }
 
/******************pagging start**************************/
	public function pagging($uri_segment,$base_url,$suffix,$total_rows,$per_page){
		$config['uri_segment'] = $uri_segment;
		$config['base_url'] = $base_url;
		$config['suffix'] = $suffix;
		$config['first_url'] = $config['base_url'].'1'.$suffix;
		$config['total_rows'] =  $total_rows;
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config); 
		return $this->pagination->create_links();	
	}
	public function searchquery($position){
		$uri = $this->uri->uri_to_assoc($position);
		$searchquery='';
			foreach($uri as $keyp=>$valp){
				$searchquery.= !empty($uri[$keyp]) ? '/'.$keyp.'/'. $uri[$keyp] : '';	
			}
		return $searchquery;		
	}	
	public function getstart($position,$limit){
		$page = ($this->uri->segment($position)) ? $this->uri->segment($position) : 1;
		$start=($page-1)*$limit;
		return $start;		
	}
/******************pagging end**************************/	
	public function breadcrumb($breadcrumb){
		$b='<ul class="breadcrumb">
		 <li><a href="'.VPATH.'"><i class="icon16 i-home-4"></i>Home</a></li>';
		foreach($breadcrumb as $name){
		if($name['path']){
		$b.="<li >";
		$b.=anchor(base_url().$name['path'],$name['title'],'');
		$b.="</li>";
		 }else{
		$b.='<li class="active">';
		$b.=$name['title'];
		}
		$b.="</li>";
		}
		$b.='</ul>';
		return $b;
	}
	public function getcleanname($name){
		return strtolower(str_replace("'","",str_replace(" ","-",str_replace("/","-",str_replace("-","",str_replace("&","and",str_replace("+","",$name)))))));
	}
	public function uploadfile($upload_path,$allowtype,$name,$redirect,$extraconfig='',$extraconfigresize=''){
		if($name){
		if(count($extraconfig)>0){
			foreach($extraconfig as $key=>$v){
				$config[$key]=$v;
			}
		}
		$cat_logo = '';
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = $allowtype; 		
		$this->load->library('upload', $config);		
		$uploaded = $this->upload->do_upload($name);
		$upload_data = $this->upload->data(); 
		$cat_logo = $upload_data['file_name'];
		$this->load->library('image_lib'); 
		//Thumbnail configs
		$config_t['source_image']   = $upload_path.$cat_logo;
		$config_t['create_thumb'] = True;///change this
		$config_t['maintain_ratio'] = TRUE;
		$config_t['new_image'] =$upload_path."thumb/".$cat_logo;
		if(count($extraconfigresize)>0){
			foreach($extraconfigresize as $keyr=>$vr){
				$config_t[$keyr]=$vr;
			}
			//end of configs
			$config_t['thumb_marker'] = "";
			$this->load->library('image_lib', $config_t); 
			$this->image_lib->initialize($config_t);
			if(!$this->image_lib->resize()){
			$error = array('error' => $this->image_lib->display_errors());
				 $this->session->set_userdata('error_msg', $error['error']);			
			}       
		}		
		if ( ! $uploaded AND $cat_logo !='' ){
			 $error = array('error' => $this->upload->display_errors());
			 $this->session->set_userdata('error_msg', $error['error']);
			 redirect($redirect);			
		}			
		 return $cat_logo;
		}else{
		return $name;
		}
	}	
	public function send_email($from,$to,$template,$data_parse){	
			$mailcontent=$this->auto_model->getalldata('contents,mail_subject','mailtemplate','mail_unique_title',$template);	
			foreach($mailcontent as $val){
				$contents=$val->contents;
				$subject=$val->mail_subject;
			}
			$this->load->library('email');			$this->email->initialize(array('mailtype' => 'html'));
			$this->email->from($from, 'admin');
			$this->email->to($to); 
			$this->email->subject($subject);		
			foreach($data_parse as $key=>$val){			
				$contents=str_replace('{'.$key.'}',$val,$contents);
			}			
			$this->email->message($contents);	
		return $this->email->send();	
	}
/****************************Permission start********************************/


		public function  getlink($checklink, $checksegment, $link, $text,$attr){
		$admin = $this->session->userdata('user');
		if($admin->role_id > 0){
			
			$tmp_link = $checklink;
			//$segment = explode('/' , $tmp_link);
			$count_segment = $checksegment;
			
			/*if($count_segment > 2){
				$tmp_link = $segment;
				unset($tmp_link[2]);
				$tmp_link = implode('/' , $tmp_link);
			}*/
			$user_role = $admin->role_id;
			$all_role = explode(',', $user_role);
			if(count($all_role) > 0){
				$this->db->where_in("admin_role" , $all_role);
			}else{
				$this->db->where('admin_role' , $user_role);
			}
			$this->db->where("FIND_IN_SET($count_segment , segment)");
			
			$permission = $this->db->where(array('url' => $tmp_link))->count_all_results('permission');
			if($permission > 0){
				return anchor($link,$text,$attr);
			}
			
		}else{
			return anchor($link,$text,$attr);
		}
		
	}
	
	
	public function  checknewpermission($url , $segment){
		if(!$this->session->userdata('user')){ 
			redirect(VPATH."login");
		}else{
			$userData = $this->session->userdata('user');
			$user_role = $userData->role_id;
			
			if($user_role != 0){
				$all_role = explode(',', $user_role);
				if(count($all_role) > 0){
					$this->db->where_in("admin_role" , $all_role);
				}else{
					$this->db->where('admin_role' , $user_role);
				}
				$this->db->where("FIND_IN_SET($segment , segment)");
				
				//$permission = $this->db->where(array('admin_role' => $user_role , 'url' => $url , 'segment' => $segment))->count_all_results('new_permission');
				$permission = $this->db->where(array('url' => $url))->count_all_results('permission');
				
				if($permission > 0){
					return 1;
				}else{
					die('<div align="center" style="color:red">You Have No Permission! Back to <a href="'.VPATH.'">Dashboard</a></div>');
				}
				
			}else{
				return 1;
			}
			
		}
		
	}
	
	
/****************************Permission end********************************/	
	public function getFeild($select,$table,$feild,$value){
		$this->db->select($select);	
		$rs = $this->db->get_where($table,array($feild=>$value));
		 $data = '';
		 foreach ($rs->result() as $row){
		  $data = $row->$select;
		 }
		 return $data;		
	}
	public function getdata($attr,$table,$where,$order=""){
		$this->db->select($attr);	
		if($order){
			$or=explode(",",$order);			
			$this->db->order_by($or[0], $or[1]); 
		}
		$rs = $this->db->get_where($table,$where);
		$data = '';
		foreach ($rs->result() as $key=>$val){
		  $data["'".$key."'"] = $val;
		}
		return $data;	
	}
	public function getdata_single($attr,$table,$where){
		$this->db->select($attr);	
		
		$rs = $this->db->get_where($table,$where);
		$data = array();
		foreach ($rs->result() as $val){
		  $data = $val;
		}
		return (array)$data;	
	}
	public function getalldata($attr,$table,$by,$value){
		$this->db->select($attr);	
		$rs = $this->db->get_where($table,array($by=>$value));
		$data = ''; 
		foreach ($rs->result() as $key=>$row){
		  $data["'".$key."'"] = $row;
		}	
		return $data;	
	}
	
	public function getallrecord($attr,$table,$for_list=TRUE){
		$this->db->select($attr)->from($table);
		if($for_list){		
			$result = $this->db->get()->result_array();
		}else{
			$result = $this->db->count_all_results();
		}
		return $result;	
	}
	
	
	
	public function getfiled($type,$name,$value,$option='',$id='') {	
		$b='';
		if($id==''){
			$id=$name;
		}
		if($type=="text"){
			$pw = array('id' => $id, 'name' => $name,  'value' => $value, 'class' => 'form-control');	
			$b=form_input($pw);
		}elseif($type=="textarea"){
			$pw = array('id' => $id, 'name' => $name,  'value' => $value, 'class' => 'form-control','rows'=>3);
			$b=form_textarea($pw);
		}elseif($type=="radio"  && $option!=''){
		$option=explode(";",$option);		
			if(count($option)){
				foreach($option as $val){
				$radio_is_checked = $val === $value;
				$b.=form_radio($name, $val, $radio_is_checked, 'id='.$id,'class=form-control'). $val;
				}				
			}
		}elseif($type=="checkbox"  && $option!=''){
		$option=explode(";",$option);
		$value=explode("||",$value);		
			if(count($option)){
				foreach($option as $val){
				$radio_is_checked =in_array($val,$value);
				$b.=form_checkbox($name.'[check][]', $val, $radio_is_checked, 'id='.$id.'[check][]','class=form-control'). $val;
				}				
			}
		}elseif($type=="selectbox"  && $option!=''){
		$option=explode(";",$option);
		$select=$value;
		$b=form_dropdown($name , $option , $select );
		}elseif($type=="drowpdown"  && $option!=''){		
		$select=$value;
		$b=form_dropdown($name , $option , $select );
		}else{
		$pw = array('id' => $id, 'name' => $name,  'value' => $value, 'class' => 'form-control');	
		$b=form_input($pw);		
		}				
		return $b;
	}
	/****************** Form Validation generation*******************/
	public function echovalidategeneration($formdata=array()){
			$item=array();
			if(is_array($formdata['fields'])){
				foreach($formdata['fields'] as $val){					
					if(array_key_exists('validate',$val)){
						$item[] = $val['validate'];
					}
					
				}
			}
			return $item;
	}
	
	public function echoallrecord_count($echotable,$echocond='')
	 {
	 if($echocond){
	 $this->db->where($echocond);
	 }
	   return $this->db->count_all_results($echotable);
	}   
	public function echoaddToLog($dataall){
		
		$other=$dataall['other'];
		$other['Agent']=$this->input->user_agent();
		$ip=$this->input->ip_address();
			$data=array(
							'user_id'=>$dataall['user_id'],
							'desc'=>$dataall['msg'],
							'ip'=>$ip,
							'status'=>$dataall['status'],			
							'not_date'=>date('Y-m-d H:i:s'),
							'other'=>serialize($other)
							);
				$this->db->insert('log', $data);
	}
	
}
