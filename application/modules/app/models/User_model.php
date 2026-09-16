<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model{

    public function __construct() {
        return parent::__construct();
    }
    
    public function list_portfolio($srch_param=array() , $limit=0 , $offset=40 , $for_list=TRUE){
        $this->db->select("*")
        ->from('user_portfolio')
        ->where('status','Y');
        if(!empty($srch_param['member_id'])){
            $this->db->where("member_id" ,$srch_param['member_id']);
        }
        if($for_list){
			$this->db->limit($offset , $limit);
            $this->db->order_by("id" , 'DESC');
            $result = $this->db->get()->result_array();
        }else{
            $result = $this->db->get()->num_rows();
        }
        return $result;
    }




    public function savebasic($member_id){
        $data = array(
			'slogan' => filter_data($this->input->post('slogan')),
			'fname' => filter_data($this->input->post('fname')),
			'lname' => filter_data($this->input->post('lname')),
			'display_name' => filter_data($this->input->post('display_name')),
            'country' =>filter_data( $this->input->post('country')),                
			'city' => filter_data($this->input->post('city')),
			'hourly_rate' => filter_data($this->input->post('hourly_rate')),
			'available_hr' => filter_data($this->input->post('available_week')),     
            'facebook_link' => filter_data($this->input->post('facebook_link')),      
            'linkedin_link' => filter_data($this->input->post('linkedin_link')),    
			'edit_date' => 'NOW()'
		);
        $response = array();
        $this->db->where('member_id',$member_id);
        $upd_user=$this->db->update('user', $data);
        if ($upd_user){
            $msg['status']=1;
            $msg['data'] = array(
                'display_name' => $data['display_name'],
                'member_id' => $member_id,
            );
        }else{
            $msg['status']=0;
            $msg['errors'][$i]['id']='singup_error';
            $msg['errors'][$i]['message']= 'Something went wrong';
        }
        $this->response=$msg;
		return $this->response;
    }
    public function saveoverview($member_id){
        $data = array(
			'overview' => filter_data($this->input->post('overview')),
			'edit_date' => 'NOW()'
		);
        $response = array();
        $this->db->where('member_id',$member_id);
        $upd_user=$this->db->update('user', $data);
        if ($upd_user){
            $msg['status']=1;
            $msg['data'] = array(
                'member_id' => $member_id,
            );
        }else{
            $msg['status']=0;
            $msg['errors'][$i]['id']='singup_error';
            $msg['errors'][$i]['message']= 'Something went wrong';
        }
        $this->response=$msg;
		return $this->response;
    }
    public function savebusiness($member_id,$account_type){
        $status=0;
        $msg=array();
        $post = filter_data($this->input->post());
        if($this->input->post('is_freelancer_company')){
            $is_freelancer_company=1;
        }else{
            $is_freelancer_company=0;
        }
        
        $checkcompany=$this->db->where('member_id',$member_id)->count_all_results('user_company');
        if($checkcompany > 0){
            $data = array(
                'business_name' => $post['business_name'],
                'company_number' => $post['company_number'],
                'vat' => $post['vat'],
                'vat_rate' => $post['vat_rate'],
            );
            if($account_type=='F'){
                $data['is_freelancer_company']=$is_freelancer_company;
            }
            $this->db->where('member_id', $member_id)->update('user_company', $data);
            $status = 1;
        }else{
            $data = array(
                'member_id' => $member_id,
                'business_name' => $post['business_name'],
                'company_number' => $post['company_number'],
                'vat' => $post['vat'],
                'vat_rate' => $post['vat_rate'],
            );
            if($account_type=='F'){
                $data['is_freelancer_company']=$is_freelancer_company;
            }
            $status = 1;
            $this->db->insert('user_company', $data);
        }
        

        if ($status){
            $msg['status']=1;
            $msg['data'] = array(
                'member_id' => $member_id,
            );
        }else{
            $msg['status']=0;
            $msg['errors'][$i]['id']='singup_error';
            $msg['errors'][$i]['message']= 'Something went wrong';
        }
        $this->response=$msg;
		return $this->response;
    }
    public function saveprofilepic($member_id){
        $response = array();
        if($this->input->post("profile_pic")){
			$dataimg=$this->input->post("profile_pic",FALSE);
			if($this->config->item('global_xss_filtering')){
				$image = base64_decode(str_replace('[removed]', '', $dataimg));
			}else{
				$formatdata=explode(';base64,',$dataimg);
				$image = base64_decode($formatdata[1]);
			}
			$image_name = md5($member_id.'-'.time());
			$filename = $image_name . '.' . 'png';
			$path = APATH.'assets/uploaded/';
			@file_put_contents($path.$filename, $image);
			
            $this->db->where(array('member_id' => $member_id))->update('user', array('logo' => $filename));
            $msg['status']=1;
            $msg['data'] = array(
                'logo' =>get_user_logo($member_id),
                'member_id' => $member_id,
            );
        }else{
            $msg['status']=0;
            $msg['errors'][$i]['id']='singup_error';
            $msg['errors'][$i]['message']= 'Something went wrong';
        }
        $this->response=$msg;
		return $this->response;
    }
    public function saveprofilebackground($member_id){
        $response = array();
        if($this->input->post("backgroud")){
			$dataimg=$this->input->post("backgroud",FALSE);
			if($this->config->item('global_xss_filtering')){
				$image = base64_decode(str_replace('[removed]', '', $dataimg));
			}else{
				$formatdata=explode(';base64,',$dataimg);
				$image = base64_decode($formatdata[1]);
			}
			$image_name = md5($member_id.'-'.time());
			$filename = $image_name . '.' . 'png';
			$path = APATH.'assets/uploaded/';
			@file_put_contents($path.$filename, $image);
			
            $this->db->where(array('member_id' => $member_id))->update('user', array('profile_bg_pic' => $filename));
            $msg['status']=1;
            $msg['data'] = array(
                'background' =>VPATH.'assets/uploaded/'.$filename,
                'member_id' => $member_id,
            );
        }else{
            $msg['status']=0;
            $msg['errors'][$i]['id']='singup_error';
            $msg['errors'][$i]['message']= 'Something went wrong';
        }
        $this->response=$msg;
		return $this->response;
    }

    
    

    public function list_invoice($srch=array() , $limit=0 , $offset=40 , $for_list=TRUE){
        $this->db->select('i.*,t.type')
				->from('invoice_main i')
				->join('invoice_type t', 'i.invoice_type=t.invoice_type_id', 'LEFT')
				->join('project_invoice p_i', 'p_i.invoice_id=i.invoice_id', 'LEFT');
		
		if(!empty($srch['project_id'])){
			$this->db->where('p_i.project_id', $srch['project_id']);
		}
		
		if(!empty($srch['invoice_number'])){
			$this->db->where('i.invoice_number', $srch['invoice_number']);
		}
		
		if(!empty($srch['invoice_type'])){
			$this->db->where('i.invoice_type', $srch['invoice_type']);
		}
		
		if(!empty($srch['member_id'])){
			$this->db->where("(i.sender_id = {$srch['member_id']} OR i.receiver_id = {$srch['member_id']})");
		}
		
		if($for_list){
			$result = $this->db->limit($offset, $limit)->order_by('i.invoice_id', 'DESC')->get()->result_array();

		}else{
			$result = $this->db->get()->num_rows();
		}
        return $result;
    }
    public function load_conversation($member_id,$start = '', $limit = '',$count=FALSE,$where=[]){
		$this->db->select('c.conversations_id,c.last_message_id,c.project_id');
		$this->db->from('conversations as c');
		$this->db->join('conversations_room as u','u.conversations_id=c.conversations_id','left');
		if($where){
			if(array_key_exists('name',$where) && $where['name']){
				$this->db->join('conversations_room as o',"(o.conversations_id=c.conversations_id and o.member_id <> $member_id)",'left');
				$this->db->join('member as us','o.member_id=us.member_id ','left');
				$this->db->like('us.username',$where['name']);
			}
		}
		$this->db->where('u.user_id',$member_id);
		if($where){
			if(array_key_exists('chatroom_id',$where)){
				$chat_id=str_replace('channel_room_','',$where['chatroom_id']);
				$this->db->where('c.conversations_id',$chat_id);
			}
		}
		$this->db->order_by('c.last_message_id','desc')->order_by('c.conversations_id', 'DESC')->group_by('c.conversations_id');
		if($count==TRUE){
			$data=$this->db->count_all_results();
		}else{
			$this->db->limit($limit,$start);
			$data=$this->db->get()->result();
			//echo $this->db->last_query();
		}
		return $data;
	}
    public function getChatMessage($conversation_id='', $limit=0, $offset=30, $for_list=TRUE){

		$this->db->select("c_m.*")
			->from('chat_message c_m');
		$this->db->where('c_m.conversations_id', $conversation_id);
		$this->db->order_by('c_m.message_id', 'DESC');
		if($for_list){
			$this->db->limit($offset, $limit);
			$result = $this->db->get()->result();
			if(count($result) > 0){
				foreach($result as $k => $v){
					$result[$k]->message = nl2br($v->message);
                }
			}
		}else{
			$result =  $this->db->get()->num_rows();
		}
		return $result;
	}
    
}