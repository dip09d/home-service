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

$ProjectDetailsURL=get_link('myProjectDetailsURL')."/".$contractDetails->project_url;
$offer_details_url=get_link('OfferDetails').'/'.md5($contractDetails->contract_id);
$application_link=get_link('viewapplicationURLAJAX')."/".$contractDetails->project_id.'/'.$contractDetails->bid_id;
?>
<section class="section">
<div class="container">
        <h1 class="display-4"><?php echo $contractDetails->contract_title;?></h1>
        <ul class="nav nav-tabs mb-3">
          <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_details_url;?>"><?php echo __('workroom_details_overview','Overview');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_worklog_url;?>"><?php echo __('workroom_details_work_logs','Work Logs');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_invoice_url;?>"><?php echo __('workroom_details_invoices','Invoices');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>"><?php echo __('workroom_details_message_file','Messages & Files');?></a> </li>
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_term_url;?>"><?php echo __('workroom_details_term','Terms & Settings');?></a> </li>
        </ul>
        <div class="row">
          <div class="col-lg-9 col-12">
			 <div class="card mb-4">
              <div class="card-header">
                <h4><?php echo __('workroom_term_contract_info','Contract info');?></h4>
              </div>
               <div class="card-body">
                
	               <?php if($pending_contract==0){?>
	               <?php if($is_owner){?>
                   <div class="row mb-3">
	               <div class="col-sm-6"><h5><?php echo __('workroom_details_feedback_to_contractor','Your Feedback to Contractor');?></h5></div>
	               <div class="col-sm-6">
	              		<?php if($reviews){
	              			if($reviews['review_by_me']){
						?>
						<div class="star-rating" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div>
	               		<p><?php echo nl2br($reviews['review_by_me']->review_comments);?></p>
						<?php		
							}else{
						?>
						<button class="btn btn-primary btn-sm mb-5 SubmitReview"><?php echo __('workroom_term_submit_review','Submit Review');?></button>
						<?php		
							}
	              		}?>
	               </div>
	               </div>
                   <div class="row mb-3">
                   <div class="col-sm-6"><h5><?php echo __('workroom_details_contractor_to_you',"Contractor's Feedback to You");?></h5></div>
	               <div class="col-sm-6">
	               <?php if($reviews){
	              			if($reviews['review_to_me']){
	              				if($reviews['review_by_me']){
						?>
						<div class="star-rating" data-rating="<?php echo $reviews['review_to_me']->average_review;?>"></div>
	               		<p><?php echo nl2br($reviews['review_to_me']->review_comments);?></p>
	               			<?php }else{?>
	               			<p><?php echo __('workroom_term_after_submit','After submit review you will able to see client review.');?></p>
						<?php	
								}
							}else{
						?>
						<p><?php echo __('workroom_term_no_review','No review yet.');?></p>
						<?php		
							}
	              	}else{?>
	              		<p><?php echo __('workroom_term_no_review','No review yet.');?></p>
	              	<?php }?>
	               		
	               </div>
                   </div>
	               <?php }else{?>
                   <div class="row mb-3">
	               <div class="col-sm-6"><h5><?php echo __('workroom_details_feedback_to_client','Your Feedback to Client');?></h5></div>
	               <div class="col-sm-6">
	               		<?php if($reviews){
	              			if($reviews['review_by_me']){
						?>
						<div class="star-rating" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div>
	               		<p><?php echo nl2br($reviews['review_by_me']->review_comments);?></p>
						<?php		
							}else{
						?>
						<button class="btn btn-primary btn-sm mb-5 SubmitReview"><?php echo __('workroom_term_submit_review','Submit Review');?></button>
						<?php		
							}
	              		}?>
	               </div>
	               </div>
                   <div class="row mb-3">
                   <div class="col-sm-6"><h5><?php echo __('workroom_details_client_to_you',"Client's Feedback to You");?></h5></div>
	               <div class="col-sm-6">
	               	<?php if($reviews){
	              			if($reviews['review_to_me']){
	              				if($reviews['review_by_me']){
						?>
						<div class="star-rating" data-rating="<?php echo $reviews['review_to_me']->average_review;?>"></div>
	               		<p><?php echo nl2br($reviews['review_to_me']->review_comments);?></p>
	               			<?php }else{?>
	               			<p><?php echo __('workroom_term_after_submit','After submit review you will able to see client review.');?> </p>
						<?php	
								}
							}else{
						?>
						<p><?php echo __('workroom_term_no_review','No review yet.');?></p>
						<?php		
							}
	              	}else{?>
	              		<p><?php echo __('No review yet.','No review yet.');?></p>
	              	<?php }?>
	               </div>
                   </div>
	               <?php }?>
	               <?php }?>
                   <div class="row mb-3">
	               <div class="col-sm-6"><h5><?php echo __('workroom_term_contract_date','Contract Date');?></h5></div>
	               <div class="col-sm-6"><p><?php echo $contractDetails->contract_date;?></p></div>
	               </div>
	               
	               <h5><?php echo __('workroom_term_description_work','Description of Work');?></h5>
	               	<p><?php echo nl2br($contractDetails->contract_details);?></p>	               
               </div>
               <div class="card-footer">
               	<h5 class="mb-0"><?php echo __('workroom_term_contract_ID','Contract ID:');?> <?php echo $contractDetails->contract_id;?></h5>
               </div>
            </div>
            
          </div>
			
          <div class="col-lg-3 col-12">          
            <div class="card text-center mx-auto">
              <div class="card-body">
			  	<a <?php echo $profile_url;?>>
              	<img src="<?php echo $logo;?>" alt="<?php echo $name;?>" class="rounded-circle mb-3" height="96" width="96">
                <h4 class="card-title"><?php echo $name;?></h4>
                </a>
				<?php if($is_owner){?>
				<p class="text-muted mb-2"><?php D($contractDetails->contractor->member_heading);?></p>
				<div class="star-rating mb-2" data-rating="<?php echo round($contractDetails->contractor->avg_rating,1);?>"></div> 
				<?php }else{ ?>
				<div class="star-rating mb-2" data-rating="<?php echo round($contractDetails->owner->statistics->avg_rating,1);?>"></div>
				<?php }?>
                <p><a href="<?php echo $offer_details_url;?>" target="_blank"><?php echo __('workroom_term_view_Offer','View Offer');?> <i class="icon-feather-external-link"></i></a></p>
                <?php if($contractDetails->bid_id){?>
                <p><a href="<?php echo $application_link;?>" target="_blank"><?php echo __('workroom_term_view_proposal','View Original Proposal');?> <i class="icon-feather-external-link"></i></a></p>
                <?php }?>
                <p><a href="<?php echo $ProjectDetailsURL;?>" target="_blank"><?php echo __('workroom_term_view_job_proposal','View Original Job Posting');?> <i class="icon-feather-external-link"></i></a></p>

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
var main=function(){
	$('.SubmitReview').click(function(){
		window.location.href="<?php echo get_link('ReviewURL')?>/"+c_id;
	});
}
</script>