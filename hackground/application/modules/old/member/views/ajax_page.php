<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <div class="form-group">
      <label for="name">Name </label>
      <input type="text" class="form-control reset_field" id="name" name="member_name" autocomplete="off">
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
      <input type="text" class="form-control reset_field" id="name" name="member_name" autocomplete="off" value="<?php echo !empty($detail['member_name']) ? $detail['member_name'] : ''; ?>">
    </div>
    <div class="form-group">
      <label for="member_email" class="form-label">Email </label>
      <input type="email" class="form-control reset_field" id="member_email" name="member_email" autocomplete="off" value="<?php echo !empty($detail['member_email']) ? $detail['member_email'] : ''; ?>">
    </div>
    <div class="form-group">
      <label for="member_email" class="form-label">New Password </label>
      <input type="password" class="form-control reset_field" id="new_pass" name="new_pass" autocomplete="off" value="">
    </div>
    <div class="form-group">
      <label for="member_email" class="form-label">Confirm Password </label>
      <input type="text" class="form-control reset_field" id="new_pass_again" name="new_pass_again" autocomplete="off" value="">
    </div>
    <div class="form-group">
      <label class="form-label">Email Verified</label>
      <div class="radio-inline">
        <input type="radio" name="is_email_verified" value="1" class="magic-radio" id="is_email_verified_1" checked>
        <label for="is_email_verified_1">Yes</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="is_email_verified" value="0" class="magic-radio" id="is_email_verified_0" <?php echo $detail['is_email_verified'] != '1' ?  'checked' : ''; ?>>
        <label for="is_email_verified_0">No</label>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Phone Verified</label>
      <div class="radio-inline">
        <input type="radio" name="is_phone_verified" value="1" class="magic-radio" id="is_phone_verified_1" checked>
        <label for="is_phone_verified_1">Yes</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="is_phone_verified" value="0" class="magic-radio" id="is_phone_verified_0" <?php echo $detail['is_phone_verified'] != '1' ?  'checked' : ''; ?>>
        <label for="is_phone_verified_0">No</label>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label" class="form-label">
      Document Verified
      </label>
      <div class="radio-inline">
        <input type="radio" name="is_doc_verified" value="0" class="magic-radio" id="is_doc_verified_0" checked>
        <label for="is_doc_verified_0">Pending</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="is_doc_verified" value="1" class="magic-radio" id="is_doc_verified_1" <?php echo $detail['is_doc_verified'] == '1' ?  'checked' : ''; ?>>
        <label for="is_doc_verified_1">Approve</label>
      </div>
      <!--  
           <div class="radio-inline">

              <input type="radio" name="is_doc_verified" value="2" class="magic-radio" id="is_doc_verified_2" <?php echo $detail['is_doc_verified'] == '2' ?  'checked' : ''; ?>>

              <label for="is_doc_verified_2">Reject</label> 

          </div>
          --> 
      
    </div>
    <div class="form-group">
      <label class="form-label">Status</label>
      <div class="radio-inline">
        <input type="radio" name="is_login" value="1" class="magic-radio" id="is_login_1" checked>
        <label for="is_login_1">Yes</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="is_login" value="0" class="magic-radio" id="is_login_0" <?php echo $detail['is_login'] == '0' ?  'checked' : ''; ?>>
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
