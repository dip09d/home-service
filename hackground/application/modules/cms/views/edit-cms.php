  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	<div class="row">
      <div class="col-sm-6 col-12">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
		 <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
	  </div>
      <div class="col-sm-6 col-12"><?php echo $breadcrumb ? $breadcrumb : '';?></div>
	</div>
    </section>


	
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
	  <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?php echo $title ? $title : '';?></h3>
        </div>
       
		<div class="card-body " id="main_table">
        <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
        <input type="hidden" name="ID" value="<?php echo $ID?>"/>	
				<div class="form-group">
                  <label for="content_slug">Content slug</label>
                  <input type="text" class="form-control reset_field" id="content_slug" name="content_slug" autocomplete="off" value="<?php echo !empty($detail['content_slug']) ? $detail['content_slug'] : '';?>" readonly />
                </div>
				
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="title_<?php echo $v;?>">Title (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="title_<?php echo $v;?>" name="lang[title][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['title'][$v]) ? $detail['lang']['title'][$v] : '';?>" />
                </div>
				
				<div class="form-group" hidden>
                  <label for="content_<?php echo $v;?>">Content (<?php echo $v;?>)</label>
				  <div data-error-wrapper="lang[content][<?php echo $v; ?>]">
                  <textarea class="form-control reset_field" id="content_<?php echo $v;?>" name="lang[content][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['content'][$v]) ? $detail['lang']['content'][$v] : '';?></textarea>
				  </div>
                </div>
				
				<?php echo get_editor('content_'.$v);?>
				
				<div class="form-group">
                  <label for="meta_title_<?php echo $v;?>">Meta title (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="meta_title_<?php echo $v;?>" name="lang[meta_title][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['meta_title'][$v]) ? $detail['lang']['meta_title'][$v] : '';?>" />
                </div>
				
				<div class="form-group">
                  <label for="meta_keys_<?php echo $v;?>">Meta keys (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="meta_keys_<?php echo $v;?>" name="lang[meta_keys][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['meta_keys'][$v]) ? $detail['lang']['meta_keys'][$v] : '';?>" />
                </div>
				
				<div class="form-group">
                  <label for="meta_dscr_<?php echo $v;?>">Meta description (<?php echo $v;?>)</label>
                  <textarea class="form-control reset_field" id="meta_dscr_<?php echo $v;?>" name="lang[meta_description][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['meta_description'][$v]) ? $detail['lang']['meta_description'][$v] : '';?></textarea>
                </div>
				
				
				<?php } ?>
				
			   
                <?php
                echo $this->load->view('common_template',array(
                    'template'=>'section_block',
                    'type'=>'list',
                    'cms_temp'=>!empty($detail) ? $detail['cms_temp']:array(),
                    ));
                ?>


            
             
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
		 <!-- /.box-body -->		
      </div>
      <!-- /.box -->
	
    </section>
    <!-- /.content -->
  </div>
  <?php
   echo $this->load->view('common_template',array(
    'template'=>'section_block',
    'type'=>'template',
    'cms_temp'=>!empty($detail) ? $detail['cms_temp']:array(),
    ));
  ?>

 
<script>
$(function(){
	
	init_plugin(); /* global.js */

	
});
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
			window.location.href="<?php echo base_url($curr_controller);?>";
		}else if(res.cmd == 'reset_form'){
			var form = $('#add_form');
			form.find('.reset_field').val('');
		}		
		
	}
}
</script>



