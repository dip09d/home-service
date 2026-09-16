<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
//get_print($contractDetails,FALSE);
$profile_url='';
if($is_owner){
	$logo=getMemberLogo($contractDetails->contractor->member_id);
	$name=$contractDetails->contractor->member_name;
	$profile_url="href='".get_link('viewprofileURL').'/'.md5($contractDetails->contractor->member_id)."' target='_blank'";
}else{
	$logo=getCompanyLogo($contractDetails->owner->organization_id);
	if($contractDetails->owner->organization_name){
		$name=$contractDetails->owner->organization_name;
	}else{
		$name=$contractDetails->owner->member_name;
	}
}
$new_contract_url=get_link('HireProjectURL')."/".md5($contractDetails->project_id)."/".md5($contractDetails->contractor_id);
$contract_details_url=get_link('ContractDetailsHourly').'/'.md5($contractDetails->contract_id);
$contract_worklog_url=get_link('ContractWorkLogHourly').'/'.md5($contractDetails->contract_id);
$contract_invoice_url=get_link('ContractInvoiceHourly').'/'.md5($contractDetails->contract_id);
$contract_message_url=get_link('ContractMessageHourly').'/'.md5($contractDetails->contract_id);
$contract_term_url=get_link('ContractTermHourly').'/'.md5($contractDetails->contract_id);
?>
<script src="<?php echo JS;?>vue.js"></script>
<script src="<?php echo JS;?>vue-infinite-loading.js"></script>
<script type="text/javascript" src="<?php echo JS;?>moment-with-locales.js"></script>

<section class="section">
<div class="container">
        <h1 class="display-4"><?php echo $contractDetails->contract_title;?></h1>
        <ul class="nav nav-tabs mb-3">
          <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_details_url;?>"><?php echo __('workroom_details_overview','Overview');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_worklog_url;?>"><?php echo __('workroom_details_work_logs','Work Logs');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_invoice_url;?>"><?php echo __('workroom_details_invoices','Invoices');?></a> </li>
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_message_url;?>"><?php echo __('workroom_details_message_file','Messages & Files');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>"><?php echo __('workroom_details_term','Terms & Settings');?></a> </li>
        </ul>
        <div class="row">
          <div class="col-lg-9">

						<div class="messages-container message-contract">
							<div class="messages-container-inner" id="message-app">
							
								<!-- Message Content -->
								<div class="message-content">
									<div v-if="active_chat">
										<active-chat-header :active_chat="active_chat"></active-chat-header>
										<active-chat-body :active_chat="active_chat" :login_user="login_user" v-on:update-message="updateMessage" :new_message_received="lastMessageReceived" v-on:new-attachment="updateAttachment"></active-chat-body>
									</div>
									<div v-else>
										<h3><?php echo __('workroom_message_select_chat','Select chat');?></h3>
									</div>
								</div>
								<!-- Message Content -->
								<div class="attachmentFile">
									<div class="messages-headline"><h4><?php echo __('workroom_message_attachments','Attachments');?></h4><p class="mb-0"><?php echo __('workroom_message_all_files','All Files');?></p></div>
									<div v-if="active_chat" class="attachScrollbar" data-simplebar>
										<conversation-attachment :active_chat="active_chat" :refresh_attachment="refresh_attachment"></conversation-attachment>
									</div>
									<div v-else>
										<h3><?php echo __('workroom_message_select_chat','Select chat');?></h3>
									</div>
								</div>
							</div>
					</div>
				
          </div>
          <div class="col-lg-3">
            <div class="card text-center mx-auto">
                <div class="card-body">
				<a <?php echo $profile_url;?>>
                <img src="<?php echo $logo;?>" alt="<?php echo $name;?>" class="rounded-circle mb-3" height="96" width="96">                    
                <h4 class="card-title"><?php echo $name;?></h4>
				</a>
				<?php if($is_owner){?>
				<p class="text-muted mb-2"><?php D($contractDetails->contractor->member_heading);?></p>
				<div class="star-rating mb-2" data-rating="<?php echo round($contractDetails->contractor->avg_rating,1);?>"></div> 
				<?php }else{ ?>
				<div class="star-rating mb-2" data-rating="<?php echo round($contractDetails->owner->statistics->avg_rating,1);?>"></div>
				<?php }?>
                </div>                    
            </div>
           </div>                          
        </div>
      </div>
</section>      

<?php $this->layout->view('message/message-template', '', TRUE); ?>

<?php if($is_owner){?>

<?php }?>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var c_id="<?php echo md5($contractDetails->contract_id)?>";

</script>
<?php if($is_owner){?>

<?php }?>

<script>
var main = function(){
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
