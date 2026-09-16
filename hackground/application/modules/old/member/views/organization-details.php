<div class="row">
	<div class="col-sm-6">
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
			<input type="hidden" name="ID" value="<?php echo $organization_id;?>"/>
			<input type="hidden" name="page" value="<?php echo $page;?>"/>
			
					<div class="form-group">
						<label for="organization_name">Company Name</label>
						<input type="text" class="form-control" name="organization_basic[organization_name]" value="<?php echo !empty($detail['organization_basic']['organization_name']) ? $detail['organization_basic']['organization_name'] : '' ;?>"/>
					</div>
					<div class="form-group">
						<label for="organization_website">Website</label>
						<input type="text" class="form-control" name="organization_basic[organization_website]" value="<?php echo !empty($detail['organization_basic']['organization_website']) ? $detail['organization_basic']['organization_website'] : '' ;?>"/>
					</div>
					<div class="form-group">
						<label for="organization_heading">Tagline</label>
						<input type="text" class="form-control" name="organization_basic[organization_heading]" value="<?php echo !empty($detail['organization_basic']['organization_heading']) ? $detail['organization_basic']['organization_heading'] : '' ;?>"/>
					</div>
					<div class="form-group">
						<label for="organization_info">Description</label>
						<input type="text" class="form-control" name="organization_basic[organization_info]" value="<?php echo !empty($detail['organization_basic']['organization_info']) ? $detail['organization_basic']['organization_info'] : '' ;?>"/>
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
function getCity(country_code){
	var url = '<?php echo base_url($curr_controller.'/getcity');?>';
	$.ajax({
		url : url,
		data: {country_code: country_code},
		type: 'POST',
		
		success: function(res){
			$('#city_section').html(res);
			
		}
	});
}
function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>