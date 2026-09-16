<?php if($page == 'add'){ ?>
<div class="modal-header">
	<h4 class="modal-title"><?php echo $title;?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
				
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[country_name][<?php echo $v; ?>]" autocomplete="off">
                </div>
			
				
				<?php } ?>
				
				<?php $this->load->view('upload_file_component', array('input_name' => 'country_background',  'label' => 'Banner Image',  'url' => base_url('country/upload_file/banner'))); ?>
				
			   <?php $this->load->view('upload_file_component', array('input_name' => 'country_thumb', 'label' => 'Thumb Image',  'url' => base_url('country/upload_file/thumb'))); ?>
			   
				<div class="form-group">
                  <label for="country_code">Country Code </label>
                  <input type="text" class="form-control reset_field" id="country_code" name="country_code" autocomplete="off">
                </div>
				
				<div class="form-group">
                  <label for="currency_code">Currency </label>
                  <input type="text" class="form-control reset_field" id="currency_code" name="currency_code" autocomplete="off">
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
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
			  <input type="hidden" name="ID" value="<?php echo $ID?>"/>
				
				<?php
				
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[country_name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['name'][$v]) ? $detail['lang']['name'][$v] : '';?>">
                </div>
				
				<?php } ?>
				
				<?php if(!empty($detail['country_background']) && file_exists(LC_PATH.'country-icon/banner/'.$detail['country_background'])){ ?>
				<div class="form-group">
                  <label>Previous Image </label>
                  <div class="image-wrapper" id="previous_country_background">
					<button type="button" class="close" onclick="removeByID('previous_country_background')"><span aria-hidden="true">&times;</span></button>
					<img src="<?php echo UPLOAD_HTTP_PATH.'country-icon/banner/'.$detail['country_background']; ?>" class="img-rounded" alt="" width="210">
					<input type="hidden" name="country_background" value="<?php echo $detail['country_background'];?>"/>
				</div>
                </div>
				<?php } ?>
				
				<?php $this->load->view('upload_file_component', array('input_name' => 'country_background',  'label' => 'Banner Image',  'url' => base_url('country/upload_file/banner'))); ?>
				
				 <?php if(!empty($detail['country_thumb']) && file_exists(LC_PATH.'country-icon/thumb/'.$detail['country_thumb'])){ ?>
				<div class="form-group">
                  <label>Previous Image </label>
                  <div class="image-wrapper" id="previous_country_thumb">
					<button type="button" class="close" onclick="removeByID('previous_country_thumb')"><span aria-hidden="true">&times;</span></button>
					<img src="<?php echo UPLOAD_HTTP_PATH.'country-icon/thumb/'.$detail['country_thumb']; ?>" class="img-rounded" alt="" width="210">
					<input type="hidden" name="country_thumb" value="<?php echo $detail['country_thumb'];?>"/>
				</div>
                </div>
				<?php } ?>
				
			   <?php $this->load->view('upload_file_component', array('input_name' => 'country_thumb', 'label' => 'Thumb Image',  'url' => base_url('country/upload_file/thumb'))); ?>
			   
			  
				<div class="form-group">
                  <label for="country_code">Country Code </label>
                  <input type="text" class="form-control reset_field" id="country_code" name="country_code" autocomplete="off" value="<?php echo !empty($detail['country_code']) ? $detail['country_code'] : '';?>" readonly />
                </div>
				
				<div class="form-group">
                  <label for="currency_code">Currency </label>
                  <input type="text" class="form-control reset_field" id="currency_code" name="currency_code" autocomplete="off" value="<?php echo !empty($detail['currency_code']) ? $detail['currency_code'] : '';?>">
                </div>
				
				
			   <div class="form-group">
			   <label class="form-label">Status</label>
                <div class="radio-inline">
					<input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
					<label for="status_1">Active</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['country_status'] == '0' ?  'checked' : ''; ?>>
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