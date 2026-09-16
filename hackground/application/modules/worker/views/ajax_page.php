<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <div class="form-group">
      <label for="name">Name </label>
      <input type="text" class="form-control reset_field" id="name" name="worker_name" autocomplete="off">
    </div>
    <div class="form-group">
      <div>
        <input type="checkbox" name="add_more" value="1" class="magic-checkbox" id="add_more">
        <label for="add_more">Add more record</label>
      </div>
    </div>
    <button type="submit" class="btn btn-site">Add</button>
    
    <!-- /.box-body -->
    
    <div class="box-footer"> </div>
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
      <label for="name" class="form-label">Name </label>
      <input type="text" class="form-control reset_field" id="name" name="worker_name" autocomplete="off" value="<?php echo !empty($detail['worker_name']) ? $detail['worker_name'] : ''; ?>">
    </div>
    <div class="form-group">
      <label for="worker_email" class="form-label">Email </label>
      <input type="email" class="form-control reset_field" id="worker_email" name="worker_email" autocomplete="off" value="<?php echo !empty($detail['worker_email']) ? $detail['worker_email'] : ''; ?>">
    </div>
    <div class="form-group">
      <label for="worker_phone" class="form-label">Phone </label>
      <input type="text" class="form-control reset_field" id="worker_phone" name="worker_phone" autocomplete="off" value="<?php echo !empty($detail['worker_phone']) ? $detail['worker_phone'] : ''; ?>">
    </div>
    
    
    <div class="form-group">
      <label class="form-label">Status</label>
      <div class="radio-inline">
        <input type="radio" name="is_login" value="1" class="magic-radio" id="is_login_1" checked>
        <label for="is_login_1">Yes</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="is_login" value="0" class="magic-radio" id="is_login_0" <?php echo $detail['login_status'] == '0' ?  'checked' : ''; ?>>
        <label for="is_login_0">No</label>
      </div>
    </div>
    <button type="submit" class="btn btn-site">Save</button>
  </form>
</div>
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
<?php if($page == 'user_badge'){ ?>
<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
   <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID; ?>"/>
    <div class="form-group">
      <label class="form-label">Badge</label>
      <?php foreach($badges as $k => $v){ ?>
      <div class="checkbox-block">
        <input type="checkbox" name="user_badge[]" value="<?php echo $v['badge_id'];?>" class="magic-checkbox" id="user_badge_<?php echo $v['badge_id'];?>" <?php echo in_array($v['badge_id'], $user_badge_array) ? 'checked' : '';?>>
        <label for="user_badge_<?php echo $v['badge_id'];?>"><?php echo $v['name'];?> <img src="<?php echo $v['icon_image_url']; ?>" width="24"/></label>
      </div>
      <?php } ?>
    </div>
    <button type="submit" class="btn btn-site">Save</button>
   </form>
</div>
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
<?php if($page == 'generateicard'){
  ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
  <input type="hidden" name="ID" value="<?php echo $ID?>"/>
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="name" class="form-label">Employee ID </label>
          <input type="text" class="form-control reset_field" id="employee_id" name="employee_id" autocomplete="off" value="<?php echo $detail['employee_code']; ?>">
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label for="designation" class="form-label">Date of joining </label>
          <input type="date" class="form-control reset_field" id="date_of_joining" name="date_of_joining" autocomplete="off" value="<?php echo date('Y-m-d');?>">
        </div>
        
      </div>
    </div>
    <div class="form-group">
        <label for="designation" class="form-label">Designation </label>
        <input type="text" class="form-control reset_field" id="designation" name="designation" autocomplete="off" value="">
      </div>
  
    
    <div class="form-group">
      <label for="name" class="form-label">Name </label>
      <input type="text" class="form-control reset_field" id="worker_name" name="worker_name" autocomplete="off" value="<?php echo !empty($detail['worker_name']) ? $detail['worker_name'] : ''; ?>">
    </div>
    <!-- <div class="form-group">
      <label for="worker_email" class="form-label">Email </label>
      <input type="email" class="form-control reset_field" id="worker_email" name="worker_email" autocomplete="off" value="<?php echo !empty($detail['worker_email']) ? $detail['worker_email'] : ''; ?>">
    </div>
    <div class="form-group">
      <label for="worker_phone" class="form-label">Phone </label>
      <input type="text" class="form-control reset_field" id="worker_phone" name="worker_phone" autocomplete="off" value="<?php echo !empty($detail['worker_phone']) ? $detail['worker_phone'] : ''; ?>">
    </div>
    <div class="form-group">
      <label for="address" class="form-label">Address </label>
      <input type="text" class="form-control reset_field" id="worker_address" name="worker_address" autocomplete="off" value="<?php echo !empty($detail['address']) ? $detail['address'] : ''; ?>">
    </div> -->
    <?php if(!empty($detail['worker_logo']) && file_exists(LC_PATH.'worker-logo/'.$detail['worker_logo'])){ ?>
    <div class="form-group">
        <label class="form-label">Previous Image </label>
        <div class="image-wrapper" id="previous_icard_logo" style="width:64px;">
            <button type="button" class="close" onclick="removeByID('previous_icard_logo')"><i class="icon-feather-trash"></i></button>
            <img src="<?php echo UPLOAD_HTTP_PATH.'worker-logo/'.$detail['worker_logo']; ?>" alt="" />
            <input type="hidden" name="previous_icard_logo" value="<?php echo $detail['worker_logo'];?>"/>
        </div>
    </div>
  <?php } ?>
    <?php $this->load->view('upload_file_component', array('input_name' => 'icard_logo', 'label' => 'Profile Image',  'url' => base_url('worker/upload_file?type=icard'))); ?>


    
   
    
    <!-- /.box-body -->
    
    <div class="box-footer">
    <button type="submit" class="btn btn-site">Generate</button>
   </div>
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
