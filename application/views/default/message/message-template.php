<script type="text/x-template" id="chat-list-template">
<div class="messages-inbox">
<div class="messages-headline">
	<div class="input-with-icon">
			<input type="text" class="form-control" id="autocomplete-input" placeholder="Search" v-model="term">
		<span class="icon-feather-search"></span>
	</div>
</div>
<div class="attachScrollbar_old">
<ul>
	<li v-for="chat_user in chatUserList" :style="{'transform': 'translateY(' + chat_user.transform + 'px)'}" :class="{'active-message': active_chat && (chat_user.conversations_id==active_chat.conversations_id)}">
		<a href="#" @click.prevent="setActiveChat(chat_user)" >
			<div class="message-avatar">
				<i class="status-icon" :class="{'status-online': chat_user.online_status, 'status-offline': !chat_user.online_status}"></i>
				<img :src="chat_user.avatar" alt="" />
			</div>

			<div class="message-by">
				<div class="message-by-headline">
					<h5>{{chat_user.name}}</h5>
					<span>{{chat_user.time}}</span>
				</div>
				<p v-if="chat_user.project_name.length > 0">{{chat_user.project_name}}</p>
				<p v-if="chat_user.unread_msg_count > 0"><b><span v-html="chat_user.message"></span></b> <span class="badge">{{chat_user.unread_msg_count}}</span></p>
				<p v-else><i class="icon-feather-check" v-if="chat_user.sender_id == login_user.member_id && chat_user.last_seen_msg >= chat_user.message_id"></i> <span v-html="chat_user.message"></span></p>
			</div>
		</a>
	</li>	
</ul>
</div>
</div>
</script>

<script type="text/javascript">
Vue.component('chat-list', {
  template: '#chat-list-template',
  props: ['last_time', 'new_message_received', 'active_chat', 'login_user'],
	data: function(){
	  return {
		chat_list: [],
		chat_org: [],
		term: '',
	  }
	},
	beforeMount: function(){
	  this.loadChat();
	},
   mounted: function(){
	   
   },
   computed: {
	   chatUserList: function(){
		
		/*  var search_term = this.term;
		  var pattern = new RegExp(search_term, "gi");
		   return this.chat_list.filter(function(item){
			   return pattern.test(item.name);
		   });*/

		 return this.applyfilter();
		
	   }
   },
   watch: {
		last_time: function(newVal, oldVal){
			this.refreshChat();
		},
		new_message_received: function(newVal, oldVal){
			this.refreshChat();
		}
   },
  methods: {
	applyfilter: function(){
		var search_term = this.term;
		var index_pos=0;
		let res_filter= [];
		if(search_term){
			var pattern = new RegExp(search_term, "gi");
			res_filter= this.chat_list.filter(function(item){
				var ret=item.name.match(pattern);
				return ret;
			});
			//console.log(res_filter.length);
			res_filter.sort( (a,b) => a.transform - b.transform );
			res_filter.forEach(function(item,index){
				res_filter[index].transform= (index_pos)*91;
				index_pos=index_pos+1; 
			})
		}else{
			res_filter= this.chat_org;
		}
		
		return res_filter;
	},
	loadChat: function(){
		var _self = this;
		$.getJSON('<?php echo base_url('message/load_chat')?>', function(res){
			if(res.status == 1){
				
				res.chat_list.forEach(function(item,index){
					item.time = moment(item.sending_date, 'YYYY-MM-DD HH:mm:ss').fromNow();
					item.transform = index*91;
					if(_self.chat_list.length >0){
						if(_self.chat_list.map((o) => Math.abs(o.conversations_id)).indexOf(Math.abs(item.conversations_id)) > -1){
							var indexrow=_self.chat_list.map((o) => Math.abs(o.conversations_id)).indexOf(Math.abs(item.conversations_id));
							_self.chat_list[indexrow].transform=item.transform;
							_self.chat_list[indexrow].time=item.time;
							_self.chat_list[indexrow].message=item.message;
							_self.chat_list[indexrow].unread_msg_count=item.unread_msg_count;
							_self.chat_list[indexrow].last_seen_msg=item.last_seen_msg;
							_self.chat_list[indexrow].message_id=item.message_id;
							
						}else{		
							_self.chat_list.unshift(item);
						} 
					}else{
						//console.log(2);
						_self.chat_list.push(item);
					}
				});
				_self.chat_org=_self.chat_list;
				setTimeout(() => {
					if(this.term){
						_self.applyfilter();
					}
				}, 1000);
				
			}
		});
	},	
	setActiveChat: function(chat_user){
		chat_user.unread_msg_count = 0;
		/* this.active_chat = chat_user; */
		this.$emit('set-chat', chat_user);
		var width=$(window).width();
		if(width<767){
			$('.messages-inbox').toggle();
			$('.message-content').toggle();
		}
	},
	refreshChat: function(){
		var _self = this;
	//	_self.chat_list = [];
		this.loadChat();
		
	}
  },
 
});
</script>


<script type="text/x-template" id="active-chat-header-template">
<div class="messages-headline">
	<div class="d-flex align-items-center">  
	<a href="javascript:void(0)" @click="showList" class="show_me d-md-none me-3" style="font-size: 1.5rem;"><i class="icon-material-outline-arrow-back"></i></a>
	<div style="flex:1;">
	<h4><a :href="active_chat.profile_url" target="_blank">{{active_chat.name}}</a></h4>
	<p class="mb-0" v-if="active_chat.project_name.length > 0"><a :href="active_chat.project_url" target="_blank">{{active_chat.project_name}}</a></p>
	</div>
	<a href="#" class="message-action" hidden><i class="icon-feather-trash-2"></i> Delete Conversation</a>
	</div>
</div>
</script>

<script type="text/javascript">
Vue.component('active-chat-header', {
  template: '#active-chat-header-template',
  props: ['active_chat'],
  data: function(){
      return {
          starred: false,
      }
  },
  methods: {
    setFilter: function(e){
        this.$emit('filter-msg', e.target.value);
    },
    setStarred: function(){
        this.starred = !this.starred;
        this.$emit('star-toggle', this.starred);
    },
	showList: function(){
		$('.messages-inbox').toggle();
		$('.message-content').toggle();
		$('.messages-inbox li').removeClass('active-message');
	}
  }
});
</script>

<script type="text/x-template" id="active-chat-message-body-template">
<div class="justify-content-start h-100">
<div class="message-content-inner" ref="message-inner">

	<infinite-loading direction="top" @infinite="infiniteHandler" ref="infiniteLoading"></infinite-loading>	
		<div v-for="message in message_list" :key="message.message_id">
			<!-- Time Sign -->
			<div class="message-time-sign" v-if="checkDate(message)">
				<span>{{message.sending_date | formatDate }}</span>
			</div>
			<div class="message-bubble" :class="{'me': message.sender_id == login_user.member_id}">
				<div class="message-bubble-inner">
					<div class="message-avatar" v-if="message.sender_id == login_user.member_id"><img :src="login_user.avatar" alt="" /></div>
					<div class="message-avatar" v-else><img :src="active_chat.avatar" alt="" /></div>
					<div class="message-text" :class="{'message-deleted': message.is_deleted}">

                        <div class="message-quote" v-if="parseInt(message.reply_to) > 0 && message.parent">
                            <div v-if="message.parent.attachment != null">
                                <div class="mb-1" v-if="message.parent.attachment.is_image">
                                    <a href="<?php D(VZ);?>" @click="OpenAttachment(message.parent.attachment)"><img :src="message.parent.attachment.file_url"  class="rounded attach-thumbnail" /></a>
                                </div>
                                <div class="mb-1" v-else>
                                    <div><a :href="message.parent.attachment.file_url"  target="_blank" style="color: black">{{message.parent.attachment.org_file_name}}</a></div>
                                    <div>Size: {{message.parent.attachment.file_size | formatFileSize }}</div>
                                </div>
                            </div>
                            <div v-else>
                                <p><i class="icon-line-awesome-quote-left"></i> <i>{{message.parent.message}}</i></p>
                            </div>
							<hr>
						</div>

						<div v-if="message.attachment != null">
							<div class="mb-1" v-if="message.attachment.is_image">
								<a href="<?php D(VZ);?>" @click="OpenAttachment(message.attachment)" ><img :src="message.attachment.file_url"  class="rounded attach-thumbnail" /></a>
							</div>
							<div class="mb-1" v-else>
								<div><a :href="message.attachment.file_url" target="_blank" style="color: black" class="attachment-box ripple-effect"><span>{{message.attachment.display_name}}</span><i>{{message.attachment.display_ext}}</i></a></div>
								<div>Size: {{message.attachment.file_size | formatFileSize }}</div>
							</div>
						</div>
						<p v-if="message.is_deleted" class="text-danger jgkjgk">
							This message is deleted <span class="time">{{message.is_deleted | formatTime }}</span>
						</p>
						<p v-if="message.is_deleted == null">
                        <span v-html="$options.filters.formatMsg(message.message, _self.term)"></span>
						<span v-if="message.is_edited && message.is_deleted == null">
                           <b> (edited)</b>
                        </span>

						<span class="time" v-if="message.is_edited && message.is_deleted == null">
							{{message.is_edited | formatTime }} 
							<i class="icon-feather-check" v-if="message.sender_id == login_user.member_id && active_chat.last_seen_msg >= message.message_id"></i>
						</span>
						<span class="time" v-else>
							{{message.sending_date | formatTime }} 
							<i class="icon-feather-check" v-if="message.sender_id == login_user.member_id && active_chat.last_seen_msg >= message.message_id"></i>
						</span>
						</p>
						<a v-if="message.is_deleted == null" href="javascript:void(0)" class="fav-star" :class="{'active': parseInt(message.starred) > 0}" @click="starMessage($event, message)"><i class="icon-material-outline-star-border"></i></a>
						<div class="input-group message-edit-box" v-if="message.is_deleted == null">
							<input type="text" class="form-control" value="Edit text here">
							<div class="input-group-append"><button class="btn btn-outline-primary"><i class="icon-feather-send"></i></button></div>
						</div>	
						<div class="fixed-action-btn" v-if="message.is_deleted == null">
							<a href="javascript:void(0)" ><i class="icon-feather-more-vertical"></i></a>
							<ul>
							  <li v-if="message.sender_id == login_user.member_id"><a class="btn-floating red" href="javascript:void(0)" title="Edit"  @click="editMsg(message)"><i class="icon-feather-edit"></i></a></li>
							  <li v-if="message.sender_id == login_user.member_id"><a class="btn-floating yellow darken-1" href="javascript:void(0)" title="Delete" @click="deleteMsg(message)"><i class="icon-feather-trash"></i></a></li>
							  <li><a class="btn-floating green" href="javascript:void(0)" @click="replyMsg(message)" title="Quote"><i class="icon-line-awesome-reply"></i></a></li>
							</ul>
						</div>                       											
					</div>
					
				</div>
				<div class="clearfix"></div>
			</div>
		</div>		
		<div class="message-bubble me_" v-if="attachment_loading.loading">
			<div class="message-bubble-inner">
				<div class="message-avatar"><img :src="login_user.avatar" alt="" /></div>
					<div class="message-text" style="width:200px;">
					<div class="progress">
						  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
						  aria-valuemin="0" aria-valuemax="100" :style="{width: attachment_loading.progress+'%'}">
						  </div>
					</div>
					</div>
			</div>
			<div class="clearfix"></div>
		</div>		
		<!--
		<div class="message-bubble">
			<div class="message-bubble-inner">
				<div class="message-avatar"><img src="images/user-avatar-small-02.jpg" alt="" /></div>
				<div class="message-text">
					
					<div class="typing-indicator">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div> -->		
</div>
<reply-chat :active_chat="active_chat" :login_user="login_user" v-on:new-message="updateMessage" v-on:new-attachment="updateAttachment" v-on:progress="handleProgress" v-on:complete="handleComplete" v-on:start-upload="handleStartUpload" :reply_msg="reply_msg" :attachment_msg="attachment_msg" v-on:reply_reset="reply_msg=null" v-on:attachment_reset="attachment_msg=null"></reply-chat>
</div>
</script>

<script type="text/javascript">
var last_message_date = '';
Vue.component('active-chat-body', {
  template: '#active-chat-message-body-template',
  props: ['active_chat', 'login_user', 'new_message_received'],
  watch:{
	active_chat: function(newVal, oldVal){
		if(newVal != null){
			/* this.loadMessage(); */
			this.resetAll();
		}
	},
	new_message_received: function(newVal, oldVal){
		this.loadNewMessage();
	}
  },
  data: function(){
	  return {
		message_list: [],
		message_total: 0,
		next_limit: 0,
		reply_msg: null,
		attachment_msg:null,
		updateScroll: false,
		message_date: '',
		attachment_loading: {
			loading: false,
			progress: 0,
		}
	  }
	  
  },
  filters: {
	formatMsg: function(val, term){
        if(term != ''){
            var regrex = new RegExp('('+term+')', 'gi');
            return val.replace(regrex, '<span style="background-color: orange">$1</span>');
        }else{
            return val;
        }
       
     },
	 formatDate: function(val){
		return moment(val, 'YYYY-MM-DD HH:mm:ss').format('D MMMM,YY');
	 },
	 formatTime: function(val){
		return  moment(val, 'YYYY-MM-DD HH:mm:ss').format('h:mm A');
	 },
	 formatFileSize: function(val){
		var size;
		if(val < 1024){
			size = val + ' KB';
		}else{
			size = Math.round((val/1024)) + ' MB';
		} 
		
		return size;
	 }
  },
  
  methods: {
	handleStartUpload: function(){
		this.attachment_loading.loading = true;
		this.updateScroll = true;
	},
	handleProgress: function(progress){
		/* this.attachment_loading.loading = true; */
		this.attachment_loading.progress = progress;
	},
	handleComplete: function(){
		this.attachment_loading.loading = false;
		this.attachment_loading.progress = 0;		
	},
	checkDate: function(message){
		if(moment(message.sending_date, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD') !== last_message_date){
			last_message_date = moment(message.sending_date, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
			return true;
		}
		
		return false;
	},
	replyMsg: function(msg){
        this.reply_msg = msg;
    },
	OpenAttachment:function(attachment){
		//console.log(attachment);
		var imagurl = attachment.file_url;
   	 	$('#modalImgageViewIMG').attr('src', imagurl);
    	$('#modalImgageView').modal('show');
	},
    editMsg: function(msg){
		var messageText = msg.message.replace(/(<br ?\/?>)*/g,"");
        bootbox.confirm({
            title: 'Edit Message!',
            size: 'small',
            message: '<textarea class="form-control" id="message">'+messageText+'</textarea>',
			buttons: {
		        confirm: {
		            label: "Save",
		            className: 'btn btn-success float-end'
		        },
		        cancel: {
		            label: "No",
		            className: 'btn btn-dark float-start'
		        }
		    },
			callback: function (result) {
				if(result==true){
					var $message = $('.bootbox').find('#message');
					if($message.val().trim() == ''){
						$message.addClass('is-invalid');
						return false;
					}else{
						$message.removeClass('is-invalid');
					}
					$.post('<?php echo base_url('message/edit_ajax')?>', {message: $message.val().trim(), ID: msg.message_id}, function(res){
						if(res.status == 1){
							msg.message = res.msg_txt;
							msg.is_edited = res.edited;
							msg.edited_display_date = res.edited_display_date;
						}
					}, 'json');
				}
			}
        });
    },
    deleteMsg: function(msg){
        bootbox.confirm({
            title: 'Delete Message!',
            message: 'Are you sure to delete this message?',
			size: 'small',
			buttons: {
		        confirm: {
		            label: "Yes",
		            className: 'btn btn-success float-end'
		        },
		        cancel: {
		            label: "No",
		            className: 'btn btn-dark float-start'
		        }
		    },
			callback: function (result) {
				if(result==true){
					$.post('<?php echo base_url('message/delete_msg')?>/'+msg.message_id, function (res) {
						if(res.status == 1){
							msg.deleted = res.deleted;
							msg.message = res.msg_txt;
							msg.attachment  = null;
						}
					}, 'JSON');
				}
			}
        });
        
    },
    starMessage: function(e, msg){
        e.preventDefault();
       
       $.post('<?php echo base_url('message/star_toggle'); ?>', {ID: msg.message_id, type: 'message'}, function(res){
            if(res.status > 0){
                if(res.action == 'added'){
                    msg.starred = 1;
                }else{
                    msg.starred = 0;
                }
            }
       }, 'json');
        
    },
	resetAll: function(){
		this.message_total = 0;
		this.next_limit = 0;
		this.message_list = [];
		this.$refs.infiniteLoading.stateChanger.reset();
	},
	
	loadMessage: function(){
		var _self = this,
			conversations_id = _self.active_chat.conversations_id;
		_self.message_list = [];
		$.getJSON('<?php echo base_url('message/load_chat_message')?>/'+conversations_id, function(res){
			if(res.status == 1){
				if(res.chat_message.length > 0){
					res.chat_message.forEach(function(item){
						if(item.attachment != null){
							item.attachment = JSON.parse(item.attachment);
						}
						_self.message_list.push(item);
					});
				}
				_self.message_total = res.chat_message_total;
				_self.next_limit = res.next_limit;
				
			}
		});
	} ,
	
	infiniteHandler: function($state){
		var _self = this,
			conversations_id = _self.active_chat.conversations_id;
		$.getJSON('<?php echo base_url('message/load_chat_message')?>/'+conversations_id, {limit: _self.next_limit}, function(res){
			if(res.status == 1){
				if(res.chat_message.length > 0){
					res.chat_message.forEach(function(item){
						if(item.attachment != null){
							item.attachment = JSON.parse(item.attachment);
						}
						_self.message_list.unshift(item);
					});
					
					_self.message_total = res.chat_message_total;
					_self.next_limit = res.next_limit;
					$state.loaded();
				}else{
					$state.complete();
				}	
				
			}
			
		});
		$('.message-content-inner').niceScroll();
		
		
	},
	
	loadNewMessage: function(){
		var _self = this,
			conversations_id = _self.active_chat.conversations_id;
		$.getJSON('<?php echo base_url('message/load_new_message')?>/'+conversations_id, function(res){
			if(res.status == 1){
				if(res.new_message.length > 0){
					var last_seen_msg;
					res.new_message.forEach(function(item){
						if(item.attachment != null){
							item.attachment = JSON.parse(item.attachment);
							_self.updateAttachment(item);
						}
						last_seen_msg = item.message_id;
						_self.message_list.push(item);
					});
					
					 _self.$emit('last-seen-msg', last_seen_msg);
					 /* _self.active_chat.last_seen_msg = last_seen_msg; */
					_self.updateScroll = true;
					
					
				}
				
			}
		});
	},
	
	updateMessage: function(msg){
		this.message_list.push(msg);
		this.$emit('update-message');
		this.updateScroll = true;
	},
	updateAttachment: function(attachment){
		this.$emit('new-attachment', attachment);
	},

	updateScrollPosition: function(){
		var scrollDiff = 0;
		var msgContainer = this.$refs['message-inner'];
		var scrollHeight = msgContainer.scrollHeight;
		var containerHeight = msgContainer.clientHeight;
		scrollDiff = scrollHeight - containerHeight;
		if(scrollDiff > 0){
			msgContainer.scrollTop = scrollDiff;
		}
	}
  },
  mounted: function(){
	//this.loadMessage();  
  },
  updated: function(){
	if(this.updateScroll){
		this.updateScrollPosition();
	}
	this.updateScroll = false;
  }
});
</script>

<script type="text/x-template" id="reply-chat-template">
<div>
	<div class="message-quote-select" v-if="attachment_msg">
        <div class="message-quote">
            <div v-if="attachment_msg.attachment != null">
                <div class="mb-1" v-if="attachment_msg.attachment.is_image">
                    <a href="<?php D(VZ);?>"><img :src="attachment_msg.attachment.file_url"  class="rounded attach-thumbnail" /></a>
                </div>
                <div class="mb-1" v-else>
                    <div><a href="<?php D(VZ);?>" style="color: black">{{attachment_msg.attachment.org_file_name}}</a></div>
                    <div>Size: {{attachment_msg.attachment.file_size | formatFileSize }}</div>
                </div>
            </div>
            <a href="#" @click.prevent="clearReplyAttachment" class="text-danger close"><i class="icon-feather-x"></i></a>
            <!--<hr>-->
        </div>
    </div>
    <div class="message-quote-select" v-if="reply_msg">
        <div class="message-quote">
            <div v-if="reply_msg.attachment != null">
                <div class="mb-1" v-if="reply_msg.attachment.is_image">
                    <a href="<?php D(VZ);?>"><img :src="reply_msg.attachment.file_url"  class="rounded attach-thumbnail" /></a>
                </div>
                <div class="mb-1" v-else>
                    <div><a href="<?php D(VZ);?>" style="color: black">{{reply_msg.attachment.org_file_name}}</a></div>
                    <div>Size: {{reply_msg.attachment.file_size | formatFileSize }}</div>
                </div>
            </div>
            <div v-else>
                <p><i class="icon-line-awesome-quote-left"></i> <i>{{reply_msg.message}}</i></p>
            </div>
            <a href="#" @click.prevent="clearReply" class="text-danger close"><i class="icon-feather-x"></i></a>
            <!--<hr>-->
        </div>
    </div>
	<div class="message-reply">
		<textarea class="form-control" oninput="auto_grow(this)" cols="1" rows="1" placeholder="Your Message" data-autoresize v-model="message" @keypress.enter="sendOnEnter"></textarea>
		<div class="uploadButton">
			<input class="uploadButton-input" v-on:change="sendAttachmentnew()" ref="file" type="file"  id="upload" multiple="">
			<label class="uploadButton-button ripple-effect" for="upload"><i class="icon-feather-paperclip"></i></label>
		</div>
		<button class="btn btn-primary" @click.prevent="sendMsg" :disabled="sent_status === 'sending'"><i class="icon-feather-send" title="Send"></i></button>
		<div class="clearfix"></div>
	</div>
	<div class="chat-foot">
		<div class="checkbox">
			<input type="checkbox" v-model="send_on_enter" id="keyboard">
			<label for="keyboard" class="mb-0"><span class="checkbox-icon"></span> Use ENTER KEY to send message</label>
		</div>
	</div>
</div>
</script>

<script type="text/javascript">
Vue.component('reply-chat', {
  template: '#reply-chat-template',
  props: ['active_chat', 'login_user', 'reply_msg'],
  data: function(){
	  return {
		message:  '',	
		attachment_data:  '',
		attachment_msg:'',
		sent_status: 'sent',
		send_on_enter: true,
	  }
  },
  filters: {
	 formatFileSize: function(val){
		var size;
		if(val < 1024){
			size = val + ' KB';
		}else{
			size = Math.round((val/1024)) + ' MB';
		} 
		
		return size;
	 }
  },
  
  methods: {
	sendOnEnter: function($event){
		
		if(this.send_on_enter){
			$event.preventDefault();
			this.sendMsg();
		}
	},
    clearReplyAttachment: function(){
		this.attachment_msg=null;
		this.attachment_data=null;
		//this.$emit('attachment_reset');
    },
	clearReply: function(){
       // this.reply_msg = null;
        this.$emit('reply_reset');
    },
	sendAttachmentnew(){
		var _self = this;
		var file = this.$refs.file.files[0];
		var formData = new FormData();
		formData.append('file', file);
		
		formData.append('sender_id', _self.login_user.member_id);
		formData.append('conversations_id', _self.active_chat.conversations_id);
		formData.append('message', '');
		formData.append('reply_to', (_self.reply_msg !== null ? _self.reply_msg.message_id: 0));
		
		
		var sendAttachentURL = '<?php echo base_url('message/send_attachment_temp'); ?>';
		
		var msg_data =  {
			message: '',
			sender_id: _self.login_user.member_id,
			conversations_id: _self.active_chat.conversations_id,
            reply_to: _self.reply_msg !== null ? _self.reply_msg.message_id: 0,
            starred: 0,
		};
		//_self.$emit('start-upload');
		$.ajax({
			  xhr: function() {
				var xhr = new window.XMLHttpRequest();

				xhr.upload.addEventListener("progress", function(evt) {
				  if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					percentComplete = parseInt(percentComplete * 100);
					_self.$emit('progress', percentComplete);

					if (percentComplete === 100) {
						_self.$emit('complete');
					}

				  }
				}, false);

				return xhr;
			  },
			  url: sendAttachentURL,
			  type: "POST",
			  data: formData,
			  dataType: "json",
			  contentType: false,
			  processData: false,
			  success: function(res) {
				if(res.status == 1){
					//msg_data.attachment = res.attachment; 
					_self.attachment_data=res.attachment_data; 
					_self.attachment_msg=res;
				}else{
					bootbox.alert({
						title:'Send Attachment',
						message: 'Failed to upload file',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							
					    }
					});
				}
			  }
		});
    },
	sendAttachment(){
		var _self = this;
		var file = this.$refs.file.files[0];
		var formData = new FormData();
		formData.append('file', file);
		
		formData.append('sender_id', _self.login_user.member_id);
		formData.append('conversations_id', _self.active_chat.conversations_id);
		formData.append('message', '');
		formData.append('reply_to', (_self.reply_msg !== null ? _self.reply_msg.message_id: 0));
		
		
		var sendAttachentURL = '<?php echo base_url('message/send_attachment'); ?>';
		
		var msg_data =  {
			message: '',
			sender_id: _self.login_user.member_id,
			conversations_id: _self.active_chat.conversations_id,
            reply_to: _self.reply_msg !== null ? _self.reply_msg.message_id: 0,
            starred: 0,
		};
		_self.sent_status = 'sending';
		_self.$emit('start-upload');
		$.ajax({
			  xhr: function() {
				var xhr = new window.XMLHttpRequest();

				xhr.upload.addEventListener("progress", function(evt) {
				  if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					percentComplete = parseInt(percentComplete * 100);
					_self.$emit('progress', percentComplete);

					if (percentComplete === 100) {
						_self.$emit('complete');
					}

				  }
				}, false);

				return xhr;
			  },
			  url: sendAttachentURL,
			  type: "POST",
			  data: formData,
			  dataType: "json",
			  contentType: false,
			  processData: false,
			  success: function(res) {
				if(res.status == 1){
					_self.message = '';
					_self.sent_status = 'sent';
					msg_data.sending_date = res.message_data.sending_date;
					msg_data.message_id = res.last_message_id;
					msg_data.reply_to = res.message_data.reply_to;
					msg_data.parent = res.message_data.parent;
					msg_data.attachment = res.attachment; 
                    _self.clearReply();
					_self.$emit('new-message', msg_data);
					_self.$emit('new-attachment', msg_data);
				}else{
					bootbox.alert({
						title:'Send Attachment',
						message: 'Failed to upload file',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							
					    }
					});
				}
			  }
		});
    },
	sendMsg: function(){
		var _self = this;
		if(_self.message.trim().length == 0 && _self.attachment_data.length==0){
			return;
		}
		
		// data to send to server
		var msg_data =  {
			message: _self.message.trim(),
			attachment: _self.attachment_data,
			sender_id: _self.login_user.member_id,
			conversations_id: _self.active_chat.conversations_id,
            reply_to: _self.reply_msg !== null ? _self.reply_msg.message_id: 0,
            starred: 0,
		};
		
		
		_self.sent_status = 'sending';
		$.ajax({
			url: '<?php echo base_url('message/send_msg')?>',
			data: msg_data,
			type: 'POST',
			dataType: 'JSON',
			success: function(res){
				if(res.status == 1){
					_self.message = '';
					_self.attachment_data = '';
					_self.sent_status = 'sent';
                    _self.clearReply();
                    _self.clearReplyAttachment();
					msg_data.sending_date = res.message_data.sending_date;
					msg_data.message_id = res.last_message_id;
                    msg_data.reply_to = res.message_data.reply_to;
					msg_data.parent = res.message_data.parent;
					msg_data.attachment = res.message_data.attachment; 
					_self.$emit('new-message', msg_data);
					_self.$emit('new-attachment', msg_data);
				}
			}
		});
		
	}
  }
});
</script>

<script type="text/x-template" id="attachment-template">

<ul>
	<li v-for="(item, $index) in attachments" :key="$index">
		<a :href="item.attachment.file_url" target="_blank">			
			<h5>{{item.attachment.org_file_name}}</h5>
			<p>Date: {{item.sending_date | formatDate}}</p>
			<p>Size: {{item.attachment.file_size | formatFileSize}}</p>			
		</a>
	</li>
	<infinite-loading @infinite="loadAttachment" ref="infiniteLoading"></infinite-loading>
</ul>

</script>

<script type="text/javascript">
Vue.component('conversation-attachment', {
  template: '#attachment-template',
  props: ['active_chat', 'refresh_attachment'],
  data: function(){
	  return {
		attachments: [],
		next_limit: 0,
		attachment_total: 0,
		
	  }
  },
  watch: {
	refresh_attachment: function(new_val, old_val){
		if(new_val != null){
			this.attachments.unshift(new_val);
		}
		
	}  
  },
  filters: {
	 formatDate: function(val){
		return moment(val, 'YYYY-MM-DD HH:mm:ss').format('D MMMM,YY');
	 },
	 formatTime: function(val){
		return  moment(val, 'YYYY-MM-DD HH:mm:ss').format('h:mm A');
	 },
	 formatFileSize: function(val){
		var size;
		if(val < 1024){
			size = val + ' KB';
		}else{
			size = Math.round((val/1024)) + ' MB';
		} 
		
		return size;
	 }
  },
  methods: {
	resetAll: function(){
		this.attachment_total = 0;
		this.next_limit = 0;
		this.attachments = [];
		this.$refs.infiniteLoading.stateChanger.reset();
	},
	loadAttachment: function($state){
		var _self = this,
			conversations_id = _self.active_chat.conversations_id;
		$.getJSON('<?php echo base_url('message/load_attachments')?>/'+conversations_id, {limit: _self.next_limit}, function(res){
			if(res.status == 1){
				if(res.attachments.length > 0){
					res.attachments.forEach(function(item){
						_self.attachments.push(item);
					});
					
					_self.attachment_total = res.attachment_total;
					_self.next_limit = res.next_limit;
					$state.loaded();
				}else{
					$state.complete();
				}	
				
			}
			
		});
	},
  }
});


</script>