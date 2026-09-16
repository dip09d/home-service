
			<?php //get_print($detail, false); ?>	
			<ul class="list-group mb-3">
				<?php foreach($detail['worker_kyc'] as $k => $v){ ?>
				<li class="list-group-item">
					<div class="row">
						<div class="col">
							<h4><?php echo $v['kyc_type']=='P' ? 'PAN' : 'Aadhar';?> No: <?php echo $v['kyc_title'];?> </h4>							
							<p class="mb-2"><span class="badge badge-success"><?php echo $v['kyc_type']=='P' ? 'PAN' : 'Aadhar';?></span> &nbsp; <i class="icon-feather-calendar"></i> <?php echo !empty($v['kyc_complete_date']) ? ' '.date('d M,Y', strtotime($v['kyc_complete_date'])) : ''; ?></p>							
						</div>
						<div class="col-auto">
							<a href="<?php echo JS_VOID;?>" onclick="edit_data('<?php echo $v['kyc_id'];?>')" title="Edit" class="btn btn-sm btn-outline-success"><i class="icon-feather-edit"></i></a>
							&nbsp;
							<a href="<?php echo JS_VOID;?>" onclick="delete_data('<?php echo $v['kyc_id'];?>')" title="Remove" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i></a>
						</div>
					</div>
                    <?php if($v['kyc_image_front']){
                        $file=json_decode($v['kyc_image_front']);
                        ?>
                    <p class="mb-1"><b>Front:</b> <a href="<?php echo UPLOAD_HTTP_PATH.'worker-kyc/'.$file->file;?>" target="_blank" title="Front"><i class="icon-feather-file green <?php echo ICON_SIZE;?>"></i> </a></p>	
                    <?php }?>
                    <?php if($v['kyc_image_back']){
                          $file=json_decode($v['kyc_image_back']);?>

                    <p class="mb-1"><b>Back:</b> <a href="<?php echo UPLOAD_HTTP_PATH.'worker-kyc/'.$file->file;?>" target="_blank" title="Back"><i class="icon-feather-file green <?php echo ICON_SIZE;?>"></i></a></p>	
                    <?php }?>

				</li>
				<?php } ?>
			</ul>
			
			<button class="btn btn-site" onclick="addKYC()"> + Add More</button>
			
		

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

function addKYC(){
	var url = '<?php echo base_url("worker/ajax_modal?page=$page&ID=$worker_id");?>';
	Modal.openURL({
		title: 'Add KYC',
		url: url,
	});
}


function edit_data(kyc_id){
	var url = '<?php echo base_url("worker/ajax_modal?page=$page&ID=$worker_id");?>&kyc_id='+kyc_id;
	Modal.openURL({
		title: 'Edit KYC',
		url: url,
	});
}

function delete_data(kyc_id){
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		$.ajax({
			url: '<?php echo base_url("worker/delete_data")?>',
			type: 'POST',
			dataType: 'JSON',
			data: {formtype: 'kyc', Mkey: '<?php echo $worker_id;?>', Okey: kyc_id},
			success: function(res){
				if(res.cmd && res.cmd == 'reload'){
					location.reload();
				}
			}
		});
	}
	return false;
}

</script>