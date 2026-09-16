<?php if($job_list){foreach($job_list as $k => $v){ 
//get_print($v,FALSE);
$budget = !empty($v['budget']) ? $v['budget'] : 0;
$is_fav_class="";
if($login_user_id){
	$is_fav = isFavouriteJob($login_user_id, $v['project_id']);
	if($is_fav){
		$is_fav_class="active";
	}	
}
?>
<!-- Task -->
<div class="task-listing">
	<div class="task-listing-body">
	<!-- Job Listing Details -->
	<div class="task-listing-details">

		<!-- Details -->
		<div class="task-listing-description">
			<h3 class="task-listing-title"><a href="<?php echo $v['project_detail_url']; ?>"><?php echo $v['project_title']; ?></a></h3>
			<p class="mb-2"><b><?php echo $budget > 0 ? ($v['is_fixed'] == '1' ? 'Fixed' : 'Hourly') . '' : 'Hourly'; ?></b> - <span><?php D($v['experience_level_name']);?> (<i class="icon-feather-<?php D($v['experience_level_key']);?>"></i>)</span> - 
			<?php if($v['is_hourly']){
				$duration=getAllProjectDuration($v['hourly_duration']);
				$durationtime=getAllProjectDurationTime($v['hourly_time_required']);
				?>
			<b><?php echo __('job_joblist_est_time','Est. Time:');?></b>  <span><?php D($duration['name']);?>, <?php D($durationtime['name']);?></span>
			<?php }else{?>
			<b><?php echo __('job_joblist_est_budget','Est. Budget:');?></b> <span><?php echo $budget > 0 ? priceSymbol(). $budget : '';?></span>
			<?php }?>
			</p>
			
           <!-- <p><b><?php echo $budget > 0 ? ($v['is_fixed'] == '1' ? 'Fixed' : 'Hourly') . '' : 'Hourly'; ?></b> <b><?php echo $budget > 0 ? priceSymbol(). $budget : '';?></b> - <span>Intermediate ($$)</span> - <b>Est. Time:</b>  <span>Less than 1 month, 10-30 hrs/week</span></p>		-->		
			<p class="task-listing-text"><?php echo $v['project_short_info']; ?></p>
			<div class="task-tags">
				<?php if($v['skills']){foreach($v['skills'] as $skill){ ?>
				<a href="#"><?php echo $skill['skill_name'];?></a>
				<?php } } ?>
			</div>
		</div>

	</div>

	<div class="task-listing-bid">
		<div class="task-listing-bid-inner">
			<div class="task-offers text-md-end">                 
            	<a href="<?php echo $v['project_detail_url']; ?>" class="btn btn-primary"><?php echo __('job_joblist_send_proposal','Send Proposal');?></a>                       
            </div>
			     
		</div>
	</div>
	</div>
	<div class="task-listing-footer">
		<ul>
        	
        	<li><div class="verified-badge" title="Verified Employer" data-tippy-placement="top"></div><?php echo __('job_joblist_payment_verified','Payment Verified');?>  </li>
            <li><i class="icon-feather-heart"></i><?php echo __('job_joblist_proposal','Proposals:');?>  <b><?php echo $v['total_proposal'];?></b></li>
			<li><i class="icon-feather-map-pin"></i>
				<?php D($v['clientInfo']['client_address']['country'])?>
				<span><?php D($v['clientInfo']['client_address']['location'])?></span>
			</li>
			<li>
			<?php 
			if($v['project_type_code']){
			$project_type = getAllProjectType($v['project_type_code']);
			
				echo '<i class="icon-material-outline-business-center"></i> ';
				echo $project_type['name'];
			}
			
			?>
			
			</li>
			<li><i class="icon-material-outline-account-balance-wallet"></i> <?php echo $budget > 0 ? get_setting('site_currency'). $budget : 'Not Set';?></li>
			<li><i class="icon-material-outline-access-time"></i> <?php D(get_time_ago($v['project_posted_date']));?></li>      
            <li class="ms-md-auto"><a href="<?php echo VZ;?>" class="btn btn-circle btn-light me-2 action_favorite <?php echo $is_fav_class;?>" data-pid="<?php echo md5($v['project_id']);?>"><i class="icon-feather-heart"></i></a>
            <!--<a href="#" class="btn btn-circle btn-light active"><i class="icon-feather-heart"></i></a>-->
            	<a href="<?php echo VZ;?>" class="btn btn-circle btn-light action_report" data-pid="<?php echo md5($v['project_id']);?>"><i class="icon-material-outline-bug-report"></i></a>      
			</li>
        </ul>
	</div>
</div>

<?php } } ?>