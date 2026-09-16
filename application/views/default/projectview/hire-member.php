<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//get_print($proposaldetails,FALSE);
//get_print($getBidDetails,FALSE);
$is_hourly=$projects['project_settings']->is_hourly;
$ProjectDetailsURL=URL::get_link('myProjectDetailsBidsClientURL').'/'.$projects['project']->project_url;
$ProjectApplicationURL=URL::get_link('myProjectDetailsBidsClientURL')."/".$projects['project']->project_id;
$currency=priceSymbol();
?>

<div id="edit-profile-page">
  <?php
$logo=getMemberLogo($bid);
?>
  
  <!-- Titlebar
================================================== -->
  <div class="single-page-header freelancer-header" data-background-image="<?php // echo IMAGE;?>">
    <div class="container">
      <div class="single-page-header-inner">
        <div class="start-side"><!--<?php // echo IMAGE;?>default-member-logo.svg-->
          <div class="header-image freelancer-avatar" id="" style="position: relative">
            <ec id="crop-avatar-dashboard" style="width: 100%"><img src="<?php D($logo);?>" alt=""></ec>
          </div>
          <div class="header-details">
            <h1><?php echo __('projectview_hire','Hire');?> </h1>
            <h3>
              <?php D(ucwords($memberInfo->member_name))?>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  
<section class="section">
  <div class="container">
    <form action="" method="post" accept-charset="utf-8" id="hireprojectform" class="form-horizontal" role="form" name="hireprojectform" onsubmit="return false;">
      <div class="row"> 
        <!-- Content -->
        <div class="col-lg-8"> 
          
          <!-- Page Content -->
          <div class="panel mb-4">
            <div class="panel-header">
              <h4 class="panel-title show_edit_btn"><?php echo __('projectview_job_listing','Job Listing');?> </h4>
            </div>
            <div class="panel-body">
              <p><strong><?php echo __('projectview_job_project_title','Project Title:');?></strong> <a href="<?php echo get_link('myProjectDetailsURL')."/".$projects['project']->project_url;?>" target="_blank">
                <?php D(ucfirst($projects['project']->project_title));?> <i class="icon-feather-external-link"></i></a></p>
              <div class="form-group">
                <label class="form-label"><?php echo __('projectview_job_contract_title','Contract Title');?></label>
                <input type="text" name="title" id="title" class="form-control input-text with-border" value="<?php D(ucfirst($projects['project']->project_title));?>" >
              </div>
            </div>
          </div>
          <div class="panel mb-4">
            <div class="panel-header">
              <h4 class="panel-title"><?php echo __('projectview_job_terms','Terms');?> </h4>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <label class="form-label"><?php echo __('projectview_job_payment_term','Payment Term');?></label>                                
                <div class="radio radio-inline">
                  <input id="payment_fixed" name="is_hourly" class="is_hourly" value="0" type="radio" <?php if($is_hourly==1){}else{echo 'checked';}?> >
                  <label for="payment_fixed"><span class="radio-label"></span><?php echo __('projectview_job_fixed','Fixed');?> </label>
                </div>
                <div class="radio radio-inline">
                  <input id="payment_hourly" name="is_hourly" class="is_hourly" value="1" type="radio" <?php if($is_hourly==1){echo 'checked';}?>>
                  <label for="payment_hourly"><span class="radio-label"></span><?php echo __('projectview_job_hourly','Hourly');?> </label>
                </div>
              </div>
              <div class="is_hourly_wrapper" <?php if($is_hourly==1){}else{echo 'style="display: none"';}?> >
              
                <div class="row">
                  <div class="col-sm-6 col-12">
                  <label class="form-label"><?php echo __('projectview_apply_hourly_rate','Hourly Rate');?></label>
                  <div class="input-with-icon-start">
                  <i><?php echo priceSymbol();?></i>
                  <input type="text" name="bid_amount_hourly" id="bid_amount_hourly" class="form-control" value="<?php if($getBidDetails && $is_hourly==1){echo $getBidDetails->bid_amount;}elseif($memberInfo->member_hourly_rate){echo $memberInfo->member_hourly_rate;}else{echo '0.00';}?>" onkeyup="updatePaymentHourly(this)">
                  </div>
                  </div>
                </div>
                
                <h6 class="text-info"><?php D(ucwords($memberInfo->member_name))?><?php echo __('projectview_job_profile_picture',"'s profile rate is");?>  <?php echo $currency.$memberInfo->member_hourly_rate;?> /hr</h6>
                 
                <div class="form-group mb-0">
				<div class="row">
                  <div class="col-sm-6 col-12">
                    <label class="form-label"><?php echo __('projectview_job_weely_limit','Weekly Limit');?></label>
                    <div class="input-group">
                      <input type="text" name="max_hour_limit" id="max_hour_limit" class="form-control" value="10" onkeyup="updatePaymentHourly(this)">
                      <span class="input-group-text max_week_payment"></span>
                    </div>
                  </div>
                  </div>
                </div>
                
                <div class="checkbox d-none">
                    <input type="checkbox" name="allow_manual_hour" id="chekcbox1" value="1" checked>
                    <label for="chekcbox1"><span class="checkbox-icon"></span><?php echo __('projectview_job_alloe_long_time','Allow freelancer to log time manually if needed');?> </label>
                </div>
                
              </div>
              <div class="is_fixed_wrapper" <?php if($is_hourly==1){echo 'style="display: none"';}else{}?>>
                <div class="row">
                  <div class="col-sm-6 col-12">
                  <label class="form-label"><?php echo __('projectview_job_total_amount','Total Amount');?></label>
                  <div class="input-with-icon-start">
                  <i><?php echo priceSymbol();?></i>
                  <input type="text" name="bid_amount" id="bid_amount" class="form-control" value="0" onkeyup="updateFullPayment(this)">
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label"><?php echo __('projectview_job_deposit_escrow','Deposit into Escrow');?></label>
                  <div class="radio">
                    <input id="payment_at_project" name="bid_by_project" class="project_payment_type" value="1" type="radio" checked>
                    <label for="payment_at_project"><span class="radio-label"></span>
                      <ec class="full_amount"><?php echo __('projectview_job_deposite_project','Deposit for the whole project');?></ec>
                    </label>
                  </div>
                  <br>
                  <div class="radio">
                    <input id="payment_at_milestone" name="bid_by_project" class="project_payment_type" value="0" type="radio" >
                    <label for="payment_at_milestone"><span class="radio-label"></span><?php echo __('projectview_job_D_lesser_amount','Deposit a lesser amount to cover the first milestone');?> </label>
                  </div>
                </div>
                <div class="payment_milestone_wrapper"  style="display: none">
                  <h4><?php echo __('projectview_apply_how_milestone','How many milestones do you want to include?');?></h4>
                  <div id="milestone_wrapper">
                    <div class="row row-10 milestone_row_parent">
                      <div class="col-sm-6 col-xs-12">
                        <input type="hidden" name="milestone_id[]" class="milestone_row" value="1"/>
                        <div class="form-group">
                        <label class="form-label"><?php echo __('projectview_apply_description','Description');?></label>
                          <input type="text" name="milestone_title_1" id="milestone_title_1" class="form-control">
                        </div>
                      </div>
                      <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                        <label class="form-label"><?php echo __('projectview_apply_due_date','Due date');?></label>
                          <input type="text" name="milestone_due_date_1" id="milestone_due_date_1" class="datepicker form-control">
                        </div>
                      </div>
                      <div class="col-sm-3 col-xs-12">
                        <div class="form-group">
                        <label class="form-label"><?php echo __('projectview_apply_amount','Amount');?></label>
                          <input type="text" name="milestone_amount_1" id="milestone_amount_1" class="milestone_amount form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                  <a href="javascript:" class="btn btn-primary btn-sm addMilestone"><i class="icon-feather-plus"></i><?php echo __('projectview_apply_add_milestone','Add milestone');?> </a> </div>
                <div class="payment_project_wrapper">
                  <h4><?php echo __('projectview_apply_full_ammount',"What is the full amount you'd like to bid for this job?");?></h4>
                  <div class="form-group mb-0">
                  <label class="form-label"><?php echo __('projectview_apply_due_date','Due date');?></label>
                      <input type="text" name="milestone_due_date" id="milestone_due_date" class="form-control datepicker" value="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="panel mb-3">
            <div class="panel-header">
              <h4 class="panel-title"><?php echo __('projectview_job_work_description','Work Description');?></h4>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <label class="form-label"><?php echo __('projectview_apply_description','Description')?></label>
                <textarea rows="4" class="form-control" id="bid_details" name="bid_details"></textarea>
              </div>
              <div class="form-group">
                <label class="form-label"><?php echo __('projectview_proposal_attachment','Attachments');?></label>                
                <input type="file" name="fileinput" id="fileinput" multiple="true">
                <div class="upload-area" id="uploadfile">
                
                  <p><?php echo __('projectview_job_drag_drop','Drag and Drop file here');?><br/>
                  <?php echo __('projectview_apply_or','Or');?> <br/>
                    <span class="text-site"><?php echo __('projectview_apply_click','Click');?></span> <?php echo __('projectview_apply_to_select','to select file');?></p>
                </div>
                <div id="uploadfile_container"> </div>
              </div>
              
                <div class="checkbox">
                  <input type="checkbox" id="i_agree" name="i_agree" value="1">
                  <label for="i_agree"><span class="checkbox-icon"></span> <small><?php echo __('projectview_job_yes_understand','Yes, I understand and agree to the');?> <a href="<?php D(get_link('CMStermsandconditions'))?>" target="_blank"><?php echo __('projectview_job_term_service','Terms of Service');?></a>, <?php echo __('projectview_job_including_the','including the');?> <a href="<?php D(get_link('CMSuseragreement'))?>" target="_blank"><?php echo __('projectview_job_user_agreement','User Agreement');?></a> <?php echo __('projectview_job_and','and');?> <a href="<?php D(get_link('CMSprivacypolicy'))?>" target="_blank"><?php echo __('projectview_job_privacy_policy','Privacy Policy');?></a></small></label>
                </div>
                <div id="i_agreeError" class="rerror"></div>
              
            </div>
          </div>
          
          <div>
            <button class="btn btn-primary nextbtnapply"><?php echo __('projectview_hire','Hire');?>
            <?php D(ucwords($memberInfo->member_name))?>
            </button>
            &nbsp;
            <button class="btn btn-secondary backbtnapply"><?php echo __('projectview_apply_back','Back');?></button>
          </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
          <div class="sidebar-container">
            <div class="panel mb-4">
              <div class="panel-body">
                <label class="form-label"><?php echo __('projectview_job_title','Title');?></label>
                <p><?php echo __('projectview_job_leverage','Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.');?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
<script type="text/javascript">
	var pid='<?php echo $pid;?>';
	var bid='<?php echo $bid;?>';
	var is_hourly='<?php if($is_hourly==1){echo 1;}else{echo 0;}?>';
	var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
	function updateFullPayment(ev){
		var amount=parseFloat($(ev).val());
		if(isNaN(amount)){
			var amounthtml="";
		}else{
			var amounthtml="<?php echo $currency;?>"+amount+' ';
		}
		$('.full_amount').html('Deposit '+amounthtml+'for the whole project');
	}
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
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> '+name+'<a href="<?php D(VZ);?>" class="text-danger ico" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
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
	function removeRow(ev){
		$(ev).closest('.milestone_row_parent').remove();
		updateTotal();
	}
	function updatePaymentHourly(){
		var amount=parseFloat($('.is_hourly_wrapper #bid_amount_hourly').val());
		if(isNaN(amount)){
			var amount=0;
		}
		var maxhour=parseFloat($('#max_hour_limit').val());
		if(isNaN(maxhour)){
			var maxhour=0;
		}
		var max_week_payment=amount*maxhour;
		if(max_week_payment>0){
			$('.max_week_payment').html('= <?php echo $currency;?>'+max_week_payment+' max/week');
		}else{
			$('.max_week_payment').html('');
		}
	}
	function updateTotal(type_from){
		
	}
	
	var  main = function(){
		$('.datepicker').datetimepicker({
			format: 'YYYY-MM-DD',
			minDate: "<?php echo date('Y-m-d');?>"
		});
		$('.addMilestone').on('click',function(){
			var html='';
			var cnt=$(".milestone_row").last().val();
			var new_row=parseInt(cnt)+1;
			html+='<div class="row row-10 milestone_row_parent">';
			html+='<div class="col-sm-6 col-12">';
			html+='<input type="hidden" name="milestone_id[]" class="milestone_row" value="'+new_row+'"/>';
			html+='<div class="form-group">';
			html+='<label class="form-label">Description</label>';
			html+='<input type="text" name="milestone_title_'+new_row+'" id="milestone_title_'+new_row+'" class="form-control">';
			html+='</div>';
			html+='</div>';
			html+='<div class="col-sm-3 col-12">';
			html+='<div class="form-group">';
			html+='<label class="form-label">Due date</label>';
			html+='<input type="text" name="milestone_due_date_'+new_row+'" id="milestone_due_date_'+new_row+'" class="datepicker form-control">';
			html+='</div>';
			html+='</div>';
			html+='<div class="col-sm-3 col-12">';
			html+='<div class="form-group">';
			html+='<label class="form-label">Amount</label>';
			html+='<div class="input-group">';
			html+='<input type="text" name="milestone_amount_'+new_row+'" id="milestone_amount_'+new_row+'" class="milestone_amount form-control">';
			html+='<button class="text-danger ms-3" onclick="removeRow(this)"><i class="icon-feather-x" style="font-size:20px"></i></button>';
			html+='</div>';
			html+='</div>';
			html+='</div>';
			html+='</div>';
			$('#milestone_wrapper').append(html);
			$('.datepicker').datetimepicker({
				format: 'YYYY-MM-DD',
				minDate: "<?php echo date('Y-m-d');?>",
			});
			
		});
		$('.is_hourly').on('change',function(){
			var project_payment_type=$('.is_hourly:checked').val();
			if(project_payment_type==1){
				$('.is_fixed_wrapper').hide();
				$('.is_hourly_wrapper').show();
				updatePaymentHourly();
			}else{
				$('.is_hourly_wrapper').hide();
				$('.is_fixed_wrapper').show();
			}
			//updateTotal();
		});
		$('.project_payment_type').on('change',function(){
			var project_payment_type=$('.project_payment_type:checked').val();
			if(project_payment_type==1){
				$('.payment_milestone_wrapper').hide();
				$('.payment_project_wrapper').show();
			}else{
				$('.payment_project_wrapper').hide();
				$('.payment_milestone_wrapper').show();
			}
			//updateTotal();
		});
		$('.backbtnapply').on('click',function(){
			window.location.href="<?php echo $ProjectApplicationURL;?>";
		});
		$('.nextbtnapply').on('click',function(){
		var buttonsection=$(this);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="hireprojectform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('hireprojectFormCheckAJAXURL'))?>/",
	        data:$('#'+formID).serialize()+'&pid='+pid+"&bid="+bid,
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					bootbox.alert({
						title: '<?php echo __('projectview_job_hire_member','Hire member');?>',
						message: '<?php echo __('projectview_job_offer_successful_sent','Offer successfully sent');?>',
						size: 'small',
						buttons: {
							ok: {
								label: "<?php echo __('projectview_job_ok','Ok');?>",
								className: 'btn-primary float-end'
							},
						},
						callback: function(result){
							window.location.href="<?php echo $ProjectApplicationURL;?>";
						}
					});
					
					
					
				} else if (msg['status'] == 'FAIL') {
					if(msg['popup']){
						if(msg['popup']=='fund'){
							bootbox.alert({
								title: 'Insufficient funds',
								message: 'Please add fund to your wallet to do the hire',
								size: 'small',
								buttons: {
									ok: {
										label: "Ok",
										className: 'btn-primary float-end'
									},
								},
								callback: function(result){
									window.open('<?php echo get_link('AddFundURL');?>','_blank');
								}
							});
						}
					}
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})		
	});
	}
	var mainload=function(){
		updateTotal();
	}
</script>