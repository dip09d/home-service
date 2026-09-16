<?php if($page == 'add'){ ?>
<div class="modal-header">
	<h5 class="modal-title"><?php echo $title;?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
				
				<?php if($cms_help_id > 0){ ?>
				<div class="form-group">
                  <label for="parent_id">CMS Help Category</label>
				  <input type="hidden" name="cms_help_id" value="<?php echo $cms_help_id; ?>"/>
                  <input type="text" class="form-control" autocomplete="off" value="<?php echo !empty($cms_help_detail['title']) ? $cms_help_detail['title'] : 'No Name'; ?>" readonly />
                </div>
				<?php } ?>
				
				<div class="form-group">
                  <label for="slug">Article Slug</label>
                  <input type="text" class="form-control reset_field" id="slug" name="slug" autocomplete="off">
                </div>
				
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="title_<?php echo $v;?>">Title (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="title_<?php echo $v;?>" name="lang[title][<?php echo $v; ?>]" autocomplete="off">
                </div>
				<?php } ?>
				
				<?php foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="content_<?php echo $v;?>">Description (<?php echo $v;?>)</label>
				  <div data-error-wrapper="lang[description][<?php echo $v; ?>]">
                  <textarea class="form-control reset_field" id="content_<?php echo $v;?>" name="lang[description][<?php echo $v; ?>]" autocomplete="off"></textarea>
				  </div>
                </div>
				
				<?php echo get_editor('content_'.$v);?>
				<?php } ?>
             
			   <div class="form-group">
			   <p><b>Status</b></p>
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

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}

function submitForm(form, evt){
	evt.preventDefault();
	CKupdate();
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
			  <input type="hidden" name="ID" value="<?php echo $ID; ?>"/>
         			
				<div class="form-group">
                  <label for="slug">Article Slug</label>
                  <input type="text" class="form-control reset_field" id="slug" name="slug" autocomplete="off" value="<?php echo !empty($detail['slug']) ? $detail['slug'] : '';?>" readonly />
                </div>
				
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="title_<?php echo $v;?>">Title (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="title_<?php echo $v;?>" name="lang[title][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['title'][$v]) ? $detail['lang']['title'][$v] : '';?>" />
                </div>
				<?php } ?>
				
				<?php foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="content_<?php echo $v;?>">Description (<?php echo $v;?>)</label>
				  <div data-error-wrapper="lang[description][<?php echo $v; ?>]">
                  <textarea class="form-control reset_field" id="content_<?php echo $v;?>" name="lang[description][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['description'][$v]) ? $detail['lang']['description'][$v] : '';?></textarea>
				  </div>
                </div>
				
				<?php echo get_editor('content_'.$v);?>
				
				<?php } ?>
				
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

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}

function submitForm(form, evt){
	evt.preventDefault();
	CKupdate();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>
<?php } ?>