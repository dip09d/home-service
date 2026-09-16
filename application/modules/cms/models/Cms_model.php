<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_model extends MX_Controller {

	function __construct()
	{
		$this->lang = get_active_lang();
			parent::__construct();
	}
	public function getTempContent($page=''){
		$arr=array(
			'select'=>'c.cms_key,c.cms_page,c.cms_class,c.child_class',
			'table'=>'cms_temp as c',
			'where'=>array('c.cms_page'=>$page),
			'order'=>array(array('c.cms_order','asc'))
		);
		$section=getData($arr);
		if($section){
			foreach($section as $k=>$sec){
				$arr=array(
					'select'=>'c.part_id,c.part_class,c.part_content',
					'table'=>'cms_temp_part as c',
					'where'=>array('c.cms_key'=>$sec->cms_key,'c.lang'=>$this->lang),
					'order'=>array(array('c.part_order','asc'))
				);
				$section[$k]->part=getData($arr);
			}
		}
		return $section;
	}
	
}
