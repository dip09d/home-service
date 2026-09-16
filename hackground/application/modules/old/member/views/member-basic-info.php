
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
            
            <?php /*
            <div class="form-group">
                <label for="category_id">DOB</label>
                <input type="text" class="form-control" name="member_basic[dob]" value="<?php echo !empty($detail['member_basic']['dob']) ? $detail['member_basic']['dob'] : '';?>"/>
            </div>
            
            <div class="form-group">
                <label for="category_id">Gender</label>
                <select class="form-control" name="member_basic[gender]">
                    <option value="">-Select-</option>
                    <?php print_select_option_assoc(array('M' => 'Male', 'F' => 'Female'), (!empty($detail['member_basic']['gender']) ? $detail['member_basic']['gender'] : '')); ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <select class="form-control" name="member_basic[member_nationality]">
                    <option value="">-Select-</option>
                    <?php print_select_option(get_all_country(), 'country_code', 'country_name', (!empty($detail['member_basic']['member_nationality']) ? $detail['member_basic']['member_nationality']['code'] : '')); ?>
                </select>
            </div>
            
            
            <div class="form-group">
                <div>
                    <input type="hidden" name="member_basic[hide_photo]" value="0" />
                    <input type="checkbox" name="member_basic[hide_photo]" value="1" class="magic-checkbox" id="hide_photo" <?php echo (!empty($detail['member_basic']['hide_photo']) && $detail['member_basic']['hide_photo'] == '1') ? 'checked' : ''; ?>>
                    <label for="hide_photo">Hide name and photo on Ads</label>
                </div>
            </div>
            */?>
            
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