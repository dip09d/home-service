<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("access-control-allow-origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header('content-type: application/json; charset=utf-8');

require APPPATH.'libraries/MX_Rest.php';
class Projectdetails extends MX_Rest{
	private $ReturnCode;
	private $ReturnStatus;
	private $ResponseData;
	function __construct() {
        if($this->input->get('originatesoft_lang')){
			$langS=get('originatesoft_lang');
			$alllanguage=explode(',',get_setting('language'));
			if(in_array($langS,$alllanguage)){
				$this->session->set_userdata('current_lang',$langS);
			} 
		}
		if($this->input->get('member_id') && $this->input->get('member_id')>0){
			$this->member_id=$this->input->get('member_id');
            $is_employer=$this->auto_model->getFeild('is_employer','member','member_id',$this->member_id);
			$this->organization_id=0;
			if($is_employer){
				$this->organization_id=$this->auto_model->getFeild('organization_id','organization','member_id',$this->member_id);
			}
            $this->account_type=($is_employer==1?'E':'F');
			$result[]=(object)[
				'member_id'=>$this->member_id,
				'account_type'=>$this->account_type,
			];
			$this->session->set_userdata('user', $result);
		}
	 	$this->ReturnStatus=0;
	 	$this->ReturnCode=200;
	 	$this->ResponseData=array();
        parent::__construct();
	}
	
	public function get_index(){
     $this->response(array(
        "status" => 1,
        "message" => "Welcome to project module"
      ) , 200);
    }
    public function get_details(){
        
        $project_id=$this->input->get('project_id');
        $member_id=$this->member_id;
        $organization_id=$this->organization_id;
        $this->db->select("project_id");
        $data=$this->db->get_where("project",array("project_id"=> $project_id,'project_status <>'=>PROJECT_DELETED))->row_array();
        if( $data){
            $project_id=$data['project_id']; 
        }else{
            $this->response(array(
                "status" => 0,
                "message" => "error"
              ) , 200);
              die;
        }
        $is_owner=FALSE;
        $data['projectData']=getProjectDetails($project_id);
        if($data['projectData'] && $data['projectData']['project_owner']){
				
            if($data['projectData']['project_settings']){
                $hourly_duration_name=getAllProjectDuration($data['projectData']['project_settings']->hourly_duration);
                $hourly_time_required_name=getAllProjectDurationTime($data['projectData']['project_settings']->hourly_time_required);
                $project_type_code_name=getAllProjectType($data['projectData']['project_settings']->project_type_code);

                $data['projectData']['project_settings']->hourly_duration_name=$hourly_duration_name['name'];
                $data['projectData']['project_settings']->hourly_time_required_name=$hourly_time_required_name['name'];
                $data['projectData']['project_settings']->project_type_code_name=$project_type_code_name['name'];

            }
            if($data['projectData']['project_files']){
                foreach($data['projectData']['project_files'] as $f=>$file){
                    $file->file_url=UPLOAD_HTTP_PATH.'projects-files/projects-requirement/'.$file->server_name;
                    $data['projectData']['project_files'][$f]=$file;
                }

            }
            if($data['projectData']['project_owner']->organization_id && $data['projectData']['project_owner']->organization_id==$organization_id){
                $is_owner=TRUE;
            }elseif($data['projectData']['project_owner']->member_id==$member_id){
                $is_owner=TRUE;
            }
            
            
            if($data['projectData']['project_owner']->organization_id){
                $memberData=getData(array(
                    'select'=>'o.organization_name,o.organization_register_date,o_a.organization_timezone,o_a.organization_city,o_a.organization_state,c_n.country_name,o.is_payment_verified',
                    'table'=>'organization as o',
                    'join'=>array(
                        array('table'=>'organization_address as o_a','on'=>'o.organization_id=o_a.organization_id','position'=>'left'),
                        array('table'=>'country as c','on'=>'o_a.organization_country=c.country_code','position'=>'left'),
                        array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
                    ),
                    'where'=>array('o.organization_id'=>$data['projectData']['project_owner']->organization_id),
                    'single_row'=>true,
                ));
        
                $client_name=$memberData->organization_name;
                $client_address=array();
                $location_address=array();
                if($memberData->organization_city){
                    $location_address[]=$memberData->organization_city;
                }
                if($memberData->organization_state){
                    $location_address[]=$memberData->organization_state;
                }
                $location=implode(', ',$location_address);
                $client_country="";
                if($memberData->country_name){
                    $client_country=$memberData->country_name;
                }
                $client_address['location']=$location;
                $client_address['country']=$client_country;
                $client_payment_verify=$memberData->is_payment_verified;
                
                $client_member_since=$memberData->organization_register_date;
                
            }else{
                $memberData=getData(array(
                    'select'=>'m.member_name,m.member_register_date,m_a.member_timezone,m_a.member_city,m_a.member_state,c_n.country_name',
                    'table'=>'member as m',
                    'join'=>array(
                        array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),
                        array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),
                        array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
                    ),
                    'where'=>array('m.member_id'=>$data['projectData']['project_owner']->member_id),
                    'single_row'=>true,
                ));
        
                $client_name=$memberData->member_name;
                $client_address=array();
                $location_address=array();
                if($memberData->member_city){
                    $location_address[]=$memberData->member_city;
                }
                if($memberData->member_state){
                    $location_address[]=$memberData->member_state;
                }
                $location=implode(', ',$location_address);
                $client_country="";
                if($memberData->country_name){
                    $client_country=$memberData->country_name;
                }
                $client_address['location']=$location;
                $client_address['country']=$client_country;
                $client_payment_verify="0";
        
                $client_member_since=$memberData->member_register_date;
                
            }
        }
        $total_project=getData(array(
            'select'=>'p.project_id',
            'table'=>'project as p',
            'join'=>array(
                array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left'),
            ),
            'where'=>array('p_o.member_id'=>$data['projectData']['project_owner']->member_id),
            'where_in'=>array('p.project_status'=>array(PROJECT_OPEN,PROJECT_HIRED,PROJECT_CLOSED)),
            'return_count'=>TRUE
        ));
        $total_hired=getData(array(
            'select'=>'p.project_id',
            'table'=>'project as p',
            'join'=>array(
                array('table'=>'project_owner as p_o','on'=>'p.project_id=p_o.project_id','position'=>'left'),
                array('table'=>'project_bids as p_b','on'=>'p.project_id=p_b.project_id','position'=>'left'),
            ),
            'where'=>array('p_o.member_id'=>$data['projectData']['project_owner']->member_id,'p_b.is_hired'=>1),
            'return_count'=>TRUE
        ));
        $client_project_info=array('total_project'=>$total_project,'total_hired'=>$total_hired,'total_active'=>0);
        $memberDatacount=getData(array(
            'select'=>'m_s.avg_rating,m_s.no_of_reviews,m_s.total_spent',
            'table'=>'member_statistics as m_s',
            'where'=>array('m_s.member_id'=>$data['projectData']['project_owner']->member_id),
            'single_row'=>TRUE
        ));
        if($memberDatacount){
            $client_review_rating=array('rating'=>$memberDatacount->avg_rating,'review'=>$memberDatacount->no_of_reviews);
            $client_total_payment=displayamount($memberDatacount->total_spent,2);
        }
        $data['projectData']['clientInfo']=array(
			'client_name'=>$client_name,
			'client_address'=>$client_address,
			'client_payment_verify'=>$client_payment_verify,
			'client_total_payment'=>$client_total_payment,
			'client_member_since'=>$client_member_since,
			'client_review_rating'=>$client_review_rating,
			'client_project_info'=>$client_project_info,
		);
		$data['projectData']['proposal']=array(
			'total_proposal'=>getBids($project_id,array(),true),
			'total_invite'=>getBids($project_id,array('is_invite'=>TRUE),true),
			'total_interview'=>getBids($project_id,array('is_interview'=>TRUE),true),
			'total_hires'=>getBids($project_id,array('is_hired'=>TRUE),true),
		);
		$data['is_owner']=$is_owner;
        $is_already_bid=getData(array(
			'select'=>'bid_id,is_hired',
			'table'=>'project_bids',
			'where'=>array('project_id'=>$project_id,'member_id'=>$this->member_id),
			'single_row'=>TRUE
		));
        $data['is_already_bid']=$is_already_bid;


        $result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);

    }

    public function get_bidlist(){
        $member_id=$this->member_id;
        $organization_id=$this->organization_id;	
        $project_id=$this->input->get('project_id');
        $data['projects']=getProjectDetails($project_id,array('project','project_owner','project_settings'));
        if($data['projects']['project_owner']->organization_id==$organization_id){
            
            
        }else{
            $this->response(array(
                "status" => 0,
                "message" => "error"
              ) , 200);
              die;
        }
       
        $srch = $this->input->get();
        $bid_list=array();
        $req_type=get('type');
        $bid_list_data=getBidsListDetails($project_id,array('is_'.get('type')=>1),FALSE);
        if($bid_list_data){
            foreach($bid_list_data as $k=>$bids){
                $bids->country_info=getAllCountry(array('country_code'=>$bids->member_country));
                $bids->logo=getMemberLogo($bids->member_id);
                $button_action=[];
                if($req_type=='proposal'){
                    if($bids->is_shortlisted==1){
                        $button_action[]=[
                            'key'=>'shortlist_off',
                            'name'=>__('projectclient_proposa_shortlisted','Shortlisted')
                        ];
                    }else{
                        $button_action[]=[
                            'key'=>'shortlist',
                            'name'=>__('projectclient_proposa_shortlist','Shortlist')
                        ];
                    }
                    if($bids->is_interview==1){
                        $button_action[]=[
                            'key'=>'interview_off',
                            'name'=>__('projectclient_proposa_interview','Interview')
                        ];
                    }else{
                        $button_action[]=[
                            'key'=>'interview',
                            'name'=>__('projectclient_proposa_interview','Interview')
                        ];
                    }
                    $button_action[]=[
                        'key'=>'archive',
                        'name'=>__('projectclient_proposa_archive','Archive')
                    ];
                    if($bids->is_hired==1){
                        $button_action[]=[
                            'key'=>'hire',
                            'name'=>__('projectclient_proposa_S_offer','Send Offer')
                        ];
                    }else{
                        $button_action[]=[
                            'key'=>'hire',
                            'name'=>__('projectclient_proposa_hire','Hire')
                        ];
                    }
                    
                }elseif($req_type=='archive'){
                    $button_action[]=[
                        'key'=>'archive_off',
                        'name'=>__('projectclient_proposa_unarchive','Unarchive')
                    ];
                }elseif($req_type=='interview'){
                    $button_action[]=[
                        'key'=>'archive',
                        'name'=>__('projectclient_proposa_archive','Archive')
                    ];
                    $button_action[]=[
                        'key'=>'hire',
                        'name'=>__('projectclient_proposa_hire','Hire')
                    ];
                }elseif($req_type=='shortlisted'){
                    if($bids->is_interview==1){
                        $button_action[]=[
                            'key'=>'interview_off',
                            'name'=>__('projectclient_proposa_interview','Interview')
                        ];
                    }else{
                        $button_action[]=[
                            'key'=>'interview',
                            'name'=>__('projectclient_proposa_interview','Interview')
                        ];
                    }
                    $button_action[]=[
                        'key'=>'archive',
                        'name'=>__('projectclient_proposa_archive','Archive')
                    ];
                    $button_action[]=[
                        'key'=>'hire',
                        'name'=>__('projectclient_proposa_hire','Hire')
                    ];
                }elseif($req_type=='hired'){
                    $button_action[]=[
                        'key'=>'hire',
                        'name'=>__('projectclient_proposa_S_offer','Send Offer')
                    ];
                }
                $button_action[]=[
                    'key'=>'messages',
                    'name'=>__('projectclient_list_messages','Message')
                ];
                
                $bids->button_action=$button_action;
                $bid_list[]=$bids;
            }
        }
        $data['bid_list']= $bid_list; 
        $result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
		$this->ResponseData=$result;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);

    }
    public function post_update_propasal(){
		$res=array();
		$project_id=post('project_id');
		$application_id=post('application_id');
		$formtype=post('formtype');
		if($this->member_id){
			$msg=array();
			$i=0;
			$member_id=$this->member_id;
			$organization_id=$this->organization_id;	
			if($member_id){
				if(!$project_id){
					$msg['status'] = '0';
					$msg['errors'][$i]['id'] = 'project_id';
					$msg['errors'][$i]['message'] = 'Select project';
					$i++;
					unset($_POST);
                    $this->ResponseData=$msg;
                    $this->response(array(
                        "status" =>$this->ReturnStatus,
                        "response" =>$this->ResponseData
                    ) , 
                    $this->ReturnCode);
					die();
				}
				$data['projects']=getProjectDetails($project_id,array('project_owner','project'));
				if($data['projects']['project_owner']->organization_id==$organization_id){
					$SENDER_NAME=$data['projects']['project_owner']->organization_name;
					$TITLE=$data['projects']['project']->project_title;
					$URL=get_link('myProjectDetailsURL').'/'.$data['projects']['project']->project_url;
					$notification_URL=$this->config->item('myProjectDetailsURL').'/'.$data['projects']['project']->project_url;
					if($formtype=='shortlist'){
						$up=updateTable('project_bids',array('is_shortlisted'=>1,'is_archive'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='unarchive' || $formtype=='archive_off'){
						$up=updateTable('project_bids',array('is_archive'=>NULL,'is_shortlisted'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='archive'){
						$up=updateTable('project_bids',array('is_archive'=>1,'is_shortlisted'=>NULL,'is_interview'=>NULL),array('bid_id'=>$application_id,'project_id'=>$project_id));
					}elseif($formtype=='interview'){
						$up=updateTable('project_bids',array('is_archive'=>NULL,'is_shortlisted'=>NULL,'is_interview'=>1),array('bid_id'=>$application_id,'project_id'=>$project_id));

					}elseif($formtype=='invite'){
						$inviteemails=$this->input->post('inviteemails');
						if($inviteemails){
							if(count($inviteemails)>100){
								$msg['status'] = 'FAIL';
				    			$msg['errors'][$i]['id'] = 'emails';
								$msg['errors'][$i]['message'] = 'Maximum limit 100';
				   				$i++;
							}
						}else{
							$msg['status'] = 'FAIL';
							$msg['errors'][$i]['id'] = 'emails';
							$msg['errors'][$i]['message'] = 'Please enter  email id';
							$i++;
						}
						if($i==0){
							$template="project-invitation";
							$existingfreelancer=array();
							$allfreelancer=$this->db->select('m.member_id,m.member_name,m.member_email')->where('m.is_employer',0)->where_in('m.member_email',$inviteemails)->from('member as m')->group_by('m.member_id')->get()->result();
							if($allfreelancer){
								foreach($allfreelancer as $f=>$freelancer){
									$RECEIVER_NAME=$freelancer->member_name;
									$data_parse = array(
										'SENDER_NAME' =>$SENDER_NAME,
										'RECEIVER_NAME' =>$RECEIVER_NAME,
										'TITLE' =>$TITLE,
										'URL' =>$URL,
									);
									$this->notification_model->log(
										$template, // template key
										$data_parse, // template data
										$notification_URL, // link (without base_url)
										$freelancer->member_id, // notification to,
										$this->member_id // notification_from
									);
									$existingfreelancer[$freelancer->member_email]=$freelancer;
								}
							}
							//print_r($allfreelancer);
							foreach($inviteemails as $to){
								$RECEIVER_NAME='Guest';
								$user_id=NULL;
								if(array_key_exists($to,$existingfreelancer)){
									$user_id=$existingfreelancer[$to]->member_id;
									$RECEIVER_NAME=$existingfreelancer[$to]->member_name;
								}
								$data_parse = array(
									'SENDER_NAME' =>$SENDER_NAME,
									'RECEIVER_NAME' =>$RECEIVER_NAME,
									'TITLE' =>$TITLE,
									'URL' =>$URL,
								);
								SendMail($to,$template,$data_parse);
								$project_bid_invitation=array(
									'project_id'=>$project_id,
									'invite_email'=>$to,
									'user_id'=>$user_id,
									'invite_date'=>date('Y-m-d H:i:s'),
								);
								insert_record('project_bid_invitation',$project_bid_invitation);
							}
							$msg['status'] = 'OK';
						}
						unset($_POST);
						echo json_encode($msg);
						die();
					}else{
						$up=0;	
					}
                    if($up){
                        $this->ReturnStatus=1;
                        $msg['status'] = 1;
                        $msg['data']=[];
                    }else{
                        $msg['status'] = 0;
                        $msg['message']='Error';
                    }
				}
			}
		}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}

    public function post_favorite(){
		$i=0;
		$msg=array();
		$this->form_validation->set_rules('project_id', 'project_id', 'required');
		$this->form_validation->set_rules('login_user_id', 'user', 'required');
		$this->form_validation->set_rules('action', 'action', 'required');
		
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}
		if($i==0){
            $object_id = post('project_id');
			$type = 'PROJECT';
            $member_id = post('login_user_id');
            $action = post('action');
            if($action=='add'){
                $insert = array(
                                'object_id' => $object_id,
                                'type' => $type,
                                'member_id' => $member_id,
                );
                $count = $this->db->where($insert)->count_all_results('favorite');
                if($count == 0){
                    $insert['date'] = date('Y-m-d');
                    $this->db->insert('favorite', $insert);
                }
            }else{
                $check = array(
                    'object_id' => $object_id,
                    'type' => $type,
                    'member_id' => $member_id,
                );
                $count = $this->db->where($check)->delete('favorite');
            }
            $this->ReturnStatus=1;
            $msg['data']['status']=1;
            $msg['data']['message']='Success';
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }






    /* public function get_biddetails(){
        $this->load->model('projectdetails_model');
        $project_id_get=$this->input->get('projectid');
        $bid_id_get=$this->input->get('id');
        $this->db->select("project_id,title,member_id,project_type,buget_min,buget_max");
        $data=$this->db->get_where("projects",array("project_id"=> $project_id_get))->row_array();
        if( $data){
            $project_id=$data['project_id']; 
            $employer_id=$data['member_id'];
        }else{
            $this->response(array(
                "status" => 0,
                "message" => "error"
              ) , 200);
              die;
        }
        $data['owner']=$this->projectdetails_model->getuserdetails($data['member_id']);
        $buget=$project_for_name="";
        if( $data['buget_min']== $data['buget_max']){
            $buget=CURRENCY. $data['buget_min'];
        }else if( $data['buget_min']!=0 &&  $data['buget_max']!=0){
            $buget=CURRENCY. $data['buget_min']. " To ".CURRENCY.$data['buget_max'];
        }
        else if( $data['buget_min']!=0 &&  $data['buget_max']==0){
            $buget="Over ".CURRENCY. $data['buget_min'];
        }
        else if( $data['buget_min']==0 &&  $data['buget_max']!=0){
            $buget="Less than ".CURRENCY. $data['buget_max'];
        }
        $data['budget']= $buget;

        $this->db->select("b.*");        
		$this->db->from("bids b");
        $this->db->where(array("b.project_id" => $project_id,"b.id" => $bid_id_get));
        $biddetails = $this->db->get()->row_array();
        if($biddetails){
            $question=array();
            $employer_question = $this->db->where('project_id', $project_id)->get('project_questions')->result_array();
            if(count($employer_question) > 0){
                foreach($employer_question as $qs){
                    $freelancer_answer_row = $this->db->where(array('question_id' => $qs['question_id'], 'freelancer_id' =>$biddetails['bidder_id']))->get('project_answers')->row_array();
                    $question[]=array(
                        'question_id'=>$qs['question_id'],
                        'question'=>$qs['question'],
                        'answer'=>!empty($freelancer_answer_row['answer']) ? $freelancer_answer_row['answer'] : 'Not answered'
                    );
                }
            }
            $biddetails['question']=$question;
            $biddetails['milestone']=$this->db->where(array('bid_id' => $bid_id_get))->order_by('id', 'ASC')->get('bid_milestone')->result_array();
            
            
            
           
            $attachment_url="";
            $attachments=array();
            if($biddetails['attachment']){
                $attachment_url=ASSETS.'jobbid_upload/'.$biddetails['attachment'];
                $attach=explode(',',$biddetails['attachment']);
                if($attach){
                    foreach($attach as $k=>$item){
                        $attachments[]=array(
                            'filename'=>$item,
                            'fileurl'=>ASSETS.'jobbid_upload/'.$item,
                        );
                    }
                }
            }
            //$biddetails['attachment_url']=$attachment_url;
            $biddetails['attachments']=$attachments;


            $this->load->model('dashboard/profile_model');
            $biddetails['userdetails'] = new FreelancerProfile($biddetails['bidder_id'],'fname,lname,country,city,hourly_rate,status,username,email');


            $biddetails['userdetails']=$this->db->select('member_id,fname,lname,username,hourly_rate,slogan,country')->from('user')->where('member_id',$biddetails['bidder_id'])->get()->row_array();

            $profile = new FreelancerProfile($biddetails['bidder_id']);
            $biddetails['userdetails']['logo']=$profile->getLogo();

            $biddetails['userdetails']['country_name']=$biddetails['userdetails']['country_flag']='';
            if($profile->location()->country){
                $biddetails['userdetails']['country_name']=$profile->location()->country_name;
                $biddetails['userdetails']['country_flag']=str_replace('.svg','.webp',$profile->location()->country_flag);
            }
            $biddetails['userdetails']["rating"] =$profile->rating();
            $biddetails['userdetails']['hourly_rate']=$profile->get('hourly_rate');
            $biddetails['userdetails']['completd_projects']=get_freelancer_project($biddetails['bidder_id'], 'C');
            $biddetails['userdetails']['earned_amount']=get_earned_amount($biddetails['bidder_id']);
            $biddetails['userdetails']['job_success']=round(freelancer_job_success($biddetails['bidder_id']));


        }
        $data['biddetails']= $biddetails; 

        $result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);

    }
    public function get_updatebid(){
        $data=array();
        $this->load->model('jobdetails/jobdetails_model');
        $this->load->model('dashboard/dashboard_model');
        $bid_id_get=$this->input->get('id');
        $action=$this->input->get('action');
        $this->db->select("b.*");        
		$this->db->from("bids b");
        $this->db->where(array("b.id" => $bid_id_get));
        $biddetails = $this->db->get()->row_array();
        if($biddetails){
            $project_id=$biddetails['project_id']; 
            $bidder_id=$biddetails['bidder_id'];
            $bid_id = $biddetails['id'];
        }else{
            $this->response(array(
                "status" => 0,
                "message" => "error"
              ) , 200);
              die;
        }
        
        $this->db->select("project_id,title,bidder_id,member_id,project_type,project_for,multi_freelancer,no_of_freelancer");
        $projectDetails=$this->db->get_where("projects",array("project_id"=> $project_id))->row_array();
        if($projectDetails){
            $employer_id=$projectDetails['member_id'];
            $project_type=$projectDetails['project_type'];


            if($action=='hire'){
                $this->load->model('notification/notification_model');
                $all_chosen=array();
                $user_wallet_id = get_user_wallet($employer_id);
                $acc_balance  = get_wallet_balance($user_wallet_id); 
                $all_chosen=explode(',',$projectDetails['bidder_id']);
                if($biddetails['total_amt'] >  $acc_balance){
                    $data['status']=0;
                    $data['error'] =  "You don't have sufficient balance in your wallet";
                }else{
                    $multifree=$projectDetails['multi_freelancer'];
                    $get_key_chosen=array_search($bidder_id,$all_chosen);
                    $exi=trim(implode(",",$all_chosen));
                    if($exi!='' && !$get_key_chosen && $project_type=='H' && $multifree=='Y'){
                        $alluser=trim(implode(",",$all_chosen)).",".$bidder_id;
                    }else{
                        $alluser=$bidder_id;
                    }
                    $new_data=array();
                    $new_data['bidder_id']= $alluser;
			        $new_data['status']= 'P';
                    $upd=$this->dashboard_model->updateProject($new_data,$project_id);
                    if($project_type == 'F'){
                        $bid_r=$biddetails;
                        if($bid_r){
                            $bid_id = $bid_r['id'];
                            $this->db->where(array('bid_id' => $bid_id))->update('project_milestone', array('status' => 'A', 'client_approval' => 'Y'));
                            $prev_escrow_count = $this->db->where('project_id', $project_id)->count_all_results('escrow_new');
                            if(($bid_r['enable_escrow'] == 1) && ($prev_escrow_count == 0)){
                                $member_id =$employer_id;
                                $this->load->model('myfinance/transaction_model');
                                $ref =  json_encode(array('project_id' => $project_id, 'project_type' => 'F'));
                                $new_txn_id = $this->transaction_model->add_transaction(PROJECT_PAYMENT_ESCROW,  $member_id);
                                $this->transaction_model->add_transaction_row(array('txn_id' => $new_txn_id, 'wallet_id' => $user_wallet_id, 'debit' => $bidder_amt['total_amt'], 'ref' => $ref , 'info' => 'Project payment to escrow'));
                                $this->transaction_model->add_transaction_row(array('txn_id' => $new_txn_id, 'wallet_id' => ESCROW_WALLET, 'credit' => $bidder_amt['total_amt'], 'ref' => $ref , 'info' => 'Project payment'));
                                wallet_less_fund($user_wallet_id, $bidder_amt['total_amt']);
                                wallet_add_fund(ESCROW_WALLET,$bidder_amt['total_amt']);
                                check_wallet($user_wallet_id,  $new_txn_id);
                                check_wallet(ESCROW_WALLET,  $new_txn_id);
                                $project_txn = array(
                                    'project_id' => $project_id,
                                    'txn_id' => $new_txn_id,
                                );
                                $this->db->insert('project_transaction', $project_txn);
                                $milestones = $this->db->where('bid_id', $bid_id)->get('project_milestone')->result_array();
                                if(count($milestones) > 0){
                                    foreach($milestones as $k => $v){
                                        $escrow_data = array(
                                            'milestone_id' => $v['id'],
                                            'amount' => $v['total_milestone_amount'],
                                            'bidder_amt' => $v['amount'],
                                            'admin_fee' => $v['admin_fee'],
                                            'tax_amount' => $v['tax_amount'],
                                            'status' => 'P',
                                            'project_id' => $project_id,
                                        );
                                        $this->db->insert('escrow_new', $escrow_data);
                                    }
                                }
                                $p_title = $projectDetails['title'];
                                $employer_email = getField('email', 'user', 'member_id', $employer_id);
                                $freelancer_email = getField('email', 'user', 'member_id', $bidder_id);

                                $template = 'project_fund_escrowed';
                                $to = $employer_email;
                                $data_parse = array(
                                    'name' => getField('username', 'user', 'member_id', $employer_id),
                                    'project' => $p_title,
                                );
                                send_layout_mail($template, $data_parse, $to);

                                $data_parse = array(
                                    'name' => getField('username', 'user', 'member_id', $alluser),
                                    'project' => $p_title,
                                );
                                $to = $freelancer_email;
                                send_layout_mail($template, $data_parse, $to);
                            }
                        }
                    }
                    $title=$projectDetails['title'];
                    $this->load->model('marketingcron/marketingcron_model');
                    if($project_type == 'H'){
                        $max_freelancer = $projectDetails['no_of_freelancer'];
                        $all_choosen_freelancer = explode(',', $alluser);
                        $rejected_bidders = array();
                        if(count($all_choosen_freelancer) >= $max_freelancer){
                            $all_bids_except_choosen = $this->db->select('bidder_id')->where('project_id',  $project_id)->where_not_in('bidder_id', $all_choosen_freelancer)->get('bids')->result_array();
                            if($all_bids_except_choosen){
                                foreach($all_bids_except_choosen as $k => $v){
                                    $rejected_bidders[] = $v['bidder_id'];
                                }
                            }
                        }
                        if($rejected_bidders){
                            $notification = 'Sorry! You are not hired for the project '.$title;
                            $link="jobdetails/details/".$project_id;
                            $app_url="/job/details/".$project_id;
                            $template = 'freelancer-not-hired';
                            $data_parse = array(
                                'PROJECT_NAME' => $title
                            );
                            foreach($rejected_bidders as $bidder){
                                $data_parse['MEMBER_NAME']=getField('username', 'user', 'member_id', $bidder);
                                $this->notification_model->log($employer_id, $bidder, $notification, $link,$app_url);
                                $to = $this->auto_model->getFeild('email','user','member_id',$bidder);
                                $this->marketingcron_model->insert_content_email($to,$template,$data_parse);
                                //send_layout_mail($template, $data_parse, $to);
                            }
                        }
                    }
                    else if($project_type == 'F'){
                        $rejected_bidders = array();
                        $all_bids_except_choosen = $this->db->select('bidder_id')->where('project_id',  $project_id)->where_not_in('bidder_id', $alluser)->get('bids')->result_array();
                        if($all_bids_except_choosen){
                            foreach($all_bids_except_choosen as $k => $v){
                                $rejected_bidders[] = $v['bidder_id'];
                            }
                        }
                        if($rejected_bidders){
                            $notification = 'Sorry! You are not hired for the project '.$title;
                            $link="jobdetails/details/".$project_id;
                            $app_url="/job/details/".$project_id;
                            $template = 'freelancer-not-hired';
                            $data_parse = array(
                                'PROJECT_NAME' => $title
                            );
                            foreach($rejected_bidders as $bidder){
                                $data_parse['MEMBER_NAME']=getField('username', 'user', 'member_id', $bidder);
                                $this->notification_model->log($employer_id, $bidder, $notification, $link,$app_url);
                                $to = $this->auto_model->getFeild('email','user','member_id',$bidder);
                                $this->marketingcron_model->insert_content_email($to,$template,$data_parse);
                                //send_layout_mail($template, $data_parse, $to);
                            }
                        }
                    }
                    $link=VPATH."jobdetails/details/".$project_id;
                    $from=ADMIN_EMAIL;
                    $to=$this->auto_model->getFeild('email','user','member_id',$bidder_id);
                    $username=$this->auto_model->getFeild('username','user','member_id',$bidder_id);
                    $template='select_job_notification';
                    $data_parse=array(
                        'name'=>$username,
                        'project'=>$title,
                        'copy_url'=>$link,
                        'url_link'=>$link
                    );
                    send_layout_mail($template, $data_parse, $to);
                    $post_data['from_id']=$employer_id;
                    $post_data['to_id']=$bidder_id;
                    $notification = 'Congratulations! You have been hired for the project '.$title;
                    $link = "projectroom/freelancer/overview/".$project_id;
                    $app_url="/workroom/freelancer/".$project_id;
                    $this->notification_model->log($post_data['from_id'], $post_data['to_id'], $notification, $link,$app_url);
                    if($upd){
                        $data['status']=1;
                    }
                }
            } 
            elseif($action=='hide'){
                $data['status']=1;
                $update = array(
                    'employer_interest' =>'-1'
                );
                $this->db->where(array('project_id' => $project_id, 'id' => $bid_id))->update('bids', $update);

            }
            elseif($action=='interested'){
                $data['status']=1;
                $update = array(
                    'employer_interest' =>'1'
                );
                $this->db->where(array('project_id' => $project_id, 'id' => $bid_id))->update('bids', $update);
            }  
        }
        $result['data'] = $data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);

    }
 */
    public function get_options(){
		
		$this->data=array();
		$this->data['all_duration']=getAllProjectDuration();
		$project_id=get('pid');
        $member_id=$this->member_id;
        $this->data['getBidDetails']=getData(array(
            'select'=>'bid_id,bid_amount,bid_site_fee,bid_by_project,bid_duration,bid_details,bid_attachment,is_hired',
            'table'=>'project_bids',
            'where'=>array('project_id'=>$project_id,'member_id'=>$member_id),
            'single_row'=>TRUE
        ));
        if($this->data['getBidDetails']){
            $attachment=[];
            $attach=json_decode($this->data['getBidDetails']->bid_attachment,true);
            if( $attach){
                foreach( $attach as $k=>$item){
                   
                    $attachment[]=[
                        'file_url'=>UPLOAD_HTTP_PATH.'projects-applications/'.$item['file'],
                        'file_name'=>$item['file'],
                        'original_name'=>$item['name'],
                        'is_new'=>0
                    ];
                }
            }
            $this->data['getBidDetails']->bid_attachment= $attachment;
            $bid_id=$this->data['getBidDetails']->bid_id;
            if($this->data['getBidDetails']->bid_by_project!=1){
                $arr=array(
                        'select'=>'p_b_m.bid_milestone_id,p_b_m.bid_milestone_title,p_b_m.bid_milestone_due_date,p_b_m.bid_milestone_amount',
                        'table'=>'project_bid_milestones as p_b_m',
                        'where'=>array('p_b_m.bid_id'=>$bid_id),
                    );
                $this->data['getBidDetails']->milestone=getData($arr);
            }
        }
        $arr=array(
            'select'=>'q.question_id,q.question_title,a.question_answer',
            'table'=>'project_question as p_q',
            'join'=>array(
                array('table'=>'question as q','on'=>'p_q.question_id=q.question_id','position'=>'left'),
                array('table'=>'project_bid_answer as a','on'=>"(q.question_id=a.question_id and a.bid_id='".$bid_id."')",'position'=>'left'),
            ),
            'where'=>array('p_q.project_id'=>$project_id,'p_q.project_question_status'=>1),
            'order'=>array(array('p_q.project_question_id','asc'))
        );
        
        $this->data['limit_over']=1;
        $membership=getMembershipData($member_id,array('bid'));
        if($membership['max_bid'] > $membership['used_bid']){
            $this->data['limit_over']=0;
        }
        $this->data['project_question']=getData($arr);
        $this->data['project_is_cover_required']=getFieldData('project_is_cover_required','project_additional','project_id',$pid);

		$result['data'] = $this->data;
		$result['status'] = 1;
		$this->ReturnStatus=1;
	
		$this->ResponseData=$result;
		
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , 
		$this->ReturnCode);
	}
    public function post_applybid(){
        $this->load->library('form_validation');
		$i=0;
		$msg=array();
        $member_id=$this->member_id;
        $organization_id=$this->organization_id;
        $pid=post('pid');
        $is_edited=0;
        $bid_id=0;
        $this->form_validation->set_rules('pid', 'pid', 'required|trim|xss_clean|is_numeric');
        if($pid){
            $project_is_cover_required=getFieldData('project_is_cover_required','project_additional','project_id',$pid);
            $is_hourly=getFieldData('is_hourly','project_settings','project_id',$pid);
            $all_milestone=array();
            $getBidDetails=getData(array(
                'select'=>'bid_id,is_hired',
                'table'=>'project_bids',
                'where'=>array('project_id'=>$pid,'member_id'=>$member_id),
                'single_row'=>TRUE
            ));
            if($getBidDetails){
                $is_edited=1;
                $bid_id=$getBidDetails->bid_id;
                if($getBidDetails->is_hired){
                    $msg['status'] = 'FAIL';
                    $msg['errors'][$i]['id'] = 'error';
                    $msg['errors'][$i]['message'] = 'Invalid ';
                    $i++;
                    $this->ResponseData=$msg;
                    $this->response(array(
                        "status" =>$this->ReturnStatus,
                        "response" =>$this->ResponseData
                    ) , $this->ReturnCode);
                    die;
                }
            }
       
            if($is_hourly==1){
                $this->form_validation->set_rules('bid_amount', 'amount', 'required|trim|xss_clean|is_numeric');
            }else{
                $this->form_validation->set_rules('bid_by_project', 'bid_by_project', 'required|trim|xss_clean');
                if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
                            
                }else{
                    $this->form_validation->set_rules('milestone_data_json', 'milestone', 'required|trim|xss_clean');
                   
                   
                    if($this->input->post('milestone_data_json')){
                        $all_milestones_data=json_decode($this->input->post('milestone_data_json'),true);
                        foreach($all_milestones_data as $k=>$row){
                            if(!$row['milestone_title']){
                                $msg['status'] = 'FAIL';
                                $msg['errors'][$i]['id'] = 'milestone_title_'.($k+1);
                                $msg['errors'][$i]['message'] = 'milestone title required';
                                $i++;
                            }
                            if(!$row['milestone_amount'] || $row['milestone_amount'] <0){
                                $msg['status'] = 'FAIL';
                                $msg['errors'][$i]['id'] = 'milestone_amount_'.($k+1);
                                $msg['errors'][$i]['message'] = 'milestone amount required';
                                $i++;
                            }
                       
                            $milestone=array(
                            'title'=>$row['milestone_title'],
                            'due_date'=>$row['milestone_due_date'],
                            'amount'=>$row['milestone_amount'],
                            );
                            $all_milestone[]=$milestone;
                        }	
                        
                    }
                }
                if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
                    $this->form_validation->set_rules('bid_amount', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
                }
                $this->form_validation->set_rules('bid_duration', 'duration', 'required|trim|xss_clean');
            }
            if($project_is_cover_required){
                $this->form_validation->set_rules('bid_details', 'details', 'required|trim|xss_clean');	
            }
        }
        
		
		if($this->form_validation->run( )== FALSE){
			$error=validation_errors_array();
			if($error){
				foreach($error as $key=>$val){
					$msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = $key;
					$msg['errors'][$i]['message'] = $val;
	   				$i++;
				}
			}
		}
        if($i==0){
            $is_email_verified=getFieldData('is_email_verified','member','member_id',$this->member_id);
            if($is_email_verified){
                $is_doc_verified=getFieldData('is_doc_verified','member','member_id',$this->member_id);
                if(!$is_doc_verified){
                    $msg['status'] = 'FAIL';
	    			$msg['errors'][$i]['id'] = 'is_doc_verified';
					$msg['errors'][$i]['message'] = 'document not verified';
	   				$i++;
                }
            }else{
                $msg['status'] = 'FAIL';
                $msg['errors'][$i]['id'] = 'is_email_verified';
                $msg['errors'][$i]['message'] = 'email not verified';
                $i++;
            }
        }
       
		if($i==0){
            $bid_site_fee=getSiteCommissionFee($member_id);
            $bid_amount=0;
            $project_bids=array(
                'member_id'=>$member_id,
                'organization_id'=>$organization_id,
                'project_id'=>$pid,
                'bid_amount'=>$bid_amount,
                'bid_site_fee'=>$bid_site_fee,
                'bid_by_project'=>0,
                'bid_duration'=>NULL,
                'bid_details'=>NULL,
                'bid_attachment'=>NULL,
                'bid_date'=>date('Y-m-d H:i:s'),
            );
            if($this->input->post('bid_by_project') && $this->input->post('bid_by_project')==1){
                $project_bids['bid_by_project']=1;
            }
            if($this->input->post('bid_details')){
                $project_bids['bid_details']=post('bid_details');
            }
            if($is_hourly==1){
                $bid_amount=post('bid_amount');
            }else{
                if($project_bids['bid_by_project']==1){
                    $bid_amount=post('bid_amount');
                }else{
                    if($all_milestone){
                        foreach($all_milestone as $mv){
                            $bid_amount=$bid_amount+$mv['amount'];
                        }
                    }
                }
                $project_bids['bid_duration']=post('bid_duration');
            }
            $project_bids['bid_amount']=$bid_amount;
            $attahment=array();
            if(post('projectfile')){
                $projectfiles=json_decode(post('projectfile'));
                foreach($projectfiles as $file){
                    $file_data=$file;
                    if($file_data){

                        if($file_data->file_name && $file_data->is_new && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
                            rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."projects-files/projects-applications/".$file_data->file_name);
                            $attahment[]=array(
                            'name'=>$file_data->original_name,
                            'file'=>$file_data->file_name,
                            );
                        }else{
                            $attahment[]=array(
                                'name'=>$file_data->original_name,
                                'file'=>$file_data->file_name,
                                );
                        }
                    }
                }
            }
            if($attahment){
                $project_bids['bid_attachment']=json_encode($attahment);
            }
            if($is_edited==1){
                updateTable('project_bids',$project_bids,array('bid_id'=>$bid_id));
            }else{
                $bid_id=insert_record('project_bids',$project_bids,TRUE);
            }
            if($bid_id){
                $this->ReturnStatus=1;
                $this->db->where('bid_id',$bid_id)->delete('project_bid_milestones');
                if($project_bids['bid_by_project']!=1){
                    if($all_milestone){
                        foreach($all_milestone as $milestone){
                            $project_bid_milestones=array(
                            'bid_id'=>$bid_id,
                            'bid_milestone_title'=>$milestone['title'],
                            'bid_milestone_due_date'=>$milestone['due_date'],
                            'bid_milestone_amount'=>$milestone['amount'],
                            'bid_milestone_id'=>time(),
                            );
                            insert_record('project_bid_milestones',$project_bid_milestones);
                            
                        }
                    }
                }
                $this->db->where('bid_id',$bid_id)->delete('project_bid_answer');
                if($this->input->post('question_data_json')){
                    $question_data=json_decode(post('question_data_json'));
                    foreach($question_data as $k=>$question){
                        if($question->answer){
                            $project_bid_answer=array(
                            'bid_id'=>$bid_id,
                            'question_id'=>$question->question_id,
                            'question_answer'=>$question->answer,
                            );
                            insert_record('project_bid_answer',$project_bid_answer);
                        }
                    }
                }
                $msg['data']=[
                    'pid'=>$pid,
                    'bid_id'=>$bid_id,
                ];
            }
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }
    

}