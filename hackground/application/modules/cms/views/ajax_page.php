<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <div class="form-group">
      <label for="content_slug">Content Slug</label>
      <input type="text" class="form-control reset_field" id="content_slug" name="content_slug" autocomplete="off">
    </div>
    <?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="title_<?php echo $v;?>">Title (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="title_<?php echo $v;?>" name="lang[title][<?php echo $v; ?>]" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="content_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
      <div data-error-wrapper="lang[content][<?php echo $v; ?>]">
        <textarea class="form-control reset_field" id="content_<?php echo $v;?>" name="lang[content][<?php echo $v; ?>]" autocomplete="off"></textarea>
      </div>
    </div>
    <?php echo get_editor('content_'.$v);?>
    <div class="form-group">
      <label for="meta_title_<?php echo $v;?>">Meta Title (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="meta_title_<?php echo $v;?>" name="lang[meta_title][<?php echo $v; ?>]" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="meta_keys_<?php echo $v;?>">Meta Keys (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="meta_keys_<?php echo $v;?>" name="lang[meta_keys][<?php echo $v; ?>]" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="meta_dscr_<?php echo $v;?>">Meta Description (<?php echo $v;?>)</label>
      <textarea class="form-control reset_field" id="meta_dscr_<?php echo $v;?>" name="lang[meta_description][<?php echo $v; ?>]" autocomplete="off"></textarea>
    </div>
    <?php } ?>
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
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID?>"/>
    <div class="form-group">
      <label for="content_slug">Content Slug</label>
      <input type="text" class="form-control reset_field" id="content_slug" name="content_slug" autocomplete="off" value="<?php echo !empty($detail['content_slug']) ? $detail['content_slug'] : '';?>" readonly />
    </div>
    <?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="title_<?php echo $v;?>">Title (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="title_<?php echo $v;?>" name="lang[title][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['title'][$v]) ? $detail['lang']['title'][$v] : '';?>" />
    </div>
    <div class="form-group">
      <label for="content_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
      <div data-error-wrapper="lang[content][<?php echo $v; ?>]">
        <textarea class="form-control reset_field" id="content_<?php echo $v;?>" name="lang[content][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['content'][$v]) ? $detail['lang']['content'][$v] : '';?></textarea>
      </div>
    </div>
    <?php echo get_editor('content_'.$v);?>
    <div class="form-group">
      <label for="meta_title_<?php echo $v;?>">Meta Title (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="meta_title_<?php echo $v;?>" name="lang[meta_title][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['meta_title'][$v]) ? $detail['lang']['meta_title'][$v] : '';?>" />
    </div>
    <div class="form-group">
      <label for="meta_keys_<?php echo $v;?>">Meta Keys (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="meta_keys_<?php echo $v;?>" name="lang[meta_keys][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['meta_keys'][$v]) ? $detail['lang']['meta_keys'][$v] : '';?>" />
    </div>
    <div class="form-group">
      <label for="meta_dscr_<?php echo $v;?>">Meta Description (<?php echo $v;?>)</label>
      <textarea class="form-control reset_field" id="meta_dscr_<?php echo $v;?>" name="lang[meta_description][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['meta_description'][$v]) ? $detail['lang']['meta_description'][$v] : '';?></textarea>
    </div>
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
