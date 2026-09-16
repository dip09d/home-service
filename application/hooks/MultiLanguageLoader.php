<?php
    /**
     * @package Contact :  CodeIgniter Multi Language
     *
     * @author Asharam Pakhira
     *
     * @email  asish9735@gmail.com
     *   
     * Description of Multi Language Switcher Controller
     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
     

        // create language Switcher method
        function initialize() {
        	$ci =& get_instance();
        	$tamplatelang_section_check=$ci->input->get('ltpl');
        	if($tamplatelang_section_check!=''){
        		$ci->session->set_userdata('ltpl',!empty($tamplatelang_section_check) ? $tamplatelang_section_check:'');
			}
	        
	        $ci->load->language('global');
	        $ci->load->language('popup');
	        $moduleL=$ci->router->fetch_class();
			if(!file_exists(APPPATH.'language/'.$ci->config->item('language').'/'.$moduleL.'_lang.php')){
				file_put_contents(APPPATH.'language/'.$ci->config->item('language').'/'.$moduleL.'_lang.php', '');
			}
	        $ci->load->language($moduleL);
   		}
   		function __($key,$default=''){
   			$ci =& get_instance(); 
   			$line = $ci->lang->line($key, FALSE);
   			$tamplatelang_section=$ci->session->userdata('ltpl');
			if($tamplatelang_section>0){
				if($tamplatelang_section==1){
	   				return "<span class='sectionview'>".$key."</span>"."".$line;
	   			}elseif($tamplatelang_section==2){
	   				return "<font color=red>".$key."</font>";
	   			}elseif($tamplatelang_section==3){
	   				if(!$line){
					 	return "<font color=red>".$key."</font>".$default;
					}else{
					 	return $line;
					}
	   			}elseif($tamplatelang_section==4){
					return $key;
				}
			}else{
				if($line){
					return $line;
				}
			}
			return $default;
			//dd($ci->lang,TRUE);
		}

    ?>