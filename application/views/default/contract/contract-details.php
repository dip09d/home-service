<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
//get_print($contractDetails,FALSE);
$profile_url='';
if($is_owner){
	$logo=getMemberLogo($contractDetails->contractor->member_id);
	$name=$contractDetails->contractor->member_name;
	$profile_url="href='".get_link('viewprofileURL').'/'.md5($contractDetails->contractor->member_id)."' target='_blank'";
}else{
	$logo=getCompanyLogo($contractDetails->owner->organization_id);
	if($contractDetails->owner->organization_name){
		$name=$contractDetails->owner->organization_name;
	}else{
		$name=$contractDetails->owner->member_name;
	}
}
$new_contract_url=get_link('HireProjectURL')."/".md5($contractDetails->project_id)."/".md5($contractDetails->contractor_id);
$contract_details_url=get_link('ContractDetails').'/'.md5($contractDetails->contract_id);
$contract_message_url=get_link('ContractMessage').'/'.md5($contractDetails->contract_id);
$contract_term_url=get_link('ContractTerm').'/'.md5($contractDetails->contract_id);
$endcontract_url=get_link('ReviewURL').'/'.md5($contractDetails->contract_id);
$make_dispute_url=get_link('MakeDisputeURL').'/'.md5($contractDetails->contract_id);
$project_Status=getFieldData('project_status','project','project_id',$contractDetails->project_id);

?>

<section class="section">
  <div class="container">
    <h1><?php echo $contractDetails->contract_title;?></h1>
	<?php if($project_Status!=PROJECT_DELETED && $project_Status!=PROJECT_CLOSED && $is_owner){?>
	<div class="d-lg-none mb-3">
        <button class="btn btn-danger btn-block close_project"><?php echo __('contract_close_project','Close Project');?></button>  
	</div>
	<?php }?>
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_details_url;?>"><?php echo __('contract_details_milestone','Milestones & Earnings');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>"><?php echo __('contract_details_mesage','Messages & Files');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>"><?php echo __('contract_details_term','Terms & Settings');?></a> </li>
    </ul>
    <div class="row">
      <div class="col-lg-9 col-12">
        <div class="panel mb-4">
          <div class="panel-body">
            <ul class="totalList mb-0">
              <li><b><?php echo __('contract_details_budget','Budget');?></b> <span> <?php echo $currency.$contractDetails->contract_amount;?></span> </li>
              <li><b><?php echo __('contract_details_escrow','In Escrow');?></b> <span><?php echo $currency.$contractDetails->in_escrow;?></span> </li>
              <li><b><?php echo __('contract_details_milestone','Milestones Paid');?></b><span><?php echo $currency.$contractDetails->milestone_paid;?></span></li>
              <?php if($not_started_contract){?>
              <li><b><?php echo __('contract_details_not_start','Not Started');?></b><span><?php echo $currency.$contractDetails->not_started;?></span></li>
              <?php }?>
              <?php if($contractDetails->disputed){?>
              <li><b><?php echo __('contract_details_disputed','Total Disputed');?></b><span><?php echo $currency.$contractDetails->disputed;?></span></li>
			  <li><b><?php echo __('contract_details_refund','Refunded');?></b> <span><?php echo $currency.$contractDetails->refund_earn;?></span> </li>
              <?php }?>
              <li><b><?php echo __('contract_details_remaining','Remaining');?></b> <span><?php echo $currency.$contractDetails->balance_remain;?></span> </li>
              <?php if($is_owner){?>
              <li><b><?php echo __('contract_details_spent','Spent');?></b> <span><?php echo $currency.$contractDetails->earning;?></span> </li>
              <?php }else{?>
              <li><b><?php echo __('contract_details_earning','Total Earning');?></b> <span><?php echo $currency.$contractDetails->earning;?></span> </li>
              <?php }?>
            </ul>
          </div>
        </div>
        <?php
        if($not_started_contract){
			?>
		<p class="alert alert-warning text-center"><?php echo $not_started_contract;?><?php echo __('contract_details_mile_not_start','milestone not started.');?> </p>	
			<?php
		}
        ?>
        <?php
        if($contractDetails->disputed_milestone){
		?>
		<div class="panel mb-4">
          <div class="panel-header relative">
            <h4><?php echo __('contract_details_disputed','Disputed');?> (<?php echo count($contractDetails->disputed_milestone);?>)</h4>
          </div>
          <div class="panel-body" >
            <ul class="list-group">
              <?php if($contractDetails->disputed_milestone){
					foreach($contractDetails->disputed_milestone as $m=>$milestone){
						//print_r($milestone);
				?>
				<li class="list-group-item w-100 d-flex align-self-center">
              <span class="number"><?php echo $m+1;?>.</span>
              <div class="milestone-item"> <b><?php echo ucfirst($milestone->milestone_title);?></b><br>
                <b><?php echo __('contract_details_amount','Amount:');?></b> <?php echo $milestone->milestone_amount;?> <br>
                <b><?php echo __('contract_details_date','Date:');?></b> <?php echo $milestone->dispute_date;?> <br>
                <?php if($milestone->dispute_status==1){?>
                <b><?php echo __('contract_details_status','Status:');?></b> <span class="badge badge-success"><?php echo __('contract_details_resolve','Resolved');?></span>
                <?php
                }else{
                ?><b><?php echo __('contract_details_status','Status:');?></b> <span class="badge badge-warning"><?php echo __('contract_details_pending','Pending');?></span>
                <?php }?> 
                </div>
             
              <div class="ms-auto align-self-center">
              <a href="<?php echo get_link('DisputeDetails').'/'.md5($milestone->contract_milestone_id);?>" class="btn btn-primary btn-sm"><?php echo __('contract_details_view','View');?></a>
              </div>
              
              </li>
              <?php		
					}
					}
					?>
            </ul>
          </div>
        </div>
		<?php	
		}
        ?>
        <?php
        if(!$pending_contract){
		?>
		<div class="panel mb-4">
			<div class="panel-header relative">
            <h4><?php echo __('contract_details_end_contract','End Contract');?></h4>
          </div>
          <div class="panel-body">
          <?php
          if($contractDetails->is_contract_ended){
		  	?>
		  <p><?php echo __('contract_details_end_contract_on','Contract Ended on');?> <?php echo $contractDetails->contract_end_date;?></p>	
		  	<?php
		  }
		 // get_print($reviews);
          if($reviews){
			  if($reviews['review_by_me'] && $reviews['review_to_me']){
			  ?>
			  <div class="row">
			  <div class="col-sm-6">
              <h5>
			  <?php if($is_owner){?>
			  	<?php echo __('contract_details_feed_contarctor','Your Feedback to Contractor');?>
			  <?php }else{?>
			   <?php echo __('contract_details_feed_client','Your Feedback to Client');?>
			  <?php }?>
			  </h5></div>
			  <div class="col-sm-6"><div class="star-rating mb-2" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div></div>
			  <div class="col-sm-6">
              <h5>
			  <?php if($is_owner){?>
			  	<?php echo __('contract_details_feed_you',"Contractor's Feedback to You");?>
			  <?php }else{?>
			  <?php echo __('contract_details_client_you',"Client's Feedback to You");?>
			  <?php }?>
			  </h5></div>
			  <div class="col-sm-6"><div class="star-rating" data-rating="<?php echo $reviews['review_to_me']->average_review;?>"></div></div>
			  </div>
			  <?php	
			  }elseif($reviews['review_by_me'] && !$reviews['review_to_me']){
			  	?>
			  <div class="row">
			  <div class="col-sm-6"><h5><?php echo __('contract_details_your_feedback','Your Feedback');?></h5></div>
			  <div class="col-sm-6"><div class="star-rating" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div></div>
              </div>
			  <p><?php echo __('contract_details_not_feedback','Client not send feedback yet.');?></p>
			  <?php
			  	
			  }elseif(!$reviews['review_by_me']){
			  ?>
			 <a href="<?php echo $endcontract_url;?>" class="btn btn-primary"><?php echo __('contract_details_send_feedback','Send Feedback');?></a>
			  <?php
			  }	
		  }else{
		  ?>
		  <a href="<?php echo $endcontract_url;?>" class="btn btn-primary"><?php echo __('contract_details_end_contracts','End Contract Now');?></a>
		  <?php	
		  }
          ?>
          </div>
        </div>
		<?php	
		}
        ?>
        <div class="panel">
          <div class="panel-header relative">
            <h4><?php echo __('contract_details_milestones','Milestone');?> (<?php echo count($contractDetails->milestone);?>) <a href="javascript:void(0)" onclick="showMilestone()" class="toggleUD milestoneToggle"><i class="icon-feather-chevron-down"></i></a></h4>
          </div>
          <div class="panel-body" id="milestone" style="display:none">
            <ul class="list-group">
              <?php if($contractDetails->milestone){
					foreach($contractDetails->milestone as $m=>$milestone){
				?>
				<li class="list-group-item w-100 d-flex align-self-center">
              <a href="<?php echo get_link('MilestoneDetails').'/'.md5($milestone->contract_milestone_id);?>" class="text-dark"> <span class="number"><?php echo $m+1;?>.</span>
              <div class="milestone-item"> <b><?php echo ucfirst($milestone->milestone_title);?></b><br>
                <b><?php echo __('contract_details_budgets','Budget:');?></b> <?php echo $currency.$milestone->milestone_amount;?> <br>
                <?php if($milestone->is_approved){?>
                <b><?php echo __('contract_details_complete','Completed:');?></b><?php echo $milestone->approved_date;}else{?><b><?php echo __('contract_dispute_deu_date','Due Date');?>:</b> <?php echo $milestone->milestone_due_date; }?> </div>
              </a>
              <div class="ms-auto align-self-center">
              <?php 
              if(!$contractDetails->is_contract_ended){
              if($is_owner && $milestone->is_approved!=1){
	              	if(!$milestone->is_escrow){
					?>
						<button type="button" class="btn btn-warning btn-sm startWork" data-mid="<?php echo md5($milestone->contract_milestone_id);?>"><?php echo __('contract_offer_initiate','Initiate');?></button>
					<?php	
					}
              	?>
              
              <?php }
              }
              ?>
              <a href="<?php echo get_link('MilestoneDetails').'/'.md5($milestone->contract_milestone_id);?>" class="btn btn-primary btn-sm"><?php echo __('contract_details_view','View');?></a>
              </div>
              
              </li>
              <?php		
					}
					}
					?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-12">
	  <?php if($project_Status!=PROJECT_DELETED && $project_Status!=PROJECT_CLOSED && $is_owner){?>
	  	<div class="d-none d-lg-block mb-2">		
		  <button class="btn btn-danger btn-block close_project"><?php echo __('contract_close_project','Close Project');?></button>  
		</div>
		<?php }?>
        <div class="card text-center mt-4 mt-lg-0">
          <div class="card-body">
			<a <?php echo $profile_url;?>>
            <span class="avatar-logo mb-3"><img src="<?php echo $logo;?>" alt="<?php echo $name;?>" class="rounded-circle" height="96" width="96"></span>
            <h4 class="card-title"><?php echo $name;?></h4>
			</a>
            <?php if($is_owner){?>
            	<p class="text-muted mb-2"><?php D($contractDetails->contractor->member_heading);?></p>
            	<div class="star-rating d-block mb-2" data-rating="<?php echo round($contractDetails->contractor->avg_rating,1);?>"></div> 
            <?php }else{ ?>
             	<div class="star-rating d-block mb-2" data-rating="<?php echo round($contractDetails->owner->statistics->avg_rating,1);?>"></div>
            <?php }?>
            
            <?php if($is_owner){?>
            <a href="<?php echo $new_contract_url;?>" class="btn btn-success btn-block">
            <icon class="icon-material-outline-add"></icon>
            <?php echo __('contract_details_n_contract','New Contract');?></a> <!--<a href="<?php echo VZ;?>" class="btn btn-primary btn-block add_fund_escrow">
            <icon class="icon-material-outline-add"></icon>
            Add Fund</a>-->
            <?php if($pending_contract){?>
            <a href="<?php echo $make_dispute_url;?>" class="btn btn-danger btn-block">
            <icon class="icon-line-awesome-warning"></icon>
            <?php echo __('contract_details_m_disputed','Make Dispute');?></a>
            <?php }?>
            <?php }?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php if($is_owner){?>

<?php }?>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var c_id="<?php echo md5($contractDetails->contract_id)?>";
function showMilestone(){ 
    $('#milestone').toggle();
	$(".milestoneToggle").toggleClass('active');
}
</script>
<?php if($is_owner){?>
<script>
var main=function(){
	$('.add_fund_escrow').click(function(){
		$('#add_fund_modal').modal('show');
	});
	
	$('.startWork').click(function(){
		var buttonsection=$(this);
		var mid=$(this).data('mid');
		bootbox.confirm({
			title:'Work Start Confimation',
			message: 'By confirm, the work will be mark as start and milestone payment will be add in escrow.',
			buttons: {
			'confirm': {
				label: 'Confirm',
				className: 'btn-primary float-end'
				},
			'cancel': {
				label: 'Cancel',
				className: 'btn-dark float-start'
				}
			},
			callback: function (result) {
				if(result){
					buttonsection.attr('disabled','disabled');
					var buttonval = buttonsection.html();
					buttonsection.html(SPINNER).attr('disabled','disabled');
					$.post( "<?php echo get_link('workStartAjaxURL');?>",{'mid':mid}, function( msg ) {
						if (msg['status'] == 'OK') {
							bootbox.alert({
								title:'Work Started',
								message: '<?php D(__('work_start_success_message','Work started and amount added to escrow'));?>',
								buttons: {
								'ok': {
									label: 'Ok',
									className: 'btn-primary float-end'
									}
								},
								callback: function () {
									location.reload();
							    }
							});
						} else if (msg['status'] == 'FAIL') {
							if(msg['popup']){
								if(msg['popup']=='fund'){
									bootbox.alert({
										title: 'Insufficient funds',
										message: 'You do not have sufficient balance to approve. Please add fund <?php echo $currency;?>'+msg['amount_due']+' amount to your wallet.',
										size: 'small',
										buttons: {
											ok: {
												label: "Ok",
												className: 'btn-primary float-end'
											},
										},
										callback: function(result){
											window.open('<?php echo get_link('AddFundURL');?>?pre_amount='+msg['amount_due'],'_blank');
										}
									});
								}
							}else{
								bootbox.alert({
									title:'Work Started',
									message: '<?php D(__('work_error_message',"Opps! . Please try again."));?>',
									buttons: {
									'ok': {
										label: 'Ok',
										className: 'btn-primary float-end'
										}
									},
									callback: function () {
										location.reload();
								    }
								});
							}
						}
						
						buttonsection.html(buttonval).removeAttr('disabled');
					},'JSON');
					

				}
			}
		});
	});
	$('.close_project').click(function(){
		var buttonsection=$(this);
		var pid="<?php echo md5($contractDetails->project_id);?>";
		bootbox.confirm({
			title:'Close Project Confimation',
			message: 'By confirm, the project will be mark as closed.',
			buttons: {
			'confirm': {
				label: 'Confirm',
				className: 'btn-primary float-end'
				},
			'cancel': {
				label: 'Cancel',
				className: 'btn-dark float-start'
				}
			},
			callback: function (result) {
				if(result){
					buttonsection.attr('disabled','disabled');
					var buttonval = buttonsection.html();
					buttonsection.html(SPINNER).attr('disabled','disabled');
					$.post( "<?php echo base_url('contract/close_project');?>",{'pid':pid}, function( msg ) {
						if (msg['status'] == 'OK') {
							bootbox.alert({
								title:'Close Project Confimation',
								message: '<?php D(__('close_project_success_message','Project Closed Successfully'));?>',
								buttons: {
								'ok': {
									label: 'Ok',
									className: 'btn-primary float-end'
									}
								},
								callback: function () {
									location.reload();
							    }
							});
						} else if (msg['status'] == 'FAIL') {
							bootbox.alert({
									title:'Close Project Confimation',
									message: '<?php D(__('work_error_message',"Opps! . Please try again."));?>',
									buttons: {
									'ok': {
										label: 'Ok',
										className: 'btn-primary float-end'
										}
									},
									callback: function () {
										location.reload();
								    }
								});
						}
						
						buttonsection.html(buttonval).removeAttr('disabled');
					},'JSON');

				}
			}
		});
	});
}
</script>
<?php }?>
