<?php if($page == 'add'){ ?>
<div class="modal-header">
       <h4 class="modal-title"><?php echo $title;?></h4>
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>
<div class="modal-body">
<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
        <div class="form-group">
          <label for="group_name" class="form-label">Group Name</label>
          <input type="text" class="form-control" id="group_name" name="group_name" autocomplete="off">
        </div>
        
        <div class="form-group">
          <label for="group_key" class="form-label">Group Key</label>
          <input type="text" class="form-control" id="group_key" name="group_key" autocomplete="off">
        </div>
    
      <div class="form-group">
       <label class="form-label">Status</label>
        <div class="radio-inline">
            <input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
            <label for="status_1">Active</label> 
        </div>
         <div class="radio-inline">
              <input type="radio" name="status" value="0" class="magic-radio" id="status_0">
              <label for="status_0">Inactive</label> 
          </div>
      </div>
      
       <div class="form-group">
        <div>
         <input type="checkbox" name="add_more" value="1" class="magic-checkbox" id="add_more">
          <label for="add_more">Add more record</label>
        </div>
      </div>
        <button type="submit" class="btn btn-site">Add</button>

</form>
</div>

<script>

init_plugin();

function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd){
		if(res.cmd == 'reload'){
			location.reload();
		}else if(res.cmd == 'reset_form'){
			var form = $('#add_form');
			form.find('[name="title"]').val('');
			form.find('[name="setting_key"]').val('');
			form.find('[name="setting_value"]').val('');
		}		
		
	}
}

</script>
<?php } ?>

<?php if($page == 'edit'){ ?>
<div class="modal-header">
	<h4 class="modal-title"><?php echo $title;?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
			  <input type="hidden" name="ID" value="<?php echo $ID?>"/>
				
				<div class="form-group">
                  <label for="group_name" class="form-label">Group Name</label>
                  <input type="text" class="form-control" id="group_name" name="group_name" autocomplete="off" value="<?php echo !empty($detail['group_name']) ? $detail['group_name'] : '';?>">
                </div>
				
				<div class="form-group">
                  <label for="group_key" class="form-label">Group Key</label>
                  <input type="text" class="form-control" id="group_key" name="group_key" autocomplete="off" value="<?php echo !empty($detail['group_key']) ? $detail['group_key'] : '';?>" readonly>
                </div>
				
				<div class="form-group">
					<label class="form-label">Status</label>
					<div class="radio-inline">
						<input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
						<label for="status_1">Active</label> 
					</div>
					 <div class="radio-inline">
						  <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['status'] == '0' ?  'checked' : ''; ?>>
						  <label for="status_0">Inactive</label> 
					  </div>
				</div>
				

                <button type="submit" class="btn btn-site">Save</button>

        </form>
</div>

<script>

init_plugin();

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
<?php } ?>