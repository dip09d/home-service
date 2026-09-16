<div class="modal-body">
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
	<input type="hidden" name="ID" value="<?php echo $ID;?>"/>
	<input type="hidden" name="education_id" value="<?php echo $education_id;?>"/>
	<input type="hidden" name="page" value="<?php echo $page;?>"/>

		<?php //get_print($detail, false); ?>
		
		<div class="form-group">
			<label for="school" class="form-label">School </label>
			<input type="text" class="form-control" name="school" value="<?php echo !empty($detail['education_school']) ? $detail['education_school'] : ''; ?>"/>
		</div>
		
		<div class="form-group">
			<label for="company" class="form-label">Dates Attended </label>
			<div class="row">
				<div class="col-sm-6">
					<select class="form-control" name="from_year">
							<option value="">Select year </option>
						<?php
							for($i=date('Y');$i>=1940;$i--){
								?>
								<option value="<?php echo $i;?>" <?php echo (!empty($detail['education_from_year']) && $detail['education_from_year'] == $i) ? 'selected' : ''; ?>><?php echo $i;?></option>
								<?php
							}
							 ?>
					</select>
				</div>
				<div class="col-sm-6">
					<select class="form-control" name="end_year">
							<option value="">Select year </option>
						<?php
							for($i=date('Y');$i>=1940;$i--){
								?>
								<option value="<?php echo $i;?>" <?php echo (!empty($detail['education_end_year']) && $detail['education_end_year'] == $i) ? 'selected' : ''; ?>><?php echo $i;?></option>
								<?php
							}
							 ?>
					</select>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label for="degree" class="form-label">Degree (optional) </label>
			<input type="text" class="form-control" name="degree" value="<?php echo !empty($detail['education_degree']) ? $detail['education_degree'] : ''; ?>"/>
		</div>
		
		<div class="form-group">
			<label for="area_of_study" class="form-label">Area of Study (Optional) </label>
			<input type="text" class="form-control" name="area_of_study" value="<?php echo !empty($detail['education_area_of_study']) ? $detail['education_area_of_study'] : ''; ?>"/>
		</div>
		
		<div class="form-group">
			<label for="description" class="form-label">Description (Optional) </label>
			<textarea class="form-control" name="description"><?php echo !empty($detail['education_description']) ? $detail['education_description'] : ''; ?></textarea>
		</div>


		<button type="submit" class="btn btn-site"><?php echo !empty($detail) ? 'Save' : 'Add'; ?></button>
</form>
</div>