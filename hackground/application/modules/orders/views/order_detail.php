 <style>
 .card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
}
.card {
    margin-bottom: 1.5rem;
    border-radius: 0;
}
.card-body {
    padding: 15px;
}
 .card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
}
 .message-div {
    border: 1px solid #e6e6e6;
    padding: 12px 20px 10px 20px;
}
.message-div .message-image {
    float: left;
    width: 45px;
    height: 45px;
    margin-right: 12px;
    border-radius: 50%;
}
.message-div .message-desc {
    margin-left: 56px;
}
.text-muted {
    color: #868e96!important;
}
.float-right {
    float: right!important;
}
.order-status-message {
	
text-align:center;	

padding:25px 20px;

	
}
 </style>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
        <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
     <?php echo $breadcrumb ? $breadcrumb : '';?>
    </section>

    <!-- Main content -->
    <section class="content">
<?php
//print_r($orderDetails);
$currency=get_setting('site_currency');
$orderStatus=array(
'0'=>'Not Paid',
'1'=>'Pending',
'2'=>'Process',
'3'=>'Revision requested',
'4'=>'Cancellation requested',
'5'=>'Cancelled',
'6'=>'Delivered',
'7'=>'Completed',
);
?>
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

          <div class="box-tools pull-right">
			Order Number: #<?php echo $orderDetails->order_number; ?>
          </div>
        </div>

       	<div class="card">
            <div class="card-body">
                <div class="">
                    <div class="col-md-2">
            			<img src="<?php echo USER_UPLOAD;?>proposal-files/<?php echo $orderDetails->proposal_image; ?>" class="img-responsive">
                    </div>
                    <div class="col-md-10">
                        <h1 class="text-success pull-right d-lg-block d-md-block d-none"><?php echo $currency ; ?><?php echo $orderDetails->order_price; ?></h1>
			            <h4>
			                Order #<?php echo $orderDetails->order_number; ?><small>
			                  <a href="<?php echo URL?>proposals/view/<?php echo $orderDetails->seller_user_name; ?>/<?php echo $orderDetails->proposal_url; ?>" target="_blank" class="text-success">
			                    	View Proposal/Service
			                  </a>
			                </small>
			            </h4>
			            <p class="text-muted">
			                <span class="font-weight-bold">Buyer: </span>
			                <a href="<?php echo URL?>p-<?php echo $orderDetails->buyer_user_name; ?>" target="_blank" class="seller-buyer-name mr-1 text-success">
			                <?php echo ucfirst($orderDetails->buyer->member_name); ?>	
			                </a> 
			                | <span class="font-weight-bold">Seller: </span>
			                <a href="<?php echo URL?>p-<?php echo $orderDetails->seller_user_name; ?>" target="_blank" class="seller-buyer-name mr-1 text-success">
			                <?php echo ucfirst($orderDetails->seller->member_name); ?>	
			                </a> 
			                | <span class="font-weight-bold ml-1"> Status: </span>  <?php echo $orderStatus[$orderDetails->order_status];?>
			                | <span class="font-weight-bold ml-1"> Date: </span> <?php echo date('F d,Y',strtotime($orderDetails->order_date)); ?>            
			            </p>
                    </div>
      			</div>
		      <div class="row d-lg-flex d-md-flex d-none">
		      	<div class="col-md-12">
		        	<table class="table table-bordered mt-3">
		          		<thead>
				            <tr>
				              <th>Item</th>
				              <th>Quantity</th>
				              <th>Duration</th>
				              <th>Amount</th>
				            </tr>
				        </thead>
				        <tbody>
		            <tr>
		              	<td class="font-weight-bold" width="600"><?php echo $orderDetails->proposal_title; ?>
		              	<?php 
			              if($orderDetails->extra){
			              ?>
			              <ul class="ml-5" style="list-style-type: circle;">
			              <?php
			              foreach($orderDetails->extra as $extra){
			              ?>
			              <li class="font-weight-normal text-muted">
			                <?php echo $extra->name; ?> (+<span class="price"><?php echo $currency.$extra->price; ?></span>)
			              </li>
			              <?php } ?>
			              </ul>
			              <?php } ?>
		              	</td>
		             	<td><?php echo $orderDetails->order_qty; ?></td>
		             	<td><?php echo $orderDetails->order_duration; ?>days</td>
		              <td> <?php echo $currency; ?><?php echo $orderDetails->order_price; ?></td>
		            </tr>
		            <?php if(!empty($orderDetails->order_fee)){ ?>
			        <tr>
			              <td>Processing Fee</td>
			              <td></td>
			              <td></td>
			              <td><?php echo $currency; ?><?php echo $orderDetails->order_fee; ?></td>
			        </tr>
	            <?php } ?>
		             <tr>
		              	<td colspan="4">
			              <span class="pull-right mr-4">
			                <strong>Total : </strong>
			                <?php echo $currency; ?><?php echo ($orderDetails->order_price+$orderDetails->order_fee); ?>
			              </span>
			              </td>
		            </tr>
		          </tbody>
		        </table>
		         </div>
		    </div>
  	</div>
</div>	
	<?php
	//print_r($orderDetails->conversation);
	?>
	<div class="row mt-4">
    <!--- 3 row Starts --->
    <div class="col-lg-12">
        <!--- col-lg-12 Starts --->
        <div class="card">
            <!--- card Starts --->
            <div class="card-header">
                <!--- card-header Starts --->
                <h4 class="h4">
                    <i class="fa fa-money-bill-alt"></i> Order Conversation Between Buyer & Seller
                </h4>
            </div>
            <!--- card-header Ends --->
            <div class="card-body">
                <!--- card-body Starts --->
                <?php
			if($orderDetails->conversation){
				foreach($orderDetails->conversation as $k=>$conversation){
					$status=$conversation->status;
					if($conversation->sender_id==$orderDetails->seller_id){
						$sender_user_name=$orderDetails->seller->member_name;
						$receiver_name=$orderDetails->buyer->member_name;
					}else{
						$sender_user_name=$orderDetails->buyer->member_name;
						$receiver_name=$orderDetails->seller->member_name;
					}
				?>
				<?php
				if($status=='message'){
				?>
				 <div class="message-div">
                        <img src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image">
                        <h5><?php echo $sender_user_name; ?></h5>
                        <p class="message-desc">
                            <?php echo $conversation->message; ?>
                           <?php if(!empty($conversation->file)){ ?>
                            <a href="<?php echo UPLOAD_HTTP_PATH.'conversation-files/'.$conversation->file?>" download class="d-block mt-2 ml-1">
                                <i class="fa fa-download"></i> <?php echo $conversation->file; ?>
                            </a>
                            <?php }?>
                        </p>
                        <p class="float-right text-muted">
                            <?php echo date('F d, Y H:i:s',strtotime($conversation->date)); ?> 
                        </p>
                        <br>
                    </div>	
				<?php }
				elseif($status == "delivered"){ ?>
				<h2 class="mt-3 mb-4" align="center"> Order Delivered </h2>
                    <div class="message-div">
                        <img src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image">
                        <h5><?php echo $sender_user_name; ?></h5>
                        <p class="message-desc">
                         <?php echo $conversation->message; ?>
                           <?php if(!empty($conversation->file)){ ?>
                            <a href="<?php echo UPLOAD_HTTP_PATH.'conversation-files/'.$conversation->file;?>" download class="d-block mt-2 ml-1">
                                <i class="fa fa-download"></i> <?php echo $conversation->file; ?>
                            </a>
                            <?php }?>
                        </p>
                        <p class="float-right text-muted">
                            <?php echo date('F d, Y H:i:s',strtotime($conversation->date)); ?> 
                        </p>
                        <br>
                    </div>
				<?php }
				elseif($status == "revision"){ ?>
				<h2 class="mt-3 mb-4" align="center"> Revision Request By <?php echo $sender_user_name; ?> </h2>
				 <div class="message-div">
                        <img src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image">
                        <h5><?php echo $sender_user_name; ?></h5>
                        <p class="message-desc">
                         <?php echo $conversation->message; ?>
                           <?php if(!empty($conversation->file)){ ?>
                            <a href="<?php echo UPLOAD_HTTP_PATH.'conversation-files/'.$conversation->file;?>" download class="d-block mt-2 ml-1">
                                <i class="fa fa-download"></i> <?php echo $conversation->file; ?>
                            </a>
                            <?php }?>
                        </p>
                        <p class="float-right text-muted">
                            <?php echo date('F d, Y H:i:s',strtotime($conversation->date)); ?> 
                        </p>
                        <br>
                    </div>

				<?php }
				elseif($status == "cancellation_request"){ ?>
				<h2 class="mt-3 mb-4" align="center"> Order Cancellation Request By <?php echo $sender_user_name; ?> </h2>
                  <div class="message-div">
                        <img src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image">
                        <h5><?php echo $sender_user_name; ?></h5>
                        <p class="message-desc">
                         <?php echo $conversation->message; ?>
                           <?php if(!empty($conversation->file)){ ?>
                            <a href="<?php echo UPLOAD_HTTP_PATH.'conversation-files/'.$conversation->file;?>" download class="d-block mt-2 ml-1">
                                <i class="fa fa-download"></i> <?php echo $conversation->file; ?>
                            </a>
                            <?php }?>
                        </p>
                        <p class="float-right text-muted">
                            <?php echo date('F d, Y H:i:s',strtotime($conversation->date)); ?> 
                        </p>
                        <br>
                    </div>
                    <div class="order-status-message">
                        <!--- order-status-message Starts --->
                        <i class="fa fa-times fa-3x text-danger"></i>
                        <h5 class="text-danger">
                            <?php if($conversation->sender_id==$orderDetails->buyer_id){ ?> 
                            Seller has not yet accepted cancellation request from buyer.
                            <?php }elseif($conversation->sender_id==$orderDetails->seller_id){ ?> 
                            Buyer has not yet accepted cancellation request from seller.
                            <?php } ?>
                        </h5>
                    </div>
				<?php }
				elseif($status == "decline_cancellation_request"){?>
				<h2 class="mt-3 mb-4" align="center"> Order Cancellation Request By <?php echo $sender_user_name; ?> </h2>
				 <div class="message-div">
                        <img src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image">
                        <h5><?php echo $sender_user_name; ?></h5>
                        <p class="message-desc">
                         <?php echo $conversation->message; ?>
                           <?php if(!empty($conversation->file)){ ?>
                            <a href="<?php echo UPLOAD_HTTP_PATH.'conversation-files/'.$conversation->file;?>" download class="d-block mt-2 ml-1">
                                <i class="fa fa-download"></i> <?php echo $conversation->file; ?>
                            </a>
                            <?php }?>
                        </p>
                        <p class="float-right text-muted">
                            <?php echo date('F d, Y H:i:s',strtotime($conversation->date)); ?> 
                        </p>
                        <br>
                    </div>
                    <div class="order-status-message">
                        <!--- order-status-message Starts --->
                        <i class="fa fa-times fa-3x text-danger"></i>
                        <h5 class="text-danger">
                            Cancellation Request Declined By <?php echo $receiver_name; ?>
                        </h5>
                    </div>
				<?php }
				elseif($status == "accept_cancellation_request"){?>
				 <h2 class="mt-3 mb-4" align="center"> Order Cancellation Request By <?php echo $sender_user_name; ?> </h2>
				 <div class="message-div">
                        <img src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image">
                        <h5><?php echo $sender_user_name; ?></h5>
                        <p class="message-desc">
                         <?php echo $conversation->message; ?>
                           <?php if(!empty($conversation->file)){ ?>
                            <a href="<?php echo UPLOAD_HTTP_PATH.'conversation-files/'.$conversation->file;?>" download class="d-block mt-2 ml-1">
                                <i class="fa fa-download"></i> <?php echo $conversation->file; ?>
                            </a>
                            <?php }?>
                        </p>
                        <p class="float-right text-muted">
                            <?php echo date('F d, Y H:i:s',strtotime($conversation->date)); ?> 
                        </p>
                        <br>
                    </div>
                    <div class="order-status-message" align="center">
                        <i class="fa fa-times fa-3x text-danger" style="position:relative;"></i>
                        <h5 class="text-danger">Order Cancelled By Mutual Agreement. </h5>
                        <p> Order Was Cancelled By Mutual Agreement Between Seller and Buyer. </p>
                    </div>
                    <!--- order-status-message Ends --->
				<?php }
				elseif($status == "cancelled_by_customer_support"){?>
				<div class="order-status-message">
                    <!--- order-status-message Starts --->
                    <i class="fa fa-times fa-3x text-danger"></i>
                    <h5 class="text-danger"> Order Cancelled By Customer Support. </h5>
                    <p> Payment for this order was refunded to buyer Wallet Balance. </p>
                </div>
				<?php }?>
				
				<?php	
				}
			}else{
			?>
			<h3 class='text-center'>This Order Has No Conversations.</h3>
			<?php
			}?>

            </div>
            <!--- card-body Ends --->

        </div>
        <!--- card Ends --->

    </div>
    <!--- col-lg-12 Ends --->

</div>
	</div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
<style>

.v-middle tr td{
	vertical-align: middle !important;
}

.v-middle tr td span.hightlight{
	    font-weight: bold;
  
}
</style>  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>

<script>

function updateWalletBalance(wallet_id){
	if(wallet_id){
		$.ajax({
			url : '<?php echo base_url('wallet/update_wallet');?>',
			data: {wallet_id: wallet_id, cmd: 'update_origional'},
			type: 'POST',
			dataType: 'JSON',
			success: function(res){
				if(res.status == 1){
					location.reload();
				}
			}
		});
	}
}

function view_txn_detail(txn_id){
	Modal.openURL({
		title : 'Transaction Detail',
		url: '<?php echo base_url($curr_controller.'load_ajax_page?page=single_txn_detail');?>&id='+txn_id+'&wallet_id=<?php echo $wallet_id;?>'
	});
}

function add(){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$add_command);?>';
	load_ajax_modal(url);
}

function edit(id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$edit_command);?>&id='+id;
	load_ajax_modal(url);
}

function deleteRecord(id, permanent){
	permanent = permanent || false;
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		console.log('ok');
		var url = '<?php echo base_url($curr_controller.'delete_record');?>/'+id;
		if(permanent){
			url += '?cmd=remove';
		}
		$.getJSON(url, function(res){
			if(res.cmd && res.cmd == 'reload'){
				location.reload();
			}
		});
	}else{
		return false;
	}
}

function changeStatus(sts, id, ele){
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
		var url = '<?php echo base_url($curr_controller.'change_status');?>';
		$.ajax({
			url : url,
			data: {ID: id, status: sts},
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}else if(res.cmd == 'replace'){
						if(typeof ele !== 'undefined'){
							$('[data-toggle="tooltip"]').tooltip("dispose");
							$(ele).replaceWith(res.data.html);
							init_plugin();
						}
					}
				}
				
			}
		});
	}
	return false;
}

function changeStatusAll(sts){
	var data = $('#main_table').find('input').serialize();
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
		data += '&status=' + sts;
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'change_status');?>';
		$.ajax({
			url : url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}else if(res.cmd == 'replace'){
						if(typeof ele !== 'undefined'){
							$('[data-toggle="tooltip"]').tooltip("dispose");
							$(ele).replaceWith(res.data.html);
							init_plugin();
						}
					}
				}
				
			}
		});
	}
	return false;
}

function deleteSelected(){
	var c = confirm('Are you sure to delete selected record ?');
	if(c){
		var data = $('#main_table').find('input').serialize();
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'delete_record');?>';
		$.ajax({
			url : url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}
				}
				
			}
		});
	}
	
	return false;
}

function init_event(){
	
	var item  = $('.check_all_main').data('target');
	
	$(item).on('change', function(){
		checkSelected();
	});
	
	$('.check_all_main').on('change', function(){
		var is_checked = $(this).is(':checked');
		var target = $(this).data('target');
		if(is_checked){
			$(target).prop('checked', true);
		}else{
			$(target).prop('checked', false);
		}
		$(target).triggerHandler('change');
	});
	
	function checkSelected(){
		var target  = $('.check_all_main').data('target');
		var l = $(target + ':checked').length;
		if(l == 0){
			$('#global_action_btn').find('button').attr('disabled', 'disabled');
			$('#global_action_btn').hide();
		}else{
			$('#global_action_btn').find('button').removeAttr('disabled');
			$('#global_action_btn').show();
		}
	} 
}

$(function(){
	
	init_plugin(); /* global.js */
	init_event();
	
	
});
</script>
