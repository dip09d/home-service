<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layout {
    private $ci;
    private $header;
    private $footer;
    private $scriptsrc;
    private $theme;
	private $title;
	private $meta;
    public function __construct()
    {
        $this->ci = &get_instance();
		$this->theme =  ADMIN_THEME ;
		$this->title = "";
		$this->meta = array();
        $this->header = $this->theme."/includes/header";
        $this->footer = $this->theme."/includes/footer";
        $this->scriptsrc = $this->theme."/includes/scriptsrc";
		$this->header_data = $this->_getHeader();
    }
    
    public function view($view = '' , $data = array() , $blank = false)
    {
        $curr_controller = $this->ci->router->fetch_class();
        $curr_method = $this->ci->router->fetch_method();
		$theme = THEME;
        if($blank)
        {
            if(!file_exists(APPPATH."modules/$curr_controller/views/$view".EXT))
            {
                $view = "$theme"."$curr_controller/$view";
            }
            $this->ci->load->view($view , $data);
        }else{
            if(!file_exists(APPPATH."modules/$curr_controller/views/$view".EXT))
            {
                $view = $theme."$curr_controller/$view";
            }
			
		   $this->header_data = $this->_getHeader();
		   $this->header_data['curr_controller'] = $curr_controller;
		   $this->header_data['curr_method'] = $curr_method;
		   $this->header_data['meta'] = $this->get_meta();
           $this->ci->load->view($this->scriptsrc , $this->header_data);
           $this->ci->load->view($this->header , $this->header_data);
           $this->ci->load->view($view , $data);
           $this->ci->load->view($this->footer , $data);
        }
        
    }
	
	public function load_filter(){
		$filter_file = 'filter-form';
		$data['curr_controller'] = $this->ci->router->fetch_class();
        $data['curr_method'] = $this->ci->router->fetch_method();
        $data['url_segment'] = $data['curr_controller'].'/'. $data['curr_method'];
		$this->ci->load->view($filter_file , $data);
	}
	
	private function _getHeader(){
		$data['title'] = $this->title;
		return $data;
	}
	
	public function set_title($title=''){
		$this->title = $title;
	}
	
	public function set_meta($key='' , $val=''){
		$this->meta[$key] = $val;
	}
	
	private function get_meta(){
		return $this->meta;
	}
	
	
}