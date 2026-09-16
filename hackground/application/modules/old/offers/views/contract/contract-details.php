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
$new_contract_url='#';
$contract_details_url='#';
/* $contract_message_url=get_link('ContractMessage').'/'.md5($contractDetails->contract_id); */
$contract_message_url=base_url('offers/message/'.md5($contractDetails->contract_id));
$contract_term_url=base_url('offers/contract_term/'.md5($contractDetails->contract_id));
$endcontract_url='#';
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
      <div class="col-sm-6 col-12">
        <h1> <?php echo $main_title ? $main_title : '';?></h1>
      </div>
      <div class="col-sm-6 col-12"><?php echo $breadcrumb ? $breadcrumb : '';?></div>
    </div>
    </section>

	 <!-- Content Filter -->
	<?php $this->layout->load_filter(); ?>
	
    <!-- Main content -->
    <section class="content">
	
    <h3><?php echo $contractDetails->contract_title;?></h3>
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_details_url;?>">Milestones & Earnings</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>">Messages & Files</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>">Terms & Settings</a> </li>
    </ul>
    

        <div class="panel mb-4">
          <div class="panel-body">
            <ul class="totalList mb-0">
              <li><b>Budget</b> <span> <?php echo $currency.$contractDetails->contract_amount;?></span> </li>
              <li><b>In Escrow</b> <span><?php echo $currency.$contractDetails->in_escrow;?></span> </li>
              <li><b>Milestones Paid</b><span><?php echo $currency.$contractDetails->milestone_paid;?></span></li>
              <li><b>Remaining</b> <span><?php echo $currency.$contractDetails->balance_remain;?></span> </li>
            </ul>
          </div>
        </div>
        <?php
        if(!$pending_contract){
		?>
		<div class="panel mb-4">
			<div class="panel-header relative">
            <h4>End Contract</h4>
          </div>
          <div class="panel-body">
          <?php
          if($contractDetails->is_contract_ended){
		  	?>
		  <p>Contract Ended on <?php echo $contractDetails->contract_end_date;?></p>	
		  	<?php
		  }
		  //get_print($reviews);
      if($reviews){
			  if($reviews['review_by_me'] || $reviews['review_to_me']){
			  ?>
        <?php if($reviews['review_by_me']){?>
			  <div class="col-sm-6">
          <h5>Client's Feedback to Contractor</h5>
        </div>
			  <div class="col-sm-6">
          <div class="star-rating" data-rating="<?php echo $reviews['review_by_me']->average_review;?>"></div>
        </div>
        <?php }?>
        <?php if($reviews['review_to_me']){?>
			  <div class="col-sm-6">
          <h5>Contractor's Feedback to Client</h5>
        </div>
			  <div class="col-sm-6">
          <div class="star-rating" data-rating="<?php echo $reviews['review_to_me']->average_review;?>"></div>
        </div>
        <?php 
        }  		
		  }
    ?>
		<?php	
		}
  }
        ?>
        <div class="panel mb-4">
          <div class="panel-header relative">
            <h4>Milestone (<?php echo count($contractDetails->milestone);?>) <a href="javascript:void(0)" onclick="showMilestone()" class="toggleUD milestoneToggle"><i class="icon-feather-chevron-down"></i></a></h4>
          </div>
          <div class="panel-body" id="milestone" style="display:none">
            <div class="list-group">
              <?php if($contractDetails->milestone){
					foreach($contractDetails->milestone as $m=>$milestone){
				?>
              <a class="list-group-item list-group-item-action" href="#"> <span class="number"><?php echo $m+1;?>.</span>
              <div class="milestone-item"> <b><?php echo ucfirst($milestone->milestone_title);?></b><br>
                <b>Budget:</b> <?php echo $milestone->milestone_amount;?> <br>
                <?php if($milestone->is_approved){?>
                <b>Completed:</b><?php echo $milestone->approved_date;}else{?><b>Due Date:</b> <?php echo $milestone->milestone_due_date; }?> </div>
              </a>
              <?php		
					}
					}
					?>
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
        <button type="button" class="btn btn-dark pull-left" data-dismiss="modal">Cancel</button>
        <h4 class="modal-title">Add Fund To Escrow</h4>
        <button type="button" class="btn btn-success pull-right" onclick="SaveFund(this)">Add</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="addfundform" class="form-horizontal" role="form" name="addfundform" onsubmit="return false;">
              <div class="form-group">
                <label><b>Amount</b></label>
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
var SPINNER='<svg class="MYspinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
var c_id="<?php echo md5($contractDetails->contract_id)?>";
function showMilestone(){ 
    $('#milestone').toggle();
	$(".milestoneToggle").toggleClass('active');
}
</script>

