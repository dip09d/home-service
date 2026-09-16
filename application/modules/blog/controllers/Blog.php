<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->lang = get_active_lang();
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		$this->load->model('blog_model');
		parent::__construct();
	}
	public function index()
	{
		$this->load->library('pagination');
		$show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
	
		$this->layout->set_js(array(
			'owl.carousel.js',
			'owl.navigation.js',
			'bootbox_custom.js',
			'mycustom.js',
		));
		$this->layout->set_css(array(
			'owl.carousel.css',
			'owl.theme.default.css'
		));

		$srch = $this->input->get();
		$limit = !empty($srch['per_page']) ? $srch['per_page'] : 0;
		$offset = 20;


		$this->data['list']=$this->blog_model->getList($srch,$limit,$offset);
		if($this->data['list']){
			foreach($this->data['list'] as $k=>$list){
				$this->data['list'][$k]->tags=$this->blog_model->getBlogTags($list->blog_id,1);
			}
		}
		$this->data['list_total']=$this->blog_model->getList($srch, $limit, $offset, FALSE);
		$srch=array('is_tranding'=>1);
		$this->data['tranding']=$this->blog_model->getList($srch,0,3);
		$this->data['popular_tags']=$this->blog_model->getPolularTags(10);
		$srch=array('is_featured'=>1);
		$this->data['featured_blog']=$this->blog_model->getList($srch,0,6);
		if($this->data['featured_blog']){
			foreach($this->data['featured_blog'] as $k=>$list){
				$this->data['featured_blog'][$k]->tags=$this->blog_model->getBlogTags($list->blog_id,1);
			}
		}
		/*Pagination Start*/
		$config['base_url'] = get_link('blogListURL');
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['total_rows'] = $this->data['list_total'];
		$config['per_page'] = $offset;

		$config['full_tag_open'] = '<div class="pagination-container margin-top-60 margin-bottom-60"><nav class="pagination"><ul>';
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
		$this->layout->set_title('Insights | Regulatory Risks');
		$this->layout->set_meta('keywords','blog');
		$this->layout->set_meta('description','Recent Insights, Research and Thought Leadership on the Important Issues in the Regulatory Compliance, ESG, and Risk Management Industry impacting your business.');
		$this->data['show']=$show;
		$this->layout->view('list', $this->data);		
	}

	public function details($blog_slug=''){
		$this->db->select('b.blog_id,b.blog_slug,b.blog_reg_date,b.blog_views,b_n.blog_title,b_n.blog_description,b_n.meta_title,b_n.meta_keys,b_n.meta_description');		 
		$this->db->from('blog as b');
		$this->db->join("blog_names as b_n", "(b.blog_id=b_n.blog_id and b_n.blog_lang='".$this->lang."')", "LEFT");
		$this->data['details']=$this->db->where('b.blog_slug',$blog_slug)->where('b.blog_status',1)->get()->row();
		if($this->data['details']){
			$blog_id=$this->data['details']->blog_id;
			$this->data['details']->images=$this->db->select('blog_image')->where('blog_id',$blog_id)->from('blog_images')->get()->row();
			//$this->data['details']->category=$this->db->select('blog_image')->where('blog_id',$blog_id)->from('blog_images')->get()->row();
			$this->data['details']->tags=$this->blog_model->getBlogTags($blog_id);

			if($this->session->userdata('blog_views-'.$blog_id)){
			
			}else{
				$this->db->where('blog_id',$blog_id)->set('blog_views','`blog_views`+1',false)->update('blog');
				$this->session->set_userdata('blog_views-'.$blog_id,TRUE);
			}
			
			$srch=array('is_tranding'=>1);
			$this->data['tranding']=$this->blog_model->getList($srch,0,3);
			$this->data['popular_tags']=$this->blog_model->getPolularTags(10);
			$srch=array('is_previous_blog'=>$blog_id);
			$this->data['previous_blog']=$this->blog_model->getList($srch,0,1);
			$srch=array('is_next_blog'=>$blog_id);
			$this->data['next_blog']=$this->blog_model->getList($srch,0,1);
			
			$this->layout->set_title($this->data['details']->meta_title);
			$this->layout->set_meta('keywords',$this->data['details']->meta_keys);
			$this->layout->set_meta('description',$this->data['details']->meta_description);

			$this->layout->view('details', $this->data);
		}else{
			show_404();
		}
	}
	
}
