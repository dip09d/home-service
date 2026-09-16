<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MX_Controller {
	
	private $data;
	
    public function __construct() {
		parent::__construct();
        $this->load->model('home_model');
		$curr_class = $this->router->fetch_class();
		$curr_method = $this->router->fetch_method();
		
		$this->data['curr_class'] = $curr_class;
		$this->data['curr_method'] = $curr_method;
		
		/**
		 * Setting default css and js
		 */
		$this->layout->set_css(array(
			'home.css','owl.carousel.css', 'owl.theme.default.css'			
		));


		$this->layout->set_js(array(
			'jquery-3.3.1.min.js',
			'jquery-migrate-3.0.0.min.js',
			'popper.js',
			'bootstrap.min.js',
			'owl.carousel.js',
			'owl.navigation.js',
			'mmenu.min.js',
			'tippy.all.min.js',
			'simplebar.min.js',
			'bootstrap-slider.min.js',
			'bootstrap-select.min.js',
			'snackbar.js',
			'clipboard.min.js',
			'counterup.min.js',
			'magnific-popup.min.js',
			'custom.js',
		));
		
    }

    public function index() {
		// $this->layout->set_meta('author', 'Venkatesh bishu');
		$this->layout->set_meta('keywords', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->set_meta('description', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');

		
		$this->load->model('job/job_model');
		$get = array('order_by'=>'rating');
		$limit = 0;
		$offset = 10;
		$this->data['popular_category'] =  $this->job_model->get_all_category(true);
		
		$this->load->model('skill_model');
		$limit = 0;
		$offset = 32;
		
		$page="how-it-works";
		$this->load->model('cms/cms_model');
		$this->data['cms_temp']=$this->cms_model->getTempContent($page);
		$this->data['testimonial']=$this->home_model->getTestimonial();
		$this->data['partner']=$this->home_model->getPartner();
		$this->data['slider']=$this->home_model->getSldier();
		$this->data['help'] = get_results(array('select' => 'b.*,a.slug', 'from' => 'cms_help_article a', 'join' => array(array('cms_help_article_names b', 'a.cms_help_article_id=b.cms_help_article_id', 'LEFT')), 'offset' => '4', 'where' => array('b.lang' => get_active_lang(), 'a.status' => 1), 'order_by' => array('a.cms_help_article_id', 'ASC')));
		$this->data['blog']=get_results(array('select' => 'b.blog_id,b.blog_slug,b.blog_reg_date,b.blog_thumb,bn.blog_title,bn.blog_short_description', 'from' => 'blog b', 'join' => array(array('blog_names bn', 'b.blog_id=bn.blog_id', 'LEFT')), 'offset' => '4', 'where' => array('bn.blog_lang' => get_active_lang(), 'b.blog_status' => 1)));
		// echo '<pre>'; print_r($this->data['blog']); die;
		$this->layout->view('index',$this->data);
	}
	public function findjobs() {
		// $this->layout->set_meta('author', 'Venkatesh bishu');
		$this->layout->set_meta('keywords', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->set_meta('description', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->view('findjobs',$this->data);
	}
	public function findtalents() {
		// $this->layout->set_meta('author', 'Venkatesh bishu');
		$this->layout->set_meta('keywords', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->set_meta('description', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->view('findtalents',$this->data);
	}
	public function enterprise() {
		$this->layout->set_title('Enterprise');
		$this->layout->set_meta('author', 'Dev Sharma');
		$this->layout->set_meta('keywords', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->set_meta('description', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->view('enterprise',$this->data);
	}
	public function membership() {
		$this->layout->set_title('Membership');
		// $this->layout->set_meta('author', 'Dev Sharma');
		$this->layout->set_meta('keywords', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->set_meta('description', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->view('membership',$this->data);
	}
	public function setlanguage(){
		$msg=array();
		$i=0;
		$default_lang=get_setting('default_lang');
		$msg['status']='OK';
		$previous_lang=post('preflang');
		$current_lang=post('newlang');
		$refeffer=$this->input->post('currentlink').'/';
		$replace=$previous_lang.'/';
		$new_location_slug=$current_lang.'/';
		if($default_lang==$current_lang){
			$new_location_slug='';
		}
		$refeffer=rtrim(str_replace($replace,'',$refeffer),'/');
		$msg['refeffer']=SITE_URL.$new_location_slug.$refeffer;				
		echo json_encode($msg);
	}
	
}
