<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Findtalents extends MX_Controller {
	
	private $data;
	
    public function __construct() {
    	$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}
		parent::__construct();
        $this->load->model('findtalents_model');
		$curr_class = $this->router->fetch_class();
		$curr_method = $this->router->fetch_method();
		
		$this->data['curr_class'] = $curr_class;
		$this->data['curr_method'] = $curr_method;
		
    }

  
	public function all_list() {
		$this->layout->set_js(array(
			'bootbox_custom.js',
			'mycustom.js',
			'bootstrap-tagsinput.min.js',
			'typeahead.bundle.min.js',
		));
		$this->layout->set_css(array(
			'bootstrap-tagsinput.css'
		));
		$this->data['searchdata']=get();
		if(get('byskillsname')){
			$this->data['searchdata']['pre_skills']=getData(array(
				'select'=>'s.skill_id,s.skill_key,s_n.skill_name',
				'table'=>'skills s',
				'join'=>array(array('table'=>'skill_names as s_n','on'=>"(s.skill_id=s_n.skill_id and s_n.skill_lang='".get_active_lang()."')",'position'=>'left')),
				'where'=>array('s.skill_status'=>'1'),
				'where_in'=>array('s.skill_key'=>get('byskillsname'))
			));
		}
		
		$this->data['all_skills']=getAllSkills();
		$this->data['all_location']=getAllCountry();

		$max_houry_rateData=$this->db->select('max(member_hourly_rate) as max_houry_rate')->from('member_basic')->where('member_hourly_rate >',0)->get()->row();
		if($max_houry_rateData){
			$max_houry_rate=ceil( $max_houry_rateData->max_houry_rate / 5 ) * 5;
		}else{
			$max_houry_rate=250;
		}
		$this->data['max_houry_rate']=$max_houry_rate;
		$this->layout->set_meta('author', 'Venkatesh bishu');
		$this->layout->set_meta('keywords', 'Freelancer Script, Freelancer, New Flance');
		$this->layout->set_meta('description', 'Freelancer Clone Script');
		$this->layout->view('findtalents',$this->data);
	}
	
	public function get_sub_category(){
		$category_id = get('category_id');
		$category = array();
		if($category_id > 0){
			$category = $this->job_model->get_sub_category($category_id);
		}
		
		$this->api->set_data('category', $category);
		$this->api->out();
	}
	
	public function talent_list_ajax(){
		$data=array();
		$get = get();

		$limit = !empty($get['per_page']) ? $get['per_page'] : 0;
		$offset = 10;
		$next_limit = $limit + $offset;
		$login_user_id=0;
		if($this->loggedUser){
			$login_user_id=$this->member_id;
		}
		$data['login_user_id']=$login_user_id;
		$data['talent_list'] =$this->findtalents_model->getTalentList($get,$limit, $offset);
		$data['talent_list_count'] =$this->findtalents_model->getTalentList($get,'','', FALSE);
		
		$json['talent_list'] = $data['talent_list'];
		$json['talent_list_count'] = $data['talent_list_count'];
		
		if($data['talent_list_count'] > $next_limit){
			unset($get['per_page']);
			
			if($get){
				$json['next'] = base_url('findtalents/talent_list_ajax?per_page='.$next_limit.'&'.http_build_query($get));
			}else{
				$json['next'] = base_url('findtalents/talent_list_ajax?per_page='.$next_limit);
			}
			
		}else{
			$json['next'] = null;
		}
		$json['html'] = $this->layout->view('talent-list-ajax',$data, TRUE, TRUE);
		$json['status'] = 1;		
		echo json_encode($json);
	}
	
	public function action_favorite(){
		checkrequestajax();
		if($this->loggedUser){
			$cmd='';
			$member_id_md5=post('mid');
			if($member_id_md5){
				$member_id=getFieldData('member_id','member','md5(member_id)',$member_id_md5);
				if($member_id){
					$cnt=$this->db->where('favorite_member_id',$member_id)->where('member_id',$this->member_id)->from('favorite_member')->count_all_results();
					if($cnt){
						$this->db->where('favorite_member_id',$member_id)->where('member_id',$this->member_id)->delete('favorite_member');
						$cmd='remove';
					}else{
						$this->db->insert('favorite_member',array('favorite_member_id'=>$member_id,'member_id'=>$this->member_id,'reg_date'=>date('Y-m-d H:i:s')));
						$cmd='add';
					}
					
				}
			}
			$json['status']='OK';
			$json['cmd']=$cmd;
		}else{
			$json['status']='FAIL';
			$json['popup']='login';
		}
		echo json_encode($json);
	}
	

	
	
}
