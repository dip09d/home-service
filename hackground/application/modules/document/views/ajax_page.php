<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <pre>Nothing to submit</pre>
    <button type="submit" class="btn btn-site">Add</button>
  </form>
</div>
<script>

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
      <label for="company_name">Company Name </label>
      <input type="text" class="form-control reset_field" id="company_name" name="company_name" autocomplete="off" value="<?php echo !empty($detail['company_name']) ? $detail['company_name'] : '';?>">
    </div>
    <?php if(!empty($detail['company_logo']) && file_exists(LC_PATH.'company-logo/'.$detail['company_logo'])){ ?>
    <div class="form-group">
      <label>Previous Logo </label>
      <div class="image-wrapper" id="previous_company_logo">
        <button type="button" class="close" onclick="removeByID('previous_company_logo')"><span aria-hidden="true">&times;</span></button>
        <img src="<?php echo UPLOAD_HTTP_PATH.'company-logo/'.$detail['company_logo']; ?>" class="img-rounded" alt="" width="210">
        <input type="hidden" name="company_logo" value="<?php echo $detail['company_logo'];?>"/>
      </div>
    </div>
    <?php } ?>
    <?php $this->load->view('upload_file_component', array('input_name' => 'company_logo',  'label' => 'Company Logo',  'url' => base_url('member_company/upload_file'))); ?>
    <div class="form-group">
      <label for="company_trade_license">Trade License</label>
      <input type="text" class="form-control reset_field" id="company_trade_license" name="company_trade_license" autocomplete="off" value="<?php echo !empty($detail['company_trade_license']) ? $detail['company_trade_license'] : '';?>">
    </div>
    <div class="form-group">
      <label for="company_website">Company Website</label>
      <input type="text" class="form-control reset_field" id="company_website" name="company_website" autocomplete="off" value="<?php echo !empty($detail['company_website']) ? $detail['company_website'] : '';?>">
    </div>
    <div class="form-group">
      <label for="company_size">Size</label>
      <input type="text" class="form-control reset_field" id="company_size" name="company_size" autocomplete="off" value="<?php echo !empty($detail['company_size']) ? $detail['company_size'] : '';?>">
    </div>
    <div class="form-group">
      <label for="company_description">Description</label>
      <textarea  class="form-control reset_field" id="company_description" name="company_description" autocomplete="off"><?php echo !empty($detail['company_description']) ? $detail['company_description'] : '';?></textarea>
    </div>
    <div class="form-group">
      <label for="company_contact_name">Contact Name</label>
      <input type="text" class="form-control reset_field" id="company_contact_name" name="company_contact_name" autocomplete="off" value="<?php echo !empty($detail['company_contact_name']) ? $detail['company_contact_name'] : '';?>">
    </div>
    <div class="form-group">
      <label for="company_phone">Phone</label>
      <input type="text" class="form-control reset_field" id="company_phone" name="company_phone" autocomplete="off" value="<?php echo !empty($detail['company_phone']) ? $detail['company_phone'] : '';?>">
    </div>
    <div class="form-group">
      <label for="company_address">Address</label>
      <input type="text" class="form-control reset_field" id="company_address" name="company_address" autocomplete="off" value="<?php echo !empty($detail['company_address']) ? $detail['company_address'] : '';?>">
    </div>
    <div class="form-group">
      <label class="form-label">Status</label>
      <div class="radio-inline">
        <input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
        <label for="status_1">Active</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['company_status'] == '0' ?  'checked' : ''; ?>>
        <label for="status_0">Inactive</label>
      </div>
    </div>
    <button type="submit" class="btn btn-site">Save</button>
  </form>
</div>
<script>

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
