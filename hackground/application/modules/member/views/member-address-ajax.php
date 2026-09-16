<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID;?>"/>
    <input type="hidden" name="member_address_id" value="<?php echo $member_address_id;?>"/>
    <input type="hidden" name="page" value="<?php echo $page;?>"/>
    <?php //get_print($detail, false); ?>
    <div class="form-group">
      <label for="name" class="form-label">Name </label>
      <input type="text" class="form-control" name="name" value="<?php echo !empty($detail['name']) ? $detail['name'] : ''; ?>"/>
    </div>
    <div class="form-group">
      <label for="name" class="form-label">Address Line 1 </label>
      <input type="text" class="form-control" name="member_address_1" value="<?php echo !empty($detail['member_address_1']) ? $detail['member_address_1'] : ''; ?>"/>
    </div>
    <div class="form-group">
      <label for="member_address_2" class="form-label">Address Line 2 </label>
      <input type="text" class="form-control" name="member_address_2" value="<?php echo !empty($detail['member_address_2']) ? $detail['member_address_2'] : ''; ?>"/>
    </div>
    <div class="form-group">
      <label for="member_city" class="form-label">City </label>
      <input type="text" class="form-control" name="member_city" value="<?php echo !empty($detail['member_city']) ? $detail['member_city'] : ''; ?>"/>
    </div>
    <div class="form-group">
      <label for="member_state">State</label>
        <select class="form-control" name="member_state">
          <option value="">-Select-</option>
          <?php print_select_option(get_all_state('IND'), 'state_id', 'state_name', (!empty($detail['member_state']) ? $detail['member_state'] : '')); ?>
        </select>
    </div>
    <div class="form-group">
      <label for="member_pincode">Postal Code</label>
      <input type="text" class="form-control" name="member_pincode" value="<?php echo !empty($detail['member_pincode']) ? $detail['member_pincode'] : '' ;?>"/>
    </div>
    <div class="form-group">
      <label for="member_landmark">Landmark</label>
      <input type="text" class="form-control" name="member_landmark" value="<?php echo !empty($detail['member_landmark']) ? $detail['member_landmark'] : '' ;?>"/>
    </div>

    <button type="submit" class="btn btn-site"><?php echo !empty($detail) ? 'Save' : 'Add'; ?></button>
  </form>
</div>
