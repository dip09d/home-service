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
$endcontract_url='#';

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
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
        <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
     <?php echo $breadcrumb ? $breadcrumb : '';?>
    </section>

	 <!-- Content Filter -->
	<?php $this->layout->load_filter(); ?>
	
    <!-- Main content -->
    <section class="content">
	<div class="">
    <h1><?php echo $contractDetails->contract_title;?></h1>
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_details_url;?>">Overview</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_worklog_url;?>">Work Logs</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_invoice_url;?>">Invoices</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>">Messages & Files</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>">Terms & Settings</a> </li>
    </ul>
    <div class="row">
      <div class="col-lg-9">
        <div class="panel mb-4">
          <div class="panel-body">
            <ul class="totalList mb-0">
              <li><b>Total Bill</b> <span> <?php echo $currency.displayamount($contractDetails->total_bill,2);?></span> </li>
              <li><b>In Escrow</b> <span><?php echo $currency.$contractDetails->in_escrow;?></span> </li>
              <li><b>Amount Paid</b><span><?php echo $currency.$contractDetails->milestone_paid;?></span></li>
              <li><b>Remaining</b> <span><?php echo $currency.displayamount($contractDetails->balance_remain,2);?></span> </li>
            </ul>
            <ul class="totalList mb-0">
              <li><b>Total Hour</b> 
              <span> 
               <?php 
              	$hour=floor($contractDetails->total_hour/60);
                $minutes = floor($contractDetails->total_hour %60);
                ?>
                <?php echo $hour;?>hr <?php echo $minutes;?>min	
                </span> 
              </li>
              <li><b>Accepted</b> 
              <span> 
               <?php 
              	$hour=floor($contractDetails->total_approved/60);
              $minutes = floor($contractDetails->total_approved %60);
              ?>
              <?php echo $hour;?>hr <?php echo $minutes;?>min	
              </span> 
              </li>
              <li><b>Pending</b>
                      <span> 
                      <?php 
                        $hour=floor($contractDetails->total_pending/60);
                $minutes = floor($contractDetails->total_pending %60);
                ?>
                <?php echo $hour;?>hr <?php echo $minutes;?>min	
                </span>
              </li>
              <li><b>Yet To Bill</b><span><?php echo $currency.displayamount($contractDetails->yet_to_bill,2);?></span></li>
            </ul>
          </div>
        </div>
        <?php
        if($contractDetails->is_contract_ended){
		?>
		<div class="panel mb-4">
			<div class="panel-header relative">
            <h4>End Contract</h4>
          </div>
          <div class="panel-body">
         
		  <p>Contract Ended on <?php echo $contractDetails->contract_end_date;?></p>	
		  	<?php
		  
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
      </div>
	  
    </div>
  </div>
	</section>
</div>


<script>
var SPINNER='<svg class="MYspinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
var c_id="<?php echo md5($contractDetails->contract_id)?>";
</script>

