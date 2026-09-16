
<?php //get_print($detail, false); ?>	
<ul class="list-group mb-3">
    <?php foreach($detail['member_education'] as $k => $v){ ?>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-8">
                <h4>
                <?php echo $v['education_degree'];?>
                <?php echo strlen($v['education_degree']) > 0 ? ', ': '';?>	
                <?php echo $v['education_area_of_study'];?> 
                <?php echo strlen($v['education_area_of_study']) > 0 ? ' | ': '';?>	
                <?php echo $v['education_school'];?></h4>
                <div><?php echo $v['education_from_year'].' - '.$v['education_end_year'];?> </div>
                <div><?php echo $v['education_description'];?></div>
            </div>
            <div class="col-sm-4 text-right">
                <a href="<?php echo JS_VOID;?>" onclick="edit_data('<?php echo $v['education_id'];?>')" title="Edit" class="btn btn-sm btn-outline-success"><i class="icon-feather-edit"></i></a>
                &nbsp;
                <a href="<?php echo JS_VOID;?>" onclick="delete_data('<?php echo $v['education_id'];?>')" title="Remove" class="btn btn-sm btn-outline-danger"><i class="icon-feather-trash"></i></a>
            </div>
        </div>
    </li>
    <?php } ?>
</ul>
			
<button class="btn btn-site" onclick="addEducation()"> + Add More</button>
			
			
		
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

function addEducation(){
	var url = '<?php echo base_url("member/ajax_modal?page=$page&ID=$member_id");?>';
	Modal.openURL({
		title: 'Add Education',
		url: url,
	});
}


function edit_data(education_id){
	var url = '<?php echo base_url("member/ajax_modal?page=$page&ID=$member_id");?>&education_id='+education_id;
	Modal.openURL({
		title: 'Edit Education',
		url: url,
	});
}

function delete_data(education_id){
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		$.ajax({
			url: '<?php echo base_url("member/delete_data")?>',
			type: 'POST',
			dataType: 'JSON',
			data: {formtype: 'education', Mkey: '<?php echo $member_id;?>', Okey: education_id},
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