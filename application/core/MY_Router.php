<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router {

    protected function _parse_routes()
    {
    // Language detection over URL
        if($this->uri->segments[1] == $this->config->config['language']) {
            unset($this->uri->segments[1]);
        } elseif(array_search($this->uri->segments[1], $this->config->config['languages'])) {
            $this->config->config['language'] = array_search($this->uri->segments[1], $this->config->config['languages']);
            $base_url=SITE_URL.''.$this->uri->segments[1].'/';
            //define('SITE_URL',$base_url);
           // define('VPATH',$base_url);
            $this->config->config['base_url']=$base_url;
            unset($this->uri->segments[1]);
            if(count($this->uri->segments)==0){
                parent::_set_default_controller();
            }
        }
        return parent::_parse_routes();

    } 

}