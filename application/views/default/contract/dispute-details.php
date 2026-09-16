<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
//get_print($contractDetails,FALSE);

$logo_contractor=getMemberLogo($contractDetails->contractor->member_id);
$name_contractor=$contractDetails->contractor->member_name;
$logo_owner=getCompanyLogo($contractDetails->owner->organization_id);
if($contractDetails->owner->organization_name){
	$name_owner=$contractDetails->owner->organization_name;
}else{
	$name_owner=$contractDetails->owner->member_name;
}	
if($is_owner){
	$logo=$logo_contractor;
	$name=$name_contractor;
}else{
	$logo=$logo_owner;
	$name=$name_owner;
}
$new_contract_url=get_link('HireProjectURL')."/".md5($contractDetails->project_id)."/".md5($contractDetails->contractor_id);
$contract_details_url=get_link('ContractDetails').'/'.md5($contractDetails->contract_id);
$contract_message_url=get_link('ContractMessage').'/'.md5($contractDetails->contract_id);
$contract_term_url=get_link('ContractTerm').'/'.md5($contractDetails->contract_id);
?>
<script src="<?php echo JS;?>vue.js"></script>
<script src="<?php echo JS;?>vue-infinite-loading.js"></script>
<script type="text/javascript" src="<?php echo JS;?>moment-with-locales.js"></script>


<section class="section">
<div class="container">
		<a href="<?php echo $contract_details_url;?>" class="mb-1 btn btn-link p-0"><i class="icon-feather-chevron-left"></i><?php echo __('contract_dispute_b_contract','Back to Contract');?> </a>
        <h1 class="mb-3"><?php echo $contractDetails->contract_title;?></h1>
      
        <div class="row">
          <div class="col-lg-8">
		  <?php 
		  if($contractDetails->is_send_to_admin==1){
			  if($contractDetails->dispute_status==1){
		  ?>
			<div class="text-center mb-2"><span class="status badge badge-success btn-block"><?php echo __('contract_dispute_close_admin','Dispute closed by admin');?></span></div>
			<ul class="totalList mb-3">
                	<li><b><?php echo __('contract_dispute_total','Total');?></b> <span ><?php echo $currency;?><?php echo $contractDetails->milestone_amount;?></span></li>
                	<li><b><?php echo __('contract_dispute_commision','Commission');?></b> <span ><?php echo $currency;?><?php echo $contractDetails->commission_amount;?></span></li>
                	<li><b><?php echo __('contract_dispute_to_client','To Client');?></b> <span ><?php echo $currency;?><?php echo $contractDetails->owner_amount;?></span></li>
                	<li><b><?php echo __('contract_dispute_to_freelancer','To Freelancer');?></b> <span ><?php echo $currency;?><?php echo $contractDetails->contractor_amount;?></span></li>
            </ul>
		<?php }else{ ?>
			<div class="text-center mb-2"><span class="status badge badge-warning btn-block"><?php echo __('contract_dispute_sent_admin','Request sent to admin');?></span></div>
			
			<?php 
			}
		  }
			?> 

          <?php if($contractDetails->submission){
          	$submission=$contractDetails->submission[0];
          	?>
         	<div class="mb-3 panel">
         	<div class="panel-body">
            <?php if($submission->is_approved==1){?>
                  <span class="badge badge-success float-end"><?php echo __('contract_list_approved','Approved');?></span>
                  <?php } elseif($submission->is_approved==2){?>
                  <span class="badge badge-danger float-end"><?php echo __('contract_list_rejected','Rejected');?></span>
                  <?php 
                  }elseif($submission->submitted_by==$current_user_id){
                  ?>
                  <span class="badge badge-warning float-end"><?php echo __('contract_dispute_w_action',
				'Waiting for action');?></span>
                  <?php	
                  }else{?>
                  <span class="badge badge-warning float-end"><?php echo __('contract_details_pending','Pending');?></span>
                 <?php	 }?>
         	<p class="mb-2"><span class="date"><i class="icon-feather-calendar"></i> <?php echo $submission->submission_date;?></span></p>
            <p><?php echo nl2br($submission->submission_description);?></p>
            <?php if($submission->submission_attachment){?>
                      <div class="attachments-container mb-2">
                        <?php
					$attachments=json_decode($submission->submission_attachment);
					foreach($attachments as $k=>$val){
						if($val->file && file_exists(UPLOAD_PATH.'projects-files/dispute-submission/'.$val->file)){
							$path_parts = pathinfo($val->name);
					?>
                        <a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/dispute-submission/'.$val->file;?>" target="_blank" class="attachment-box "><span><?php echo $path_parts['filename'];?></span><i><?php echo strtoupper($path_parts['extension']);?></i></a>
                        <?php
						}	
					}
					?>
                      </div>
            <?php }?>
            
                <ul class="totalList mb-3">
                	<li><b><?php echo __('contract_dispute_total','Total');?></b> <span ><?php echo $currency;?><?php echo $contractDetails->milestone_amount;?></span></li>
                	<li><b><?php echo __('contract_dispute_commision','Commission');?></b> <span ><?php echo $currency;?><?php echo $submission->commission_amount;?></span></li>
                	<li><b><?php echo __('contract_dispute_to_client','To Client');?></b> <span ><?php echo $currency;?><?php echo $submission->owner_amount;?></span></li>
                	<li><b><?php echo __('contract_dispute_to_freelancer','To Freelancer');?></b> <span ><?php echo $currency;?><?php echo $submission->contractor_amount;?></span></li>
                </ul>
               
                
                  <?php if($submission->is_approved==0 && $submission->submitted_by!=$current_user_id){?>
				  <?php if($contractDetails->is_send_to_admin==0){?>
                  <div class="text-center"><button class="btn btn-success btn-sm acceptbtn" data-sid="<?php echo $submission->submission_id;?>"><?php echo __('contract_dispute_accept','Accept');?></button>&nbsp;
                  <button class="btn btn-primary btn-sm submit_offer" data-sid="<?php echo $submission->submission_id;?>"><?php echo __('contract_dispute_new_offer','New Offer Request');?></button></div>
                  <?php 
				  }
  					}
					?>
                                             
           </div>
			</div>
          <?php 
          }?>
          
  
        <div class="messages-container message-contract">
            <div class="messages-container-inner" id="message-app">            
                <!-- Message Content -->
                <div class="message-content">
                    <div v-if="active_chat">
                        <active-chat-header :active_chat="active_chat"></active-chat-header>
                        <active-chat-body :active_chat="active_chat" :login_user="login_user" v-on:update-message="updateMessage" :new_message_received="lastMessageReceived" v-on:new-attachment="updateAttachment"></active-chat-body>
                    </div>
                    <div v-else>
                        <h3><?php echo __('contract_message_chat','Select chat');?></h3>
                    </div>
                </div>
                <!-- Message Content -->
                <div class="attachmentFile">
                    <div class="messages-headline"><h4><?php echo __('contract_message_attachment','Attachments');?></h4><p class="mb-0"><?php echo __('contract_message_all_files','All Files');?></p></div>
                    <div v-if="active_chat" class="attachScrollbar" data-simplebar>
                        <conversation-attachment :active_chat="active_chat" :refresh_attachment="refresh_attachment"></conversation-attachment>
                    </div>
                    <div v-else>
                        <h3><?php echo __('contract_message_chat','Select chat');?></h3>
                    </div>
                </div>
            </div>
    </div>
				
          </div>
          <div class="col-lg-4">
            <div class="card text-center mx-auto mb-4">
                <div class="card-body">
                <div class="profile_pic mb-3">
                	<img src="<?php echo $logo;?>" alt="<?php echo $name;?>" class="rounded-circle" height="96" width="96"> 
                </div>                   
                <h4 class="card-title"><?php echo $name;?></h4>
                <?php if($is_owner){?>
            	<p class="text-muted mb-2"><?php D($contractDetails->contractor->member_heading);?></p>
            	<div class="star-rating d-block mb-2" data-rating="<?php echo round($contractDetails->contractor->avg_rating,1);?>"></div> 
            	<?php }else{ ?>
             	<div class="star-rating d-block mb-2" data-rating="<?php echo round($contractDetails->owner->statistics->avg_rating,1);?>"></div>
            	<?php }?>
                <?php if($contractDetails->dispute_status==0){	
				if($contractDetails->is_send_to_admin==0){
				?>
				<a href="javascript:void(0)" class="btn btn-primary submit_offer"><icon class="icon-material-outline-add"></icon><?php echo __('contract_dispute_submit_offer','Submit Offer');?> </a>
				<a href="javascript:void(0)" class="btn btn-dark  submit_send_to_admin"><icon class="icon-material-outline-account"></icon><?php echo __('contract_dispute_s_admin','Send to admin');?> </a>
                
				<?php
				}else{
					echo '<span class="status badge badge-warning">Send to admin</span>';
				}
				?>
				<?php }?>
                </div> 
            </div>
            <div class="card">
                 <div class="card-header"><h4><?php echo __('contract_dispute_project','Project');?></h4></div>     
               
                <?php if($contractDetails->submission){
          	foreach($contractDetails->submission as $k=>$submission){
          	?>
            <div class="card-body border-bottom">
            <?php if($submission->is_approved==1){?>
                  <span class="status badge badge-success float-end"><i class="fa fa-reply"></i> <?php echo __('contract_list_approved','Approved');?></span>
                  <?php } elseif($submission->is_approved==2){?>
                  <span class="status badge badge-danger float-end"><i class="fa fa-reply"></i><?php echo __('contract_list_rejected','Rejected');?> </span>
                  <?php 
                  }elseif($submission->submitted_by==$current_user_id){
                  ?>
                  <span class="status badge badge-warning float-end"><i class="fa fa-reply"></i> <?php echo __('contract_dispute_w_action','Waiting for action');?></span>
                  <?php	
                  }else{?>
                  <span class="status badge badge-warning float-end"><i class="fa fa-reply"></i> <?php echo __('contract_details_pending','Pending');?></span>
                  <?php	

					}
				  ?>
          	 <?php 
          	 if($submission->submitted_by==$contractDetails->contractor->member_id){?>
          	 <p><b><?php echo __('contract_dispute_from','From:');?></b> <?php echo $name_contractor;?></p>
          	 <?php }else{?>
          	 <p><b><?php echo __('contract_dispute_from','From:');?></b> <?php echo $name_owner;?></p>
          	 <?php }?>
          	 <span class="date"><i class="icon-feather-calendar"></i> <?php echo $submission->submission_date;?></span>
          	 <ul class="mb-0">
            	<li><b><?php echo __('contract_dispute_to_client','To Client:');?></b> <span ><?php echo $currency;?><?php echo $submission->owner_amount;?></span></li>
            	<li><b><?php echo __('contract_dispute_to_freelancer','To Freelancer:');?></b> <span ><?php echo $currency;?><?php echo $submission->contractor_amount;?></span></li>
                </ul>
                  
                </div>
          	
          	
          	
          	<?php }
          	}else{?>
          	<div class="card-body"><p><?php echo __('contract_dispute_pffer','No pffer yet');?></p></div>
          	<?php }?>
          	              
            </div>
           </div>                          
        </div>
      </div>
</section>      
<div id="submit_offer_modal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('contract_end_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('contract_dispute_submit_offer','Submit Offer');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveOffer(this)"><?php echo __('contract_dispute_send','Send');?></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="offerform" class="form-horizontal" role="form" name="offerform" onsubmit="return false;">
            <div class="row mb-4 text-center border-bottom pb-2 bg-light">
            	<div class="col-lg-4">
                <label><b><?php echo __('contract_dispute_total_ammount','Total Disputed Amount:');?></b> </label>
                <h3><?php echo $currency;?><?php echo $contractDetails->milestone_amount;?></h3>
                </div>
            	<div class="col-lg-4">
                <label><b><?php echo __('contract_dispute_comm_ammount','Commission Amount:');?></b> </label>
                <h3><?php echo $currency;?><?php echo $site_fee_amount;?></h3>
                </div>
                <div class="col-lg-4">
                <label><b><?php echo __('contract_dispute_remain_ammount','Remain Amount:');?></b> </label>
                <h3><?php echo $currency;?><?php echo displayamount($remain_amount,2);?></h3>
                </div>
            </div>
            
              <div class="row">
              	<div class="col-lg-6">
              		<div class="form-group">
		                <label><b><?php echo __('contract_dispute_to_client','To Client');?></b></label>
		                <div class="input-with-icon-start">
		                  <i><?php echo $currency;?></i>
		                  <input type="text" name="to_client" id="to_client" class="form-control" value="0" onkeypress="return isNumberKey(this)" onkeyup="updateFullPayment(this)">
		                </div>
		            </div>	
              	</div>
              	<div class="col-lg-6">
              		<div class="form-group">
		                <label><b><?php echo __('contract_dispute_to_freelancer','To Freelance');?></b></label>
		                <div class="input-with-icon-start">
		                  <i><?php echo $currency;?></i>
		                	<input type="text" class="form-control" name="to_freelancer" id="to_freelancer" value="0" onkeyup="updateFullPayment(this)"/>
		                </div>
		            </div>	
              	</div>
              	
              	
              </div>
              <div class="form-group">
                <label><b><?php echo __('contract_dispute_description','Description');?></b></label>
                <textarea class="form-control" id="details" name="details"></textarea>
              </div>
              <div class="form-group">
                <label><b><?php echo __('contract_message_attachment','Attachments');?></b></label>
                <input type="file" name="fileinput" id="fileinput" multiple="true">
                <div class="upload-area" id="uploadfile">
                  <p class="mb-0"><?php echo __('contract_dispute_drag_drop','Drag and Drop file here');?><br/>
				  <?php echo __('contract_dispute_or','Or');?><br/>
                  <span class="text-site"><?php echo __('click','click');?></span> <?php echo __('to_select_file','to select file');?></p>
                </div>
                <div id="uploadfile_container"> </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->layout->view('message/message-template', '', TRUE); ?>


<?php if($is_owner){?>

<?php }?>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var c_id="<?php echo md5($contractDetails->contract_id)?>";
var m_id="<?php echo md5($contractDetails->contract_milestone_id)?>";
var dis_id="<?php echo md5($contractDetails->project_contract_dispute_id)?>";

</script>
<?php if($is_owner){?>

<?php }?>

<script>
function updateFullPayment(ev){
	var maxamount=parseFloat("<?php echo displayamount($remain_amount,2);?>");
	var sec=$(ev).attr('id');
	var amount=parseFloat($(ev).val());
	if(isNaN(amount)){
		var amountOrg=0;
	}else{
		var amountOrg=amount;
		if(amountOrg>maxamount){
			$(ev).val(maxamount);
			amountOrg=maxamount;
		}
	}
	var remain=maxamount-amountOrg;
	if(sec=='to_client'){
		$('#to_freelancer').val(remain.toFixed(2));
	}else{
		$('#to_client').val(remain.toFixed(2));
	}
}
function uploadData(formdata){
	var len = $("#uploadfile_container div.thumbnail_sec").length;
   	var num = Number(len);
	num = num + 1;	
	$("#uploadfile_container").append('<div id="thumbnail_'+num+'" class="thumbnail_sec">'+SPINNER+'</div>');
    $.ajax({
        url: "<?php D(get_link('uploadFileFormCheckAJAXURL'))?>",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
           if(response.status=='OK'){
    			var name = response.upload_response.original_name;
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class=" text-danger ico float-end" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
		   }else{
		   	bootbox.alert({
				title: 'Uplaod File',
				message: 'Error in upload file',
				size: 'small',
				buttons: {
					ok: {
						label: "Ok",
						className: 'btn-primary float-end'
					},
				},
				callback: function(result){
					$("#thumbnail_"+num).remove();
				}
			});	
		   }
		   $('#fileinput').val('');
        },
        
    }).fail(function(){
    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
    });
}
function SaveOffer(ev){
	var buttonsection=$(ev);
	buttonsection.attr('disabled','disabled');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	var formID="offerform";
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('submitDisputeOfferFormCheckAJAXURL'))?>",
        data:$('#'+formID).serialize()+'&mid='+m_id+'&dis_id='+dis_id,
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				location.reload();
			} else if (msg['status'] == 'FAIL') {
				registerFormPostResponse(formID,msg['errors']);
			}
		}
	})		
}
var main = function(){
	$('.submit_offer').click(function(){
		$('#submit_offer_modal').modal('show');
	});
	$('.submit_send_to_admin').click(function(){
		bootbox.confirm({
			title:'Dispute Send To Admin',
			message: 'After send to admin , admin will take decission',
			buttons: {
			'confirm': {
				label: 'Confirm',
				className: 'btn-primary float-end'
				},
			'cancel': {
				label: 'Cancel',
				className: 'btn-dark float-start'
				}
			},
			callback: function (result) {
				if(result){
					$.post("<?php echo get_link('disputeActionAjaxURL');?>",{'mid':m_id,'dis_id':dis_id,'action_type':'send_to_admin','sid':1}, function( msg ) {
						if (msg['status'] == 'OK') {
							bootbox.alert({
								title:'Dispute Send To Admin',
								message: '<?php D(__('dispute_send_to_admin_success_message','Request sent to admin succesfully'));?>',
								buttons: {
								'ok': {
									label: 'Ok',
									className: 'btn-primary float-end'
									}
								},
								callback: function () {
									location.reload();
							    }
							});
						}
					},'JSON'); 	
				}
			}
		});

	});

	$('.acceptbtn').click(function(){
		var buttonsection=$(this);
		var sid=$(this).data('sid');
		bootbox.confirm({
			title:'Dispute Offer Confimation',
			message: 'By accept, the dispute will be resolve and payment will be release.',
			buttons: {
			'confirm': {
				label: 'Confirm',
				className: 'btn-primary float-end'
				},
			'cancel': {
				label: 'Cancel',
				className: 'btn-dark float-start'
				}
			},
			callback: function (result) {
				if(result){
					buttonsection.attr('disabled','disabled');
					var buttonval = buttonsection.html();
					buttonsection.html(SPINNER).attr('disabled','disabled');
					$.post( "<?php echo get_link('disputeActionAjaxURL');?>",{'mid':m_id,'dis_id':dis_id,'action_type':'accept','sid':sid}, function( msg ) {
						if (msg['status'] == 'OK') {
							bootbox.alert({
								title:'Dispute Offer Accepted',
								message: '<?php D(__('work_success_message','Work accepted succesfully'));?>',
								buttons: {
								'ok': {
									label: 'Ok',
									className: 'btn-primary float-end'
									}
								},
								callback: function () {
									location.reload();
							    }
							});
						} else if (msg['status'] == 'FAIL') {
							if(msg['popup']){
								if(msg['popup']=='fund'){
									bootbox.alert({
										title: 'Insufficient funds',
										message: 'You do not have sufficient balance to approve. Please add fund <?php echo $currency;?>'+msg['amount_due']+' amount to your wallet.',
										size: 'small',
										buttons: {
											ok: {
												label: "Ok",
												className: 'btn-primary float-end'
											},
										},
										callback: function(result){
											window.open('<?php echo get_link('AddFundURL');?>?pre_amount='+msg['amount_due'],'_blank');
										}
									});
								}
							}else{
								bootbox.alert({
									title:'Dispute Offer Accepted',
									message: '<?php D(__('work_error_message',"Opps! . Please try again."));?>',
									buttons: {
									'ok': {
										label: 'Ok',
										className: 'btn-primary float-end'
										}
									},
									callback: function () {
										location.reload();
								    }
								});
							
							}
						}
						
						buttonsection.html(buttonval).removeAttr('disabled');
					},'JSON');
					
				}
			}
		});

	});
	
	/**
	active_chat : {
	avatar:  {String} user logo,
	name: {String} name,
	message: {String} message,
	time: {Number} time in milliseconds,
	online_status: {Boolean} true|false,
},
	*/
	
	var login_user = <?php echo !empty($login_member) ? json_encode($login_member) : 'null'; ?>;
	var App = new Vue({
		el: '#message-app',
		data: {
			active_chat: <?php echo $active_chat ? json_encode($active_chat) : 'null'; ?>,
			login_user: login_user,
			lastMessage: new Date().getTime(),
			lastMessageReceived: new Date().getTime(),
			refresh_attachment: null,
		},
		methods: {
			setActiveChat: function(d){
				this.active_chat = d;
			},
			updateMessage: function(){
				this.lastMessage = new Date().getTime();
			},
			updateLastSeenMsg: function(last_seen_msg){
				this.active_chat.last_seen_msg = last_seen_msg;
			},
			updateAttachment: function(attachment){
				this.refresh_attachment = attachment;
			}
		}
	});
	
	AppService.on('new_message', function(data){
		if(data > 0){
			App.lastMessageReceived = new Date().getTime();
		}
	});
	
	AppService.on('msg_seen_update', function(data){
		if(data.last_message_id != 'undefined'){
			if(App.active_chat && data.conversations_id == App.active_chat.conversations_id){
				App.updateLastSeenMsg(data.last_message_id);
				$.post('<?php echo base_url('message/reset_msg_seen')?>');
				App.updateMessage();
			}
		}
	});
	
	
	
};

</script>
