<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h5 class="modal-title"><?php echo $title;?></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <?php if($category_subchild_id){ ?>
    <div class="form-group">
      <label for="category">Category</label>
      <input type="hidden" name="category_subchild_id" value="<?php echo $category_subchild_id;?>"/>
      <input type="text" class="form-control" id="category" autocomplete="off" value="<?php echo $category_name;?>" readonly />
    </div>
    <?php }else{ ?>
    <?php } ?>
    <?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[category_subchild_name][<?php echo $v; ?>]" autocomplete="off">
    </div>
    <?php } ?>
    <?php $this->load->view('upload_file_component', array('input_name' => 'category_subchild_icon', 'url' => base_url('category/upload_file'))); ?>
    <div class="form-group">
      <label for="category_subchild_key">Category Key </label>
      <input type="text" class="form-control reset_field" id="category_subchild_key" name="category_subchild_key" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="category_subchild_order">Display Order </label>
      <input type="text" class="form-control reset_field" id="category_subchild_order" name="category_subchild_order" autocomplete="off">
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
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID?>"/>
    <?php
				
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[category_subchild_name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['category_subchild_name'][$v]) ? $detail['lang']['category_subchild_name'][$v] : '';?>">
    </div>
    <?php } ?>
    <?php if(!empty($detail['category_subchild_icon']) && file_exists(LC_PATH.'category_icons/'.$detail['category_subchild_icon'])){ ?>
    <div class="form-group">
      <label>Previous Image </label>
      <div class="image-wrapper" id="previous_image">
        <button type="button" class="close" onclick="removeByID('previous_image')"><span aria-hidden="true">&times;</span></button>
        <img src="<?php echo UPLOAD_HTTP_PATH.'category_icons/'.$detail['category_subchild_icon']; ?>" class="img-rounded" alt="" width="210">
        <input type="hidden" name="category_subchild_icon" value="<?php echo $detail['category_subchild_icon'];?>"/>
      </div>
    </div>
    <?php } ?>
    <?php $this->load->view('upload_file_component', array('input_name' => 'category_subchild_icon', 'url' => base_url('category/upload_file'))); ?>
    <div class="form-group">
      <label for="category_subchild_key">Category Key </label>
      <input type="text" class="form-control reset_field" id="category_subchild_key" name="category_subchild_key" autocomplete="off"  value="<?php echo !empty($detail['category_subchild_key']) ? $detail['category_subchild_key'] : '';?>">
      <input type="hidden" name="category_subchild_key_old" value="<?php echo !empty($detail['category_subchild_key']) ? $detail['category_subchild_key'] : '';?>"/>
    </div>
    <div class="form-group">
      <label for="category_subchild_order">Display Order </label>
      <input type="text" class="form-control reset_field" id="category_subchild_order" name="category_subchild_order" autocomplete="off" value="<?php echo !empty($detail['category_subchild_order']) ? $detail['category_subchild_order'] : '';?>">
    </div>
    <div class="form-group">
      <label class="form-label">Status</label>
      <div class="radio-inline">
        <input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
        <label for="status_1">Active</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['category_subchild_status'] == '0' ?  'checked' : ''; ?>>
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
