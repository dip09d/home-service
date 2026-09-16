<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
?>
<!-- Dashboard Container -->
<div class="dashboard-container">

	<?php echo $left_panel;?>
	<!-- Dashboard Sidebar / End -->


	<!-- Dashboard Content
	================================================== -->
	<div class="dashboard-content-container">
		<div class="dashboard-content-inner" >		
			<!--<div class="dashboard-headline">
				<h3>My Favourite</h3>				
			</div>-->
            
			<div class="dashboard-box margin-top-0">

						<!-- Headline -->
						<div class="headline">
							<h3><?php echo __('notification_all','All Notifications');?></h3>
						</div>

						<div class="content">
						<ul class="dashboard-box-list activity-feed">
							<?php if($notification_list){
								foreach($notification_list as $k=>$list){
								// echo '<pre>';
								//print_r($list);
								// echo '</pre>';
								$url=base_url('notification/seen?id='.$list->notification_id.'&link='.urlencode($list->link));
								$logo=getMemberLogo($list->notification_from);
								$read_class='<i class="icon-feather-check"></i>';
								if($list->read_status){
									$read_class='<i class="icon-feather-check-all"></i>';
								}
								$parse_data = !empty($list->template_data) ? (array) json_decode($list->template_data) : array();
								$sender_name='';
								if(array_key_exists('SENDER_NAME',$parse_data)){
									$sender_name=$parse_data['SENDER_NAME'];
								}
							?>
								<li>
								<div class="job-listing">                 
									<!-- Job Listing Details -->
									<div class="job-listing-details">                   
									<!-- Logo --> 
									<a href="<?php echo $url;?>" class="job-listing-company-logo"> <img src="<?php echo $logo;?>" alt=""> </a>                   
									<!-- Details -->
									<div class="job-listing-description">
										<span class="float-end text-muted d-none d-md-block"><i class="icon-material-outline-access-time"></i> <?php echo $list->time_ago;?></span>
										<h4 class="job-listing-title mb-1 mw-100"><a href="<?php echo $url;?>"><?php echo $list->notification;?></a> </h4>                    
										<div class="job-listing-footer">
										<ul>
										<li><i class="icon-material-outline-account-circle"></i> <?php echo $sender_name;?></li>
										<li><i class="icon-feather-calendar"></i> <?php echo $list->sent_date;?> </li>
                                        <li class="d-md-none"><i class="icon-material-outline-access-time"></i> <?php echo $list->time_ago;?></li>
										<li>
											<?php echo $read_class;?>
											
										</li>
										</ul>
										</div>
									</div>
									</div>
								</div> 
								</li>
							<?php
								}
							}else{
							?>
							<li><?php echo __('notification_no_record_F','No record found');?></li>
							<?php 
							}
							?>           
						</ul>
							
							
						</div>
						
					</div>	
					<?php echo $links; ?>

		</div>
	</div>
	<!-- Dashboard Content / End -->

</div>
<!-- Dashboard Container / End -->
