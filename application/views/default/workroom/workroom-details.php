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
$contract_details_url=get_link('ContractDetailsHourly').'/'.md5($contractDetails->contract_id);
$contract_worklog_url=get_link('ContractWorkLogHourly').'/'.md5($contractDetails->contract_id);
$contract_invoice_url=get_link('ContractInvoiceHourly').'/'.md5($contractDetails->contract_id);
$contract_message_url=get_link('ContractMessageHourly').'/'.md5($contractDetails->contract_id);
$contract_term_url=get_link('ContractTermHourly').'/'.md5($contractDetails->contract_id);
$endcontract_url=get_link('ReviewURL').'/'.md5($contractDetails->contract_id);

?>

<section class="section">
  <div class="container">
    <h1 class="display-4"><?php echo $contractDetails->contract_title;?></h1>
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_details_url;?>"><?php echo __('workroom_details_overview','Overview');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_worklog_url;?>"><?php echo __('workroom_details_work_logs','Work Logs');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_invoice_url;?>"><?php echo __('workroom_details_invoices','Invoices');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>"><?php echo __('workroom_details_message_file','Messages & Files');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>"><?php echo __('workroom_details_term','Terms & Settings');?></a> </li>
    </ul>
    <div class="row">
      <div class="col-lg-9">
        <div class="panel mb-4">
          <div class="panel-body">
          <ul class="totalList mb-0">
              <li><b><?php echo __('workroom_details_total_bill','Total Bill');?></b> <span> <?php echo $currency.displayamount($contractDetails->total_bill,2);?></span> </li>
              <li><b><?php echo __('workroom_details_in_escrow','In Escrow');?></b> <span><?php echo $currency.$contractDetails->in_escrow;?></span> </li>
              <li><b><?php echo __('workroom_details_amount_paid','Amount Paid');?></b><span><?php echo $currency.$contractDetails->milestone_paid;?></span></li>
              <li><b><?php echo __('workroom_details_remaining','Remaining');?></b> <span><?php echo $currency.displayamount($contractDetails->balance_remain,2);?></span> </li>            
              <li><b><?php echo __('workroom_details_total_hour','Total Hour');?></b> 
              <span> 
               <?php 
              	$hour=floor($contractDetails->total_hour/60);
				$minutes = floor($contractDetails->total_hour %60);
				?>
				<?php echo $hour;?>hr <?php echo $minutes;?>min	
				</span> 
			  </li>
              <li><b><?php echo __('workroom_details_accepted','Accepted');?></b> 
              <span> 
               <?php 
              	$hour=floor($contractDetails->total_approved/60);
				$minutes = floor($contractDetails->total_approved %60);
				?>
				<?php echo $hour;?>hr <?php echo $minutes;?>min	
				</span> 
			  </li>
              <li><b><?php echo __('workroom_worklog_pending','Pending');?></b>
              <span> 
               <?php 
              	$hour=floor($contractDetails->total_pending/60);
				$minutes = floor($contractDetails->total_pending %60);
				?>
				<?php echo $hour;?>hr <?php echo $minutes;?>min	
				</span>
			  </li>
              <li><b><?php echo __('workroom_details_to_bill','Yet To Bill');?></b><span><?php echo $currency.displayamount($contractDetails->yet_to_bill,2);?></span></li>
            </ul>
          </div>
        </div>
        
		<?php
       	if(!$pending_contract){
		?>
		<div class="panel mb-4">
			<div class="panel-header relative">
            <h4><?php echo __('workroom_details_end_contract','End Contract');?></h4>
          </div>
           <div class="panel-body">
          <?php
          if($contractDetails->is_contract_ended){
		  	?>
		  <p><?php echo __('workroom_details_end_contract_on','Contract Ended on');?> <?php echo $contractDetails->contract_end_date;?></p>	
		  	<?php
		  }
		 // get_print($reviews);
          if($reviews){
			  if($reviews['review_by_me'] && $reviews['review_to_me']){
			  ?>
              <div class="row">
			  <div class="col-sm-6"><h5>
			  <?php if($is_owner){?>
			  	<?php echo __('workroom_details_feedback_to_contractor','Your Feedback to Contractor');?>
			  <?php }else{?>
			   <?php echo __('workroom_details_feedback_to_client','Your Feedback to Client');?>
			  <?php }?>
			  </h5></div>
			  <div class="col-sm-6"><div class="star-rating" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div></div>
			  <div class="col-sm-6"><h5>
			  <?php if($is_owner){?>
			  	<?php echo __('workroom_details_contractor_to_you',"Contractor's Feedback to You");?>
			  <?php }else{?>
			  <?php echo __('workroom_details_client_to_you',"Client's Feedback to You");?>
			  <?php }?>
			  </h5></div>
			  <div class="col-sm-6"><div class="star-rating" data-rating="<?php echo $reviews['review_to_me']->average_review;?>"></div></div>
              </div>
			  <?php	
			  }elseif($reviews['review_by_me'] && !$reviews['review_to_me']){
			  	?>
			  <div class="row">
              <div class="col-sm-6"><h5><?php echo __('workroom_details_your_feedback','Your Feedback');?></h5></div>
			  <div class="col-sm-6"><div class="star-rating" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div></div>
              </div>
			  <p><?php echo __('workroom_details_client_not_feedback','Client not send feedback yet.');?></p>
			  <?php
			  	
			  }elseif(!$reviews['review_by_me']){
			  ?>
			 <a href="<?php echo $endcontract_url;?>" class="btn btn-primary"><?php echo __('workroom_details_send_feedback','Send Feedback');?></a>
			  <?php
			  }	
		  }else{
		  ?>
		  <a href="<?php echo $endcontract_url;?>" class="btn btn-primary"><?php echo __('workroom_details_end_contract','End Contract Now');?></a>
		  <?php	
		  }
          ?>
          </div>
        </div>
		<?php	
		}
        ?>
      </div>
      <div class="col-lg-3">
        <div class="card text-center mx-auto">
          <div class="card-body"> 
		  	<a <?php echo $profile_url;?>>
		  	<img src="<?php echo $logo;?>" alt="<?php echo $name;?>" class="rounded-circle mb-3" height="96" width="96">
            <h4 class="card-title mb-0"><?php echo $name;?></h4>
			</a>
			<?php if($is_owner){?>
			<p class="text-muted mb-0"><?php D($contractDetails->contractor->member_heading);?></p>
			<div class="star-rating mb-2" data-rating="<?php echo round($contractDetails->contractor->avg_rating,1);?>"></div> 
			<?php }else{ ?>
			<div class="star-rating mb-2" data-rating="<?php echo round($contractDetails->owner->statistics->avg_rating,1);?>"></div>
			<?php }?>
            <?php if($contractDetails->is_pause){?>
            <p class="alert alert-warning"><?php echo __('workroom_details_contract_pause','Contract Pause');?></p>
            <?php }?>
            <?php if($is_owner){?>
            <a href="<?php echo $new_contract_url;?>" class="btn btn-success btn-block">
            <icon class="icon-material-outline-add"></icon>
            <?php echo __('workroom_details_new_contract','New Contract');?></a> <a href="<?php echo VZ;?>" class="btn btn-primary btn-block add_fund_escrow">
            <icon class="icon-material-outline-add"></icon>
            <?php echo __('workroom_details_add_fund','Add Fund');?></a>
            <?php if($contractDetails->is_pause){?>
            <a href="<?php echo VZ;?>" class="btn btn-warning btn-block start_contract">
            <icon class="icon-feather-pause-circle"></icon>
           <?php echo __('workroom_details_resume_contract','Resume Contract');?></a>
           <?php }else{?>
           <a href="<?php echo VZ;?>" class="btn btn-warning btn-block pause_contract">
            <icon class="icon-feather-pause-circle"></icon>
           <?php echo __('workroom_details_pause_contract','Pause Contract');?></a>
           <?php }?>
            <?php }else{?>
            <?php /*if($contractDetails->allow_manual_hour){
            	if($contractDetails->is_pause!=1){?>
            <a href="<?php echo VZ;?>" class="btn btn-primary btn-block add_manual_hour">
            <icon class="icon-material-outline-add"></icon>
            Add Hour</a>
            <?php }
            }*/
            }?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php if($is_owner){?>
<div id="add_fund_modal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('workroom_details_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('workroom_details_add_fund_escrow','Add Fund To Escrow');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveFund(this)"><?php echo __('workroom_details_add','Add');?></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="addfundform" class="form-horizontal" role="form" name="addfundform" onsubmit="return false;">
              <div class="form-group">
                <label><b><?php echo __('workroom_details_amount','Amount');?></b></label>
                <input class="form-control" type="text" id="amount" name="amount" value="0" onkeypress="return isNumberKey(this)">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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
function SaveFund(ev){
	var buttonsection=$(ev);
	buttonsection.attr('disabled','disabled');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	var formID="addfundform";
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('AddFundToEscrowAjaxURL'))?>",
        data:$('#'+formID).serialize()+'&cid='+c_id,
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				$('#add_fund_modal').modal('hide');
				bootbox.alert({
					title:'Add Fund To Escrow',
					message: '<?php D(__('add_fund_escrow_success_message','Fund transfer to escrow succesfully'));?>',
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
						$('#add_fund_modal').modal('hide');
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
				}
				registerFormPostResponse(formID,msg['errors']);
			}
		}
	})		
}
var main=function(){
	$('.add_fund_escrow').click(function(){
		$('#add_fund_modal').modal('show');
	});
	
	$('.pause_contract').click(function(){
		bootbox.confirm({
			title: 'Pause Current Contract',
			message: 'Are you sure? You want to pause the contract',
			size: 'small',
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
			callback: function(result){
				if(result){
					$.post('<?php echo get_link('PauseContractAJAXURL');?>',{cid:c_id,action_type:'pause'},function(response){
						if(response){
							if(response.status=='OK'){
								bootbox.alert({
									title:'Pause Current Contract',
									message: '<?php D(__('pause_success_message','Contract pause succesfully'));?>',
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
					},'JSON');
				}
			}
		});
	});
	$('.start_contract').click(function(){
		bootbox.confirm({
			title: 'Resume Current Contract',
			message: 'Are you sure? You want to resume the contract',
			size: 'small',
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
			callback: function(result){
				if(result){
					$.post('<?php echo get_link('PauseContractAJAXURL');?>',{cid:c_id,action_type:'start'},function(response){
						if(response){
							if(response.status=='OK'){
								bootbox.alert({
									title:'Resume Current Contract',
									message: '<?php D(__('resume_success_message','Contract resume succesfully'));?>',
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
					},'JSON');
				}
			}
		});
	});
}
</script>
<?php }?>
