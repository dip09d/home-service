<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Projectdetails_model extends BaseModel{

    public function __construct() {
        return parent::__construct();
    }
    public function savebid($biddata){
     

        $status=0;
        $msg=array();
        $project_id=$biddata->project_id;
        $member_id=$biddata->member_id;

        $this->load->model('dashboard/profile_model');
        $this->load->model('findjob/project_model');
        $project = new Project_Str($project_id);
        $bidder = new FreelancerProfile($member_id);

        $details=$biddata->cover_letter;
        $attachment='';
        $milestones=array();
        if($biddata->attachment){
            $all_attach=array();
            foreach($biddata->attachment as $atta){
                $all_attach[]=$atta->file;
            }

            $attachment=implode(',',$all_attach);
        }
        $days_required=$available_hr=$enable_escrow=0;
        if($biddata->day_required){
            $days_required=$biddata->day_required; 
        }
      
      
        $project_user=$this->auto_model->getFeild('member_id','projects','project_id',$project_id);
        $amount=0;

        $p_type =$project_type =getField('project_type', 'projects', 'project_id', $project_id);
        if($project_type=='H'){
            $amount=$biddata->bidamount;


        }else{
            $payment_type=$biddata->paid_by;
            if($payment_type=='P'){
                $milestones=array();
                $amount=$biddata->bidamount;
                $milestones_row=new stdClass();
                $milestones_row->amount=$amount;
                $milestones_row->title='Project payment';
                $milestones[]=$milestones_row;
            }else{
                $total_amount=array();
                $milestones=$biddata->milestones;
                foreach($milestones as $k => $v){
                    $total_amount[] = $v->amount;
                }
                $amount = array_sum($total_amount);
            }
           
        }
       
        $bid = $this->db->where(['project_id' => $project_id, 'bidder_id' =>$member_id])->get('bids')->row();
        if(!empty($bid->id)){
            $bid_id = $bid->id;
            $bid_dbdata = [
                'details' => $details,
               // 'attachment' => $attachment,
                'bidder_amt' =>$amount,
                'total_amt' => $amount,
                'days_required' => $days_required,
            ]; 
            if($attachment){
                $bid_dbdata['attachment']=$attachment;
            }
            $this->db->where('id', $bid_id)->update('bids', $bid_dbdata);
        }else{
            $bid_dbdata = [
                'project_id' => $project_id,
                'bidder_id' => $member_id,
                'details' => $details,
                'attachment' => $attachment,
                'bidder_amt' => $amount,
                'total_amt' => $amount,
                'days_required' =>  $days_required,
                'add_date' => date('Y-m-d H:i:s'),
                'payment_at' => $biddata->paid_by,
            ]; 
            
            $this->db->insert('bids', $bid_dbdata);
            $bid_id = $this->db->insert_id();
            $used_bid = get_used_bids($member_id); // used bid 
			$free_bid = getField('free_bid_per_month', 'setting', 'id', 1); // free bid per month
			
			if($used_bid > $free_bid){
				$this->db->set('available_bids', 'available_bids - 1', FALSE);
				$this->db->where('member_id', $member_id);
				$this->db->update('user');
			}
        }
        $this->db->where('bid_id', $bid_id)->delete('bid_milestone');
        if($milestones && $p_type == 'F'){
            $dbdata = [];
            foreach($milestones as $k => $v){
                $dbdata[] = [
                    'bid_id' => $bid_id,
                    'project_id' => $project_id,
                    'freelancer_id' => $member_id,
                    'title' => $v->title,
                    'amount' => $v->amount,
                    'commission_rate' => SITE_COMMISSION,
                    'added_on' => date('Y-m-d H:i:s')
                ];
            }
            if($dbdata){
                $this->db->insert_batch('bid_milestone', $dbdata);
            }
        }
        
        if($biddata->questions){
            foreach($biddata->questions as $i=>$question){
                $question_id = $question->question_id; 
                $answer = $question->answer; 
                $ans_array = array(
                    'freelancer_id' => $member_id,
                    'question_id' => $question_id,
                    'answer' => trim(htmlentities($answer)),
                );
                $count_row = $this->db->where(array('freelancer_id' => $member_id, 'question_id' => $question_id))->count_all_results('project_answers');
                
                if($count_row > 0){
                    $this->db->where(array('freelancer_id' => $member_id, 'question_id' => $question_id))->update('project_answers', $ans_array);
                }else{
                    $this->db->insert('project_answers', $ans_array);
                }
            }

        }
        $proposal_link = 'bid/bidders/'.$project_id;
        $employer_id = getField('member_id', 'projects', 'project_id', $project_id);
        $username =  getField('username', 'user', 'member_id', $member_id);
        if($employer_id){
            if(!empty($bid->id)){
                $this->notification_model->parseLog('update_proposal', ['NAME' => $username], $employer_id, $proposal_link);
            }else{
                $this->notification_model->parseLog('new_proposal', ['NAME' => $username], $employer_id, $proposal_link);
            }
        }
        

      
        if(empty($bid->id)){
            $to=$project->employer()->get('email');
            $template='bid_on_job_for_employe';
            $data_parse=array(
                'username'=>$project->employer()->displayName(),
                'freelancer'=>$bidder->displayName(),
                'project'=>$project->get('title'),
                'project_url'=> $project->projectLink(),
                'amount'=> $amount,
                'duration'=> $days_required,
            );

            Vmailer::send_layout_mail_lazy($template, $data_parse, $to);

        }else{
            $to=$project->employer()->get('email');
			$template='bid_reverse_for_employe';
			$data_parse=array(
				'username'=>$project->employer()->displayName(),
				'freelancer'=>$bidder->displayName(),
				'project'=>$project->get('title'),
				'project_url'=> $project->projectLink(),
				'amount'=>$amount,
				'duration'=> $days_required,
			);
			
			Vmailer::send_layout_mail_lazy($template, $data_parse, $to);
        }
        if($bid_id){
            $msg['status']=1;
            $msg['data'] = array(
                'bidid' => $bid_id,
            );
        }else{
            $msg['status']=0;
            $msg['errors'][$i]['id']='singup_error';
            $msg['errors'][$i]['message']= 'Something went wrong';
        }

        $this->response=$msg;
		return $this->response;
    }
    public function getuserdetails($uid){
        $result = get_row(array(
            'select' => 'member_id,fname,lname,username,country,city',
            'from' => 'user',
            'where' => array(
                'member_id' => $uid
            )
        ));
        $result['logo_url']=get_user_logo($uid);
        $result['rating']=get_user_rating($uid);
        
        $result['location'] = array(
            'country' => array(
                'name' => get_country_name($result['country']),
                'code' => $result['country'],
                'flag' => str_replace('.svg','.webp',get_country_flag($result['country'])),
            ),
            'city' => array(
                'name' => get_city_name($result['city']),
                'city_id' => $result['city'],
            )
        );
        return  $result;
    }
    public function averageBidAmount($project_id){
        $amount = $this->db->select_avg('total_amt')
            ->from('bids')
            ->where(['project_id' => $project_id])
            ->get()->row();
        if(!empty($amount->total_amt)){
            $avg_bid_amount = $amount->total_amt;
        }else{
            $avg_bid_amount = 0;
        }
        return $avg_bid_amount;
    }
    public function getbid_details($pid, $srch=array()){ 
        $review_new = $this->db->dbprefix('review_new');
        $this->db->select("b.*, (select avg(average) from $review_new where review_to_user=b.bidder_id) as average_rating",false);        
		$this->db->from("bids b");
		$this->db->join("review r", "r.member_id=b.bidder_id", "LEFT");
		
		$this->db->where(array("b.project_id" => $pid));
		
		$this->db->group_by("b.bidder_id");
		if(!empty($srch['sort']) AND $srch['sort'] == 'bid'){
			$this->db->order_by('b.total_amt', (array_key_exists('sortval',$srch)?$srch['sortval']:'asc'));
		}else if(!empty($srch['sort']) AND $srch['sort'] == 'rating'){
			$this->db->order_by("average_rating", (array_key_exists('sortval',$srch)?$srch['sortval']:'desc') );
		}else{
			$this->db->order_by("b.id", "desc");
		}
		
		$data=array();
        $data = $this->db->get()->result_array();
	
        return $data;        

    }
}
?>