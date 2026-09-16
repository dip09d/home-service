<div class="row">
	<div class="col-sm-6">
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
			<input type="hidden" name="ID" value="<?php echo $member_id;?>"/>
			<input type="hidden" name="page" value="<?php echo $page;?>"/>
			<div class="box-body">
					<div class="form-group">
						<label for="category_id">Member CV</label>
						<?php if(!empty($detail['member_basic']['member_cv'])){ ?>
						<p><a target="_blank" href="<?php echo $detail['member_basic']['member_cv']['file_url']; ?>"><?php echo $detail['member_basic']['member_cv']['original_name']; ?></a></p>
						<?php }else{  ?>
						<p><i>Not Available</i></p>
						<?php } ?>
					</div>
					
					<div class="form-group">
						<label for="category_id">Highest Academic Achievement</label>
						<select name="member_basic[member_highest_academy]" class="form-control">
							<option value="">-Select-</option>
							<?php print_select_option($options['academy'], 'academy_id', 'name', $detail['member_basic']['member_highest_academy']); ?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="category_id">CV Summary</label>
						<textarea class="form-control" name="member_basic[member_cv_summary]"><?php echo !empty($detail['member_basic']['member_cv_summary']) ? $detail['member_basic']['member_cv_summary'] : '';?></textarea>
					</div>
					
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn-block btn btn-primary">Save</button>
				</div>
	
			</div>
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