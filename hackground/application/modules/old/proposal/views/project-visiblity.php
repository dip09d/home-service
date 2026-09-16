
		<?php //get_print($detail, false); ?>
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
				<input type="hidden" name="ID" value="<?php echo $project_id;?>"/>
				<input type="hidden" name="page" value="<?php echo $page;?>"/>

					<div class="row">
                    <div class="col-sm-6">
					<div class="form-group">
						<label for="category_id" class="form-label">Who can see this project ? </label>
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
					</div>
                    <div class="col-sm-6">
					<div class="form-group">
						<label for="category_id" class="form-label">How many freelancer you need for your project? ? </label>
						<div>
							 <input type="radio" name="project_member_required" value="1" class="magic-checkbox" id="project_member_required_1" checked>
							  <label for="project_member_required_1">One freelancer</label>
						</div>
						<div>
							 <input type="radio" name="project_member_required" value="M" class="magic-checkbox" id="project_member_required_M" <?php echo ($detail['project_member_required'] > 1) ? 'checked' : ''; ?>>
							  <label for="project_member_required_M">More than one freelancer</label>
						</div>
						
					</div>
					</div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
					<div class="form-group multifreelancer_wrapper">
						<label for="budget" class="form-label">Number of freelancer </label>
						<input type="number" class="form-control" name="no_of_freelancer" value="<?php echo ($detail['project_member_required'] > 1) ? $detail['project_member_required'] : ''; ?>"/>
					</div>
					</div>
                    </div>
                	<button type="submit" class="btn btn-site">Save</button>	
                			
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

$('[name="project_member_required"]').click(function(){
	var val = $('[name="project_member_required"]:checked').val();
	if(val == 'M'){
		$('.multifreelancer_wrapper').show();
	}else{
		$('.multifreelancer_wrapper').hide();
	}
});

$('[name="project_member_required"]:checked').click();
</script>