<?php //get_print($talent_list, false); ?>

<?php 
if(!function_exists('print_skill')){
	function print_skill($v){
		echo '<span>'.$v['skill_name'].'</span> ';
	}
}
if($talent_list){foreach($talent_list as $k => $freelancer){
	$is_fav_class="";
	if($login_user_id){
		$is_fav = isFavouriteMember($login_user_id, $freelancer['member_id']);
		if($is_fav){
			$is_fav_class="active";
		}	
	}
	
	 ?>
<!-- Freelancer -->
<div class="job-listing">
	<!-- Job Listing Details -->
	<div class="job-listing-details">
		<!-- Logo -->
		<div class="job-listing-company-logo">
			<a href="<?php echo $freelancer['profile_link'];?>"><img src="<?php echo $freelancer['user_logo'];?>" alt="">
			<span class="verified-badge"></span></a>
		</div>

		<!-- Details -->
		<div class="job-listing-description">	
			<div class="freelancer-about">
			<div class="freelancer-intro">		
            	<h5 class="mb-2"><a href="<?php echo $freelancer['profile_link'];?>"><?php echo $freelancer['member_name'];?></a></h5>				
				<h4 class="job-listing-title"><?php echo $freelancer['member_heading'];?></h4>				
				<div class="freelancer-rating">
					<div class="star-rating" data-rating="<?php echo round($freelancer['avg_rating'],1);?>"></div>
				</div>
			</div>
			
			<div class="freelancer-details-list">
				<ul>	                	
                    <li><a href="<?php echo VZ;?>" data-mid="<?php echo md5($freelancer['member_id']);?>" class="btn btn-primary hire-member"><?php echo __('findtalents_hire_freelancer','Hire Freelancer');?></a></li>
                    <li><a href="<?php echo VZ;?>" data-mid="<?php echo md5($freelancer['member_id']);?>" class="btn btn-outline-site invite-member"><?php echo __('findtalents_invite_job','Invite to Job');?></a></li>				
					<li>
                        <p class="mb-1"><?php echo __('findtalents_job_success','Job Success');?> <b><?php echo $freelancer['success_rate'];?>%</b></p>
                        <div class="progress" style="height:6px; min-width: 100px;">
                          <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?php echo $freelancer['success_rate'];?>%" aria-valuenow="<?php echo $freelancer['success_rate'];?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>                
                    </li>
				</ul>
			</div>
				
			</div>
			
			<p class="job-listing-text"><?php echo $freelancer['member_overview'];?></p>
			<div class="task-tags">	
				<?php array_map("print_skill", $freelancer['user_skill']); ?>
			</div>
		</div>
	</div>		
    <div class="job-listing-footer">
        <ul>
            <li><i class="icon-feather-map-pin"></i> <?php echo $freelancer['country_name'];?></li>
            <li><?php echo __('');?>Rate <strong><?php echo $freelancer['member_hourly_rate'] > 0 ? priceSymbol().  priceFormat($freelancer['member_hourly_rate']) . ' / hr' : ' - ';?></strong></li>
			<li><i class="icon-material-outline-account-balance-wallet"></i><?php echo __('');?>Earned <strong><?php D(priceSymbol().displayamount($freelancer['total_earning'],2));?></strong></li>
            <li class="ms-md-auto">
            <?php if($freelancer['badges']){
              	foreach($freelancer['badges'] as $b=>$badge){
              		$badge_icon=UPLOAD_HTTP_PATH.'badge-icons/'.$badge->icon_image;
				?>
				<img src="<?php echo $badge_icon;?>" alt="<?php echo $badge->name;?>" height="26" width="26" data-tippy-placement="top" title="<?php echo $badge->name;?>"  /> &nbsp;
				<?php	
				}
				}
              	?>
            <a href="<?php echo VZ;?>" class="btn btn-circle action_favorite <?php echo $is_fav_class;?>" data-mid="<?php echo md5($freelancer['member_id']);?>"><i class="icon-feather-heart"></i></a>
            <!--<a href="#" class="btn btn-circle btn-light active"><i class="icon-feather-heart"></i></a>-->
            </li>
        </ul>
    </div>			
</div>		
<?php } } ?>
<!-- Freelancer -->

<?php /* -- just for backup
<div class="job-listing">

	<!-- Job Listing Details -->
	<div class="job-listing-details">
		<!-- Logo -->
		<div class="job-listing-company-logo">
			<a href="#"><img src="<?php echo IMAGE;?>user-avatar-big-01.jpg" alt="">
			<span class="verified-badge"></span></a>
		</div>

		<!-- Details -->
		<div class="job-listing-description">	
			<div class="freelancer-about">
			<div class="freelancer-intro">						
				<h3 class="job-listing-title"><a href="#">David Peterson</a></h3>
				<span class="text-muted">iOS Expert + Node Dev</span>
				<div class="freelancer-rating">
					<div class="star-rating" data-rating="4.5"></div>
				</div>
			</div>
			
			<div class="freelancer-details-list">
				<ul>
					<li>Location <strong><i class="icon-feather-map-pin"></i> London</strong></li>
					<li>Rate <strong>$60 / hr</strong></li>
					<li>Job Success <strong>95%</strong></li>
				</ul>
			</div>
				
			</div>
			
			<p class="job-listing-text">Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value.</p>
			<div class="task-tags">
				<a href="#">Accounting</a>
				<a href="#">Analytics</a>
				<a href="#">Brand Licensing</a>
				<a href="#">Business Development</a>
				<a href="#">Financial Management</a>
			</div>
		</div>
	</div>					
</div>
*/?>
<script>
$(document).ready(function(){
  $('#filter').click(function(){
    $('.sidebar-container').slideToggle();
  });
});
</script>