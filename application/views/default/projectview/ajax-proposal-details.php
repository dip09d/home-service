<?php
defined('BASEPATH') OR exit('No direct script access allowed');
get_print($proposaldetails,FALSE);
//get_print($projects,FALSE);
$is_hourly=$projects['project_settings']->is_hourly;
?>
<div class="modal-header">
<button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('projectview_proposal_cancel','Cancel');?></button>
	<h4 class="modal-title"><?php echo __('projectview_proposal_details','Proposal Details');?></h4>
	<button type="button" class="btn btn-success float-end"><?php echo __('projectview_proposal_save','Save');?></button>
</div>
<div class="modal-body">
<?php if($is_hourly){?>
<label><b><?php echo __('projectview_proposal_total_price','Total price');?> </b></label>

<?php }else{?>
<label><b><?php echo __('projectview_proposal_how_paid','How do you want to be paid?');?></b></label>

<label><b><?php echo __('projectview_proposal_total_price','Total price of project :');?> </b> <?php echo priceSymbol().priceFormat($proposaldetails['proposal']->bid_amount);?></label>

<label><b><?php echo __('projectview_proposal_milestones','Milestones');?></b></label>

<label><b><?php echo __('projectview_proposal_how_long','How long will this project take? :');?> </b><?php echo getAllBidDuration($proposaldetails['proposal']->bid_duration);?></label>
<?php }?>
<label><b><?php echo __('projectview_proposal_cover_letter','Cover Letter');?></b></label>
<?php echo nl2br($proposaldetails['proposal']->bid_details);?>

<?php
			if($proposaldetails['project_question']){
			?>
			<label><b><?php echo __('projectview_proposal_question','Question');?></b></label>
			<?php
				foreach($proposaldetails['project_question'] as $k=>$val){
			?>
			<div class="form-group">
				<label><b><?php echo $k+1;?>. <?php echo $val->question_title;?></b></label>
				<?php echo $val->question_answer;?>
			</div>
			<?php		
				}
			}
			?>

<label><b><?php echo __('projectview_proposal_attachment','Attachments');?></b></label>


</div>
