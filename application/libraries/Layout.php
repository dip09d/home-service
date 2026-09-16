<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layout {
    private $ci;
    private $header;
    private $footer;
    private $scriptsrc;
    private $theme;
	private $meta_title;
	private $meta_description;
	private $meta;
	private $css;
	private $js;
    public function __construct()
    {
        $this->ci = &get_instance();
		$this->theme =  THEME ;
		$this->meta_title = "";
		$this->meta_description = "";
		$this->meta = array();
        $this->header = $this->theme."/inc/header";
        $this->footer = $this->theme."/inc/footer";
        $this->scriptsrc = $this->theme."/inc/scriptsrc";
		$this->header_data = $this->_getHeader();
		$this->css = array();
		$this->js = array();
    }
    
    public function view($view = '' , $data = array() , $blank = false,$return_view=FALSE)
    {
        $curr_controller = $this->ci->router->fetch_class();
        $curr_method = $this->ci->router->fetch_method();
		$theme = THEME;
        if($blank)
        {
            if(@file_exists(APPPATH.'views/'.THEME.$view.'.php')){
				$view =  THEME.$view;
			}
			elseif(!file_exists(APPPATH."modules/$curr_controller/views/$view".EXT)){
                $view = "$theme"."$curr_controller/$view";
            }
            if($return_view){
				return $this->ci->load->view($view , $data,TRUE);
			}else{
				$this->ci->load->view($view , $data);
			}
            
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
		$data['meta_title'] = $this->meta_title;
		$data['meta_description'] = $this->meta_description;
		return $data;
	}
	
	public function set_title($title=''){
		$this->meta_title = $title;
	}
	
	public function set_meta($key='' , $val=''){
		$this->meta[$key] = $val;
		if($key=='description'){
			$this->meta_description =$val;
		}
	}
	
	private function get_meta(){
		return $this->meta;
	}
	public function load_meta(){
		foreach($this->meta as $meta_key => $meta_val){
			echo '<meta name="'.$meta_key.'" content="'.$meta_val.'">';
		}
	}
	
	public function set_js($src=array()){
		if(is_array($src)){
			$this->js = array_merge($this->js, $src);
		}else{
			$this->js[] = $src;
		}
		
	}
	
	public function set_css($src=array()){
		if(is_array($src)){
			$this->css = array_merge($this->css, $src);
		}else{
			$this->css[] = $src;
		}
	}
	
	public function load_js($minify=FALSE){
		/*if(count($this->js) > 0){

			if($minify){
				$this->ci->load->library('minify');
				$this->ci->minify->js($this->js);
				echo $this->ci->minify->deploy_js(FALSE);
			}else{
				foreach($this->js as $script){
					echo '<script src="'.JS.$script.'" type="text/javascript"></script>';
				}
			}
		}*/
		return $this->js;
	}

	public function load_css($minify=FALSE){
		/*if(count($this->css) > 0){
			if($minify){
				$this->ci->load->library('minify');
				$this->ci->minify->css($this->css);
				echo $this->ci->minify->deploy_css(FALSE);
			}else{
				foreach($this->css as $css){
					echo '<link rel="stylesheet" href="'.CSS.$css.'">';
				}
			}
		}*/
		return $this->css;
		
	}
	
}