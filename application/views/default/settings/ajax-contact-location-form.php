<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>


<form action="" method="post" accept-charset="utf-8" id="locationform" class="form-horizontal" role="form" name="locationform" onsubmit="updateLocation();return false;">  
	<div class="row">
		<div class="col-md-6">
        <div class="submit-field">
            <label class="form-label"><?php echo __('setting_contact_info_time_zone','Time Zone');?></label>
            <input type="text" class="form-control" value="<?php D($memberInfo->member_timezone)?>" name="timezone" id="timezone" placeholder="Enter timezone">
            <span id="timezoneError" class="rerror"></span>
        </div>
    	</div>
        <div class="col-md-6">
        <div class="submit-field remove_arrow_select">
            <label class="form-label"><?php echo __('setting_contact_info_country','Country');?></label>  
            <select name="country" id="country" data-size="4" class=" selectpicker" title="Select country" data-live-search="true">
                <?php
                if($country){
                    foreach($country as $country_list){
                        ?>
                        <option value="<?php echo $country_list->country_code;?>" <?php if($country_list->country_code==$memberInfo->member_country){echo 'selected';}?>><?php echo ucfirst($country_list->country_name);?></option>
                        <?php
                    }
                }
                 ?>
            </select>          	
        </div>
        </div>
	</div>    
        
        <span id="countryError" class="rerror"></span>
        <div class="submit-field">
            <label class="form-label"><?php echo __('setting_contact_info_address','Address');?></label>
            <input type="text" class="form-control mb-3" value="<?php D($memberInfo->member_address_1)?>" name="address_1" id="address_1" placeholder="Enter address line 1">
            <input type="text" class="form-control" value="<?php D($memberInfo->member_address_2)?>" name="address_2" id="address_2" placeholder="Enter address line 2">
            <span id="address_1Error" class="rerror"></span>
        </div>
        
		<div class="row">	
			<div class="col-md-4">
				<div class="submit-field">
					<label class="form-label"><?php echo __('setting_contact_info_city','City');?></label>
					<input type="text" class="form-control" value="<?php D($memberInfo->member_city)?>" name="city" id="city" placeholder="Enter city">
					<span id="cityError" class="rerror"></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="submit-field">
					<label class="form-label"><?php echo __('setting_contact_info_state','State');?></label>
					<input type="text" class="form-control" value="<?php D($memberInfo->member_state)?>" name="state" id="state" placeholder="Enter state">
					<span id="stateError" class="rerror"></span>
				</div>
			</div>
            <div class="col-md-4">
				<div class="submit-field">
					<label class="form-label"><?php echo __('setting_contact_info_pastal_code','Postal Code');?></label>
					<input type="text" class="form-control" value="<?php D($memberInfo->member_pincode)?>" name="pincode" id="pincode" placeholder="Enter pincode">
					<span id="pincodeError" class="rerror"></span>
				</div>
			</div>
		</div>
		
		<div class="submit-field">	
		<label class="form-label"><?php echo __('setting_contact_info_phone','Phone');?></label>
		<div class="input-group">
            <div class="input-group-prepend">					
                <input type="text" class="form-control brr-0" style="max-width:80px" value="<?php D($memberInfo->member_mobile_code)?>" name="mobile_code" id="mobile_code" placeholder="Enter mobile code">
            </div>
			<input type="text" class="form-control" value="<?php D($memberInfo->member_mobile)?>" name="mobile" id="mobile" placeholder="Enter mobile">					
		</div>
		<span id="mobileError" class="rerror"></span>
		<span id="mobile_codeError" class="rerror"></span>			
		</div>
        
			<button class="btn btn-primary me-2 locationUpdateBTN"><?php echo __('setting_contact_info_update','Update');?></button>
			<a href="javascript:void(0)" class="btn btn-secondary" id="cancel_location"><?php echo __('setting_contact_info_cancel','Cancel');?> </a>
			
</form>
	