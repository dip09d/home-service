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
                  <label for="name_<?php echo $v;?>" class="form-label">Name (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[category_name][<?php echo $v; ?>]" autocomplete="off">
                </div>
				
				
				<?php } ?>
				
				<?php foreach($lang as $k => $v){ ?> 
				<div class="form-group">
                  <label for="description_<?php echo $v;?>" class="form-label">Description (<?php echo $v;?>)</label>
				  <textarea class="form-control reset_field" id="description_<?php echo $v;?>" name="lang[description][<?php echo $v; ?>]" autocomplete="off"></textarea>
                </div>
				<?php } ?>
				
					
				<?php //$this->load->view('upload_file_component', array('input_name' => 'category_background',  'label' => 'Banner Image',  'url' => base_url('category/upload_file/banner'))); ?>
				
			   <?php $this->load->view('upload_file_component', array('input_name' => 'category_thumb', 'label' => 'Thumb Image',  'url' => base_url('category/upload_file/thumb'))); ?>
			   
			   <div class="form-group">
                  <label for="category_key" class="form-label">Category Key </label>
                  <input type="text" class="form-control reset_field" id="category_key" name="category_key" autocomplete="off">
                </div>
				
				 <?php $this->load->view('upload_file_component', array('input_name' => 'category_icon', 'label' => 'Icon Image',  'url' => base_url('category/upload_file/icon'))); ?>
				 
			   <div class="form-group">
                  <label for="category_order" class="form-label">Display Order </label>
                  <input type="text" class="form-control reset_field" id="category_order" name="category_order" autocomplete="off">
                </div>
				
				<div class="form-group">
					<div>
					 <input type="hidden" name="is_featured" value="0" />
					 <input type="checkbox" name="is_featured" value="1" class="magic-checkbox" id="is_featured">
					  <label for="is_featured">Featured</label>
					</div>
				</div>
				
			   <div class="form-group">
			   <label class="form-label">Status?</label>
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
                  <label for="name_<?php echo $v;?>" class="form-label">Name (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[category_name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['category_name'][$v]) ? $detail['lang']['category_name'][$v] : '';?>">
                </div>
				
				<?php } ?>
				
				<?php foreach($lang as $k => $v){ ?> 
				<div class="form-group">
                  <label for="description_<?php echo $v;?>" class="form-label">Description (<?php echo $v;?>)</label>
				  <textarea class="form-control reset_field" id="description_<?php echo $v;?>" name="lang[description][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['description'][$v]) ? $detail['lang']['description'][$v] : '';?></textarea>
                </div>
				<?php } ?>
				
				<?php /*if(!empty($detail['category_background']) && file_exists(LC_PATH.'category_icons/banner/'.$detail['category_background'])){ ?>
				<div class="form-group">
                  <label>Previous Image </label>
                  <div class="image-wrapper" id="previous_category_background">
					<button type="button" class="close" onclick="removeByID('previous_category_background')"><i class="icon-feather-trash"></i></button>
					<img src="<?php echo UPLOAD_HTTP_PATH.'category_icons/banner/'.$detail['category_background']; ?>" class="img-rounded" alt="" width="210">
					<input type="hidden" name="category_background" value="<?php echo $detail['category_background'];?>"/>
				</div>
                </div>
				<?php }*/ ?>
				
				<?php //$this->load->view('upload_file_component', array('input_name' => 'category_background',  'label' => 'Banner Image',  'url' => base_url('category/upload_file/banner'))); ?>
				
				<?php if(!empty($detail['category_thumb']) && file_exists(LC_PATH.'category_icons/thumb/'.$detail['category_thumb'])){ ?>
				<div class="form-group">
                  <label class="form-label">Previous Image </label>
                  <div class="image-wrapper" id="previous_category_thumb" style="width:64px;">
					<button type="button" class="close" onclick="removeByID('previous_category_thumb')"><i class="icon-feather-trash"></i></button>
					<img src="<?php echo UPLOAD_HTTP_PATH.'category_icons/thumb/'.$detail['category_thumb']; ?>" alt="" />
					<input type="hidden" name="category_thumb" value="<?php echo $detail['category_thumb'];?>"/>
				</div>
                </div>
				<?php } ?>
				
				<?php $this->load->view('upload_file_component', array('input_name' => 'category_thumb',  'label' => 'Thumb Image',  'url' => base_url('category/upload_file/thumb'))); ?>
				
				 <div class="form-group">
                  <label for="category_key" class="form-label">Category Key </label>
                  <input type="text" class="form-control reset_field" id="category_key" name="category_key" autocomplete="off" value="<?php echo !empty($detail['category_key']) ? $detail['category_key'] : '';?>">
				  <input type="hidden" name="category_key_old" value="<?php echo !empty($detail['category_key']) ? $detail['category_key'] : '';?>"/>
                </div>
				
				<?php if(!empty($detail['category_icon']) && file_exists(LC_PATH.'category_icons/'.$detail['category_icon'])){ ?>
				<div class="form-group">
                  <label class="form-label">Previous Image </label>
                  <div class="image-wrapper" id="previous_category_icon" style="width:64px;">
					<button type="button" class="close" onclick="removeByID('previous_category_icon')"><i class="icon-feather-trash"></i></button>
					<img src="<?php echo UPLOAD_HTTP_PATH.'category_icons/'.$detail['category_icon']; ?>" alt="" />
					<input type="hidden" name="category_icon" value="<?php echo $detail['category_icon'];?>"/>
				</div>
                </div>
				<?php } ?>
				
				<?php $this->load->view('upload_file_component', array('input_name' => 'category_icon', 'label' => 'Icon Image', 'url' => base_url('category/upload_file/icon'))); ?>
				
				
				
				<div class="form-group">
                  <label for="category_order" class="form-label">Display Order </label>
                  <input type="text" class="form-control reset_field" id="category_order" name="category_order" autocomplete="off" value="<?php echo !empty($detail['category_order']) ? $detail['category_order'] : '';?>">
                </div>
				
				<div class="form-group">
					<div>
					 <input type="hidden" name="is_featured" value="0" />
					 <input type="checkbox" name="is_featured" value="1" class="magic-checkbox" id="is_featured" <?php echo (!empty($detail['is_featured']) && $detail['is_featured'] == '1') ? 'checked' : '';?>>
					  <label for="is_featured">Featured</label>
					</div>
				</div>
				
			   <div class="form-group">
			   <label class="form-label">Status</label>
                <div class="radio-inline">
					<input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
					<label for="status_1">Active</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['category_status'] == '0' ?  'checked' : ''; ?>>
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