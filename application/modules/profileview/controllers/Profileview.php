<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profileview extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();	
			
			parent::__construct();
	}
	public function _my_callback_function($field_value, $second_parameter){
   		return TRUE;
	}
	public function index()
	{
		if($this->access_member_type=='E'){
			redirect(get_link('dashboardURL'));
		}
		if($this->loggedUser){
			$log_member_id=$this->member_id;
			redirect(get_link('viewprofileURL')."/".md5($log_member_id));
		}else{
			redirect(get_link('loginURL')."?ref=my-profile");
		}
	}
	public function view($member_id_enc='')
	{
		$member_id=getFieldData('member_id','member','md5(member_id)',$member_id_enc);
		if(!$member_id){
			show_404();
		}
		$is_editable=FALSE;
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
				'bootbox_custom.js',
			));
		$log_member_id=0;
		if($this->loggedUser){
			$log_member_id=$this->member_id;	
			if(!$member_id){
				$member_id=$log_member_id;
			}
			if($log_member_id==$member_id){
				$is_editable=TRUE;
			}
		}
		$this->data['login_user_id']=$log_member_id;
		$memberDataBasic=getData(array(
				'select'=>'m.member_name,m_b.member_heading,m_b.member_overview,m_b.member_hourly_rate,m_b.available_per_week,m_b.not_available_until,c_n.country_name,c.country_code_short,m_l.logo,m_s.avg_rating,m_s.total_earning,m_s.no_of_reviews,m_s.total_working_hour,m_s.success_rate',
				'table'=>'member as m',
				'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left'),array('table'=>'member_statistics m_s','on'=>'m.member_id=m_s.member_id','position'=>'left'),array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left'),array('table'=>'member_logo as m_l','on'=>'(m.member_id=m_l.member_id and m_l.status=1)','position'=>'left'),),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			));	
			
		if($memberDataBasic){
			$this->data['profile_url']=URL::get_link('viewprofileURL').'/'.md5($member_id);
			$this->data['member_id']=$member_id;
			$this->data['memberInfo']=$memberDataBasic;
			
			$badge_ids=array();
			$member_badges=getData(array(
				'select'=>'m.badge_id',
				'table'=>'member_badges as m',
				'where'=>array('m.member_id'=>$member_id),
			));
			if($member_badges){
				foreach($member_badges as $b=>$row){
					$badge_ids[]=$row->badge_id;
				}
			}
			$membership_id=getFieldData('membership_id','member_membership','member_id',$member_id);
			if($membership_id){
				$membership_badges=getData(array(
					'select'=>'m.badge_id',
					'table'=>'membership_badge as m',
					'where'=>array('m.membership_id'=>$membership_id),
				));
				if($membership_badges){
					foreach($membership_badges as $b=>$row){
						$badge_ids[]=$row->badge_id;
					}
				}
			}
			$badges=new stdClass();
			if($badge_ids){
				$badges=getData(array(
					'select'=>'b.icon_image,b_n.name,b_n.description',
					'table'=>'badges as b',
					'join'=>array(array('table'=>'badges_names as b_n','on'=>"(b.badge_id=b_n.badge_id and b_n.lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('b.status'=>1),
					'where_in'=>array('b.badge_id'=>$badge_ids),
					'order'=>array(array('b.display_order','asc')),
				));
			}	
			$this->data['memberInfo']->badges=$badges;
			$this->data['memberInfo']->total_jobs=$this->db->where(array('c.contractor_id'=>$member_id,'c.contract_status'=>1))->from('project_contract as c')->count_all_results();
			$this->data['is_editable']=$is_editable;
			if($is_editable){
				$this->data['all_skills']=getAllSkills();
				$this->layout->set_js(array(
					'utils/helper.js',
					'mycustom.js',
					'bootbox_custom.js',
					'bootstrap-tagsinput.min.js',
					'typeahead.bundle.min.js',
					'cropper.min.js',
					'main-editprofile.js',
					'moment-with-locales.js',
					'bootstrap-datetimepicker.min.js'
				));
				$this->layout->set_css(array(
					'bootstrap-tagsinput.css',
					'cropper.min.css',
					'bootstrap-datetimepicker.css'
				));
			}
			$this->layout->view('edit-profile', $this->data);
		}
		
	}
	public function delete_data(){
		checkrequestajax();
		if($this->access_member_type=='E'){
			redirect(get_link('dashboardURL'));
		}
		$data=array();
		$formtype=post('formtype');
		$dataid=post('Okey');
		$up='';
		if($this->loggedUser && $dataid){
			$member_id=$this->member_id;	
			if($formtype=='language'){
				$memberlanguage=getData(array(
					'select'=>'m_l.member_language_id',
					'table'=>'member_language as m_l',
					'where'=>array('m_l.member_id'=>$member_id,'m_l.member_language_id'=>$dataid),
					'single_row'=>true,
					));
				if($memberlanguage){
					$up=updateTable('member_language',array('language_status'=>0),array('member_language_id'=>$memberlanguage->member_language_id));
				}
			}
			elseif($formtype=='employment'){
				$memberemployment=getData(array(
					'select'=>'m_e.employment_id',
					'table'=>'member_employment as m_e',
					'where'=>array('m_e.member_id'=>$member_id,'m_e.employment_id'=>$dataid),
					'single_row'=>true,
					));
				if($memberemployment){
					$up=updateTable('member_employment',array('employment_status'=>0),array('employment_id'=>$memberemployment->employment_id));
				}
			}
			elseif($formtype=='education'){
				$membereducation=getData(array(
					'select'=>'m_e.education_id',
					'table'=>'member_education as m_e',
					'where'=>array('m_e.member_id'=>$member_id,'m_e.education_id'=>$dataid),
					'single_row'=>true,
					));
				if($membereducation){
					$up=updateTable('member_education',array('education_status'=>0),array('education_id'=>$membereducation->education_id));
				}
			}
			elseif($formtype=='portfolio'){
				$memberportfolio=getData(array(
					'select'=>'m_p.portfolio_id',
					'table'=>'member_portfolio as m_p',
					'where'=>array('m_p.member_id'=>$member_id,'m_p.portfolio_id'=>$dataid),
					'single_row'=>true,
					));
				if($memberportfolio){
					$up=updateTable('member_portfolio',array('portfolio_status'=>0),array('portfolio_id'=>$memberportfolio->portfolio_id));
				}
			}
		}
		if($up){
			$msg['status'] = 'OK';
		}else{
			$msg['status'] = 'FAIL';
		}
	}
	public function load_data($md5_member_id)
	{
		$type=get('type');
		$is_editable=FALSE;
		if($this->loggedUser){
			$log_member_id=$this->member_id;
			if(md5($log_member_id)==$md5_member_id){
				$is_editable=TRUE;
			}
		}
		$this->data['is_editable']=$is_editable;
		if($md5_member_id){	
			if($type=='language'){
				/* $memberData=getData(array(
						'select'=>'m_l.member_language_id,l.language_id,l.language_name,l.language_id,l_p.language_preference_name',
						'table'=>'member_language as m_l',
						'join'=>array(array('table'=>'language as l','on'=>'l.language_id=m_l.language_id','position'=>'left'),array('table'=>'language_preference as l_p','on'=>'m_l.language_preference_id=l_p.language_preference_id','position'=>'left')),
						'where'=>array('md5(m_l.member_id)'=>$md5_member_id,'m_l.language_status'=>1),
						'order'=>array(array('l_p.language_preference_ord','asc'),array('m_l.member_language_id','asc'))
						));*/

						$memberData=getData(array(
							'select'=>'m_l.member_language_id,l_n.language_id,l_n.language_name,l_p_n.language_preference_name',
							'table'=>'member_language as m_l',
							
							'join'=>array(array('table'=>'language_names as l_n','on'=>"(m_l.language_id=l_n.language_id and l_n.language_lang='".get_active_lang()."')",'position'=>'left'),array('table'=>'language_preference_names as l_p_n','on'=>"(m_l.language_preference_id=l_p_n.language_preference_id and l_p_n.language_preference_lang='".get_active_lang()."')",'position'=>'left'),array('table'=>'language_preference as l_p','on'=>'m_l.language_preference_id=l_p.language_preference_id','position'=>'left')),
	
							'where'=>array('md5(m_l.member_id)'=>$md5_member_id,'m_l.language_status'=>1),
							'order'=>array(array('l_p.language_preference_ord','asc'),array('m_l.member_language_id','asc'))
							));

				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-language-display', $this->data,TRUE);

			}
			elseif($type=='employment'){
				$memberData=getData(array(
						'select'=>'m_e.employment_company,m_e.employment_id,m_e.employment_city,m_e.employment_title,m_e.employment_role,m_e.employment_from,m_e.employment_to,m_e.employment_is_working_on,m_e.employment_description,c_n.country_name',
						'table'=>'member_employment as m_e',
						'join'=>array(array('table'=>'country as c','on'=>'m_e.employment_country_code=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')),
						'where'=>array('md5(m_e.member_id)'=>$md5_member_id,'m_e.employment_status'=>1),
						'order'=>array(array('m_e.employment_is_working_on','desc'),array('m_e.employment_to','desc'))
						));

				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-employment-display', $this->data,TRUE);
			}	
			elseif($type=='education'){
				$memberData=getData(array(
						'select'=>'m_e.education_school,m_e.education_from_year,m_e.education_end_year,m_e.education_degree,m_e.education_area_of_study,m_e.education_description,m_e.education_id',
						'table'=>'member_education as m_e',
						'where'=>array('md5(m_e.member_id)'=>$md5_member_id,'m_e.education_status'=>1),
						'order'=>array(array('m_e.education_end_year','desc'),array('m_e.education_id','desc'))
						));

				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-education-display', $this->data,TRUE);
			}
			elseif($type=='skill'){
				$memberData=getData(array(
						'select'=>'s_n.skill_name',
						'table'=>'member_skills as m_s',
						'join'=>array(array('table'=>'skills as s','on'=>'m_s.skill_id=s.skill_id','position'=>'left'),array('table'=>'skill_names as s_n','on'=>"(s.skill_id=s_n.skill_id and s_n.skill_lang='".get_active_lang()."')",'position'=>'left')),
						'where'=>array('md5(m_s.member_id)'=>$md5_member_id),
						'order'=>array(array('m_s.member_skills_order','asc'))
						));
				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-skill-display', $this->data,TRUE);
			}
			elseif($type=='portfolio'){
				$memberData=getData(array(
						'select'=>'m_p.portfolio_id,m_p.portfolio_title,m_p.portfolio_description,m_p.portfolio_complete_date,cs_n.category_subchild_name,m_p.portfolio_image',
						'table'=>'member_portfolio as m_p',
						'join'=>array(array('table'=>'category_subchild as cs','on'=>'m_p.category_subchild_id=cs.category_subchild_id','position'=>'left'),array('table'=>'category_subchild_names as cs_n','on'=>"(cs.category_subchild_id=cs_n.category_subchild_id and cs_n.category_subchild_lang='".get_active_lang()."')",'position'=>'left')),
						'where'=>array('md5(m_p.member_id)'=>$md5_member_id,'m_p.portfolio_status'=>1),
						'order'=>array(array('m_p.portfolio_id','desc'))
						));

				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-portfolio-display', $this->data,TRUE);
			}
			elseif($type=='reviews'){
				$memberData=getData(array(
						'select'=>'p.project_title,c_r.for_skills,c_r.for_quality,c_r.for_availability,c_r.for_deadlines,c_r.for_communication,c_r.for_cooperation,c_r.average_review,c_r.review_comments,c_r.review_date',
						'table'=>'contract_reviews as c_r',
						'join'=>array(
							array('table'=>'project as p','on'=>'c_r.project_id=p.project_id','position'=>'left'),
						),
						'where'=>array('md5(c_r.review_to)'=>$md5_member_id,'c_r.review_status'=>1,'c_r.is_display_public'=>1),
						'order'=>array(array('c_r.review_id','desc'))
						));

				$this->data['memberInfo']=$memberData;
				$this->layout->view('ajax-reviews-display', $this->data,TRUE);
			}
		}
	}
	public function get_form(){
		checkrequestajax();
		$form_type=get('formtype');
		if($form_type=='portfolio_view'){
			$dataid=get('Okey');
				$memberportfolio=array();
				if($dataid){
					$memberportfolio=getData(array(
					'select'=>'m_p.portfolio_id,m_p.portfolio_title,m_p.portfolio_description,m_p.portfolio_complete_date,m_p.portfolio_url,m_p.category_id,m_p.category_subchild_id,m_p.portfolio_image,c_n.category_name,cs_n.category_subchild_name',
					'table'=>'member_portfolio as m_p',
					'join'=>array(array('table'=>'category_names as c_n','on'=>"(m_p.category_id=c_n.category_id and c_n.category_lang='".get_active_lang()."')",'position'=>'left'),array('table'=>'category_subchild_names as cs_n','on'=>"(m_p.category_subchild_id=cs_n.category_subchild_id and cs_n.category_subchild_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('m_p.portfolio_id'=>$dataid),
					'single_row'=>true,
					));
				}	
				$this->data['memberInfo']=$memberportfolio;
				$this->data['all_category']=getAllCategory();
				$all_category_subchild=array();
				if($memberportfolio && $memberportfolio->category_id){
					$all_category_subchild=getAllSubCategory($memberportfolio->category_id);
				}
				$this->data['all_category_subchild']=$all_category_subchild;
				$this->data['formtype']=$form_type;
				$this->data['dataid']=$dataid;
				$this->layout->view('ajax-portfolio-view', $this->data,TRUE);
			//die;
		}
		elseif($this->access_member_type=='C' && $form_type!='getsubcat'){
			redirect(get_link('dashboardURL'));
		}
		elseif($this->loggedUser){
			$member_id=$this->member_id;	
			$form_type=get('formtype');
			if($form_type=='heading'){
				$memberData=getData(array(
					'select'=>'m_b.member_heading',
					'table'=>'member as m',
					'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left')),
					'where'=>array('m.member_id'=>$member_id),
					'single_row'=>true,
				));	
				if($memberData){
					$this->data['memberInfo']=$memberData;
					$this->data['formtype']=$form_type;
					$this->layout->view('ajax-heading-form', $this->data,TRUE);
				}
			}
			elseif($form_type=='overview')
			{
				$memberData=getData(array(
					'select'=>'m_b.member_overview',
					'table'=>'member as m',
					'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left')),
					'where'=>array('m.member_id'=>$member_id),
					'single_row'=>true,
				));	
				if($memberData){
					$this->data['memberInfo']=$memberData;
					$this->data['formtype']=$form_type;
					$this->layout->view('ajax-overview-form', $this->data,TRUE);
				}
			}
			elseif($form_type=='hourly')
			{
				$memberData=getData(array(
					'select'=>'m_b.member_hourly_rate',
					'table'=>'member as m',
					'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left')),
					'where'=>array('m.member_id'=>$member_id),
					'single_row'=>true,
				));	
				if($memberData){
					$this->data['memberInfo']=$memberData;
					$this->data['formtype']=$form_type;
					$this->layout->view('ajax-hourly-form', $this->data,TRUE);
				}
			}
			elseif($form_type=='availability')
			{
				$memberData=getData(array(
					'select'=>'m_b.available_per_week,m_b.not_available_until',
					'table'=>'member as m',
					'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left')),
					'where'=>array('m.member_id'=>$member_id),
					'single_row'=>true,
				));	
				if($memberData){
					$this->data['memberInfo']=$memberData;
					$this->data['all_Duration']=getAllProjectDurationTime();
					$this->data['formtype']=$form_type;
					$this->layout->view('ajax-availability-form', $this->data,TRUE);
				}
			}
			elseif($form_type=='language')
			{
				$dataid=get('Okey');
				$alllanguage=array();
				$memberlanguage=array();
				$language_preference=getData(array(
					'select'=>'l_p.language_preference_id,l_p_n.language_preference_name,l_p_n.language_preference_info',
					'table'=>'language_preference as l_p',
					'join'=>array(array('table'=>'language_preference_names as l_p_n','on'=>"(l_p.language_preference_id=l_p_n.language_preference_id and l_p_n.language_preference_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('l_p.language_preference_status'=>'1'),
				));
				if($dataid){
					$memberlanguage=getData(array(
					'select'=>'l.language_id,l_n.language_name,l.language_id,l_p.language_preference_id',
					'table'=>'member_language as m_l',
					'join'=>array(array('table'=>'language as l','on'=>'l.language_id=m_l.language_id','position'=>'left'),array('table'=>'language_preference as l_p','on'=>'m_l.language_preference_id=l_p.language_preference_id','position'=>'left'),array('table'=>'language_names as l_n','on'=>"(l.language_id=l_n.language_id and l_n.language_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('m_l.member_id'=>$member_id,'m_l.member_language_id'=>$dataid),
					'single_row'=>true,
					));
				}else{
					$alllanguage=getData(array(
					'select'=>'l.language_id,l_n.language_name',
					'table'=>'language as l',
					'join'=>array(array('table'=>'language_names as l_n','on'=>"(l.language_id=l_n.language_id and l_n.language_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('l.language_status'=>'1'),
					));
				}
					
				if($language_preference){
					$this->data['alllanguage']=$alllanguage;
					$this->data['language_preference']=$language_preference;
					$this->data['memberInfo']=$memberlanguage;
					$this->data['formtype']=$form_type;
					$this->data['dataid']=$dataid;
					$this->layout->view('ajax-language-form', $this->data,TRUE);
				}
			}
			elseif($form_type=='employment')
			{
				$dataid=get('Okey');
				$memberemployment=array();
				$this->data['country']=getAllCountry();
				$this->data['role']=getRoleUserEmployemnt();
				$this->data['month']=getMonth();
				if($dataid){
					$memberemployment=getData(array(
					'select'=>'m_e.employment_company,m_e.employment_id,m_e.employment_city,m_e.employment_country_code,m_e.employment_title,m_e.employment_role,m_e.employment_from,m_e.employment_to,m_e.employment_is_working_on,m_e.employment_description',
					'table'=>'member_employment as m_e',
					'where'=>array('m_e.member_id'=>$member_id,'m_e.employment_id'=>$dataid),
					'single_row'=>true,
					));
				}	
				if($this->data['country']){
					$this->data['memberInfo']=$memberemployment;
					$this->data['formtype']=$form_type;
					$this->data['dataid']=$dataid;
					$this->layout->view('ajax-employment-form', $this->data,TRUE);
				}
			}
			elseif($form_type=='education')
			{
				$dataid=get('Okey');
				$membereducation=array();
				if($dataid){
					$membereducation=getData(array(
					'select'=>'m_e.education_school,m_e.education_from_year,m_e.education_end_year,m_e.education_degree,m_e.education_area_of_study,m_e.education_description,m_e.education_id',
					'table'=>'member_education as m_e',
					'where'=>array('m_e.member_id'=>$member_id,'m_e.education_id'=>$dataid),
					'single_row'=>true,
					));
				}	
				$this->data['memberInfo']=$membereducation;
				$this->data['formtype']=$form_type;
				$this->data['dataid']=$dataid;
				$this->layout->view('ajax-education-form', $this->data,TRUE);
			}
			elseif($form_type=='skill')
			{
				$dataid=get('Okey');
				$this->data['memberInfo']=new stdClass;
				$memberskills=getData(array(
				'select'=>'s.skill_id,s.skill_key,s_n.skill_name',
				'table'=>'member_skills as m_s',
				'join'=>array(array('table'=>'skills as s','on'=>'m_s.skill_id=s.skill_id','position'=>'left'),array('table'=>'skill_names as s_n','on'=>"(s.skill_id=s_n.skill_id and s_n.skill_lang='".get_active_lang()."')",'position'=>'left')),
				'where'=>array('m_s.member_id'=>$member_id),
				'order'=>array(array('m_s.member_skills_order','asc'))
				));
				$this->data['memberInfo']->skills=$memberskills;
				$this->data['formtype']=$form_type;
				$this->data['dataid']=$dataid;
				$this->layout->view('ajax-skill-form', $this->data,TRUE);
			}
			elseif($form_type=='portfolio')
			{
				$dataid=get('Okey');
				$memberportfolio=array();
				if($dataid){
					$memberportfolio=getData(array(
					'select'=>'m_p.portfolio_id,m_p.portfolio_title,m_p.portfolio_description,m_p.portfolio_complete_date,m_p.portfolio_url,m_p.category_id,m_p.category_subchild_id,m_p.portfolio_image',
					'table'=>'member_portfolio as m_p',
					'where'=>array('m_p.member_id'=>$member_id,'m_p.portfolio_id'=>$dataid),
					'single_row'=>true,
					));
					$this->data['limit_over']=0;
				}else{
					$this->data['limit_over']=1;
					$membership=getMembershipData($member_id,array('portfolio'));
					if($membership['max_portfolio'] > $membership['used_portfolio']){
						$this->data['limit_over']=0;
					}
				}
				$this->data['memberInfo']=$memberportfolio;
				$this->data['all_category']=getAllCategory();
				$all_category_subchild=array();
				if($memberportfolio && $memberportfolio->category_id){
					$all_category_subchild=getAllSubCategory($memberportfolio->category_id);
				}
				$this->data['all_category_subchild']=$all_category_subchild;
				$this->data['formtype']=$form_type;
				$this->data['dataid']=$dataid;
				$this->layout->view('ajax-portfolio-form', $this->data,TRUE);
			}
			elseif($form_type=='portfolio_view')
			{
				$dataid=get('Okey');
				$memberportfolio=array();
				if($dataid){
					$memberportfolio=getData(array(
					'select'=>'m_p.portfolio_id,m_p.portfolio_title,m_p.portfolio_description,m_p.portfolio_complete_date,m_p.portfolio_url,m_p.category_id,m_p.category_subchild_id,m_p.portfolio_image,c_n.category_name,cs_n.category_subchild_name',
					'table'=>'member_portfolio as m_p',
					'join'=>array(array('table'=>'category_names as c_n','on'=>"(m_p.category_id=c_n.category_id and c_n.category_lang='".get_active_lang()."')",'position'=>'left'),array('table'=>'category_subchild_names as cs_n','on'=>"(m_p.category_subchild_id=cs_n.category_subchild_id and cs_n.category_subchild_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('m_p.member_id'=>$member_id,'m_p.portfolio_id'=>$dataid),
					'single_row'=>true,
					));
				}	
				$this->data['memberInfo']=$memberportfolio;
				$this->data['all_category']=getAllCategory();
				$all_category_subchild=array();
				if($memberportfolio && $memberportfolio->category_id){
					$all_category_subchild=getAllSubCategory($memberportfolio->category_id);
				}
				$this->data['all_category_subchild']=$all_category_subchild;
				$this->data['formtype']=$form_type;
				$this->data['dataid']=$dataid;
				$this->layout->view('ajax-portfolio-view', $this->data,TRUE);
			}
			elseif($form_type=='getsubcat')
			{
				$dataid=get('Okey');
				$all_category_subchild=array();
				if($dataid){
					$all_category_subchild=getAllSubCategory($dataid);
				}
				$this->data['all_category_subchild']=$all_category_subchild;
				$this->layout->view('ajax-subcategory-form', $this->data,TRUE);
			
			}
			elseif($form_type=='logo'){
				$memberData=getData(array(
					'select'=>'m_l.logo',
					'table'=>'member as m',
					'join'=>array(array('table'=>'member_logo as m_l','on'=>'m.member_id=m_l.member_id','position'=>'left')),
					'where'=>array('m.member_id'=>$member_id),
					'single_row'=>true,
				));	
				if($memberData){
					$this->data['memberInfo']=$memberData;
					$this->data['formtype']=$form_type;
					$this->layout->view('ajax-logo-form', $this->data,TRUE);
				}
			}
		}
	}
	public function get_form_check(){
		$this->load->library('form_validation');
		checkrequestajax();
		if($this->access_member_type=='E'){
			redirect(get_link('dashboardURL'));
		}
		$i=0;
		$msg=array();
		if($this->loggedUser){
		$member_id=$this->member_id;
		if($member_id){
			if($this->input->post()){
			$form_type=post('formtype');
			$dataid=post('dataid');
				if($form_type=='heading')
				{
					$this->form_validation->set_rules('heading', 'Heading', 'required|trim|xss_clean');
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
						$memberDatacount=getData(array(
							'select'=>'m_b.member_id',
							'table'=>'member_basic as m_b',
							'where'=>array('m_b.member_id'=>$member_id),
							'return_count'=>true,
						));
						if(!$memberDatacount){
							$up=insert_record('member_basic',array('member_id'=>$member_id,'member_heading'=>post('heading')),TRUE);
						}else{
							$up=updateTable('member_basic',array('member_heading'=>trim(post('heading'))),array('member_id'=>$member_id));
						}
						
						if($up){
							$msg['status'] = 'OK';
							$msg['msg_heading'] = trim(post('heading'));
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'heading';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}	
				}
				elseif($form_type=='overview')
				{
					$this->form_validation->set_rules('overview', 'Overview', 'required|trim|xss_clean');
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
						$memberDatacount=getData(array(
							'select'=>'m_b.member_id',
							'table'=>'member_basic as m_b',
							'where'=>array('m_b.member_id'=>$member_id),
							'return_count'=>true,
						));
						if(!$memberDatacount){
							$up=insert_record('member_basic',array('member_id'=>$member_id,'member_overview'=>post('overview')),TRUE);
						}else{
							$up=updateTable('member_basic',array('member_overview'=>trim(post('overview'))),array('member_id'=>$member_id));
						}
						
						if($up){
							$msg['status'] = 'OK';
							$msg['msg_overview'] = nl2br(trim(post('overview')));
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'heading';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}	
				elseif($form_type=='hourly')
				{
					$this->form_validation->set_rules('hourly', 'Hourly', 'required|trim|xss_clean|numeric');
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
						$memberDatacount=getData(array(
							'select'=>'m_b.member_id',
							'table'=>'member_basic as m_b',
							'where'=>array('m_b.member_id'=>$member_id),
							'return_count'=>true,
						));
						if(!$memberDatacount){
							$up=insert_record('member_basic',array('member_id'=>$member_id,'member_hourly_rate'=>post('hourly')),TRUE);
						}else{
							$up=updateTable('member_basic',array('member_hourly_rate'=>trim(post('hourly'))),array('member_id'=>$member_id));
						}
						
						if($up){
							$msg['status'] = 'OK';
							$msg['msg_hourly'] = priceSymbol().priceFormat(trim(post('hourly')));
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'hourly';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}	
				elseif($form_type=='availability')
				{
					$this->form_validation->set_rules('is_available', 'is_available', 'required|trim|xss_clean|numeric');
					$is_available=post('is_available');
					if($is_available==1){
						$this->form_validation->set_rules('available_per_week', 'week', 'required|trim|xss_clean');
					}else{
						$this->form_validation->set_rules('not_available_until', 'date', 'required|trim|xss_clean|valid_date');
					}
					
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
						$memberDatacount=getData(array(
							'select'=>'m_b.member_id',
							'table'=>'member_basic as m_b',
							'where'=>array('m_b.member_id'=>$member_id),
							'return_count'=>true,
						));
						
						$data=array();
						if($is_available==1){
							$data['available_per_week']=post('available_per_week');
							$data['not_available_until']=NULL;
							$duration=getAllProjectDurationTime($data['available_per_week']);
							$availability=$duration['freelanceName'];
						}else{
							$data['not_available_until']=post('not_available_until');
							$availability='Offline till '.dateFormat($data['not_available_until']);
						}
						
						if(!$memberDatacount){
							$data['member_id']=$member_id;
							$up=insert_record('member_basic',$data,TRUE);
							
						}else{
							$up=updateTable('member_basic',$data,array('member_id'=>$member_id));
						}
						
						if($up){
							$msg['status'] = 'OK';
							$msg['msg_availability'] = $availability;
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'availability';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				elseif($form_type=='language')
				{
					$this->form_validation->set_rules('language', 'language', 'required|trim|xss_clean|numeric');
					$this->form_validation->set_rules('language_preference', 'Preference', 'required|trim|xss_clean|numeric');
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
						$memberDatacount=getData(array(
							'select'=>'m_l.member_language_id',
							'table'=>'member_language as m_l',
							'join'=>array(array('table'=>'language as l','on'=>'m_l.language_id=l.language_id','position'=>'left')),
							'where'=>array('m_l.member_id'=>$member_id,'l.language_id'=>post('language')),
							'return_count'=>true,
						));
						if(!$memberDatacount){
							$up=insert_record('member_language',array('member_id'=>$member_id,'language_id'=>post('language'),'language_preference_id'=>post('language_preference'),'language_status'=>1),TRUE);
						}else{
							$up=updateTable('member_language',array('language_preference_id'=>post('language_preference'),'language_status'=>1),array('member_id'=>$member_id,'language_id'=>post('language')));
						}
						
						if($up){
							$msg['status'] = 'OK';
							//$msg['msg_language'] = trim(post('language'));
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'language';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				elseif($form_type=='employment')
				{
					$this->form_validation->set_rules('company', 'Company', 'required|trim|xss_clean');
					$this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
					$this->form_validation->set_rules('country', 'Country', 'required|trim|xss_clean');
					$this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
					$this->form_validation->set_rules('role', 'Role', 'required|trim|xss_clean');
					$this->form_validation->set_rules('frommonth', 'Month', 'required|trim|xss_clean');
					$this->form_validation->set_rules('fromyear', 'year', 'required|trim|xss_clean');
					if(post('employment_is_working_on')){
						
					}else{
						$this->form_validation->set_rules('tomonth', 'Month', 'required|trim|xss_clean');
						$this->form_validation->set_rules('toyear', 'year', 'required|trim|xss_clean');
					}
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
						$memberDatacount=getData(array(
							'select'=>'m_e.employment_id',
							'table'=>'member_employment as m_e',
							'where'=>array('m_e.member_id'=>$member_id,'m_e.employment_id'=>$dataid),
							'single_row'=>true,
						));
						$employment_is_working_on=NULL;
						$employment_to=NULL;
						$f_year=post('fromyear');
						$f_month=post('frommonth');
						if(strlen($f_month)==1){
							$f_month="0".$f_month;
						}
						$employment_from=$f_year."-".$f_month."-01";
						if(post('employment_is_working_on')){
							$employment_is_working_on=1;
						}else{
							$t_year=post('toyear');
							$t_month=post('tomonth');
							if(strlen($t_month)==1){
								$t_month="0".$t_month;
							}
							$employment_to=$t_year."-".$t_month."-01";
						}
						
						if($memberDatacount){
							$up=updateTable('member_employment',array('employment_company'=>post('company'),'employment_city'=>post('city'),'employment_country_code'=>post('country'),'employment_title'=>post('title'),'employment_role'=>post('role'),'employment_from'=>$employment_from,'employment_to'=>$employment_to,'employment_is_working_on'=>$employment_is_working_on,'employment_description'=>post('description'),'employment_status'=>1),array('member_id'=>$member_id,'employment_id'=>$memberDatacount->employment_id));
						}else{
							$up=insert_record('member_employment',array('member_id'=>$member_id,'employment_company'=>post('company'),'employment_city'=>post('city'),'employment_country_code'=>post('country'),'employment_title'=>post('title'),'employment_role'=>post('role'),'employment_from'=>$employment_from,'employment_to'=>$employment_to,'employment_is_working_on'=>$employment_is_working_on,'employment_description'=>post('description'),'employment_status'=>1),TRUE);
						}
						
						if($up){
							$msg['status'] = 'OK';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'employment';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				elseif($form_type=='education')
				{
					$this->form_validation->set_rules('school', 'School', 'required|trim|xss_clean');
					$this->form_validation->set_rules('from_year', 'From year', 'required|trim|xss_clean');
					$this->form_validation->set_rules('end_year', 'To year', 'required|trim|xss_clean');
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
						$memberDatacount=getData(array(
							'select'=>'m_e.education_id',
							'table'=>'member_education as m_e',
							'where'=>array('m_e.member_id'=>$member_id,'m_e.education_id'=>$dataid),
							'single_row'=>true,
						));
						$data_ins=array(
						'education_school'=>post('school'),
						'education_from_year'=>post('from_year'),
						'education_end_year'=>post('end_year'),
						'education_degree'=>NULL,
						'education_area_of_study'=>NULL,
						'education_description'=>NULL,
						'education_status'=>1,
						);
						if(post('degree')){
							$data_ins['education_degree']=post('degree');
						}
						if(post('area_of_study')){
							$data_ins['education_area_of_study']=post('area_of_study');
						}
						if(post('description')){
							$data_ins['education_description']=post('description');
						}

						if($memberDatacount){
							$up=updateTable('member_education',$data_ins,array('member_id'=>$member_id,'education_id'=>$memberDatacount->education_id));
						}else{
							$data_ins['member_id']=$member_id;
							$up=insert_record('member_education',$data_ins,TRUE);
						}
						
						if($up){
							$msg['status'] = 'OK';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'education';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				elseif($form_type=='skill')
				{
					
					
					if($i==0){
						$all_skill=post('skills');
						if($all_skill){
							$sk=explode(',',$all_skill);
							$membership=getMembershipData($member_id,array('skills'));
							if($membership['max_skills'] >= count($sk)){

							}else{
								$msg['status'] = 'FAIL';
								$msg['errors'][$i]['id'] = 'skills';
								$msg['errors'][$i]['message'] = 'Max limit over, please upgrade your membership plan.';
								$i++;
								unset($_POST);
								echo json_encode($msg);
								die;
							}
						}
						delete_record('member_skills',array('member_id'=>$member_id));
						if($all_skill){
							foreach($sk as $ord=>$skill_id){
								insert_record('member_skills',array('member_id'=>$member_id,'skill_id'=>$skill_id,'member_skills_order'=>$ord),TRUE);
							}
						}
						$up=1;
						if($up){
							$msg['status'] = 'OK';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'skills';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				elseif($form_type=='portfolio')
				{
					$this->form_validation->set_rules('title', 'Project Title', 'required|trim|xss_clean');
					$this->form_validation->set_rules('description', 'Project Overview', 'required|trim|xss_clean');
					$this->form_validation->set_rules('category', 'Category', 'required|trim|xss_clean');
					if(post('category')){
						$this->form_validation->set_rules('sub_category', 'Sub Category', 'required|trim|xss_clean');
					}
					if(post('complete_date')){  
						$this->form_validation->set_message('valid_date', 'The Date field must be dd-mm-yyyy');
						$this->form_validation->set_rules('complete_date', 'Date', 'required|trim|xss_clean|valid_date[Ymd]');
						
					}
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
						$memberDatacount=getData(array(
							'select'=>'m_p.portfolio_id',
							'table'=>'member_portfolio as m_p',
							'where'=>array('m_p.member_id'=>$member_id,'m_p.portfolio_id'=>$dataid),
							'single_row'=>true,
						));
						$data_ins=array(
						'portfolio_title'=>post('title'),
						'category_id'=>post('category'),
						'category_subchild_id'=>post('sub_category'),
						'portfolio_description'=>post('description'),
						'portfolio_url'=>NULL,
						'portfolio_image'=>NULL,
						'portfolio_complete_date'=>NULL,
						'portfolio_status'=>1,
						);
						if(post('url')){
							$data_ins['portfolio_url']=addhttp(trim(strtolower(post('url'))));
						}
						if(post('complete_date')){
							$data_ins['portfolio_complete_date']=date('Y-m-d',strtotime(post('complete_date')));
						}
						if(post('projectfile')){
							$projectfiles=post('projectfile');
							foreach($projectfiles as $file){
								$file_data=json_decode($file);
								if($file_data){
									if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
										rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."member-portfolio/".$file_data->file_name);
										$ext=explode('.',$file_data->file_name);
										$attahment=array(
											'name'=>$file_data->original_name,
											'file'=>$file_data->file_name,
										);
										$data_ins['portfolio_image']=json_encode($attahment);
									}
								}
							}
						}elseif(post('projectfileprevious')){
							$data_ins['portfolio_image']=post('projectfileprevious');
						}



						if($memberDatacount){
							$up=updateTable('member_portfolio',$data_ins,array('member_id'=>$member_id,'portfolio_id'=>$memberDatacount->portfolio_id));
						}else{
							$data_ins['member_id']=$member_id;
							$up=insert_record('member_portfolio',$data_ins,TRUE);
						}
						
						if($up){
							$msg['status'] = 'OK';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'portfolio';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				if($form_type=='logo')
				{
					$up=0;
					$i=0;
					if($i==0){
					$this->load->library('cropimage');
					$crop=$this->cropimage->cropimageP(UPLOAD_PATH."member-logo/".md5($member_id)."-");
					$filepathFullpath=$crop -> getResult();
					$a=explode("/",$filepathFullpath);
					$filename=end($a);
					$response = array(
					  'state'  => 200,
					  'message' => $crop -> getMsg(),
					  'filename'=>$filename,
					  'fullpath'=>UPLOAD_HTTP_PATH."member-logo/".$filename,
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
			            
						$profile_logo=getFieldData('logo','member_logo','member_id',$member_id);
						if($profile_logo!=''){
							@unlink(UPLOAD_PATH."member_logo/".$profile_logo);
							$name=str_replace("_thumb",'',$profile_logo);
							$r=explode(".",$name);
							$fileext = end($r);
							$filename = basename(UPLOAD_PATH."member-logo/".$name,".".$fileext);
							if(file_exists(UPLOAD_PATH."member-logo/".$filename.".jpg")){
								@unlink(UPLOAD_PATH."member-logo/".$filename.".jpg");
							}if(file_exists(UPLOAD_PATH."member-logo/".$filename.".jpeg")){
								@unlink(UPLOAD_PATH."member-logo/".$filename.".jpeg");
							}elseif(file_exists(UPLOAD_PATH."member-logo/".$filename.".png")){
								@unlink(UPLOAD_PATH."member-logo/".$filename.".png");
							}elseif(file_exists(UPLOAD_PATH."member-logo/".$filename.".gif")){
								@unlink(UPLOAD_PATH."member-logo/".$filename.".gif");
							}
						}
						$memberDatacount=getData(array(
							'select'=>'m_l.member_id',
							'table'=>'member_logo as m_l',
							'where'=>array('m_l.member_id'=>$member_id),
							'return_count'=>true,
						));
						if(!$memberDatacount){
							$up=insert(array('table'=>'member_logo','data'=>array('member_id'=>$member_id,'logo'=>$response['filename'],'status'=>1,'reg_date'=>date('Y-m-d H:i:s'))),TRUE);
							
						}else{
							$up=update(array('table'=>'member_logo','data'=>array('logo'=>$response['filename'],'status'=>1,'reg_date'=>date('Y-m-d H:i:s')),'where'=>array('member_id'=>$member_id)));
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
		}else{
			$msg['status'] = 'FAIL';
			$msg['errors'][$i]['id'] = 'error';
			$msg['errors'][$i]['message'] = 'Invalid ';
			$i++;
		}
	unset($_POST);
	echo json_encode($msg);
	}
	}
		
}
