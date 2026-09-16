<div class="row">
	<div class="col-sm-6">
		<?php //get_print($detail, false); ?>
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
				<input type="hidden" name="ID" value="<?php echo $project_id;?>"/>
				<input type="hidden" name="page" value="<?php echo $page;?>"/>
				<div class="box-body">
					
					<div class="form-group">
						<label for="category_id">Project Visiblity </label>
						<div>
							 <input type="radio" name="visiblity" value="is_visible_anyone" class="magic-checkbox" id="is_visible_anyone" <?php echo $detail['settings']['is_visible_anyone'] == '1' ? 'checked' : ''; ?>>
							  <label for="is_visible_anyone">Anyone (Public)</label>
						</div>
						<div>
							 <input type="radio" name="visiblity" value="is_visible_private" class="magic-checkbox" id="is_visible_private" <?php echo $detail['settings']['is_visible_private'] == '1' ? 'checked' : ''; ?>>
							  <label for="is_visible_private">Private</label>
						</div>
						<div>
							 <input type="radio" name="visiblity" value="is_visible_invite" class="magic-checkbox" id="is_visible_invite" <?php echo $detail['settings']['is_visible_invite'] == '1' ? 'checked' : ''; ?>>
							  <label for="is_visible_invite">Invite</label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="project_type">Payment Type</label>
						<div>
							 <input type="radio" name="payment_type" value="is_fixed" class="magic-checkbox" id="payment_type_is_fixed" <?php echo $detail['settings']['is_fixed'] == '1' ? 'checked' : ''; ?>>
							  <label for="payment_type_is_fixed">Fixed</label>
						</div>
						<div>
							 <input type="radio" name="payment_type" value="is_hourly" class="magic-checkbox" id="payment_type_is_hourly" <?php echo $detail['settings']['is_hourly'] == '1' ? 'checked' : ''; ?>>
							  <label for="payment_type_is_hourly">Hourly</label>
						</div>
					</div>
					
					
					<div class="form-group fixed_wrapper">
						<label for="budget">Budget </label>
						<input type="number" class="form-control" name="budget" value="<?php echo $detail['settings']['budget']; ?>"/>
					</div>
					
					<div class="form-group hourly_wrapper">
						<label for="experience_level">Project Duration </label>
						<select class="form-control" name="hourly_duration">
							<option value=""> - Select - </option>
							<option value="Less1month" <?php echo $detail['settings']['hourly_duration'] == 'Less1month' ? 'selected' : ''; ?>> Less than 1 month</option>
							<option value="1To2month" <?php echo $detail['settings']['hourly_duration'] == '1To2month' ? 'selected' : ''; ?>> 1 to 2 month</option>
							<option value="More3month" <?php echo $detail['settings']['hourly_duration'] == 'More3month' ? 'selected' : ''; ?>> More than 3 month</option>
						</select>
					</div>
					
					<div class="form-group hourly_wrapper">
						<label for="experience_level">Time required </label>
						<select class="form-control" name="hourly_time_required">
							<option value=""> - Select - </option>
							<option value="FullTime" <?php echo $detail['settings']['hourly_time_required'] == 'FullTime' ? 'selected' : ''; ?>> Full Time</option>
							<option value="PartTime" <?php echo $detail['settings']['hourly_time_required'] == 'PartTime' ? 'selected' : ''; ?>> Part Time</option>
							<option value="NotSure" <?php echo $detail['settings']['hourly_time_required'] == 'NotSure' ? 'selected' : ''; ?>> Not Sure</option>
						</select>
					</div>
					
					<div class="form-group">
						<label for="experience_level">Experience Level </label>
						<select class="form-control" name="experience_level">
							<option value=""> - Select - </option>
							<?php print_select_option($experience_level, 'experience_level_id', 'experience_level_name', $detail['settings']['experience_level']['ID']); ?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="experience_level">Type of project </label>
						<select class="form-control" name="project_type_code">
							<option value=""> - Select - </option>
							<option value="OneTime" <?php echo $detail['settings']['project_type_code'] == 'OneTime' ? 'selected' : ''; ?>> One time project</option>
							<option value="Ongoing" <?php echo $detail['settings']['project_type_code'] == 'Ongoing' ? 'selected' : ''; ?>> Ongoing project</option>
							<option value="NotSure" <?php echo $detail['settings']['project_type_code'] == 'NotSure' ? 'selected' : ''; ?>> Not Sure</option>
						</select>
					</div>
					
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn-block btn btn-primary">Save</button>
				</div>
		</form>
	</div>
	
</div>
</div>

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

$('[name="payment_type"]').change(function(){
	var val = $('[name="payment_type"]:checked').val();
	console.log(val);
	if(val == 'is_fixed'){
		$('.hourly_wrapper').hide();
		$('.fixed_wrapper').show();
	}else if(val == 'is_hourly'){
		$('.hourly_wrapper').show();
		$('.fixed_wrapper').hide();
	}else{
		$('.hourly_wrapper').hide();
		$('.fixed_wrapper').hide();
	}
});

$('[name="payment_type"]').change();
</script>