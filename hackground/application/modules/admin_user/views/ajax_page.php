<?php if($page == 'add'){ ?>
<div class="modal-header">
	<h4 class="modal-title"><?php echo $title;?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>	
</div>
<div class="modal-body">
<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
        
        <div class="form-group">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control reset_field" id="username" name="username" autocomplete="off">
        </div>
       
       <div class="form-group">
          <label for="full_name" class="form-label">Name</label>
          <input type="text" class="form-control reset_field" id="full_name" name="full_name" autocomplete="off">
        </div>
        
        <div class="form-group">
          <label for="email" class="form-label">Email</label>
          <input type="text" class="form-control reset_field" id="email" name="email" autocomplete="off">
        </div>
        
        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control reset_field" id="password" name="password" autocomplete="off">
        </div>
        
        <div class="form-group">
          <label for="password" class="form-label">Role</label>
          <select name="role_id" class="form-control">
            <option value="">Select Role</option>
            <?php print_select_option($admin_role, 'role_id', 'name');?>
          </select>
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

<?php if($page == 'edit'){ 
$login_admin_id = get_session('admin_id');
?>
<div class="modal-header">
	<h4 class="modal-title"><?php echo $title;?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">

<?php if(is_super_admin($ID) && $login_admin_id !== $ID){ ?>
<div class="callout callout-danger">
    <h4>Permission Error !</h4>

    <p>Sorry , You don't have permission to edit information of a "<b>Super Admin</b>" . </p>
</div>
<?php }else{ ?>

<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
      <input type="hidden" name="ID" value="<?php echo $ID?>"/>
      
        <div class="form-group">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control reset_field" id="username" name="username" autocomplete="off" value="<?php echo !empty($detail['username']) ? $detail['username'] : ''; ?>" readonly />
        </div>
       
       <div class="form-group">
          <label for="full_name" class="form-label">Name</label>
          <input type="text" class="form-control reset_field" id="full_name" name="full_name" autocomplete="off" value="<?php echo !empty($detail['full_name']) ? $detail['full_name'] : ''; ?>">
        </div>
        
        <div class="form-group">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control reset_field" id="email" name="email" autocomplete="off" value="<?php echo !empty($detail['email']) ? $detail['email'] : ''; ?>">
        </div>
        
        <?php
        if($ID != $login_admin_id){ 
        ?>
        <div class="form-group">
          <label for="password" class="form-label">Role</label>
          <select name="role_id" class="form-control">
            <option value="">Select Role</option>
            <?php print_select_option($admin_role, 'role_id', 'name', $detail['role_id']);?>
          </select>
        </div>
        <?php }else{  ?>
        <input type="hidden" name="role_id" value="<?php echo $detail['role_id']; ?>"/>
        <?php } ?>
        
        
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
      
        <div class="form-group">
            <div>
                <input type="checkbox" name="change_password" value="1" class="magic-checkbox" id="change_password">
              <label for="change_password">Change Password</label>
            </div>
        </div>
        
        <div class="form-group" style="display:none;" id="change_password_wrapper">
          <label for="password" class="form-label">New Password</label>
          <input type="password" class="form-control" id="password" name="password" autocomplete="off" disabled>
        </div>
        
      <button type="submit" class="btn btn-site">Save</button>
      
</form>
<?php } ?>

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

$('#change_password').change(function(){
	var is_checked =  $(this).is(':checked');
	var $password_wrapper = $('#change_password_wrapper');
	var $password_input = $password_wrapper.find('[name="password"]');
	if(is_checked){
		$password_wrapper.show();
		$password_input.removeAttr('disabled');
	}else{
		$password_wrapper.hide();
		$password_input.attr('disabled', 'disabled');
	}
});

</script>
<?php } ?>