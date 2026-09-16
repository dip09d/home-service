<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//get_print($contractDetails,false);
if($contractDetails->is_hourly){
	$contract_details_url=get_link('ContractDetailsHourly').'/'.md5($contractDetails->contract_id);
}else{
	$contract_details_url=get_link('ContractDetails').'/'.md5($contractDetails->contract_id);
}

?>
<section class="section">
<div class="container">
      <a href="<?php echo $contract_details_url;?>" class="mb-1 btn btn-link p-0"><i class="icon-feather-chevron-left"></i><?php echo __('contract_end_b_contract','Back to Contract');?> </a>
      
        <h1 class="display-4"><?php echo __('contract_end_l_feedback','Leave Feedback');?></h1>
        <div class="row">
          <div class="col-lg-9">
<form action="" method="post" accept-charset="utf-8" id="endcontractform" class="form-horizontal" role="form" name="endcontractform" onsubmit="return false;">  
            <div class="panel mb-4">
              <div class="panel-header">
                <h4><?php echo __('contract_end_details','Details');?></h4>
              </div>
              <div class="panel-body">
              <?php if($is_owner){?>
              <p><b><?php echo __('contract_end_contractor','Contractor:');?></b> <?php echo $contractDetails->contractor->member_name;?></p>
              <?php }else{?>
              <p><b><?php echo __('contract_end_client','Client:');?></b> <?php echo ($contractDetails->owner->organization_name ? $contractDetails->owner->organization_name:$contractDetails->owner->member_name);?></p>
              <?php }?>
                <p><b><?php echo __('contract_end_c_title','Contract Title:');?></b> <?php echo $contractDetails->contract_title;?></p>
              
              </div>
            </div>
            <div class="panel mb-4">
              <div class="panel-header d-flex">
                <h4 class="mt-2"><?php echo __('contract_end_p_feedback','Public Feedback');?></h4>
               
                </div>
              <div class="panel-body">
              <?php if($is_owner){?>
               <p><?php echo __('contract_end_this_feedback','This feedback will share on your contracttor profile after they have left feedback to you.');?> </p>
                <h5><?php echo __('contract_end_f_contractor','Feedback to Contracttor');?></h5>
                 <div class="">
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_skills"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_skills','Skills');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_quality"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_quility','Quality');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_availability"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_availability','Availability');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_deadlines"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_deadline','Deadlines');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_communication"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_communication','Communication');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_cooperation"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_coorperation','Cooperation');?></div>
                </div>
                
                	
                </div>
                
                
                
                
                
                <?php }else{?>
                <p><?php echo __('contract_end_this_feedback','This feedback will share on your client profile after they have left feedback to you.');?> </p>
                <h5><?php echo __('contract_end_f_client','Feedback to Client');?></h5>
                <div class="">
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_skills"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_skills','Skills');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_quality"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_q_requirement','Quality Of Requirements');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_availability"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_availability','Availability');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_deadlines"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_s_deadlines','Set Reasonable Deadlines');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_communication"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_communication','Communication');?></div>
                </div>
                <div class="form-group row">
                	<div class="col-auto">
                		<div id="modal_rating_cooperation"></div>
                	</div>
                	<div class="col-auto"><?php echo __('contract_end_coorperation','Cooperation');?></div>
                </div>
                
                	
                </div>
                <?php }?>
                <h5><?php echo __('contract_end_total_score','Total Score:');?> <span id="modal_avg_rating_view">0</span></h5>
                
                
                
                <div class="form-group">
	                <label><b><?php echo __('contract_end_s_experience','Share your experience');?></b></label>
	                <textarea class="form-control" id="comment" name="comment"></textarea>
	              </div>
              
              
                <input type="hidden" name="skills" value="0" id="modal_rating_skills_input"/>
                <input type="hidden" name="quality" value="0" id="modal_rating_quality_input"/>
                <input type="hidden" name="availability" value="0" id="modal_rating_availability_input"/>
                <input type="hidden" name="deadlines" value="0" id="modal_rating_deadlines_input"/>
                <input type="hidden" name="communication" value="0" id="modal_rating_communication_input"/>
                <input type="hidden" name="cooperation" value="0" id="modal_rating_cooperation_input"/>
                <input type="hidden" name="average" value="0" id="modal_rating_average_input"/>
                
                
              </div>
				
            </div>
            
				<button class="btn btn-primary nextbtnapply"><?php echo __('contract_end_sub_feedback','Submit Feedback');?></button> &nbsp;
				<button class="btn btn-secondary backbtnapply"><?php echo __('contract_end_cancel','Cancel');?></button>
			
</form>
          </div>

        </div>
      </div>
</section>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var c_id='<?php echo md5($contractDetails->contract_id);?>';
function check_total_rating(){
	var rating_skills_input = parseInt($('#modal_rating_skills_input').val());
	var rating_quality_input = parseInt($('#modal_rating_quality_input').val());
	var rating_availablity_input = parseInt($('#modal_rating_availability_input').val());
	var rating_deadlines_input = parseInt($('#modal_rating_deadlines_input').val());
	var rating_communication_input = parseInt($('#modal_rating_communication_input').val());
	var rating_cooperation_input = parseInt($('#modal_rating_cooperation_input').val());
	
	var avg = ((rating_skills_input + rating_quality_input + rating_availablity_input +rating_deadlines_input + rating_communication_input + rating_cooperation_input) / 6); 
	$('#modal_rating_average_input').val(avg);
	$('#modal_avg_rating_view').html(avg.toFixed(1));
}
	var main=function(){
		$('.backbtnapply').on('click',function(){
			window.location.href="<?php echo $contract_details_url;?>";
		});
		$('.nextbtnapply').on('click',function(){
			var buttonsection=$(this);
			buttonsection.attr('disabled','disabled');
			var buttonval = buttonsection.html();
			buttonsection.html(SPINNER).attr('disabled','disabled');
			var formID="endcontractform";
			$.ajax({
		        type: "POST",
		        url: "<?php D(get_link('endcontractformFormCheckAJAXURL'))?>/",
		        data:$('#'+formID).serialize()+'&c_id='+c_id,
		        dataType: "json",
		        cache: false,
				success: function(msg) {
					buttonsection.html(buttonval).removeAttr('disabled');
					clearErrors();
					if (msg['status'] == 'OK') {
						bootbox.alert({
							title:'End Contract',
							message: '<?php D(__('end_contract_success_message','Contract Ended succesfully.'));?>',
							buttons: {
							'ok': {
								label: 'Ok',
								className: 'btn-primary float-end'
								}
							},
							callback: function () {
								window.location.href="<?php echo $contract_details_url;?>";
						    }
						});
					} else if (msg['status'] == 'FAIL') {
						registerFormPostResponse(formID,msg['errors']);
					}
				}
			})		
		});
		$("#modal_rating_skills").rateYo({
			normalFill: "#ddd",
			ratedFill: "#FF912C",
			rating    : 0,
			fullStar: true,
			starWidth: "20px",
			spacing: "5px",
			onSet: function (rating, rateYoInstance) {
				$('#modal_rating_skills_input').val(rating);
				check_total_rating();
			}
		});
		$("#modal_rating_quality").rateYo({
			normalFill: "#ddd",
			ratedFill: "#FF912C",
			rating    : 0,
			fullStar: true,
			starWidth: "20px",
			spacing: "5px",
			onSet: function (rating, rateYoInstance) {
				$('#modal_rating_quality_input').val(rating);
				check_total_rating();
			}
		});
		$("#modal_rating_availability").rateYo({
			normalFill: "#ddd",
			ratedFill: "#FF912C",
			rating    : 0,
			fullStar: true,
			starWidth: "20px",
			spacing: "5px",
			onSet: function (rating, rateYoInstance) {
				$('#modal_rating_availability_input').val(rating);
				check_total_rating();
			}
		});
		$("#modal_rating_deadlines").rateYo({
			normalFill: "#ddd",
			ratedFill: "#FF912C",
			rating    : 0,
			fullStar: true,
			starWidth: "20px",
			spacing: "5px",
			onSet: function (rating, rateYoInstance) {
				$('#modal_rating_deadlines_input').val(rating);
				check_total_rating();
			}
		});
		$("#modal_rating_communication").rateYo({
			normalFill: "#ddd",
			ratedFill: "#FF912C",
			rating    : 0,
			fullStar: true,
			starWidth: "20px",
			spacing: "5px",
			onSet: function (rating, rateYoInstance) {
				$('#modal_rating_communication_input').val(rating);
				check_total_rating();
			}
		});
		$("#modal_rating_cooperation").rateYo({
			normalFill: "#ddd",
			ratedFill: "#FF912C",
			rating    : 0,
			fullStar: true,
			starWidth: "20px",
			spacing: "5px",
			onSet: function (rating, rateYoInstance) {
				$('#modal_rating_cooperation_input').val(rating);
				check_total_rating();
			}
		});
	}
</script>