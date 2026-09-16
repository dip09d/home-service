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
.message-div .message-offer {
	background:#fafafa;
	border:1px solid #e5e5e5;
	padding:12px 20px 20px 20px;
	margin-bottom:6px;
} 

.message-div .message-offer .price{
	font-size:36px;
	color:#28a745;
} 

.message-div .message-offer p{
	margin-bottom:8px;
}

 </style>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <div class="row">
        <div class="col-sm-6 col-12">
          <h1>
             <?php echo $main_title ? $main_title : '';?>
            <small><?php echo $second_title ? $second_title : '';?></small>
          </h1>
      	</div>
      	<div class="col-sm-6 col-12"><?php echo $breadcrumb ? $breadcrumb : '';?></div>
     </div>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

          <div class="box-tools pull-right">
          </div>
        </div>
		<div class="card-body">
		<p>
		<b>Converstion Under This Category:</b> 
		<a href="<?php echo SITE_URL;?>p/" target="_blank">
			<?php echo $conversation_details->category->category_name; ?>
		</a>
		</p>

		<div class="reviewTo">
		<?php 
			if(!empty($conversation_details->group)) {
				foreach($conversation_details->group as $person){
					// Determine name, ID, and type
					if(!empty($person->member_name)){
						$name = $person->member_name;
						$id = $person->member_id;
						$logo = getMemberLogo($id); 
					} elseif(!empty($person->worker_name)){
						$name = $person->worker_name;
						$id = $person->worker_id;
						$logo = getWorkerLogo($id); 
					} else {
						$name = 'N/A';
						$id = 0;
						$logo = base_url('assets/default-avatar.png');
					}
					?>
					<p class="mb-1">
						<a href="<?php echo base_url('member/list_record'); ?>?member_id=<?php echo $id;?>" target="_blank">
							<img src="<?php echo $logo;?>" class="rounded-circle mr-2" alt="User Image" height="32" width="32" />
							<?php echo $name; ?>
						</a>
					</p>
					<?php
				}
			}
		?>
        <span class="icon-feather-corner-down-right"></span>
        </div>
		</div>
		</div>

		<div class="row mt-4">
    <!--- 3 row Starts --->
    <div class="col-lg-12">
<?php if($conversation_details->conversations){?>
		<ul class="timeline">
		<?php
	foreach($conversation_details->conversations as $k=>$conversation){
		//print_r($conversation);
		/*$status=$conversation->status;*/
		$sender_user_name=$conversation->sender_name;
				  ?>
<!-- timeline time label -->
<!-- <li class="time-label">
	<span class="bg-red">
		<?php echo date('d M, Y',strtotime($conversation->sending_date)); ?>
	</span>
</li> -->
<!-- /.timeline-label -->
<?php 
if(!empty($conversation->attachment)){ 
$file=json_decode($conversation->attachment);
$is_image=false;
if($file->is_image){
	$is_image=true;
}
?>

<li>
	<!-- timeline icon -->
	<?php if($is_image){?>
	<i class="fa fa-camera bg-purple"></i>
	<?php }else{?>
		<i class="fa fa-file bg-purple"></i>
	<?php }?>
	<div class="timeline-item">
		<span class="time"><i class="icon-feather-clock"></i> <?php echo date('d M, Y H:i:s',strtotime($conversation->sending_date)); ?></span>

		<h3 class="timeline-header"><a  href="<?php echo base_url('member/list_record'); ?>?member_id=<?php echo $conversation->sender_id;?>" target="_blank"><img height="32" src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image"> <?php echo $sender_user_name; ?></a> <?php if($conversation->is_edited){?> edited the message<?php }?></h3>

		<div class="timeline-body">
		<a href="<?php echo UPLOAD_HTTP_PATH.'message-files/'.$file->file_name;?>" download>
		<?php 
		if($is_image){
		?>
		<img src="<?php echo UPLOAD_HTTP_PATH.'message-files/'.$file->file_name;?>" alt="<?php echo $file->org_file_name?>" class="margin">
		<?php }else{?>
             <i class="fa fa-download"></i> <?php echo $file->org_file_name; ?>
                           
		<?php }?>
		</a>
		</div>
	</div>
</li>
<?php }else{?>
<!-- timeline item -->
<li>
    <!-- timeline icon -->
    <i class="fa fa-comments bg-yellow"></i>
    <div class="timeline-item">
        <span class="time"><i class="icon-feather-clock"></i> <?php echo date('d M, Y H:i:s', strtotime($conversation->sending_date)); ?></span>

        <h3 class="timeline-header">
            <?php
            // Determine logo and name dynamically
            $sender_logo = $conversation->member_id ? getMemberLogo($conversation->member_id) : getWorkerLogo($conversation->worker_id);
            $sender_name = $conversation->sender_name;
            ?>
            <a href="<?php echo base_url('member/list_record'); ?>?member_id=<?php echo $conversation->member_id; ?>" target="_blank">
                <img height="32" src="<?php echo $sender_logo; ?>" class="img-responsive message-image"> 
                <?php echo $sender_name; ?>
            </a>
            <?php if(!empty($conversation->is_edited)){ ?> edited the message <?php } ?>
        </h3>

        <div class="timeline-body">
            <?php echo html_entity_decode($conversation->message); ?>
        </div>
    </div>
</li>
<?php }?>
<?php if($conversation->is_edited){
	if($conversation->edited){
	foreach($conversation->edited as $edited){
	?>
<li style="padding-left:30px">
	<!-- timeline icon -->
	<div class="timeline-item">
		<span class="time"><i class="icon-feather-clock"></i> <?php echo date('d M, Y H:i:s',strtotime($edited->edit_date)); ?></span>

		<h3 class="timeline-header"><a  href="<?php echo base_url('member/list_record'); ?>?member_id=<?php echo $conversation->sender_id;?>" target="_blank"><img height="32" src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image"> <?php echo $sender_user_name; ?></a></h3>

		<div class="timeline-body">
		<?php echo html_entity_decode($edited->message_org); ?>
		</div>
	</div>
</li>
<?php } }}?>
<?php if($conversation->is_deleted){?>
	<li style="padding-left:30px">
	<i class="fa fa-trash bg-red"></i>

	<div class="timeline-item">
	<span class="time"><i class="icon-feather-clock"></i> <?php echo date('d M, Y H:i:s',strtotime($conversation->is_deleted)); ?></span>
	<h3 class="timeline-header  no-border"><a  href="<?php echo base_url('member/list_record'); ?>?member_id=<?php echo $conversation->sender_id;?>" target="_blank"><img height="32" src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image"> <?php echo $sender_user_name; ?></a> deleted this message</h3>
	</div>
    </li>
<?php }?>
<!-- END timeline item -->
<?php
	}
?>

</ul>
<?php }?>
</div>
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
