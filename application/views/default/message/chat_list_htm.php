<?php foreach($chat_list as $k => $v){ ?>
<!-- Notification -->
<li class="notifications-not-read">
<a href="<?php echo base_url('message/index/'.$v->conversations_id);?>">
<span class="notification-avatar status-<?php echo $v->online_status ? 'online' : 'offline';?>"><img src="<?php echo $v->avatar;?>" alt=""></span>
<div class="notification-text">
	<strong><?php echo $v->name;?></strong>
	<p class="notification-msg-text"><?php echo $v->message;?></p>
	<span class="color"><?php echo $v->time_ago; ?></span>
</div>
</a>
</li>
<?php } ?>

<!-- Notification 
<li class="notifications-not-read">
<a href="dashboard-messages.html">
<span class="notification-avatar status-offline"><img src="<?php echo IMAGE;?>user-avatar-small-02.jpg" alt=""></span>
<div class="notification-text">
	<strong>Sindy Forest</strong>
	<p class="notification-msg-text">Hi Tom! Hate to break it to you, but I'm actually on vacation until...</p>
	<span class="color">Yesterday</span>
</div>
</a>
</li>
-->