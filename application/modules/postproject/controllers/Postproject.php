<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postproject extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='F';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
			$this->organization_id=$this->loggedUser['OID'];
		}else{
			redirect(URL::get_link('loginURL').'?ref=postprojectURL');
		}	
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		//print_r($this->loggedUser);
		parent::__construct();
	}
	public function index()
	{
		redirect(get_link('postprojectURL'));
	}
	public function add()
	{
		if($this->access_member_type=='F'){
			redirect(get_link('dashboardURL'));
		}
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
				'bootstrap-tagsinput.min.js',
				'typeahead.bundle.min.js',
				'upload-drag-file.js'
			));
		$this->layout->set_css(array(
				'bootstrap-tagsinput.css'
			));
		if($this->loggedUser){
			$member_id=$this->member_id;	
			$memberData=getData(array(
				'select'=>'o.organization_name,o.organization_email,m.member_name,m.member_email,o_l.logo',
				'table'=>'member as m',
				'join'=>array(array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left'),array('table'=>'organization_logo as o_l','on'=>'(o.organization_id=o_l.organization_id and o_l.status=1)','position'=>'left')),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			));	
			if($memberData){
				$this->data['projectData']=array();
				$this->data['itemid']='';
				$this->data['organizationInfo']=$memberData;
				$this->data['all_category']=getAllCategory();
				$this->data['all_projectType']=getAllProjectType();
				$this->data['all_projectExperienceLevel']=getAllExperienceLevel();
				$this->data['all_projectDuration']=getAllProjectDuration();
				$this->data['all_projectDurationTime']=getAllProjectDurationTime();
				$this->data['all_skills']=getAllSkills();
				//$this->data['left_panel']=load_view('inc/client-setting-left','',TRUE);
				$this->layout->view('post-project', $this->data);
			}
		}	
	}
	public function edit($md5id='',$token='')
	{
		if($this->access_member_type=='F'){
			redirect(get_link('dashboardURL'));
		}
		$this->data['itemid']=$md5id;	
		$verify_token=md5('UPW'.'-'.date("Y-m-d").'-'.$md5id);
		$this->layout->set_js(array(
				'utils/helper.js',
				'mycustom.js',
				'bootstrap-tagsinput.min.js',
				'typeahead.bundle.min.js',
				'upload-drag-file.js'
			));
		$this->layout->set_css(array(
				'bootstrap-tagsinput.css'
			));
			
				
		if($this->loggedUser){
			$member_id=$this->member_id;	
			if($verify_token==$token){	
				$arr=array(
					'select'=>'p.project_id,p.project_url,p.project_title,p.project_posted_date,p.project_expired_date,p.project_status',
					'table'=>'project as p',
					'join'=>array(
						array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left'),
					),
					'where'=>array('p.project_status <>'=>PROJECT_DELETED,'p_o.member_id'=>$member_id),
					'single_row'=>true,
					);
				$arr['where']['md5(p.project_id)']=$md5id;
				$ProjectDataBasic=getData($arr);
				if($ProjectDataBasic){
					$project_id=$ProjectDataBasic->project_id;
					$this->data['projectData']=getProjectDetails($project_id);
				}else{
					redirect(get_link('dashboardURL'));
				}
			}else{
				redirect(get_link('dashboardURL'));
			}
			
			$memberData=getData(array(
				'select'=>'o.organization_name,o.organization_email,m.member_name,m.member_email,o_l.logo',
				'table'=>'member as m',
				'join'=>array(array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left'),array('table'=>'organization_logo as o_l','on'=>'(o.organization_id=o_l.organization_id and o_l.status=1)','position'=>'left')),
				'where'=>array('m.member_id'=>$member_id),
				'single_row'=>true,
			));	
			if($memberData){
				$this->data['organizationInfo']=$memberData;
				$this->data['all_category']=getAllCategory();
				$this->data['all_projectType']=getAllProjectType();
				$this->data['all_projectExperienceLevel']=getAllExperienceLevel();
				$this->data['all_projectDuration']=getAllProjectDuration();
				$this->data['all_projectDurationTime']=getAllProjectDurationTime();
				$this->data['all_skills']=getAllSkills();
				//$this->data['left_panel']=load_view('inc/client-setting-left','',TRUE);
				$this->layout->view('post-project', $this->data);
			}
		}	
	}
	public function uploadattachment(){
		if($this->loggedUser){
		$config['upload_path']          = TMP_UPLOAD_PATH;
		$from=get('from');
		if($from=='verifydocument'){
			$config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|bnp';
		}elseif($from=='portfolio'){
			$config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|bnp';
		}else{
			$config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|zip|rar|txt|ppt|pptx|bnp|svg';
		}
        
        
        $config['max_size']             = 2048;
        $config['file_name']            = md5($this->member_id.'-'.time());
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('fileinput'))
        {
            $msg['status']='FAIL';
            $msg['error']= $this->upload->display_errors();
        }
        else
        {
        	$msg['status']='OK';
        	$upload_data=$this->upload->data();
        	$msg['upload_response']=array('file_name'=>$upload_data['file_name'],'original_name'=>$upload_data['client_name']);
        }
		echo json_encode($msg);
		}
	}
	public function post_project_form_check($step=""){
		$this->load->library('form_validation');
		checkrequestajax();
		if($this->access_member_type=='F'){
			redirect(get_link('dashboardURL'));
		}
		$i=0;
		$project_id=0;
		$is_edited=0;
		$msg=array();
		if($this->loggedUser){
		$member_id=$this->member_id;	
		$organization_id=$this->organization_id;
		if($member_id){
			if($this->input->post()){
				$dataid=post('dataid');
				if($dataid){
					if($member_id){
						$arr=array(
							'select'=>'p.project_id',
							'table'=>'project as p',
							'join'=>array(
								array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left'),
							),
							'where'=>array('p.project_status <>'=>PROJECT_DELETED),
							'single_row'=>true,
							);
						$arr['where']['md5(p.project_id)']=$dataid;
						$arr['where']['p_o.member_id']=$member_id;
						$ProjectDataBasic=getData($arr);
						if($ProjectDataBasic){
							$project_id=$ProjectDataBasic->project_id;
							$is_edited=1;
						}else{
							show_404();
						}
					}else{
						show_404();
					}
				}

				if($step=='1' || $step=='7')
				{
					$this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
					$this->form_validation->set_rules('category', 'Category', 'required|trim|xss_clean|is_natural_no_zero');
					$this->form_validation->set_rules('sub_category', 'Speciality', 'required|trim|xss_clean|is_natural_no_zero');
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
						$up=1;
						if($up){
							$msg['status'] = 'OK';
							$msg['preview_data'] = 'title';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'title';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				if($step=='2' || $step=='7')
				{
					$this->form_validation->set_rules('description', 'Description', 'required|trim|xss_clean');
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
						$up=1;
						if($up){
							$msg['status'] = 'OK';
							$msg['preview_data'] = 'description';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'title';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				if($step=='3' || $step=='7')
				{
					$this->form_validation->set_rules('projectType', 'Project Type', 'required|trim|xss_clean');
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
						$up=1;
						if($up){
							$msg['status'] = 'OK';
							$msg['preview_data'] = 'details';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'title';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				if($step=='4' || $step=='7')
				{
					$this->form_validation->set_rules('skills', 'Skills', 'required|trim|xss_clean');
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
						$up=1;
						if($up){
							$msg['status'] = 'OK';
							$msg['preview_data'] = 'expertise';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'title';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				if($step=='5' || $step=='7')
				{
					$this->form_validation->set_rules('projectVisibility', 'Project Visibility', 'required|trim|xss_clean|in_list[public,private,invite]');
					$this->form_validation->set_rules('member_required', 'member required', 'required|trim|xss_clean|in_list[S,M]');
					if(post('member_required') && post('member_required')=='M'){
						$this->form_validation->set_rules('no_of_freelancer', 'no of freelancer', 'required|trim|xss_clean|is_natural_no_zero');
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
						$up=1;
						if($up){
							$msg['status'] = 'OK';
							$msg['preview_data'] = 'visibility';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'title';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				if($step=='6' || $step=='7')
				{
					$this->form_validation->set_rules('projectPaymentType', 'Project pay type', 'required|trim|xss_clean|in_list[fixed,hourly]');
					$this->form_validation->set_rules('experience_level', 'Experience level required', 'required|trim|xss_clean');
					if(post('projectPaymentType') && post('projectPaymentType')=='fixed'){
						$this->form_validation->set_rules('fixed_budget', 'budget', 'required|trim|xss_clean|numeric');
					}
					if(post('projectPaymentType') && post('projectPaymentType')=='hourly'){
						$this->form_validation->set_rules('hourly_duration', 'Duration', 'required|trim|xss_clean');
						$this->form_validation->set_rules('hourly_duration_time', 'Duration time', 'required|trim|xss_clean');
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
						$up=1;
						if($up){
							$msg['status'] = 'OK';
							$msg['preview_data'] = 'budget';
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'title';
							$msg['errors'][$i]['message'] = 'Invalid ';
							$i++;
						}
					}
				}
				if($step=='7' && $i==0)
				{
					/*$chk=array(
						'select'=>'p.project_id',
						'table'=>'project as p',
						'join'=>array(array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left')),
						'where'=>array('p.project_id'=>$dataid),
						'single_row'=>true,
					)
					$chk['where']['p_o.organization_id']=$organization_id;
					$memberDatacount=getData($chk);;*/
					$project_title=generateProjectSlug(post('title'));
					$project=array(
					'project_title'=>post('title'),
					'project_short_info'=>substr(strip_tags(post('description')),0,150),
					'project_member_required'=>1,
					'project_posted_date'=>date('Y-m-d H:i:s'),
					'project_expired_date'=>date('Y-m-d H:i:s',strtotime('+1 month')),
					'project_status'=>PROJECT_OPEN,
					'project_edit_id'=>NULL,
					'project_url'=>$project_title,
					);
					if(post('member_required') && post('member_required')=='M'){
						$project['project_member_required']=post('no_of_freelancer');
					}
					if($is_edited){
						unset($project['project_url']);
						updateTable('project',$project,array('project_id'=>$project_id));
					}else{
						$project_id=insert_record('project',$project,TRUE);
					}
					if($project_id){
						if($is_edited){}else{
							$project_owner=array(
							'project_id'=>$project_id,
							'member_id'=>$member_id,
							'organization_id'=>$organization_id,
							);
							insert_record('project_owner',$project_owner);
						}
						$project_category=array(
						'project_id'=>$project_id,
						'category_id'=>post('category'),
						'category_subchild_id'=>post('sub_category'),
						);
						if($is_edited){
							unset($project_category['project_id']);
							updateTable('project_category',$project_category,array('project_id'=>$project_id));
						}else{
							insert_record('project_category',$project_category);
						}
						
						$project_additional=array(
						'project_id'=>$project_id,
						'project_description'=>post('description'),
						'project_is_cover_required'=>NULL,
						);
						if(post('question')){
							if(post('is_cover_required')){
								$project_additional['project_is_cover_required']=1;
							}
						}else{
							$project_additional['project_is_cover_required']=1;
						}
						if($is_edited){
							unset($project_additional['project_id']);
							updateTable('project_additional',$project_additional,array('project_id'=>$project_id));
						}else{
							insert_record('project_additional',$project_additional);
						}
						if($is_edited==1){
							$previous_file=array();
							if(post('projectfileprevious')){
								$projectfileprevious=post('projectfileprevious');
								foreach($projectfileprevious as $file){
									$file_data_p=json_decode($file);
									if($file_data_p){
										$previous_file[]=$file_data_p->file_id;
										$is_primary=0;
										$file_order[]=array('file_id'=>$file_data_p->file_id);
									}
								}
							}
							if($previous_file){
								$this->db->where_not_in('file_id',$previous_file)->where('project_id',$project_id)->delete('project_files');
							}else{
								$this->db->where('project_id',$project_id)->delete('project_files');
							}
						}
						if(post('projectfile')){
							$projectfiles=post('projectfile');
							foreach($projectfiles as $file){
								$file_data=json_decode($file);
								if($file_data){
									if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
										rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."projects-files/projects-requirement/".$file_data->file_name);
										$ext=explode('.',$file_data->file_name);
										$files=array(
										'original_name'=>$file_data->original_name,
										'server_name'=>$file_data->file_name,
										'upload_time'=>date('Y-m-d H:i:s'),
										'file_ext'=>strtolower(end($ext)),
										);
										$file_id=insert_record('files',$files,TRUE);
										if($file_id){
											$project_files=array(
											'project_id'=>$project_id,
											'file_id'=>$file_id,
											);
											insert_record('project_files',$project_files);
										}
									}
								}
							}
						}
						if($is_edited==1){
							$previous_question=array();
							if(post('pre_question')){
								$projectpre_question=post('pre_question');
								foreach($projectpre_question as $question_id=>$question){
									if(trim($question)!=''){
									if($question_id){
										$previous_question[]=$question_id;
									}
									$questionDatacount=getData(array(
										'select'=>'q.question_id',
										'table'=>'question as q',
										'where'=>array('LOWER(q.question_title)'=>trim(strtolower($question))),
										'single_row'=>true,
									)
									);
									$newquestion_id=0;
									if($questionDatacount){
										$newquestion_id=$questionDatacount->question_id;
										updateTable('question',array('question_status'=>1),array('question_id'=>$question_id));
									}else{
										$question=array(
										'question_title'=>$question,
										'category_subchild_id'=>$project_category['category_subchild_id'],
										'question_status'=>1,
										'is_manual'=>1,
										);
										$newquestion_id=insert_record('question',$question,TRUE);
									}
									if($newquestion_id){
										$questionDatacount_p=getData(array(
										'select'=>'q.question_id',
										'table'=>'project_question as q',
										'where'=>array('project_id'=>$project_id,'question_id'=>$newquestion_id),
										'single_row'=>true,
										)
										);
										if(!$questionDatacount_p){
											$previous_question[]=$newquestion_id;
											$project_question=array(
											'project_id'=>$project_id,
											'question_id'=>$newquestion_id,
											'project_question_status'=>1,
											);
											insert_record('project_question',$project_question);
										}
											
									}
									}
								}
							}
							if($previous_question){
								$this->db->where_not_in('question_id',$previous_question)->where('project_id',$project_id)->delete('project_question');
							}else{
								$this->db->where('project_id',$project_id)->delete('project_question');
							}
							
						}
						if(post('question')){
							if($is_edited==1){}else{
								$this->db->where('project_id', $project_id)->delete('project_question');
							}
							$projectquestion=post('question');
							foreach($projectquestion as $question){
							if(trim($question)!=''){
								$questionDatacount=getData(array(
									'select'=>'q.question_id',
									'table'=>'question as q',
									'where'=>array('LOWER(q.question_title)'=>trim(strtolower($question))),
									'single_row'=>true,
								)
								);
								if($questionDatacount){
									$question_id=$questionDatacount->question_id;
									updateTable('question',array('question_status'=>1),array('question_id'=>$question_id));
								}else{
									$question=array(
									'question_title'=>$question,
									'category_subchild_id'=>$project_category['category_subchild_id'],
									'question_status'=>1,
									'is_manual'=>1,
									);
									$question_id=insert_record('question',$question,TRUE);
									
								}
								if($question_id){
									$project_question=array(
									'project_id'=>$project_id,
									'question_id'=>$question_id,
									'project_question_status'=>1,
									);
									insert_record('project_question',$project_question);
								}
							}
							}
						}
						if(post('skills')){
							$all_skill=post('skills');
							updateTable('project_skills',array('project_skill_status'=>0),array('project_id'=>$project_id));
							if($all_skill){
								$sk=explode(',',$all_skill);
								foreach($sk as $ord=>$skill_id){
									$skillDatacount=getData(array(
										'select'=>'p_s.project_skill_id',
										'table'=>'project as p',
										'join'=>array(array('table'=>'project_skills as p_s','on'=>'p.project_id=p_s.project_id','position'=>'left')),
										'where'=>array('p.project_id'=>$project_id,'p_s.skill_id'=>$skill_id),
										'single_row'=>true,
									));
									if($skillDatacount){
										updateTable('project_skills',array('project_skill_status'=>1),array('project_skill_id'=>$skillDatacount->project_skill_id));
									}else{
										insert_record('project_skills',array('project_id'=>$project_id,'skill_id'=>$skill_id,'project_skill_status'=>1),TRUE);
									}
								}
							}
						}
						$project_settings=array(
						'project_id'=>$project_id,
						'is_visible_anyone'=>NULL,
						'is_visible_private'=>NULL,
						'is_visible_invite'=>NULL,
						'is_hourly'=>NULL,
						'is_fixed'=>NULL,
						'budget'=>NULL,
						'experience_level'=>NULL,
						'hourly_duration'=>NULL,
						'hourly_time_required'=>NULL,
						'project_type_code'=>NULL,
						);

						if(post('projectVisibility') && post('projectVisibility')=='public'){
							$project_settings['is_visible_anyone']=1;
						}elseif(post('projectVisibility') && post('projectVisibility')=='private'){
							$project_settings['is_visible_private']=1;
						}elseif(post('projectVisibility') && post('projectVisibility')=='invite'){
							$project_settings['is_visible_invite']=1;
						}
						if(post('projectPaymentType') && post('projectPaymentType')=='hourly'){
							$project_settings['is_hourly']=1;
							if(post('hourly_duration')){
								$project_settings['hourly_duration']=post('hourly_duration');
							}
							if(post('hourly_duration_time')){
								$project_settings['hourly_time_required']=post('hourly_duration_time');
							}
						}
						if(post('projectPaymentType') && post('projectPaymentType')=='fixed'){
							$project_settings['is_fixed']=1;
							$project_settings['budget']=post('fixed_budget');
							
						}
						if(post('experience_level')){
							$project_settings['experience_level']=post('experience_level');
						}
						if(post('experience_level')){
							$project_settings['experience_level']=post('experience_level');
						}
						if(post('projectType')){
							$project_settings['project_type_code']=post('projectType');
						}
						if($is_edited==1){
							unset($project_settings['project_id']);
							updateTable('project_settings',$project_settings,array('project_id'=>$project_id));
						}else{
							insert_record('project_settings',$project_settings);	
						}
						
						
						$data_parse = array(
							'TITLE' => post('title')
						);
						if($is_edited==1){
							
						}else{
						$this->admin_notification_model->parse('admin-ad-post', $data_parse, 'proposal/list_record?ID='.$project_id);
						SendMail(get_setting('admin_email'),'admin-ad-post',$data_parse);
						}
						$msg['status'] = 'OK';
						$msg['preview_data'] = 'project';
						$msg['project_id'] =$project_id;
						
						
					//print_r($project);
					//print_r($project_owner);
					//print_r($project_category);	
					//print_r($project_additional);	
					//print_r($project_files);	
					//print_r($files);
					//print_r($question);
					//print_r($project_question);
					//print_r($project_settings);
						
					}else{
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = 'project';
						$msg['errors'][$i]['message'] = 'Invalid ';
						$i++;
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
	public function success(){
		if($this->access_member_type=='F'){
			redirect(get_link('dashboardURL'));
		}
		$this->layout->view('post-success', $this->data);
	}
	
}
