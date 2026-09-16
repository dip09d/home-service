<div class="modal-body">
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
	<input type="hidden" name="ID" value="<?php echo $ID;?>"/>
	<input type="hidden" name="bank_id" value="<?php echo $bank_id;?>"/>
	<input type="hidden" name="page" value="<?php echo $page;?>"/>

		<?php //get_print($detail, false); ?>
		
		<div class="form-group">
			<label for="name" class="form-label ">Account Holder Name</label>
			<input type="text" class="form-control" name="name" value="<?php echo !empty($detail['name']) ? $detail['name'] : ''; ?>"/>
		</div>
		<div class="form-group">
			<label for="bank_name" class="form-label ">Bank Name </label>
			<input type="text" class="form-control" name="bank_name" value="<?php echo !empty($detail['bank_name']) ? $detail['bank_name'] : ''; ?>"/>
		</div>
		<div class="form-group">
			<label for="account_number" class="form-label ">Account Number </label>
			<input type="text" class="form-control" name="account_number" value="<?php echo !empty($detail['account_number']) ? $detail['account_number'] : ''; ?>"/>
		</div>
		<div class="form-group">
			<label for="ifsc" class="form-label ">IFSC Code </label>
			<input type="text" class="form-control" name="ifsc" value="<?php echo !empty($detail['ifsc']) ? $detail['ifsc'] : ''; ?>"/>
		</div>
		<div class="form-group">
			<label for="upi_id" class="form-label ">UPI ID (optional)</label>
			<input type="text" class="form-control" name="upi_id" value="<?php echo !empty($detail['upi_id']) ? $detail['upi_id'] : ''; ?>"/>
		</div>
		
		
		
		<?php 
		if($detail){
			$files=json_decode($detail['bank_image_front']);	
			if(!empty($detail['bank_image_front']) && file_exists(LC_PATH.'worker-bank/'.$files->file)){ 
		
		?>
		<div class="form-group">
			<label>Previous Image </label>
			<div class="image-wrapper" id="previous_image">
				<button type="button" class="close" onclick="removeByID('previous_image')"><span aria-hidden="true">&times;</span></button>
				<img src="<?php echo UPLOAD_HTTP_PATH.'worker-bank/'.$files->file; ?>" class="img-rounded" alt="" width="210">
				<input type="hidden" name="pre_bank_image_front" value='<?php echo $detail['bank_image_front'];?>'/>
			</div>
		</div>
		<?php 
			}
		}
		?>
		
		<?php $this->load->view('upload_file_component', array('label'=>'Bank Passbook / Cancelled Cheque','input_name' => 'bank_image_front', 'url' => base_url('worker/upload_file').'?type=bank')); ?>
		
		

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

$('input[name="bank_type"]').on('change', function () {
    let bankType = $(this).val();
	if(bankType=='P'){
		$('.').hide();
		$('.bank_type_P').show();
	}else{
		$('.').show();
		$('.bank_type_P').hide();
	}
    console.log(bankType); // A or P
});

</script>