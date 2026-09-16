<div class="modal-header">
	<h4 class="modal-title"><?php echo $title;?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
			  <input type="hidden" name="booking_id" value="<?php echo $booking_id?>"/>
			  
				
				<div class="form-group">
                    <label for="worker_id" class="form-label">Assign Provider</label>
                    <select class="form-control" name="worker_id">
                        <option value="">-Select-</option>
                        <?php print_select_option($worker_list, 'worker_id', 'worker_name', ''); ?>
                    </select>
				 
				</div>
				
			  
                <button type="submit" class="btn btn-site">Save</button>
        </form>
</div>

<script>

init_plugin();
$('.blog_tags').tagsinput({
    maxTags: 10
});
function syncEditors() {
    if (typeof CKEDITOR !== 'undefined') {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }
}
function submitForm(form, evt){
    syncEditors();
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>
