<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
$ProjectDetailsURL=get_link('myProjectDetailsURL')."/".$contractMilestoneDetails->project_url;
//get_print($contractMilestoneDetails,FALSE);
$profile_url='';
if($is_owner){
	$logo=getMemberLogo($contractMilestoneDetails->contractor->member_id);
	$name=$contractMilestoneDetails->contractor->member_name;
	$profile_url="href='".get_link('viewprofileURL').'/'.md5($contractMilestoneDetails->contractor->member_id)."' target='_blank'";
}else{
	$logo=getCompanyLogo($contractMilestoneDetails->owner->organization_id);
	if($contractMilestoneDetails->owner->organization_name){
		$name=$contractMilestoneDetails->owner->organization_name;
	}else{
		$name=$contractMilestoneDetails->owner->member_name;
	}
}
$contract_details_url=get_link('ContractDetails').'/'.md5($contractMilestoneDetails->contract_id);
?>

<section class="section">
<div class="container">
      <a href="<?php echo $contract_details_url;?>" class="mb-1 btn btn-link p-0"><i class="icon-feather-chevron-left"></i><?php echo __('contract_end_b_contract','Back to Contract');?> </a>
      
        <h1 class="display-4"><?php echo $contractMilestoneDetails->contract_title;?></h1>
        <div class="row">
          <div class="col-lg-9">
            <div class="panel mb-4">
              <div class="panel-header">
                <h4><?php echo __('contract_end_details','Details');?></h4>
              </div>
              <div class="panel-body">
                <p class="mb-2"><b><?php echo __('contract_milestone_title','Title:');?></b> <?php echo $contractMilestoneDetails->milestone_title;?></p>
                <p class="mb-2"><b><?php echo __('contract_dispute_deu_date','Due date:');?></b> <i class="icon-feather-calendar text-muted"></i> <?php echo $contractMilestoneDetails->milestone_due_date;?></p>
                <?php if($contractMilestoneDetails->is_approved==1){?>
                <p><b><?php echo __('contract_milestone_app_date','Approved Date:');?></b> <i class="icon-feather-calendar text-muted"></i> <?php echo $contractMilestoneDetails->approved_date;?></p>
                <?php }else{
                	if(!$contractMilestoneDetails->is_escrow){
				?>
				<span class="badge badge-warning"><?php echo __('contract_details_mile_not_start','Milestone not started.');?></span>
				<?php		
					}
                	
                }?>
                
              </div>
            </div>
            <div class="panel mb-4">
              <div class="panel-header d-flex">
                <h4 class="mt-2"><?php echo __('contract_milestone_submission','Submission');?></h4>
                 <?php if(!$is_owner && $contractMilestoneDetails->is_approved!=1){
                 	if($contractMilestoneDetails->is_escrow){
                 	?>
                <a href="javascript:void(0)" class="btn btn-primary  float-end submit_work ms-auto">
                <icon class="icon-material-outline-add"></icon>
                <?php echo __('contract_milestone_sub_Work','Submit Work');?></a> 
                <?php }}?>
                </div>
              <div class="panel-body">
                <section class="comments">
                  <ul>
                    <?php if($contractMilestoneDetails->submission){
                	foreach($contractMilestoneDetails->submission as $k=>$submission){
                ?>
                    <li>
                      <div class="comment-content p-0">
                        <div class="arrow-comment"></div>
                        <div class="comment-by">
                        <h4 class="mb-1"><i class="icon-feather-arrow-right text-success"></i> <b><?php echo __('contract_milestone_wo_submitted','Work Submitted');?></b></h4>
                        <span class="date"><i class="icon-feather-calendar"></i> <?php echo $submission->submission_date;?></span>
                          <?php if($submission->is_approved==1){?>
                          <span class="status badge badge-success"><?php echo __('contract_list_approved','Approved');?></span>
                          <?php } elseif($submission->is_approved==2){?>
                          <span class="status badge badge-danger"><?php echo __('contract_list_rejected','Rejected');?></span>
                          <?php }else{
	if($is_owner){ 
	?>
                          <div class="status"><button class="btn btn-success acceptbtn" data-sid="<?php echo $submission->submission_id;?>"><?php echo __('contract_dispute_accept','Accept');?></button>&nbsp;
                          <button class="btn btn-danger denybtn" data-sid="<?php echo $submission->submission_id;?>"><?php echo __('contract_milestone_reject','Reject');?></button></div>
                          <?php
	}else{
	?>
                          <span class="status badge badge-warning"><?php echo __('contract_details_pending','Pending');?></span>
                          <?php	
	}
								   }
	 ?>
                        </div>
                        <p><?php echo nl2br($submission->submission_description);?></p>
                      </div>
                      <?php if($submission->submission_attachment){?>
                      <div class="attachments-container">
                        <?php
					$attachments=json_decode($submission->submission_attachment);
					foreach($attachments as $k=>$val){
						if($val->file && file_exists(UPLOAD_PATH.'projects-files/milestone-submission/'.$val->file)){
							$path_parts = pathinfo($val->name);
					?>
                        <a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/milestone-submission/'.$val->file;?>" target="_blank" class="attachment-box "><span><?php echo $path_parts['filename'];?></span><i><?php echo strtoupper($path_parts['extension']);?></i></a>
                        <?php
						}	
					}
					?>
                      </div>
                      <?php }?>
                      <?php if($submission->is_approved==1){?>
                      <ul>
                        <li>
                          <div class="comment-content p-0">
                            <div class="arrow-comment"></div>
                            <div class="comment-by"><b class="text-success"><?php echo __('contract_milestone_got_apporoved','Submission got approved On:');?></b> <span class="date"><i class="icon-feather-calendar"></i> <?php echo $submission->update_date;?></span> </div>
                          </div>
                        </li>
                      </ul>
                      <?php } elseif($submission->is_approved==2){?>
                      <ul>
                        <li>
                          <div class="comment-content p-0">
                            <div class="arrow-comment"></div>
                            <div class="comment-by"><b class="text-danger"><?php echo __('contract_milestone_got_rejected','Submission got rejected');?></b> <span class="date"><i class="icon-feather-calendar"></i> <?php echo $submission->update_date;?></span> </div>
                            <p><b><?php echo __('contract_milestone_reason','Reason:');?></b> <?php echo nl2br($submission->change_reason);?></p>
                          </div>
                        </li>
                      </ul>
                      <?php }?>
                    </li>
                    
                    <?php /*?>   
                <?php if($submission->is_approved==1){?>
                <p class="alert alert-success">Submission got approved On:<?php echo $submission->update_date;?></p>
                <?php }
                elseif($submission->is_approved==2){?>
                 <p class="alert alert-danger">Submission got rejected</p>
                 <span><i class="icon-feather-calendar"></i> <?php echo $submission->update_date;?></span>
                 <p><b>Reason:</b> <?php echo nl2br($submission->change_reason);?></p>

                <?php }
                else{
                	if($is_owner){?>
                	<p>Work submitted 
					<button class="btn btn-success acceptbtn" data-sid="<?php echo $submission->submission_id;?>">Accept</button>
					<button class="btn btn-danger denybtn" data-sid="<?php echo $submission->submission_id;?>">Reject</button></p>
					<?php
					}
					else{?>
					<p>Waiting for approval.</p>
					<?php	
					}
                	
                }?>
                
                <span><i class="icon-feather-calendar"></i> <?php echo $submission->submission_date;?></span>
				<p><?php echo nl2br($submission->submission_description);?></p>
               
                <?php if($submission->submission_attachment){?>
                <div class="attachments-container">
	              <?php
					$attachments=json_decode($submission->submission_attachment);
					foreach($attachments as $k=>$val){
						if($val->file && file_exists(UPLOAD_PATH.'projects-files/milestone-submission/'.$val->file)){
							$path_parts = pathinfo($val->name);
					?>
	              <a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/milestone-submission/'.$val->file;?>" target="_blank" class="attachment-box "><span><?php echo $path_parts['filename'];?></span><i><?php echo strtoupper($path_parts['extension']);?></i></a>
	              <?php
						}	
					}
					?>
	            </div>
	            <?php }?>
	            	
	            <hr><?php */?>
                <?php 
	            	}
	            }else{?>
                    <li class="text-center text-danger"><?php echo __('contract_list_no_recoard','No record found');?></li>
                    <?php }?>
                  </ul>
                </section>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="card text-center mx-auto">
              <div class="card-body">
			  	<a <?php echo $profile_url;?>>
                <span class="avatar-logo mb-3"><img src="<?php echo $logo;?>" alt="<?php echo $name;?>" class="rounded-circle" height="96" width="96"></span>
                <h4 class="card-title mb-0"><?php echo $name;?></h4>
				</a>
				<?php if($is_owner){?>
            	<p class="text-muted mb-0"><?php D($contractMilestoneDetails->contractor->member_heading);?></p>
            	<div class="star-rating d-block mb-2" data-rating="<?php echo round($contractMilestoneDetails->contractor->avg_rating,1);?>"></div> 
				<?php }else{ ?>
					<div class="star-rating d-block mb-2" data-rating="<?php echo round($contractMilestoneDetails->owner->statistics->avg_rating,1);?>"></div>
				<?php }?>
               
              </div>
            </div>
          </div>
        </div>
      </div>
</section>

<div id="submit_work_modal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('contract_end_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('contract_milestone_sub_Work','Submit Work');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveWork(this)"><?php echo __('contract_dispute_send','Send');?></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="workform" class="form-horizontal" role="form" name="workform" onsubmit="return false;">
              <div class="form-group">
                <label><b><?php echo __('contract_dispute_description','Description');?></b></label>
                <textarea class="form-control" id="details" name="details"></textarea>
              </div>
              <div class="form-group">
                <label><b><?php echo __('contract_message_attachment','Attachments');?></b></label>
                <input type="file" name="fileinput" id="fileinput" multiple="true">
                <div class="upload-area" id="uploadfile">
                  <p class="mb-0"><?php echo __('contract_dispute_drag_drop','Drag and Drop file here');?><br/>
                    <?php echo __('contract_dispute_or','Or');?><br/>
                    <span class="text-site"><?php echo __('click','click');?></span> <?php echo __('to_select_file','to select file');?></p>
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
<div id="action_work_modal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('contract_end_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('contract_milestone_reject_Work','Reject Work');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="ActionWork(this)"><?php echo __('contract_dispute_send','Send');?></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <form action="" method="post" accept-charset="utf-8" id="workactionform" class="form-horizontal" role="form" name="workactionform" onsubmit="return false;">
              <input type="hidden" name="sid" id="sid" value="0"/>
              <div class="form-group">
                <label><b><?php echo __('contract_milestone_reason','Reason');?></b></label>
                <textarea class="form-control" id="reason" name="reason"></textarea>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Dashboard Container / End --> 
<script>
	var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
	var m_id="<?php echo md5($contractMilestoneDetails->contract_milestone_id)?>";
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
	function SaveWork(ev){
		var buttonsection=$(ev);
		buttonsection.attr('disabled','disabled');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="workform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('submitworkFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize()+'&mid='+m_id,
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
	function ActionWork(ev){
		var buttonsection=$(ev);
		buttonsection.attr('disabled','disabled');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="workactionform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('workActionAjaxURL'))?>",
	        data:$('#'+formID).serialize()+'&mid='+m_id+"&action_type=deny",
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
		$('.submit_work').click(function(){
			$('#submit_work_modal').modal('show');
		});
		$('.acceptbtn').click(function(){
			var buttonsection=$(this);
			var sid=$(this).data('sid');
			bootbox.confirm({
				title:'Work Accept Confimation',
				message: 'By accept, the work will be mark as completed and payment will be release.',
				buttons: {
				'confirm': {
					label: 'Confirm',
					className: 'btn-primary float-end'
					},
				'cancel': {
					label: 'Cancel',
					className: 'btn-dark float-start'
					}
				},
				callback: function (result) {
					if(result){
			
						buttonsection.attr('disabled','disabled');
			var buttonval = buttonsection.html();
			buttonsection.html(SPINNER).attr('disabled','disabled');
			$.post( "<?php echo get_link('workActionAjaxURL');?>",{'mid':m_id,'action_type':'accept','sid':sid}, function( msg ) {
				if (msg['status'] == 'OK') {
					bootbox.alert({
						title:'Work Accepted',
						message: '<?php D(__('work_success_message','Work accepted succesfully'));?>',
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
					}else{
						bootbox.alert({
							title:'Work Accepted',
							message: '<?php D(__('work_error_message',"Opps! . Please try again."));?>',
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
					
					}
				}
				
				buttonsection.html(buttonval).removeAttr('disabled');
			},'JSON');
			
			}
				}
			});
	
		});
		$('.denybtn').click(function(){
			var sid=$(this).data('sid');
			$('#sid').val(sid);
			$('#action_work_modal').modal('show');
		});
	}
</script>