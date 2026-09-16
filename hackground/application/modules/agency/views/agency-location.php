<div class="row">
	<div class="col-sm-6">
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
			<input type="hidden" name="ID" value="<?php echo $agency_id;?>"/>
			<input type="hidden" name="page" value="<?php echo $page;?>"/>
			
					
					
					<div class="form-group">
						<label for="address_1">Address</label>
						<input type="text" class="form-control" name="agency_address[agency_address]" value="<?php echo !empty($detail['agency_address']['agency_address']) ? $detail['agency_address']['agency_address'] : '' ;?>"/>
					</div>
					
					
					
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="agency_city">City</label>
								
								<input type="text" class="form-control" name="agency_address[agency_city]" value="<?php echo !empty($detail['agency_address']['agency_city']) ? $detail['agency_address']['agency_city'] : '' ;?>"/>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-group">
								<label for="agency_state">State</label>
								<select class="form-control" name="agency_address[agency_state]">
									<option value="">-Select-</option>
									<?php print_select_option(get_all_state('IND'), 'state_id', 'state_name', (!empty($detail['agency_address']['agency_state']) ? $detail['agency_address']['agency_state'] : '')); ?>
								</select>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-group">
								<label for="agency_pincode">Postal Code</label>
								<input type="text" class="form-control" name="agency_address[agency_pincode]" value="<?php echo !empty($detail['agency_address']['agency_pincode']) ? $detail['agency_address']['agency_pincode'] : '' ;?>"/>
							</div>
						</div>
						
					</div>
					
					<div class="form-group">
						<label for="agency_landmark">Landmark </label>
						<input type="text" class="form-control" name="agency_address[agency_landmark]" value="<?php echo !empty($detail['agency_address']['agency_landmark']) ? $detail['agency_address']['agency_landmark'] : '' ;?>"/>
					</div>
					
					<button type="submit" class="btn btn-site">Save</button>
	
			
		</form>
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

</script>