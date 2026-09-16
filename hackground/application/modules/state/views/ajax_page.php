<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <div class="form-group">
      <label for="country_code">Country </label>
      <select class="form-control" name="country_code">
        <option value="">-select-</option>
        <?php print_select_option($country, 'country_code', 'country_name');?>
      </select>
    </div>
    <?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[state_name][<?php echo $v; ?>]" autocomplete="off">
    </div>
    <?php } ?>
    <div class="form-group">
      <label for="state_key">Slug </label>
      <input type="text" class="form-control reset_field" id="state_key" name="state_key" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="state_order">Display Order </label>
      <input type="text" class="form-control reset_field" id="state_order" name="state_order" autocomplete="off">
    </div>
    <?php $this->load->view('upload_file_component', array('input_name' => 'state_thumb', 'label' => 'Thumb Image',  'url' => base_url('state/upload_file/thumb'))); ?>
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
    <div class="form-group">
      <label for="country_code">Country </label>
      <select class="form-control" name="country_code">
        <option value="">-select-</option>
        <?php print_select_option($country, 'country_code', 'country_name', $detail['country_code']);?>
      </select>
    </div>
    <?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[state_name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['state_name'][$v]) ? $detail['lang']['state_name'][$v] : '';?>">
    </div>
    <?php } ?>
    <div class="form-group">
      <label for="state_key">Slug </label>
      <input type="hidden" name="state_key_old" value="<?php echo !empty($detail['state_key']) ? $detail['state_key'] : ''; ?>"/>
      <input type="text" class="form-control reset_field" id="state_key" name="state_key" autocomplete="off" value="<?php echo !empty($detail['state_key']) ? $detail['state_key'] : ''; ?>">
    </div>
    <div class="form-group">
      <label for="state_order">Display Order </label>
      <input type="text" class="form-control reset_field" id="state_order" name="state_order" autocomplete="off" value="<?php echo !empty($detail['state_order']) ? $detail['state_order'] : ''; ?>">
    </div>
    <?php if(!empty($detail['state_thumb']) && file_exists(LC_PATH.'state-icon/thumb/'.$detail['state_thumb'])){ ?>
    <div class="form-group">
      <label>Previous Image </label>
      <div class="image-wrapper" id="previous_state_thumb">
        <button type="button" class="close" onclick="removeByID('previous_state_thumb')"><span aria-hidden="true">&times;</span></button>
        <img src="<?php echo UPLOAD_HTTP_PATH.'state-icon/thumb/'.$detail['state_thumb']; ?>" class="img-rounded" alt="" width="210">
        <input type="hidden" name="state_thumb" value="<?php echo $detail['state_thumb'];?>"/>
      </div>
    </div>
    <?php } ?>
    <?php $this->load->view('upload_file_component', array('input_name' => 'state_thumb', 'label' => 'Thumb Image',  'url' => base_url('state/upload_file/thumb'))); ?>
    <div class="form-group">
      <label class="form-label">Status</label>
      <div class="radio-inline">
        <input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
        <label for="status_1">Active</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['state_status'] == '0' ?  'checked' : ''; ?>>
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
