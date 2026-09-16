<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[name][<?php echo $v; ?>]" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="dscr_<?php echo $v;?>">Description (<?php echo $v;?>)</label>
      <textarea  class="form-control reset_field" id="dscr_<?php echo $v;?>" name="lang[description][<?php echo $v; ?>]" autocomplete="off"></textarea>
    </div>
    <?php } ?>
    <?php $this->load->view('upload_file_component', array('input_name' => 'slide_image', 'url' => base_url('slider/upload_file'))); ?>
    <div class="form-group">
      <label for="slide_url">Slide URL </label>
      <input type="text" class="form-control reset_field" id="slide_url" name="slide_url" autocomplete="off" />
    </div>
    <div class="form-group">
      <label for="display_order">Display Order </label>
      <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off" />
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
      <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['name'][$v]) ? $detail['lang']['name'][$v] : '';?>">
    </div>
    <div class="form-group">
      <label for="dscr_<?php echo $v;?>">Description (<?php echo $v;?>)</label>
      <textarea class="form-control reset_field" id="dscr_<?php echo $v;?>" name="lang[description][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['description'][$v]) ? $detail['lang']['description'][$v] : '';?></textarea>
    </div>
    <?php } ?>
    <?php if(!empty($detail['slide_image']) && file_exists(LC_PATH.'slider-user/'.$detail['slide_image'])){ ?>
    <div class="form-group">
      <label>Previous Image </label>
      <div class="image-wrapper" id="previous_image">
        <button type="button" class="close" onclick="removeByID('previous_image')"><span aria-hidden="true">&times;</span></button>
        <img src="<?php echo UPLOAD_HTTP_PATH.'slider-user/'.$detail['slide_image']; ?>" class="img-rounded" alt="" width="210">
        <input type="hidden" name="slide_image" value="<?php echo $detail['slide_image'];?>"/>
      </div>
    </div>
    <?php } ?>
    <?php $this->load->view('upload_file_component', array('input_name' => 'slide_image', 'url' => base_url('slider/upload_file'))); ?>
    <div class="form-group">
      <label for="slide_url">Slide URL </label>
      <input type="text" class="form-control reset_field" id="slide_url" name="slide_url" autocomplete="off" value="<?php echo !empty($detail['slide_url']) ? $detail['slide_url'] : ''; ?>"/>
    </div>
    <div class="form-group">
      <label for="display_order">Display Order </label>
      <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off" value="<?php echo !empty($detail['display_order']) ? $detail['display_order'] : ''; ?>">
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
