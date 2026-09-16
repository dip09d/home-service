<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>


<div class="row">
<?php if($memberInfo->member_timezone){?>
			<div class="col-md-4">
				<div class="submit-field">
					<label>Time Zone</label>
					<p><?php D($memberInfo->member_timezone)?></p>
				</div>
			</div>
<?php }?>
<?php if($memberInfo->member_address_1 || $memberInfo->member_address_2){?>
			<div class="col-md-4">
				<div class="submit-field">
					<label>Address</label>
					<p>
					<?php if($memberInfo->member_address_1){?>
					<?php D($memberInfo->member_address_1)?><br/>
					<?php }?>
					<?php if($memberInfo->member_address_2){?>
					<?php D($memberInfo->member_address_2)?><br/>
					<?php }?>

					<?php if($memberInfo->member_city){?>
					<?php D($memberInfo->member_city)?>,  
					<?php }?>
					<?php if($memberInfo->member_state){?>
					<?php D($memberInfo->member_state)?> 
					<?php }?>
					<?php if($memberInfo->member_pincode){?>
					<?php D($memberInfo->member_pincode)?> 
					<?php }?>

					<?php if($memberInfo->country_name){?>
					<br/>
					<?php D($memberInfo->country_name)?>
					<?php }?>	
					</p>
				</div>
			</div>
<?php }?>
<?php if($memberInfo->member_mobile){?>
			<div class="col-md-4">
				<div class="submit-field">
					<label><?php echo __('setting_contact_info_phone','Phone');?></label>
					<p><?php D($memberInfo->member_mobile_code." "); D($memberInfo->member_mobile)?></p>
				</div>
			</div>
<?php }?>

			
		</div>