<?php if($notification_list){foreach($notification_list as $k => $v){ ?>
<li class="notifications-<?php echo $v->read_status == '0' ? 'not-read' : 'read'; ?>">
	<a href="<?php echo base_url('notification/seen?id='.$v->notification_id.'&link='.urlencode($v->link)); ?>">
		<span class="notification-icon" hidden><i class="icon-material-outline-group"></i></span>
		<span class="notification-text">
			<?php echo $v->notification; ?>
		</span>
	</a>
</li>
<?php } } ?>