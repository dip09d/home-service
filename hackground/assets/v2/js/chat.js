
;(function($, window ) {

  'use strict';
  
  
function Chat(options){
	this.options = options;
	this.chat_container = options.chat_container;
	this.chatBox = options.chatBox;
	this.chatListURL = options.chatListURL;
	this.chat_user_list = [];
	this.login_user_id = options.login_user_id;
	this._init();
};

Chat.prototype._init = function(){
	this.load();
	
};

Chat.prototype.chat_user = function(){
	var chat_user = [];
	$.ajax({
		url: this.chatListURL,
		dataType: 'json',
		async: false,
		success: function(res){
			this.chat_user_list = res['result'];
			chat_user = this.chat_user_list;
			
			
		}
	});
	
	var current_user = this.login_user_id;
	return {
		getUser: function(ukey){
		
			for(var i in chat_user){
				if(chat_user[i].user_key == ukey){
					return chat_user[i];
				}
			}
		},
		
		get: function(){
				for(var i in chat_user){
					if(chat_user[i].last_message_detail.sender_id == current_user){

						chat_user[i]['sender_icon'] = '<i class="material-icons" style="font-size: 17px;float: left;margin-top: 2px;margin-right: 3px;">forward</i>';
					}else{
						chat_user[i]['sender_icon'] = '<i class="material-icons" style="font-size: 17px;float: left;margin-top: 2px;margin-right: 3px;">reply</i>';
					}
					chat_user[i]['last_message'] = (chat_user[i].last_message_detail.message.length > 18) ? chat_user[i].last_message_detail.message.substring(0, 18)+'..' : chat_user[i].last_message_detail.message.substring(0, 18);
					chat_user[i]['user_logo'] = chat_user[i].image_thumb;
					chat_user[i]['time'] = get_time(chat_user[i].last_message_detail.date);
					chat_user[i]['timestamp'] = chat_user[i].last_message_detail.date;
					chat_user[i]['unread_badge'] = chat_user[i].unread_message > 0 ? '<span class="right new badge red">'+chat_user[i].unread_message+'</span>' : '';
				}	
				
			return chat_user;
			
		},
	}
	
	
	
	
	
};

Chat.prototype.load = function(){
	var chat_users = this.chat_user();
	this.render(chat_users.get());
};

Chat.prototype.render = function(users){
	var c = this.chat_container;
	var html = '';
	this.open_chat_class = 'openchat';
	var tmp = '<li class="collection-item avatar" id="chat_user_{user_key}"><a href="javascript:void(0);" class="openchat" data-chat-user-key="{user_key}"><img src="{user_logo}" alt="" class="circle"><span class="title"><b>{full_name}</b></span>{unread_badge}<p class="msg-inline lst_msg">{sender_icon} {last_message} <span class="right time" data-time="{timestamp}">{time}</span></p></a></li>';
	
	if(users.length > 0){
		var d = new DynamicC(c);
		d.createTemplete(tmp);
		d.prepare(users);
		d.render();
	}else{
		c.html('<li>No Messages</li>');
	}
};

Chat.prototype.openChatBox = function(chat_id){
	/*
		Steps :
		 Check whether the given chat is open or not
		 If opened then show it on the top
		 else create a new chat box and push the open chat_id in open_chat array
	*/
	var chatbx = this.chatBox;
	var bx_id = 'chat_box_'+chat_id;
	if(chatbx.find('#'+bx_id).length > 0){
		// show on the top
		$('.chat-wrapper.active').removeClass('active');
		$('#'+bx_id).addClass('active');
	}else{
		this.createChatBox(chat_id);
	}
		
	
};


Chat.prototype.createChatBox = function(chat_id){
	var c_user = this.chat_user();
	var u_detail = c_user.getUser(chat_id);
	
	if(!u_detail){
		return false;
	}
	var chatbx = this.chatBox;
	var bx_id = 'chat_box_'+chat_id;
	var wrapper = '<div class="chat-wrapper" id="'+bx_id+'"></div>',
		head = '<div class="chat-heading"><img src="'+u_detail.image_thumb+'" alt="" class="circle"><span class="title">'+u_detail['full_name']+'</span><ul class="right inline" style="margin-top:10px;"><li><a href="javascript:void(0)" title="close" class="destroy_chat" data-chatbox="'+chat_id+'"><i class="material-icons">clear</i></a></li><li><a href="javascript:void(0)" title="mininize"><i class="material-icons">remove_circle</i></a></li></ul></div>',
		body = '<div class="chat-body"><ol class="chat" id="chat_body_'+chat_id+'"></ol></div>',
		
		foot = '<div class="chat-foot"><form id="chat_form_'+chat_id+'" class="chat-form" data-chat-box-id="'+chat_id+'"><input class="chattextarea" name="message" type="text" placeholder="Write your message here!" autocomplete="off"/> <input type="hidden" name="reciever_id" value="'+u_detail.user_id+'"/> <input type="hidden" name="user_key" value="'+u_detail.user_key+'"/><div class="emojis"></div><button class="send-btn"><i class="material-icons" style="font-size:32px;">send</i></button></form></div>';
		
		chatbx.append(wrapper);
		$('#'+bx_id).html(head + body + foot);
		
		$('#'+bx_id).show('medium');
		$('.chat-wrapper.active').removeClass('active');
		$('#'+bx_id).addClass('active');
		
		var chat_msg = this.message();
		chat_msg.load(u_detail.user_id, chat_id, u_detail);
};


Chat.prototype.destroyBox = function(chat_id){
	var bx_id = 'chat_box_'+chat_id;
	$('#'+bx_id).remove();	
};


Chat.prototype.message = function(){
		
		var template = '<li class="{msg_class}">{avatar}<div class="msg"><p>{message}</p><time>{time}</time></div></li>';
		var current_user = this.login_user_id;
		return {
			
			load: function(user_id, chatbodyid, udetail){
				var chat_user_logo = udetail.image_thumb;
				var t = this;
				$.ajax({
					url: VPATH+'ajax/get_user_message',
					dataType: 'json',
					data: {user_id: user_id},
					success: function(res){
						var messages = res['result'];
						var html = '';
						if(messages.length > 0){
							for(var i in messages){
								if(messages[i].sender_id == current_user){
									messages[i].msg_class = 'self';
									messages[i].avatar = '';
								}else{
									messages[i].msg_class = 'other';
									messages[i].avatar = '<div class="avatar"><img src="'+chat_user_logo+'"draggable="false"/></div>';
								}
								messages[i].time = get_time(messages[i].date);
								
								html += replaceAll(template, messages[i]);
							}
							$('#chat_body_'+chatbodyid).html(html); 
							t.checkScroll($('#chat_form_'+chatbodyid));
						}
					},
				});
			},
			
			
			get: function(user_id){
				
			},
			
			refresh: function(){
				var template = '<li class="{msg_class}">{avatar}<div class="msg"><p>{message}</p><time>{time}</time></div></li>';
				var current_user = this.login_user_id;
				var t = this;
				$.ajax({
					url : VPATH + 'ajax/get_new_message',
					dataType: 'json',
					success: function(res){

						var html = '';
						if(res['result']){
							var n_msg = res['result'];
							for(var i in n_msg){
								n_msg[i].msg_class = 'other';
								n_msg[i].avatar = '<div class="avatar"><img src="'+n_msg[i].image_thumb+'"draggable="false"/></div>';
								n_msg[i].time = get_time(n_msg[i].date);
								html = replaceAll(template, n_msg[i]);
								$('#chat_body_'+n_msg[i].sender_key).append(html);
								
								if($('#chat_body_'+n_msg[i].sender_key).length > 0){
									t.checkScroll($('#chat_form_'+n_msg[i].sender_key));
									
									// emit all messages as seen status as seen
								}
								
							}
						}
					}
				});
			},
			
			
			send: function(form){
				var msg = form.find('.chattextarea').val(); // prevent the value from script tags
				var chat_box_id = form.data('chat-box-id'),
					fdata = form.serialize();
				var tmpl = '<li class="self"><div class="msg"><p>'+msg+'</p><time>now</time></div></li>';
				$('#chat_body_'+chat_box_id).append(tmpl);
				form[0].reset();
				$.ajax({
					url: VPATH+'ajax/send_msg',
					data: fdata,
					type: 'POST',
					dataType: 'json',
					success: function(res){
						console.log(res);
					}
				});
				var s = this;
				s.checkScroll(form);
				/* checking scroll */
				
				
				/*setTimeout(function(){
					var tmpl2 = '<li class="other"><div class="msg"><p>'+msg+'</p><time>now</time></div></li>';
					$('#chat_body_'+chat_box_id).append(tmpl2);
					s.checkScroll(form);
				}, 3000);*/
			},
			
			deleteMessage: function(msg_id){
				
			},
			
			checkScroll: function(form){
				var chat_box_id = form.data('chat-box-id');
				var body_height = $(form).closest('.chat-wrapper').find('.chat-body').height();
				if($('#chat_body_'+chat_box_id)[0].scrollHeight > body_height){
					var diff = $('#chat_body_'+chat_box_id)[0].scrollHeight - body_height;
					$(form).closest('.chat-wrapper').find('.chat-body').scrollTop(diff);
				}
			},
			
		}
	
};

  
  window.Chat = Chat;

})(jQuery,  window );


/*Attaching event to chat box */

;(function($, window ) {

  'use strict';
  
  
	var chat_settings = {
			chat_container : $('#chat-container'), 
			chatBox : $('#chat_bx_wrapper'),
			chatListURL: VPATH + 'ajax/get_chat',
			login_user_id : CURR_USER.user_id,
		};
	
	$(document).ready(function(){
		var chat = new Chat(chat_settings);
		
		var service = new EventSource(VPATH+'ajax/update_service');
		
		
		service.onmessage = function(e) {
		 if(e.data != '0'){
			var d = JSON.parse(e.data);
			if(d.new_message != undefined && d.new_message > 0){
				var msg = chat.message();
				msg.refresh();
			}
		 }
		};
		
		
		$('body').on('click', '.openchat', function(){
			var clicked_user = $(this).data('chat-user-key');
			chat.openChatBox(clicked_user);
		});
		
		$('body').on('click', '.destroy_chat', function(){
			var chat_bx = $(this).data('chatbox');
			chat.destroyBox(chat_bx);
		});
		
		$('body').on('submit', '.chat-form', function(e){
			e.preventDefault();
			var form = $(this);
				var msg = chat.message();
				msg.send(form);
			
		});
		
	});
 
 
})(jQuery,  window );

