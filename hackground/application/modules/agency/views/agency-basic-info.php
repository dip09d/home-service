
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
<input type="hidden" name="ID" value="<?php echo $agency_id;?>"/>
<input type="hidden" name="page" value="<?php echo $page;?>"/>

    <div class="row">

    <div class="col-sm">
        <div class="form-group">
            <label for="category_id">Agency Name</label>
            <input type="text" class="form-control" name="agency[agency_name]" value="<?php echo $detail['agency_name'];?>"/>
        </div>
        <div class="form-group">
            <label for="agency_member_name">Full Name</label>
            <input type="text" class="form-control" name="agency[agency_member_name]" value="<?php echo $detail['agency_member_name'];?>"/>
        </div>
        
        <button type="submit" class="btn btn-site">Save</button>
    </div>
    </div>
            
            
            
             <?php /*
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <select class="form-control" name="agency_basic[agency_nationality]">
                    <option value="">-Select-</option>
                    <?php print_select_option(get_all_country(), 'country_code', 'country_name', (!empty($detail['agency_basic']['agency_nationality']) ? $detail['agency_basic']['agency_nationality']['code'] : '')); ?>
                </select>
            </div>
            
           
            <div class="form-group">
                <div>
                    <input type="hidden" name="agency_basic[hide_photo]" value="0" />
                    <input type="checkbox" name="agency_basic[hide_photo]" value="1" class="magic-checkbox" id="hide_photo" <?php echo (!empty($detail['agency_basic']['hide_photo']) && $detail['agency_basic']['hide_photo'] == '1') ? 'checked' : ''; ?>>
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