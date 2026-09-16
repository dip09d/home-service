
			<?php //get_print($detail, false); ?>	
			<ul class="list-group mb-3">
				<?php foreach($detail['member_portfolio'] as $k => $v){ ?>
				<li class="list-group-item">
					<div class="row">
						<div class="col">
							<h4><?php echo $v['portfolio_title'];?> </h4>							
							<p class="mb-2"><span class="badge badge-success"><?php echo !empty($v['sub_category']['name']) ? $v['sub_category']['name'] : '';?></span> &nbsp; <i class="icon-feather-calendar"></i> <?php echo !empty($v['portfolio_complete_date']) ? ' '.date('d M,Y', strtotime($v['portfolio_complete_date'])) : ''; ?></p>							
						</div>
						<div class="col-auto">
							<a href="<?php echo JS_VOID;?>" onclick="edit_data('<?php echo $v['portfolio_id'];?>')" title="Edit" class="btn btn-sm btn-outline-success"><i class="icon-feather-edit"></i></a>
							&nbsp;
							<a href="<?php echo JS_VOID;?>" onclick="delete_data('<?php echo $v['portfolio_id'];?>')" title="Remove" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i></a>
						</div>
					</div>
                    <div class="mt-2"><?php echo $v['portfolio_description'];?></div>
				</li>
				<?php } ?>
			</ul>
			
			<button class="btn btn-site" onclick="addPortfolio()"> + Add More</button>
			
		

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

function addPortfolio(){
	var url = '<?php echo base_url("member/ajax_modal?page=$page&ID=$member_id");?>';
	Modal.openURL({
		title: 'Add Portfolio',
		url: url,
	});
}


function edit_data(portfolio_id){
	var url = '<?php echo base_url("member/ajax_modal?page=$page&ID=$member_id");?>&portfolio_id='+portfolio_id;
	Modal.openURL({
		title: 'Edit Portfolio',
		url: url,
	});
}

function delete_data(portfolio_id){
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		$.ajax({
			url: '<?php echo base_url("member/delete_data")?>',
			type: 'POST',
			dataType: 'JSON',
			data: {formtype: 'portfolio', Mkey: '<?php echo $member_id;?>', Okey: portfolio_id},
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