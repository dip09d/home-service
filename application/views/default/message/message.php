<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo JS;?>vue.js"></script>
<script src="<?php echo JS;?>vue-infinite-loading.js"></script>
<script type="text/javascript" src="<?php echo JS;?>moment-with-locales.js"></script>

<!-- Dashboard Container -->

<div class="dashboard-container"> <?php echo $left_panel;?> 
  <!-- Dashboard Content -->
  <div class="dashboard-content-container">
    <div class="dashboard-content-inner"> 
      
      <!-- Dashboard Headline
			<div class="dashboard-headline">
				<h3>Messages</h3>				
			</div> -->
      
      <div class="messages-container margin-top-0">
        <div class="messages-container-inner" id="message-app"> 
          
          <!-- Messages -->
          <chat-list v-on:set-chat="setActiveChat" :active_chat="active_chat" :last_time="lastMessage" :new_message_received="lastMessageReceived" :login_user="login_user"></chat-list>
          <!-- Messages / End --> 
          
          <!-- Message Content -->
          <div class="message-content justify-content-center">
            <div v-if="active_chat">
              <active-chat-header :active_chat="active_chat"></active-chat-header>
              <active-chat-body :active_chat="active_chat" :login_user="login_user" v-on:update-message="updateMessage" :new_message_received="lastMessageReceived" v-on:last-seen-msg="updateLastSeenMsg"></active-chat-body>
            </div>
            
            <div class="text-center" v-else> <img src="<?php echo IMAGE;?>communication.png" alt="">
              <p class="text-muted"><i class="icon-feather-message-circle"></i> <i><?php echo __('message_select_a_chart','Select a chat to view conversation');?></i></p>
            </div>            
          </div>
          <!-- Message Content --> 
          
        </div>
      </div>
      <!-- Messages Container / End --> 
      
    </div>
  </div>
</div>
<?php $this->layout->view('message-template', '', TRUE); ?>

<!-- Dashboard Container / End --> 

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

$(".message-content-inner").scroll(function() {
  $(".message-content-inner").getNiceScroll().resize();
});
document.addEventListener("touchstart", ScrollStart, false);
};
function ScrollStart(){
	$(".message-content-inner").getNiceScroll().resize();
}
function auto_grow(element) {
   
	if(element.scrollHeight>=103){
		return true;
	}else{
		element.style.height = "5px";
		element.style.height = (element.scrollHeight)+"px";
	}
   
}
</script>
<style>
@media (max-width:767px) {
	body{
		height:100%;
		overflow:hidden;
		padding-bottom: 70px;
	}
	.message-content {
		background-color: #fff;
		max-height: calc(100% - 135px) !important;
		position: fixed;
		top: 64px;
		width: 100%;
		height: 100%;
	}
	.message-content > div {
		height: 100%;
	}
	.messages-container-inner .message-content-inner{
		overflow-x:auto;
		padding-bottom: 100px;
		height: 100%;
	}
	.message-reply {
		background-color: #fff;
		position: fixed;
		width: 100%;
		bottom: 0;
	}
	.chat-foot, #footer{
		display:none;
	}
}
.message-by p .badge{
	font-weight: 700;
    height: 19px;
    width: 19px;
    /* line-height: 19px; */
    text-align: center;
    background-color: #ffc107;
	color: #212529;
    font-size: 11px;
    border-radius: 50%;
    position: relative;
    margin: 0 0 0 4px;
    /* top: -2px; */
    position: absolute;
    right: 10px;
}
</style>
<div class="modal fade" id="modalImgageView" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body text-center">
			<button type="button" class="btn btn-dark " data-dismiss="modal" aria-label="Close" style="position:absolute;top:5px;right:5px;color: #fff;">X</button>
            <img src="" alt="Image Title" class="img-fluid" id="modalImgageViewIMG">
        </div>
    </div>
</div></div>