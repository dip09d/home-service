<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proposal extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('proposal_model', 'proposal');
		$this->data['table'] = 'project';
		$this->data['primary_key'] = 'project_id';
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	
	public function list_record(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Project Management';
		$this->data['second_title'] = 'All Project List';
		$this->data['title'] = 'Project';
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->proposal->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->proposal->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		
		/* search parameter */
		$this->load->model('category/category_model');
		$this->data['category'] = $this->category_model->get_all_category();
		$this->layout->view('list', $this->data);
       
	}
	
	public function view_edit($module='', $project_id=''){
		
		$this->data['module'] = $module;
		$this->data['project_id'] = $project_id;
		if($module == 'basic_info'){
			$this->_project_basic_info($project_id);
		}else if($module == 'title'){
			$this->_project_title($project_id);
		}else if($module == 'basic_detail'){
			$this->_project_basic_detail($project_id);
		}else if($module == 'files'){
			$this->_project_files($project_id);
		}else if($module == 'contact'){
			$this->_project_contact($project_id);
		}else if($module == 'location'){
			$this->_project_location($project_id);
		}else if($module == 'settings'){
			$this->_project_settings($project_id);
		}else if($module == 'skills'){
			$this->_project_skills($project_id);
		}else if($module == 'visiblity'){
			$this->_project_visiblity($project_id);
		}else if($module == 'budget'){
			$this->_project_budget($project_id);
		}
		/* get_print($this->data); */
		$this->layout->view('project-detail', $this->data);
       
	}
	
	private function _project_title($project_id=''){
		$this->load->model('category/category_model');
		$this->load->model('sub_category/sub_category_model');
		
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Title';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-title';
		
		$this->data['category'] = $this->category_model->get_all_category();
		
		$this->sub_category_model->configure(array(
			'table' => 'category_subchild',
			'lang_table' => 'category_subchild_names',
			'primary_key' => 'category_subchild_id',
		));
		
		$srch = array();
		if(!empty($this->data['detail']['category']['category'])){
			$srch['category'] = $this->data['detail']['category']['category']['id'];
		}else{
			$srch['category'] = 'abcd';
		}
		
		$this->data['sub_category'] = $this->sub_category_model->getList($srch, 0, 300);
		
		
	}
	
	private function _project_basic_detail($project_id=''){
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Details';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-basic-detail';
	}
	
	private function _project_visiblity($project_id=''){
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Visiblity';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-visiblity';
	}
	
	
	private function _project_skills($project_id=''){
		$this->load->model('skills/skill_model');
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Skills';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-skills';
		
		/* attributes */
		$this->data['all_skills']= $this->skill_model->getAllSkill();
	}
	
	public function get_category(){
		$category_id = get('id');
		$table = get('type');
		
		if($table == 'category_subchild'){
			
			$this->load->model('sub_category/sub_category_model');
			$this->sub_category_model->configure(array(
				'table' => 'category_subchild',
				'lang_table' => 'category_subchild_names',
				'primary_key' => 'category_subchild_id',
			));
			
			$srch = array();
			if(!empty($category_id)){
				$srch['category'] = $category_id;
			}else{
				$srch['category'] = 'abcd';
			}
			
			$category = $this->sub_category_model->getList($srch, 0, 300);
			if(count($category) > 0){
				echo '<option value="">-Select-</option>';
				print_select_option($category, 'category_subchild_id', 'category_subchild_name');
			}else{
				echo 0;
			}
		
		
		}else if($table == 'category_subchild_level_3'){
			
			$this->load->model('sub_category_level_three/sub_category_level_three_model');
			
			$this->sub_category_level_three_model->configure(array(
				'table' => 'category_subchild_level_3',
				'lang_table' => 'category_subchild_level_3_names',
				'primary_key' => 'category_subchild_level_3_id',
			));
			
			$srch = array();
			if(!empty($category_id)){
				$srch['category'] = $category_id;
			}else{
				$srch['category'] = 'abcd';
			}
			
			$category = $this->sub_category_level_three_model->getList($srch, 0, 300);
			if(count($category) > 0){
				echo '<option value="">-Select-</option>';
				print_select_option($category, 'category_subchild_level_3_id', 'category_subchild_name');
			}else{
				echo 0;
			}
			
			
		}else if($table == 'category_subchild_level_4'){
			
			$this->load->model('sub_category_level_four/sub_category_level_four_model');
			
			$this->sub_category_level_four_model->configure(array(
				'table' => 'category_subchild_level_4',
				'lang_table' => 'category_subchild_level_4_names',
				'primary_key' => 'category_subchild_level_4_id',
			));
			
			$srch = array();
			if(!empty($category_id)){
				$srch['category'] = $category_id;
			}else{
				$srch['category'] = 'abcd';
			}
			
			$category = $this->sub_category_level_four_model->getList($srch, 0, 300);
			if(count($category) > 0){
				echo '<option value="">-Select-</option>';
				print_select_option($category, 'category_subchild_level_4_id', 'category_subchild_name');
			}else{
				echo 0;
			}
			
		}
	}
	
	
	private function _project_basic_info($project_id=''){
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Description';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-basic-info';
	}
	
	private function _project_settings($project_id=''){
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Project Settings';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-settings';
		
		/*Attributes */
		$this->load->model('experience_level/experience_level_model');
		$this->data['experience_level'] = $this->experience_level_model->allData();
	}
	
	private function _project_budget($project_id=''){
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Budget';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-budget';
		
		/*Attributes */
		$this->load->model('experience_level/experience_level_model');
		$this->data['experience_level'] = $this->experience_level_model->allData();
	}
	
	private function _project_files($project_id=''){
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Files';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-files';
	}
	
	private function _project_contact($project_id=''){
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Contact';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-contact';
	}
	
	private function _project_location($project_id=''){
		$this->data['main_title'] = 'Project ';
		$this->data['title'] = 'Contact';
		$this->data['detail'] = $this->proposal->getAllDetail($project_id);
		$this->data['second_title'] = '<b>'.$this->data['detail']['project_title'].'</b>';
		
		$breadcrumb = array(
			array(
				'name' => 'Project',
				'path' => base_url('proposal/list_record'),
			),
			array(
				'name' => $this->data['detail']['project_title'],
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['action'] = base_url('proposal/edit_project_info');
		$this->data['page'] = 'project-location';
	}
	
	public function edit_project_info(){
		if(post() && $this->input->is_ajax_request()){
			$page = post('page');
			
			if($page == 'project-basic-info'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('project_additional[project_description]', 'project description', 'required|trim');
				if($this->form_validation->run()){
					$project_id = post('ID');
					$project_additional = post('project_additional');
					$this->proposal->updateAdditionalInfo($project_additional, $project_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'project-title'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('project[project_title]', 'project title', 'required|trim|max_length[255]');
				$this->form_validation->set_rules('category_id', 'project title', 'required');
				$this->form_validation->set_rules('category_subchild_id', 'sub category', 'required');
				
				if($this->form_validation->run()){
					$project_id = post('ID');
					$basic_info = post('project');
					
					$project_category = array(
						'category_id' => post('category_id'),
						'category_subchild_id' => post('category_subchild_id'),
					);
					$this->proposal->updateBasicInfo($basic_info, $project_id);
					$this->proposal->updateProjectCategory($project_category, $project_id);
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
			}else if($page == 'project-basic-detail'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('project_type_code', 'project type', 'required');
				if($this->form_validation->run()){
					$project_id = post('ID');
					$basic_info = post('project');
					
					$project_setting = array(
						'project_type_code' => post('project_type_code'),
					);
					
					$project_additional = post('project_additional');
					
					$this->proposal->updateAdditionalInfo($project_additional, $project_id);
					$this->proposal->updateProjectSettings($project_setting, $project_id);
					
					if(post('question')){
						$projectquestion=post('question');
						$this->db->where('project_id', $project_id)->delete('project_question');
						foreach($projectquestion as $question){
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
								'category_subchild_id'=> post('category_subchild_id'),
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
					
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
			}else if($page == 'project-location'){
				$project_id = post('ID');
				
				$project_location = array(
					'location_locality' => post('locality'),
					'location_lat' => post('lat'),
					'location_long' => post('lng'),
				);
				
				$this->proposal->updateProjectLocation($project_location, $project_id);
				$this->api->cmd('reload');
				
			}else if($page == 'project-settings'){
				$project_id = post('ID');
				$post = post(); 
				$project_setting = array(
					'is_visible_anyone' => (!empty($post['visiblity']) && $post['visiblity'] == 'is_visible_anyone') ? 1 : 0,
					'is_visible_private' => (!empty($post['visiblity']) && $post['visiblity'] == 'is_visible_private') ? 1 : 0,
					'is_visible_invite' => (!empty($post['visiblity']) && $post['visiblity'] == 'is_visible_invite') ? 1 : 0,
					'is_hourly' => (!empty($post['payment_type']) && $post['payment_type'] == 'is_hourly') ? 1 : 0,
					'is_fixed' => (!empty($post['payment_type']) && $post['payment_type'] == 'is_fixed') ? 1 : 0,
					'budget' => post('budget'),
					'experience_level' => post('experience_level'),
					'hourly_duration' => post('hourly_duration'),
					'hourly_time_required' => post('hourly_time_required'),
					'project_type_code' => post('project_type_code'),
				);
				
				$this->proposal->updateProjectSettings($project_setting, $project_id);
				$this->api->cmd('reload');
				
			}else if($page == 'project-visiblity'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('visiblity', 'visiblity', 'required');
				$this->form_validation->set_rules('project_member_required', 'member required', 'required');
				if(post('project_member_required') == 'M'){
					$this->form_validation->set_rules('no_of_freelancer', 'no_of_freelancer', 'required|numeric|greater_than[1]');
				}
				if($this->form_validation->run()){
					$project_id = post('ID');
					$post = post(); 
					$project_setting = array(
						'is_visible_anyone' => (!empty($post['visiblity']) && $post['visiblity'] == 'is_visible_anyone') ? 1 : 0,
						'is_visible_private' => (!empty($post['visiblity']) && $post['visiblity'] == 'is_visible_private') ? 1 : 0,
						'is_visible_invite' => (!empty($post['visiblity']) && $post['visiblity'] == 'is_visible_invite') ? 1 : 0,
					);
					if(post('project_member_required') == 'M'){
						$member_required = post('no_of_freelancer');
					}else{
						$member_required = 1;
					}
					$project = array(
						'project_member_required' => $member_required,
					);
					$this->proposal->updateBasicInfo($project, $project_id);
					$this->proposal->updateProjectSettings($project_setting, $project_id);
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
				
			}else if($page == 'project-skills'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('skills', 'skills', 'required');
				
				if($this->form_validation->run()){
					$project_id = post('ID');
					
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
					$this->api->cmd('reload');
					
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
				
			}else if($page == 'project-budget'){
				$this->load->library('form_validation');
				$this->form_validation->set_rules('payment_type', 'payment_type', 'required');
				$this->form_validation->set_rules('experience_level', 'payment_type', 'required');
				if(post('payment_type') == 'is_hourly'){
					$this->form_validation->set_rules('hourly_duration', 'hourly_duration', 'required');
					$this->form_validation->set_rules('hourly_time_required', 'hourly_time_required', 'required');
				}else if(post('payment_type') == 'is_fixed'){
					$this->form_validation->set_rules('budget', 'budget', 'required|numeric|greater_than[0]');
				}
				
				
				
				if($this->form_validation->run()){
					$project_id = post('ID');
					$post = post(); 
					$project_setting = array(
						'is_hourly' => (!empty($post['payment_type']) && $post['payment_type'] == 'is_hourly') ? 1 : 0,
						'is_fixed' => (!empty($post['payment_type']) && $post['payment_type'] == 'is_fixed') ? 1 : 0,
						'budget' => post('budget'),
						'experience_level' => post('experience_level'),
						'hourly_duration' => post('hourly_duration'),
						'hourly_time_required' => post('hourly_time_required'),
					);
				
					$this->proposal->updateProjectSettings($project_setting, $project_id);
					$this->api->cmd('reload');
				}else{
					$errors = validation_errors_array();
					$this->api->set_error($errors);
				}
				
				
				
			}
			
			
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Test Three';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->proposal->getDetail($id);
			$this->data['title'] = 'Edit Test Three';
		}
		$this->load->view('ajax_page_global', $this->data);
	}
	
	public function get_project_component(){
		$this->load->helper('my_basic_helper');
		$category_id = get('category_id');
		$project_id = get('project_id');
		$child_level = get('child_level');
		$this->data['additional_fields'] = getAllAdditionalField($category_id, 'post', $child_level);
		$this->data['detail']['component'] = $this->proposal->getProjectComponent($project_id);
		$this->load->view('project-component', $this->data);
	}
	
	public function add(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->proposal->addRecord($post);
				if(post('add_more') && post('add_more') == '1'){
					$this->api->cmd('reset_form');
				}else{
					$this->api->cmd('reload');
				}
				
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function edit(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->proposal->updateRecord($post, $ID);
				$this->api->cmd('reload');
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function change_status(){
		if(post() && $this->input->is_ajax_request()){
			
			$ID = post('ID');
			$sts = post('status');
			$action_type = post('action_type');
			$note = post('note');
			
			if(is_array($ID)){
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('project_status' => $sts));
			}else{
				$upd['data'] = array('project_status' => $sts, 'note' => $note);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
				$member_id=getField('member_id','project_owner',$this->data['primary_key'],$ID);
				$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
				$data_parse=array(
				'SELLER_NAME'=>getField('member_name','member','member_id',$member_id),
				'PROPOSAL_URL'=>URL.'item/'.getField('project_url','project','project_id',$ID),
				);
				if($sts==PROJECT_ACTIVE){
					$template='proposal-approved-by-admin';
				}elseif($sts==PROJECT_DELETED){
					$template='proposal-deleted-by-admin';
				}elseif($sts==PROJECT_DECLINED){
					$template='proposal-declined-by-admin';
				}
				SendMail($RECEIVER_EMAIL,$template,$data_parse);
			}
			
			$this->api->cmd('reload');
			
			/* if($action_type == 'multiple'){
				$this->api->cmd('reload');
			}else{
				
				$html = '';
				if($sts == ACTIVE_STATUS){
					$html = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$ID.', this)"><span class="badge badge-success">Active</span></a>';
				}else{
					$html = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$ID.', this)"><span class="badge badge-danger">Inactive</span></a>';
				}
			
			
				$this->api->data('html', $html);
				$this->api->cmd('replace');
			} */
			
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function delete_record($id=''){
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		if($id){
			$this->test_three->deleteRecord($id);
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}else{
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
	
	public function referral(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Proposal Referral Management';
		$this->data['second_title'] = 'All Proposal Referral List';
		$this->data['title'] = 'Proposal Referral';
		$breadcrumb = array(
			array(
				'name' => 'Proposal Referral',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->proposal->getReferralList($srch, $limit, $offset);
		$this->data['list_total'] = $this->proposal->getReferralList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'referral');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('referral', $this->data);
       
	}
	
	public function delete_project_file(){
		if(post()){
			$file_id = post('file_id');
			$project_id = post('project_id');
			
			$del['table'] = 'project_files';
			$del['where'] = array('file_id' => $file_id, 'project_id' => $project_id);
			delete($del);
			
		}
		
		echo 1;
	}
	
	public function uploadattachment_direct(){
		$config['upload_path']  =UPLOAD_PATH.'projects-files/projects-requirement';
		$from=get('from');
		$config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|zip|rar|txt|ppt|pptx|bnp|svg';
        
        $config['max_size']             = 2048;
        $config['encrypt_name']            = TRUE;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('fileinput'))
        {
            $msg['status']='FAIL';
            $msg['error']= $this->upload->display_errors();
        }
        else
        {
			$project_id = post('project_id');
        	$msg['status']='OK';
        	$upload_data=$this->upload->data();
        	$msg['upload_response']=array('file_name'=>$upload_data['file_name'],'original_name'=>$upload_data['client_name']);
			
			$file_ext = str_replace('.', '', $upload_data['file_ext']);
			$file_array = array('server_name'=>$upload_data['file_name'],'original_name'=>$upload_data['client_name'], 'upload_time' => date('Y-m-d H:i:s'), 'file_ext' => $file_ext);
			$this->db->insert('files', $file_array);
			$file_id = $this->db->insert_id();
			$this->db->insert('project_files', array('project_id' => $project_id, 'file_id' => $file_id));
			
        }
		echo json_encode($msg);
	}
	
	public function uploadattachment(){
		
		$config['upload_path']          = TMP_UPLOAD_PATH;
		$from=get('from');
		if($from=='verifydocument'){
			$config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|bnp';
		}else{
			$config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|zip|rar|txt|ppt|pptx|bnp|svg';
		}
        
        
        $config['max_size']             = 2048;
        $config['encrypt_name']            = TRUE;
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
        	$msg['upload_response']=array('file_name'=>$upload_data['file_name'],'original_name'=>$upload_data['client_name'], 'file_url' => TMP_UPLOAD_HTTP_PATH.$upload_data['file_name']);
        }
		echo json_encode($msg);
		
	}
	
	public function post_project(){
		$srch = get();
		$this->data['member_id'] = get('member_id');
		$this->data['main_title'] = 'Project';
		$this->data['second_title'] = 'Project Post';
		$this->data['title'] = 'Project Post';
		$breadcrumb = array(
			array(
				'name' => 'Project Post',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
		if($this->data['member_id'] > 0){
			$this->data['all_category']=getAllCategory();
			$this->data['all_projectType']=getAllProjectType();
			$this->data['all_projectExperienceLevel']=getAllExperienceLevel();
			$this->data['all_projectDuration']=getAllProjectDuration();
			$this->data['all_projectDurationTime']=getAllProjectDurationTime();
			$this->data['all_skills']=getAllSkills();
			$this->layout->view('postproject/post-project', $this->data);
		}else{
			$this->data['title'] = 'Choose User';
			$this->layout->view('post-project-choose-user', $this->data);
		}
		
       
	}
	
	public function post_project_form_check($step=""){
		$this->load->library('form_validation');
		$this->load->model('admin_notification_global_model', 'admin_notification_model');
		$i=0;
		$msg=array();
		
		$member_id=post('member_id');
		$organization_id=getField('organization_id', 'organization', 'member_id', $member_id);
		if($member_id > 0){
			if($this->input->post()){
				$dataid=post('dataid');
				if($step=='1' || $step=='7')
				{
					$this->form_validation->set_rules('title', 'Title', 'required|trim');
					$this->form_validation->set_rules('category', 'Category', 'required|trim|is_natural_no_zero');
					$this->form_validation->set_rules('sub_category', 'Speciality', 'required|trim|is_natural_no_zero');
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
					$this->form_validation->set_rules('description', 'Description', 'required|trim');
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
					$this->form_validation->set_rules('projectType', 'Project Type', 'required|trim');
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
					$this->form_validation->set_rules('skills', 'Skills', 'required|trim');
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
					$this->form_validation->set_rules('projectVisibility', 'Project Visibility', 'required|trim|in_list[public,private,invite]');
					$this->form_validation->set_rules('member_required', 'member required', 'required|trim|in_list[S,M]');
					if(post('member_required') && post('member_required')=='M'){
						$this->form_validation->set_rules('no_of_freelancer', 'no of freelancer', 'required|trim|is_natural_no_zero');
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
					$this->form_validation->set_rules('projectPaymentType', 'Project pay type', 'required|trim|in_list[fixed,hourly]');
					$this->form_validation->set_rules('experience_level', 'Experience level required', 'required|trim');
					if(post('projectPaymentType') && post('projectPaymentType')=='fixed'){
						$this->form_validation->set_rules('fixed_budget', 'budget', 'required|trim|numeric');
					}
					if(post('projectPaymentType') && post('projectPaymentType')=='hourly'){
						$this->form_validation->set_rules('hourly_duration', 'Duration', 'required|trim');
						$this->form_validation->set_rules('hourly_duration_time', 'Duration time', 'required|trim');
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
					
					$project_id=insert_record('project',$project,TRUE);
					if($project_id){
						$project_owner=array(
						'project_id'=>$project_id,
						'member_id'=>$member_id,
						'organization_id'=>$organization_id,
						);
						insert_record('project_owner',$project_owner);
						
						$project_category=array(
						'project_id'=>$project_id,
						'category_id'=>post('category'),
						'category_subchild_id'=>post('sub_category'),
						);
						insert_record('project_category',$project_category);
						
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
						insert_record('project_additional',$project_additional);
						
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
						if(post('question')){
							$this->db->where('project_id', $project_id)->delete('project_question');
							$projectquestion=post('question');
							foreach($projectquestion as $question){
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
						insert_record('project_settings',$project_settings);
						
						
						$data_parse = array(
							'TITLE' => post('title')
						);
						$this->admin_notification_model->parse('admin-ad-post', $data_parse, 'proposal/list_record?ID='.$project_id);
						SendMail(get_setting('admin_email'),'admin-ad-post',$data_parse);
						
						$msg['status'] = 'OK';
						$msg['preview_data'] = 'project';
						$msg['project_id'] =$project_id;
						
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
	
	public function success(){
		$this->layout->view('postproject/post-success', $this->data);
	}
	
	public function search_employer(){
		$this->load->model('member/member_model');
		$term = get('email');
		$srch = array(
			'u_type' => 'employer',
			'term' => $term
		);
		$total = $this->member_model->getList($srch, '', '', FALSE);
		$result = $this->member_model->getList($srch, 0, $total);
		
		if($result && $term){
			foreach($result as $k => $v){
				$logo = getMemberLogo($v['member_id']);
				echo '<a href="'.base_url('proposal/post_project?member_id='.$v['member_id']).'" class="list-group-item list-group-item-action">
						<div class="float-left"><img src="'.$logo.'" width="60"/></div>
						<div>'.$v['member_name'].'</div>
						<div>'.$v['member_email'].'</div>
					  </a>';
			}
		}else{
			echo '<a href="#" class="list-group-item list-group-item-action">No results</a>';
		}
	}
	
}





