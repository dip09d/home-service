<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stripe 
{
	function __construct($config = array())
	{
		
	}

	public function load()
    {
        require_once(APPPATH."third_party/stripe/init.php");
        $secret_key = get_setting('stripe_secret_key');
        $api=\Stripe\Stripe::setApiKey($secret_key);
        
    }
}

