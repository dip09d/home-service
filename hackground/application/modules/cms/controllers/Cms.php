<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		
		$this->load->model('cms_model', 'cms');
		$this->data['table'] = 'content';
		$this->data['lang_table'] = 'content_names';
		$this->data['primary_key'] = 'content_id';
		
		
		$model_configuration = array(
			'table' => $this->data['table'],
			'lang_table' => $this->data['lang_table'],
			'primary_key' => $this->data['primary_key'],
		);
		
		$this->cms->configure($model_configuration);
		
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
		$this->data['main_title'] = 'Content Management';
		$this->data['second_title'] = 'All Content List';
		$this->data['title'] = 'Content';
		$breadcrumb = array(
			array(
				'name' => 'Content',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->cms->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->cms->getList($srch, $limit, $offset, FALSE);
		
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
		$this->data['add_btn'] = 'Add Content';
		$this->layout->view('list', $this->data);
       
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Content';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->cms->getDetail($id);
			$this->data['title'] = 'Edit Content';
		}
		$this->load->view('ajax_page', $this->data);
	}
	
	public function add(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[content]['.$v.']', "content $v", 'required|trim');
				$this->form_validation->set_rules('lang[title]['.$v.']', "title $v", 'required|trim|max_length[100]');
				$this->form_validation->set_rules('lang[meta_title]['.$v.']', "meta title $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_keys]['.$v.']', "meta keys $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_description]['.$v.']', "meta description $v", 'required|trim');
			}
			
			$this->form_validation->set_rules('content_slug', 'content slug', 'required|regex_match[/^[a-z\-A-Z]+$/]|is_unique[content.content_slug]');
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->cms->addRecord($post);
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
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[content]['.$v.']', "content $v", 'required|trim');
				$this->form_validation->set_rules('lang[title]['.$v.']', "title $v", 'required|trim|max_length[100]');
				$this->form_validation->set_rules('lang[meta_title]['.$v.']', "meta title $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_keys]['.$v.']', "meta keys $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_description]['.$v.']', "meta description $v", 'required|trim');
			}
			
			$this->form_validation->set_rules('content_slug', 'content slug', 'required|regex_match[/^[a-z\-A-Z]+$/]');
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->cms->updateRecord($post, $ID);
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
			$this->cms->deleteRecord($id);
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
	public function addedit($id=''){
		$this->data['main_title'] = 'Cms';
		if($id){
			$this->data['ID']= $id;
			$this->data['detail'] = $this->cms->getDetail($id);
			$this->data['detail']['cms_temp']=$this->cms->getTempContent($this->data['detail']['content_slug']);
			$this->data['second_title'] = 'Edit Content';
			$this->data['title'] = 'Edit Content';
			$breadcrumb = array(
				array(
					'name' => 'Cms',
					'path' => base_url($this->data['curr_controller'].'list_record'),
				),
				array(
					'name' => 'edit Content',
					'path' => '',
				),
			);
		}else{
			$this->data['second_title'] = 'Add Content';
			$this->data['title'] = 'Add Content';
			$breadcrumb = array(
				array(
					'name' => 'Cms',
					'path' => base_url($this->data['curr_controller'].'list_record'),
				),
				array(
					'name' => 'Add Content',
					'path' => '',
				),
			);
		}

		$this->data['form_action'] = base_url($this->data['curr_controller'].'save');
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		
		if($id){
		$this->layout->view('edit-cms', $this->data);
		}else{
			$this->layout->view('add-cms', $this->data);
		}
       
	}
	public function save(){
		$lang = get_lang();
		if(post() && $this->input->is_ajax_request()){
			$ID = post('ID');
			$this->load->library('form_validation');
			foreach($lang as $k => $v){
				$this->form_validation->set_rules('lang[content]['.$v.']', "content $v", 'required|trim');
				$this->form_validation->set_rules('lang[title]['.$v.']', "title $v", 'required|trim|max_length[100]');
				$this->form_validation->set_rules('lang[meta_title]['.$v.']', "meta title $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_keys]['.$v.']', "meta keys $v", 'required|trim');
				$this->form_validation->set_rules('lang[meta_description]['.$v.']', "meta description $v", 'required|trim');
			}
			if($ID>0){
				$this->form_validation->set_rules('content_slug', 'content slug', 'required|regex_match[/^[a-z\-A-Z]+$/]');
			}else{
				$this->form_validation->set_rules('content_slug', 'content slug', 'required|regex_match[/^[a-z\-A-Z]+$/]|is_unique[content.content_slug]');
			}
			$this->form_validation->set_rules('status', 'status', '');
			//$this->form_validation->set_rules('cms_key[]', 'cms_key', 'required');


				

		$cms_page=$this->input->post('content_slug');
		$cms_key=$this->input->post('cms_key');
		$cms_temp=$cms_temp_part=array();
		if($cms_key){
			$i=0;
			foreach($cms_key as $cms_key_id){
				$this->form_validation->set_rules('block_type_'.$cms_key_id, 'type', 'required');
				$p=0;
				$i++;
				$cms_temp_part=array();
				$cms_key=time().'_'.$i;
				$block_type=post('block_type_'.$cms_key_id);
				$block_class=post('block_type_section_area_class_'.$cms_key_id);
				$block_child_class=post('block_type_section_area_sub_class_'.$cms_key_id);
				if($block_type){
					
				if($block_type=='section'){
					$section_type="SEC";
					$child_block=post('child_key_p_'.$cms_key_id);
					if($child_block){
						foreach($child_block as $k=>$block_part){
							$p++;
							$child_part_class=$block_part['block_type_section_child_class'];
							$child_part_content=$block_part['block_type_section_child_area'];
							$cms_temp_part[]=array(
								'cms_key'=>$cms_key,
								'part_id'=>$p,
								'part_class'=>$child_part_class,
								'part_content'=>$child_part_content,
								'part_order'=>$p,
							);
						}
					}
				}elseif($block_type=='custom'){
					$p++;
					$section_type="CUS";
					$child_part_content=post('block_type_custom_area_'.$cms_key_id);
					$cms_temp_part[]=array(
						'cms_key'=>$cms_key,
						'part_id'=>$p,
						'part_class'=>NULL,
						'part_content'=>$child_part_content,
						'part_order'=>$p,
					);
				}
				$cms_temp_row=array(
					'cms_key'=>$cms_key,
					'cms_page'=>$cms_page,
					'section_type'=>$section_type,
					'cms_class'=>$block_class,
					'child_class'=>$block_child_class,
					'cms_order'=>$i,

				);
				$cms_temp_row['cms_temp_part']=$cms_temp_part;
				$cms_temp[]=$cms_temp_row;
				}
			}
		}
		if($this->form_validation->run()){
			$post = post();
			if($ID>0){
				$update = $this->cms->updateRecord($post, $ID);
			}else{
				$insert = $this->cms->addRecord($post);
			}
			
			
			unset($post['ID']);

			$previous=$this->db->select('cms_key')->where('cms_page',$cms_page)->from('cms_temp')->get()->result();
			if($previous){
				foreach($previous as $r=>$row){
					$this->db->where('cms_key' , $row->cms_key)->delete('cms_temp_part');
				}
			}
			$this->db->where('cms_page' ,  $cms_page)->delete('cms_temp');
			//print_r($cms_temp);
			if($cms_temp){
				foreach($cms_temp as $p=>$row){
					$cms_order=$p+1;
					$cms_temp_data=array(
						'cms_key'=>$row['cms_key'],
						'cms_page'=>$row['cms_page'],
						'section_type'=>$row['section_type'],
						'cms_class'=>$row['cms_class'],
						'child_class'=>$row['child_class'],
						'cms_order'=>$cms_order,	
					);
					$this->db->insert('cms_temp',$cms_temp_data);
					$cmspart=$row['cms_temp_part'];
					if($cmspart){
						foreach($cmspart as $cp=>$crow){
							$part_order=$cp+1;
							if($crow['part_content']){
								foreach($crow['part_content'] as $langk=>$content_value){
									$cms_temp_part_data=array(
										'cms_key'=>$crow['cms_key'],
										'part_id'=>$crow['part_id'],
										'part_class'=>$crow['part_class'],
										'part_content'=>$content_value,
										'part_order'=>$part_order,
										'lang'=>$langk,
									);
									$this->db->insert('cms_temp_part',$cms_temp_part_data);
								}
							}
						}
					}	
				}
			}
			$this->api->cmd('reload');
		}else{
			$errors = validation_errors_array();
			$this->api->set_error($errors);
		}
	}else{
		$this->api->set_error('invalid_request', 'Invalid Request');
	}
	//	print_r($cms_temp);
		//print_r($cms_temp_part);
		$this->api->out();
	}
}





