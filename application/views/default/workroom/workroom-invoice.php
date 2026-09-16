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

?>

<section class="section">
  <div class="container">
    <h1 class="display-4"><?php echo $contractDetails->contract_title;?></h1>
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_details_url;?>"><?php echo __('workroom_details_overview','Overview');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_worklog_url;?>"><?php echo __('workroom_details_work_logs','Work Logs');?></a> </li>
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_invoice_url;?>"><?php echo __('workroom_details_invoices','Invoices');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>"><?php echo __('workroom_details_message_file','Messages & Files');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>"><?php echo __('workroom_details_term','Terms & Settings');?></a> </li>
    </ul>
    <div class="row">
      <div class="col-lg-9">
      	<div class="panel mb-4">
          <div class="panel-header relative">
            <h4><?php echo __('workroom_invoice_list','Invoice List');?> </h4>
          </div>
          <div class="panel-body p-0">
          <ul class="nav nav-tabs">
	      <li class="nav-item"> <a class="nav-link <?php if($show=='all'){?>active<?php }?>" href="<?php echo $contract_invoice_url;?>"><?php echo __('workroom_invoice_all','All');?></a> </li>
	      <li class="nav-item"> <a class="nav-link <?php if($show=='pending'){?>active<?php }?>" href="<?php echo $contract_invoice_url;?>?show=pending"><?php echo __('workroom_worklog_pending','Pending');?></a> </li>
	      <li class="nav-item"> <a class="nav-link <?php if($show=='completed'){?>active<?php }?>" href="<?php echo $contract_invoice_url;?>?show=completed"><?php echo __('workroom_invoice_paid','Paid');?></a> </li>
	       <li class="nav-item"> <a class="nav-link <?php if($show=='rejected'){?>active<?php }?>" href="<?php echo $contract_invoice_url;?>?show=rejected"><?php echo __('workroom_worklog_rejected','Rejected');?></a> </li>
	       
	    </ul>
	    <div class="dashboard-box margin-top-0">
	     <section class=" workLogData"></section>
		 <div class="text-center" id="loader" style="display: none"><?php load_view('inc/spinner',array('size'=>30));?></div>
	    </div>
	    <div class="text-center">
            <button class="btn btn-primary mt-3 mb-3" id="load_more" data-page = "0"><?php echo __('workroom_invoice_load_more','Load more..');?></button>
        </div>
          </div>
        </div>
          

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
            <?php }else{?>
            <?php if($contractDetails->allow_manual_hour){
            	if($contractDetails->is_pause!=1){?>
            <a href="<?php echo VZ;?>" class="btn btn-primary btn-block add_manual_hour">
            <icon class="icon-material-outline-add"></icon>
            <?php echo __('workroom_invoice_add_hour','Add Hour');?></a>
            <?php }
            }
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
<div id="action_invoice_modal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('workroom_details_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('workroom_invoice_reject_invoice','Reject Invoice');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="ActionInvoice(this)"><?php echo __('workroom_invoice_send','Send');?></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="invoiceactionform" class="form-horizontal" role="form" name="invoiceactionform" onsubmit="return false;">
              <input type="hidden" name="sid" id="sid" value="0"/>
              <div class="form-group">
                <label><b><?php echo __('workroom_worklog_reason','Reason');?></b></label>
                <textarea class="form-control" id="reason" name="reason"></textarea>
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
var getinvoice = function(from){
	$("#loader").show();
	$.ajax({
		url:"<?php D(get_link('GetInvoiceAJAXURL'))?>",
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
var mainload=function(){
	$("#load_more").click(function(e){
		e.preventDefault();
		var page = $(this).data('page');
		console.log(page);
		getinvoice(page);
	});
	getinvoice(1);
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
function ActionInvoice(ev){
	var buttonsection=$(ev);
	buttonsection.attr('disabled','disabled');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	var formID="invoiceactionform";
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('InvoiceActionAjaxURL'))?>",
        data:$('#'+formID).serialize()+'&action_type=deny',
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				$('#action_invoice_modal').modal('hide');
				bootbox.alert({
				title:'Invoice Reject',
				message: '<?php D(__('invoice_reject_success_message','Update Success'));?>',
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
				registerFormPostResponse(formID,msg['errors']);
			}
		}
	})		
}
var main=function(){
	$('.add_fund_escrow').click(function(){
		$('#add_fund_modal').modal('show');
	});
}
</script>
<?php }else{?>
<script>

var main=function(){
	$('.add_manual_hour').click(function(){
		$('#add_hour_modal').modal('show');
	});
}
</script>
<?php }?>