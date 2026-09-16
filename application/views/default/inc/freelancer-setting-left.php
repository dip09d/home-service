<?php
$loggedUser=$this->session->userdata('loggedUser');
if($loggedUser){
	$profile_name='';
	$this->access_user_id=$loggedUser['LID'];	
	$this->access_member_type=$loggedUser['ACC_P_TYP'];
	$this->member_id=$loggedUser['MID'];
	$this->organization_id=$loggedUser['OID'];
	$member_name=getFieldData('member_name','member','member_id',$this->member_id);
	if($this->access_member_type=='C'){
		$logo=getCompanyLogo($this->organization_id);
		$organization_name=getFieldData('organization_name','organization','member_id',$this->member_id);
		$profile_name=($organization_name  ? $organization_name:$member_name);
	}else{
		$logo=getMemberLogo($this->member_id);
		$profile_name=$member_name;
	}
	$profile_type_name=($this->access_member_type =='C'  ? "Client":"Freelancer");
	$badges =getData(array(
		'select'=>'b.icon_image,b_n.name,b_n.description',
		'table'=>'member_badges as m',
		'join'=>array(array('table'=>'badges as b','on'=>'m.badge_id=b.badge_id','position'=>'left'),array('table'=>'badges_names as b_n','on'=>"(b.badge_id=b_n.badge_id and b_n.lang='".get_active_lang()."')",'position'=>'left')),
		'where'=>array('m.member_id'=>$this->member_id,'b.status'=>1),
		'order'=>array(array('b.display_order','asc')),
	));
	$memberDataBasic=getData(array(
		'select'=>'m_b.member_hourly_rate,m_b.available_per_week,m_b.not_available_until,m_s.avg_rating,',
		'table'=>'member as m',
		'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left'),array('table'=>'member_statistics m_s','on'=>'m.member_id=m_s.member_id','position'=>'left')),
		'where'=>array('m.member_id'=>$this->member_id),
		'single_row'=>true,
	));	
	$memberDataBasic->badges=$badges;
	$memberDataBasic->balance=getFieldData('balance','wallet','user_id',$this->member_id);
	$page_class=$this->router->fetch_class();
	$page_method=$this->router->fetch_method();
	$page_key=$page_class.'_'.$page_method;
	//print_r($page_key);
}
?>
<!-- Dashboard Sidebar -->
	<div class="dashboard-sidebar">
		<div class="dashboard-sidebar-inner" data-simplebar>
			<div class="dashboard-nav-container">
				<!-- Responsive Navigation Trigger -->
				<a href="#" class="dashboard-responsive-nav-trigger">
					<span class="hamburger hamburger--collapse" >
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</span>
					<span class="trigger-title"><?php echo __('navigation','Navigation');?></span>
				</a>
				
				<!-- Navigation -->
				<div class="dashboard-nav">
					<div class="dashboard-nav-inner">
                        <div class="profile">
                            <div class="profile_pic">
                            	<img src="<?php echo $logo;?>" alt="<?php echo $profile_name;?>" class="rounded-circle" />                            
                            	<span class="verified-badge"></span>
                            </div>                        
                            <div class="profile-details text-center">
                            <div class="">
                            <h4><?php echo $profile_name;?></h4>
                            <div class="star-rating mb-2" data-rating="<?php echo round($memberDataBasic->avg_rating,1);?>"></div>
                            <?php if($memberDataBasic->badges){?>
                            <div class="mb-2">
                            <?php
                            foreach($memberDataBasic->badges as $b=>$badge){
                                $badge_icon=UPLOAD_HTTP_PATH.'badge-icons/'.$badge->icon_image;
                            ?>
                                <img src="<?php echo $badge_icon;?>" alt="<?php echo $badge->name;?>" height="26" width="26" data-tippy-placement="top" title="<?php echo $badge->name;?>"  /> &nbsp;
                            <?php
                            }
                            ?>
                           </div>
                            <?php }?>
                            </div>
                            
                            <h5>
                            <i class="icon-feather-clock text-info"></i> <?php if($memberDataBasic->member_hourly_rate && $memberDataBasic->member_hourly_rate>0){D(CurrencySymbol().priceFormat($memberDataBasic->member_hourly_rate).'/hr');}else{D('Not set');}?> &nbsp; <span class="text-muted">|</span> &nbsp; 
                            <i class="icon-material-outline-account-balance-wallet text-success"></i> <?php echo CurrencySymbol();?><b><?php D(priceFormat($memberDataBasic->balance));?></b>
                            <?php /*<i class="icon-feather-calendar text-primary"></i> 
                            <b>
                            <?php if($memberDataBasic->not_available_until){
                                    echo 'Offline till '.dateFormat($memberDataBasic->not_available_until);
                                }elseif($memberDataBasic->available_per_week){
                                    $duration=getAllProjectDurationTime($memberDataBasic->available_per_week);
                                    D($duration['freelanceName']);
                                }else{
                                    D('Not set');
                                }?>
                            
                            </b>  */?>
                            </h5>
                            <!-- <h5><i class="icon-material-outline-account-balance-wallet text-success"></i> <?php echo CurrencySymbol();?><b>1500</b></h5> -->
                            <a href="<?php echo URL::get_link('myprofileAJAXURL');?>" class="btn btn-primary btn-block"><?php echo __('my_profile','My profile'); ?></a>
                            
                        	</div>
                    	</div>

						<ul>
							<li class="<?php echo ($page_key == 'dashboard_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('dashboardURL'); ?>"><i class="icon-material-outline-dashboard"></i> <?php echo __('dashboard','Dashboard'); ?></a></li>
							<li class="<?php echo ($page_key == 'message_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('MessageURL');?>"><i class="icon-material-outline-question-answer"></i> <?php echo __('messages','Messages'); ?> <!--<span class="nav-tag">2</span>--></a></li>
							<li class="<?php echo ($page_key == 'notification_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('NotificationURL');?>"><i class="icon-material-outline-notifications-active"></i> <?php echo __('notifications','Notifications'); ?> <!--<span class="nav-tag">2</span>--></a></li>
							<li class="<?php echo ($page_key == 'favorite_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('favoriteURL');?>"><i class="icon-feather-heart"></i> <?php echo __('favourite','Favourite'); ?></a></li>
							<li class="<?php echo ($page_key == 'reviews_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('MyReviewURL');?>"><i class="icon-material-outline-star-border"></i> <?php echo __('reviews','Reviews'); ?></a></li>
						</ul>
						<ul data-submenu-title="Finance">
							<li><a href="#"><i class="icon-material-outline-account-balance-wallet"></i> <?php echo __('finance','Finance'); ?></a>
								<ul>
									<li class="<?php echo ($page_key == 'finance_addfund') ? 'active' : '' ;?>"><a href="<?php D(get_link('AddFundURL'))?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('add_fund','Add Fund');?></a></li>
									<li class="<?php echo ($page_key == 'finance_transaction') ? 'active' : '' ;?>"><a href="<?php D(get_link('TransactionHistoryURL'))?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('transaction','Transaction')?></a></li>
									<li class="<?php echo ($page_key == 'finance_withdraw') ? 'active' : '' ;?>"><a href="<?php D(get_link('WithdrawURL'))?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('withdraw','Withdraw');?></a></li>
									<li class="<?php echo ($page_key == 'invoice_listdata') ? 'active' : '' ;?>"><a href="<?php D(get_link('InvoiceURL'))?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('invoice','Invoice');?></a></li>
								</ul>	
							</li>
						</ul>
						<ul>
							<!--<li><a href="#"><i class="icon-material-outline-business-center"></i> Jobs</a>
								<ul>
									<li><a href="dashboard-manage-jobs.html">Manage Jobs <span class="nav-tag">3</span></a></li>
									<li><a href="dashboard-manage-candidates.html">Manage Candidates</a></li>
									<li><a href="dashboard-post-a-job.html">Post a Job</a></li>
								</ul>	
							</li>-->
							<li><a href="#"><i class="icon-material-outline-assignment"></i> <?php echo __('projects','Projects'); ?></a>
								<ul>
									<li class="<?php echo ($page_key == 'projectfreelancer_bids') ? 'active' : '' ;?>"><a href="<?php D(get_link('myBidsURL'))?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('my_proposals','My Proposals');?></a></li>
									<li class="<?php echo ($page_key == 'contract_offerlist') ? 'active' : '' ;?>"><a href="<?php D(get_link('OfferList'))?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('my_offers','My Offers');?> </a></li>
									<li class="<?php echo ($page_key == 'contract_index') ? 'active' : '' ;?>"><a href="<?php D(get_link('ContractList'))?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('my_contract','My Contract');?> </a></li>
									<!--<li><a href="dashboard-manage-tasks.html">Manage Tasks <span class="nav-tag">2</span></a></li>
									<li><a href="dashboard-manage-bidders.html">Manage Bidders</a></li>
									<li><a href="dashboard-my-active-bids.html">My Active Bids <span class="nav-tag">4</span></a></li>
									<li><a href="dashboard-post-a-task.html">Post a Task</a></li>-->
								</ul>	
							</li>
						</ul>

						<ul>
							<li><a href="<?php echo URL::get_link('settingaccountInfoURL')?>"><i class="icon-material-outline-settings"></i> <?php echo __('settings','Settings'); ?></a>
								<ul>
									<li class="<?php echo ($page_key == 'settings_contact_info') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('settingaccountInfoURL');?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('contact_info','Contact info');?></a></li>
									<li class="<?php echo ($page_key == 'password_security_password') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('settingpasswordURL');?>"><?php echo __('',''); ?><i class="icon-feather-check"></i> <?php echo __('password_security','Password & security');?></a></li>
									<li><a href="<?php echo URL::get_link('myprofileAJAXURL');?>"><i class="icon-feather-check"></i> <?php echo __('my_profile','My profile'); ?></a></li>
								</ul>
							
							</li>
							
							<li><a href="<?php echo URL::get_link('logoutURL');?>"><i class="icon-material-outline-power-settings-new"></i> <?php echo __('logout','Logout'); ?></a></li>
						</ul>
						
					</div>
				</div>
				<!-- Navigation / End -->

			</div>
		</div>
	</div>
	<!-- Dashboard Sidebar / End -->