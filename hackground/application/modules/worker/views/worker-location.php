<div class="row">
	<div class="col-sm-6">
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
			<input type="hidden" name="ID" value="<?php echo $worker_id;?>"/>
			<input type="hidden" name="page" value="<?php echo $page;?>"/>
			
					
					
					<div class="form-group">
						<label for="address_1">Address</label>
						<input type="text" class="form-control" name="worker_address[worker_address]" value="<?php echo !empty($detail['worker_address']['worker_address']) ? $detail['worker_address']['worker_address'] : '' ;?>"/>
					</div>
					
					<div class="form-group">
						<label for="worker_flat">House/Flat No.(optional)</label>
						<input type="text" class="form-control" name="worker_address[worker_flat]" value="<?php echo !empty($detail['worker_address']['worker_flat']) ? $detail['worker_address']['worker_flat'] : '' ;?>"/>
					</div>
					<div class="form-group">
						<label for="worker_street">Street/Area (optional)</label>
						<input type="text" class="form-control" name="worker_address[worker_street]" value="<?php echo !empty($detail['worker_address']['worker_flat']) ? $detail['worker_address']['worker_street'] : '' ;?>"/>
					</div>
					
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="worker_city">City</label>
								
								<input type="text" class="form-control" name="worker_address[worker_city]" value="<?php echo !empty($detail['worker_address']['worker_city']) ? $detail['worker_address']['worker_city'] : '' ;?>"/>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-group">
								<label for="worker_state">State</label>
								<select class="form-control" name="worker_address[worker_state]">
									<option value="">-Select-</option>
									<?php print_select_option(get_all_state('IND'), 'state_id', 'state_name', (!empty($detail['worker_address']['worker_state']) ? $detail['worker_address']['worker_state'] : '')); ?>
								</select>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-group">
								<label for="worker_pincode">Postal Code</label>
								<input type="text" class="form-control" name="worker_address[worker_pincode]" value="<?php echo !empty($detail['worker_address']['worker_pincode']) ? $detail['worker_address']['worker_pincode'] : '' ;?>"/>
							</div>
						</div>
						
					</div>
					
					<div class="form-group">
						<label for="worker_landmark">Landmark </label>
						<input type="text" class="form-control" name="worker_address[worker_landmark]" value="<?php echo !empty($detail['worker_address']['worker_landmark']) ? $detail['worker_address']['worker_landmark'] : '' ;?>"/>
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