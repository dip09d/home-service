<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="min_value">Min Value</label>
          <input type="text" class="form-control reset_field" id="min_value" name="min_value" autocomplete="off"/>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="max_value">Max Value</label>
          <input type="text" class="form-control reset_field" id="max_value" name="max_value" autocomplete="off"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="commission_percent">Commission Percent (%)</label>
      <input type="text" class="form-control reset_field" id="commission_percent" name="commission_percent" autocomplete="off"/>
    </div>
    <div class="form-group">
      <label for="display_order">Display Order</label>
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
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="min_value">Min Value</label>
          <input type="text" class="form-control reset_field" id="min_value" name="min_value" autocomplete="off" value="<?php echo !empty($detail['min_value']) ? $detail['min_value'] : '0';?>"/>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="max_value">Max Value</label>
          <input type="text" class="form-control reset_field" id="max_value" name="max_value" autocomplete="off" value="<?php echo !empty($detail['max_value']) ? $detail['max_value'] : '';?>"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="commission_percent">Commission Percent (%)</label>
      <input type="text" class="form-control reset_field" id="commission_percent" name="commission_percent" autocomplete="off" value="<?php echo !empty($detail['commission_percent']) ? $detail['commission_percent'] : '';?>" />
    </div>
    <div class="form-group">
      <label for="display_order">Display Order</label>
      <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off" value="<?php echo !empty($detail['display_order']) ? $detail['display_order'] : '';?>">
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
