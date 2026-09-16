<div class="row">
	<div class="col-sm-6">
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
				<input type="hidden" name="ID" value="<?php echo $project_id;?>"/>
				<input type="hidden" name="page" value="<?php echo $page;?>"/>
				<div class="box-body">
					<!--<div class="form-group">
						<label for="category_id">Contact Name </label>
						<input type="text" class="form-control" name="contact_name" value="<?php echo $detail['contact']['contact_name'];?>"/>
					</div>-->
					<div class="form-group">
						<label for="category_id">Phone Number </label>
						<input type="text" class="form-control" name="contact_phone" value="<?php echo $detail['contact']['contact_phone'];?>"/>
					</div>
					<div class="form-group">
						<label for="category_id">WhatsApp Number </label>
						<input type="text" class="form-control" name="contact_whatsapp" value="<?php echo $detail['contact']['contact_whatsapp'];?>"/>
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

</script>