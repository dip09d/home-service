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
.comments b{
	font-weight: 600;
}
.comments h4 {
	color: #333;
	font-size: 1.25rem;
}
.comments h4 span {
	display: inline-block;
	font-size: inherit;
}
.comment {
	font-size: 20px;
}
.comments .button {
	margin: 0 0 10px 0;
	padding: 7px 15px;
}
.comments.reviews .button {
	margin: 0;
}
.comments ul {
	margin-bottom: 0;
	padding-left: 0;
}
.comments ul li {
	display: block;
}
.comments ul li, .comments ul li ul li, .comments ul li ul li ul li, .comments ul li ul li ul li {
	margin: 1rem 0 0 0;
}
.comments ul li ul {
	margin: 0 0 0 64px;
}
.comment-content p {
	margin: 3px 0 0 0;
	line-height: 26px;
}
.comment-content {
	color: #666;
	padding: 0 0 0 100px;
}
.comments ul li ul {
	border-left: 1px solid #e0e0e0;
	padding-left: 25px;
}
.comments ul li ul li:before {
	content: "";
	width: 15px;
	height: 1px;
	background-color: #e0e0e0;
	display: inline-block;
	position: absolute;
	top: 30px;
	left: -25px;
}
.comments ul li {
	position: relative;
}
.comments > ul > li{
	border-bottom:1px dashed #ccc;
	padding-bottom:1.25rem;
}
.comments > ul > li:first-child {
	margin-top:0
}
.comments > ul > li:last-child {
	border-bottom:none;
	padding-bottom:0;
}
.comment-content strong {
	padding-right: 5px;
	color: #666;
}
.comment-content > span {
	color: #777;
}
body .comment-content p {
	padding: 5px 0;
}
.comments-amount {
	color: #777;
	font-weight: 500;
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
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_worklog_url;?>">Work Logs</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_invoice_url;?>">Invoices</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>">Messages & Files</a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>">Terms & Settings</a> </li>
    </ul>
    <div class="row">
      <div class="col-lg-9">
        <div class="panel mb-4">
          <div class="panel-body">
          <ul class="nav nav-tabs mb-3">
          <li class="nav-item"> <a class="nav-link <?php if($show=='all'){?>active<?php }?>" href="<?php echo $contract_worklog_url;?>">All</a> </li>
          <li class="nav-item"> <a class="nav-link <?php if($show=='pending'){?>active<?php }?>" href="<?php echo $contract_worklog_url;?>?show=pending">Pending</a> </li>
          <li class="nav-item"> <a class="nav-link <?php if($show=='completed'){?>active<?php }?>" href="<?php echo $contract_worklog_url;?>?show=completed">Waiting For Invoice</a> </li>
          <li class="nav-item"> <a class="nav-link <?php if($show=='rejected'){?>active<?php }?>" href="<?php echo $contract_worklog_url;?>?show=rejected">Rejected</a> </li>
          
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
var getsubmission = function(from){
	$("#loader").show();
	$.ajax({
		url:"<?php echo base_url('workroom/load_worklog')?>/"+c_id,
		type:'GET',
		dataType:'json',
		cache: false,
		data: {from:from,show:'<?php echo $show;?>'},
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
		getsubmission(page);
	});
	getsubmission(1);

})

</script>

