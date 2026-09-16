
<?php //get_print($detail, false); ?>	
<ul class="list-group mb-3">
    <?php foreach($detail['member_address'] as $k => $v){ ?>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-8">
                <h4><?php echo $v['member_address_1'];?><?php echo ($v['member_address_2'] ? ','.$v['member_address_2']:'');?> | <?php echo $v['member_pincode'];?></h4>
                <p><i class="icon-feather-tag"></i> <?php echo $v['member_landmark'];?> &nbsp;  <i class="icon-feather-map-pin"></i> <?php echo $v['member_city'];?> ,<?php echo $v['member_state']; ?></p>
                
            </div>
            <div class="col-sm-4 text-right">
                <a href="<?php echo JS_VOID;?>" onclick="edit_data('<?php echo $v['member_address_id'];?>')" title="Edit" class="btn btn-sm btn-outline-success"><i class="icon-feather-edit"></i></a>
                &nbsp;
                <a href="<?php echo JS_VOID;?>" onclick="delete_data('<?php echo $v['member_address_id'];?>')" title="Remove" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i></a>
            </div>
        </div>
    </li>
    <?php } ?>
</ul>
			
<button class="btn btn-site" onclick="addAddress()"> + Add More</button>
			
		
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

function addAddress(){
	var url = '<?php echo base_url("member/ajax_modal?page=$page&ID=$member_id");?>';
	Modal.openURL({
		title: 'Add Address',
		url: url,
	});
}


function edit_data(member_address_id){
	var url = '<?php echo base_url("member/ajax_modal?page=$page&ID=$member_id");?>&member_address_id='+member_address_id;
	Modal.openURL({
		title: 'Edit Address History',
		url: url,
	});
}

function delete_data(member_address_id){
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		$.ajax({
			url: '<?php echo base_url("member/delete_data")?>',
			type: 'POST',
			dataType: 'JSON',
			data: {formtype: 'address', Mkey: '<?php echo $member_id;?>', Okey: member_address_id},
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