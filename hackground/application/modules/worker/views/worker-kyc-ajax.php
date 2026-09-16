<div class="modal-body">
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
	<input type="hidden" name="ID" value="<?php echo $ID;?>"/>
	<input type="hidden" name="kyc_id" value="<?php echo $kyc_id;?>"/>
	<input type="hidden" name="page" value="<?php echo $page;?>"/>

		<?php //get_print($detail, false); ?>
		<div class="form-group">
			<label class="form-label">KYC Type</label>
			<div class="radio-inline">
				<input type="radio" name="kyc_type" value="A" class="magic-radio" id="kyc_type_A" checked>
				<label for="kyc_type_A">Aadhar Card</label>
			</div>
			<div class="radio-inline">
				<input type="radio" name="kyc_type" value="P" class="magic-radio" id="kyc_type_P" <?php echo ($detail && $detail['kyc_type'] == 'P' ?  'checked' : ''); ?>>
				<label for="kyc_type_P">PAN Card</label>
			</div>
		</div>
		<div class="form-group">
			<label for="title" class="form-label kyc_type_A">Aadhar Card Number </label>
			<label for="title" class="form-label kyc_type_P" style="display:none">PAN Card Number </label>
			<input type="text" class="form-control" name="title" value="<?php echo !empty($detail['kyc_title']) ? $detail['kyc_title'] : ''; ?>"/>
		</div>
		
		
		
		<?php 
		if($detail){
			$files=json_decode($detail['kyc_image_front']);	
			if(!empty($detail['kyc_image_front']) && file_exists(LC_PATH.'worker-kyc/'.$files->file)){ 
		
		?>
		<div class="form-group">
			<label>Previous Image </label>
			<div class="image-wrapper" id="previous_image">
				<button type="button" class="close" onclick="removeByID('previous_image')"><span aria-hidden="true">&times;</span></button>
				<img src="<?php echo UPLOAD_HTTP_PATH.'worker-kyc/'.$files->file; ?>" class="img-rounded" alt="" width="210">
				<input type="hidden" name="pre_kyc_image_front" value='<?php echo $detail['kyc_image_front'];?>'/>
			</div>
		</div>
		<?php 
			}
		}
		?>
		
		<?php $this->load->view('upload_file_component', array('label'=>'Front','input_name' => 'kyc_image_front', 'url' => base_url('worker/upload_file').'?type=kyc')); ?>
		
		<?php 
		if($detail){
		$files=json_decode($detail['kyc_image_back']);	
		if(!empty($detail['kyc_image_back']) && file_exists(LC_PATH.'worker-kyc/'.$files->file)){ 
		
		?>
		<div class="kyc_type_A">
			<div class="form-group">
				<label>Previous Image </label>
				<div class="image-wrapper" id="previous_image">
					<button type="button" class="close" onclick="removeByID('previous_image')"><span aria-hidden="true">&times;</span></button>
					<img src="<?php echo UPLOAD_HTTP_PATH.'worker-kyc/'.$files->file; ?>" class="img-rounded" alt="" width="210">
					<input type="hidden" name="pre_kyc_image_back" value='<?php echo $detail['kyc_image_back'];?>'/>
				</div>
			</div>
		</div>
		<?php } }?>
		
		<?php $this->load->view('upload_file_component', array('label'=>'Back','input_name' => 'kyc_image_back', 'url' => base_url('worker/upload_file').'?type=kyc')); ?>
		


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

$('input[name="kyc_type"]').on('change', function () {
    let kycType = $(this).val();
	if(kycType=='P'){
		$('.kyc_type_A').hide();
		$('.kyc_type_P').show();
	}else{
		$('.kyc_type_A').show();
		$('.kyc_type_P').hide();
	}
    console.log(kycType); // A or P
});

</script>