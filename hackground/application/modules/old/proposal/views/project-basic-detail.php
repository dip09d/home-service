
		<?php //get_print($detail, false); ?>
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
				<input type="hidden" name="ID" value="<?php echo $project_id;?>"/>
				<input type="hidden" name="page" value="<?php echo $page;?>"/>
				<input type="hidden" name="category_subchild_id" value="<?php echo $page;?>"/>
				
                        <div class="form-group">
						<label for="experience_level" class="form-label">Type of project </label>
						<select class="form-control" name="project_type_code">
							<option value=""> - Select - </option>
							<option value="OneTime" <?php echo $detail['settings']['project_type_code'] == 'OneTime' ? 'selected' : ''; ?>> One time project</option>
							<option value="Ongoing" <?php echo $detail['settings']['project_type_code'] == 'Ongoing' ? 'selected' : ''; ?>> Ongoing project</option>
							<option value="NotSure" <?php echo $detail['settings']['project_type_code'] == 'NotSure' ? 'selected' : ''; ?>> Not Sure</option>
						</select>
						</div>
                        
                        <div class="form-group">                   
						<label for="experience_level" class="form-label">Screen Question (optional)</label>
						<div id="screen-question-wrapper">
						<?php if(count($detail['questions']) > 0){foreach($detail['questions'] as $k => $v){ ?>
							<div class="input-group mb-3" id="row_<?php echo $k+1;?>">
								<input type="text" class="form-control" name="question[]" value="<?php echo $v['question_title'];?>"/>
								<div class="input-group-append"><a href="#" data-row-id="<?php echo $k+1;?>" class="remove_row btn btn-danger"><i class="fa fa-remove"></i></a></div>
							</div>
							
						<?php } } ?>
						</div>
                        
						<button type="button" id="add-question-btn" class="btn btn-site"> + Add</button>
					
                        </div>
                    
					
					<div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
						<div>
							<input type="hidden" name="project_additional[project_is_cover_required]" value="0" />
							 <input type="checkbox" name="project_additional[project_is_cover_required]" value="1" class="magic-checkbox" id="required_cover" <?php echo $detail['additional']['project_is_cover_required'] == '1' ? 'checked' : ''; ?>/>
							<label for="required_cover">Required cover letter</label>
						</div>
					</div>
                        </div>
                        <div class="col-sm-6">
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

$('[name="payment_type"]').change();

(function(){
	var $wrapper = $('#screen-question-wrapper');
	var qstn_length = $('#screen-question-wrapper > div').length;
	var screen_question_count = 0;
	if(qstn_length > 0){
		screen_question_count += qstn_length;
	}
	
	$wrapper.on('click', '.remove_row', function(e){
		e.preventDefault();
		var row_id = $(this).data('rowId');
		$wrapper.find('#row_'+row_id).remove();
	});
	$('#add-question-btn').click(function(){
		screen_question_count++;
		var $wrapper = $('#screen-question-wrapper');
		var html = '<div class="input-group mb-3" id="row_'+screen_question_count+'"><input type="text" class="form-control" name="question[]"/><div class="input-group-append"><a href="#" data-row-id="'+screen_question_count+'" class="remove_row btn btn-danger"><i class="fa fa-remove"></i></a></div></div>';
		$wrapper.append(html);
	});
	
})();
</script>