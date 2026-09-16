<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class URL{
    /**
     *  Get Link 
     * 
     *  @param slug     {String} - Url slug
     *  @param segment  {Array} - segment to add after the url . ex. array('project', '101') => output = project/101
     * 
     *  @return url    {String} - url of the given slug
     * 
     */
    
    public static function getLink($slug='', $segment=array(), $return=FALSE){
        $url_segment['login'] = base_url('login');
        $url_segment['login-submit'] = base_url('user/login_check');
        $url_segment['signup'] = base_url('signup');
      
        if(array_key_exists($slug, $url_segment)){
            $url = $url_segment[$slug];

            if($segment && is_array($segment)){
                $segment_str = implode('/', $segment);
                $url .= '/'.$segment_str;
            }

            if($return){
                return  $url;
            }else{
                echo $url;
            }
        }else{

            $url = '#';
            if($return){
                return  $url;
            }else{
                echo $url;
            }
        }
    }

    public static function getResource(){

    }

    public static function SEO($str=''){

        /**
         *  Convert string in seo friendly string
         * 
         * @param str {String} - Input text
         * 
         * @return  {String} - Seo friendly string
         * 
         */
        return seo_string($str);
    }
    public static function get_link($var='', $return=FALSE){
		$ci =& get_instance();
		$ci->load->config('siteurl');
		return base_url($ci->config->item($var));
	}

}