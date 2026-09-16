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
$contract_details_url=base_url('dispute/details/'.$contractDetails->contract_milestone_id);
$contract_message_url=base_url('dispute/message/'.$contractDetails->contract_milestone_id);
$ProjectDetailsURL='#';
$offer_details_url='#';
$application_link='#';
?>

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
        <h3>Contract: <?php echo $contractDetails->contract_title;?> <a href="<?php echo base_url('offers/contracts')?>?contract_id=<?php echo $contractDetails->contract_id;?>" target="_blank"><i class="icon-feather-external-link"></i></a></h3>
        <h3>Milestone: <?php echo $contractDetails->milestone_title;?></h3>
        
        <ul class="nav nav-tabs mb-3">
          <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_details_url;?>">Submission</a> </li>
          <li class="nav-item"> <a class="nav-link " href="<?php echo $contract_message_url;?>">Messages & Files</a> </li>
        </ul>
        <div class="row">
          <div class="col-lg-12">
			 <div class="card mb-4">
              <div class="card-header">
              <div class="row">
                <div class="col-sm-6">
                  <h5>Client details</h5>
                  <?php 
                    $logo = getMemberLogo($contractDetails->owner->member_id);
                  ?>
                  <p class="mb-0"><a href="<?php echo base_url('member/list_record'); ?>?member_id=<?php echo $contractDetails->owner->member_id;?>" target="_blank"><img src="<?php echo $logo;?>" class="rounded-circle mr-2" alt="User Image" height="32" width="32" /><?php echo $contractDetails->owner->member_name;?></a></p>
                </div>
                <div class="col-sm-6">
                  <h5>Freelancer details</h5>
                  <?php 
                    $logo = getMemberLogo($contractDetails->contractor->member_id);
                  ?>
                  <p class="mb-0"><a href="<?php echo base_url('member/list_record'); ?>?member_id=<?php echo $contractDetails->owner->member_id;?>" target="_blank"><img src="<?php echo $logo;?>" class="rounded-circle mr-2" alt="User Image" height="32" width="32" /><?php echo $contractDetails->contractor->member_name;?></a></p>
                </div>
                
              </div>
              </div>
       </div>
       <?php if($contractDetails->is_send_to_admin){?>
                  <div class="col-sm-12 text-center">
                    <span class="status badge badge-warning">Request sent to admin</span>
                    <?php if($contractDetails->dispute_status==0){?>
                    <a href="javascript:void(0)" class="btn btn-sm btn-site" onclick="resolve()">Resolve</a>
                    <?php }?>
                  </div>
        <?php }?>
       <?php if($contractDetails->dispute_status==1){?>
       <div class=" mt-4 card">

       <div class="card-header">
       <h5>Final Amount</h5>
       <ul class="totalList mb-3">
                	<li><b>Total</b> <span ><?php echo $currency;?><?php echo $contractDetails->milestone_amount;?></span></li>
                	<li><b>Commission</b> <span ><?php echo $currency;?><?php echo $contractDetails->commission_amount;?></span></li>
                	<li><b>To Client</b> <span ><?php echo $currency;?><?php echo $contractDetails->owner_amount;?></span></li>
                	<li><b>To Freelancer</b> <span ><?php echo $currency;?><?php echo $contractDetails->contractor_amount;?></span></li>
            </ul>
      </div>
      </div>
      <?php }?>
          
         
		  <div class="row mt-4">
    <!--- 3 row Starts --->
    <div class="col-lg-12">
<?php if($contractDetails->submission){?>
		<ul class="timeline">
		<?php
	foreach($contractDetails->submission as $k=>$conversation){
		//print_r($conversation);
		/*$status=$conversation->status;*/
		$sender_user_name=$conversation->sender_name;
				  ?>
<!-- timeline time label -->
<!-- <li class="time-label">
	<span class="bg-red">
		<?php echo date('d M, Y',strtotime($conversation->submission_date)); ?>
	</span>
</li> -->
<!-- /.timeline-label -->

<!-- timeline item -->
<li>
	<!-- timeline icon -->
	<i class="fa fa-comments bg-yellow"></i>
	<div class="timeline-item">
		<span class="time"><i class="fa fa-clock-o"></i> <?php echo date('d M,Y H:i:s',strtotime($conversation->submission_date)); ?></span>

		<h3 class="timeline-header">
    <a  href="<?php echo base_url('member/list_record'); ?>?member_id=<?php echo $conversation->sender_id;?>" target="_blank"><img height="32" src="<?php echo getMemberLogo($conversation->sender_id); ?>" class="img-responsive message-image"> <?php echo $sender_user_name; ?></a> 
    
    <?php if($conversation->is_approved==1){?>
        <span class="badge badge-success">Approved</span>
        <?php } elseif($conversation->is_approved==2){?>
        <span class="badge badge-danger">Rejected</span>
        <?php 
        }else{?>
        <span class="badge badge-warning">Pending</span>
        <?php	 }?>
    </h3>

		<div class="timeline-body">
		<p><?php echo html_entity_decode($conversation->submission_description); ?></p>
    <?php 
if(!empty($conversation->submission_attachment)){ 
?>
    <?php
  $files=json_decode($conversation->submission_attachment);
  if($files){
    foreach($files as $f=>$file){
      
    
  ?>
		<a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/dispute-submission/'.$file->file;?>" download>
	<i class="fa fa-download"></i> <?php echo $file->name; ?>
		</a>
    <?php
    }
  }
    ?>
    <?php }?>
    <ul class="totalList mb-3">
        <li><b>Total</b> <span ><?php echo $currency;?><?php echo $contractDetails->milestone_amount;?></span></li>
        <li><b>Commission</b> <span ><?php echo $currency;?><?php echo $conversation->commission_amount;?></span></li>
        <li><b>To Client</b> <span ><?php echo $currency;?><?php echo $conversation->owner_amount;?></span></li>
        <li><b>To Freelancer</b> <span ><?php echo $currency;?><?php echo $conversation->contractor_amount;?></span></li>
      </ul>
		</div>
	</div>
</li>



<!-- END timeline item -->
<?php
	}
?>

</ul>
<?php }?>
</div>
</div>

</div>		
        </div>
      </div>
</section>      
 </div>
 <div id="submit_offer_modal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark pull-left" data-dismiss="modal">Cancel</button>
        <h4 class="modal-title">Resolve dispute</h4>
        <button type="button" class="btn btn-success pull-right" onclick="SaveOffer(this)">Send</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="offerform" class="form-horizontal" role="form" name="offerform" onsubmit="return false;">
            <div class="row mb-4 text-center border-bottom">
            	<div class="col-lg-4">
                <label><b>Total Disputed Amount:</b> </label>
                <h3><?php echo $currency;?><?php echo $contractDetails->milestone_amount;?></h3>
                </div>
            	<div class="col-lg-4">
                <label><b>Commission Amount:</b> </label>
                <h3><?php echo $currency;?><?php echo $site_fee_amount;?></h3>
                </div>
                <div class="col-lg-4">
                <label><b>Remain Amount:</b> </label>
                <h3><?php echo $currency;?><?php echo displayamount($remain_amount,2);?></h3>
                </div>
            </div>
            
              <div class="row">
              	<div class="col-lg-6">
              		<div class="form-group">
		                <label><b>To Client</b></label>
		                <div class="input-with-icon-left">
		                  <i><?php echo $currency;?></i>
		                  <input type="text" name="to_client" id="to_client" class="form-control" value="0" onkeypress="return isNumberKey(this)" onkeyup="updateFullPayment(this)">
		                </div>
		            </div>	
              	</div>
              	<div class="col-lg-6">
              		<div class="form-group">
		                <label><b>To Freelance</b></label>
		                <div class="input-with-icon-left">
		                  <i><?php echo $currency;?></i>
		                	<input type="text" class="form-control" name="to_freelancer" id="to_freelancer" value="0" onkeyup="updateFullPayment(this)"/>
		                </div>
		            </div>	
              	</div>
              </div>

       
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
var SPINNER='loading';
var c_id="<?php echo md5($contractDetails->contract_id)?>";
var m_id="<?php echo md5($contractDetails->contract_milestone_id)?>";
var dis_id="<?php echo md5($contractDetails->project_contract_dispute_id)?>";
function resolve(){
  $('#submit_offer_modal').modal();
}
function updateFullPayment(ev){
	var maxamount=parseFloat("<?php echo displayamount($remain_amount,2);?>");
	var sec=$(ev).attr('id');
	var amount=parseFloat($(ev).val());
	if(isNaN(amount)){
		var amountOrg=0;
	}else{
		var amountOrg=amount;
		if(amountOrg>maxamount){
			$(ev).val(maxamount);
			amountOrg=maxamount;
		}
	}
	var remain=maxamount-amountOrg;
	if(sec=='to_client'){
		$('#to_freelancer').val(remain.toFixed(2));
	}else{
		$('#to_client').val(remain.toFixed(2));
	}
}
function SaveOffer(ev){
	var buttonsection=$(ev);
	buttonsection.attr('disabled','disabled');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	var formID="offerform";
	$.ajax({
    type: "POST",
    url: "<?php D(base_url('dispute/resolve'))?>",
    data:$('#'+formID).serialize()+'&mid='+m_id+'&dis_id='+dis_id,
    dataType: "json",
    cache: false,
    success: function(msg) {
      buttonsection.html(buttonval).removeAttr('disabled');
      clearErrors();
      if (msg['status'] == 'OK') {
        location.reload();
      } else if (msg['status'] == 'FAIL') {
        registerFormPostResponse(formID,msg['errors']);
      }
		}
	})		
}
function clearErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.rerror').hide();
    $('.invalid-feedback').removeClass('invalid-feedback');
}
function registerFormPostResponse(formnameid,errors) {
    clearErrors();
    $('#'+formnameid+' input[type="text"] , #'+formnameid+' input[type="password"], #'+formnameid+' input[type="date"], #'+formnameid+' input[type="number"] , #'+formnameid+' textarea').removeClass('is-invalid').addClass('is-valid');
    if (errors.length > 0) {
        for (i = 0; i < errors.length; i++) {
            showError(formnameid,errors[i].id, errors[i].message);
        }
    }
}
var error_icon='<span class=" icon-line-awesome-exclamation-circle" aria-hidden="true"></span>';
function showError(formnameid,field,message) {
	$('#'+formnameid+' #'+field).addClass('is-invalid');
	$('#'+formnameid+' #'+field+'Error').addClass('invalid-feedback').html(error_icon+' '+message).show();
}
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
   /* console.log(charCode);*/
    if(charCode=='46'){
		 return true;
	}else if (charCode > 31 && (charCode < 48 || charCode > 57)){
       return false;
    }
    else{
      return true;
    }
}
</script>
