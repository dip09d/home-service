<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pusher 
{
	function __construct($config = array())
	{
		
	}

	public function load()
    {
        require_once(APPPATH."third_party/pusherLib/autoload.php");
        $this->CI = get_instance();
		$this->CI->load->config('pusher');
        $obj = new Pusher\Pusher($this->CI->config->item('pusher_api_key'), $this->CI->config->item('pusher_secret'), $this->CI->config->item('pusher_app_id'), array('cluster' => $this->CI->config->item('pusher_cluster')));
        return $obj;
    }
}