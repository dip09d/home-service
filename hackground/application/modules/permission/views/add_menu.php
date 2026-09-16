 <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
		  
            <div class="page-title">
              <div class="title_left">
                <h3>Add Menu</h3>
              </div>
			</div>
			
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add Menu <small>permission menu</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<?php
					$succ_msg = get_flash('succ_msg');
					$error_msg = get_flash('error_msg');
					if(!empty($succ_msg)){ 
					?>
					<div class="alert alert-success alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						<strong>Success!</strong> <?php echo $succ_msg;?>
					</div>
					<?php } ?>
					
					<?php if(!empty($error_msg)){ ?>
					<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						<strong>Error!</strong> <?php echo $error_msg;?>
					</div>
					<?php } ?>
					
					
				  
                    <form class="form-horizontal form-label-left" novalidate method="post" enctype="multipart/form-data">
						
					  <?php if($parent){ ?>
					  <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Parent Menu
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" name="name" placeholder="" type="text" value="<?php echo $parent['name']; ?>" readonly>
						  <input type="hidden" name="parent_id" value="<?php echo $parent['id'];?>"/>
                        </div>
                      </div>
					  
					  <?php } ?>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Menu Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" name="name" placeholder="" required="required" type="text" value="<?php echo !empty($details['name']) ? $details['name'] : set_value('name');?>">
                        </div>
                      </div>
					  
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" name="menu_desc" placeholder="" required="required" type="text" value="<?php echo !empty($details['menu_desc']) ? $details['menu_desc'] : set_value('menu_desc');?>">
                        </div>
                      </div>
					  
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">URL <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" name="url" placeholder="" required="required" type="text" value="<?php echo !empty($details['url']) ? $details['url'] : set_value('url');?>">
                        </div>
                      </div>
					  
					  <?php if(!$details){ ?>
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Action
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						 <?php 
						 $actions = ['ADD', 'EDIT', 'LIST', 'DELETE'];
						 
						 ?>
                          <select name="action" class="form-control">
							<option value="">Action</option>
							<?php foreach( $actions as $v ){ ?>
							<option value="<?php echo $v; ?>"><?php echo $v; ?></option>
							<?php } ?>
							<!--<option value="other">Other</option>-->
						  </select>
                        </div>
                      </div>
					  
					  <div class="item form-group" id="menu_other_wrapper" style="display:none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Action
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" name="action_other" placeholder="" type="text" value="">
						  <p><small>(Use uppercase with no space)</small></p>
                        </div>
						
                      </div>
					  <?php }else{  ?>
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Menu Code
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input class="form-control col-md-7 col-xs-12" placeholder="" type="text" value="<?php echo $details['menu_code']; ?>" readonly>
                        </div>
						
                      </div>
					  <?php } ?>
					  <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Menu Order
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input  class="form-control col-md-7 col-xs-12" name="ord" placeholder="" type="number" value="<?php echo !empty($details['ord']) ? $details['ord'] : set_value('ord');?>">
                        </div>
                      </div>
					  
					  
					  <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Icon Class 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input  class="form-control col-md-7 col-xs-12" name="style_class" placeholder="" type="text" value="<?php echo !empty($details['style_class']) ? $details['style_class'] : set_value('style_class');?>">
                        </div>
                      </div>
					  
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Show Left 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
							<div class="radio">
								<label>
								  <input type="radio" class="flat" name="show_left" value="Y" <?php echo (!empty($details['show_left']) AND $details['show_left'] == 'Y') ? 'checked="checked"' : ''?> checked> Yes
								</label>
								<label>
								  <input type="radio" class="flat" name="show_left" value="N" <?php echo (!empty($details['show_left']) AND $details['show_left'] == 'N') ? 'checked="checked"' : ''?>> No
								</label>
							</div>
							
                        </div>
                      </div>
					  
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Status 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
							<div class="radio">
								<label>
								  <input type="radio" class="flat" name="status" value="<?php echo STATUS_ACTIVE; ?>" checked> Yes
								</label>
								<label>
								  <input type="radio" class="flat" name="status" value="<?php echo STATUS_INACTIVE; ?>" <?php echo (!empty($details) AND $details['status'] == STATUS_INACTIVE) ? 'checked="checked"' : ''?>> No
								</label>
							</div>
					    </div>
                      </div>
					
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <button type="submit" class="btn  btn-success">Save &nbsp;<i class="fa fa-check-circle"></i> </button>
                          <a href="<?php echo base_url('permission/menu_list'); ?>" class="btn  btn-danger">Cancel &nbsp;<i class="fa fa-close"></i></a>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
		
<script src="<?php echo ADMIN_EXTRA;?>validator/validator.js"></script>
<script src="<?php echo ADMIN_EXTRA;?>select2/dist/js/select2.full.min.js"></script>


<script>
     
      // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
      $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

      $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

      $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }

        if (submit)
          this.submit();

        return false;
      });
</script>

<script>
	$(document).ready(function(){
		$(".select2_single").select2({
			 placeholder: "Choose Parent",
			 allowClear: true
		});
	});
	
	$('[name="action"]').change(function(){
		var val  = $('[name="action"] :selected').val();
		if(val == 'other'){
			$('#menu_other_wrapper').show();
		}else{
			$('#menu_other_wrapper').hide();
		}
	});
</script>