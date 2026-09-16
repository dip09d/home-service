<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<form action="" method="post" accept-charset="utf-8" id="changepasswordform" class="form-horizontal" role="form" name="changepasswordform" onsubmit="updatePassword();return false;">
  <div class="submit-field">
    <label class="form-label"><?php echo __('password_change_old_password','Old Password');?></label>
    <input type="text" class="form-control input-text with-border" value="" name="old_password" id="old_password" placeholder="<?php echo __('password_enter_old','Enter Old Password');?>">
    <span id="old_passwordError" class="rerror"></span> </div>
  <div class="submit-field">
    <label class="form-label"><?php echo __('password_change_new_password','New Password');?></label>
    <input type="text" class="form-control input-text with-border" value="" name="new_password" id="new_password" placeholder="<?php echo __('password_enter_new','Enter New Password');?>">
    <span id="new_passwordError" class="rerror"></span> </div>
  <div class="submit-field">
    <label class="form-label"><?php echo __('password_change_C_password','Confirm Password');?></label>
    <input type="text" class="form-control input-text with-border" value="" name="confirm_password" id="confirm_password" placeholder="<?php echo __('password_enter_confirm','Enter Confirm Password');?>">
    <span id="confirm_passwordError" class="rerror"></span> </div>
  <button class="btn btn-primary me-2 passwordUpdateBTN"><?php echo __('password_change_update','Update');?></button>
  <a href="javascript:void(0)" class="btn btn-secondary" id="cancel_password"><?php echo __('password_change_cancel','Cancel');?> </a>
</form>
