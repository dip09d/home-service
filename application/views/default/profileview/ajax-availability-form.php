<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('profileview_availability_change_availability','Change availability');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveAvailability(this)"><?php echo __('profileview_save','Save');?></button>
      </div>
    <div class="modal-body">
	    <div class="row">
			<div class="col">
				<form action="" method="post" accept-charset="utf-8" id="availabilityform" class="form-horizontal" role="form" name="availabilityform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
				
				<div class="row">
					<div class="col-xl-6">
						<div class="submit-field">
							<h5><?php echo __('profileview_availability_currently','I am currently');?></h5>
							<div class="account-type">
								<div>
									<input type="radio" name="is_available" id="is_available" class="account-type-radio" value="1" onclick="$('.for_available').show();$('.for_not_available').hide()" <?php if(!$memberInfo->not_available_until){echo 'checked';}?>>
									<label for="is_available" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i><?php echo __('profileview_availability_available','Available');?> </label>
								</div>

								<div>
									<input type="radio" name="is_available" id="is_not_available" class="account-type-radio" value="0" onclick="$('.for_available').hide();$('.for_not_available').show()" <?php if($memberInfo->not_available_until){echo 'checked';}?>>
									<label for="is_not_available" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i><?php echo __('profileview_availability_not_available','Not Available');?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="for_available" <?php if($memberInfo->not_available_until){?> style="display:none" <?php }else{?>style="display:block"<?php }?>>
				<div class="form-group">
				<?php if($all_Duration){
					foreach($all_Duration as $key=>$keydata){
						?>
					<div class="radio">
	                  <input id="defaultInline<?php D($key);?>" name="available_per_week" value="<?php D($key);?>" type="radio" <?php if($memberInfo->available_per_week && $memberInfo->available_per_week==$key){echo 'checked';}?> >
	                  <label for="defaultInline<?php D($key);?>"><span class="radio-label"></span> <?php D($keydata['freelanceName']);?></label>
	                </div>
	                 <br>
	            <?php }
	            }
	            ?>
	            <span id="available_per_weekError" class="rerror"></span>
				</div>	
				</div>
				<div class="for_not_available" <?php if($memberInfo->not_available_until){?> style="display:block" <?php }else{?>style="display:none"<?php }?>>
					<div class="row">
						<div class="col-xl-6">
							<div class="submit-field">
								<h5><?php echo __('profileview_availability_new_work','When do you expect to be ready for new work?');?></h5>
								<input type="text" class="form-control datepicker" value="<?php D($memberInfo->not_available_until);?>" name="not_available_until" id="not_available_until" placeholder="">
								<span id="not_available_untilError" class="rerror"></span>
							</div>
						</div>
       				</div>
				</div>
				
       			</form>
       		</div>
       	</div>
    </div>
<script>
$(document).ready(function(){
	$('.datepicker').datetimepicker({
		format: 'YYYY-MM-DD',
		minDate: "<?php echo date('Y-m-d');?>",
	});
})
</script>