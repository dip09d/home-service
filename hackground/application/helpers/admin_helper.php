<?php
defined('BASEPATH') OR exit('No direct script access allowed');



if(!function_exists('admin_log_check')){
	
	
	function admin_log_check(){
		
		$admin_id = get_session('admin_id');
		$flag = FALSE;
        $curr_url = base_url(uri_string());
        $get = get();
        if($get){
            $get = "?".http_build_query($get);
            $curr_url .= $get;
        }
        if (!$admin_id) {
            $flag = TRUE;
        }
	
        if ($flag) {
			
			redirect(base_url('login/?ref='.urlencode($curr_url)));
        }
		
	}
	
}
if (!function_exists('make_invoice_number')) {
	function make_invoice_number($INV) {
		$num =str_pad($INV,8,'0',STR_PAD_LEFT);
		return $num;
	}
}

if(!function_exists('breadcrumb')){
	
	function breadcrumb($option=array()){
		$html = '<ol class="breadcrumb float-sm-right">';
       $html .= '<li class="breadcrumb-item"><a href="'.base_url().'"> Home</a></li>';
		if($option){
			foreach($option as $v){
				if(empty($v['path']) || $v['path'] == '#'){
					$html .= ' <li class="breadcrumb-item active">'.$v['name'].'</li>';
				}else{
					$html .= ' <li class="breadcrumb-item"><a href="'.$v['path'].'">'.$v['name'].'</a></li>';
				}
				
			}  
		}
	  
	  $html .= '</ol>';
	  
	  return $html;
	  
	}
	
}


if(!function_exists('get_setting')){
	
	function get_setting($key=''){
		$val = getField('setting_value', 'settings', 'setting_key', $key);
		return $val;
	}
	
}




if(!function_exists('get_lang')){
	
	function get_lang(){
		$lang = get_setting('language');
		$lang_array = explode(',', $lang);
		return $lang_array;
	}
	
}



if(!function_exists('admin_default_lang')){
	
	function admin_default_lang(){
		$lang = get_setting('admin_default_lang');
		return $lang;
	}
	
}

if(!function_exists('default_country')){
	
	function default_country(){
		$ci = &get_instance();
		return $ci->config->item('default_country');
	}
	
}

if(!function_exists('get_admin_role')){
	
	function get_admin_role(){
		$admin = get_session('admin_detail');
	
		$role_id = !empty($admin['role_id']) ? $admin['role_id'] : 0;
		
		return $role_id;
	}
	
}

if(!function_exists('is_super_admin')){
	
	function is_super_admin($admin_id=''){
		if(!$admin_id){
			$admin_id = get_session('admin_id');
		}
		
		$super_admin = getField('super_admin', 'admin', 'admin_id', $admin_id);
		if($super_admin == '1'){
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	
}


if(!function_exists('get_table')){
	
	function get_table($table='', $where=array()){
		$ci = &get_instance();
		$ci->db->select('*')
			->from($table)
			->where($where);
			
		$result = $ci->db->get()->result_array();
		return $result;
	}
	
}


if(!function_exists('print_select_option')){
	
	
	function print_select_option($array=array(), $value='', $name='', $selected=''){
		if(count($array) > 0){
			
			if(!empty($value) && !empty($name)){
				
				foreach($array as $k => $v){
					$select = '';
					
					if(!empty($selected)){
						if(is_array($selected)){
							if(in_array($v[$value], $selected)){
								$select = 'selected';
							}
						}else{
							if($selected == $v[$value]){
								$select = 'selected';
							}
						}
					
					}
					if($select){
						echo  '<option value="'.$v[$value].'" '.$select.'>'.$v[$name].'</option>';
					}else{
						echo  '<option value="'.$v[$value].'">'.$v[$name].'</option>';
					}
					
				
				}
			
			}
			
		}
		
	}
	
}

if(!function_exists('format_money')){
	
	
	function format_money($amount=0){
		return number_format($amount, 2, '.', '');
	}
	
}

if(!function_exists('format_date_time')){
	
	
	function format_date_time($time=''){
		return date('d M,Y h:i A', strtotime($time));
	}
	
}

if(!function_exists('get_wallet_balance')){
	
	
	function get_wallet_balance($wallet_id=''){
		$balance = getField('balance', 'wallet', 'wallet_id', $wallet_id);
		return $balance;
	}
	
}

if(!function_exists('get_editor')){
	
	
	function get_editor($input_id=''){
		$ckeditor_url = ADMIN_PLUGINS.'ckeditor/ckeditor.js';
		$ckfinder_url= ADMIN_PLUGINS.'ckfinder';
		$script = <<<EOD
	
	<script>
		$(document).ready(function(){
			if(typeof CKEDITOR == 'undefined'){
				var scriptTag = document.createElement('script');
				scriptTag.type = 'text/javascript';
				scriptTag.src = '$ckeditor_url';
				scriptTag.onload = function(){
					CKEDITOR.replace('$input_id', {
						filebrowserBrowseUrl: '$ckfinder_url/ckfinder.html',
						filebrowserUploadUrl: '$ckfinder_url/core/connector/php/connector.php?command=QuickUpload&type=Files',
						filebrowserWindowWidth: '1000',
						filebrowserWindowHeight: '700'
				});
				};
				document.body.appendChild(scriptTag);
			}else{
				CKEDITOR.replace('$input_id', {
                    filebrowserBrowseUrl: '$ckfinder_url/ckfinder.html',
					filebrowserUploadUrl: '$ckfinder_url/core/connector/php/connector.php?command=QuickUpload&type=Files',
					filebrowserWindowWidth: '1000',
					filebrowserWindowHeight: '700'
            });
			}
			
		});
	</script>
		
EOD;

	return $script;
		
	}
	
}

if(!function_exists('check_wallet')){
	
	// function defination here 
	
	function check_wallet($wallet_id='',  $txn_id='0'){
		
		$ci = &get_instance();
		
		$res = $ci->db->select("(sum(tr.credit) - sum(tr.debit)) as balance")
			->from('wallet_transaction_row tr')
			->join('wallet_transaction t', 't.wallet_transaction_id=tr.wallet_transaction_id', 'LEFT')
			->where('tr.wallet_id', $wallet_id)
			->where('t.status', 1)
			->get()->row_array();
		
		$txn_balance = $res['balance'];
		
		$wallet_balance = getField('balance', 'wallet', 'wallet_id', $wallet_id);
		
		if($wallet_balance != $txn_balance){
			
			$notification = 'Wallet Error ! Wallet ID # : '.$wallet_id.' after transaction #' . $txn_id;
			
			error_log($notification);
			
			notify_admin($notification);
			
			
		}
		
		
	}
	
}

if(!function_exists('update_wallet_balance')){
	
	// function defination here 
	
	function update_wallet_balance($wallet_id='', $amount=''){
		
		$ci = &get_instance();
		
		return $ci->db->where('wallet_id', $wallet_id)->update('wallet', array('balance' => $amount));
		
	}
	
}

 function SendMail_old($from='', $to='', $template='', $data_parse=[],$type='html',$bcc=array(),$cc=array(),$data_subject=array()) {
 		$CI = get_instance();
 		$config['protocol'] = get_setting('protocol');
		$config['smtp_host'] = get_setting('smtp_host');
		$config['smtp_port'] = get_setting('smtp_port');
		$config['smtp_user'] = get_setting('smtp_user');
		$config['smtp_pass'] = get_setting('smtp_pass');
		$config['mailtype'] = get_setting('mailtype');
		$config['charset'] = get_setting('charset'); 
		
		
 		$mailemailID=get_setting('admin_email');
		$name=get_setting('website_name');
		$site_logo=URL.'themes/'.get_setting('active_theme').'/images/'.LOGO_NAME;
		$default_lang=get_setting('admin_default_lang');
		$mailcontent=$CI->db->select('m.template_id,mt_n.template_content,mt_n.template_subject')->from('mailtemplate as m')->join('mailtemplate_names as mt_n',"m.template_id=mt_n.template_id and mt_n.lang='".$default_lang."'",'left')->where('m.template_type',$template)->get()->row();
 		
       if($mailcontent){
            $subject = $mailcontent->template_subject;
            $contents = $mailcontent->template_content;
	   }else{
	   		 $contents = 'Invalid Template';
            $subject ='Invalid Template';
	   }
        if($data_subject){
			foreach ($data_subject as $key => $val) {
           	 $subject = str_replace('{' . $key . '}', $val, $subject);
        	}
		}
		$preparse=array(
		'WEBSITE_NAME'=>$name,
		'WEBSITE_LOGO'=>"<img src='".$site_logo."' width='100' >",
		'ADMIN_URL'=>ADMIN_URL,
		);
		foreach ($data_parse as $key => $val) {
            $contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
            
        }
        foreach($preparse as $key=>$val){
			$contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
		}
		
		//$to='asish9735@gmail.com';
        //$CI->load->library('email');
        //$CI->email->initialize($config);
        $CI->load->library('email', $config);
		$CI->email->from($mailemailID, $name);
		$CI->email->reply_to($mailemailID, $name);

        $CI->email->to($to);
        //$CI->email->bcc('asish9735@gmail.com');
        $CI->email->subject($subject);
		$CI->email->set_mailtype($type);
		if($bcc){
			$CI->email->bcc($bcc);	
		}
		if($cc){
			$CI->email->bcc($cc);	
		}
        $CI->email->message($contents);
        $send=$CI->email->send();
       // echo $CI->email->print_debugger();
       ob_clean();
        return $send;
    }

if(!function_exists('SendDirectMail')){
	function SendDirectMail($to='', $contents='', $subject='') {
		$CI = get_instance();
		$config['protocol'] = get_setting('protocol');
		$config['smtp_host'] = get_setting('smtp_host');
		$config['smtp_port'] = get_setting('smtp_port');
		$config['smtp_user'] = get_setting('smtp_user');
		$config['smtp_pass'] = get_setting('smtp_pass');
		$config['mailtype'] = get_setting('mailtype');
		$config['charset'] = get_setting('charset'); 
		
		
		$mailemailID=get_setting('admin_email');
		$name=get_setting('website_name');
		$type='html';
		$default_lang=get_setting('admin_default_lang');
		
	  
		$CI->load->library('email', $config);
		$CI->email->from($mailemailID, $name);
		$CI->email->reply_to($mailemailID, $name);

		$CI->email->to($to);
		$CI->email->subject($subject);
		$CI->email->set_mailtype($type);
		
		$CI->email->message($contents);
		$send=$CI->email->send();
		ob_clean();
		return $send;
	}
}	

	

 function getUserName($member_id){
 	$ci = &get_instance();
	$ci->db->select('a.access_username');
	$ci->db->from('profile_connection as p_c');
	$ci->db->join('access_panel as a','p_c.access_user_id=a.access_user_id','left');
	$ci->db->where(array('p_c.member_id'=>$member_id,'p_c.organization_id'=>NULL));
	$data=$ci->db->get()->row();
	if($data){
		return $data->access_username;
	}else{
		return $member_id;
	}
}


if(!function_exists('get_country_name')){
	
	
	function get_country_name($code=''){
		$country = getFieldData('country_name', 'country_names', '', '', array('country_code' => $code, 'country_lang' => admin_default_lang()));
		return $country;
	}
	
}

if(!function_exists('getFieldData')){

	function getFieldData($select, $table, $feild = "", $value = "", $where = null, $limit_from = 0, $limit_to = 0) {
		$ci =& get_instance();
		$ci->db->select($select);
		if ($value != '' AND $feild != '') {
			if ($limit_from > 0) {
				$rs = $ci->db->get_where($table, array($feild => $value), $limit_to, $limit_from);
			} else {
				$rs = $ci->db->get_where($table, array($feild => $value));
			}
		} else {
			if ($limit_from > 0) {
				$rs = $ci->db->get_where($table, $where, $limit_to, $limit_from);
			} else {
				$rs = $ci->db->get_where($table, $where);
			}
		}
		$data = '';
		foreach ($rs->result() as $row) {
			$data = $row->$select;
		}
		return $data;
	}

}

if(!function_exists('get_all_country')){
	
	function get_all_country(){
		
		$default_lang = admin_default_lang();
		$results = get_results(array(
			'select' => 'c.country_code,c_n.country_name,c.country_code_short',
			'from' => 'country c',
			'join' => array(
						array('country_names as c_n', 'c.country_code=c_n.country_code', 'left')
					),
			'where' => array(
				'c_n.country_lang' => $default_lang
			),
			'group'=>'c.country_code',
			'offset' => 'all',
			'order_by'=>array('c_n.country_name','asc'),
			
		));
		
		return $results;
	}
	
}
if(!function_exists('get_state')){
	
	
	function get_all_state($country_code=''){
		$default_lang = admin_default_lang();
		$ci = &get_instance();
		$ci->db->select('s.state_id,l.state_name')
			->from('state s')
			->join('state_names l', 'l.state_id=s.state_id', 'LEFT');
			
		$ci->db->where('s.country_code', $country_code);
		$ci->db->where('l.state_lang', $default_lang);
		$ci->db->group_by('s.state_id');
		$result = $ci->db->get()->result_array();
		
		return $result;
	}
	
}
if(!function_exists('get_all_religion')){
	
	function get_all_religion(){
		
		$default_lang = admin_default_lang();
		$results = get_results(array(
			'select' => 'c.religion_id,c_n.religion_name',
			'from' => 'religion c',
			'join' => array(
						array('religion_names as c_n', 'c.religion_id=c_n.religion_id', 'left')
					),
			'where' => array(
				'c_n.religion_lang' => $default_lang
			),
			'group'=>'c.religion_id',
			'offset' => 'all',
			'order_by'=>array('c_n.religion_name','asc'),
			
		));
		
		return $results;
	}
	
}
if(!function_exists('getAllCity')){
	function getAllCity($param=array()){
		$lang = admin_default_lang();
		$ci = &get_instance();
		$ci->db->select('c.city_id,c_n.city_name,c.city_order,c.is_featured')
			->from('city c')
			->join('city_names c_n', 'c.city_id=c_n.city_id', 'LEFT');
		$ci->db->where('c_n.city_lang', $lang);
		if($param){
			if(array_key_exists('is_featured',$param)){
				$ci->db->where('c.is_featured',1);
			}
			if(array_key_exists('country_code',$param)){
				$ci->db->where('c.country_code',$param['country_code']);
			}
			
		}	
		$ci->db->where('c.city_status',1);
		$ci->db->order_by('c.city_order','asc');
		$ci->db->order_by('c_n.city_name','asc');
		if($param){
			if(array_key_exists('limit',$param)){
				$ci->db->limit($param['limit']);
			}
		}
		$result = $ci->db->get()->result();
	return $result;
	}
}

if ( ! function_exists('getRoleUserEmployemnt'))
{
function getRoleUserEmployemnt($role_id='') {
    $role=array(
    '1'=>'Intern',
    '2'=>'Individual Contributor',
    '3'=>'Lead',
    '4'=>'Manager',
    '5'=>'Executive',
    '6'=>'Owner',
    );
    if($role_id && $role[$role_id]){
		return $role[$role_id];
	}elseif(!$role_id){
		return $role;
	}
}
}
if ( ! function_exists('getMonth'))
{
function getMonth($month_id='') {
    $month=array(
    '1'=>'January',
    '2'=>'February',
    '3'=>'March',
    '4'=>'April',
    '5'=>'May',
    '6'=>'June',
    '7'=>'July',
    '8'=>'August',
    '9'=>'September',
    '10'=>'October',
    '11'=>'November',
    '12'=>'December',
    );
    if($month_id && $month[$month_id]){
		return $month[$month_id];
	}elseif(!$month_id){
		return $month;
	}
}
}
if (!function_exists('wallet_balance_check')) {

    function wallet_balance_check($wallet_id,$data=array()) {
    	$ci = &get_instance();
    	$ci->load->database();
        $error=0;
        $wallet_name='';
        $transaction_id='';
        if($data['transaction_id']){
			$transaction_id=$data['transaction_id'];
			
		}
		$wallet_transaction_type_id=get_setting('WITHDRAW');
		
        $ci->db->select('w.title,w.balance as wallet_balance,SUM( wtr.credit ) - SUM( wtr.debit ) AS balance,w.user_id, m.member_name');
        $ci->db->from('wallet_transaction_row  as wtr');
        $ci->db->join('wallet_transaction  as wt', 'wtr.wallet_transaction_id=wt.wallet_transaction_id', 'LEFT');
        $ci->db->join('wallet as w', 'wtr.wallet_id=w.wallet_id', 'LEFT');
		$ci->db->join('member  as m', 'w.user_id=m.member_id', 'LEFT');
		$ci->db->where('w.wallet_id',$wallet_id);
		//$ci->db->where('wt.status',1);
		$ci->db->where("IF(wt.wallet_transaction_type_id='".$wallet_transaction_type_id."' , wt.status!='2',wt.status='1')");
		//$ci->db->where('wt.status', '1');
		$ci->db->where('wt.transaction_date !=', '0000-00-00 00:00:00');
		$ci->db->group_by('w.wallet_id');	
		$result = $ci->db->get()->row_array();
		if($result){
			list($wallet_balance) = explode(".", $result['wallet_balance']);
			list($balance) = explode(".", $result['balance']);
			//if($result['wallet_balance']!=displayamount($result['balance'],2)){
			if($wallet_balance!=$balance){
				  $error=1;
				  if(!empty($result['title'])){
						$wallet_name= $result['title'];
					}else{
						if($result['user_id'] > 0){
							$wallet_name= $result['member_name'];
						}else{
							$wallet_name= 'Site Wallet';
						}
						
					}
			}
		}
       if($error==1){
       	error_log('wallet balance:'.$result['wallet_balance'].'!='.'system balance:'.$result['balance']." tranastion id:".$transaction_id." wallet_id:".$wallet_id);
	       	$data_parse=array(
		       	'WALLET_ID'=>$wallet_id,
		       	'TRANSACTION_ID'=>$transaction_id,
	       	);
       		$ci->load->model('admin_notification_global_model'); 
       		$ci->admin_notification_global_model->parse('wallet-balance-error', $data_parse, 'wallet/txn_list?ID='.$transaction_id);
	   		//system_notification(array('wallet_id'=>$wallet_id,'wallet_name'=>$wallet_name,'transaction_id'=>$transaction_id),'wallet_error');
	   }
    }
	
	


}


if ( ! function_exists('getAllProjectType'))
{
function getAllProjectType($select=''){
	$data=array(
	'OneTime'=>array('name'=>'One time project'),
	'Ongoing'=>array('name'=>'Ongoing project'),
	'NotSure'=>array('name'=>'Not sure'),
	);
	if($select){
		$data=$data[$select];
	}
	return $data;
}
}

if ( ! function_exists('getAllCategory'))
{
function getAllCategory(){
	$default_lang = admin_default_lang();
	return getData(array(
				'select'=>'c.category_id,c.category_key,c_n.category_name',
				'table'=>'category c',
				'join'=>array(array('table'=>'category_names as c_n','on'=>'c.category_id=c_n.category_id','position'=>'left')),
				'where'=>array('c.category_status'=>'1','c_n.category_lang'=>$default_lang),
				'order'=>array(array('c.category_order','asc'))
		));
}
}

if ( ! function_exists('getAllSubCategory'))
{
function getAllSubCategory($category_id=''){
	$default_lang = admin_default_lang();
	$r=array(
		'select'=>'sc.category_subchild_id,sc.category_subchild_key,sc_n.category_subchild_name',
		'table'=>'category_subchild sc',
		'join'=>array(array('table'=>'category_subchild_names as sc_n','on'=>'sc.category_subchild_id=sc_n.category_subchild_id','position'=>'left')),
		'where'=>array('sc.category_subchild_status'=>'1','sc_n.category_subchild_lang'=>$default_lang),
		'order'=>array(array('sc.category_subchild_order','asc')),
		);
	if($category_id){
		$r['where']['sc.category_id']=$category_id;
	}
	return getData($r);
}
}


if ( ! function_exists('getAllExperienceLevel'))
{
function getAllExperienceLevel(){
	$default_lang = admin_default_lang();
	return getData(array(
				'select'=>'e_l.experience_level_id,e_l.experience_level_key,e_l_n.experience_level_name',
				'table'=>'experience_level e_l',
				'join'=>array(array('table'=>'experience_level_name as e_l_n','on'=>"(e_l.experience_level_id=e_l_n.experience_level_id and e_l_n.experience_level_lang='".$default_lang."')",'position'=>'left')),
				'where'=>array('e_l.experience_level_status'=>'1'),
				'order'=>array(array('e_l.experience_level_id','asc'))
		));
}
}

if ( ! function_exists('getAllProjectDuration'))
{
function getAllProjectDuration($select=''){
	$data=array(
	'Less1month'=>array('name'=>'Less than 1 month'),
	'1To2month'=>array('name'=>'1 to 2 month'),
	'More3month'=>array('name'=>'More than 3 month'),
	);
	if($select){
		$data=$data[$select];
	}
	return $data;
}
}

if ( ! function_exists('getAllProjectDurationTime'))
{
function getAllProjectDurationTime($select=''){
	$data=array(
	'FullTime'=>array('name'=>'More then 30hr/week','freelanceName'=>'More than 30 hrs/week'),
	'PartTime'=>array('name'=>'Less then 30hr/week','freelanceName'=>'Less than 30 hrs/week'),
	'NotSure'=>array('name'=>'Not sure','freelanceName'=>'As needed - open to offers'),
	);
	if($select){
		$data=$data[$select];
	}
	return $data;
}
}

if ( ! function_exists('getAllSkills'))
{
	function getAllSkills(){
		$default_lang = admin_default_lang();
		return getData(array(
					'select'=>'s.skill_id,s.skill_key,s_n.skill_name',
					'table'=>'skills s',
					'join'=>array(array('table'=>'skill_names as s_n','on'=>"(s.skill_id=s_n.skill_id and s_n.skill_lang='".$default_lang."')",'position'=>'left')),
					'where'=>array('s.skill_status'=>'1'),
			));
	}
}
if ( ! function_exists('D'))
{
	function D($res)
	{
		echo $res;
	}
}

if ( ! function_exists('priceSymbol'))
{
	function priceSymbol() {
	    return get_setting('site_currency');
	}
}

if ( ! function_exists('generateProjectSlug'))
{
	function generateProjectSlug($string,$inc=0) {
		$url=getSlug($string);
		if($url){
			$CI = get_instance();
			$CI->load->database();
			if($inc>0){
				$url=$url.'-'.$inc;
			}
			$query= $CI->db->get_where('project',array('project_url'=>$url));		
			if($query->num_rows()>0)
			{
				$inc++;
				$url=generateProjectSlug($string,$inc);
			}
		}
		return $url;
	}
}
if ( ! function_exists('getSlug'))
{
	function getSlug($string , $separator = '-', $lowercase = FALSE) {
		return mb_strtolower(url_title($string,$separator));
	}
}

if (!function_exists('get_contract_details')) {
	function get_contract_details($contract_id_enc='',$data=array()) {
		$arr=array(
		'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,co.contract_details,c.bid_id,co.contract_attachment,co.max_hour_limit,co.allow_manual_hour,c.is_contract_ended,c.contract_end_date,c.is_pause',
		'table'=>'project_contract c',
		'join'=>array(
			array('table'=>'project_contract_offer co', 'on'=>'c.contract_id=co.contract_id', 'position'=>'left'),
			array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
			array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
		),
		'where'=>array('md5(c.contract_id)'=>$contract_id_enc),
		'single_row'=>TRUE
		);
		if($data){
			if(array_key_exists('data_from',$data)){
				$data_from=$data['data_from'];
				if($data_from=='offer_action'){
					$arr['c.contract_status']=0;
				}elseif($data_from=='workroom_workaction'){
					$arr['c.contract_status']=1;
				}elseif($data_from=='contract_addfund'){
					$member_id=$data['member_id'];
					$arr['o.member_id']=$member_id;
				}
			}
		}
		$res=getData($arr);
		if($res){
			$owner_id=$res->owner_id;
			$contractor_id=$res->contractor_id;
			if($data){
				if(array_key_exists('data_from',$data)){
					$data_from=$data['data_from'];
					$checkmember_for=array(
					'offer',
					'contract_details',
					'workroom_details',
					'contract_message',
					'contract_invoice',
					'contract_term',
					'workroom_invoice',
					'workroom_worklog',
					'workroom_load_worklog',
					);
					/* if(in_array($data_from,$checkmember_for)){
						$member_id=$data['member_id'];
						if(!in_array($member_id,array($owner_id,$contractor_id))){
							return ;
						}
					} */
					
				}
			}
		}
		
		return $res;
	}
}
if (!function_exists('get_contract_view')) {
	function get_contract_view($contract_id='',$member_id='') {
		$res=array();
		$res['review_by_me']=getData(array(
		'select'=>'r.*',
		'table'=>'contract_reviews r',
		'where'=>array('r.contract_id'=>$contract_id,'r.review_by'=>$member_id,'r.review_status'=>1),
		'single_row'=>TRUE
		));
		$res['review_to_me']=getData(array(
		'select'=>'r.*',
		'table'=>'contract_reviews r',
		'where'=>array('r.contract_id'=>$contract_id,'r.review_to'=>$member_id,'r.review_status'=>1),
		'single_row'=>TRUE
		));
		if($res['review_by_me'] || $res['review_to_me']){
			
		}else{
			$res=array();
		}
		return $res;
	}
}

if ( ! function_exists('getProjectDetails'))
{
function getProjectDetails($project_id,$show=array()){
	$data=array();
	if(empty($show) || in_array('project',$show)){
	$arr=array(
				'select'=>'p.project_id,p.project_url,p.project_title,p.project_member_required,p.project_posted_date,p.project_expired_date,p.project_status',
				'table'=>'project as p',
				'where'=>array('p.project_id'=>$project_id),
				'single_row'=>true,
			);
	$project=getData($arr);	
	$data['project']=$project;
	}
	if(empty($show) || in_array('project_additional',$show)){
	$arr=array(
				'select'=>'p_a.project_description,p_a.project_is_cover_required',
				'table'=>'project_additional as p_a',
				'where'=>array('p_a.project_id'=>$project_id),
				'single_row'=>true,
			);
	$project_additional=getData($arr);
	$data['project_additional']=$project_additional;
	}
	if(empty($show) || in_array('project_category',$show)){
	$arr=array(
				'select'=>'c.category_id,c.category_key,c_n.category_name,s_c.category_subchild_id,s_c.category_subchild_key,sc_n.category_subchild_name',
				'table'=>'project_category as p_c',
				'join'=>array(
					array('table'=>'category as c','on'=>'p_c.category_id=c.category_id','position'=>'left'),
					array('table'=>'category_names as c_n','on'=>"(c.category_id=c_n.category_id and c_n.category_lang='".get_active_lang()."')",'position'=>'left'),
					array('table'=>'category_subchild as s_c','on'=>'p_c.category_subchild_id=s_c.category_subchild_id','position'=>'left'),
					array('table'=>'category_subchild_names as sc_n','on'=>"(s_c.category_subchild_id=sc_n.category_subchild_id and sc_n.category_subchild_lang='".get_active_lang()."')",'position'=>'left')
				),
				'where'=>array('p_c.project_id'=>$project_id),
				'single_row'=>true,
			);
	$project_category=getData($arr);
	$data['project_category']=$project_category;
	}
	if(empty($show) || in_array('project_files',$show)){
	$arr=array(
				'select'=>'f.file_id,f.original_name,f.server_name,f.file_ext',
				'table'=>'project_files as p_f',
				'join'=>array(
					array('table'=>'files as f','on'=>'p_f.file_id=f.file_id','position'=>'left'),
				),
				'where'=>array('p_f.project_id'=>$project_id),
			);
	$project_files=getData($arr);
	$data['project_files']=$project_files;
	}
	if(empty($show) || in_array('project_owner',$show)){
	$arr=array(
				'select'=>'p_o.organization_id,p_o.member_id,o.organization_name,m.member_name',
				'table'=>'project_owner as p_o',
				'join'=>array(
					array('table'=>'organization as o','on'=>'p_o.organization_id=o.organization_id','position'=>'left'),
					array('table'=>'member as m','on'=>'p_o.member_id=m.member_id','position'=>'left'),
				),
				'where'=>array('p_o.project_id'=>$project_id),
				'single_row'=>true,
			);
	$project_owner=getData($arr);
	$data['project_owner']=$project_owner;
	}
	if(empty($show) || in_array('project_question',$show)){
	$arr=array(
				'select'=>'q.question_id,q.question_title',
				'table'=>'project_question as p_q',
				'join'=>array(
					array('table'=>'question as q','on'=>'p_q.question_id=q.question_id','position'=>'left'),
				),
				'where'=>array('p_q.project_id'=>$project_id,'p_q.project_question_status'=>1),
			);
	$project_question=getData($arr);
	$data['project_question']=$project_question;
	}
	if(empty($show) || in_array('project_settings',$show)){
	$arr=array(
				'select'=>'p_s.is_visible_anyone,p_s.is_visible_private,p_s.is_visible_invite,p_s.is_hourly,p_s.is_fixed,p_s.budget,e_l.experience_level_key,e_l_n.experience_level_name,p_s.hourly_duration,p_s.hourly_time_required,p_s.project_type_code',
				'table'=>'project_settings as p_s',
				'join'=>array(
					array('table'=>'experience_level as e_l','on'=>'p_s.experience_level=e_l.experience_level_id','position'=>'left'),
					array('table'=>'experience_level_name as e_l_n','on'=>"(e_l.experience_level_id=e_l_n.experience_level_id and e_l_n.experience_level_lang='".get_active_lang()."')",'position'=>'left'),
				),
				'where'=>array('p_s.project_id'=>$project_id),
				'single_row'=>true,
			);
	$project_settings=getData($arr);
	$data['project_settings']=$project_settings;
	}
	if(empty($show) || in_array('project_skills',$show)){
	$arr=array(
				'select'=>'s.skill_id,s.skill_key,s_n.skill_name',
				'table'=>'project_skills as p_s',
				'join'=>array(
					array('table'=>'skills as s','on'=>'p_s.skill_id=s.skill_id','position'=>'left'),
					array('table'=>'skill_names as s_n','on'=>"(s.skill_id=s_n.skill_id and s_n.skill_lang='".get_active_lang()."')",'position'=>'left')
				),
				'where'=>array('p_s.project_id'=>$project_id,'p_s.project_skill_status'=>1),
			);
	$project_skills=getData($arr);	
	$data['project_skills']=$project_skills;
	}
	
	/*$data=array(
		'project'=>$project,
		'project_additional'=>$project_additional,
		'project_category'=>$project_category,
		'project_files'=>$project_files,
		'project_owner'=>$project_owner,
		'project_question'=>$project_question,
		'project_settings'=>$project_settings,
		'project_skills'=>$project_skills,
	);*/
	return $data;
}
}

if ( ! function_exists('getProposalDetails'))
{
function getProposalDetails($proposal_id=''){
	$arr=array(
				'select'=>'p_b.bid_id,p_b.bid_amount,p_b.bid_site_fee,p_b.bid_by_project,p_b.bid_duration,p_b.bid_details,p_b.bid_date,p_b.is_archive,p_b.is_shortlisted,p_b.is_interview,p_b.is_hired,p_b.bid_attachment,p_b.member_id,p_b.project_id',
				'table'=>'project_bids as p_b',
				'where'=>array('p_b.bid_id'=>$proposal_id),
				'single_row'=>true,
			);
	$proposal=getData($arr);	
	$data['proposal']=$proposal;
	$arr=array(
				'select'=>'p_b_m.bid_milestone_id,p_b_m.bid_milestone_title,p_b_m.bid_milestone_due_date,p_b_m.bid_milestone_amount',
				'table'=>'project_bid_milestones as p_b_m',
				'where'=>array('p_b_m.bid_id'=>$proposal_id),
			);
	$bid_milestones=getData($arr);
	$data['proposal']->project_bid_milestones=$bid_milestones;
	return $data;
}
}

if ( ! function_exists('priceFormat'))
{
	function priceFormat($number) {
	      if ($number) {
	          $text = number_format($number, 2, '.', ' ');
	      }else{
		  	$text="0.00";
		  }
	      return $text;
	}
}