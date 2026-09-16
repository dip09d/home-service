<?php if($page == 'add'){ ?>
<div class="modal-header">
<h4 class="modal-title"><?php echo $title;?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
              
			  
				<div class="form-group">
                  <label for="title_tkey">Transaction Key</label>
                  <input type="text" class="form-control reset_field" id="title_tkey" name="title_tkey" autocomplete="off">
                </div>
				
				<div class="form-group">
                  <label for="description_tkey">Transaction Description</label>
                  <input type="text" class="form-control reset_field" id="description_tkey" name="description_tkey" autocomplete="off">
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
			form.find('.reset_field').val('');
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
                  <label for="title_tkey">Transaction Key</label>
                  <input type="text" class="form-control reset_field" id="title_tkey" name="title_tkey" autocomplete="off" value="<?php echo !empty($detail['title_tkey']) ? $detail['title_tkey'] : ''; ?>">
                </div>
				
				<div class="form-group">
                  <label for="description_tkey">Transaction Description</label>
                  <input type="text" class="form-control reset_field" id="description_tkey" name="description_tkey" autocomplete="off" value="<?php echo !empty($detail['description_tkey']) ? $detail['description_tkey'] : ''; ?>">
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