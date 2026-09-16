<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('profileview_hourly_rate_change','Change hourly rate');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveHourly(this)"><?php echo __('profileview_save','Save');?></button>
      </div>
    <div class="modal-body">
	    <div class="row">
			<div class="col">
				<form action="" method="post" accept-charset="utf-8" id="hourlyform" class="form-horizontal" role="form" name="hourlyform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
				<?php if($memberInfo->member_hourly_rate && $memberInfo->member_hourly_rate>0){?>
					<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<p><?php echo __('profileview_hourly_profile_rate','Your profile rate:');?> <?php D(priceSymbol().$memberInfo->member_hourly_rate);?>/hr</p>
							</div>
						</div>
       				</div>
       			<?php }?>
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_hourly_hourly_rate','Hourly Rate');?></h5>
								<input type="text" class="form-control input-text with-border" value="<?php D($memberInfo->member_hourly_rate)?>" name="hourly" id="hourly" placeholder="10">
								<span id="hourlyError" class="rerror"></span>
							</div>
						</div>
       				</div>
       			</form>
       		</div>
       	</div>
    </div>