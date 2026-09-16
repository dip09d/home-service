<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('document_model', 'member_company');
		$this->data['table'] = 'member_document_application';
		$this->data['primary_key'] = 'document_id';
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
		$this->data['main_title'] = 'Document Verification Management';
		$this->data['second_title'] = 'Document Verification List';
		$this->data['title'] = 'Document Verification';
		$breadcrumb = array(
			array(
				'name' => 'Document Verification',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] =$this->member_company->getList($srch, $limit, $offset);
		$this->data['list_total'] =$this->member_company->getList($srch, $limit, $offset, FALSE);
		
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
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Company';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] =$this->member_company->getDetail($id);
			$this->data['title'] = 'Edit Company';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert =$this->member_company->addRecord($post);
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
			$this->form_validation->set_rules('company_name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('company_trade_license', 'license', 'required|trim');
			$this->form_validation->set_rules('company_size', 'license', 'required|trim');
			$this->form_validation->set_rules('company_description', 'license', 'required|trim');
			$this->form_validation->set_rules('company_contact_name', 'license', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('company_phone', 'license', 'required|trim');
			$this->form_validation->set_rules('company_address', 'address', 'required|trim|max_length[255]');
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update =$this->member_company->updateRecord($post, $ID);
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
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('document_status' => $sts));
			}else{
				$upd['data'] = array('document_status' => $sts);
				if($sts==2){
					$upd['data']['reject_reason'] = $note;	
				}
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
				$member_id=getField('member_id',$this->data['table'],$this->data['primary_key'],$ID);
				$RECEIVER_EMAIL=getField('member_email','member','member_id',$member_id);
				$data_parse=array(
				'MEMBER_NAME'=>getField('member_name','member','member_id',$member_id),
				'REASON'=>$note,
				);
				
				if($sts==1){
					$upd=array();
					$upd['data'] = array('is_doc_verified' => $sts);
					$upd['where'] = array('member_id' => $member_id);
					$upd['table'] = 'member';
					update($upd);
					$template='document-verification-approved-by-admin';
					SendMail($RECEIVER_EMAIL,$template,$data_parse);
				}elseif($sts==2){
					$upd=array();
					$upd['data'] = array('is_doc_verified' => 0);
					$upd['where'] = array('member_id' => $member_id);
					$upd['table'] = 'member';
					update($upd);
					$template='document-verification-declined-by-admin';
					SendMail($RECEIVER_EMAIL,$template,$data_parse);
				}
				
			}
			$this->api->cmd('reload');
			/*if($action_type == 'multiple'){
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
			}*/
			
			
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
			$this->member_company->deleteRecord($id);
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
	
	public function upload_file($type=''){
		if($_FILES && $this->input->is_ajax_request()){
			$upload_dir = LC_PATH.'company-logo/';
			
			if(!is_dir($upload_dir)){
				mkdir($upload_dir);
			}
			$config['upload_path']          = $upload_dir;
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['file_ext_tolower']        = TRUE;
			$config['encrypt_name']        = TRUE;
			
			$this->load->library('upload', $config);
			
			if(! $this->upload->do_upload('file')){
				
				$this->api->set_error('upload_error', $this->upload->display_errors());
				
			}else{
				
				$this->api->data('upload_data', $this->upload->data());
				
				$this->api->data('file_url', UPLOAD_HTTP_PATH.'company-logo/'.$this->upload->data('file_name'));
				
			}
			

		}
		
		$this->api->out();
	}
	
}





