<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends MX_Controller {
	private $lang;
	function __construct()
	{
			$this->lang = get_active_lang();
			parent::__construct();
	}
	
	public function getList($srch=array(), $limit=0, $offset=20, $for_list=TRUE){ 
	
	 	$this->db->select('b.blog_id,b.blog_slug,b.blog_reg_date,b.blog_thumb,b_n.blog_title,b_n.blog_short_description');		 
		$this->db->from('blog as b');
		$this->db->join("blog_names as b_n", "(b.blog_id=b_n.blog_id and b_n.blog_lang='".$this->lang."')", "LEFT");
		$this->db->join("blog_tags as t", "(b.blog_id=t.blog_id and t.lang='".$this->lang."')", "LEFT");

		$this->db->where('blog_status',1);
		if($srch){
			if(array_key_exists('term',$srch) && $srch['term']){
				if(!empty($srch['term'])){
					$this->db->like('b_n.blog_title', $srch['term']);
				}
			}
			if(array_key_exists('tag',$srch) && $srch['tag']){
				if(!empty($srch['tag'])){
					$this->db->like('t.name', $srch['tag']);
				}
			}
			if(array_key_exists('is_previous_blog',$srch) && $srch['is_previous_blog']){
				$this->db->where('b.blog_id <', $srch['is_previous_blog']);
			}
			if(array_key_exists('is_next_blog',$srch) && $srch['is_next_blog']){
				$this->db->where('b.blog_id >', $srch['is_next_blog']);
			}
			if(array_key_exists('is_featured',$srch) && $srch['is_featured']){
				// $this->db->where('b.is_featured', 1);
			}
		}

		
		$this->db->group_by('b.blog_id');
		if($srch){
		 	if(array_key_exists('is_tranding',$srch) && $srch['is_tranding']==1){
				// $this->db->order_by('b.blog_views','desc');
			}
		}
		
		$this->db->order_by('b.blog_id','desc');

		if($for_list){
			$this->db->limit($offset, $limit);
			if(array_key_exists('is_previous_blog',$srch) || array_key_exists('is_next_blog',$srch)){
				$result = $this->db->get()->row();
			}else{
				$result = $this->db->get()->result();
			}
		}else{
			$result = $this->db->get()->num_rows();
		}
		return $result;
	}
	public function getBlogTags($blog_id,$limit=''){
		$this->db->select('name')->where('blog_id',$blog_id)->where('lang',$this->lang)->from('blog_tags');
		if($limit){
			$this->db->limit($limit);
		}
		return $this->db->get()->result();
	}
	public function getPolularTags($limit=''){
		$this->db->select('name,count(name) as total')->where('lang',$this->lang)->from('blog_tags')->group_by('name');
		if($limit){
			$this->db->limit($limit);
		}
		$this->db->order_by('total','desc');
		return $this->db->get()->result();
	}

}
