
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
<input type="hidden" name="ID" value="<?php echo $member_id;?>"/>
<input type="hidden" name="page" value="<?php echo $page;?>"/>
    <div class="row">
    <div class="col-sm-auto">
    <div class="form-group">
        <label for="user_logo">Logo</label>
        <div><img src="<?php echo $detail['member_logo'];?>" width="150" class="img-rounded"/></div>
    </div>
    </div>
    <div class="col-sm">
    <div class="form-group">
        <label for="category_id">Name</label>
        <input type="text" class="form-control" name="member[member_name]" value="<?php echo $detail['member_name'];?>"/>
        </div>
        <?php $this->load->view('upload_file_component', array('input_name' => 'member_logo', 'label' => 'Upload New Logo',  'url' => base_url('member/upload_file'))); ?>
    <button type="submit" class="btn btn-site">Save</button>
    </div>
    </div>
            
            
            
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

</script>