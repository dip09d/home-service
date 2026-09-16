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
	
	$memberDataBasic=getData(array(
		'select'=>'m_s.avg_rating,',
		'table'=>'member as m',
		'join'=>array(array('table'=>'member_basic as m_b','on'=>'m.member_id=m_b.member_id','position'=>'left'),array('table'=>'member_statistics m_s','on'=>'m.member_id=m_s.member_id','position'=>'left')),
		'where'=>array('m.member_id'=>$this->member_id),
		'single_row'=>true,
	));	
	$memberDataBasic->balance=getFieldData('balance','wallet','user_id',$this->member_id);
	$page_class=$this->router->fetch_class();
	$page_method=$this->router->fetch_method();
	$page_key=$page_class.'_'.$page_method;
	//print_r($page_key);
}
?>
<!-- Dashboard Sidebar
	================================================== -->
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
                        </div>
                        <h5> <i class="icon-material-outline-account-balance-wallet text-success"></i> <?php echo CurrencySymbol();?><b><?php D(priceFormat($memberDataBasic->balance));?></b> <a href="<?php D(get_link('AddFundURL'))?>" class="btn btn-circle btn-outline-site ms-2" style="width: 1.5rem;height: 1.5rem;line-height: 1.5rem;"><i class="icon-feather-plus"></i></a></h5>
                        
                    </div>
                </div>
						<ul data-submenu-title="Start">
							<li class="<?php echo ($page_key == 'dashboard_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('dashboardURL'); ?>"><i class="icon-material-outline-dashboard"></i><?php echo __('dashboard','Dashboard');?></a></li>
							<li class="<?php echo ($page_key == 'message_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('MessageURL');?>"><i class="icon-material-outline-question-answer"></i>  <?php echo __('messages','Messages');?><!--<span class="nav-tag">2</span>--></a></li>
							<li class="<?php echo ($page_key == 'favorite_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('favoriteURL');?>"><i class="icon-feather-heart"></i><?php echo __('favourite','Favourite'); ?></a></li>
							<li class="<?php echo ($page_key == 'reviews_index') ? 'active' : '' ;?>"><a href="<?php echo URL::get_link('MyReviewURL');?>"><i class="icon-material-outline-star-border"></i> <?php echo __('reviews','Reviews'); ?></a></li>
						</ul>
						<ul data-submenu-title="Finance">
							<li><a href="#"><i class="icon-material-outline-account-balance-wallet"></i> <?php echo __('finance','Finance');?></a>
								<ul>
									<li class="<?php echo ($page_key == 'finance_addfund') ? 'active' : '' ;?>"><a href="<?php D(get_link('AddFundURL'))?>"><i class="icon-feather-check"></i> <?php echo __('add_fund','Add Fund');?></a></li>
									<li class="<?php echo ($page_key == 'finance_transaction') ? 'active' : '' ;?>"><a href="<?php D(get_link('TransactionHistoryURL'))?>"><i class="icon-feather-check"></i> <?php echo __('transaction','Transaction')?></a></li>
									<li class="<?php echo ($page_key == 'finance_withdraw') ? 'active' : '' ;?>"><a href="<?php D(get_link('WithdrawURL'))?>"><i class="icon-feather-check"></i> <?php echo __('withdraw','Withdraw');?></a></li>
									<li class="<?php echo ($page_key == 'invoice_listdata') ? 'active' : '' ;?>"><a href="<?php D(get_link('InvoiceURL'))?>"><i class="icon-feather-check"></i> <?php echo __('invoice','Invoice');?></a></li>
								</ul>	
							</li>
						</ul>
						<ul data-submenu-title="Organize and Manage">
							<li><a href="#"><i class="icon-material-outline-business-center"></i><?php echo __('projects','Projects');?></a>
								<ul>
									<!--<li><a href="<?php D(get_link('myprojectrecentClientURL'))?>">My Projects</a></li>-->
									<li class="<?php echo ($page_key == 'projectclient_all') ? 'active' : '' ;?>"><a href="<?php D(get_link('myProjectClientURL'))?>"><i class="icon-feather-check"></i> <?php echo __('all_posting','All Posting');?></a></li>
									<li class="<?php echo ($page_key == 'contract_offerlist') ? 'active' : '' ;?>"><a href="<?php D(get_link('OfferList'))?>"><i class="icon-feather-check"></i> <?php echo __('all_offers','All Offers');?></a></li>
									<li class="<?php echo ($page_key == 'contract_index') ? 'active' : '' ;?>"><a href="<?php D(get_link('ContractList'))?>"><i class="icon-feather-check"></i> <?php echo __('all_contracts','All Contract');?></a></li>
									<!--<li><a href="<?php D(get_link('myContractClientURL'))?>">All Contracts</a></li>-->
									<li><a href="<?php D(get_link('postprojectURL'))?>"><i class="icon-feather-check"></i> <?php echo __('post_a_job','Post a Job');?></a></li>
								</ul>	
							</li>
							<!--<li><a href="#"><i class="icon-material-outline-assignment"></i> Tasks</a>
								<ul>
									<li><a href="dashboard-manage-tasks.html">Manage Tasks <span class="nav-tag">2</span></a></li>
									<li><a href="dashboard-manage-bidders.html">Manage Bidders</a></li>
									<li><a href="dashboard-my-active-bids.html">My Active Bids <span class="nav-tag">4</span></a></li>
									<li><a href="dashboard-post-a-task.html">Post a Task</a></li>
								</ul>	
							</li>-->
						</ul>

						<ul data-submenu-title="Account">
							<li><a href="<?php D(get_link('settingclientaccountInfoURL'))?>"><i class="icon-material-outline-settings"></i><?php echo __('settings','Settings');?></a>
								<ul>
									<li class="<?php echo ($page_key == 'settings_contact_info') ? 'active' : '' ;?>"><a href="<?php D(get_link('settingclientaccountInfoURL'))?>"><i class="icon-feather-check"></i> <?php echo __('contact_info','Contact info');?></a></li>
									<li class="<?php echo ($page_key == 'password_security_password') ? 'active' : '' ;?>"><a href="<?php D(get_link('settingpasswordURL'))?>"><i class="icon-feather-check"></i> <?php echo __('password_security','Password & security');?></a></li>
								</ul>
							
							</li>
							
							<li><a href="<?php D(get_link('logoutURL'))?>"><i class="icon-material-outline-power-settings-new"></i><?php echo __('logout','Logout');?></a></li>
						</ul>
						
					</div>
				</div>
				<!-- Navigation / End -->

			</div>
		</div>
	</div>
	<!-- Dashboard Sidebar / End -->