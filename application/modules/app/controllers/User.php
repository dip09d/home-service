<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("access-control-allow-origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header('content-type: application/json; charset=utf-8');

require APPPATH.'libraries/MX_Rest.php';
class User extends MX_Rest{
	protected $worker_id;
	protected $member_id;
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
            // $is_employer=$this->auto_model->getFeild('is_employer','member','member_id',$this->member_id);
			// $this->account_type=($is_employer==1?'E':'F');
			$result[]=(object)[
				'member_id'=>$this->member_id,
				// 'account_type'=>$this->account_type,
			];
			$this->session->set_userdata('user', $result);
		}
		if($this->input->get('worker_id') && $this->input->get('worker_id')>0){
			$this->worker_id=$this->input->get('worker_id');
			$result[]=(object)[
				'worker_id'=>$this->worker_id,
			];
			$this->session->set_userdata('provider', $result);
		}
		$this->load->library('form_validation');
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
    public function get_notification(){
        $this->load->model('notification/notification_model','notification_model');
        $srch = get();
        $srch['member_id']=$this->member_id;
        $offset = 10;
        $page=(get('page')? get('page'):1);
        $limit=($page-1)*$offset;
        $all=$this->notification_model->getNotificationList($this->member_id, $limit, $offset);
        if($all){
            foreach($all as $k=>$row){
                $row->template_data=json_decode($row->template_data);
                $row->logo=getMemberLogo($row->notification_from);
                $all[$k]=$row;
            }
        }
        $data = array(
            'list' => $all,
            'total' =>  $this->notification_model->getNotificationList($this->member_id, $limit, $offset, FALSE),
        );
        $data['total_page']=ceil($data['total']/$offset);
        $data['current_page']=$page;
        
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
    public function get_reviews(){
        $this->load->model('reviews/reviews_model','reviews_model');
        $srch = get();
        $srch['member_id']=$this->member_id;
        $offset = 10;
        $page=(get('page')? get('page'):1);
        $limit=($page-1)*$offset;
        $all=$this->reviews_model->getreviews($srch,$limit,$offset);
        if($all){
            foreach($all as $k=>$row){
                $row->logo=getMemberLogo($row->member_id);
                $all[$k]=$row;
            }
        }
        $data = array(
            'list' => $all,
            'total' =>  $this->reviews_model->getreviews($srch, $limit, $offset, FALSE),
        );
        $data['total_page']=ceil($data['total']/$offset);
        $data['current_page']=$page;
        
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
    public function get_review_details(){
        $this->load->model('reviews/reviews_model','reviews_model');
        $review_id=get('review_id');
        $contract_id=getFieldData('contract_id','contract_reviews','','',array('review_id'=>$review_id));
		
		if($contract_id){
			$contract_id_enc=md5($contract_id);
			$data['reviews']=get_contract_view($contract_id,$this->member_id);
			$data['contractDetails'] = get_contract_details($contract_id_enc,array('data_from'=>'contract_term','member_id'=>$this->member_id));
			
			$project_id=$data['contractDetails']->project_id;

			$owner=getProjectDetails($project_id,array('project_owner'));
			$data['contractDetails']->owner=$owner['project_owner'];
			$data['contractDetails']->contractor=getData(array(
				'select'=>'m.member_id,m.member_name',
				'table'=>'member m',
				'where'=>array('m.member_id'=>$data['contractDetails']->contractor_id),
				'single_row'=>true
			));

			$data['is_owner']=0;
			if($data['contractDetails']->owner_id==$this->member_id){
				$data['is_owner']=1;
			}
			$data['show_client_review']=$data['show_freelancer_review']=0;
			if($data['reviews']){
				if($data['reviews']['review_by_me']){
					$data['show_client_review']=1;
					$data['show_freelancer_review']=1;
				}else{
					if($data['is_owner']==1){
						$data['show_client_review']=1;
					}else{
						$data['show_freelancer_review']=1;
					}
				}
				if($data['is_owner']==1){
					$data['review_by_client']=$data['reviews']['review_by_me'];
					$data['review_by_freelancer']=$data['reviews']['review_to_me'];
				}else{
					$data['review_by_client']=$data['reviews']['review_to_me'];
					$data['review_by_freelancer']=$data['reviews']['review_by_me'];
				}

			}
            $result['data'] = $data;
            $result['status'] = 1;
            $this->ReturnStatus=1;
		}else{
            $result['status'] = 0;
            $result['message'] = 'Invalid Id';
        }
      
    
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
    }
    public function get_transaction_history(){
        $this->load->model('finance/finance_model', 'finance_model');
        $srch = get();
        $srch['member_id']=$this->member_id;
        $offset = 10;
        $page=(get('page')? get('page'):1);
        $limit=($page-1)*$offset;
        $balance = 0;
        $wallet_id = 0;
        $data['member_wallet']=getWalletMember($this->member_id);
		if($data['member_wallet']){
			$wallet_id=$data['member_wallet']->wallet_id;
			$balance=$data['member_wallet']->balance;
		}
        $data['current_balance']=$balance;
		$data['total_debit']=$this->finance_model->wallet_debit_balance($wallet_id);
		$data['total_credit']=$this->finance_model->wallet_credit_balance($wallet_id);
       
		$srch['wallet_id'] = $wallet_id; 
		
        $all= $this->finance_model->getTransaction($srch, $limit, $offset);;
         /* if($all){
            foreach($all as $k=>$row){
               
               
            }
        } */
        $data = array(
            'list' => $all,
            'total' => $this->finance_model->getTransaction($srch, $limit, $offset, FALSE),
        );
     
        $data['total_page']=ceil($data['total']/$offset);
        $data['current_page']=$page;
        
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

    public function post_add_device(){
        $result=[];
		$device_type=post('device_type');
		$device_token=post('device_token');
        $member_id=$worker_id=0;
        if($this->input->get('member_id')){
            $member_id=$this->input->get('member_id');
        }elseif($this->input->get('worker_id')){
            $worker_id=$this->input->get('worker_id');
        }
		
		$device_tokenData=[];
		$this->db->where('device_type',$device_type)->where('device_token',$device_token)->delete('member_device');
        $member_device=array(
            'member_id'=>$member_id,
            'worker_id'=>$worker_id,
            'reg_date'=>date('Y-m-d H:i:s'),
            'device_type'=>$device_type,
            'device_token'=>$device_token,
        );
        $this->db->insert('member_device',$member_device);
        $result['data']=$member_device;
        $result['status'] = 1;
        $this->ReturnStatus=1;
		
      
    
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);

	}


    public function post_payment_cashfree(){
        $result=[];
		$this->data=[];
		$worker_id=$this->worker_id;
		$amount=$this->input->post('amount');
		
        
        $unique_id=$this->worker_id.'-'.time();	
        $this->data['formdata']=array(
            'amount'=>$amount,
            'org_amt'=>$amount,
            'fee'=>0,
            //'return_url'=>base_url('app/user/payment_success/'.$this->worker_id),
            //'cancel_url'=>base_url('app/user/payment_failed/'.$this->worker_id),
            //'notify_url'=>get_link('PaypalNotify').'addfund/'.$unique_id,
            'custom'=>md5('PPAY-'.$unique_id),
            'worker_id'=>$this->worker_id,
        );
        $transansaction_data=array('payment_type'=>'CASHFREE','content_key'=> $this->data['formdata']['custom']);

        $this->data['formdata']['amount_converted']=$amount;
        $transansaction_data['request_value']=json_encode( $this->data['formdata']);
        insert_record('online_transaction_data',$transansaction_data);

        $url='https://api.cashfree.com/pg/orders';
        $is_sandbox=get_setting('is_sandbox');
		if($is_sandbox){
            $url='https://sandbox.cashfree.com/pg/orders';
        }
        $customer_id='W'.$this->worker_id;
        $customer_phone=getFieldData('worker_phone','worker','worker_id',$this->worker_id);;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'order_currency' => 'INR',
                'order_amount' => $amount,
                'customer_details' => [
                    'customer_id' => $customer_id,
                    'customer_phone' => $customer_phone
                ],
                'order_meta'=>[
                    'notify_url'=>base_url('app/user/notify_cashfree/'.$this->data['formdata']['custom'])
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-api-version: 2025-01-01",
                "x-client-id: TEST109934433b7a2249e8e7018f0aa734439901",
                "x-client-secret: cfsk_ma_test_01e337999e6470eca488d40f27df7d89_8a94fc65"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $result['errors']=$err;
        } else {
            $this->ReturnStatus=1;
            $result['data']=$response;
            $result['status'] = 1;
        }
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);

	}
    // public function post_notify_cashfree($custom=''){
    //     $payload = @file_get_contents('php://input');
    //     file_put_contents(UPLOAD_PATH.'cashfree.log', json_encode($payload));
    // }

    public function post_notify_cashfree($content_key)
    {
        $is_failed=0;
        $result=[];
        $payload = @file_get_contents('php://input');
        file_put_contents(
            UPLOAD_PATH . 'cashfree.log',
            json_encode($payload) . PHP_EOL,
            FILE_APPEND
        );
       /*  $path = UPLOAD_PATH . 'cashfree.log';

        if(file_exists($path)){
           $content = file_get_contents($path);
        } */

        //$data = json_decode(json_decode($content), true);
        $data=json_decode($payload,true);
        //$data = json_decode($data, true);
            //print_r($data );
           

        //echo $order_id       = $data['data']['order']['order_id'] ?? '';
        $payment_amount = $data['data']['payment']['payment_amount'] ?? 0;
        $payment_status = $data['data']['payment']['payment_status'] ?? '';
        $eventType      = $data['type'];
        if($eventType!='PAYMENT_SUCCESS_WEBHOOK'){
            return true;
            die;
        }
        

        if ($payment_status !== 'SUCCESS' || empty($content_key)) {
            $result['msg'] = 'payment not success';
            $is_failed=1;
        }

        // Atomic claim: only one concurrent webhook can flip status 0→2 (processing)
        $this->db->where('content_key', $content_key)
                 ->where('payment_type', 'CASHFREE')
                 ->where('status', 0)
                 ->update('online_transaction_data', ['status' => 2]);
        if ($this->db->affected_rows() == 0) {
            // Already being processed or completed — ignore duplicate webhook
            $this->response(['status' => 0, 'response' => ['msg' => 'duplicate or already processed']], 200);
            return;
        }

        // Get transaction record (now safely locked to this request)
        $transaction_data = getData(array(
            'select' => 'request_value,status',
            'table'  => 'online_transaction_data',
            'where'  => array(
                'payment_type' => 'CASHFREE',
                'content_key'  => $content_key,
            ),
            'single_row' => TRUE
        ));

        if (!$transaction_data) {
            $result['msg'] = 'no transaction data';
            $is_failed=1;
        }

        $payment_request = json_decode($transaction_data->request_value);
        if (!$payment_request) {
            $result['msg'] = 'no payment request data';
           $is_failed=1;
        }
        //print_r( $payment_request);

        $total     = $payment_request->org_amt;
        $order_fee = $payment_request->fee ?? 0;

        // Get Wallets
        $cashfree_details = getWallet(get_setting('CASHFREE_WALLET'));
        $fee_wallet_details = getWallet(get_setting('PROCESSING_FEE_WALLET'));
        $worker_details = getWalletWorker($payment_request->worker_id);
        //print_r( $worker_details);
        if(!$cashfree_details || !$worker_details || !$fee_wallet_details){
            $result['msg'] = 'no data';
            $is_failed=1;
        }
        if($is_failed==1){
            $this->load->library('pusher');
            $pusher=$this->pusher->load();
            $pusherDataGlobal=[
                'worker_id'=>$payment_request->worker_id,
            ];
            $pusher->trigger('payment_acknowledgement_'.$payment_request->worker_id, 'error',$pusherDataGlobal);
        }else{
        $wallet_transaction_type_id = get_setting('ADD_FUND_CASHFREE');
        $current_datetime = date('Y-m-d H:i:s');

        $wallet_transaction_id = insert_record('wallet_transaction', [
            'wallet_transaction_type_id' => $wallet_transaction_type_id,
            'status' => 1,
            'created_date' => $current_datetime,
            'transaction_date' => $current_datetime
        ], TRUE);

        if($wallet_transaction_id){

            updateTable('online_transaction_data', [
                'status'=>1,
                'tran_id'=>$wallet_transaction_id,
                'response_value'=>$payload
            ], ['content_key'=>$content_key]);

            /* Debit Cashfree Wallet */
            insert_record('wallet_transaction_row', [
                'wallet_transaction_id'=>$wallet_transaction_id,
                'wallet_id'=>$cashfree_details->wallet_id,
                'debit'=>$payment_amount,
                'description_tkey'=>'Online_payment_from',
                'relational_data'=>'Cashfree',
                'ref_data_cell'=>json_encode([
                    'FW'=>$cashfree_details->title,
                    'TW'=>$worker_details->title,
                    'TP'=>'Payment_Payment',
                ])
            ]);

            /*  Credit Member Wallet */
            insert_record('wallet_transaction_row', [
                'wallet_transaction_id'=>$wallet_transaction_id,
                'wallet_id'=>$worker_details->wallet_id,
                'credit'=>$payment_amount,
                'description_tkey'=>'Online_payment_from',
                'relational_data'=>'Cashfree',
                'ref_data_cell'=>json_encode([
                    'FW'=>$cashfree_details->title,
                    'TW'=>$worker_details->title,
                    'TP'=>'Wallet_Topup',
                ])
            ]);

            /* Deduct Processing Fee From Member */
            if($order_fee > 0){

                insert_record('wallet_transaction_row', [
                    'wallet_transaction_id'=>$wallet_transaction_id,
                    'wallet_id'=>$worker_details->wallet_id,
                    'debit'=>$order_fee,
                    'description_tkey'=>'Cashfree_fee',
                    'relational_data'=>$order_fee,
                ]);

                insert_record('wallet_transaction_row', [
                    'wallet_transaction_id'=>$wallet_transaction_id,
                    'wallet_id'=>$fee_wallet_details->wallet_id,
                    'credit'=>$order_fee,
                    'description_tkey'=>'Cashfree_fee',
                    'relational_data'=>$order_fee,
                ]);

                $new_fee_balance = displayamount($fee_wallet_details->balance,2) + displayamount($order_fee,2);
                updateTable('wallet',['balance'=>$new_fee_balance],['wallet_id'=>$fee_wallet_details->wallet_id]);
                wallet_balance_check($fee_wallet_details->wallet_id,['transaction_id'=>$wallet_transaction_id]);
            }

            /*  Update Member Balance */
            $new_member_balance = displayamount($worker_details->balance,2) + displayamount($total,2);
            updateTable('wallet',['balance'=>$new_member_balance],['wallet_id'=>$worker_details->wallet_id]);
            wallet_balance_check($worker_details->wallet_id,['transaction_id'=>$wallet_transaction_id]);
                // sms to worker for wallet update
                $mobile=getField('worker_phone','worker','worker_id',$payment_request->worker_id);
                // echo $mobile; die;
                $currency = ($total == 1) ? 'Re.' : 'Rs.';
                $amt_var = $currency.' '.$total;
                if($mobile && $total > 0){
                    $smstext='Your service provider wallet has been successfully recharged with '.$amt_var.'. You can now accept jobs and start earning more. Thank you for choosing SNAPHIVE.';
                    $sendSms=sendSMS($mobile,'1707177633556818028',$smstext);
                }

            /* Update Cashfree Wallet Balance */
            $new_cashfree_balance = displayamount($cashfree_details->balance,2) - displayamount($payment_amount,2);
            updateTable('wallet',['balance'=>$new_cashfree_balance],['wallet_id'=>$cashfree_details->wallet_id]);
            wallet_balance_check($cashfree_details->wallet_id,['transaction_id'=>$wallet_transaction_id]);

            $result['msg'] = 'success';
            $this->ReturnStatus=1;

            $this->load->library('pusher');
            $pusher=$this->pusher->load();
            $pusherDataGlobal=[
                'worker_id'=>$payment_request->worker_id,
                'balance'=>$new_member_balance
            ];
            $pusher->trigger('payment_acknowledgement_'.$payment_request->worker_id, 'success',$pusherDataGlobal);

        }
        }
        $this->ResponseData=$result;
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) ,
        $this->ReturnCode);
    }

    // Customer pays invoice via Cashfree — creates a payment order and returns session token
    public function post_payment_cashfree_customer(){
        $result=[];
        $this->data=[];
        $member_id=$this->member_id;
        $booking_id=$this->input->post('booking_id');

        if(!$member_id || !$booking_id){
            $this->response(array('status'=>0,'response'=>array('message'=>'member_id and booking_id are required')),200);
            return;
        }

        $InvoiceData=getData(array(
            'select'=>'invoice_id,round_up_amount,invoice_status',
            'table'=>'invoice',
            'where'=>array('invoice_order_id'=>$booking_id,'recipient_member_id'=>$member_id,'invoice_status'=>0),
            'single_row'=>TRUE
        ));

        if(!$InvoiceData){
            $this->response(array('status'=>0,'response'=>array('message'=>'Invoice not found or already paid')),200);
            return;
        }

        $amount=$InvoiceData->round_up_amount;
        $invoice_id=$InvoiceData->invoice_id;

        $unique_id=$member_id.'-'.time();
        $this->data['formdata']=array(
            'amount'=>$amount,
            'org_amt'=>$amount,
            'fee'=>0,
            'custom'=>md5('CPAY-'.$unique_id),
            'member_id'=>$member_id,
            'booking_id'=>$booking_id,
            'invoice_id'=>$invoice_id,
        );

        $transansaction_data=array('payment_type'=>'CASHFREE_CUSTOMER','content_key'=>$this->data['formdata']['custom']);
        $this->data['formdata']['amount_converted']=$amount;
        $transansaction_data['request_value']=json_encode($this->data['formdata']);
        insert_record('online_transaction_data',$transansaction_data);

        $url='https://api.cashfree.com/pg/orders';
        $is_sandbox=get_setting('is_sandbox');
        if($is_sandbox){
            $url='https://sandbox.cashfree.com/pg/orders';
        }

        $customer_phone=getFieldData('member_phone','member','member_id',$member_id);

        $curl=curl_init();
        curl_setopt_array($curl,array(
            CURLOPT_URL=>$url,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_ENCODING=>"",
            CURLOPT_MAXREDIRS=>10,
            CURLOPT_TIMEOUT=>30,
            CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST=>"POST",
            CURLOPT_POSTFIELDS=>json_encode(array(
                'order_currency'=>'INR',
                'order_amount'=>$amount,
                'customer_details'=>array(
                    'customer_id'=>'C'.$member_id,
                    'customer_phone'=>$customer_phone
                ),
                'order_meta'=>array(
                    'notify_url'=>base_url('app/user/notify_cashfree_customer/'.$this->data['formdata']['custom'])
                )
            )),
            CURLOPT_HTTPHEADER=>array(
                "Content-Type: application/json",
                "x-api-version: 2025-01-01",
                "x-client-id: TEST109934433b7a2249e8e7018f0aa734439901",
                "x-client-secret: cfsk_ma_test_01e337999e6470eca488d40f27df7d89_8a94fc65"
            ),
        ));
        $response=curl_exec($curl);
        $err=curl_error($curl);
        curl_close($curl);

        if($err){
            $result['errors']=$err;
        }else{
            $this->ReturnStatus=1;
            $result['data']=$response;
            $result['status']=1;
        }

        $this->ResponseData=$result;
        $this->response(array(
            "status"=>$this->ReturnStatus,
            "response"=>$this->ResponseData
        ),$this->ReturnCode);
    }

    // Cashfree webhook for customer invoice payment — marks invoice paid
    public function post_notify_cashfree_customer($content_key)
    {
        $is_failed=0;
        $result=[];
        $payload=@file_get_contents('php://input');
        file_put_contents(UPLOAD_PATH.'cashfree_customer.log',json_encode($payload).PHP_EOL,FILE_APPEND);

        $data=json_decode($payload,true);
        $payment_amount=$data['data']['payment']['payment_amount'] ?? 0;
        $payment_status=$data['data']['payment']['payment_status'] ?? '';
        $eventType=$data['type'];

        if($eventType!='PAYMENT_SUCCESS_WEBHOOK'){
            return true;
        }

        if($payment_status!=='SUCCESS' || empty($content_key)){
            $is_failed=1;
        }

        // Atomic claim: only one webhook can flip status 0→2
        $this->db->where('content_key',$content_key)
                 ->where('payment_type','CASHFREE_CUSTOMER')
                 ->where('status',0)
                 ->update('online_transaction_data',array('status'=>2));
        if($this->db->affected_rows()==0){
            $this->response(array('status'=>0,'response'=>array('msg'=>'duplicate or already processed')),200);
            return;
        }

        $transaction_data=getData(array(
            'select'=>'request_value,status',
            'table'=>'online_transaction_data',
            'where'=>array('payment_type'=>'CASHFREE_CUSTOMER','content_key'=>$content_key),
            'single_row'=>TRUE
        ));

        if(!$transaction_data){
            $is_failed=1;
        }

        $payment_request=($transaction_data ? json_decode($transaction_data->request_value) : null);

        if($is_failed==1){
            if($payment_request && $payment_request->member_id){
                $this->load->library('pusher');
                $pusher=$this->pusher->load();
                $pusher->trigger('payment_acknowledgement_member_'.$payment_request->member_id,'error',array('member_id'=>$payment_request->member_id));
            }
            $this->response(array('status'=>0,'response'=>array('msg'=>'payment failed')),200);
            return;
        }

        $booking_id=$payment_request->booking_id;
        $invoice_id=$payment_request->invoice_id;
        $member_id=$payment_request->member_id;

        // Get invoice amount for SMS
        $round_up_amount=getFieldData('round_up_amount','invoice','invoice_id',$invoice_id);

        // Mark invoice as paid (payment_type = CASHFREE marks it as customer direct payment)
        updateTable('invoice',array('invoice_status'=>1,'payment_type'=>'CASHFREE'),array('invoice_id'=>$invoice_id));

        // Record that customer paid directly via Cashfree — status 1 = confirmed payment
        updateTable('online_transaction_data',array('status'=>1,'response_value'=>$payload),array('content_key'=>$content_key));

        /*
        // --- Wallet transactions & commission (to be enabled later) ---
        $InvoiceData=getData(array(
            'select'=>'a.invoice_id,a.round_up_amount,a.cancel_charges,a.tax_amount,a.platform_fee',
            'table'=>'invoice a',
            'where'=>array('a.invoice_id'=>$invoice_id),
            'single_row'=>TRUE
        ));
        $worker_id=getFieldData('provider_id','booking_services','booking_id',$booking_id);
        $cancel_charges=$InvoiceData->cancel_charges;
        $tax_amount=$InvoiceData->tax_amount;
        $platform_fee=$InvoiceData->platform_fee;
        $total_amount=$round_up_amount - $cancel_charges - $tax_amount - $platform_fee;

        $sitecommission_fee_amount=0;
        $sitecommission=get_setting('site_commision');
        if($sitecommission>0){
            $sitecommission_fee_amount=round(($total_amount * $sitecommission)/100,2);
        }
        $profit_details=getWallet(get_setting('SITE_PROFIT_WALLET'));
        $workerWalletDetails=getWalletWorker($worker_id);
        $wallet_transaction_type_id=get_setting('COMMISION_RELEASE');
        $current_datetime=date('Y-m-d H:i:s');
        $wallet_transaction_id=insert_record('wallet_transaction',array(
            'wallet_transaction_type_id'=>$wallet_transaction_type_id,
            'status'=>1,
            'created_date'=>$current_datetime,
            'transaction_date'=>$current_datetime
        ),TRUE);
        if($wallet_transaction_id){
            insert_record('wallet_transaction_row',array(
                'wallet_transaction_id'=>$wallet_transaction_id,
                'wallet_id'=>$workerWalletDetails->wallet_id,
                'debit'=>$sitecommission_fee_amount,
                'description_tkey'=>'Project_Commision_',
                'relational_data'=>$sitecommission.'%',
                'ref_data_cell'=>json_encode(array('FW'=>$workerWalletDetails->worker_name.' wallet','TW'=>$profit_details->title,'TP'=>'Commission_Payment','PID'=>$booking_id))
            ));
            insert_record('wallet_transaction_row',array(
                'wallet_transaction_id'=>$wallet_transaction_id,
                'wallet_id'=>$profit_details->wallet_id,
                'credit'=>$sitecommission_fee_amount,
                'description_tkey'=>'Project_Commision',
                'relational_data'=>$sitecommission.'%',
                'ref_data_cell'=>json_encode(array('FW'=>$workerWalletDetails->worker_name.' wallet','TW'=>$profit_details->title,'TP'=>'Commission_Payment','PID'=>$booking_id))
            ));
            $new_worker_balance=displayamount($workerWalletDetails->balance,2)-displayamount($sitecommission_fee_amount,2);
            updateTable('wallet',array('balance'=>$new_worker_balance),array('wallet_id'=>$workerWalletDetails->wallet_id));
            wallet_balance_check($workerWalletDetails->wallet_id,array('transaction_id'=>$wallet_transaction_id));
            $new_profit_balance=displayamount($profit_details->balance,2)+displayamount($sitecommission_fee_amount,2);
            updateTable('wallet',array('balance'=>$new_profit_balance),array('wallet_id'=>$profit_details->wallet_id));
            wallet_balance_check($profit_details->wallet_id,array('transaction_id'=>$wallet_transaction_id));
        }
        $this->load->model('app_model','app');
        if($cancel_charges){ $this->app->release_cancel_charges($booking_id,$invoice_id); }
        if($tax_amount){ $this->app->release_tax_charges($booking_id,$invoice_id); }
        // --- End wallet transactions ---
        */

        // SMS to customer
        $mobile=getFieldData('member_phone','member','member_id',$member_id);
        if($mobile && $round_up_amount>0){
            $smstext='Payment received successfully. Manpower service amount: Rs. '.$round_up_amount.'. Thank you for choosing our service! -SNAPHIVE';
            sendSMS($mobile,'1707177390474228474',$smstext);
        }

        // Pusher — notify customer app
        $this->load->library('pusher');
        $pusher=$this->pusher->load();
        $pusher->trigger('payment_acknowledgement_member_'.$member_id,'success',array(
            'member_id'=>$member_id,
            'booking_id'=>$booking_id,
            'invoice_id'=>$invoice_id,
        ));

        $result['msg']='success';
        $this->ReturnStatus=1;
        $this->ResponseData=$result;
        $this->response(array(
            "status"=>$this->ReturnStatus,
            "response"=>$this->ResponseData
        ),$this->ReturnCode);
    }











    public function get_details(){
        $options=array();
        $user_id_get=$this->input->get('profile_id');
        $this->db->select('u.member_id')
        ->from('member as u')
        ->where('u.member_id',$user_id_get);
        $user_row=$this->db->get()->row_array();
        if($user_row){
            $member_id=$user_row['member_id']; 
        }else{
            $this->response(array(
                "status" => 0,
                "message" => "error"
              ) , 200);
              die;
        }
        $log_member_id=$this->member_id;	
        if($log_member_id==$member_id){
            $is_editable=TRUE;
        }
        $memberDataBasic=getData(array(
            'select'=>'m.member_name,m_b.member_heading,m_b.member_overview,m_b.member_hourly_rate,m_b.available_per_week,m_b.not_available_until,c_n.country_name,c.country_code_short,m_l.logo,m_s.avg_rating,m_s.total_earning,m_s.no_of_reviews,m_s.total_working_hour,m_s.success_rate',
            'table'=>'member as m',
            'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left'),array('table'=>'member_statistics m_s','on'=>'m.member_id=m_s.member_id','position'=>'left'),array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left'),array('table'=>'member_logo as m_l','on'=>'(m.member_id=m_l.member_id and m_l.status=1)','position'=>'left'),),
            'where'=>array('m.member_id'=>$member_id),
            'single_row'=>true,
        ));
        if($memberDataBasic){
			$data['profile_url']=URL::get_link('viewprofileURL').'/'.md5($member_id);
			$data['member_id']=$member_id;
            $memberDataBasic->logo=getMemberLogo($member_id);
           
            if($memberDataBasic->not_available_until){
                $memberDataBasic->available_text='Offline till '.dateFormat($memberDataBasic->not_available_until);
            }elseif($memberDataBasic->available_per_week){
                $duration=getAllProjectDurationTime($memberDataBasic->available_per_week);
                $memberDataBasic->available_text=$duration['freelanceName'];
            }else{
                $memberDataBasic->available_text='Not set';
            }

			$data['memberInfo']=$memberDataBasic;
            $badge_ids=array();
			$member_badges=getData(array(
				'select'=>'m.badge_id',
				'table'=>'member_badges as m',
				'where'=>array('m.member_id'=>$member_id),
			));
			if($member_badges){
				foreach($member_badges as $b=>$row){
					$badge_ids[]=$row->badge_id;
				}
			}
            $membership_id=getFieldData('membership_id','member_membership','member_id',$member_id);
			if($membership_id){
				$membership_badges=getData(array(
					'select'=>'m.badge_id',
					'table'=>'membership_badge as m',
					'where'=>array('m.membership_id'=>$membership_id),
				));
				if($membership_badges){
					foreach($membership_badges as $b=>$row){
						$badge_ids[]=$row->badge_id;
					}
				}
			}
			$badges=new stdClass();
			if($badge_ids){
				$badges=getData(array(
					'select'=>'b.icon_image,b_n.name,b_n.description',
					'table'=>'badges as b',
					'join'=>array(array('table'=>'badges_names as b_n','on'=>"(b.badge_id=b_n.badge_id and b_n.lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('b.status'=>1),
					'where_in'=>array('b.badge_id'=>$badge_ids),
					'order'=>array(array('b.display_order','asc')),
				));
			}	
            if($badges){
                foreach($badges as $k=>$row){
                    if($row->icon_image){
                        $row->url=UPLOAD_HTTP_PATH."member-badge/".$row->icon_image;
                    }else{
                        $row->url='';
                    }
                    $badges[$k]=$row; 
                }
            }

			$data['memberInfo']->badges=$badges;
			$data['memberInfo']->total_jobs=$this->db->where(array('c.contractor_id'=>$member_id,'c.contract_status'=>1))->from('project_contract as c')->count_all_results();
			$data['is_editable']=$is_editable;


           $laguagedata=getData(array(
            'select'=>'m_l.member_language_id,l_n.language_id,l_n.language_name,l_p_n.language_preference_name',
            'table'=>'member_language as m_l',
            
            'join'=>array(array('table'=>'language_names as l_n','on'=>"(m_l.language_id=l_n.language_id and l_n.language_lang='".get_active_lang()."')",'position'=>'left'),array('table'=>'language_preference_names as l_p_n','on'=>"(m_l.language_preference_id=l_p_n.language_preference_id and l_p_n.language_preference_lang='".get_active_lang()."')",'position'=>'left'),array('table'=>'language_preference as l_p','on'=>'m_l.language_preference_id=l_p.language_preference_id','position'=>'left')),

            'where'=>array('m_l.member_id'=>$member_id,'m_l.language_status'=>1),
            'order'=>array(array('l_p.language_preference_ord','asc'),array('m_l.member_language_id','asc'))
            ));
            $data['memberInfo']->language=$laguagedata; 

            $memberDataemployment=getData(array(
						'select'=>'m_e.employment_company,m_e.employment_id,m_e.employment_city,m_e.employment_title,m_e.employment_role,m_e.employment_from,m_e.employment_to,m_e.employment_is_working_on,m_e.employment_description,c_n.country_name',
						'table'=>'member_employment as m_e',
						'join'=>array(array('table'=>'country as c','on'=>'m_e.employment_country_code=c.country_code','position'=>'left'),array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')),
						'where'=>array('m_e.member_id'=>$member_id,'m_e.employment_status'=>1),
						'order'=>array(array('m_e.employment_is_working_on','desc'),array('m_e.employment_to','desc'))
						));

            $data['memberInfo']->employment=$memberDataemployment;

            $memberDataeducation=getData(array(
						'select'=>'m_e.education_school,m_e.education_from_year,m_e.education_end_year,m_e.education_degree,m_e.education_area_of_study,m_e.education_description,m_e.education_id',
						'table'=>'member_education as m_e',
						'where'=>array('m_e.member_id'=>$member_id,'m_e.education_status'=>1),
						'order'=>array(array('m_e.education_end_year','desc'),array('m_e.education_id','desc'))
						));
           $data['memberInfo']->education=$memberDataeducation;  
           
           $memberDataskills=getData(array(
						'select'=>'s_n.skill_name',
						'table'=>'member_skills as m_s',
						'join'=>array(array('table'=>'skills as s','on'=>'m_s.skill_id=s.skill_id','position'=>'left'),array('table'=>'skill_names as s_n','on'=>"(s.skill_id=s_n.skill_id and s_n.skill_lang='".get_active_lang()."')",'position'=>'left')),
						'where'=>array('m_s.member_id'=>$member_id),
						'order'=>array(array('m_s.member_skills_order','asc'))
						));
            $data['memberInfo']->skills=$memberDataskills; 
            
            $memberDataportfolio=getData(array(
						'select'=>'m_p.portfolio_id,m_p.portfolio_title,m_p.portfolio_description,m_p.portfolio_complete_date,cs_n.category_subchild_name,m_p.portfolio_image',
						'table'=>'member_portfolio as m_p',
						'join'=>array(array('table'=>'category_subchild as cs','on'=>'m_p.category_subchild_id=cs.category_subchild_id','position'=>'left'),array('table'=>'category_subchild_names as cs_n','on'=>"(cs.category_subchild_id=cs_n.category_subchild_id and cs_n.category_subchild_lang='".get_active_lang()."')",'position'=>'left')),
						'where'=>array('m_p.member_id'=>$member_id,'m_p.portfolio_status'=>1),
						'order'=>array(array('m_p.portfolio_id','desc'))
						));
            if($memberDataportfolio){
                foreach($memberDataportfolio as $k=>$row){
                    $row->portfolio_image=json_decode($row->portfolio_image);
                    if($row->portfolio_image){
                        $row->portfolio_image->url=UPLOAD_HTTP_PATH."member-portfolio/".$row->portfolio_image->file;
                    }
                    $memberDataportfolio[$k]=$row; 
                }
            }            
            $data['memberInfo']->portfolio=$memberDataportfolio; 
            
                     
            if($is_editable){
				$options['all_skills']=getAllSkills();
                $options['all_language']=getData(array(
					'select'=>'l.language_id,l_n.language_name',
					'table'=>'language as l',
					'join'=>array(array('table'=>'language_names as l_n','on'=>"(l.language_id=l_n.language_id and l_n.language_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('l.language_status'=>'1'),
				));
                $options['all_language']=getData(array(
					'select'=>'l.language_id,l_n.language_name',
					'table'=>'language as l',
					'join'=>array(array('table'=>'language_names as l_n','on'=>"(l.language_id=l_n.language_id and l_n.language_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('l.language_status'=>'1'),
				));
                $options['language_preference']=getData(array(
					'select'=>'l_p.language_preference_id,l_p_n.language_preference_name,l_p_n.language_preference_info',
					'table'=>'language_preference as l_p',
					'join'=>array(array('table'=>'language_preference_names as l_p_n','on'=>"(l_p.language_preference_id=l_p_n.language_preference_id and l_p_n.language_preference_lang='".get_active_lang()."')",'position'=>'left')),
					'where'=>array('l_p.language_preference_status'=>'1'),
				));
            }

        }





        
        $result['data'] = $data;
        $result['options'] = $options;
        $result['status'] = 1;
        $this->ReturnStatus=1;
    
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
    }
    
    public function get_portfolio(){
        $this->load->model('user_model', 'user');
		$srch = get();
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$all_portfolio=$this->user->list_portfolio($srch,$start,$limit);
        if($all_portfolio){
            foreach($all_portfolio as $k=>$row){
                $all_portfolio[$k]['thumb_img']=VPATH . 'assets/portfolio/' . $row['thumb_img'];
                $all_portfolio[$k]['original_img']=VPATH . 'assets/portfolio/' . $row['original_img'];
            }
        }
		$data = array(
			'all_portfolio' => $all_portfolio,
			'total_portfolio' => $this->user->list_portfolio($srch,'','', false),
		);
		$data['total_page']=ceil($data['total_portfolio']/$limit);
		$data['current_page']=$page;
		
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
    public function post_save_profile(){
        $this->load->library('form_validation');
		$i=0;
		$msg=array();
        $step=get('for');
        $member_id=$this->member_id;
        if($step=='basic'){
            $this->form_validation->set_rules('heading', 'Heading', 'required|trim|xss_clean');
            $this->form_validation->set_rules('overview', 'Overview', 'required|trim|xss_clean');
        }elseif($step=='employment'){
            $this->form_validation->set_rules('company', 'Company', 'required|trim|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
            $this->form_validation->set_rules('country', 'Country', 'required|trim|xss_clean');
            $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
            $this->form_validation->set_rules('role', 'Role', 'required|trim|xss_clean');
            $this->form_validation->set_rules('frommonth', 'Month', 'required|trim|xss_clean');
            $this->form_validation->set_rules('fromyear', 'year', 'required|trim|xss_clean');
            if(post('employment_is_working_on')){
                
            }else{
                $this->form_validation->set_rules('tomonth', 'Month', 'required|trim|xss_clean');
                $this->form_validation->set_rules('toyear', 'year', 'required|trim|xss_clean');
            }
        }elseif($step=='delete_employment'){
            $this->form_validation->set_rules('id', 'id', 'required');
        }elseif($step=='education'){
            $this->form_validation->set_rules('school', 'School', 'required|trim|xss_clean');
            $this->form_validation->set_rules('from_year', 'From year', 'required|trim|xss_clean');
            $this->form_validation->set_rules('end_year', 'To year', 'required|trim|xss_clean');
        }elseif($step=='delete_education'){
            $this->form_validation->set_rules('id', 'id', 'required');
        }elseif($step=='language'){
            $this->form_validation->set_rules('language', 'language', 'required|trim|xss_clean|numeric');
			$this->form_validation->set_rules('language_preference', 'Preference', 'required|trim|xss_clean|numeric');
        }elseif($step=='delete_language'){
            $this->form_validation->set_rules('id', 'id', 'required');
        }elseif($step=='portfolio'){
            $this->form_validation->set_rules('title', 'Project Title', 'required|trim|xss_clean');
            $this->form_validation->set_rules('description', 'Project Overview', 'required|trim|xss_clean');
            $this->form_validation->set_rules('category', 'Category', 'required|trim|xss_clean');
            if(post('category')){
                $this->form_validation->set_rules('sub_category', 'Sub Category', 'required|trim|xss_clean');
            }
            if(post('complete_date')){  
                $this->form_validation->set_message('valid_date', 'The Date field must be dd-mm-yyyy');
                $this->form_validation->set_rules('complete_date', 'Date', 'required|trim|xss_clean|valid_date[Ymd]');
                
            }
        }elseif($step=='delete_portfolio'){
            $this->form_validation->set_rules('id', 'id', 'required');
        }elseif($step=='hourly_rate'){ 
            $this->form_validation->set_rules('hourly', 'Hourly', 'required|trim|xss_clean|numeric');  
            $this->form_validation->set_rules('is_available', 'is_available', 'required|trim|xss_clean|numeric');
            $is_available=post('is_available');
            if($is_available==1){
                $this->form_validation->set_rules('available_per_week', 'week', 'required|trim|xss_clean');
            }else{
                $this->form_validation->set_rules('not_available_until', 'date', 'required|trim|xss_clean|valid_date');
            }
        }elseif($step=='logo'){
            $this->form_validation->set_rules('logo_data', 'logo_data', 'required');
        }elseif($step=='background'){
            $this->form_validation->set_rules('logo_data', 'logo_data', 'required');
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
         

        }
		if($i==0){
            if($step=='basic'){
                $memberDatacount=getData(array(
                    'select'=>'m_b.member_id',
                    'table'=>'member_basic as m_b',
                    'where'=>array('m_b.member_id'=>$member_id),
                    'return_count'=>true,
                ));
                if(!$memberDatacount){
                    $up=insert_record('member_basic',array('member_id'=>$member_id,'member_heading'=>trim(post('heading')),'member_overview'=>post('overview')),TRUE);
                }else{
                    $up=updateTable('member_basic',array('member_heading'=>trim(post('heading')),'member_overview'=>trim(post('overview'))),array('member_id'=>$member_id));
                }
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }
            elseif($step=='employment'){
                $dataid=post('id');
                $memberDatacount=getData(array(
                    'select'=>'m_e.employment_id',
                    'table'=>'member_employment as m_e',
                    'where'=>array('m_e.member_id'=>$member_id,'m_e.employment_id'=>$dataid),
                    'single_row'=>true,
                ));
                $employment_is_working_on=NULL;
                $employment_to=NULL;
                $f_year=post('fromyear');
                $f_month=post('frommonth');
                if(strlen($f_month)==1){
                    $f_month="0".$f_month;
                }
                $employment_from=$f_year."-".$f_month."-01";
                if(post('employment_is_working_on')){
                    $employment_is_working_on=1;
                }else{
                    $t_year=post('toyear');
                    $t_month=post('tomonth');
                    if(strlen($t_month)==1){
                        $t_month="0".$t_month;
                    }
                    $employment_to=$t_year."-".$t_month."-01";
                }
                
                if($memberDatacount){
                    $up=updateTable('member_employment',array('employment_company'=>post('company'),'employment_city'=>post('city'),'employment_country_code'=>post('country'),'employment_title'=>post('title'),'employment_role'=>post('role'),'employment_from'=>$employment_from,'employment_to'=>$employment_to,'employment_is_working_on'=>$employment_is_working_on,'employment_description'=>post('description'),'employment_status'=>1),array('member_id'=>$member_id,'employment_id'=>$memberDatacount->employment_id));
                    $id = $dataid;
                }else{
                    $up=insert_record('member_employment',array('member_id'=>$member_id,'employment_company'=>post('company'),'employment_city'=>post('city'),'employment_country_code'=>post('country'),'employment_title'=>post('title'),'employment_role'=>post('role'),'employment_from'=>$employment_from,'employment_to'=>$employment_to,'employment_is_working_on'=>$employment_is_working_on,'employment_description'=>post('description'),'employment_status'=>1),TRUE);
                    $id = $this->db->insert_id();
                }
                $msg['data']['id']=$id;
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }
            elseif($step=='delete_employment'){
                $id=post('id');
                $up=updateTable('user_experience',array('employment_status'=>0),array('employment_id'=>$id,'member_id' => $this->member_id));
                $msg['data']['id']=$id;
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='education'){
                $dataid=post('id');
                $memberDatacount=getData(array(
                    'select'=>'m_e.education_id',
                    'table'=>'member_education as m_e',
                    'where'=>array('m_e.member_id'=>$member_id,'m_e.education_id'=>$dataid),
                    'single_row'=>true,
                ));
                $data_ins=array(
                'education_school'=>post('school'),
                'education_from_year'=>post('from_year'),
                'education_end_year'=>post('end_year'),
                'education_degree'=>NULL,
                'education_area_of_study'=>NULL,
                'education_description'=>NULL,
                'education_status'=>1,
                );
                if(post('degree')){
                    $data_ins['education_degree']=post('degree');
                }
                if(post('area_of_study')){
                    $data_ins['education_area_of_study']=post('area_of_study');
                }
                if(post('description')){
                    $data_ins['education_description']=post('description');
                }

                if($memberDatacount){
                    $up=updateTable('member_education',$data_ins,array('member_id'=>$member_id,'education_id'=>$memberDatacount->education_id));
                    $id = $dataid;
                }else{
                    $data_ins['member_id']=$member_id;
                    $up=insert_record('member_education',$data_ins,TRUE);
                    $id = $this->db->insert_id();
                }
                $msg['data']['id']=$id;
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='delete_education'){
                $id=post('id');
                $up=updateTable('user_education',array('education_status'=>0),array('education_id'=>$id,'member_id'=>$member_id));
                $msg['data']['id']=$id;
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='skills'){
                $all_skill=post('skills');
                if($all_skill){
                    $sk=explode(',',$all_skill);
                    $membership=getMembershipData($member_id,array('skills'));
                    if($membership['max_skills'] >= count($sk)){

                    }else{
                        $msg['status'] = 'FAIL';
                        $msg['errors'][$i]['id'] = 'skills';
                        $msg['errors'][$i]['message'] = 'Max limit over, please upgrade your membership plan.';
                        $i++;
                        unset($_POST);
                        echo json_encode($msg);
                        die;
                    }
                }
                delete_record('member_skills',array('member_id'=>$member_id));
                if($all_skill){
                    foreach($sk as $ord=>$skill_id){
                        insert_record('member_skills',array('member_id'=>$member_id,'skill_id'=>$skill_id,'member_skills_order'=>$ord),TRUE);
                    }
                }
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='language'){
                $id=post('id');
                $memberDatacount=getData(array(
                    'select'=>'m_l.member_language_id',
                    'table'=>'member_language as m_l',
                    'join'=>array(array('table'=>'language as l','on'=>'m_l.language_id=l.language_id','position'=>'left')),
                    'where'=>array('m_l.member_id'=>$member_id,'l.language_id'=>post('language')),
                    'return_count'=>true,
                ));
                if(!$memberDatacount){
                    $up=insert_record('member_language',array('member_id'=>$member_id,'language_id'=>post('language'),'language_preference_id'=>post('language_preference'),'language_status'=>1),TRUE);
                    $id = $this->db->insert_id();
                }else{
                    $up=updateTable('member_language',array('language_preference_id'=>post('language_preference'),'language_status'=>1),array('member_id'=>$member_id,'language_id'=>post('language')));
                    $id = $memberDatacount->member_language_id;
                }
                
                $msg['data']['id']=$id;
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='delete_language'){
                $id=post('id');
                $up=updateTable('member_language',array('language_status'=>0),array('member_language_id'=>$id,'member_id'=>$this->member_id));
                $msg['data']['id']=$id;
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='portfolio'){
                $id=post('id');
                $data = array(
                    'title' => filter_data($this->input->post('title')),
                    'description' => filter_data($this->input->post('description')),
                    'tags' => filter_data($this->input->post('tags')),
                    'url' => filter_data($this->input->post('url')),
                    'original_img' => filter_data($this->input->post('original_img')),
                    'thumb_img' => filter_data($this->input->post('thumb_img')),
                );
                if($id){
                    $ins = $this->db->where(array('id' => $id, 'member_id' => $this->member_id))->update('user_portfolio', $data);
                }else{
                    $data['member_id']=$this->member_id;
                    $data['add_date']= date('Y-m-d');
                    $data['status']= 'Y';
                    $ins = $this->db->insert('user_portfolio', $data);
                    $id = $this->db->insert_id();
                }
                $msg['data']['id']=$id;
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='delete_portfolio'){
                $id=post('id');
                $up=updateTable('member_portfolio',array('portfolio_status'=>0),array('portfolio_id'=>$id,'member_id' => $this->member_id));
                $msg['data']['id']=$id;
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='hourly_rate'){
                $memberDatacount=getData(array(
                    'select'=>'m_b.member_id',
                    'table'=>'member_basic as m_b',
                    'where'=>array('m_b.member_id'=>$member_id),
                    'return_count'=>true,
                ));
                $data=array();
                $data['member_hourly_rate']=post('hourly');
                if($is_available==1){
                    $data['available_per_week']=post('available_per_week');
                    $data['not_available_until']=NULL;
                    $duration=getAllProjectDurationTime($data['available_per_week']);
                    $availability=$duration['freelanceName'];
                }else{
                    $data['not_available_until']=post('not_available_until');
                    $availability='Offline till '.dateFormat($data['not_available_until']);
                }

                if(!$memberDatacount){
                    $data['member_id']=$member_id;
                    $up=insert_record('member_basic',$data,TRUE);
                    
                }else{
                    $up=updateTable('member_basic',$data,array('member_id'=>$member_id));
                }
                $msg['data']['status']=1;
                $msg['data']['message']='Success';
                $this->ReturnStatus=1;
            }elseif($step=='logo'){
                $dataimg=$this->input->post("logo_data",FALSE);
                if($this->config->item('global_xss_filtering')){
                    $image = base64_decode(str_replace('[removed]', '', $dataimg));
                }else{
                    $formatdata=explode(';base64,',$dataimg);
                    $image = base64_decode($formatdata[1]);
                }
                $image_name = md5($this->member_id.'-'.time());
				$filename = $image_name . '.' . 'png';
                $path = UPLOAD_PATH.'member-logo/';
				@file_put_contents($path.$filename, $image);

                $filepathFullpath=UPLOAD_PATH.'member-logo/'.$filename;
                $this->load->library('image_lib');
                $configer =  array(
                  'image_library'   => 'gd2',
                  'source_image'    =>  $filepathFullpath,
                  'maintain_ratio'  =>  TRUE,
                  'width'           =>  150,
                  'height'          =>  150,
                );
                $this->image_lib->clear();
                $this->image_lib->initialize($configer);
                $this->image_lib->resize();

                if($filename){
                    
                    $memberDatacount=getData(array(
                        'select'=>'m_l.member_id',
                        'table'=>'member_logo as m_l',
                        'where'=>array('m_l.member_id'=>$member_id),
                        'return_count'=>true,
                    ));
                    if(!$memberDatacount){
                        $up=insert(array('table'=>'member_logo','data'=>array('member_id'=>$member_id,'logo'=>$filename,'status'=>1,'reg_date'=>date('Y-m-d H:i:s'))),TRUE);
                        
                    }else{
                        $up=update(array('table'=>'member_logo','data'=>array('logo'=>$filename,'status'=>1,'reg_date'=>date('Y-m-d H:i:s')),'where'=>array('member_id'=>$member_id)));
                    }
                    $attachment=[
                        'filename'=>$filename,
                        'logo'=>getMemberLogo($member_id),
                    ];
                    $msg['data']['attachment']=$attachment;
                    $msg['data']['status']=1;
                    $msg['data']['message']='Success';
                    $this->ReturnStatus=1;
                }else{
                    $msg['data']['status']=0;
                    $msg['data']['message']='Failed to generate thumb';
                }  
            }elseif($step=='background'){
                $dataimg=$this->input->post("logo_data",FALSE);
                if($this->config->item('global_xss_filtering')){
                    $image = base64_decode(str_replace('[removed]', '', $dataimg));
                }else{
                    $formatdata=explode(';base64,',$dataimg);
                    $image = base64_decode($formatdata[1]);
                }
                $image_name = md5($this->member_id.'-bg-'.time());
				$filename = $image_name . '.' . 'png';
                $path = APATH.'assets/uploaded/';
				@file_put_contents($path.$filename, $image);
                $configs['image_library'] = 'gd2';
                $configs['source_image']	= 'assets/uploaded/'.$filename;
                $configs['create_thumb'] = TRUE;
                $configs['maintain_ratio'] = TRUE;
                $this->load->library('image_lib', $configs);
                $rsz=$this->image_lib->resize();
                if($rsz){
                    $image_thumb='bgcropped_'.$filename;
                    $dataU=array(
                        "original_img" =>$filename,
                        "thumb_img" =>$image_thumb,
                    );
                
                    $attachment = array(
                        'original_img' => $dataU['original_img'],
                        'thumb_img' => $dataU['thumb_img'],
                        'is_image' => 1,
                        'image_url'=>base_url('assets/uploaded').'/'. $dataU['thumb_img']
                    );
                    $data = array(
                        'profile_bg_pic' => $filename,
                    );
                    $this->db->where('member_id',$this->member_id);
                    $upd_user=$this->db->update('user', $data);
                    $msg['data']['attachment']=$attachment;
                    $msg['data']['status']=1;
                    $msg['data']['message']='Success';
                    $this->ReturnStatus=1;
                }else{
                    $msg['data']['status']=0;
                    $msg['data']['message']='Failed to generate thumb';
                }  
            }
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }


    
    public function get_invoice(){
        $this->load->model('invoice/invoice_model', 'invoice_model');
        $srch = get();
        $show='all';
		if($this->input->get('show')){
			$show=$this->input->get('show');
		}
		$contract_id=$this->input->get('contract_id');
		$get_all_invoice=$this->input->get('get_all_invoice');
        $show_invoice=FALSE;
        if($get_all_invoice){
            $show_invoice=TRUE;
            $srch['invoice_for']='all_invoice';
            $srch['invoice_for_member']=$this->member_id;
        }elseif($contract_id){
            $data['contractDetails'] = getData(array(
            'select'=>'p.project_id,p.project_url,p.project_title,c.contract_id,c.contract_title,c.contract_amount,c.is_hourly,c.contract_status,c.offer_by,c.contract_date,o.member_id as owner_id,c.contractor_id,co.max_hour_limit,co.allow_manual_hour',
            'table'=>'project_contract c',
            'join'=>array(
                array('table'=>'project_contract_offer co', 'on'=>'c.contract_id=co.contract_id', 'position'=>'left'),
                array('table'=>'project p', 'on'=>'c.project_id=p.project_id', 'position'=>'left'),
                array('table'=>'project_owner o', 'on'=>'p.project_id=o.project_id', 'position'=>'left'),
            ),
            'where'=>array('c.contract_id'=>$contract_id),
            'single_row'=>TRUE
            ));
            if($data['contractDetails']){
                $contract_id=$data['contractDetails']->contract_id;
                $project_id=$data['contractDetails']->project_id;
                $contractor_id=$data['contractDetails']->contractor_id;
                $srch['project_id']=$project_id;
                $srch['contract_id']=$contract_id;
                $srch['invoice_for']='contract';
                $show_invoice=TRUE;
            }
        }
        if($show=='pending'){
            $srch['invoice_status']='0';
        }elseif($show=='rejected'){
            $srch['invoice_status']='2';
        }elseif($show=='completed'){
            $srch['invoice_status']='1';
        }
        $srch['member_id']=$this->member_id;
        $limit=10;
        $page=(get('page')? get('page'):1);
        $start=($page-1)*$limit;
      
		
        $all=$this->invoice_model->getInvoice($srch,$limit,$start);
         if($all){
            foreach($all as $k=>$row){
                $all[$k]->invoice_number=make_invoice_number($row->invoice_number);
                $all[$k]->invoice_url=get_link('InvoiceDetailsURL').'/'.md5($row->invoice_id);
               /*  $token = md5($row['invoice_id'].'-'.date('Y-m-d').'SE##%!@JK');
                $all[$k]['sender_information']=json_decode($row['sender_information']);
                $all[$k]['receiver_information']=json_decode($row['receiver_information']);
               
                */
            }
        }
        $data = array(
            'list' => $all,
            'total' => $this->invoice_model->getInvoice($srch,'','', TRUE),
        );
       
        $data['total_page']=ceil($data['total']/$limit);
        $data['current_page']=$page;
        
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
    public function post_testimonial(){
		$i=0;
		$msg=array();
		$this->form_validation->set_rules('description', __('testimonial_feedback','Feedback'), 'required');
		
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
            $data=[];
            $data['member_id'] = $this->member_id;
            $data['description'] = $this->input->post('description');
            $data['posted_date'] =date('Y-m-d');
            $data['status'] = 'N';

            $this->db->insert('testimonial',$data);

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

    public function get_withdraw_account(){
        //$this->load->model('user_model', 'user');
        $srch = get();
        $srch['member_id']=$this->member_id;
        $limit=10;
        $page=(get('page')? get('page'):1);
        $start=($page-1)*$limit;
      
        $data['list'] = getData(array(
			'select'=>'m.account_id,m.member_id,m.payment_type,m.account_heading,m.acount_details',
			'table'=>'member_withdraw_account as m',
			'where'=>array('m.member_id'=>$this->member_id,'m.account_status'=>1),
		));
        if($data['list']){
            foreach($data['list'] as $k=>$row){
                $row->acount_details=json_decode($row->acount_details);
                $data['list'][$k]=$row;
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
    public function post_save_withdraw_account(){
        $this->load->library('form_validation');
		$i=0;
		$msg=array();
        $this->form_validation->set_rules('payment_method', 'payment method', 'required|trim|xss_clean');
        if(post('payment_method')=='paypal'){
            $this->form_validation->set_rules('paypal_address', 'address', 'required|trim|xss_clean|valid_email');
            $account_heading=post('paypal_address');
            $acount_details=json_encode(array('id'=>post('paypal_address')));
        }
        elseif(post('payment_method')=='stripe'){
            $this->form_validation->set_rules('stripe_address', 'address', 'required|trim|xss_clean|valid_email');
            $account_heading=post('stripe_address');
            $acount_details=json_encode(array('id'=>post('stripe_address')));
        }elseif(post('payment_method')=='bank'){
            $this->form_validation->set_rules('bank_name', 'bank name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('bank_ac_number', 'account number', 'required|trim|xss_clean');
            $this->form_validation->set_rules('bank_swift_code', 'swift code', 'required|trim|xss_clean');
            $this->form_validation->set_rules('bank_iban', 'IBAN', 'required|trim|xss_clean');
            $account_heading=post('bank_ac_number');
            $acount_details=json_encode(array('id'=>post('bank_ac_number'),'name'=>post('bank_name'),'swift'=>post('bank_swift_code'),'iban'=>post('bank_iban')));
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
        if($i==0 && !in_array(post('payment_method'),array('paypal','bank'))){
            $msg['status'] = 'FAIL';
            $msg['errors'][$i]['id'] = 'payment_method';
            $msg['errors'][$i]['message'] = 'Invalid payment method';
            $i++;

        }
		if($i==0){
            $member_withdraw_account=array(
                'member_id'=>$this->member_id,
                'payment_type'=>post('payment_method'),
                'account_heading'=>$account_heading,
                'acount_details'=>$acount_details,
                'account_status'=>1,
                'reg_date'=>date('Y-m-d H:i:s')
            );
            $account_id=insert_record('member_withdraw_account',$member_withdraw_account,true);

            $this->ReturnStatus=1;
            $msg['data']['status']=1;
            $msg['data']['account_id']=$account_id;
            $msg['data']['message']='Success';
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }
    public function post_remove_withdraw_account(){
        $this->load->library('form_validation');
		$i=0;
		$msg=array();
        $this->form_validation->set_rules('account_id', 'account id', 'required|trim|xss_clean');
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
            $account_id=post('account_id');
            $this->db->where('account_id',$account_id)->where('member_id',$this->member_id)->update('member_withdraw_account',array('account_status'=>2));
            $this->ReturnStatus=1;
            $msg['data']['status']=1;
            $msg['data']['account_id']=$account_id;
            $msg['data']['message']='Success';
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }
    public function post_process_withdraw_fund(){
        $this->load->library('form_validation');
		$i=0;
		$msg=array();
        $this->form_validation->set_rules('amount', 'amount', 'required|trim|xss_clean|is_numeric|greater_than[0]');
		$this->form_validation->set_rules('account_id', 'account id', 'required|trim|xss_clean|is_numeric|greater_than[0]');
		$this->form_validation->set_rules('payment_type', 'payment_type', 'required|trim|xss_clean');
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
            $member_details=getWalletMember($this->member_id);
			$current_balance=$member_details ? $member_details->balance : 0;
			$wallet_id=$member_details ? $member_details->wallet_id : 0;
			$site_details=getWallet(get_setting('WITHDRAW_WALLET'));
			$receiver_wallet_id=$site_details ? $site_details->wallet_id : 0;
			$receiver_wallet_balance=$site_details ? $site_details->balance : 0;
			$fee_wallet_details=getWallet(get_setting('PROCESSING_FEE_WALLET'));
			$fee_wallet_id=$fee_wallet_details ? $fee_wallet_details->wallet_id : 0;
			$fee_wallet_balance=$fee_wallet_details ? $fee_wallet_details->balance : 0;
            $method=post('payment_type');
            $total=post('amount');
            $account_id=post('account_id');
            if($current_balance>=$total){
                $accountDetails=getData(array(
                    'select'=>'ma.account_id,ma.member_id,ma.payment_type,ma.account_heading,ma.acount_details,m.member_name',
                    'table'=>'member_withdraw_account as ma',
                    'join'=>array(
                        array('table'=>'member as m','on'=>'ma.member_id=m.member_id','position'=>'left'),
                    ),
                    'where'=>array('ma.member_id'=>$this->member_id,'ma.account_status'=>1,'ma.account_id'=>$account_id),
                    'single_row'=>true
                ));
                if(!$accountDetails){
                    $msg['status'] = 'FAIL';
                    $msg['errors'][$i]['id'] = 'account';
                    $msg['errors'][$i]['message'] = 'Invalid account details';
                    $i++;
                }
            }else{
                $msg['status'] = 'FAIL';
                $msg['errors'][$i]['id'] = 'amount';
                $msg['errors'][$i]['message'] = __('myfinance_transfer_amount_should_not_be_greater_than_your_total_balance','Transfer amount should not be greater than your total balance');
                $i++;
            }
            
        }
       
		if($i==0){
            if($method=='paypal'){
                $acount_details_data=json_decode($accountDetails->acount_details);
                $paypal_email=$acount_details_data->id;
                
                $feeCalculation=generateProcessingFee('withdrawal_paypal',$total);
                $order_fee=$feeCalculation['processing_fee'];

                $relational_data=json_encode(array('method'=>'Paypal','to'=>$paypal_email));
                $wallet_transaction_type_id=get_setting('WITHDRAW');
                $current_datetime=date('Y-m-d H:i:s');
                $wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
                if($wallet_transaction_id){
                    $insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$wallet_id,'debit'=>$total,'description_tkey'=>'Paypal_Transfer','relational_data'=>$relational_data);
                    $insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
                        'FW'=>$accountDetails->member_name.' wallet',
                        'TW'=>$site_details->title,
                        'TP'=>'Withdraw',
                        ));
                    insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
                    $w_payment=$total-$order_fee;
                    $insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$receiver_wallet_id,'credit'=>$w_payment,'description_tkey'=>'Transfer_from','relational_data'=>$accountDetails->member_name);
                    $insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
                        'FW'=>$accountDetails->member_name.' wallet',
                        'TW'=>$site_details->title,
                        'TP'=>'Withdraw',
                        ));
                    insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
                    if($order_fee>0){
                    $insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Paypel_fee','relational_data'=>$order_fee);
                    $insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
                        'FW'=>$accountDetails->member_name.' wallet',
                        'TW'=>$fee_wallet_details->title,	
                        'TP'=>'Processing_Fee',
                        ));
                    insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
                    }
                    $new_balance=displayamount($current_balance,2)-displayamount($total,2);
                    updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$wallet_id));
                    wallet_balance_check($wallet_id,array('transaction_id'=>$wallet_transaction_id));
                    
                    $new_balance=displayamount($receiver_wallet_balance,2)+displayamount($w_payment,2);
                    updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$receiver_wallet_id));
                    wallet_balance_check($receiver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
                    if($order_fee>0){
                    $new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
                    updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
                    wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
                    }
                    $template='withdrawn-request';
                    $data_parse=array(
                        'WITHDRAWN_URL'=>ADMIN_URL.'wallet/withdrawn_list',
                    );
                    SendMail(get_setting('admin_email'),$template,$data_parse);
                    $msg['status'] = 'OK';
                    $this->ReturnStatus=1;
                    $msg['data']['wallet_transaction_id']=$wallet_transaction_id;
                    $msg['data']['message']='Success';
                }else{
                    $msg['status'] = 'FAIL';
                    $msg['error'] = 'Invalid request';
                }
            }elseif($method=='bank'){
                $acount_details_data=json_decode($accountDetails->acount_details);
                $bank_account_number=$acount_details_data->id;
                
                $feeCalculation=generateProcessingFee('withdrawal_bank',$total);
                $order_fee=$feeCalculation['processing_fee'];

                $relational_data=json_encode(array('method'=>'Bank','to'=>$bank_account_number,'name'=>$acount_details_data->name,'swift'=>$acount_details_data->swift,'iban'=>$acount_details_data->iban));
                $wallet_transaction_type_id=get_setting('WITHDRAW');
                $current_datetime=date('Y-m-d H:i:s');
                $wallet_transaction_id=insert_record('wallet_transaction',array('wallet_transaction_type_id'=>$wallet_transaction_type_id,'status'=>0,'created_date'=>$current_datetime,'transaction_date'=>$current_datetime),TRUE);
                if($wallet_transaction_id){
                    $insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$wallet_id,'debit'=>$total,'description_tkey'=>'Bank_Transfer','relational_data'=>$relational_data);
                    $insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
                        'FW'=>$accountDetails->member_name.' wallet',
                        'TW'=>$site_details->title,
                        'TP'=>'Withdraw',
                        ));
                    insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
                    $w_payment=$total-$order_fee;
                    $insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$receiver_wallet_id,'credit'=>$w_payment,'description_tkey'=>'Transfer_from','relational_data'=>$accountDetails->member_name);
                    $insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
                        'FW'=>$accountDetails->member_name.' wallet',
                        'TW'=>$site_details->title,
                        'TP'=>'Withdraw',
                        ));
                    insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
                    if($order_fee>0){
                    $insert_wallet_transaction_row=array('wallet_transaction_id'=>$wallet_transaction_id,'wallet_id'=>$fee_wallet_id,'credit'=>$order_fee,'description_tkey'=>'Bank_fee','relational_data'=>$order_fee);
                    $insert_wallet_transaction_row['ref_data_cell']=json_encode(array(
                        'FW'=>$accountDetails->member_name.' wallet',
                        'TW'=>$fee_wallet_details->title,	
                        'TP'=>'Processing_Fee',
                        ));
                    insert_record('wallet_transaction_row',$insert_wallet_transaction_row);
                    }
                    $new_balance=displayamount($current_balance,2)-displayamount($total,2);
                    updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$wallet_id));
                    wallet_balance_check($wallet_id,array('transaction_id'=>$wallet_transaction_id));
                    
                    $new_balance=displayamount($receiver_wallet_balance,2)+displayamount($w_payment,2);
                    updateTable('wallet',array('balance'=>$new_balance),array('wallet_id'=>$receiver_wallet_id));
                    wallet_balance_check($receiver_wallet_id,array('transaction_id'=>$wallet_transaction_id));
                    if($order_fee>0){
                    $new_balance_fee=displayamount($fee_wallet_balance,2)+displayamount($order_fee,2);
                    updateTable('wallet',array('balance'=>$new_balance_fee),array('wallet_id'=>$fee_wallet_id));
                    wallet_balance_check($fee_wallet_id,array('transaction_id'=>$wallet_transaction_id));
                    }
                    $template='withdrawn-request';
                    $data_parse=array(
                        'WITHDRAWN_URL'=>ADMIN_URL.'wallet/withdrawn_list',
                    );
                    SendMail(get_setting('admin_email'),$template,$data_parse);
                    $msg['status'] = 'OK';
                    $this->ReturnStatus=1;
                    $msg['data']['wallet_transaction_id']=$wallet_transaction_id;
                    $msg['data']['message']='Success';
                }else{
                    $msg['status'] = 'FAIL';
                    $msg['error'] = 'Invalid request';
                }	
            }
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }
    
    
    public function post_withdraw_fund(){
		$i=0;
		$msg=array();
        $this->form_validation->set_rules('type', 'type', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');
        $this->form_validation->set_rules('otp', 'otp', 'required');
		$user_wallet_id = get_user_wallet($this->member_id);
		$data['balance']=$balance=get_wallet_balance($user_wallet_id);

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
        if($this->input->post('amount')> $balance)
        {
            $msg['status'] = 'FAIL';
            $msg['errors'][$i]['id'] = 'amount';
            $msg['errors'][$i]['message'] = __('myfinance_transfer_amount_should_not_be_greater_than_your_total_balance','Transfer amount should not be greater than your total balance');
            $i++;  
        }
		if($i==0){
            $post_otp = $this->input->post('otp');
				
            $otp = $this->auto_model->getFeild('otp','user','member_id',$this->member_id);
            $otp_expire_on = $this->auto_model->getFeild('otp_expire_on','user','member_id',$this->member_id);
            $curr_time = time();
            if(strlen($otp) > 0 && $curr_time <= strtotime($otp_expire_on) && ($otp == $post_otp)){
                $post_data=[];
                $post_data['transer_through'] =	$this->input->post('type'); 
                $post_data['admin_pay']= 0;
                $post_data['account_id']= $this->input->post('account_id');
                $post_data['member_id'] =  $this->member_id;
                $post_data['total_amount'] =  $this->input->post('amount');
                $post_data['status'] =  'N';

                $this->load->model('myfinance/transaction_model','transaction_model');	
                $this->load->model('myfinance/myfinance_model','myfinance_model');	
                $user_account_detail = $this->db->where('account_id', $this->input->post('account_id'))->get('user_bank_account')->row_array();
                $this->db->insert("withdrawl",$post_data);
                $withdraw_id = $this->db->insert_id();
                $new_txn_id = $this->transaction_model->add_transaction(WITHDRAW_WALLET_FUND,  $this->member_id, 'P');
                if($withdraw_id && $new_txn_id){
                    $user_wallet_id = get_user_wallet($this->member_id);	
                    $this->transaction_model->add_transaction_row(array('txn_id' => $new_txn_id, 'wallet_id' => $user_wallet_id, 'debit' => $this->input->post('amount'), 'info' => 'Fund Withdraw', 'ref' => json_encode($user_account_detail)));
                    $fname=$this->auto_model->getFeild('fname','user','member_id',$this->member_id);
                    $lname=$this->auto_model->getFeild('lname','user','member_id',$this->member_id);
                    
                    $from=ADMIN_EMAIL;
                    $to=ADMIN_EMAIL;
                    $template='withdrawl_request_notification';
                    $data_parse=array('name'=>$fname." ".$lname
                    );
                    /* $this->auto_model->send_email($from,$to,$template,$data_parse); */
                    Vmailer::send_layout_mail($template, $data_parse, $to);
                    
                    $template='withdrawl_request_user';
                    $to = getField('email','user','member_id',$this->member_id);
                    Vmailer::send_layout_mail($template, $data_parse, $to);
                    
                    $this->ReturnStatus=1;
                    $json['status'] = 1;
                }else{
							
                    $json['status'] = 0;
                    $json['msg'] = __('myfinance_error_on_update_please_try_again','Error on update please try again');
                }

            }else{
					
                $json['status'] = 0;
                $json['msg'] = __('myfinance_invalid_OTP','Invalid OTP');
            }        
            $msg['data']=$json;
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }


    public function get_profile_verification(){
        $this->load->model('user_model', 'user');
        $this->load->model('dashboard/dashboard_model', 'dashboard_model');
        $srch = get();
        $srch['member_id']=$this->member_id;
        $limit=10;
        $page=(get('page')? get('page'):1);
        $start=($page-1)*$limit;
      
        $data = $this->db->where('status', 1)->order_by('doc_type', 'ASC')->get('profile_verification_doc_type')->result();
        if($data){
            foreach($data as $k=>$row){

                $row->doc_side=json_decode($row->doc_side);
                $user_verfication=$this->dashboard_model->get_user_verfication_doc_by_doc_type($row->doc_type_id);
                if($user_verfication && $user_verfication->documents){
                    foreach($user_verfication->documents as $d=>$document){
                        $document->file->file_url=base_url('assets/verification_doc/'.$document->file->filename);
                        $user_verfication->documents[$d]=$document; 
                    }
                    switch($user_verfication->status){
                        case '1':
                            $status = __('profile_verification_approve','Approved');
                            break;
                        
                        case '0':
                            $status = __('profile_verification_pending','Pending');
                            break;
                        
                        case '-1':
                            $status = __('profile_verification_rejected','Rejected');
                            break;
        
                    }
                    $user_verfication->status_name=$status;
                }
                
                $row->user_verfication=$user_verfication;
                $data[$k]=$row; 
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

    public function post_send_document(){
		$i=0;
		$msg=array();
        $this->form_validation->set_rules('doc_type_id', 'type', 'required');
        $this->form_validation->set_rules('attachments', 'attachments', 'required');
      
		
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
            $post_data=[];
            $this->db->where(['doc_type' => post('doc_type_id'), 'member_id' =>  $this->member_id])->delete('profile_verification_user_documents');

            $dbdata = [
                'doc_type' => filter_data(post('doc_type_id')),
                'documents' => post('attachments'),
                'member_id' => $this->member_id,
                'added_date' => date('Y-m-d H:i:s')
            ];
            insert_record('profile_verification_user_documents', $dbdata);

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
    public function post_delete_document(){
       
        $id=$this->input->post('id');
        $msg['status']=1;
        $msg['data']['status']=1;
        if($id){
            $this->db->where(['member_id' => $this->member_id, 'id' => $id])->delete('profile_verification_user_documents');
        }
        $this->ResponseData=$msg;
		$this->ReturnStatus=1;
        $this->response(array(
           "status" =>$this->ReturnStatus,
           "response" =>$this->ResponseData
         ) , 
         $this->ReturnCode);
    }

    public function post_pusher($type=''){
		if($type=='auth'){
			//$channel_id=post('channel_id');
			$channel_name=post('channel_name');
			$socket_id=post('socket_id');
			
			$tokendata=json_decode(base64_decode(post('tokendata')));
			$member_id=$tokendata->member_id;
			$user_info=$tokendata->user_info;
			
			//$this->load->library('pusher');
			$this->load->library('pusher');
			$pusher=$this->pusher->load();
			$res=$pusher->presence_auth($channel_name,$socket_id, $member_id, $user_info);
			echo $res;
		}
	}
    public function post_getroom(){
		$booking_id=post('booking_id');
		
		$conversation_id=getFieldData('conversations_id','chat','booking_id',$booking_id);
        if(!$conversation_id){
            $booking_services=$this->db->select('member_id,provider_id')->from('booking_services')->where('booking_id',$booking_id)->get()->row();
            $chat=array(
				'booking_id'=>$booking_id,
				'member_id'=>$booking_services->member_id,
				'worker_id'=>$booking_services->provider_id
			);
			$conversation_id=insert_record('chat',$chat,TRUE);
        }
        
		$data['room_id']=$conversation_id;
		$data['channel_id']='channel_room_'.$conversation_id;
		$result['data']=$data;

		$this->ReturnStatus=1;
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
	}
    public function get_room_member(){
		
		$channel_id=get('chatroom');
		$conversation_id=str_replace('channel_room_','',$channel_id);
		if($conversation_id){
          $member_id=getFieldData('member_id','chat','conversations_id',$conversation_id);  
          $worker_id=getFieldData('worker_id','chat','conversations_id',$conversation_id);  

           $memberDetails=getData(array(
					'select'=>'m.member_name as name,m.member_email as email,m.member_id as id',
					'table'=>'member m',
					'single_row'=>true,
					'where'=>array('m.member_id'=>$member_id),
			));
            if (!$memberDetails) {
                $memberDetails = new stdClass();
                $memberDetails->name = '';
                $memberDetails->email = '';
                $memberDetails->id = $member_id;
            }
            $memberDetails->logo=getMemberLogo($member_id);
            
            $WorkerDetails=getData(array(
					'select'=>'m.worker_name as name,m.worker_email as email,m.worker_id as id',
					'table'=>'worker m',
					'single_row'=>true,
					'where'=>array('m.worker_id'=>$worker_id),
			));
            if (!$WorkerDetails) {
                $WorkerDetails = new stdClass();
                $WorkerDetails->name = '';
                $WorkerDetails->email = '';
                $WorkerDetails->id = $worker_id;
            }
            $WorkerDetails->logo=getWorkerLogo($worker_id);
            $conversationMemberData['worker']=$WorkerDetails;
            $conversationMemberData['customer']=$memberDetails;
			
			$result['room_member']=$conversationMemberData;
		}
		$this->ReturnStatus=1;
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
	}
    
    public function get_message_list(){
        $this->load->model('user_model');
		$data=array();
		$limit=10;
		$page=(get('page')? get('page'):1);
		$start=($page-1)*$limit;
		$channel_id=get('chatroom');
		$conversation_id=str_replace('channel_room_','',$channel_id);
		$where=array();
		if($conversation_id){
			$data['current_page']=$page;
			$where['chat_id']=$conversation_id;
			$data['total_message']=$this->user_model->getChatMessage($conversation_id,'','',FALSE);
			$data['total_page']=ceil($data['total_message']/$limit);
			$all_mesages=$this->user_model->getChatMessage($conversation_id,$start,$limit);
			if($all_mesages){
				 foreach($all_mesages as $k=>$mesages){
                    if($mesages->member_id){
                        $sender_profile=getData(array(
                                'select'=>'m.member_name as name,m.member_email as email,m.member_id as id',
                                'table'=>'member m',
                                'single_row'=>true,
                                'where'=>array('m.member_id'=>$mesages->member_id),
                        ));
                        if (!$sender_profile) {
                            $sender_profile = new stdClass();
                            $sender_profile->name = '';
                            $sender_profile->email = '';
                            $sender_profile->id = $mesages->member_id;
                        }
                        $sender_profile->logo=getMemberLogo($mesages->member_id);
                    }else{
                        $sender_profile=getData(array(
                                'select'=>'m.worker_name as name,m.worker_email as email,m.worker_id as id',
                                'table'=>'worker m',
                                'single_row'=>true,
                                'where'=>array('m.worker_id'=>$mesages->worker_id),
                        ));
                        if (!$sender_profile) {
                            $sender_profile = new stdClass();
                            $sender_profile->name = '';
                            $sender_profile->email = '';
                            $sender_profile->id = $mesages->worker_id;
                        }
                        $sender_profile->logo=getWorkerLogo($mesages->worker_id);
                    }

					$mesages->sender_profile=$sender_profile;
                   	
					$all_mesages[$k]=$mesages;
				}
                
			}
			$data['all_mesages']=$all_mesages;
		}
		$result['data']=$data;
		$this->ReturnStatus=1;
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
	}
    public function post_sendmessage(){
        $this->load->model('user_model');
        $this->load->model('app/app_model');
		$msg=$last_data=$pusherData=array();
		$this->load->library('pusher');
		$pusher=$this->pusher->load();
		$channel_id=post('channel_id');
		$reply_to=post('parent_id');
        $message_post = post('message');
        $attachment = post('attachment');
		$conversation_id=str_replace('channel_room_','',$channel_id);
        $msg['status']=0;
		if($conversation_id){
			$member_id=$this->member_id;
			$worker_id=$this->worker_id;
            
			if($member_id || $worker_id){
                    $message = array(
                        'member_id' => $member_id,
                        'worker_id' => $worker_id,
                        'conversations_id' => $conversation_id,
                        'message' => $message_post,
                        'sending_date' => date('Y-m-d H:i:s'),
                    );
                    $message_id=insert_record('chat_message',$message,TRUE);
                    if($message_id){
                       $pusherData=array(
                            'roomid'=>$channel_id,
                            'member_id'=>$member_id,
                            'worker_id'=>$worker_id,
                            'message'=>$message_post,
                            'type'=>'TEXT'
                        ); 
                        $msg['message_id']=$message_id;
                        $msg['message_data']=$message;
                        $msg['status']=1;

                        // onesignal notification
                        $short_msg = (strlen($message_post) > 15) ? substr($message_post, 0, 15) . '..' : $message_post;
                        $booking_id = getField('booking_id','chat','conversations_id ',$conversation_id);
                        if($member_id){
                            $bookingData = $this->db
                                ->select("
                                    m.member_name,
                                    sc.category_subchild_name
                                ")
                                ->from('pref_booking_services b')
                                ->join('pref_member m','m.member_id = b.member_id','left')
                                ->join(
                                    'pref_category_subchild_names sc',
                                    'sc.category_subchild_id = b.sub_cat_id AND sc.category_subchild_lang = "en"',
                                    'left'
                                )
                                ->where('b.booking_id', $booking_id)
                                ->get()
                                ->row_array();
                            $send_to = 'W';
                            $send_to_id = getField('worker_id','chat','conversations_id',$conversation_id);
                            // $worker_name = getField('worker_name','worker','worker_id',$send_to_id);
                            $member_name = getField('member_name','member','member_id',$send_to_id);
                            $oneSignalData= array(
                                'heading' => $member_name,
                                'content' => $short_msg,
                                'data' => [
                                    'screen' => 'chat',
                                    'channel_id' => $channel_id,
                                    'member_name' => $bookingData['member_name'],
                                    'category_subchild_name' => $bookingData['category_subchild_name'],
                                ]
                            );
                            
                        }else{
                            $send_to = '';
                            $send_to_id = getField('member_id','chat','conversations_id',$conversation_id);
                            // $member_name = getField('member_name','member','member_id',$send_to_id);
                            $worker_name = getField('worker_name','worker','worker_id',$send_to_id);
                            $pro_det = [
                                "name" => getField('worker_name','worker','worker_id',$worker_id),
                                "logo" => getWorkerLogo($worker_id),
                            ];
                            $oneSignalData= array(
                                'heading' => $worker_name,
                                'content' => $short_msg,
                                'data' => [
                                    'screen' => 'chat',
                                    'channel_id' => $channel_id,
                                    'booking_id' => $booking_id,
                                    'providerDetails' => $pro_det,
                                ]
                            );
                        }
                        
                        $msg['onesignal']=$this->app_model->sendpush_OneSignal($oneSignalData,$send_to_id,$send_to);

                    }
			}
		}
		if($msg['status']==1){
			$pusherData['message_id']=$msg['message_id'];
			$pusherData['created_datetime']=date('Y-m-d H:i:s');
			
			if($message['member_id']){
                $sender_profile=getData(array(
                        'select'=>'m.member_name as name,m.member_email as email',
                        'table'=>'member m',
                        'single_row'=>true,
                        'where'=>array('m.member_id'=>$message['member_id']),
                ));
                if ($sender_profile) {
                    $sender_profile->logo=getMemberLogo($message['member_id']);
                }
            }else{
                $sender_profile=getData(array(
                        'select'=>'m.worker_name as name,m.worker_email as email',
                        'table'=>'worker m',
                        'single_row'=>true,
                        'where'=>array('m.worker_id'=>$message['worker_id']),
                ));
                if ($sender_profile) {
                    $sender_profile->logo=getWorkerLogo($message['worker_id']);
                }
            }
            		
			$pusherData['sender_profile']=$sender_profile;
			$res=$pusher->trigger($channel_id, 'post_message',$pusherData);
			$msg['pusher']=$res;
			
		}
		$msg['pusherData']=$pusherData;

      /*   $pusherDataGlobal=[
            'user_id'=>$chat_member->user_id,
            'channel_id'=>$channel_id,
            'unread_message'=>$this->message_model->getUnreadMsgCount($chat_member->user_id,$conversation_id)
        ];
        $msg['pusherGlobal']=$pusher->trigger('message_sendby_global', 'post_message',$pusherDataGlobal);
        */
        

		echo json_encode($msg);
	}
    public function get_read_message(){
        $this->load->model('message/message_model','message_model');
		$member_id=get('member_id');
		$channel_id=get('chatroom');
		$conversation_id=str_replace('channel_room_','',$channel_id);

        $this->message_model->markAsRead($conversation_id, $member_id);
        $this->message_model->updateSeenStatus($member_id, $conversation_id);
        updateTable('conversations_message',array('is_read'=>'1'),array('conversations_id'=>$conversation_id,'sender_id <>'=>$member_id));

		$result['status']=1;
        $this->ReturnStatus=1;
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
	}
	public function get_just_read(){
		$user_id=get('member_id');
		$receiver_id=get('receiver_id');
		$message_id=get('message_id');
		$channel_id=get('chatroom');
		$conversation_id=str_replace('channel_room_','',$channel_id);
		$this->load->library('pusher');
		$pusher=$this->pusher->load();
		$pusherDataGlobal=[
			'message_id'=>$message_id,
			'receiver_id'=>$receiver_id,
		];
		$pusher->trigger($channel_id, 'just_read',$pusherDataGlobal);
		//$last_message = getField('last_msg', 'chats', 'chat_id', $conversation_id);
		updateTable('conversations_message',array('is_read'=>'1'),array('message_id'=>$message_id));
		//$this->db->where(['chat_id' => $conversation_id, 'user_id' => $receiver_id])->update('chat_member', ['last_seen_msg' => $last_message]);
		$result['status']=1;
        $this->ReturnStatus=1;
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
	}
    public function post_delete_msg(){
		$channel_id=post('chatroom');
		$message_id=post('message_id');
		$conversation_id=str_replace('channel_room_','',$channel_id);
        $this->db->where(['sender_id' => $this->member_id, 'message_id' => $message_id])->update('conversations_message', array('is_deleted' => date('Y-m-d H:i:s')));
        $result=array(
            'status' => 1,
            'deleted' => date('Y-m-d H:i:s'),
            'msg_txt' => 'This message is deleted ('.date('d M, Y h:i A').')'
		);
        $this->ReturnStatus=1;
        $this->ResponseData=$result;
        
        $this->response(array(
            "status" =>$this->ReturnStatus,
            "response" =>$this->ResponseData
        ) , 
        $this->ReturnCode);
    }

    public function get_makepayment(){
        header('content-type: text/html; charset=UTF-8');
        $this->data=[];
        $data=[
            'member_id'=>$this->member_id,
            'originatesoft_lang'=>$this->input->get('originatesoft_lang'),
        ];
        $type=$this->input->get('paymentmode');
        $amount=$this->input->get('amount');
        

        $member_email=getField('member_email','member','member_id',$this->member_id);
        
        $site_currency_code=CurrencyCode();
        if($type=='paypal'){
            $unique_id=$this->member_id.'-'.time();	
            $feeCalculation=generateProcessingFee('paypal',$amount);
            $processing_fee=$feeCalculation['processing_fee'];
            $this->data['formdata']=array(
                'amount'=>$amount+$processing_fee,
                'org_amt'=>$amount,
                'fee'=>$processing_fee,
                'return_url'=>base_url('app/user/payment_success/'.$this->member_id),
                'cancel_url'=>base_url('app/user/payment_failed/'.$this->member_id),
                'notify_url'=>get_link('PaypalNotify').'addfund/'.$unique_id,
                'custom'=>md5('PPAY-'.$unique_id),
                'member_id'=>$this->member_id,
            );
            $transansaction_data=array('payment_type'=>'PAYPAL','content_key'=> $this->data['formdata']['custom']);
            $amount= $this->data['formdata']['amount'];
            
            if($site_currency_code=='AED'){
                $conversion=get_setting('AED_TO_USD');
                $site_currency_code='USD';
                $amount=displayamount( $this->data['formdata']['amount']*$conversion);
            }
            $this->data['formdata']['amount_converted']=$amount;
            
            $transansaction_data['request_value']=json_encode( $this->data['formdata']);
            insert_record('online_transaction_data',$transansaction_data);
            $site_paypal=get_setting('paypal_email');
            $is_sandbox=get_setting('is_sandbox');
            $url = 'https://www.'.($is_sandbox ? 'sandbox.':'').'paypal.com/cgi-bin/websc?cmd=_xclick&currency_code='.$site_currency_code.'&notify_url='.$this->data['formdata']['notify_url'].'&return='.$this->data['formdata']['return_url'].'&cancel_return='.$this->data['formdata']['cancel_url'].'&business='.$site_paypal.'&amount='.$amount.'&custom='.$this->data['formdata']['custom'].'&item_name=Add Cash in Account';
            
            redirect($url);
        }elseif($type=='stripe'){
            $unique_id=$this->member_id.'-'.time();	
            $feeCalculation=generateProcessingFee('stripe',$amount);
            $processing_fee=$feeCalculation['processing_fee'];
            $this->data['formdata']=array(
                'amount'=>$amount+$processing_fee,
                'org_amt'=>$amount,
                'fee'=>$processing_fee,
                //'notify_url'=>get_link('StripeNotify').$type.'/'.$unique_id,
                'custom'=>md5('PPAY-'.$unique_id),
                'member_id'=>$this->member_id,
                'type'=>'addfund'
            );
            $this->data['formdata']['amount_converted']=$this->data['formdata']['amount'];
			$transansaction_data=array('payment_type'=>'STRIPE','content_key'=> $this->data['formdata']['custom']);
			$transansaction_data['request_value']=json_encode( $this->data['formdata']);
			
			$amount=$this->data['formdata']['amount'];
            $this->load->library('stripe');
			$stripe = $this->stripe->load();
            $checkout_session = \Stripe\Checkout\Session::create([
				'payment_method_types' => ['card'], //alipay, card, ideal, fpx, bacs_debit, bancontact, giropay, p24, eps, sofort, sepa_debit, grabpay, or afterpay_clearpay
				'line_items' => [[
				  'price_data' => [
					'currency' => CurrencyCode(),
					'unit_amount' => $amount*100,
					'product_data' => [
					  'name' => 'Add Fund From '.get_setting('website_name'),
					  //'images' => [LOGO],
					],
				  ],
				  'quantity' => 1,
				]],
				'client_reference_id'=>$this->data['formdata']['custom'],
				'mode' => 'payment',
				'success_url' => base_url('app/user/payment_success/'.$this->member_id),
				'cancel_url' => base_url('app/user/payment_failed/'.$this->member_id),
			  ]);
			  $this->data['checkout_session']=$checkout_session;
			$ins=insert_record('online_transaction_data',$transansaction_data,TRUE);
            $this->load->view('stripe',$this->data);
        }else{
            echo 'error';
        }
    }
    public function get_payment_success($member_id) {
        header('content-type: text/html; charset=UTF-8');
        $this->load->view('payment_success');
        $this->load->library('pusher');
        $pusher=$this->pusher->load();
        $pusherDataGlobal=[
            'member_id'=>$member_id,
        ];
        $pusher->trigger('payment_acknowledgement_'.$member_id, 'success',$pusherDataGlobal);
    }
    public function get_payment_failed($member_id) {
        header('content-type: text/html; charset=UTF-8');
        $this->load->view('payment_failed');
        $this->load->library('pusher');
        $pusher=$this->pusher->load();
        $pusherDataGlobal=[
            'member_id'=>$member_id,
        ];
        $pusher->trigger('payment_acknowledgement_'.$member_id, 'error',$pusherDataGlobal);
    }







    public function get_notification_count(){
        $member_id=$this->input->get('member_id');
        $msg['status']=1;
        $msg['data']['unread']=$this->db->where('notification_to',$member_id)->where('read_status',0)->from('member_notifications')->count_all_results();
        $this->ResponseData=$msg;
		$this->ReturnStatus=1;
        $this->response(array(
           "status" =>$this->ReturnStatus,
           "response" =>$this->ResponseData
         ) , 
         $this->ReturnCode);
    }
    public function get_notifications(){
        $member_id=$this->input->get('member_id');
        $msg['status']=1;
        
        $this->load->library('parser');
        $active_lang = get_active_lang();
        if(!$active_lang){
            $active_lang = 'en';
        }
        
        $rows = $this->db->select('m_n.*, n.template_content')
            ->from('member_notifications m_n')
            ->join('notifications_template n_t', 'n_t.template_key=m_n.notification_template_key', 'LEFT')
            ->join('notifications_template_names n', 'n.notification_template_id=n_t.notification_template_id', 'LEFT')
            ->where('n.lang', $active_lang)
            ->where('m_n.notification_to', $member_id)
            ->where('m_n.read_status', 0)
            ->order_by('m_n.notification_id', 'DESC')
            ->get()->result();
            
        $data = array();
        if($rows){
            foreach($rows as $v){
                if($v->template_content){
                    $notification_string_template = $v->template_content;
                }else{
                    $notification_string_template = '';
                }
                $parse_data = !empty($v->template_data) ? (array) json_decode($v->template_data) : array();
                $notification_text = $this->parser->parse_string($notification_string_template, $parse_data, TRUE);
                
                $data[] = array(
                    'id' => $v->notification_id,
                    'to_id' => $v->notification_to,
                    'from_id' => $v->notification_from,
                    'notification' => $notification_text,
                    'read_status' => 'N',
                    'link' => $v->link,
                    'date' => $v->sent_date
                );
            }
        }
        
        $msg['data']=$data;
        $this->ResponseData=$msg;
		$this->ReturnStatus=1;
        $this->response(array(
           "status" =>$this->ReturnStatus,
           "response" =>$this->ResponseData
         ) , 
         $this->ReturnCode);
    }
    public function post_update_notifications(){
        $member_id=$this->input->post('member_id');
        $ids=$this->input->post('id');
        $msg['status']=1;
        $msg['data']['status']=1;
        if($ids){
             $this->db->where('notification_to',$member_id)->where_in('notification_id',$ids)->update('member_notifications',array('read_status'=>1));
             $this->load->model('notification/notification_model');
             $this->notification_model->notify_unset($member_id);
        }
        $this->ResponseData=$msg;
		$this->ReturnStatus=1;
        $this->response(array(
           "status" =>$this->ReturnStatus,
           "response" =>$this->ResponseData
         ) , 
         $this->ReturnCode);
    }

    public function post_create_token_video(){
		$msg['status']=0;
		$booking_id=$this->input->post('booking_id');
		$roomkey=md5($booking_id);
		$caller_id=0;
        if($this->member_id){
            $caller_id=$this->member_id;
            $call_by='customer';
            $caller_name = getFieldData('member_name', 'member', 'member_id', $caller_id);
            $caller_logo = getMemberLogo($caller_id);
        }else{
            $caller_id=$this->worker_id;
            $call_by='worker';
            $caller_name = getFieldData('worker_name', 'worker', 'worker_id', $caller_id);
            $caller_logo = getWorkerLogo($caller_id);
        }


        $booking_services=$this->db->select('member_id,provider_id')->from('booking_services')->where('booking_id',$booking_id)->get()->row();
        $chat=array(
            'booking_id'=>$booking_id,
            'member_id'=>$booking_services->member_id,
            'worker_id'=>$booking_services->provider_id
        );
		
        $curl = curl_init();
        $data = array(
            "region" => "sg001",
            "customRoomId" =>$roomkey,
            /* "webhook" => array(
                'endPoint'=> base_url('app/video_endpoint').'/'.$roomkey,
                'events'=> ['session-started', 'session-ended']
            ), */
        );
        $token="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiIwODNkODE4OC1mZTE5LTQ3YzYtYWEwMi03MTRkNjliNDNmNjkiLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTc3MjI2NTU0NCwiZXhwIjoxODAzODAxNTQ0fQ.DnnKXMZDTMT8lWUhJF-gU-oSSR5KV9BShoePMB7jmNI";
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.videosdk.live/v2/rooms",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$token,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($data),
        ));
        $responsed = curl_exec($curl);
        curl_close($curl);
        $res=json_decode($responsed);
        $msg['status']=0;
        if($res){
            if($res->roomId){
                //$this->db->insert('call_requests', ['caller_id'=>$this->user_id,'receiver_id'=>$other_user_id,'channel_id'=>$chatroom_id,'room_id'=>$res->roomId,'created_at'=>date('Y-m-d H:i:s')]);
                //$request_id = $this->db->insert_id();

                $this->load->library('pusher');
                $pusher=$this->pusher->load();
                $pusher->trigger("audio-video-call", "incoming_call", [
                    "caller_id" => $caller_id,
                    'call_by'=>$call_by,
                    "booking_id"  => $booking_id,
                    "roomId"=>$res->roomId,
                    "customer_id"=>$booking_services->member_id,
                    "provider_id"=>$booking_services->provider_id,
                    'sender_name'=>$caller_name,
                    'sender_logo'=>$caller_logo,
                ]);
                $msg['status']=1;
                $msg['data']=$res;
            }else{
                $msg['message']="Failed to create token";
            }
        }else{
            $msg['message']="Failed to create token";
        }
		
		//$this->response=$msg;
		echo json_encode($msg);	

	}
    public function post_end_call() {
		$booking_id=$this->input->post('booking_id');
		$roomId=$this->input->post('roomId');
        $caller_id=0;
        if($this->member_id){
            $caller_id=$this->member_id;
            $call_by='customer';
        }else{
            $caller_id=$this->worker_id;
            $call_by='worker';
        }
		$this->load->library('pusher');
        $pusher=$this->pusher->load();
        $pusher->trigger("end-video-call", "end_call", [
           "caller_id" => $caller_id,
            'call_by'=>$call_by,
            "booking_id"  => $booking_id,
            "roomId"  => $roomId,
        ]);
		$msg['status']=1;
		$this->response=$msg;
		echo json_encode($this->response);
		
	}
    public function get_transaction_history_worker(){
        $this->load->model('finance/finance_model', 'finance_model');
        $srch = get();
        $srch['worker_id']=$this->worker_id;
        $offset = 10;
        $page=(get('page')? get('page'):1);
        $limit=($page-1)*$offset;
        $data['member_wallet']=getWalletWorker($this->worker_id);
		if($data['member_wallet']){
			$wallet_id=$data['member_wallet']->wallet_id;
			$balance=$data['member_wallet']->balance;
		}
        $data['current_balance']=$balance;
		$data['total_debit']=$this->finance_model->wallet_debit_balance($wallet_id);
		$data['total_credit']=$this->finance_model->wallet_credit_balance($wallet_id);
       
		$srch['wallet_id'] = $wallet_id; 
		
        $all= $this->finance_model->getTransaction($srch, $limit, $offset);;
         /* if($all){
            foreach($all as $k=>$row){
               
               
            }
        } */
        $data = array(
            'list' => $all,
            'total' => $this->finance_model->getTransaction($srch, $limit, $offset, FALSE),
        );
     
        $data['total_page']=ceil($data['total']/$offset);
        $data['current_page']=$page;
        
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
    public function get_worker_profile(){
		$msg=array();
		if($this->worker_id){
			$this->db->select('worker_id,worker_name,worker_email,worker_phone,worker_alt_phone,worker_whatsapp,worker_gender,worker_dob,worker_religion')
			->from('worker')
			->where('worker_id', $this->worker_id);
            $profile=$this->db->get()->row_array();
            $profile['logo']=getWorkerLogo($this->worker_id);
            $profile['location']=$this->db->select('worker_state,worker_city,worker_address,worker_flat,worker_street,worker_pincode,worker_landmark')->from('worker_address')->where('worker_id',$this->worker_id)->get()->row_array();
            $profile['services']=$this->db->select('category_subchild_id')->from('worker_service')->where('worker_id',$this->worker_id)->get()->result_array();
			$msg['data'] = array(
				'profile_det'=> $profile,
			);
			$this->ReturnStatus =1;
		}
		
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
	}
    public function get_worker_balance(){
      
        $msg['status']=1;
        $wallet=getWalletWorker($this->worker_id);
        $msg['data']['balance']=($wallet  ? $wallet->balance:0);
        $this->ResponseData=$msg;
		$this->ReturnStatus=1;
        $this->response(array(
           "status" =>$this->ReturnStatus,
           "response" =>$this->ResponseData
         ) , 
         $this->ReturnCode);
    }
    public function post_set_availability(){
      
        $msg['status']=1;
        $is_offline=( $this->input->post('offline') ? 1:0);
        $this->db->where('worker_id',$this->worker_id)->update('worker',['is_offline'=>$is_offline]);
        $msg['data']['is_offline']=$is_offline;
        $this->ResponseData=$msg;
		$this->ReturnStatus=1;
        $this->response(array(
           "status" =>$this->ReturnStatus,
           "response" =>$this->ResponseData
         ) , 
         $this->ReturnCode);
    }
   
    public function post_send_review(){
         $this->load->library('form_validation');
         $this->form_validation->set_data($this->_args);
		$i=0;
		$msg=array();
		$this->form_validation->set_rules('booking_id', 'booking_id', 'required');
		$this->form_validation->set_rules('behaviour', 'Behaviour', 'required');
		$this->form_validation->set_rules('punctuality', 'Punctuality', 'required');
		$this->form_validation->set_rules('quality', 'Quality of work ', 'required');
		$this->form_validation->set_rules('comment', 'feedback ', 'trim');

		
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
           
            $booking_id = $this->_args['booking_id'] ?? post('booking_id');
            $check=$this->db->select('status,booking_date,booking_time,member_id,provider_id')->from('booking_services')->where('booking_id', $booking_id)->where('status', 4)->get()->row();
            $this->db->where('project_id',$booking_id)->delete('contract_reviews');
            $contract_reviews=array(
                'project_id'=>$booking_id,
                'contract_id'=>0,
                'review_by'=>$check->member_id,
                'review_to'=>$check->provider_id,

                //'for_skills'=>post('skills'),
                'for_quality'=>$this->_args['quality'] ?? post('quality') ?? 0,
                //'for_availability'=>post('availability'),
                'for_deadlines'=>$this->_args['punctuality'] ?? post('punctuality') ?? 0,
                'for_communication'=>$this->_args['behaviour'] ?? post('behaviour') ?? 0,
                //'for_cooperation'=>post('cooperation'),

                'review_status'=>1,
                'is_display_public'=>0,
                'review_comments'=>$this->_args['comment'] ?? post('comment') ?? '',
                'review_date'=>date('Y-m-d H:i:s'),
            );
            $total_review=$contract_reviews['for_quality']+$contract_reviews['for_deadlines']+$contract_reviews['for_communication'];
            $average_review=displayamount($total_review/3,2);
            $contract_reviews['average_review']=$average_review;
            $review_id=insert_record('contract_reviews',$contract_reviews,TRUE);
            if($review_id){
                $this->ReturnStatus=1;
                $msg['status']=1;
                $msg['data']['review_id']=$review_id;
                $msg['message']='Success';
            }else{
                $msg['status']=0;
                $msg['message']='failed to create review';
            }
            
    		
    	}
		$this->ResponseData=$msg;
		$this->response(array(
			"status" =>$this->ReturnStatus,
			"response" =>$this->ResponseData
		) , $this->ReturnCode);
    }
     public function get_join_call($roomkey='',$request_id=''){
        $data=[];
         header('content-type: text/html; charset=UTF-8');
        $data['apiKey']='083d8188-fe19-47c6-aa02-714d69b43f69';
        $data['meetingId']=$roomkey;
        $data['meetingtype']='audio';
       /*  $this->data['meetingdtl'] = get_row([
            'select' => 'id,channel_id',
            'from' => 'call_requests',
            'where' => ['id' => $request_id]
            ]); */
		$this->load->view('video', $data);	
	}
    
}