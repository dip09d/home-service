<?php if($page == 'add'){ ?>
<div class="modal-header">
	<h5 class="modal-title"><?php echo $title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
              
			
				<?php /* ?><div class="form-group">
                  <label for="language_preference_name">Language Preference</label>
                  <input type="text" class="form-control reset_field" id="language_preference_name" name="language_preference_name" autocomplete="off">
                </div>
               
			   <div class="form-group">
                  <label for="language_preference_info">Language Preference info</label>
                   <textarea class="form-control" name="language_preference_info" autocomplete="off" id="language_preference_info"></textarea>
                </div><?php */ ?>

				<?php
					$lang = get_lang();
					foreach($lang as $k => $v){ ?>
						<div class="form-group">
							<label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
							<input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[language_preference_name][<?php echo $v; ?>]" autocomplete="off">
						</div>

						<div class="form-group">
							<label for="name_<?php echo $v;?>">info (<?php echo $v;?>)</label>
							<textarea class="form-control reset_field" id="language_preference_info_<?php echo $v;?>" name="lang[language_preference_info][<?php echo $v; ?>]" autocomplete="off"></textarea>
						</div>
				<?php } ?>
				
				<div class="form-group">
                  <label for="language_preference_ord">Display Order</label>
                  <input type="text" class="form-control reset_field" id="language_preference_ord" name="language_preference_ord" autocomplete="off">
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
			form.find('.reset_field').val('');
		}		
		
	}
}

</script>
<?php } ?>

<?php if($page == 'edit'){ ?>
<div class="modal-header">
<h5 class="modal-title"><?php echo $title;?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
			  <input type="hidden" name="ID" value="<?php echo $ID?>"/>
	
				<?php /* ?><div class="form-group">
                  <label for="language_preference_name">Language Preference</label>
                  <input type="text" class="form-control reset_field" id="language_preference_name" name="language_preference_name" autocomplete="off" value="<?php echo !empty($detail['language_preference_name']) ? $detail['language_preference_name'] : '';?>">
                </div>
				
				<div class="form-group">
                  <label for="language_preference_info">Language Preference info</label>
                   <textarea class="form-control" name="language_preference_info" autocomplete="off" id="language_preference_info"><?php echo !empty($detail['language_preference_info']) ? $detail['language_preference_info'] : '';?></textarea>
                </div><?php */ ?>

				<?php
					$lang = get_lang();
					foreach($lang as $k => $v){ ?>
						<div class="form-group">
							<label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
							<input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[language_preference_name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['language_preference_name'][$v]) ? $detail['lang']['language_preference_name'][$v] : '';?>">
						</div>
						
						<div class="form-group">
							<label for="name_<?php echo $v;?>">Info (<?php echo $v;?>)</label>
							<textarea  class="form-control reset_field" id="language_preference_info_<?php echo $v;?>" name="lang[language_preference_info][<?php echo $v; ?>]" autocomplete="off" ><?php echo !empty($detail['lang']['language_preference_info'][$v]) ? $detail['lang']['language_preference_info'][$v] : '';?></textarea>
						</div>
				<?php } ?>
				
				<div class="form-group">
                  <label for="language_preference_ord">Display Order</label>
                  <input type="text" class="form-control reset_field" id="language_preference_ord" name="language_preference_ord" autocomplete="off" value="<?php echo !empty($detail['language_preference_ord']) ? $detail['language_preference_ord'] : '';?>">
                </div>
				
			   <div class="form-group">
			   <label class="form-label">Status</label>
                <div class="radio-inline">
					<input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
					<label for="status_1">Active</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['language_preference_status'] == '0' ?  'checked' : ''; ?>>
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