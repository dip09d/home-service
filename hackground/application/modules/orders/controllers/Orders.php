<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MX_Controller {
   
   private $data;
   
	public function __construct(){
		$this->data['curr_controller'] = $this->router->fetch_class()."/";
		$this->data['curr_method'] = $this->router->fetch_method()."/";
		$this->load->model('order_model', 'order');
		$this->data['table'] = 'orders';
		$this->data['primary_key'] = 'order_id';
		parent::__construct();
		
		admin_log_check();
	}

	public function index(){
		redirect(base_url($this->data['curr_controller'].'list_record'));
	}
	
	public function list_record(){
		$srch = get();
		$curr_limit = get('per_page');
		$limit = !empty($curr_limit) ? $curr_limit : 0; 
		$offset = 20;
		$this->data['main_title'] = 'Order Management';
		$this->data['second_title'] = 'All Order List';
		$this->data['title'] = 'Order';
		$breadcrumb = array(
			array(
				'name' => 'Order',
				'path' => '',
			),
		);
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		$this->data['list'] = $this->order->getList($srch, $limit, $offset);
		$this->data['list_total'] = $this->order->getList($srch, $limit, $offset, FALSE);
		
		$this->load->library('pagination');
		$config['base_url'] = base_url($this->data['curr_controller'].'list_record');
		$config['total_rows'] =$this->data['list_total'];
		$config['per_page'] = $offset;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$this->pagination->initialize($config);
		
		$this->data['links'] = $this->pagination->create_links();
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('list', $this->data);
       
	}
	public function order_detail($order_id=''){
		if(!$order_id){
			show_404(); return;
		}
		$srch = get();
		$srch['order_id'] = $order_id;
		$this->data['orderDetails']=$this->order->getOrderDetail($order_id);
		$this->data['orderDetails']->extra=$this->db->select('o.name,o.price')->where('o.order_id',$order_id)->from('orders_extras as o')->get()->result();
		$this->data['orderDetails']->buyer=$this->db->select('member_name')->where('member_id',$this->data['orderDetails']->buyer_id)->from('member')->get()->row();
		$this->data['orderDetails']->seller=$this->db->select('member_name')->where('member_id',$this->data['orderDetails']->seller_id)->from('member')->get()->row();
		$this->data['orderDetails']->seller_user_name=$this->order->getUserName($this->data['orderDetails']->seller_id);
		$this->data['orderDetails']->buyer_user_name=$this->order->getUserName($this->data['orderDetails']->buyer_id);
		
		
		
		$this->data['orderDetails']->conversation=$this->db->select('o.sender_id,o.message,o.file,o.date,o.reason,o.status')->from('orders_conversations as o')->where('o.order_id',$order_id)->get()->result();
		
		
		$this->data['main_title'] = 'Order details of ';
		$this->data['second_title'] = $this->data['orderDetails']->order_number;
		$this->data['title'] = 'Order details';
		
		$breadcrumb = array(
			array(
				'name' => 'Orders',
				'path' => base_url($this->data['curr_controller'].'list_record'),
			),
			array(
				'name' => $this->data['orderDetails']->order_number,
				'path' => '',
			),
		);
		
		$this->data['breadcrumb'] = breadcrumb($breadcrumb);
		//$this->data['list'] = $this->wallet->getTxnDetail($srch, $limit, $offset);
		
		
		$this->data['add_command'] = null;
		$this->data['edit_command'] = null;
		$this->layout->view('order_detail', $this->data);
       
	}
	
	public function load_ajax_page(){
		$page = get('page');
		$this->data['page'] = $page;
		if($page == 'add'){
			$this->data['title'] = 'Add Test Three';
			$this->data['form_action'] = base_url($this->data['curr_controller'].'add');
		}else if($page == 'edit'){
			$id = get('id');
			$this->data['ID']= $id;
			$this->data['form_action'] = base_url($this->data['curr_controller'].'edit');
			$this->data['detail'] = $this->order->getDetail($id);
			$this->data['title'] = 'Edit Test Three';
		}
		$this->load->view('ajax_page_global', $this->data);
	}
	
	public function add(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			if($this->form_validation->run()){
				$post = post();
				$insert = $this->order->addRecord($post);
				if(post('add_more') && post('add_more') == '1'){
					$this->api->cmd('reset_form');
				}else{
					$this->api->cmd('reload');
				}
				
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function edit(){
		if(post() && $this->input->is_ajax_request()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[100]');
			$this->form_validation->set_rules('status', 'status', '');
			$this->form_validation->set_rules('ID', 'id', 'required');
			if($this->form_validation->run()){
				$post = post();
				$ID = post('ID');
				unset($post['ID']);
				$update = $this->order->updateRecord($post, $ID);
				$this->api->cmd('reload');
			}else{
				$errors = validation_errors_array();
				$this->api->set_error($errors);
			}
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function change_status(){
		if(post() && $this->input->is_ajax_request()){
			
			$ID = post('ID');
			$sts = post('status');
			$action_type = post('action_type');
			
			if(is_array($ID)){
				$this->db->where_in($this->data['primary_key'], $ID)->update($this->data['table'], array('status' => $sts));
			}else{
				$orderdetails=$this->db->select('o.order_id,o.seller_id,o.buyer_id,o.order_status,o.order_price,o.proposal_id')->where('o.order_id',$ID)->where('o.order_status <>',$sts)->from('orders as o')->get()->row();
				
				$upd['data'] = array('order_status' => $sts);
				$upd['where'] = array($this->data['primary_key'] => $ID);
				$upd['table'] = $this->data['table'];
				update($upd);
				
				
				if($orderdetails){
					$conversations=array(
					'order_id'=>$ID,
					'sender_id'=>0,
					'message'=>'',
					'date'=>date('Y-m-d H:i:s'),
					'reason'=>'',
					'status'=>'cancelled_by_customer_support',
					);
					$this->db->insert('orders_conversations',$conversations);
					
					$upd['data'] = array('status' => '2');
					$upd['where'] = array($this->data['primary_key'] => $ID);
					$upd['table'] = 'proposals_referrals';
					update($upd);
					
					$total=$orderdetails->order_price;
					$order_id=$ID;
					$seller_details=$this->db->select('m.member_name,m.member_email,w.wallet_id,w.balance')->from('member as m')->join('wallet as w','m.member_id=w.user_id','left')->where('m.member_id',$orderdetails->seller_id)->get()->row();
					$buyer_details=$this->db->select('m.member_name,m.member_email,w.wallet_id,w.balance')->from('member as m')->join('wallet as w','m.member_id=w.user_id','left')->where('m.member_id',$orderdetails->buyer_id)->get()->row();
					$reciver_wallet_id=$buyer_wallet_id=$buyer_details->wallet_id;
					$reciver_wallet_balance=$buyer_wallet_balance=$buyer_details->balance;
					$recipient_relational_data=get_setting('website_name');
					$site_wallet=get_setting('SITE_MAIN_WALLET');
					$site_details=$this->db->select('w.wallet_id,w.balance')->from('wallet as w')->where('w.wallet_id',$site_wallet)->get()->row();
					$sender_wallet_id=$site_details->wallet_id;
					$sender_wallet_balance=$site_details->balance;
					
					$wallet_transaction_type_id=get_setting('ORDER_PAYMENT_REFUND');
					$current_datetime=date('Y-m-d H:i:s');
					
					$ins=array();
					$ins['data'] = array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>1,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime);
					$ins['table'] = 'wallet_transaction';
					$wallet_transaction_id = insert($ins, TRUE);
					if($wallet_transaction_id){
						insert(array('data'=>array('order_id'=>$order_id,'transaction_id'=>$wallet_transaction_id),'table'=>'orders_transaction'));
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$sender_wallet_id,'debit'=>$total,'description_tkey'=>'OrderID','relational_data'=>$order_id);
						insert(array('data'=>$insert_wallet_transaction_row,'table'=>'wallet_transaction_row'));
						
						$insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$reciver_wallet_id,'credit'=>$total,'description_tkey'=>'Transfer_from','relational_data'=>$recipient_relational_data);
						insert(array('data'=>$insert_wallet_transaction_row,'table'=>'wallet_transaction_row'));
	
						$this->db->set('used_purchases','used_purchases-'.$total,FALSE)->where('wallet_id',$reciver_wallet_id)->update('wallet');

						$this->load->model('wallet/wallet_model', 'wallet');
						$total_debit = $this->wallet->wallet_debit_balance($sender_wallet_id);
						$total_credit = $this->wallet->wallet_credit_balance($sender_wallet_id);
						$org_balance = $total_credit - $total_debit;
						update_wallet_balance($sender_wallet_id, $org_balance);
						
						$total_debit = $this->wallet->wallet_debit_balance($reciver_wallet_id);
						$total_credit = $this->wallet->wallet_credit_balance($reciver_wallet_id);
						$org_balance = $total_credit - $total_debit;
						update_wallet_balance($reciver_wallet_id, $org_balance);
						
						
						$this->load->model('notifications/notification_model');
						$notificationData=array(
						'sender_id'=>0,
						'receiver_id'=>$orderdetails->seller_id,
						'template'=>'cancelled_by_customer_support',
						'url'=>'order-details/'.$ID,
						'content'=>json_encode(array('OID'=>$ID)),
						);
						$this->notification_model->savenotification($notificationData);
						$notificationData['receiver_id']=$orderdetails->buyer_id;
						$this->notification_model->savenotification($notificationData);
					
						$url=URL.'order-details/'.$order_id;
						$RECEIVER_EMAIL=$seller_details->member_email;
						$template='order-cancelled-to-seller';
						$data_parse=array(
						'SELLER_NAME'=>$seller_details->member_name,
						'ORDER_DETAILS_URL'=>$url,
						);
						SendMail($RECEIVER_EMAIL,$template,$data_parse);
						
						$RECEIVER_EMAIL=$buyer_details->member_email;
						$template='order-cancelled-to-buyer';
						$data_parse=array(
						'BUYER_NAME'=>$buyer_details->member_name,
						'ORDER_DETAILS_URL'=>$url,
						);
						SendMail($RECEIVER_EMAIL,$template,$data_parse);
					}	
				}	
			}
			
			if($action_type == 'multiple'){
				$this->api->cmd('reload');
			}else{
				$this->api->cmd('reload');
			}
			
			
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		
		$this->api->out();
	}
	
	public function delete_record($id=''){
		$action_type = post('action_type');
		if($action_type == 'multiple'){
			$id = post('ID');
		}
		if($id){
			$this->order->deleteRecord($id);
			$cmd = get('cmd');
			if($cmd && $cmd == 'remove'){
				if($id && is_array($id)){
					$this->db->where_in($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}else{
					$this->db->where($this->data['primary_key'] ,  $id)->delete($this->data['table']);
				}
				
			}
			$this->api->cmd('reload');
		}else{
			$this->api->set_error('invalid_request', 'Invalid Request');
		}
		$this->api->out();
	}
}





