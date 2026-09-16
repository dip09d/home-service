<?php


if(!function_exists('generate_pass_code')){
	
	function generate_pass_code(){
		$length = 6;
		$str = "";
		$characters = array_merge(range('A','Z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		while(code_exist($str)){
			generate_pass_code();
		}
		
		return $str;
	}
	
}


if(!function_exists('code_exist')){
	
	function code_exist($code='', $db_table='bookings', $db_field='pass_code'){
		$ci = &get_instance();
		$db_check = $ci->db->where(array($db_field => $code))->count_all_results($db_table);
		
		return $db_check > 0;
	}
	
}


if(!function_exists('generate_visit_code')){
	
	function generate_visit_code(){
		$length = 6;
		$str = "";
		$characters = array_merge(range('A','Z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		while(code_exist($str, 'experience_bookings', 'pass_code')){
			generate_visit_code();
		}
		
		return $str;
	}
	
}


if(!function_exists('get_file_icon')){
	
	
	function get_file_icon($file_name=''){
		
		$icons = array(
			'doc' => DOC_ICON,
			'docx' => DOC_ICON,
			'pdf' => PDF_ICON,
			'txt' => TXT_ICON,
		);
		$default_file_icon = COMMON_ICON;
		
		$file_part = explode('.', $file_name);
		$file_ext = trim(end($file_part));
		
		if(!empty($icons[strtolower($file_ext)])){
			$file_icon = $icons[strtolower($file_ext)];
		}else{
			$file_icon = $default_file_icon;
		}
		
		return $file_icon;
		
	}
	
}
if(!function_exists('getAllCountry')){
	function getAllCountry($param=array()){
		$lang = get_active_lang();
		$ci = &get_instance();
		$ci->db->select('c.country_code,c_n.country_name,c.country_code_short')
			->from('country c')
			->join('country_names c_n', 'c.country_code=c_n.country_code', 'LEFT');
		$ci->db->where('c_n.country_lang', $lang);
		if($param){
			if(array_key_exists('country_code',$param)){
				$ci->db->where('c.country_code', $param['country_code']);
				$arr['where']['c.country_code']=$param['country_code'];
				$result = $ci->db->get()->row();
			}
		}else{
			$arr['where']['c.country_status']=1;
			$ci->db->order_by('c_n.country_name','asc');
			$result = $ci->db->get()->result();
		}
	return $result;
	}
}


if(!function_exists('get_country')){
	
	
	function get_country(){
		$lang = get_active_lang();
		$ci = &get_instance();
		$ci->db->select('c.id as country_id,c.name,l.display_value')
			->from('countries c')
			->join('countries_lang l', 'l.id=c.id', 'LEFT');
			
		$ci->db->where('l.lang_code', $lang);
		$ci->db->group_by('c.id');
		$result = $ci->db->get()->result_array();
		if($result){
			foreach($result as $k => $v){
				$result[$k]['display_value'] = !empty($v['display_value']) ? $v['display_value'] : $v['name'];
			}
		}
		
		return $result;
	}
	
}

if(!function_exists('get_state')){
	
	
	function get_state($country_code=''){
		$lang = get_active_lang();
		$ci = &get_instance();
		$ci->db->select('s.state_id,l.state_name')
			->from('state s')
			->join('state_names l', 'l.state_id=s.state_id', 'LEFT');
			
		$ci->db->where('s.country_code', $country_code);
		$ci->db->where('l.state_lang', $lang);
		$ci->db->group_by('s.state_id');
		$result = $ci->db->get()->result_array();
		return $result;
	}
	
}

if(!function_exists('get_city')){
	
	
	function get_city($state_id=''){
		$lang = get_active_lang();
		$ci = &get_instance();
		$ci->db->select('c.id as city_id,c.name,l.display_value')
			->from('cities c')
			->join('cities_lang l', 'l.id=c.id', 'LEFT');
			
		$ci->db->where('c.state_id', $state_id);
		$ci->db->where('l.lang_code', $lang);
		$ci->db->group_by('c.id');
		$result = $ci->db->get()->result_array();
		if($result){
			foreach($result as $k => $v){
				$result[$k]['display_value'] = !empty($v['display_value']) ? $v['display_value'] : $v['name'];
			}
		}
		return $result;
	}
	
}


if(!function_exists('get_user_logo')){
	
	
	function get_user_logo($user_id=''){
		$default_logo = NO_IMAGE_USER;
		$logo = NULL;
		$logo_f = getField('profile_pic', 'users_info', 'user_id', $user_id);
		if($logo_f){
			if(file_exists(UPLOAD_PATH.'cropped_'.$logo_f)){
				$logo = UPLOAD_HTTP_PATH.'cropped_'.$logo_f;
			}else if(file_exists(UPLOAD_PATH.$logo_f)){
				$logo = UPLOAD_HTTP_PATH.$logo_f;
			}else{
				$logo =$default_logo;
			}
		}else{
			$logo =$default_logo;
		}
		
		return $logo;
	}
	
}


if(!function_exists('hidden_inputs')){
	
	
	function hidden_inputs($inputs=array()){
		
		/* $inputs = array(
			'input_name' => 'input value',
			'input_name' => 'input value',
			'input_name' => 'input value',
			'input_name' => 'input value',
		); */
		
		$html = '';
		if($inputs){
			foreach($inputs as $k => $v){
				$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
			}
		}
		
		return $html;
	}
	
}

if(!function_exists('breadcrumb')){
	
	
	function breadcrumb($path=array(), $title=''){
		
		/**
		 * Create breadcrumb 
		 * 
		 * @param $path {Array} 
		 * 		 array(
		 * 				array(
		 * 					'title' => 'Breadcrumb title',
		 * 					'path'	=>	'url'
		 * 					),
		 * 
		 * 			)
		 * 
		 * @param $title {String} - Breadcrumb title
		 */
		
		$breadcrumb_html = '<li><a href="'.base_url().'">Home</a></li>';
		if($path){
			foreach($path as $breadcrumb){

				if(!empty($breadcrumb['path'])){
					$breadcrumb_html .= ' <li><a href="'.$breadcrumb['path'].'">'.$breadcrumb['title'].'</a></li>';
				}else{
					$breadcrumb_html .= ' <li>'.$breadcrumb['title'].'</li>';
				}
				
			}
		}

		$html = ' 
			<div class="dashboard-headline headline-after-login">
				<div class="container">
					<div class="row">
						<aside class="col-md-6 align-self-center">
							<h1>'.$title.'</h1>												
						</aside>
						<aside class="col-md-6">
								<nav id="breadcrumbs" class="float-md-right">
								<ul>								
									'.$breadcrumb_html.'
								</ul>					
							</nav>  				  
						</aside>
					</div>
				</div>
			</div>';

		
		return $html;
	}
	
}
if(!function_exists('checkrequestajax')){
function checkrequestajax() {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        
    } else {
        die('error ');
    }
}
}
if ( ! function_exists('load_view'))
{
	function load_view($view='',$data=array(),$buffer=FALSE,$theme='')
	{
		$CI 	= get_instance();
		 $curr_controller = $CI->router->fetch_class();
        $curr_method = $CI->router->fetch_method();
		$theme = THEME;
		if($buffer==FALSE)
		{
			if(@file_exists(APPPATH.'views/'.THEME.$view.EXT)){
				$CI->load->view($theme.$view,$data);
			}elseif(!file_exists(APPPATH."modules/$curr_controller/views/$view".EXT)){
				$CI->load->view("$theme"."$curr_controller/$view",$data);	
			}
		}
		else
		{
			if(@file_exists(APPPATH.'views/'.THEME.$view.EXT)){
			$view_data = $CI->load->view($theme.$view,$data,TRUE);
			}elseif(!file_exists(APPPATH."modules/$curr_controller/views/$view".EXT)){
			$view_data = $CI->load->view("$theme"."$curr_controller/$view",$data,TRUE);
			}
			return $view_data;
		}
	}
}
if ( ! function_exists('D'))
{
	function D($res)
	{
		echo $res;
	}
}
if ( ! function_exists('get_link'))
{
	function get_link($var=''){
		$ci =& get_instance();
		$ci->load->config('siteurl');
		return base_url($ci->config->item($var));
	}
}
if ( ! function_exists('getAllSkills'))
{
	function getAllSkills(){
		return getData(array(
					'select'=>'s.skill_id,s.skill_key,s_n.skill_name',
					'table'=>'skills s',
					'join'=>array(array('table'=>'skill_names as s_n','on'=>"(s.skill_id=s_n.skill_id and s_n.skill_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('s.skill_status'=>'1'),
			));
	}
}
if ( ! function_exists('CurrencySymbol'))
{
function CurrencySymbol(){
	if(defined('SITE_CURRENCY')){
		$site_currency=SITE_CURRENCY;
	}else{
		$site_currency=get_setting('site_currency');
		defined('SITE_CURRENCY') OR define('SITE_CURRENCY',$site_currency); 
	}
    return $site_currency;
}
}
if ( ! function_exists('CurrencyCode'))
{
function CurrencyCode() {
	if(defined('SITE_CURRENCY_CODE')){
		$site_currency_code=SITE_CURRENCY_CODE;
	}else{
		$site_currency_code=get_setting('site_currency_code');
		defined('SITE_CURRENCY_CODE') OR define('SITE_CURRENCY_CODE',$site_currency_code); 
	}
    return $site_currency_code;
}
}
if ( ! function_exists('priceSymbol'))
{
	function priceSymbol() {
	    if(defined('SITE_CURRENCY')){
			$site_currency=SITE_CURRENCY;
		}else{
			$site_currency=get_setting('site_currency');
			defined('SITE_CURRENCY') OR define('SITE_CURRENCY',$site_currency); 
		}
		return $site_currency;
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
if ( ! function_exists('getAllCategory'))
{
function getAllCategory($search=array()){
	$arr=array(
		'select'=>'c.category_id,c.category_key,c_n.category_name',
		'table'=>'category c',
		'join'=>array(array('table'=>'category_names as c_n','on'=>'c.category_id=c_n.category_id','position'=>'left')),
		'where'=>array('c.category_status'=>'1','c_n.category_lang'=>get_active_lang()),
		'order'=>array(array('c.category_order','asc'))
	);
	if($search){
		if(array_key_exists('limit',$search)){
			$arr['limit']=$search['limit'];
		}
		if(array_key_exists('is_featured',$search) && $search['is_featured']==1){
			$arr['where']['c.is_featured']=1;
		}
	}
	return getData($arr);
}
}
if ( ! function_exists('getAllSubCategory'))
{
function getAllSubCategory($category_id=''){
	$r=array(
		'select'=>'sc.category_subchild_id,sc.category_subchild_key,sc_n.category_subchild_name',
		'table'=>'category_subchild sc',
		'join'=>array(array('table'=>'category_subchild_names as sc_n','on'=>'sc.category_subchild_id=sc_n.category_subchild_id','position'=>'left')),
		'where'=>array('sc.category_subchild_status'=>'1','sc_n.category_subchild_lang'=>get_active_lang()),
		'order'=>array(array('sc.category_subchild_order','asc')),
		);
	if($category_id){
		$r['where']['sc.category_id']=$category_id;
	}
	return getData($r);
}
}
if ( ! function_exists('dateFormat'))
{
function dateFormat($date,$format='d m,Y') {
	if($format){
		$df=date($format,strtotime($date));
	}else{
		$df=date('d m Y',strtotime($date));
	}
    return $df;
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
if ( ! function_exists('getAllProjectStatus'))
{
function getAllProjectStatus($status=''){
	$data=array(
		PROJECT_ERROR=>array('name'=>'Error','class'=>''),
		PROJECT_DRAFT=>array('name'=>'Dtaft','class'=>''),
		PROJECT_OPEN=>array('name'=>'Open','class'=>''),
		PROJECT_HIRED=>array('name'=>'Hired','class'=>''),
		PROJECT_CLOSED=>array('name'=>'Closed','class'=>''),
		PROJECT_DELETED=>array('name'=>'Deleted','class'=>''),
	);
	if($status!=''){
		$data=$data[$status];
	}
	return $data;
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
if ( ! function_exists('getAllExperienceLevel'))
{
function getAllExperienceLevel(){
	return getData(array(
				'select'=>'e_l.experience_level_id,e_l.experience_level_key,e_l_n.experience_level_name',
				'table'=>'experience_level e_l',
				'join'=>array(array('table'=>'experience_level_name as e_l_n','on'=>"(e_l.experience_level_id=e_l_n.experience_level_id and e_l_n.experience_level_lang='".get_active_lang()."')",'position'=>'left')),
				'where'=>array('e_l.experience_level_status'=>'1'),
				'order'=>array(array('e_l.experience_level_id','asc'))
		));
}
}
if ( ! function_exists('getBids'))
{
function getBids($project_id='',$param=array(),$count=FALSE){
	$r=array(
		'select'=>'b.bid_id,',
		'table'=>'project_bids b',
		'where'=>array('b.project_id'=>$project_id),
		);
	if($param){
		if(array_key_exists('is_hired',$param)){
			$r['where']['b.is_hired']=1;
			$r['where']['b.is_archive']=NULL;
		}
		if(array_key_exists('is_archive',$param)){
			$r['where']['b.is_archive']=1;
		}
		if(array_key_exists('is_shortlisted',$param)){
			$r['where']['b.is_shortlisted']=1;
		}
		if(array_key_exists('is_interview',$param)){
			$r['where']['b.is_interview']=1;
		}	
		if(array_key_exists('is_hired',$param)){
			$r['where']['b.is_hired']=1;
		}	
		if(array_key_exists('only_active',$param) || array_key_exists('is_proposal',$param)){
			$r['where']['b.is_archive']=NULL;
		}
	}
	if($count){
		$r['return_count']=TRUE;
	}
	return getData($r);
}
}
if ( ! function_exists('get_time_ago'))
{
function get_time_ago( $time )
{
    $time_difference = time() - strtotime($time);
    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );
    foreach( $condition as $secs => $str )
    {
        $d = $time_difference / $secs;
        if( $d >= 1 )
        {
            $t = round( $d );
            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }
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

if ( ! function_exists('getSlug'))
{
	function getSlug($string , $separator = '-', $lowercase = FALSE) {
		return mb_strtolower(url_title($string,$separator));
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
if ( ! function_exists('getBidsListDetails'))
{
function getBidsListDetails($project_id='',$param=array(),$count=FALSE){
	$r=array(
		'select'=>'b.bid_id,b.bid_amount,b.bid_by_project,b.bid_duration,b.bid_details,b.bid_date,b.is_archive,b.is_shortlisted,b.is_interview,b.is_hired,b.member_id,b.organization_id,m.member_name,m.is_email_verified,m_b.member_heading,m_b.member_hourly_rate,m_a.member_country,m_l.logo,m_s.avg_rating as avg_review,m_s.total_earning as totalearn,m_s.no_of_reviews,m_s.success_rate',
		'table'=>'project_bids b',
		'join'=>array(
			array('table'=>'member m','on'=>'b.member_id=m.member_id','position'=>'left'),
			array('table'=>'member_address m_a','on'=>'b.member_id=m_a.member_id','position'=>'left'),
			array('table'=>'member_basic m_b','on'=>'b.member_id=m_b.member_id','position'=>'left'),
			array('table'=>'member_logo m_l','on'=>'b.member_id=m_l.member_id','position'=>'left'),
			array('table'=>'member_statistics m_s','on'=>'b.member_id=m_s.member_id','position'=>'left'),
		),
		'where'=>array('b.project_id'=>$project_id),
		);
	if($param){
		if(array_key_exists('is_hired',$param)){
			$r['where']['b.is_hired']=1;
			$r['where']['b.is_archive']=NULL;
		}
		if(array_key_exists('is_archive',$param)){
			$r['where']['b.is_archive']=1;
		}
		if(array_key_exists('is_shortlisted',$param)){
			$r['where']['b.is_shortlisted']=1;
		}
		if(array_key_exists('is_interview',$param)){
			$r['where']['b.is_interview']=1;
		}	
		if(array_key_exists('is_hired',$param)){
			$r['where']['b.is_hired']=1;
		}
		if(array_key_exists('only_active',$param) || array_key_exists('is_proposal',$param)){
			$r['where']['b.is_archive']=NULL;
		}
	}
	if($count){
		$r['return_count']=TRUE;
	}
	return getData($r);
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
if ( ! function_exists('getAllBidDuration'))
{
function getAllBidDuration($select=''){
	$data=array(
	'Less1week'=>array('name'=>'Less than 1 week'),
	'Less1month'=>array('name'=>'Less than 1 month'),
	'1To3month'=>array('name'=>'1 to 3 months'),
	'3To6month'=>array('name'=>'3 to 6 months'),
	'More6month'=>array('name'=>'More than 6 months'),
	);
	if($select){
		if(array_key_exists($select,$data)){
			$res=$data[$select]['name'];
		}else{
			$res='';
		}
		
	}else{
		$res=$data;
	}
	return $res;
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
       	
       		$ci->admin_notification_model->parse('wallet-balance-error', $data_parse, 'wallet/txn_list?ID='.$transaction_id);
	   		//system_notification(array('wallet_id'=>$wallet_id,'wallet_name'=>$wallet_name,'transaction_id'=>$transaction_id),'wallet_error');
	   }
    }

}
if (!function_exists('generate_invoice_number')) {
	function generate_invoice_number() {
		$currentno=get_setting('INVOICE_NUMBER');
		$INV=$currentno+1;
		updateTable('settings',array('setting_value'=>$INV),array('setting_key'=>'INVOICE_NUMBER'));
		$num =make_invoice_number($INV);
		return $num;
	}
}
if (!function_exists('make_invoice_number')) {
	function make_invoice_number($INV) {
		$num =str_pad($INV,8,'0',STR_PAD_LEFT);
		return $num;
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
					if(in_array($data_from,$checkmember_for)){
						$member_id=$data['member_id'];
						if(!in_array($member_id,array($owner_id,$contractor_id))){
							return ;
						}
					}
					
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
if (!function_exists('isFavouriteMember')) {
	function isFavouriteMember($member_id='',$favorite_member_id='') {
		$ci = &get_instance();
		return $ci->db->where('favorite_member_id',$favorite_member_id)->where('member_id',$member_id)->from('favorite_member')->count_all_results();
	}
}
if (!function_exists('isFavouriteJob')) {
	function isFavouriteJob($member_id='',$project_id='') {
		$ci = &get_instance();
		return $ci->db->where('project_id',$project_id)->where('member_id',$member_id)->from('favorite_project')->count_all_results();
	}
}
if (!function_exists('updateMembershipUser')) {
	function updateMembershipUser($member_id='') {
		$ci = &get_instance();
		$membership_id=get_setting('FREE_MEMBERSHIP_ID');
		$membership=getData(array(
			'select'=>'b.membership_id,b.membership_bid,b.membership_portfolio,b.membership_skills,b.membership_commission_percent,b.price_per_month,b.price_per_year,l.name,l.description',
			'table'=>'membership b',
			'join'=>array(array('table'=>'membership_names l','on'=>"(l.membership_id=b.membership_id and l.lang='".$lang."')",'position'=>'left')),
			'where'=>array('b.membership_status'=>1,'b.membership_id'=>$membership_id),
			'single_row'=>TRUE
		));
		if($membership){
			$dura='+ 1 month';
			$member_membership=array(
				'membership_id'=>$membership->membership_id,
				'is_free'=>1,
				//'membership_expire_date'=>$membership_expire_date,
				'max_bid'=>$membership->membership_bid,
				'max_portfolio'=>$membership->membership_portfolio,
				'max_skills'=>$membership->membership_skills,
				'commission_percent'=>$membership->membership_commission_percent,
			);
			$membership_expire_date=date('Y-m-d',strtotime($dura));
			$member_membership['membership_expire_date']=$membership_expire_date;
			$ci->db->where('is_free',0)->where('membership_expire_date <',date('Y-m-d'));
			if($member_id){
				$ci->db->where('member_id',$member_id);
			}
			return $ci->db->update('member_membership',$member_membership);
		}	
	}
}
if (!function_exists('updateMembershipUser')) {
function formatHours($decimalHour) {
    $hours = floor($decimalHour);
    $minutes = round(($decimalHour - $hours) * 60);

    // Handle edge case: 60 minutes
    if ($minutes == 60) {
        $hours++;
        $minutes = 0;
    }

    return "{$hours} hr {$minutes} min";
}
}

