
<?php //get_print($detail, false); ?>	
<ul class="list-group mb-3">
    <?php foreach($detail['member_employment'] as $k => $v){ ?>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-8">
                <h4><?php echo $v['employment_title'];?> | <?php echo $v['employment_company'];?></h4>
                <p><i class="icon-feather-map-pin"></i> <?php echo $v['employment_city'].', '.$v['employment_country']['name'];?> &nbsp;  <i class="icon-feather-calendar"></i> <?php echo date('M Y', strtotime($v['employment_from']));?> - <?php echo $v['employment_is_working_on'] == '1' ? 'Present' : (!empty($v['employment_to']) ? date('M Y', strtotime($v['employment_to'])) : ''); ?></p>
                <p class="mb-0"><?php echo $v['employment_description'];?></p>
            </div>
            <div class="col-sm-4 text-right">
                <a href="<?php echo JS_VOID;?>" onclick="edit_data('<?php echo $v['employment_id'];?>')" title="Edit" class="btn btn-sm btn-outline-success"><i class="icon-feather-edit"></i></a>
                &nbsp;
                <a href="<?php echo JS_VOID;?>" onclick="delete_data('<?php echo $v['employment_id'];?>')" title="Remove" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i></a>
            </div>
        </div>
    </li>
    <?php } ?>
</ul>
			
<button class="btn btn-site" onclick="addEmployment()"> + Add More</button>
			
		
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

function addEmployment(){
	var url = '<?php echo base_url("member/ajax_modal?page=$page&ID=$member_id");?>';
	Modal.openURL({
		title: 'Add Employment',
		url: url,
	});
}


function edit_data(employment_id){
	var url = '<?php echo base_url("member/ajax_modal?page=$page&ID=$member_id");?>&employment_id='+employment_id;
	Modal.openURL({
		title: 'Edit Employment History',
		url: url,
	});
}

function delete_data(employment_id){
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		$.ajax({
			url: '<?php echo base_url("member/delete_data")?>',
			type: 'POST',
			dataType: 'JSON',
			data: {formtype: 'employment', Mkey: '<?php echo $member_id;?>', Okey: employment_id},
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