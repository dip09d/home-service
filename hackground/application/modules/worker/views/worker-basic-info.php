
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
<input type="hidden" name="ID" value="<?php echo $worker_id;?>"/>
<input type="hidden" name="page" value="<?php echo $page;?>"/>

    <div class="row">
    <div class="col-sm-auto">
    <div class="form-group">
        <label for="user_logo">Profile Image</label>
        <div><img src="<?php echo $detail['worker_logo'];?>" width="150" class="img-rounded"/></div>
    </div>
    </div>
    <div class="col-sm">
        <div class="form-group">
            <label for="category_id">Name</label>
            <input type="text" class="form-control" name="worker[worker_name]" value="<?php echo $detail['worker_name'];?>"/>
        </div>
        <div class="form-group">
            <label for="worker_dob">DOB</label>
            <input type="date" class="form-control" name="worker[worker_dob]" value="<?php echo !empty($detail['worker_dob']) ? $detail['worker_dob'] : '';?>"/>
        </div>
        <div class="form-group">
            <label for="worker_religion">Religion</label>
            <select class="form-control" name="worker[worker_religion]">
                <option value="">-Select-</option>
                <?php print_select_option(get_all_religion(), 'religion_id', 'religion_name', (!empty($detail['worker_religion']) ? $detail['worker_religion'] : '')); ?>
            </select>
        </div>
        <div class="form-group">
            <label for="worker_gender">Gender</label>
            <select class="form-control" name="worker[worker_gender]">
                <option value="">-Select-</option>
                <?php print_select_option_assoc(array('M' => 'Male', 'F' => 'Female'), (!empty($detail['worker_gender']) ? $detail['worker_gender'] : '')); ?>
            </select>
        </div>
        <?php $this->load->view('upload_file_component', array('input_name' => 'worker_logo', 'label' => 'Upload Profile Image',  'url' => base_url('worker/upload_file'))); ?>
        <button type="submit" class="btn btn-site">Save</button>
    </div>
    </div>
            
            
            
             <?php /*
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <select class="form-control" name="worker_basic[worker_nationality]">
                    <option value="">-Select-</option>
                    <?php print_select_option(get_all_country(), 'country_code', 'country_name', (!empty($detail['worker_basic']['worker_nationality']) ? $detail['worker_basic']['worker_nationality']['code'] : '')); ?>
                </select>
            </div>
            
           
            <div class="form-group">
                <div>
                    <input type="hidden" name="worker_basic[hide_photo]" value="0" />
                    <input type="checkbox" name="worker_basic[hide_photo]" value="1" class="magic-checkbox" id="hide_photo" <?php echo (!empty($detail['worker_basic']['hide_photo']) && $detail['worker_basic']['hide_photo'] == '1') ? 'checked' : ''; ?>>
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