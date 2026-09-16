<?php
$currency=priceSymbol();
$ProjectDetailsURL=SITE_URL.'p/'.$contractDetails->project_url;

if($is_owner){
	$logo=getMemberLogo($contractDetails->contractor->member_id);
	$name=$contractDetails->contractor->member_name;
}else{
	$logo=getCompanyLogo($contractDetails->owner->organization_id);
	if($contractDetails->owner->organization_name){
		$name=$contractDetails->owner->organization_name;
	}else{
		$name=$contractDetails->owner->member_name;
	}
	
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	<div class="row">
      <div class="col-sm-6 col-12">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
      </h1>
	  </div>
      <div class="col-sm-6 col-12"><?php echo $breadcrumb ? $breadcrumb : '';?></div>
	</div>
    </section>

	 <!-- Content Filter -->
	<?php $this->layout->load_filter(); ?>
	
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      
            <!-- content area --> 
			
			<div class="dashboard-content-container">
				<div class="dashboard-content-inner">
                  
					<div id="main_table">		
																	
						<?php 
						if($contractDetails->contract_status==0){
							if($current_member!=$contractDetails->offer_by){ /*
							?>
							<p>You have a new offer. 
							<button class="btn btn-success acceptbtn">Accept</button>
							<button class="btn btn-danger denybtn">Reject</button></p>
							<?php	
							*/ }
						}elseif($contractDetails->contract_status==1){
						?>
						<p><span class="badge badge-success"><i class="icon-material-outline-thumb-up"></i> Offer Accepted.</span></p>
						<?php	
						}elseif($contractDetails->contract_status==2){
						?>
						<p><span class="badge badge-danger"><i class="icon-material-outline-thumb-down"></i> Offer Rejected</span></p>
						<?php	
						}?>
						<div class="card mb-4">
						<div class="card-header">
                        <h4><?php echo $contractDetails->contract_title;?></h4>
                        
                        </div>
						<div class="card-body">
						<?php if($contractDetails->contract_details){?>
						<p><?php echo nl2br($contractDetails->contract_details);?></p>
						<?php }?>
						<p>Title: <a href="<?php echo $ProjectDetailsURL;?>" target="_blank"><?php echo $contractDetails->project_title;?></a></p>
						</div>
						</div>
						<?php if($contractDetails->is_hourly){?>
						<div class="card mb-4">
						<div class="card-header relative"><h4>Term </h4></div>	
						<div class="card-body">
						<p><b>Hourly Rate:</b> <?php echo $currency.$contractDetails->contract_amount;?> /hr</p>
						<p><b>Max Limit:</b> <?php if($contractDetails->max_hour_limit){echo round($contractDetails->max_hour_limit).' hr/week';}else{echo 'No limit';}?></p>
						<p><b>Allow Manual Hour:</b> <?php if($contractDetails->allow_manual_hour){echo 'Yes';}else{echo 'No';}?></p>
						</div>
						
						</div>	
						<?php }else{?>
						<div class="card mb-4">
						<div class="card-header relative"><h4>Milestone (<?php echo count($contractDetails->milestone);?>)</h4> <a href="javascript:void(0)" onclick="showMilestone()" class="toggleUD milestoneToggle"><i class="icon-feather-chevron-down"></i></a></div>
						<div class="card-body" id="milestone" style="display:none">
						<ul class="list-group ">
						<?php if($contractDetails->milestone){
							foreach($contractDetails->milestone as $m=>$milestone){
						?>
						<li class="list-group-item">
						<span class="number"><?php echo $m+1;?>.</span>
						<div class="milestone-item">
							<b><?php echo ucfirst($milestone->milestone_title);?></b><br>
							<b>Budget:</b> <?php echo $milestone->milestone_amount;?> <br> 
							<b>Due Date:</b> <?php echo $milestone->milestone_due_date; ?>
						</div>				
						</li>
						<?php		
							}
							}
							?>
						</ul>
						
						
						</div>
					</div>
						<?php }?>
					<?php if($contractDetails->contract_attachment){?>
					<div class="card mb-4">
						<div class="card-header relative"><h4>Attachment</h4><a href="javascript:void(0)" onclick="showAttach()" class="toggleUD attachmentToggle"><i class="icon-feather-chevron-down"></i></a></div>
						<div class="card-body" id="attachment" style="display:none">
						<div class="attachments-container">
						  <?php
							$attachments=json_decode($contractDetails->contract_attachment);
							foreach($attachments as $k=>$val){
								if($val->file && file_exists(UPLOAD_PATH.'projects-files/projects-contract/'.$val->file)){
									$path_parts = pathinfo($val->name);
							?>
						  <a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/projects-contract/'.$val->file;?>" target="_blank" class="attachment-box "><span><?php echo $path_parts['filename'];?></span><i><?php echo strtoupper($path_parts['extension']);?></i></a>
						  <?php
								}	
							}
							?>
						</div>
						</div>
						</div>	
						<?php }?>
												

				</div>
			
	
        </div>
		 <!-- /.box-body -->
	 </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
<script>
function showMilestone(){ 
    $('#milestone').toggle();
	$(".milestoneToggle").toggleClass('active');
}
function showAttach(){ 
	$('#attachment').toggle();
	$(".attachmentToggle").toggleClass('active');
}
</script>