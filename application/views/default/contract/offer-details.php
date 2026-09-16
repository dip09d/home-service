<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
$ProjectDetailsURL=get_link('myProjectDetailsURL')."/".$contractDetails->project_url;
//get_print($contractDetails,FALSE);
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
<section class="section">
	<?php //echo $left_panel;?>
    <div class="container">
        <h1 class="display-4"><?php echo $contractDetails->contract_title;?></h1>
        <div class="d-lg-none mb-3">				
        <?php 
        if($contractDetails->contract_status==0){
            if($current_member!=$contractDetails->offer_by){
            ?>
            <button class="btn btn-success btn-sm acceptbtn me-2"><?php echo __('contract_offer_accept','Offer Accept');?></button>
            <button class="btn btn-danger btn-sm denybtn me-2"><?php echo __('contract_offer_reject','Offer Reject');?></button>
            <?php	
            }
        }elseif($contractDetails->contract_status==1){
        ?>
        
        <p><span class="badge badge-success"><i class="icon-material-outline-thumb-up"></i><?php echo __('contract_offer_accepted','Offer Accepted.');?> </span></p>
        
        <?php	
        }elseif($contractDetails->contract_status==2){
        ?>
        <p><span class="badge badge-danger"><i class="icon-material-outline-thumb-down"></i><?php echo __('contract_offer_rejected','Offer Rejected');?> </span></p>        
        <?php	
        }?>
        </div>
        <div class="row">
        <div class="col-lg-9">
        <div class="panel mb-4">
        <div class="panel-header"><h4><?php echo __('contract_end_details','Details');?></h4></div>
        <div class="panel-body">
        <?php if($contractDetails->contract_details){?>
        <p><?php echo nl2br($contractDetails->contract_details);?></p>
        <?php }?>
        <p><a href="<?php echo $ProjectDetailsURL;?>" target="_blank"><?php echo $contractDetails->project_title;?></a></p>
        </div>
        </div>
        <?php if($contractDetails->is_hourly){?>
        <div class="panel mb-4">
        <div class="panel-header relative"><h4><?php echo __('contract_offer_term','Term');?> </h4></div>	
        <div class="panel-body">
        <p><b><?php echo __('contract_offer_h_rate','Hourly Rate:');?></b> <?php echo $currency.$contractDetails->contract_amount;?> /hr</p>
        <p><b><?php echo __('contract_offer_m_limit','Max Limit:');?></b> <?php if($contractDetails->max_hour_limit){echo round($contractDetails->max_hour_limit).' hr/week';}else{echo 'No limit';}?></p>
        <p><b><?php echo __('contract_offer_m_hour','Allow Manual Hour:');?></b> <?php if($contractDetails->allow_manual_hour){echo 'Yes';}else{echo 'No';}?></p>
        </div>
        
        </div>	
        <?php }else{?>
        <div class="panel mb-4">
        <div class="panel-header relative"><h4><?php echo __('contract_details_milestones','Milestone');?> (<?php echo count($contractDetails->milestone);?>)</h4> <a href="javascript:void(0)" onclick="showMilestone()" class="toggleUD milestoneToggle"><i class="icon-feather-chevron-down"></i></a></div>
        <div class="panel-body" id="milestone" style="display:none">
        <ul class="list-group ">
        <?php if($contractDetails->milestone){
            foreach($contractDetails->milestone as $m=>$milestone){
        ?>
        <li class="list-group-item">
        <span class="number"><?php echo $m+1;?>.</span>
        <div class="milestone-item">
            <b><?php echo ucfirst($milestone->milestone_title);?></b><br>
            <b><?php echo __('contract_details_budgets','Budget:');?></b> <?php echo $currency.$milestone->milestone_amount;?> <br> 
            <b><?php echo __('contract_dispute_deu_date','Due Date:');?></b> <i class="icon-feather-calendar text-muted"></i> <?php echo $milestone->milestone_due_date; ?>
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
        <div class="panel mb-4">
        <div class="panel-header relative"><h4><?php echo __('contract_offer_attachment','Attachment');?></h4><a href="javascript:void(0)" onclick="showAttach()" class="toggleUD attachmentToggle"><i class="icon-feather-chevron-down"></i></a></div>
        <div class="panel-body" id="attachment" style="display:none">
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
        <div class="col-lg-3">
        <div class="d-none d-lg-block">				
        <?php 
        if($contractDetails->contract_status==0){
            if($current_member!=$contractDetails->offer_by){
            ?>
            <button class="btn btn-success btn-block acceptbtn"><?php echo __('contract_offer_accept','Offer Accept');?></button>
            <button class="btn btn-danger btn-block denybtn mb-3"><?php echo __('contract_offer_reject','Offer Reject');?></button>
            <?php	
            }
        }elseif($contractDetails->contract_status==1){
        ?>
        
        <p><span class="badge badge-success"><i class="icon-material-outline-thumb-up"></i> <?php echo __('contract_offer_accepted','Offer Accepted.');?></span></p>
        
        <?php	
        }elseif($contractDetails->contract_status==2){
        ?>
        <p><span class="badge badge-danger"><i class="icon-material-outline-thumb-down"></i> <?php echo __('contract_offer_rejected','Offer Rejected');?></span></p>        
        <?php	
        }?>
        </div>
        <div class="card text-center mx-auto">
            <div class="card-body">
            <span class="avatar-logo mb-3"><img src="<?php echo $logo;?>" alt="<?php echo $name;?>" class="rounded-circle" height="96" width="96"></span>                    
            <h4 class="card-title mb-0"><?php echo $name;?></h4>
            <?php if($is_owner){?>
            	<p class="text-muted mb-0"><?php D($contractDetails->contractor->member_heading);?></p>
            	<div class="star-rating" data-rating="<?php echo round($contractDetails->contractor->avg_rating,1);?>"></div> 
            	<?php }else{ ?>
             	<div class="star-rating" data-rating="<?php echo round($contractDetails->owner->statistics->avg_rating,1);?>"></div>
            <?php }?>
            </div>                    
        </div>
        </div>
        </div>
    </div>
</section>

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
<script>
	var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
	var offer_id="<?php echo md5($contractDetails->contract_id)?>";
	var main=function(){
		$('.acceptbtn').click(function(){
			var buttonsection=$(this);
			buttonsection.attr('disabled','disabled');
			var buttonval = buttonsection.html();
			buttonsection.html(SPINNER).attr('disabled','disabled');
			$.post( "<?php echo get_link('offerActionAjaxURL');?>",{'offer_id':offer_id,'action_type':'accept'}, function( msg ) {
				if (msg['status'] == 'OK') {
					bootbox.alert({
						title:'<?php echo __('contract_offer_action','Offer Action');?>',
						message: '<?php D(__('offer_success_message','Update Success'));?>',
						buttons: {
						'ok': {
							label: '<?php echo __('contract_offer_ok','Ok');?>',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							window.location.href=msg['redirect'];
					    }
					});
				} else if (msg['status'] == 'FAIL') {
					bootbox.alert({
						title:'<?php echo __('contract_offer_action','Offer Action');?>',
						message: '<?php D(__('offer_error_message',"Opps! . Please try again."));?>',
						buttons: {
						'ok': {
							label: '<?php echo __('contract_offer_ok','Ok');?>',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							location.reload();
					    }
					});
				}
				
			},'JSON');
	
		});
		$('.denybtn').click(function(){
			var buttonsection=$(this);
			buttonsection.attr('disabled','disabled');
			var buttonval = buttonsection.html();
			buttonsection.html(SPINNER).attr('disabled','disabled');
			$.post( "<?php echo get_link('offerActionAjaxURL');?>",{'offer_id':offer_id,'action_type':'deny'}, function( msg ) {
				if (msg['status'] == 'OK') {
					bootbox.alert({
						title:'Offer Action',
						message: '<?php D(__('offer_success_message','Update Success'));?>',
						buttons: {
						'ok': {
							label: '<?php echo __('contract_offer_ok','Ok');?>',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							window.location.href=msg['redirect'];
					    }
					});
				} else if (msg['status'] == 'FAIL') {
					bootbox.alert({
						title:'Offer Action',
						message: '<?php D(__('offer_error_message',"Opps! . Please try again."));?>',
						buttons: {
						'ok': {
							label: '<?php echo __('contract_offer_ok','Ok');?>',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							location.reload();
					    }
					});
				}
			},'JSON');
		});
	}
</script>