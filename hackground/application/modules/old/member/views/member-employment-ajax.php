<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID;?>"/>
    <input type="hidden" name="employment_id" value="<?php echo $employment_id;?>"/>
    <input type="hidden" name="page" value="<?php echo $page;?>"/>
    <?php //get_print($detail, false); ?>
    <div class="form-group">
      <label for="company" class="form-label">Company </label>
      <input type="text" class="form-control" name="company" value="<?php echo !empty($detail['employment_company']) ? $detail['employment_company'] : ''; ?>"/>
    </div>
    <div class="form-group">
      <label for="company" class="form-label">Location </label>
      <div class="row">
        <div class="col-sm-6">
          <input type="text" class="form-control" name="city" value="<?php echo !empty($detail['employment_city']) ? $detail['employment_city'] : ''; ?>"/>
        </div>
        <div class="col-sm-6">
          <select class="form-control" name="country">
            <?php print_select_option($country, 'country_code', 'country_name', (!empty($detail['employment_country_code']) ? $detail['employment_country_code'] : '')); ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="company" class="form-label">Title </label>
      <input type="text" class="form-control" name="title" value="<?php echo !empty($detail['employment_title']) ? $detail['employment_title'] : ''; ?>"/>
    </div>
    <div class="form-group">
      <label for="company" class="form-label">Role </label>
      <select class="form-control" name="role">
        <?php print_select_option_assoc($role, (!empty($detail['employment_role']) ? $detail['employment_role'] : ''));?>
      </select>
    </div>
    <div class="form-group">
      <label for="company" class="form-label">Period From </label>
      <div class="row">
        <div class="col-sm-6">
          <select class="form-control" name="frommonth">
            <option value="">Select month </option>
            <?php print_select_option_assoc($month, ((!empty($detail['employment_from']) && $detail['employment_from'] != '0000-00-00') ? date('m', strtotime($detail['employment_from'])) : ''));?>
          </select>
        </div>
        <div class="col-sm-6">
          <select class="form-control" name="fromyear">
            <option value="">Select year </option>
            <?php
								for($i=date('Y');$i>=1940;$i--){
									?>
            <option value="<?php echo $i;?>" <?php if(!empty($detail['employment_from']) && $i==date('Y',strtotime($detail['employment_from']))){echo 'selected';}?>><?php echo $i;?></option>
            <?php
								}
			            		 ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group" id="is_working_now"  <?php if($detail && $detail['employment_is_working_on']==1){echo 'style="display:none"';}?>>
      <label for="company" class="form-label">Period To </label>
      <div class="row">
        <div class="col-sm-6">
          <select class="form-control" name="tomonth">
            <option value="">Select month </option>
            <?php print_select_option_assoc($month, ((!empty($detail['employment_to']) && $detail['employment_to'] != '0000-00-00') ? date('m', strtotime($detail['employment_to'])) : ''));?>
          </select>
        </div>
        <div class="col-sm-6">
          <select class="form-control" name="toyear">
            <option value="">Select year </option>
            <?php
								for($i=date('Y');$i>=1940;$i--){
									?>
            <option value="<?php echo $i;?>" <?php if(!empty($detail['employment_to']) && $i==date('Y',strtotime($detail['employment_to']))){echo 'selected';}?>><?php echo $i;?></option>
            <?php
								}
			            		 ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div>
        <input type="checkbox" name="employment_is_working_on" value="1" class="magic-checkbox" id="employment_is_working_on" onchange="$('#is_working_now').toggle();" <?php if($detail && $detail['employment_is_working_on']==1){echo 'checked';}?>>
        <label for="employment_is_working_on">I currently work here</label>
      </div>
    </div>
    <button type="submit" class="btn btn-site"><?php echo !empty($detail) ? 'Save' : 'Add'; ?></button>
  </form>
</div>
