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
.comment-by .status {
    position: absolute;
    right: 0;
    top: 6px;
}

.relative {
    position: relative;
}

.panel-header {
    border-bottom: 1px solid #ddd;
    padding: 1rem 1.333rem;
}

/* Comments */
ul.dashboard-box-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
ul.dashboard-box-list > li {
    border-bottom: 1px solid #e4e4e4;
    padding: 0;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    flex-flow: row wrap;
    transition: 0.3s;
    overflow: hidden;
}
ul.dashboard-box-list > li:hover {
    background-color: #fbfbfb;
}
.dashboard-box .job-listing-details {
	box-shadow: none;
	padding: 0;
	align-items: center;
	flex: auto;
}
.dashboard-box .job-listing {
	box-shadow: none;
	padding: 0;
	margin: 0;
	background-color: transparent;
	display: flex;
	flex-direction: row;
	width: 100%;
}
.dashboard-box .job-listing.width-adjustment {
	max-width: 60%
}
.dashboard-box .job-listing h4 a,
.dashboard-box .job-listing h3 a {
	font-size: 1.125rem;
}
.dashboard-box .job-listing .job-listing-company-logo {
	max-width: 48px;
}
.dashboard-box .job-listing .job-listing-company-logo img {
    height: 48px;
    object-fit: contain;
}
.dashboard-box .job-listing .job-listing-description {
	padding-top: 0;
}
.dashboard-box .job-listing:hover {
	transform: none;
}
.dashboard-box .job-listing .job-listing-footer {
	background-color:transparent;
	border-top:0;
	padding: 0;
	border-radius: 0;
}
.dashboard-box .freelancer-overview {
	padding: 0;
}
.dashboard-box .freelancer-overview-inner {
	flex:  auto;
	display: flex;
	align-items: center;
}
.dashboard-box .freelancer-overview .freelancer-name {
	text-align: left;
	margin: 0 0 0 1.25rem;
	width: 100%;
}
.dashboard-box .freelancer-overview .freelancer-avatar {
	margin: 0;
	width: 90px;
}
.dashboard-box .freelancer-overview .freelancer-avatar img{
	max-height:80px;
    background-color: #eee;
}
.dashboard-box .freelancer-overview.manage-candidates .freelancer-avatar {
	align-self: flex-start;
}
.dashboard-box-footer {
	background-color:#f9f9f9;
	padding:1rem 1.25rem;
    border-top: 1px solid #e4e4e4;
}
.dashboard-box-footer .backbtnproject {
	margin-right:0.5rem
}

.btn-light.active {
	background-color: #40de00 !important;
    color: #fff !important;
}
.job-listing h4.job-listing-title {	
	display: -webkit-box;
    /*height: 24px;
	max-width:300px;*/
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dashboard-box .job-listing h4.job-listing-title {
    max-width: 80%;
}
.job-listing .job-listing-description p {
	overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.938rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.freelancer-intro > h5 {
	font-size: 1.063rem;
	margin-bottom: 0;
	text-transform:capitalize
}
.dashboard-box .job-listing h4.job-listing-title {
    max-width: 80%;
}
.buttons-to-right, .dashboard-box-list .button.to-right {
    position: absolute;
    right: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: 0.3s;
    box-shadow: 0px 0px 10px 15px #fff;
    background-color: #fff;
}
.dashboard-box-list .buttons-to-right.always-visible, .dashboard-box-list li:hover .buttons-to-right {
    opacity: 1;
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
      <li class="nav-item"> <a class="nav-link " href="<?php echo $contract_details_url;?>">Overview</a> </li>
      <li class="nav-item"> <a class="nav-link " href="<?php echo $contract_worklog_url;?>">Work Logs</a> </li>
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_invoice_url;?>">Invoices</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>">Messages & Files</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>">Terms & Settings</a> </li>
    </ul>
    <div class="row">
      <div class="col-lg-9">
        <div class="panel mb-4">
          <div class="panel-body">
          <ul class="nav nav-tabs mb-3">
	      <li class="nav-item"> <a class="nav-link <?php if($show=='all'){?>active<?php }?>" href="<?php echo $contract_invoice_url;?>">All</a> </li>
	      <li class="nav-item"> <a class="nav-link <?php if($show=='pending'){?>active<?php }?>" href="<?php echo $contract_invoice_url;?>?show=pending">Pending</a> </li>
	      <li class="nav-item"> <a class="nav-link <?php if($show=='completed'){?>active<?php }?>" href="<?php echo $contract_invoice_url;?>?show=completed">Paid</a> </li>
	       <li class="nav-item"> <a class="nav-link <?php if($show=='rejected'){?>active<?php }?>" href="<?php echo $contract_invoice_url;?>?show=rejected">Rejected</a> </li>
	       
	    </ul>
          <section class="comments workLogData"></section>
            <div class="text-center" id="loader" style="display: none">
            <svg class="MYspinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>
            </div>
            <div class="container text-center padding-bottom-40">
                  <button class="btn btn-primary" id="load_more" data-page = "0">Load more..</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
	</section>
</div>


<script>
var SPINNER='<svg class="MYspinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
var c_id="<?php echo md5($contractDetails->contract_id)?>";
var getinvoice = function(from){
	$("#loader").show();
	$.ajax({
		url:"<?php echo base_url('invoice/load_invoice')?>",
		type:'GET',
		dataType:'json',
		cache: false,
		data: {from:from,show:'<?php echo $show;?>','cid':c_id},
		success: function(response) {
			var newpage= parseInt(from)+1;
			//console.log(newpage);
			if(response){
				$(".workLogData").append(response.list);
				$("#loader").hide();
				$('#load_more').data('page', newpage);
				if(response.total_page>=newpage){
					$('#load_more').show();
				}else{
					$('#load_more').hide();
				}
			}else{
				$("#loader").hide();
				$('#load_more').hide();
			}
		}
	});
};
$(document).ready(function(){
  $("#load_more").click(function(e){
		e.preventDefault();
		var page = $(this).data('page');
		console.log(page);
		getinvoice(page);
	});
	getinvoice(1);

})

</script>

