<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Favorite extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
			$this->organization_id=$this->loggedUser['OID'];
		}else{
			$refer=uri_string();
			redirect(URL::get_link('loginURL').'?refer='.$refer);
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		$this->load->model('favorite_model');
		parent::__construct();
	}
	public function index()
	{
		$this->load->library('pagination');
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		if($this->loggedUser){
			$this->layout->set_js(array(
				'bootbox_custom.js',
				'mycustom.js',
			));
			/* $this->layout->set_css(array(
					'bootstrap-datetimepicker.css'
				)); */

			$srch = $this->input->get();
			$limit = !empty($srch['per_page']) ? $srch['per_page'] : 0;
			$offset = 10;
			$srch['member_id']=$this->member_id;

			if($this->access_member_type=='F'){
				$this->data['bookmark_type']='project';
				$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
				$this->data['list']=$this->favorite_model->getfavoriteProjects($srch,$limit,$offset);
				$this->data['list_total']=$this->favorite_model->getfavoriteProjects($srch, $limit, $offset, FALSE);
				
			}else{
				$this->data['bookmark_type']='freelancer';
				$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
				$this->data['list']=$this->favorite_model->getfavoriteFreelancers($srch,$limit,$offset);
				$this->data['list_total']=$this->favorite_model->getfavoriteFreelancers($srch, $limit, $offset, FALSE);
			}

			/*Pagination Start*/
			$config['base_url'] = base_url('favorite/index');
			$config['page_query_string'] = TRUE;
			$config['reuse_query_string'] = TRUE;
			$config['total_rows'] = $this->data['list_total'];
			$config['per_page'] = $offset;

			$config['full_tag_open'] = '<div class="pagination-container"><nav class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></nav></div>';
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<li class="waves-effect">';
			$config['first_tag_close'] = '</li>';
			$config['num_tag_open'] = '<li class="waves-effect">';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li><a class='current-page' href='javascript:void(0)'>";
			$config['cur_tag_close'] = '</a></li>';
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = "<li class='last waves-effect'>";
			$config['last_tag_close'] = '</li>';
			$config['next_link'] = '<i class="icon-material-outline-keyboard-arrow-right"></i>';
			$config['next_tag_open'] = '<li class="waves-effect">';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = '<i class="icon-material-outline-keyboard-arrow-left"></i>';
			$config['prev_tag_open'] = '<li class="waves-effect">';
			$config['prev_tag_close'] = '</li>';  

			$this->pagination->initialize($config);
			$this->data['links'] = $this->pagination->create_links();



			
			$this->data['show']=$show;
			$this->layout->view('favorite-'.$this->data['bookmark_type'], $this->data);
		}
	}
	
	
}
