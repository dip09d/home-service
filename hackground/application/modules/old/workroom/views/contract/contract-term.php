<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
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

$contract_details_url=base_url('workroom/details/'.md5($contractDetails->contract_id));
/* $contract_message_url=get_link('ContractMessage').'/'.md5($contractDetails->contract_id); */
$contract_worklog_url=base_url('workroom/worklog/'.md5($contractDetails->contract_id));
$contract_invoice_url=base_url('workroom/invoice/'.md5($contractDetails->contract_id));
$contract_message_url=base_url('workroom/message/'.md5($contractDetails->contract_id));
$contract_term_url=base_url('workroom/contract_term/'.md5($contractDetails->contract_id));
?>
<style>
.panel {
    background-color: #fff;
}
.panel-body {
    padding: 1.333rem;
}
ul.totalList {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    /* align-items: center; */
    align-content: center;
    text-align: center;
    background-color: #fff;
    margin-bottom: 0;
    overflow: hidden;
}
ul.totalList > li {
    flex: 1;
    border-right: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    margin-bottom: -1px;
    padding: 1rem;
}
ul.totalList > li > span {
    display: block;
    margin-top: 0.5rem;
}
.relative {
    position: relative;
}

.panel-header {
    border-bottom: 1px solid #ddd;
    padding: 1rem 1.333rem;
}
.toggleUD {
    font-size: 2rem;
    position: absolute;
    top: 10px;
    right: 15px;
    line-height: 0;
}
.number {
    position: absolute;
    left: 1.25rem;
    top: 0.75rem;
}
.milestone-item {
    padding-left: 1.25rem;
}
</style>
<div class="content-wrapper">
<section class="content-header">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
        <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
     <?php echo $breadcrumb ? $breadcrumb : '';?>
    </section>
	
<section class="content">
<div class="">
        <h1><?php echo $contractDetails->contract_title;?></h1>
		<ul class="nav nav-tabs mb-3">
		<li class="nav-item"> <a class="nav-link " href="<?php echo $contract_details_url;?>">Overview</a> </li>
		<li class="nav-item"> <a class="nav-link " href="<?php echo $contract_worklog_url;?>">Work Logs</a> </li>
		<li class="nav-item"> <a class="nav-link " href="<?php echo $contract_invoice_url;?>">Invoices</a> </li>
		<li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>">Messages & Files</a> </li>
		<li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_term_url;?>">Terms & Settings</a> </li>
		</ul>
        <div class="row">
          <div class="col-lg-9">
			 <div class="card mb-4">
              <div class="card-header">
                <h4>Contract info</h4>
              </div>
               <div class="card-body">
                <div class="row">
                 <?php if($pending_contract==0){?>
	               <?php if($is_owner){?>
	               <div class="col-sm-6"><h5>Client's Feedback to Contractor</h5></div>
	               <div class="col-sm-6">
	              		<?php if($reviews){
	              			if($reviews['review_by_me']){
						?>
						<div class="star-rating" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div>
	               		<p><?php echo nl2br($reviews['review_by_me']->review_comments);?></p>
						<?php		
							}else{
						?>
						<p>No review yet.</p>
						<?php		
							}
	              		}?>
	               </div>
	                <div class="col-sm-6"><h5>Contractor's Feedback to Client</h5></div>
	               <div class="col-sm-6">
	               <?php if($reviews){
	              			if($reviews['review_to_me']){
	              				if($reviews['review_by_me']){
						?>
						<div class="star-rating" data-rating="<?php echo $reviews['review_to_me']->average_review;?>"></div>
	               		<p><?php echo nl2br($reviews['review_to_me']->review_comments);?></p>
	               			<?php }else{?>
	               			<p>After submit review you will able to see client review.</p>
						<?php	
								}
							}else{
						?>
						<p>No review yet.</p>
						<?php		
							}
	              	}else{?>
	              		<p>No review yet.</p>
	              	<?php }?>
	               		
	               </div>
	               <?php }else{?>
	               <div class="col-sm-6"><h5>Contractor's Feedback to Client</h5></div>
	               <div class="col-sm-6">
	               		<?php if($reviews){
	              			if($reviews['review_by_me']){
						?>
						<div class="star-rating" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div>
	               		<p><?php echo nl2br($reviews['review_by_me']->review_comments);?></p>
						<?php		
							}else{
						?>
						<button class="btn btn-site btn-sm mb-5 SubmitReview">Submit Review</button>
						<?php		
							}
	              		}?>
	               </div>
	                <div class="col-sm-6"><h5>Client's Feedback to Contractor</h5></div>
	               <div class="col-sm-6">
	               	<?php if($reviews){
	              			if($reviews['review_to_me']){
	              				if($reviews['review_by_me']){
						?>
						<div class="star-rating" data-rating="<?php echo $reviews['review_to_me']->average_review;?>"></div>
	               		<p><?php echo nl2br($reviews['review_to_me']->review_comments);?></p>
	               			<?php }else{?>
	               			<p>After submit review you will able to see client review. </p>
						<?php	
								}
							}else{
						?>
						<p>No review yet.</p>
						<?php		
							}
	              	}else{?>
	              		<p>No review yet.</p>
	              	<?php }?>
	               </div>
	               <?php }?>
	               <?php }?>
	               <div class="col-sm-6"><h5>Contract Date</h5></div>
	               <div class="col-sm-6"><p><?php echo $contractDetails->contract_date;?></p></div>
	               
	               <div class="col-sm-12">
	               <h5>Description of Work</h5>
	               	<p><?php echo nl2br($contractDetails->contract_details);?></p>
	               </div>
	            </div>
               </div>
               <div class="card-footer">
               	<div class="row">
	               <div class="col-sm-6"><h5 class="mb-0">Contract ID</h5></div>
	                <div class="col-sm-6"><p class="mb-0"><?php echo $contractDetails->contract_id;?></p></div>
                </div>
               </div>
            </div>
            
          </div>
			
        </div>
      </div>
</section>      
 </div>
