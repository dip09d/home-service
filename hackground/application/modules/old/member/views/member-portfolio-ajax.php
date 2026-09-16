<div class="modal-body">
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
	<input type="hidden" name="ID" value="<?php echo $ID;?>"/>
	<input type="hidden" name="portfolio_id" value="<?php echo $portfolio_id;?>"/>
	<input type="hidden" name="page" value="<?php echo $page;?>"/>

		<?php //get_print($detail, false); ?>
		
		<div class="form-group">
			<label for="title" class="form-label">Project Title </label>
			<input type="text" class="form-control" name="title" value="<?php echo !empty($detail['portfolio_title']) ? $detail['portfolio_title'] : ''; ?>"/>
		</div>
		
		<div class="form-group">
			<label for="url" class="form-label">Project URL (Optional) </label>
			<input type="text" class="form-control" name="url" value="<?php echo !empty($detail['portfolio_url']) ? $detail['portfolio_url'] : ''; ?>"/>
		</div>
		
		<div class="form-group">
			<label for="category" class="form-label">Category </label>
			<select name="category" class="form-control fetch-category" data-level="1" data-target="#category_subchild_id" id="category_id">
				<option value="">-Select-</option>
				<?php print_select_option($category, 'category_id', 'category_name', (!empty($detail['category_id']) ? $detail['category_id'] : '')); ?>
			</select>
		</div>
		
		<div class="form-group">
			<label for="sub_category" class="form-label">Sub Category </label>
			<select name="sub_category" class="form-control" data-level="2" data-target="#category_subchild_level_3_id" id="category_subchild_id">
				<option value="">-Select-</option>
				<?php print_select_option($sub_category, 'category_subchild_id', 'category_subchild_name', (!empty($detail['category_subchild_id']) ? $detail['category_subchild_id'] : '')); ?>
			</select>
		</div>
		
		<div class="form-group">
			<label for="complete_date" class="form-label">Completion Date (Optional) </label>
			<input type="date" class="form-control" name="complete_date" value="<?php echo !empty($detail['portfolio_complete_date']) ? $detail['portfolio_complete_date'] : ''; ?>"/>
		</div>
		
		<div class="form-group">
			<label for="description" class="form-label">Project Overview </label>
			<textarea class="form-control" name="description"><?php echo !empty($detail['portfolio_description']) ? $detail['portfolio_description'] : ''; ?></textarea>
		</div>	

		<?php 
		$files=json_decode($detail['portfolio_image']);	
		if(!empty($detail['portfolio_image']) && file_exists(LC_PATH.'member-portfolio/'.$files->file)){ 
		
		?>
		<div class="form-group">
			<label>Previous Image </label>
			<div class="image-wrapper" id="previous_image">
			<button type="button" class="close" onclick="removeByID('previous_image')"><span aria-hidden="true">&times;</span></button>
			<img src="<?php echo UPLOAD_HTTP_PATH.'member-portfolio/'.$files->file; ?>" class="img-rounded" alt="" width="210">
			<input type="hidden" name="pre_portfolio_image" value="<?php echo $detail['portfolio_image'];?>"/>
		</div>
		</div>
		<?php } ?>
		
		<?php $this->load->view('upload_file_component', array('input_name' => 'portfolio_image', 'url' => base_url('member/upload_file').'?type=portfolio')); ?>
		


		<button type="submit" class="btn btn-site"><?php echo !empty($detail) ? 'Save' : 'Add'; ?></button>

	 

</form>
</div>

<script>
function get_category(id, target, level){
	var type = '';
	switch(target){
		case '#category_subchild_id':
		type = 'category_subchild';
		break;
		
		case '#category_subchild_level_3_id':
		type = 'category_subchild_level_3';
		break;
		
		case '#category_subchild_level_4_id':
		type = 'category_subchild_level_4';
		break;
		
	}
	
	hide_and_reset_all_child(level);
	
	$.get('<?php echo base_url('proposal/get_category');?>?type='+type+'&id='+id, function(res){
		if(res == 0){
			$(target).html('<option value=""> - Select -</option>');
			show_direct_child(level);
		}else{
			$(target).html(res);
			show_direct_child(level);
			
		}
		
		
		
	});
}

function hide_and_reset_all_child(level){
	$("[data-level]").filter(function() {
		return $(this).data('level') > level;
	}).parent().hide();
	
	$("[data-level]").filter(function() {
		return $(this).data('level') > level;
	}).html('<option value="">-Select-</option>');
}

function hide_all_child(level){
	$("[data-level]").filter(function() {
		return $(this).data('level') > level;
	}).parent().hide();
}

function show_direct_child(level){
	var direct_child = parseInt(level)+1;
	$("[data-level]").filter(function() {
		return $(this).data('level') == direct_child;
	}).parent().show();
}


function reset_select(ele){
	$(ele).html('<option value="">-Select-</option>');
}


function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

$('.fetch-category').change(function(){
	var val = $(this).val();
	var target = $(this).data('target');
	var level = $(this).data('level');
	if(!val){
		reset_select(target);
		return false;
	}
	
	get_category(val, target, level);
	
});

</script>