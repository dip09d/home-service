<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('permission');
	}

	public function index()
	{
		echo "Working";
	}

	public function get_data($n = "Venkatesh bishu")
	{
		$n = str_replace("_", " ", $n);
		$q = $this->db->select('name , subject')->get('student');
		echo "<pre>";
		//print_r($q->result());
		foreach($q->result() as $v)
		{
			$s[$v->name] = $v->subject;
		}

		echo "<pre>";
		print_r($s);

		$a = $s[$n];
		$s = "Math";

		$a = explode(",", $a);
		if(in_array($s, $a))
		{
			echo "You have permission";
		}	
		else
		{
			echo "Sorry you dont have permission";
		}

		

	}

	public function guess($g = '')
	{
		$a[1] = "listing";
		$a[2] = "form";
		$a[3] = "form/";
		$a[4] = "delete";

		$b = array(1, 2, 4);
		foreach($b as $v)
		{
			$h[] = $a[$v];
		}

		echo "<pre>";
		print_r($h);

		if(empty($g))
		{
			return ;
		}
		else
		{
			if($g == 5)
			{
				echo "Welcome";
				return;
				echo "Hiiiiiiiiii how are you";
				echo "i am fine";
			}
		}
	}

	public function ghi($url)
	{
		$a = $url;
		echo "<pre>";

		$e = explode("/", $a);

		$c = count($e);
		print_r($e);

		if($c > 3)
		{
			echo $e[3];
		}
		else
		{
			echo $e[2];
		}
	}
	public function aaa()
	{
		$this->ghi(uri_string());
	}
	


}


