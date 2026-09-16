<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MX_Controller {

	function __construct()
	{
		
		loadModel('notification_model');
			parent::__construct();
	}

}
?>