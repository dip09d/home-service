<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Job extends MX_Controller {
	
	private $data;
	
    public function __construct() {
    	$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}
		parent::__construct();
        $this->load->model('job_model');
		$curr_class = $this->router->fetch_class();
		$curr_method = $this->router->fetch_method();
		
		$this->data['curr_class'] = $curr_class;
		$this->data['curr_method'] = $curr_method;
		$this->layout->set_js(array(
			'bootbox_custom.js',
		));
		/**
		 * Setting default css and js
		 */
		/* $this->layout->set_css(array(
			'bootstrap.css',
			
		));
 */

		/* $this->layout->set_js(array(
			'jquery-3.3.1.min.js',
			'jquery-migrate-3.0.0.min.js',
			'popper.js',
			'bootstrap.min.js',
			'mmenu.min.js',
			'tippy.all.min.js',
			'simplebar.min.js',
			'bootstrap-slider.min.js',
			'bootstrap-select.min.js',
			'snackbar.js',
			'clipboard.min.js',
			'counterup.min.js',
			'magnific-popup.min.js',
			'slick.min.js',
			'custom.js',
		)); */
		
    }

  
	public function findjobs() {
		$this->data['category'] = $this->job_model->get_all_category();
		$this->data['experience_level'] = $this->job_model->get_experience_level();
		$this->data['searchdata']=get();
		$category_id = get('category');
		if($category_id > 0){
			$this->data['sub_category'] = $this->job_model->get_sub_category($category_id);
		}

		$this->layout->set_meta('author', 'Venkatesh bishu');
		$this->layout->set_meta('keywords', 'Nurse, Driver, Chef, Aaya, Physiotharapy');
		$this->layout->set_meta('description', 'Freelancer Clone Script');
		$this->layout->view('findjobs',$this->data);
	}
	public function services() {
		
		$this->load->model('job/job_model');
		$this->data['popular_category'] =  $this->job_model->get_all_category(true);
		$this->layout->set_meta('author', 'Venkatesh bishu');
		$this->layout->set_meta('keywords', 'Nurse, Driver, Chef, Aaya, Physiotharapy');
		$this->layout->set_meta('description', 'Freelancer Clone Script');
		$this->layout->view('services',$this->data);
	}
	public function services_details() {
		$this->load->model('job/job_model');
		$this->data['popular_category'] =  $this->job_model->get_all_category(true);
		$this->layout->set_meta('author', 'Venkatesh bishu');
		$this->layout->set_meta('keywords', 'Nurse, Driver, Chef, Aaya, Physiotharapy');
		$this->layout->set_meta('description', 'Freelancer Clone Script');
		$this->layout->view('services-details',$this->data);
	}
	public function get_sub_category(){
		$category_id = get('category_id');
		$category = array();
		if($category_id > 0){
			$category = $this->job_model->get_sub_category($category_id);
		}
		
		$this->api->set_data('category', $category);
		$this->api->out();
	}
	
	public function job_list_ajax(){
		$data=array();
		$get = get();

		$limit = !empty($get['per_page']) ? $get['per_page'] : 0;
		$offset = 10;
		$next_limit = $limit + $offset;
		$login_user_id=0;
		if($this->loggedUser){
			$login_user_id=$this->member_id;
		}
		$data['login_user_id']=$login_user_id;
		$data['job_list'] =$this->job_model->getJobList($get,$limit, $offset);
		$data['job_list_count'] =$this->job_model->getJobList($get,'','', FALSE);
		
		$json['job_list'] = $data['job_list'];
		$json['job_list_count'] = $data['job_list_count'];
		
		if($data['job_list_count'] > $next_limit){
			unset($get['per_page']);
			
			if($get){
				$json['next'] = base_url('job/job_list_ajax?per_page='.$next_limit.'&'.http_build_query($get));
			}else{
				$json['next'] = base_url('job/job_list_ajax?per_page='.$next_limit);
			}
			
		}else{
			$json['next'] = null;
		}
		$json['html'] = $this->layout->view('job-list-ajax',$data, TRUE, TRUE);
		$json['status'] = 1;		
		echo json_encode($json);
	}
	public function action_favorite(){
		checkrequestajax();
		if($this->loggedUser){
			$cmd='';
			$project_id_md5=post('pid');
			if($project_id_md5){
				$project_id=getFieldData('project_id','project','md5(project_id)',$project_id_md5);
				if($project_id){
					$cnt=$this->db->where('project_id',$project_id)->where('member_id',$this->member_id)->from('favorite_project')->count_all_results();
					if($cnt){
						$this->db->where('project_id',$project_id)->where('member_id',$this->member_id)->delete('favorite_project');
						$cmd='remove';
					}else{
						$this->db->insert('favorite_project',array('project_id'=>$project_id,'member_id'=>$this->member_id,'reg_date'=>date('Y-m-d H:i:s')));
						$cmd='add';
					}
					
				}
			}
			$json['status']='OK';
			$json['cmd']=$cmd;
		}else{
			$json['status']='FAIL';
			$json['popup']='login';
		}
		echo json_encode($json);
	}
	public function action_report_form(){
		checkrequestajax();
		$data = $this->input->get();
		$this->layout->view('ajax-report-form',$data, TRUE);
	}
	public function action_report(){
	if($this->loggedUser){
		$this->load->library('form_validation');
		
		$json['status'] = 0;
		$project_id_md5 = post('project_id');
		$project_id=getFieldData('project_id','project','md5(project_id)',$project_id_md5);
		$member_id = $this->member_id;
		$action = post('cmd');
		
		if($project_id && $member_id){
			
			$data = array(
				'content_id' => $project_id,
				'reporter_id' => $member_id,
				'content_type' => 'project',
			);
			
			if($action == 'add'){
				
				$this->form_validation->set_rules('reason', 'reason', 'required');
				$this->form_validation->set_rules('additional_information', 'additional information', 'required');
				$this->form_validation->set_rules('project_id', 'project', 'required');
			
				if($this->form_validation->run()){
					
					
					$data['reason'] = htmlentities(post('reason'));
					$data['additional_information'] = htmlentities(post('additional_information'));
					$this->db->insert('reports', $data);
					
					$template = 'admin-reported-project';
					$ad_title =  getFieldData('project_title', 'project', 'project_id', $project_id); 
					$admin_report_url = ADMIN_URL.'reports/project';
					$data_parse = array(
						'TITLE' => $ad_title,
						'URL' => $admin_report_url, 
					);
					$this->admin_notification_model->parse($template, $data_parse, 'reports/project');
					SendMail(get_setting('admin_email'),$template,$data_parse);
					
					$json['status'] = 1;
				}else{
					$json['error_html'] = validation_errors('<div class="rerror">', '</div>');
					$json['status'] = 0;
				}
			
				
			}else if($action == 'remove'){
				$this->db->where($data)->delete('reports');
				$json['status'] = 1;
			}
		
		}
	}else{
		$json['status']='FAIL';
		$json['popup']='login';
	}
		
		echo json_encode($json);
		
	}

	
	
}
