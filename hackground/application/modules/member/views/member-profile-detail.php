<link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<script src="<?php echo ADMIN_COMPONENT;?>bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<style>
.account-type {
    display: flex;
    width: calc(100% + 15px);
    margin: 0 0 1rem 0;
}

.account-type div {
    /* flex: 1; */
    margin-right: 15px;
}
.account-type input.account-type-radio:empty {
    display: none;
}
label, legend {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
}
.ripple-effect-dark, .ripple-effect {
    overflow: hidden;
    position: relative;
    z-index: 1;
}
.account-type label {
    border-radius: 20px;
    border: 1px solid #47bb67;
    margin-bottom: 0;
    width: 100%;
}
.account-type input.account-type-radio:empty ~ label {
    position: relative;
    padding: 5px 12px;
    text-align: center;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #47bb67;
    transition: 0.4s;
    overflow: hidden;
}
.account-type input.account-type-radio:checked ~ label {
    color: #fff;
    background-color: #47bb67;
}

.radio input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.radio label {
    margin: 3px 0;
    cursor: pointer;
    position: relative;
    padding-left: 24px;
    line-height: 25px;
}
label span, legend span {
    font-weight: normal;
    font-size: 0.933rem;
    color: #666;
}
.radio input[type="radio"] + label .radio-label {
    content: '';
    background: #fff;
    border-radius: 100%;
    border: 2px solid #b4b4b4;
    display: inline-block;
    width: 18px;
    height: 18px;
    position: relative;
    margin-right: 5px;
    vertical-align: top;
    cursor: pointer;
    text-align: center;
    transition: all 250ms ease;
    background-color: #fff;
    box-shadow: inset 0 0 0 8px #fff;
    z-index: 100;
    position: absolute;
    top: 2px;
    left: 0;
}
.radio input[type="radio"]:checked + label .radio-label {
    background-color: #66676b;
    border-color: #66676b;
    box-shadow: inset 0 0 0 3px #fff;
}
.radio input[type="radio"]:checked + label .radio-label {
    background-color: #40de00;
    border-color: #40de00;
}
.datepicker-days .table-condensed {
    color: #010101;
}
</style>
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $member_id;?>"/>
    <input type="hidden" name="page" value="<?php echo $page;?>"/>
            <?php //get_print($detail, false); ?> 
            <div class="form-group">
                <label for="profile_overview" class="form-label">Overview</label>
                <textarea name="member_basic[member_overview]" class="form-control" rows="8"><?php echo !empty($detail['member_basic']['member_overview']) ? $detail['member_basic']['member_overview'] : '';?></textarea>
            </div>
            <div class="row">
                <div class="col-sm-6">
					<div class="form-group">
						<label for="profile_title" class="form-label">Profile Title</label>
						<input type="text" class="form-control" name="member_basic[member_heading]" value="<?php echo !empty($detail['member_basic']['member_heading']) ? $detail['member_basic']['member_heading'] : '';?>"/>
					</div>
                </div>
                <div class="col-sm-6">
					<div class="form-group">
						<label for="profile_title" class="form-label">Hourly Rate</label>
						<input type="text" class="form-control" name="member_basic[member_hourly_rate]" value="<?php echo !empty($detail['member_basic']['member_hourly_rate']) ? $detail['member_basic']['member_hourly_rate'] : '';?>"/>
					</div>
                </div>
            </div>
			
			 <div class="row">
                <div class="col-sm-6">
					<div class="form-group">
						<label for="profile_title" class="form-label">User Availablity Status</label>
							<div class="account-type">
									<div>
										<input type="radio" name="is_available" id="is_available" class="account-type-radio" value="1" onclick="$('.for_available').show();$('.for_not_available').hide()" checked />
										<label for="is_available" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Available</label>
									</div>

									<div>
										<input type="radio" name="is_available" id="is_not_available" class="account-type-radio" value="0" onclick="$('.for_available').hide();$('.for_not_available').show()" <?php echo !empty($detail['member_basic']['not_available_until']) ? 'checked' : ''; ?> />
										<label for="is_not_available" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i> Not Available</label>
									</div>
							</div>
							
							<div class="for_available" style="display:<?php echo !empty($detail['member_basic']['not_available_until']) ? 'none' : 'block'; ?>">
								<div class="form-group">
									<div class="radio">
									  <input id="defaultInlineFullTime" name="member_basic[available_per_week]" value="FullTime" type="radio" checked />
									  <label for="defaultInlineFullTime"><span class="radio-label"></span> More than 30 hrs/week</label>
									</div>
									
									<div class="radio">
									  <input id="defaultInlinePartTime" name="member_basic[available_per_week]" value="PartTime" type="radio" <?php echo (!empty($detail['member_basic']['available_per_week']) && $detail['member_basic']['available_per_week'] == 'PartTime') ? 'checked' : ''; ?>/>
									  <label for="defaultInlinePartTime"><span class="radio-label"></span> Less than 30 hrs/week</label>
									</div>
									
									<div class="radio">
									  <input id="defaultInlineNotSure" name="member_basic[available_per_week]" value="NotSure" type="radio"  <?php echo (!empty($detail['member_basic']['available_per_week']) && $detail['member_basic']['available_per_week'] == 'NotSure') ? 'checked' : ''; ?> />
									  <label for="defaultInlineNotSure"><span class="radio-label"></span> As needed - open to offers</label>
									</div>
									
									<span id="available_per_weekError" class="rerror"></span>
								</div>	
							</div>
							
							<div class="for_not_available" style="display:<?php echo !empty($detail['member_basic']['not_available_until']) ? 'block' : 'none'; ?>">
								<div class="row">
									<div class="col-xl-6">
										<div class="submit-field">
											<label>When do you expect to be ready for new work?</label>
											<input type="text" class="form-control" value="<?php echo !empty($detail['member_basic']['not_available_until']) ? $detail['member_basic']['not_available_until'] : ''; ?>" name="member_basic[not_available_until]" id="not_available_until" placeholder="">
											<span id="not_available_untilError" class="rerror"></span>
										</div>
									</div>
								</div>
							</div>
					</div>
                </div>
               
            </div>
			
           
            <button type="submit" class="btn btn-site">Save</button>
            
            <?php /*
            <div class="form-group">
                <label for="category_id">DOB</label>
                <input type="text" class="form-control" name="member_basic[dob]" value="<?php echo !empty($detail['member_basic']['dob']) ? $detail['member_basic']['dob'] : '';?>"/>
            </div>
            
            <div class="form-group">
                <label for="category_id">Gender</label>
                <select class="form-control" name="member_basic[gender]">
                    <option value="">-Select-</option>
                    <?php print_select_option_assoc(array('M' => 'Male', 'F' => 'Female'), (!empty($detail['member_basic']['gender']) ? $detail['member_basic']['gender'] : '')); ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <select class="form-control" name="member_basic[member_nationality]">
                    <option value="">-Select-</option>
                    <?php print_select_option(get_all_country(), 'country_code', 'country_name', (!empty($detail['member_basic']['member_nationality']) ? $detail['member_basic']['member_nationality']['code'] : '')); ?>
                </select>
            </div>
            
            
            <div class="form-group">
                <div>
                    <input type="hidden" name="member_basic[hide_photo]" value="0" />
                    <input type="checkbox" name="member_basic[hide_photo]" value="1" class="magic-checkbox" id="hide_photo" <?php echo (!empty($detail['member_basic']['hide_photo']) && $detail['member_basic']['hide_photo'] == '1') ? 'checked' : ''; ?>>
                    <label for="hide_photo">Hide name and photo on Ads</label>
                </div>
            </div>
            */?>

    
</form>


<script>
function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}
$(function(){
	$('#not_available_until').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		startDate: new Date()
	});
});



</script>