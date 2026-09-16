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
      <li class="nav-item"> <a class="nav-link active" href="<?php echo $contract_worklog_url;?>"><?php echo __('workroom_details_work_logs','Work Logs');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_invoice_url;?>"><?php echo __('workroom_details_invoices','Invoices');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_message_url;?>"><?php echo __('workroom_details_message_file','Messages & Files');?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="<?php echo $contract_term_url;?>"><?php echo __('workroom_details_term','Terms & Settings');?></a> </li>
    </ul>
    <div class="row">
      <div class="col-lg-9">
      	<div class="panel mb-4">
          <div class="panel-header relative">
            <h4><?php echo __('workroom_details_work_logs','Work Logs');?> </h4>
          </div>
          <div class="panel-body pt-0">
          <ul class="nav nav-tabs mb-3">
	      <li class="nav-item"> <a class="nav-link <?php if($show=='all'){?>active<?php }?>" href="<?php echo $contract_worklog_url;?>"><?php echo __('workroom_invoice_all','All');?></a> </li>
	      <li class="nav-item"> <a class="nav-link <?php if($show=='pending'){?>active<?php }?>" href="<?php echo $contract_worklog_url;?>?show=pending"><?php echo __('workroom_worklog_pending','Pending');?></a> </li>
	      <li class="nav-item"> <a class="nav-link <?php if($show=='completed'){?>active<?php }?>" href="<?php echo $contract_worklog_url;?>?show=completed"><?php echo __('workroom_worklog_waiting_invoice','Waiting For Invoice');?></a> </li>
	       <li class="nav-item"> <a class="nav-link <?php if($show=='rejected'){?>active<?php }?>" href="<?php echo $contract_worklog_url;?>?show=rejected"><?php echo __('workroom_worklog_rejected','Rejected');?></a> </li>
	       
	    </ul>
	    <div class="">
	    <?php if($show=='completed'){if($is_owner){}else{?>
	    <div class="pb-4">
			<div class="checkbox">
			  <input type="checkbox" value="1" id="pending_invoice_all">
			  <label for="pending_invoice_all"><span class="checkbox-icon"></span><?php echo __('workroom_worklog_select_all','Select All');?></label>
			</div>
			<div class="float-end"> <button class="btn btn-primary creatinvoice"><?php echo __('workroom_worklog_create_invoice','Create Invoice');?></button> </div>
	    </div>
	       
	       <div class="clearfix"></div>
	       <?php }}?>
	     <section class="comments workLogData"></section>
		 <div class="text-center" id="loader" style="display: none"><?php load_view('inc/spinner',array('size'=>30));?></div>
	    </div>
	    <div class="text-center">
            <button class="btn btn-primary mb-3" id="load_more" data-page = "0"><?php echo __('workroom_invoice_load_more','Load more..');?></button>
        </div>
          </div>
        </div>
          

      </div>
      <div class="col-lg-3">
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
	            	if($contractDetails->is_pause!=1){
					?>
					 <a href="<?php echo VZ;?>" class="btn btn-primary btn-block add_manual_hour">
	            <icon class="icon-material-outline-add"></icon>
	            <?php echo __('workroom_invoice_add_hour','Add Hour');?></a>
					<?php	
					}
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
<div id="action_work_modal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('workroom_details_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('workroom_worklog_reject_work','Reject Work');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="ActionWork(this)"><?php echo __('workroom_invoice_send','Send');?></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="workactionform" class="form-horizontal" role="form" name="workactionform" onsubmit="return false;">
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
<div id="add_hour_modal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('workroom_details_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('workroom_worklog_add_manual_H','Add Manual Hour');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveLog(this)"><?php echo __('workroom_details_add','Add');?></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="addhourform" class="form-horizontal" role="form" name="addhourform" onsubmit="return false;">
            
            	<div class="row">
            		<div class="col-sm-4">
            		  <div class="form-group">
		                <label><b><?php echo __('workroom_worklog_start_date','Start Date');?></b></label>
		                <input class="form-control datepicker_from" type="text" id="start_date" name="start_date" value="">
		              </div>
            		</div>
            		<div class="col-sm-4">
            		  <div class="form-group">
		                <label><b><?php echo __('workroom_worklog_end_date','End Date');?></b></label>
		                <input class="form-control datepicker_to" type="text" id="end_date" name="end_date" value="">
		              </div>
            		</div>
            		<div class="col-sm-4">
            			<div class="form-group">
                <label><b><?php echo __('workroom_worklog_duration','Duration');?></b></label>
                <div class="input-group">
                <input class="form-control" type="text" id="duration_hour" name="duration_hour" value="0" onkeypress="return isNumberKey(this)">
                <span class="input-group-text">hr</span>
                <input class="form-control" type="text" id="duration_minutes" name="duration_minutes" value="0" onkeypress="return isNumberKey(this)" style="margin-left: -1px;">
                <span class="input-group-text">min</span>                
                </div>
              </div>
            		</div>
            	</div>
            
            
              <div class="form-group">
                <label><b><?php echo __('workroom_worklog_title','Title');?></b></label>
                <input type="text" class="form-control" id="title" name="title" value="">
              </div>
              <div class="form-group">
                <label><b><?php echo __('workroom_worklog_description','Description');?></b></label>
                <textarea class="form-control" id="details" name="details"></textarea>
              </div>
              <div class="form-group">
                <label><b><?php echo __('workroom_message_attachments','Attachments');?></b></label>
                <input type="file" name="fileinput" id="fileinput" multiple="true">
                <div class="upload-area" id="uploadfile">
                  <h4 class="mb-0"><?php echo __('workroom_worklog_drag_drop','Drag and Drop file here');?><br/>
				  <?php echo __('workroom_worklog_or','Or');?> <br/>
                    <?php echo __('workroom_worklog_select_file','Click to select file');?></h4>
                </div>
                <div id="uploadfile_container"> </div>
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
        <h4 class="modal-title"><?php echo __('workroom_worklog_send_invoice','Send Invoice');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SendInvoice(this)"><?php echo __('workroom_invoice_send','Send');?></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="sendinvoiceform" class="form-horizontal" role="form" name="sendinvoiceform" onsubmit="return false;">
              <div class="log_details_entry"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var c_id="<?php echo md5($contractDetails->contract_id)?>";
var getsubmission = function(from){
	$("#loader").show();
	$.ajax({
		url:"<?php D(get_link('ContractWorkLogHourlyAJAXURL'))?>/"+c_id,
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
var mainload=function(){
	$("#load_more").click(function(e){
		e.preventDefault();
		var page = $(this).data('page');
		console.log(page);
		getsubmission(page);
	});
	getsubmission(1);
}
</script>
<?php if($is_owner){?>
<script>
function SaveFund(ev){
	var buttonsection=$(ev);
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
function ActionWork(ev){
	var buttonsection=$(ev);
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	var formID="workactionform";
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('workActionHourlyAjaxURL'))?>",
        data:$('#'+formID).serialize()+'&cid='+c_id+"&action_type=deny",
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
				$('#action_work_modal').modal('hide');
				bootbox.alert({
				title:'Work Reject',
				message: '<?php D(__('work_success_message','Update Success'));?>',
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
function uploadData(formdata){
	var len = $("#uploadfile_container div.thumbnail_sec").length;
   	var num = Number(len);
	num = num + 1;	
	$("#uploadfile_container").append('<div id="thumbnail_'+num+'" class="thumbnail_sec">'+SPINNER+'</div>');
    $.ajax({
        url: "<?php D(get_link('uploadFileFormCheckAJAXURL'))?>",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
           if(response.status=='OK'){
    			var name = response.upload_response.original_name;
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class=" text-danger ico float-end" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
		   }else{
		   	bootbox.alert({
				title: 'Uplaod File',
				message: 'Error in upload file',
				size: 'small',
				buttons: {
					ok: {
						label: "Ok",
						className: 'btn-primary float-end'
					},
				},
				callback: function(result){
					$("#thumbnail_"+num).remove();
				}
			});	
		   }
		   $('#fileinput').val('');
        },
        
    }).fail(function(){
    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
    });
	}
function SaveLog(ev){
		var buttonsection=$(ev);
		buttonsection.attr('disabled','disabled');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="addhourform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('submithourFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize()+'&cid='+c_id,
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#add_hour_modal').modal('hide');
					bootbox.alert({
						title:'Add Manual Hour',
						message: '<?php D(__('add_hour_success_message','Work added successfull'));?>',
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
function SendInvoice(ev){
		var buttonsection=$(ev);
		buttonsection.attr('disabled','disabled');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="sendinvoiceform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('CreateInvoicehourFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize()+'&cid='+c_id,
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#action_invoice_modal').modal('hide');
					bootbox.alert({
						title:'Send Invoice',
						message: '<?php D(__('add_invoice_success_message','Invoice send successfull'));?>',
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
	$('#pending_invoice_all').on('change',function(){
		if($(this).is(':checked')){
			$('.pending_invoice').prop('checked',true);
		}else{
			$('.pending_invoice').removeAttr('checked');
		}
	})
	$('.add_manual_hour').click(function(){
		$('#add_hour_modal').modal('show');
	});
	$('.datepicker_from').datetimepicker({
		format: 'YYYY-MM-DD',
		maxDate: "<?php echo date('Y-m-d');?>",
		
	});
	$('.datepicker_to').datetimepicker({
		format: 'YYYY-MM-DD',
		maxDate: "<?php echo date('Y-m-d');?>",
		
	});
	$(".datepicker_from").on("dp.change", function (e) {
        $('.datepicker_to').data("DateTimePicker").minDate(e.date);
    });
    $(".datepicker_to").on("dp.change", function (e) {
        $('.datepicker_from').data("DateTimePicker").maxDate(e.date);
    });
    $('.creatinvoice').click(function(){
    	$('.log_details_entry').empty();
    	$('.pending_invoice:checked').each(function(i){
    		var content=$(this).data('content');
    		var count=i+1;
    		$('.log_details_entry').append('<p>'+count+'. '+content+'</p>');
			console.log(content);
    	});
    	if($('.pending_invoice:checked').length>0){
			$('#action_invoice_modal').modal('show');
		}else{
			bootbox.alert({
				title:'Send Invoice',
				message: '<?php D(__('add_invoice_error_message','Please select some work log'));?>',
				buttons: {
				'ok': {
					label: 'Ok',
					className: 'btn-primary float-end'
					}
				},
				callback: function () {
					
			    }
			});
		}
    })
}
</script>
<?php }?>