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
        <div class="dashboard-box">
            <!-- Headline -->
            <div class="headline">
                <h3><?php echo __('contract_offer_all','All Offers');?></h3>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    <?php if($list){foreach($list as $k => $v){ 
                    $contract_details_url=get_link('OfferDetails').'/'.md5($v['contract_id']);
                    ?>
                    <li>
                        <!-- Job Listing -->
                        <div class="job-listing width-adjustment">        
                            <!-- Job Listing Details -->
                            <div class="job-listing-details">
                                <!-- Details -->
                                <div class="job-listing-description">
                                    <h4 class="job-listing-title"><a href="<?php echo $contract_details_url;?>"><?php echo $v['contract_title']; ?></a></h4>                                    <!-- Job Listing Footer -->
                                    <div class="job-listing-footer if-button">
                                        <ul>
                                            <li><b><?php echo __('contract_details_budgets','Budget:');?></b> <?php D($currency.$v['contract_amount']);?><?php if($v['is_hourly']==1){echo'/hr';}?></li>
                                            <li><b><?php echo __('contract_details_date','Date:');?></b> <?php D($v['contract_date']);?></li>
                                            <li>
											<?php if($v['contract_status']==1){?>
                                            <span class="dashboard-status-button green"><?php echo __('contract_list_approved','Approved');?></span>
                                            <?php }elseif($v['contract_status']==2){?>
                                            <span class="dashboard-status-button red"><?php echo __('contract_list_rejected','Rejected');?></span>
                                            <?php }elseif($v['contract_status']==0){?>
                                            <span class="dashboard-status-button yellow"><?php echo __('contract_details_pending','Pending');?></span>
                                            <?php }?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="buttons-to-end single-right-button always-visible">
                            <a href="<?php echo $contract_details_url;?>" class="btn btn-sm btn-outline-site ico" data-tippy-placement="left" title="View">
                                <i class="icon-feather-eye"></i>
                            </a>
                        </div>									
                    </li>
                    <?php } }else{ ?>
                    <li><?php echo __('contract_list_no_recoard','No record found');?></li>
                    <?php }?>        
                </ul>                                
            </div>            
        </div>	
		<?php echo $links; ?>
		</div>
	</div>
	<!-- Dashboard Content / End -->

</div>
<!-- Dashboard Container / End -->
