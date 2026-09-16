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

      

		<div class="row mt-4">
			<div class="col-lg-12">
				<?php if(!empty($conversation_details)){ ?>
					<ul class="timeline">

						<?php foreach($conversation_details as $k => $conversation){ ?>

						<li>
							<!-- timeline icon -->
							<i class="fa fa-comments bg-yellow"></i>

							<div class="timeline-item">

								<span class="time">
									<i class="icon-feather-clock"></i>
									<?php echo date('d M, Y H:i:s', strtotime($conversation->sending_date)); ?>
								</span>

								<h3 class="timeline-header">
									<?php
									// Detect sender type
									if($conversation->sender_type == 'member'){
										$sender_logo = getMemberLogo($conversation->member_id);
										$profile_link = base_url('member/list_record').'?member_id='.$conversation->member_id;
									}else{
										$sender_logo = getWorkerLogo($conversation->worker_id);
										$profile_link = base_url('worker/list_record').'?worker_id='.$conversation->worker_id;
									}
									?>

									<a href="<?php echo $profile_link; ?>" target="_blank">
										<img height="32"
											src="<?php echo $sender_logo; ?>"
											class="img-responsive message-image">

										<?php echo $conversation->sender_name; ?>
									</a>
								</h3>

								<div class="timeline-body">
									<?php echo nl2br(html_entity_decode($conversation->message)); ?>
								</div>

							</div>
						</li>

						<?php } ?>

					</ul>
				<?php } ?>
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
  


<script>





</script>
