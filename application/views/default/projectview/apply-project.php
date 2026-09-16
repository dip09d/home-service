<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//get_print($projectData,FALSE);

$ProjectDetailsURL=get_link('myProjectDetailsURL')."/".$projectData['project']->project_url;
$ProjectApplicationURL=get_link('myProjectDetailsBidsClientURL')."/".$projectData['project']->project_id;
$ApplyProjecURL=get_link('ApplyProjectURL')."/".$projectData['project']->project_url;
$currency=priceSymbol();
?>
<!-- Titlebar -->

<div class="single-page-header">
  <div class="container">
    <div class="single-page-header-inner">
      <div class="start-side">
        <div class="header-details">
          <h1>
            <?php D(ucfirst($projectData['project']->project_title));?>
          </h1>
          <p>
            <?php D($projectData['project_category']->category_subchild_name);?>
            ,
            <?php D($projectData['project_category']->category_name);?>
          </p>
        </div>
      </div>
      <?php if($projectData['project_settings']->is_fixed==1){?>
      <div class="end-side">
        <div class="salary-box">
          <div class="salary-type"><?php echo __('projectview_apply_fixed_budget','Fixed Budget');?></div>
          <div class="salary-amount">
            <?php D($currency.priceFormat($projectData['project_settings']->budget));?>
          </div>
        </div>
      </div>
      <?php }?>
    </div>
  </div>
</div>

<section class="section">
<div class="container">
  <?php if($limit_over){?>
  <div class="alert alert-warning"><?php echo __('projectview_apply_max_limit','Max limit over, please upgrade your membership plan.');?> <a href="<?php echo get_link('membershipURL');?>"><?php echo __('projectview_apply_click_here','Click here');?></a><?php echo __('projectview_apply_to_upgrade','to upgrade');?> </div>
  <?php }else{?>
  <form action="" method="post" accept-charset="utf-8" id="applyprojectform" class="form-horizontal" role="form" name="applyprojectform" onsubmit="return false;">
    <?php /*?><input type="hidden" name="pid" value="<?php echo $projectData['project']->project_id;?>"/>
			<input type="hidden" id="is_hourly" value="<?php if($projectData['project_settings']->is_hourly==1){echo 1;}else{echo 0;}?>"/>-->
			<?php */?>
    <?php if($projectData['project_settings']->is_hourly==1){?>
    <div class="panel mb-4">
      <div class="panel-header">
        <h3><?php echo __('projectview_apply_header','Lorem ipsum dolar');?> </h3>
      </div>
      <div class="panel-body">
        <div class="payment_project_wrapper">
          <h4><?php echo __('projectview_apply_what_rate',"What is the rate you'd like to bid for this job?");?></h4>
          <div class="padding-top-20" style="max-width:720px">
            <div class="row">
              <div class="col-sm-8">
                <h4><?php echo __('projectview_apply_hourly_rate','Hourly Rate');?></h4>
                <span><?php echo __('projectview_apply_client_amount','Total amount the client will see on your proposal');?></span></div>
              <div class="col-sm-4">
                <div class="input-with-icon both"> <i><?php echo $currency;?></i>
                  <input type="text" name="bid_amount" id="bid_amount" class="form-control text-right notick" value="<?php if($getBidDetails){echo $getBidDetails->bid_amount;}else{echo '0.00';}?>" onkeyup="updateTotal('bid_amount')">
                  <span>/hr</span> </div>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-8">
                <h4 class="mb-0"><?php echo $bid_site_fee;?><?php echo __('projectview_apply_service_fee','% Service Fee');?></h4>
              </div>
              <div class="col-sm-4 text-sm-right"><span class="f20" style="line-height: 1.2;"><?php echo $currency;?> <span class="total_fee">0.00</span></span></div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-8">
                <h4><?php echo __('projectview_apply_you_receive',"You'll Receive");?></h4>
                <span><?php echo __('projectview_apply_after_fees',"The estimated amount you'll receive after service fees");?></span></div>
              <div class="col-sm-4">
                <div class="input-with-icon both"> <i><?php echo $currency;?></i>
                  <input type="text" name="bid_amount_receive" id="bid_amount_receive" class="form-control text-right notick" value="0.00" onkeyup="updateTotal('bid_amount_receive')">
                  <span>/hr</span> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php }else{?>
    <div class="panel mb-4">
      <div class="panel-header">
        <h3><?php echo __('projectview_apply_header','Lorem ipsum dolar');?> </h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <h4><?php echo __('projectview_proposal_how_paid','How do you want to be paid?');?></h4>
          <div class="radio">
            <input id="payment_at_milestone" name="bid_by_project" class="project_payment_type" value="0" type="radio" <?php if($getBidDetails && $getBidDetails->bid_by_project==1){}else{echo 'checked';}?> >
            <label for="payment_at_milestone"><span class="radio-label"></span> <b><?php echo __('projectview_apply_my_milestone','By milestone');?></b><br>
              <span><?php echo __('projectview_apply_devide_project',"Divide the project into smaller segments, called milestones. You'll be paid for milestones as they are completed and approved.");?></span></label>
          </div>
          <br>
          <div class="radio">
            <input id="payment_at_project" name="bid_by_project" class="project_payment_type" value="1" type="radio" <?php if($getBidDetails && $getBidDetails->bid_by_project==1){echo 'checked';}?>>
            <label for="payment_at_project"><span class="radio-label"></span> <b><?php echo __('projectview_apply_by_project','By project');?></b><br>
              <span><?php echo __('projectview_apply_get_payment','Get your entire payment at the end, when all work has been delivered.');?><small></small></span></label>
          </div>
        </div>
        <div class="payment_milestone_wrapper" <?php if($getBidDetails && $getBidDetails->bid_by_project==1){?> style="display: none"<?php }?>>
          <h4><?php echo __('projectview_apply_how_milestone','How many milestones do you want to include?');?></h4>
          <div id="milestone_wrapper">
            <?php if($getBidDetails && $getBidDetails->bid_by_project==0){
				foreach($getBidDetails->milestone as $k=>$milestone){
				$pmid=$k+1;
			?>
            <div class="row milestone_row_parent">
              <div class="col-md-6 col-12">
                <input type="hidden" name="milestone_id[]" class="milestone_row" value="<?php echo $pmid;?>"/>
                <div class="form-group">
                  <label class="form-label" for="description"><?php echo __('projectview_apply_description','Description');?></label>
                  <input type="text" name="milestone_title_<?php echo $pmid;?>" id="milestone_title_<?php echo $pmid;?>" class="form-control" value="<?php echo $milestone->bid_milestone_title;?>">
                </div>
              </div>
              <div class="col-md-3 col-12">
                <div class="form-group position-relative">
                  <label class="form-label" for="due date"><?php echo __('projectview_apply_due_date','Due date');?></label>
                  <input type="text" name="milestone_due_date_<?php echo $pmid;?>" id="milestone_due_date_<?php echo $pmid;?>" class="form-control datepicker" value="<?php echo $milestone->bid_milestone_due_date;?>">
                </div>
              </div>
              <div class="col-md-3 col-12">
                <div class="form-group">
                  <label class="form-label" for="amount"><?php echo __('projectview_apply_amount','Amount');?></label>
                  <?php if($k>0){?>
                  <div class="input-group">
                    <?php }?>
                    <input type="text" name="milestone_amount_<?php echo $pmid;?>" id="milestone_amount_<?php echo $pmid;?>" class="milestone_amount form-control" value="<?php echo $milestone->bid_milestone_amount;?>" onKeyUp="updateTotal()" style="border-radius:0.25rem">
                    <?php if($k>0){?>
                      <button class="btn btn-danger btn-circle mt-1 ms-3" onclick="removeRow(this)" style="border-radius:50%; font-size:1.125rem;"><i class="icon-feather-x"></i></button>                    
                  </div>
                  <?php }?>
                </div>
              </div>
            </div>
            <?php }
			}else{?>
            <div class="row milestone_row_parent">
              <div class="col-sm-6 col-xs-12">
                <input type="hidden" name="milestone_id[]" class="milestone_row" value="1"/>
                <div class="form-group">
                  <label class="form-label" for="title"><b><?php echo __('projectview_apply_description','Description');?></b></label>
                  <input type="text" name="milestone_title_1" id="milestone_title_1" class="form-control">
                </div>
              </div>
              <div class="col-sm-3 col-xs-12">
                <div class="form-group position-relative">
                  <label class="form-label" for="title"><b><?php echo __('projectview_apply_due_date','Due date');?></b></label>
                  <input type="text" name="milestone_due_date_1" id="milestone_due_date_1" class="datepicker form-control">
                </div>
              </div>
              <div class="col-sm-3 col-xs-12">
                <div class="form-group">
                  <label class="form-label" for="title"><b><?php echo __('projectview_apply_amount','Amount');?></b></label>
                  <input type="text" name="milestone_amount_1" id="milestone_amount_1" class="milestone_amount form-control" onKeyUp="updateTotal()">
                </div>
              </div>
            </div>
            <?php }?>
          </div>
          <a href="javascript:" class="btn btn-sm btn-primary addMilestone"><i class="icon-feather-plus"></i><?php echo __('projectview_apply_add','Add');?> </a>
          <div class="panel border bg-light mt-3">
            <div class="panel-body">
            <div class="row">
              <div class="col-sm-8">
                <h4><?php echo __('projectview_proposal_total_price','Total price of project');?></h4>
                <span><?php echo __('projectview_apply_includes_all','This includes all milestones, and is the amount your client will see');?></span></div>
              <div class="col-sm-4 text-sm-right"><span class="f20"><?php echo $currency;?><span class="total_amount">0.00</span></span></div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-8">
                <h4 class="mb-0"><?php echo $bid_site_fee;?><?php echo __('projectview_apply_service_fee','% Service Fee');?></h4>
              </div>
              <div class="col-sm-4 text-sm-right"><span class="f20" style="line-height:1.2;"><?php echo $currency;?><span class="total_fee">0.00</span></span></div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-8">
                <h4 class="mb-0"><?php echo __('projectview_apply_you_receive',"You'll Receive");?></h4>
                <span><?php echo __('projectview_apply_your_estimated','Your estimated payment, after service fees');?></span></div>
              <div class="col-sm-4 text-sm-right"><span class="f20" style="line-height:1.2;"><?php echo $currency;?><span class="total_recive">0.00</span></span></div>
            </div>
            </div>
          </div>
        </div>
        <div class="payment_project_wrapper" <?php if($getBidDetails && $getBidDetails->bid_by_project==1){}else{?> style="display: none"<?php }?>>
          <h4><?php echo __('projectview_apply_full_ammount',"What is the full amount you'd like to bid for this job?");?></h4>
          <div class="padding-top-20" style="max-width:720px">
            <div class="row">
              <div class="col-sm-8">
                <h4><?php echo __('projectview_apply_bid','Bid');?></h4>
                <p><?php echo __('projectview_apply_client_amount','Total amount the client will see on your proposal');?></p>
              </div>
              <div class="col-sm-4">
                <div class="input-with-icon-start"> <i><?php echo $currency;?></i>
                  <input type="text" name="bid_amount" id="bid_amount" class="form-control text-right" value="<?php if($getBidDetails){echo $getBidDetails->bid_amount;}else{echo '0.00';}?>" onkeyup="updateTotal('bid_amount')">
                </div>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-8">
                <h4 class="mb-0"><?php echo $bid_site_fee;?><?php echo __('projectview_apply_service_fee','% Service Fee');?></h4>
              </div>
              <div class="col-sm-4 text-sm-right"><span class="f20" style="line-height:1.2;"><?php echo $currency;?> <span class="total_fee">0.00</span></span></div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-8">
                <h4><?php echo __('projectview_apply_you_receive',"You'll Receive");?></h4>
                <p><?php echo __('projectview_apply_after_fees',"The estimated amount you'll receive after service fees");?></p>
              </div>
              <div class="col-sm-4">
                <div class="input-with-icon-start"> <i><?php echo $currency;?></i>
                  <input type="text" name="bid_amount_receive" id="bid_amount_receive" class="form-control text-right" value="0.00" onkeyup="updateTotal('bid_amount_receive')">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel mb-4">
      <div class="panel-header">
        <h3><?php echo __('projectview_proposal_how_long','How long will this project take?');?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4 col-sm-6">
            <select class="form-control" name="bid_duration" id="bid_duration">
              <option value=""><?php echo __('projectview_apply_please_select','Please Select');?></option>
              <?php if($bidduration){
							foreach($bidduration as $k=>$val){
							?>
              <option value="<?php echo $k;?>" <?php if($getBidDetails && $getBidDetails->bid_duration==$k){echo 'selected';}?>><?php echo $val['name'];?></option>
              <?php	
							}
							}
							?>
            </select>
          </div>
        </div>
      </div>
    </div>
    <?php }?>
    <div class="panel mb-3">
      <div class="panel-header">
        <h3><?php echo __('projectview_apply_additional_details','Additional details');?></h3>
      </div>
      <div class="panel-body">
        <?php
			if($project_question){
			?>
        <label class="form-label"><?php echo __('projectview_apply_question','Question');?></label>
        <?php
				foreach($project_question as $k=>$val){
			?>
        <div class="form-group">
          <label class="form-label"><?php echo $k+1;?>. <?php echo $val->question_title;?></label>
          <input type="hidden" name="qid[]" value="<?php echo $val->question_id;?>"/>
          <input type="text" name="question[<?php echo $val->question_id;?>]" class="form-control" value="<?php echo $val->question_answer;?>">
        </div>
        <?php		
				}
			}
			?>
        <div class="form-group">
          <label class="form-label"><?php echo __('projectview_proposal_cover_letter','Cover Letter');?>
            <?php if($projectData['project_additional'] && $projectData['project_additional']->project_is_cover_required){?>
              <?php echo __('projectview_apply_require_cover','(this client require cover letter)');?>
            <?php }?>
          </label>
          <textarea class="form-control" rows="4" id="bid_details" name="bid_details"><?php if($getBidDetails && $getBidDetails->bid_details){echo $getBidDetails->bid_details;}?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label"><?php echo __('projectview_proposal_attachment','Attachments');?></label>          
          <input type="file" name="fileinput" id="fileinput" multiple="true">
          <div class="upload-area" id="uploadfile">
          
            <p><?php echo __('projectview_apply_drag','Drag');?> &amp; <?php echo __('projectview_apply_drop_file','drop file here');?> <br />
            <?php echo __('projectview_apply_or','or');?><br />
              <span class="text-site"><?php echo __('projectview_apply_click','Click');?></span> <?php echo __('projectview_apply_to_select','to select file');?> </p>
          </div>
          <div id="uploadfile_container">
            <?php if($getBidDetails && $getBidDetails->bid_attachment){
                    $inc=0;
                    $attachment=json_decode($getBidDetails->bid_attachment);
                    foreach($attachment as $files){
                        $inc++;
                        $filejson=array(
                        'file_name'=>$files->file,
                        'original_name'=>$files->name,
                        );
                        ?>
            <div id="thumbnail_<?php D($inc)?>" class="thumbnail_sec">
              <input type="hidden" name="projectfileprevious[]" value='<?php D(json_encode($filejson))?>'>
              <?php D($filejson['original_name']);?>
              <a href="javascript:void(0)" class=" text-danger ico float-end" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a></div>
            <?php
                    }
                    
                   }?>
          </div>
        </div>
      </div>
    </div>
    
      <button class="btn btn-primary nextbtnapply"><?php echo __('projectview_apply_submit_proposal','Submit Proposal');?></button>
      &nbsp;
      <button class="btn btn-secondary backbtnapply"><?php echo __('projectview_apply_back','Back');?></button>
    
  </form>
  <?php }?>
</div>
</section>
<script type="text/javascript">
	var pid='<?php echo $projectData['project']->project_id;?>';
	var is_hourly='<?php if($projectData['project_settings']->is_hourly==1){echo 1;}else{echo 0;}?>';
	var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
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
	function removeRow(ev){
		$(ev).closest('.milestone_row_parent').remove();
		updateTotal();
	}
	function updateTotal(type_from){
		var fee_percent=<?php echo $bid_site_fee;?>;
		var total_project_price=0;
		var total_fee=0;
		var total_receive=0;
		//var is_hourly=$("#is_hourly").val();
		if(is_hourly==1){
			if(type_from=='bid_amount_receive'){
				var amount=parseFloat($('#bid_amount_receive').val());
				if(isNaN(amount)){
					amount=0;
				}
				total_project_price=amount/(1-(fee_percent/100));
				total_fee=(total_project_price*fee_percent)/100;
				var total_project_price_new=parseFloat(amount)+parseFloat(total_fee);
				$('#bid_amount').val(parseFloat(total_project_price_new).toFixed(2));
			}else{
				var amount=parseFloat($('#bid_amount').val());
				if(isNaN(amount)){
					amount=0;
				}
				total_project_price=amount;
				total_fee=(total_project_price*fee_percent)/100;
				total_recive=parseFloat(total_project_price)-parseFloat(total_fee);
				$('#bid_amount_receive').val(parseFloat(total_recive).toFixed(2));
			}
			$('.total_fee').html(parseFloat(total_fee).toFixed(2));
		}else{
			var project_payment_type=$('.project_payment_type:checked').val();
			if(project_payment_type==1){
				if(type_from=='bid_amount_receive'){
					var amount=parseFloat($('#bid_amount_receive').val());
					if(isNaN(amount)){
						amount=0;
					}
					total_project_price=amount/(1-(fee_percent/100));
					
					total_fee=(total_project_price*fee_percent)/100;
					//console.log(amount+' '+total_fee);
					var total_project_price_new=parseFloat(amount)+parseFloat(total_fee);
					$('#bid_amount').val(parseFloat(total_project_price_new).toFixed(2));
				}else{
					var amount=parseFloat($('#bid_amount').val());
					if(isNaN(amount)){
						amount=0;
					}
					total_project_price=amount;
					total_fee=(total_project_price*fee_percent)/100;
					total_recive=parseFloat(total_project_price)-parseFloat(total_fee);
					$('#bid_amount_receive').val(parseFloat(total_recive).toFixed(2));
				}

				$('.total_fee').html(parseFloat(total_fee).toFixed(2));
				
			}else{
				$('.milestone_row_parent .milestone_amount').each(function(){
					var amount=parseFloat($(this).val());
					if(isNaN(amount)){
						amount=0;
					}
					total_project_price=total_project_price+amount;
				});
				total_fee=(total_project_price*fee_percent)/100
				total_recive=parseFloat(total_project_price)-parseFloat(total_fee);
				$('.total_amount').html(parseFloat(total_project_price).toFixed(2));
				$('.total_fee').html(parseFloat(total_fee).toFixed(2));
				$('.total_recive').html(parseFloat(total_recive).toFixed(2));
				$('#bid_amount').val(parseFloat(total_project_price).toFixed(2));
			}
		}
		//console.log(total_project_price+' '+total_fee+' '+total_receive);
	}
	
	var  main = function(){
		$('.datepicker').datetimepicker({
			format: 'YYYY-MM-DD',
			minDate: "<?php echo date('Y-m-d');?>",
		});
		$('.addMilestone').on('click',function(){
			var html='';
			var cnt=$(".milestone_row").last().val();
			var new_row=parseInt(cnt)+1;
			html+='<div class="row milestone_row_parent">';
			html+='<div class="col-md-6 col-12">';
			html+='<input type="hidden" name="milestone_id[]" class="milestone_row" value="'+new_row+'"/>';
			html+='<div class="form-group">';
			html+='<label class="form-label" for="title"><b>Description</b></label>';
			html+='<input type="text" name="milestone_title_'+new_row+'" id="milestone_title_'+new_row+'" class="form-control">';
			html+='</div>';
			html+='</div>';
			html+='<div class="col-md-3 col-12">';
			html+='<div class="form-group position-relative">';
			html+='<label class="form-label" for="title"><b>Due date</b></label>';
			html+='<input type="text" name="milestone_due_date_'+new_row+'" id="milestone_due_date_'+new_row+'" class="datepicker form-control">';
			html+='</div>';
			html+='</div>';
			html+='<div class="col-md-3 col-12">';
			html+='<div class="form-group">';
			html+='<label class="form-label" for="title"><b>Amount</b></label>';
			html+='<div class="input-group">';
			html+='<input type="text" name="milestone_amount_'+new_row+'" id="milestone_amount_'+new_row+'" class="milestone_amount form-control" onKeyUp="updateTotal()">';
			html+='<button class="btn btn-danger btn-circle mt-1 ms-3" onclick="removeRow(this)" style="border-radius:50%; font-size:1.125rem;"><i class="icon-feather-x"></i></button>';
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
		$('.project_payment_type').on('change',function(){
			var project_payment_type=$('.project_payment_type:checked').val();
			if(project_payment_type==1){
				$('.payment_milestone_wrapper').hide();
				$('.payment_project_wrapper').show();
			}else{
				$('.payment_project_wrapper').hide();
				$('.payment_milestone_wrapper').show();
			}
			updateTotal();
		});
		$('.backbtnapply').on('click',function(){
			window.location.href="<?php echo $ProjectDetailsURL;?>";
		});
		$('.nextbtnapply').on('click',function(){
		var buttonsection=$(this);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="applyprojectform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('applyprojectFormCheckAJAXURL'))?>/",
	        data:$('#'+formID).serialize()+'&pid='+pid,
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					bootbox.alert({
						title: '<?php echo __('projectview_apply_success','Project Applied Successfully!');?>',
						message: '<?php echo __('projectview_apply_success_msg','Your submission has been received. You will receive a confirmation shortly in the provided email ID.');?>',
						size: 'small',
						buttons: {
							ok: {
								label: "<?php echo __('projectview_apply_ok','OK');?>",
								className: 'btn-primary float-end'
							},
						},
						callback: function(result){
							window.location.href="<?php echo $ProjectDetailsURL;?>";
						}
					});
					
					
				} else if (msg['status'] == 'FAIL') {
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