
			<?php //get_print($detail, false); ?>	
			<ul class="list-group mb-3">
				<?php foreach($detail['worker_bank'] as $k => $v){ ?>
				<li class="list-group-item">
					<div class="row">
						<div class="col">
							<h4><?php echo $v['bank_name'];?> : <?php echo $v['account_number'];?> </h4>							
							<p class="mb-2"><b>IFSC:</b><?php echo $v['ifsc'];?> &nbsp; <b>Account Holder Name:</b> <?php echo $v['name']; ?></p>							
							<p class="mb-2">UPI:<span class="badge badge-success"><?php echo $v['upi_id'];?></span> &nbsp; <i class="icon-feather-calendar"></i> <?php echo !empty($v['bank_complete_date']) ? ' '.date('d M,Y', strtotime($v['bank_complete_date'])) : ''; ?></p>							
						</div>
						<div class="col-auto">
							<a href="<?php echo JS_VOID;?>" onclick="edit_data('<?php echo $v['bank_id'];?>')" title="Edit" class="btn btn-sm btn-outline-success"><i class="icon-feather-edit"></i></a>
							&nbsp;
							<a href="<?php echo JS_VOID;?>" onclick="delete_data('<?php echo $v['bank_id'];?>')" title="Remove" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i></a>
						</div>
					</div>
                    <?php if($v['bank_image_front']){
                        $file=json_decode($v['bank_image_front']);
                        ?>
                    <p class="mb-1"><b>Bank Passbook / Cancelled Cheque :</b> <a href="<?php echo UPLOAD_HTTP_PATH.'worker-bank/'.$file->file;?>" target="_blank" title="Front"><i class="icon-feather-file green <?php echo ICON_SIZE;?>"></i> </a></p>	
                    <?php }?>
             

				</li>
				<?php } ?>
			</ul>
			
			<button class="btn btn-site" onclick="addBank()"> + Add More</button>
			
		

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

function addBank(){
	var url = '<?php echo base_url("worker/ajax_modal?page=$page&ID=$worker_id");?>';
	Modal.openURL({
		title: 'Add Bank',
		url: url,
	});
}


function edit_data(bank_id){
	var url = '<?php echo base_url("worker/ajax_modal?page=$page&ID=$worker_id");?>&bank_id='+bank_id;
	Modal.openURL({
		title: 'Edit Bank',
		url: url,
	});
}

function delete_data(bank_id){
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		$.ajax({
			url: '<?php echo base_url("worker/delete_data")?>',
			type: 'POST',
			dataType: 'JSON',
			data: {formtype: 'bank', Mkey: '<?php echo $worker_id;?>', Okey: bank_id},
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