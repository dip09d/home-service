<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}else{
			redirect(URL::get_link('loginURL').'?ref=dashboardURL');
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		$this->load->model('dashboard_model');
		parent::__construct();
	}
	public function index()
	{
		if($this->loggedUser){
			$wallet_id=0;
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'mycustom.js',
				'chart.min.js',
			));
			$this->data['currency']=priceSymbol();
			$this->data['is_email_verified']=getFieldData('is_email_verified','member','member_id',$this->member_id);
			$this->data['is_doc_verified']=getFieldData('is_doc_verified','member','member_id',$this->member_id);

			$limit = 0;
			$offset = 10;
			$this->load->model('notification/notification_model','notification_model');
			$this->data['notification_list'] = $this->notification_model->getNotificationList($this->member_id,$limit, $offset);
			if($this->access_member_type=='F'){
				$srch['contractor_id'] = $this->member_id;
			}else{
				$srch['owner_id'] = $this->member_id;
			}
			$srch['contract_status'] = 1;
			$this->load->model('contract/contract_model','contract_model');
			$this->data['contract_list'] = $this->contract_model->getContracts($srch, $limit, $offset);
			$this->data['member_wallet']=getWalletMember($this->member_id);
			if($this->data['member_wallet']){
				$wallet_id=$this->data['member_wallet']->wallet_id;
				$balance=$this->data['member_wallet']->balance;
			}

			if($this->access_member_type=='F'){
				$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
				$layout_tempate='freelancer';
				$memberDataBasic=getData(array(
					'select'=>'m.member_name,m_s.avg_rating,m_s.total_earning,m_s.no_of_reviews,m_s.total_working_hour,m_s.success_rate',
					'table'=>'member as m',
					'join'=>array(array('table'=>'member_statistics m_s','on'=>'m.member_id=m_s.member_id','position'=>'left'),array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>'c.country_code=c_n.country_code','position'=>'left'),array('table'=>'member_logo as m_l','on'=>'(m.member_id=m_l.member_id and m_l.status=1)','position'=>'left'),),
					'where'=>array('m.member_id'=>$this->member_id),
					'single_row'=>true,
				));	
				$this->data['memberInfo']=$memberDataBasic;
				$this->data['memberInfo']->total_jobs=$this->db->where(array('c.contractor_id'=>$this->member_id,'c.contract_status'=>1))->from('project_contract as c')->count_all_results();
				$this->data['memberInfo']->total_offer=$this->db->where(array('c.contractor_id'=>$this->member_id))->from('project_contract as c')->count_all_results();
				$this->data['bid_invitation_list']=$this->dashboard_model->getBidInvitation($srch, $limit, $offset);
				$srch['contract_status'] = 0;
				$this->data['offer_invitation_list']=$this->contract_model->getContracts($srch, $limit, $offset);
				$this->data['line_chart_earning']=$this->dashboard_model->getEarningGraph($wallet_id);
				$this->data['pie_chart_project']=$this->dashboard_model->getprojectGraphFreelancer($this->member_id);
			}else{
				$this->data['is_doc_verified']=1;
				$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
				$layout_tempate='employer';
				$memberDataBasic=getData(array(
					'select'=>'m.member_name,m_s.avg_rating,m_s.total_spent,m_s.no_of_reviews',
					'table'=>'member as m',
					'join'=>array(array('table'=>'member_statistics m_s','on'=>'m.member_id=m_s.member_id','position'=>'left'),array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>'c.country_code=c_n.country_code','position'=>'left'),array('table'=>'member_logo as m_l','on'=>'(m.member_id=m_l.member_id and m_l.status=1)','position'=>'left'),),
					'where'=>array('m.member_id'=>$this->member_id),
					'single_row'=>true,
				));	
				$this->data['memberInfo']=$memberDataBasic;
				$srch=array();
				$srch['owner_id'] = $this->member_id;
				$srch['contract_status'] = 1;
				$srch['show'] = 'pending';
				$this->data['memberInfo']->open_contract=$this->contract_model->getContracts($srch, '','',false);
				$srch['show'] = 'completed';
				$this->data['memberInfo']->complete_contract=$this->contract_model->getContracts($srch, '','',false);

				$this->data['line_chart_spent']=$this->dashboard_model->getSpentGraph($wallet_id);
				$this->data['pie_chart_project']=$this->dashboard_model->getprojectGraphEmployer($this->member_id);
			}
			$this->layout->view('dashboard-'.$layout_tempate, $this->data);
		}
	}
	public function verifydocument()
	{
		if($this->loggedUser){
			$this->layout->set_js(array(
				'utils/helper.js',
				'bootbox_custom.js',
				'upload-drag-file.js',
				'mycustom.js',
			));
			$this->data['is_email_verified']=getFieldData('is_email_verified','member','member_id',$this->member_id);
			$this->data['is_doc_verified']=getFieldData('is_doc_verified','member','member_id',$this->member_id);
			if($this->data['is_doc_verified']!=1){
				$this->data['last_application']=$this->db->where('member_id',$this->member_id)->order_by('document_id',"desc")->get('member_document_application')->row();
			}
			if($this->access_member_type=='F'){
				$this->data['left_panel']=$this->layout->view('inc/freelancer-setting-left',$this->data,TRUE,TRUE);
			}else{
				$this->data['left_panel']=$this->layout->view('inc/client-setting-left',$this->data,TRUE,TRUE);
			}
			$this->layout->view('verify-document', $this->data);
		}
	}
	public function verifydocumentCheckAjax(){
		$this->load->library('form_validation');
		checkrequestajax();
		$i=0;
		$msg=array();

		$member_id=$this->member_id;
		if($member_id){
			if($this->input->post()){
				$this->form_validation->set_rules('id_proof_name', 'ID Proff', 'required|trim|xss_clean');
				$this->form_validation->set_rules('address_proof_name', 'address Proff', 'required|trim|xss_clean');
				$this->form_validation->set_rules('projectfile_uploadfile1', 'file', 'required|trim|xss_clean');
				$this->form_validation->set_rules('projectfile_uploadfile2', 'file', 'required|trim|xss_clean');
				if ($this->form_validation->run() == FALSE){
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
					updateTable('member_document_application',array('document_status'=>2,'reject_reason'=>'Auto close by user'),array('member_id'=>$member_id,'document_status <>'=>2));
					$member_document_application=array(
						'member_id'=>$member_id,
						'document_status'=>0,
						'document_data'=>NULL,
						'document_date'=>date('Y-m-d H:i:s'),
					);
					if(post('projectfile_uploadfile1')){
						$file=post('projectfile_uploadfile1');
						$file_data=json_decode($file);
						if($file_data){
							if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
								rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."verification-documents/".$file_data->file_name);
								$attahment[]=array(
								'name'=>$file_data->original_name,
								'file'=>$file_data->file_name,
								'title'=>'ID Proff',
								'type'=>post('id_proof_name'),
								);
							}
						}
					}
					if(post('projectfile_uploadfile2')){
						$file=post('projectfile_uploadfile2');
						$file_data=json_decode($file);
						if($file_data){
							if($file_data->file_name && file_exists(TMP_UPLOAD_PATH.$file_data->file_name)){
								rename(TMP_UPLOAD_PATH.$file_data->file_name, UPLOAD_PATH."verification-documents/".$file_data->file_name);
								$attahment[]=array(
								'name'=>$file_data->original_name,
								'file'=>$file_data->file_name,
								'title'=>'Address Proff',
								'type'=>post('address_proof_name'),
								);
							}
						}
					}
					if($attahment){
						$member_document_application['document_data']=json_encode($attahment);
					}
					$document_id=insert_record('member_document_application',$member_document_application,TRUE);
					if($document_id){
						$msg['status'] = 'OK';
						$RECEIVER_EMAIL=get_setting('admin_email');
						$data_parse=array(
						'MEMBER_NAME'=>getFieldData('member_name','member','member_id',$member_id),
						'DOCUMENT_URL'=>ADMIN_URL.'document/list_record',
						);
						$template='document-verification-request';
						SendMail($RECEIVER_EMAIL,$template,$data_parse);
						
						$this->admin_notification_model->parse('admin-document-verification', array('MEMBER_NAME'=>$data_parse['MEMBER_NAME']), 'document/list_record?ID='.$document_id);
					}
				}
			}
		}else{
			$msg['status'] = 'FAIL';
			$msg['errors'][$i]['id'] = 'error';
			$msg['errors'][$i]['message'] = 'Invalid ';
			$i++;
		}
		unset($_POST);
		echo json_encode($msg);	
	}
	
}
