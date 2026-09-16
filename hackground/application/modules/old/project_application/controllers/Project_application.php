<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_application extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('project_application_model', 'project_application');
		$this->data['table'] = 'project_bids';
		$this->data['primary_key'] = 'bid_id';
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
		$this->data['main_title'] = 'Project Application';
		$this->data['second_title'] = 'All Application List';
		$this->data['title'] = 'Applications';
		$breadcrumb = array(
			array(
				'name' => 'Application',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->project_application->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->project_application->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = 'add';
		$this->data['edit_command'] = 'edit';
		$this->layout->view('list', $this->data);
       
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
			$this->data['detail'] = $this->project_application->getDetail($id);
			$this->data['title'] = 'Edit Test Three';
		}
		$this->load->view('ajax_page_global', $this->data);
	}
	
	public function add(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->project_application->addRecord($post);
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
				$update = $this->project_application->updateRecord($post, $ID);
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
			
			if(is_array($ID)){
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('status' => $sts));
			}else{
				$upd['data'] = array('status' => $sts);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
			}
			
			if($action_type == 'multiple'){
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
			}
			
			
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
			$this->project_application->deleteRecord($id);
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
	
	public function application_detail($project_id='',$application_id=''){
		$this->data['main_title'] = 'Application Detail';
		$this->data['second_title'] = 'Application Detail';
		$this->data['title'] = 'Application Detail';
		$breadcrumb = array(
			array(
				'name' => 'Application',
				'path' => base_url('project_application/list_record'),
			),
			array(
				'name' => 'Application Detail',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
		/*Application logic */
		$this->data['projects']=getProjectDetails($project_id,array('project_owner','project_settings'));
		$this->data['projects']['project_id']=$project_id;
		$organization_id = $owner_organization_id=$this->data['projects']['project_owner']->organization_id;
		$member_id = $owner_member_id=$this->data['projects']['project_owner']->member_id;
		$bidder_id=getFieldData('member_id','project_bids','bid_id',$application_id);
		
		$this->data['proposaldetails']=getProposalDetails($application_id);
		$memberDataBasic=getData(array(
			'select'=>'m.member_name,m_b.member_heading,m_b.member_overview,m_b.member_hourly_rate,c_n.country_name,c.country_code_short,m_l.logo,m_b.available_per_week,m_b.not_available_until',
			'table'=>'member as m',
			'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left'),array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>'c.country_code=c_n.country_code','position'=>'left'),array('table'=>'member_logo as m_l','on'=>'(m.member_id=m_l.member_id and m_l.status=1)','position'=>'left'),),
			'where'=>array('m.member_id'=>$bidder_id,'c_n.country_lang'=>admin_default_lang()),
			'single_row'=>true,
		));
		$this->data['member_id']=$bidder_id;
		$this->data['memberInfo']=$memberDataBasic;
		$this->data['memberInfo']->badges=getData(array(
			'select'=>'b.icon_image,b_n.name,b_n.description',
			'table'=>'member_badges as m',
			'join'=>array(array('table'=>'badges as b','on'=>'m.badge_id=b.badge_id','position'=>'left'),array('table'=>'badges_names as b_n','on'=>"(b.badge_id=b_n.badge_id and b_n.lang='".admin_default_lang()."')",'position'=>'left')),
			'where'=>array('m.member_id'=>$bidder_id,'b.status'=>1),
			'order'=>array(array('b.display_order','asc')),
		));
		$this->data['memberInfo']->total_earned=0;
		$this->data['memberInfo']->total_jobs=0;
		
		$arr=array(
			'select'=>'q.question_id,q.question_title,a.question_answer',
			'table'=>'project_question as p_q',
			'join'=>array(
				array('table'=>'question as q','on'=>'p_q.question_id=q.question_id','position'=>'left'),
				array('table'=>'project_bid_answer as a','on'=>"(q.question_id=a.question_id and a.bid_id='".$application_id."')",'position'=>'left'),
			),
			'where'=>array('p_q.project_id'=>$project_id,'p_q.project_question_status'=>1),
			'order'=>array(array('p_q.project_question_id','asc'))
		);
		
		
		$this->data['proposaldetails']['project_question']=getData($arr);
		$this->layout->view('application-detail', $this->data);
	}
}





