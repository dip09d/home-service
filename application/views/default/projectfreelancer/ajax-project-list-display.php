<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($all_projects,TRUE);
if($all_projects){
	foreach($all_projects as $p=>$projectDetails){
		if($projectDetails->project_status==PROJECT_DRAFT){
			$link=get_link('editprojectURL');
		}else{
			$link=get_link('myProjectDetailsURL')."/".$projectDetails->project_url;
		}
		$link_bid=get_link('myProjectDetailsBidsClientURL')."/".$projectDetails->project_id;
		
		

?>
<li>
	<!-- Job Listing -->
	<div class="job-listing">
		<!-- Job Listing Details -->
		<div class="job-listing-details">

			<!-- Details -->
			<div class="job-listing-description">
				<h4 class="job-listing-title"><a href="<?php D($link);?>"><?php D($projectDetails->project_title);?></a></h4>
<?php
$status=getAllProjectStatus($projectDetails->project_status);
?>
				<!-- Job Listing Footer -->
				<div class="job-listing-footer if-button">
					<ul>                    	
						<li><i class="icon-material-outline-access-time"></i> <b><?php echo __('projectfreelancer_list_posted','Posted:');?></b> <?php D(get_time_ago($projectDetails->bid_date));?></li>
						<li><i class="icon-material-outline-account-balance-wallet"></i> <b><?php echo __('projectfreelancer_list_bid','Bid:');?></b> <?php D($projectDetails->bid_amount);?> <!--<span><?php /* if($projectDetails->is_hourly){?>Hourly<?php }else{?>Fixed<?php }*/?></span>--></li>
                        <li>
						<?php if($projectDetails->is_hourly){?>
                        <span class="dashboard-status-button yellow"><?php D('Hourly');?></span>
                        <?php }else{?>
                        <span class="dashboard-status-button green"><?php D('Fixed');?></span>
                        <?php }?>
                        </li>
						<li>
						<!--<b>Status:</b>--> 
						<?php if($projectDetails->is_hired){?>
						<span class="dashboard-status-button green"><?php D('Hired');?></span>
						<?php }else{?>
						<span class="dashboard-status-button yellow"><?php D('Pending');?></span>
						<?php }?>
							
						</li>
						</ul>
				</div>
                </div>                				
		</div>
	</div>
	<!-- Buttons -->
    <div class="buttons-to-end single-right-button always-visible">
        <a href="<?php D($link);?>" class="btn btn-sm btn-outline-site ico" data-tippy-placement="left" title="View"><i class="icon-feather-eye"></i></a>
    </div>		
</li>
<?php 
	}
}
?>