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
                  <label for="name_<?php echo $v;?>" class="form-label">Title (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[blog_title][<?php echo $v; ?>]" autocomplete="off">
                </div>
								
				<?php } ?>
				<div class="form-group">
                  <label for="blog_slug" class="form-label">Seo URL </label>
                  <input type="text" class="form-control reset_field" id="blog_slug" name="blog_slug" autocomplete="off">
					<span class="small text-danger">Note: Only [a-z][-][-A-Z] allowed</span>
				</div>
				<?php foreach($lang as $k => $v){ ?> 
				<div class="form-group">
                  <label for="blog_short_description_<?php echo $v;?>" class="form-label">Short Description (<?php echo $v;?>)</label>
				  <textarea class="form-control reset_field" id="blog_short_description_<?php echo $v;?>" name="lang[blog_short_description][<?php echo $v; ?>]" autocomplete="off"></textarea>
                </div>			
				
				<div class="form-group">
				<label for="blog_description_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
				<div data-error-wrapper="lang[blog_description][<?php echo $v; ?>]">
					<textarea class="form-control reset_field" id="blog_description_<?php echo $v;?>" name="lang[blog_description][<?php echo $v; ?>]" autocomplete="off"></textarea>
				</div>
				</div>
				<?php echo get_editor('blog_description_'.$v);?>
				
				<?php } ?>
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="name_<?php echo $v;?>" class="form-label">Tags (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field blog_tags" id="tags_<?php echo $v;?>" name="tags[name][<?php echo $v; ?>]" autocomplete="off">
                </div>
				<?php } ?>
				<div class="form-group" hidden>
                  <label for="category">Category </label>
                  <select name="category_id[]" class="form-control">
					<option value=""> - Select Category -</option>
					<?php print_select_option($category, 'category_id', 'category_name',''); ?>
				  </select>
                </div>
				<div class="form-group" hidden>
				<label class="form-label">Profile For</label>
					<div class="radio-inline">
						<input type="radio" name="blog_for" value="B" class="magic-radio" id="blog_for_B" checked>
						<label for="blog_for_B">All</label> 
					</div>
					<div class="radio-inline">
						<input type="radio" name="blog_for" value="F" class="magic-radio" id="blog_for_F">
						<label for="blog_for_F">Freelancers</label> 
					</div>
					<div class="radio-inline">
						<input type="radio" name="blog_for" value="E" class="magic-radio" id="blog_for_E">
						<label for="blog_for_E">Employer</label> 
					</div>
				</div>								
					
				<?php $this->load->view('upload_file_component', array('input_name' => 'blog_background',  'label' => 'Banner Image',  'url' => base_url('blog/upload_file/banner'))); ?>
				<div class="form-text mb-3" style="margin-top: -15px;">Banner Image Size 960 x 480</div>
				
			    <?php $this->load->view('upload_file_component', array('input_name' => 'blog_thumb', 'label' => 'Thumb Image',  'url' => base_url('blog/upload_file/thumb'))); ?>
			    <div class="form-text mb-3" style="margin-top: -15px;">Thumb Image Size 320 x 240</div>
			   
			   <?php foreach($lang as $k => $v){ ?> 
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
			  
			 
			 
			  

                <button type="submit" class="btn btn-site">Add</button>
       
        </form>
</div>

<script>

init_plugin();
$('.blog_tags').tagsinput({
    maxTags: 10
});
function syncEditors() {
    if (typeof CKEDITOR !== 'undefined') {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }
}
function submitForm(form, evt){
	syncEditors();
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
                  <label for="name_<?php echo $v;?>" class="form-label">Title (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[blog_title][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['blog_title'][$v]) ? $detail['lang']['blog_title'][$v] : '';?>">
                </div>
				
				<?php } ?>
				<div class="form-group">
                  <label for="blog_slug" class="form-label">Seo URL</label>
                  <input type="text" class="form-control reset_field" id="blog_slug" name="blog_slug" autocomplete="off" value="<?php echo !empty($detail['blog_slug']) ? $detail['blog_slug'] : '';?>">
				  <input type="hidden" name="blog_slug_old" value="<?php echo !empty($detail['blog_slug']) ? $detail['blog_slug'] : '';?>"/>
				  <span class="small text-danger">Note: Only [a-z][-][-A-Z] allowed</span>
				</div>
				<?php foreach($lang as $k => $v){ ?> 
				<div class="form-group">
                  <label for="blog_short_description_<?php echo $v;?>" class="form-label">Short Description (<?php echo $v;?>)</label>
				  <textarea class="form-control reset_field" id="blog_short_description_<?php echo $v;?>" name="lang[blog_short_description][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['blog_short_description'][$v]) ? $detail['lang']['blog_short_description'][$v] : '';?></textarea>
                </div>

				<div class="form-group">
				<label for="blog_description_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
				<div data-error-wrapper="lang[blog_description][<?php echo $v; ?>]">
					<textarea class="form-control reset_field" id="blog_description_<?php echo $v;?>" name="lang[blog_description][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['blog_description'][$v]) ? $detail['lang']['blog_description'][$v] : '';?></textarea>
				</div>
				</div>
				<?php echo get_editor('blog_description_'.$v);?>
				
				<?php } ?>
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="name_<?php echo $v;?>" class="form-label">Tags (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field blog_tags" id="tags_<?php echo $v;?>" name="tags[name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['tags']['name'][$v]) ? implode(',',$detail['tags']['name'][$v]) : '';?>">
                </div>
				<?php } ?>
				<div class="form-group" hidden>
                  <label for="category">Category </label>
                  <select name="category_id[]" class="form-control">
					<option value=""> - Select Category -</option>
					<?php print_select_option($category, 'category_id', 'category_name',(!empty($detail['category']['category_id']) ? $detail['category']['category_id']:'')); ?>
				  </select>
                </div>
				<div class="form-group" hidden>
				<label class="form-label">Profile For</label>
					<div class="radio-inline">
						<input type="radio" name="blog_for" value="B" class="magic-radio" id="blog_for_B" checked>
						<label for="blog_for_B">All</label> 
					</div>
					<div class="radio-inline">
						<input type="radio" name="blog_for" value="F" class="magic-radio" id="blog_for_F" <?php echo $detail['blog_for'] == 'F' ?  'checked' : ''; ?>>
						<label for="blog_for_F">Freelancers</label> 
					</div>
					<div class="radio-inline">
						<input type="radio" name="blog_for" value="E" class="magic-radio" id="blog_for_E" <?php echo $detail['blog_for'] == 'E' ?  'checked' : ''; ?>>
						<label for="blog_for_E">Employer</label> 
					</div>
				</div>

				<?php if(!empty($detail['images']['blog_image']) && file_exists(LC_PATH.'blog/banner/'.$detail['images']['blog_image'])){ ?>
				<div class="form-group">
                  <label>Previous Image </label>
                  <div class="image-wrapper" id="previous_blog_background">
					<button type="button" class="close" onclick="removeByID('previous_blog_background')"><i class="icon-feather-trash"></i></button>
					<img src="<?php echo UPLOAD_HTTP_PATH.'blog/banner/'.$detail['images']['blog_image']; ?>" class="img-rounded" alt="" width="210">
					<input type="hidden" name="blog_background" value="<?php echo $detail['images']['blog_image'];?>"/>
				</div>
                </div>
				<?php } ?>
				
				<?php $this->load->view('upload_file_component', array('input_name' => 'blog_background',  'label' => 'Banner Image',  'url' => base_url('blog/upload_file/banner'))); ?>
				<div class="form-text mb-3" style="margin-top: -15px;">Banner Image Size 960 x 480</div>
				
				<?php if(!empty($detail['blog_thumb']) && file_exists(LC_PATH.'blog/thumb/'.$detail['blog_thumb'])){ ?>
				<div class="form-group">
                  <label class="form-label">Previous Image </label>
                  <div class="image-wrapper" id="previous_blog_thumb" style="width:64px;">
					<button type="button" class="close" onclick="removeByID('previous_blog_thumb')"><i class="icon-feather-trash"></i></button>
					<img src="<?php echo UPLOAD_HTTP_PATH.'blog/thumb/'.$detail['blog_thumb']; ?>" alt="" />
					<input type="hidden" name="blog_thumb" value="<?php echo $detail['blog_thumb'];?>"/>
				</div>
                </div>
				<?php } ?>
				
				<?php $this->load->view('upload_file_component', array('input_name' => 'blog_thumb',  'label' => 'Thumb Image',  'url' => base_url('blog/upload_file/thumb'))); ?>
				<div class="form-text mb-3" style="margin-top: -15px;">Thumb Image Size 320 x 240</div>

				<?php foreach($lang as $k => $v){ ?> 
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
					  <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['blog_status'] == '0' ?  'checked' : ''; ?>>
					  <label for="status_0">Inactive</label> 
				  </div>
              </div>
			  
                <button type="submit" class="btn btn-site">Save</button>
        </form>
</div>

<script>

init_plugin();
$('.blog_tags').tagsinput({
    maxTags: 10
});
function syncEditors() {
    if (typeof CKEDITOR !== 'undefined') {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }
}
function submitForm(form, evt){
    syncEditors();
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