<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blog extends MX_Controller {
	
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
        $this->load->model('blog_model');
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

  

	public function index() {
		// $this->layout->set_meta('author', 'Venkatesh bishu');
			$this->layout->set_meta('keywords', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
			$this->layout->set_meta('description', 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.');
		$this->layout->view('index',$this->data);
	}
	
	
}
