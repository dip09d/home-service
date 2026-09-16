<?php if($page == 'add'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[name][<?php echo $v; ?>]" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="description_<?php echo $v;?>">Description (<?php echo $v;?>)</label>
      <textarea  class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[description][<?php echo $v; ?>]" autocomplete="off"></textarea>
    </div>
    <?php } ?>
    <div class="form-group">
      <label for="membership_bid" class="form-label">Bid</label>
      <input type="text" class="form-control reset_field" id="membership_bid" name="membership_bid" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="membership_portfolio" class="form-label">Portfolio</label>
      <input type="text" class="form-control reset_field" id="membership_portfolio" name="membership_portfolio" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="membership_skills" class="form-label">Skills</label>
      <input type="text" class="form-control reset_field" id="membership_skills" name="membership_skills" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="membership_commission_percent" class="form-label">Commission %</label>
      <input type="text" class="form-control reset_field" id="membership_commission_percent" name="membership_commission_percent" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="price_per_month" class="form-label">Price/month</label>
      <input type="text" class="form-control reset_field" id="price_per_month" name="price_per_month" autocomplete="off" >
    </div>
    <div class="form-group">
      <label for="price_per_year" class="form-label">Price/year</label>
      <input type="text" class="form-control reset_field" id="price_per_year" name="price_per_year" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="display_order" class="form-label">Display Order </label>
      <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off">
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
<?php if($page == 'edit'){ ?>
<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID?>"/>
    <?php
				
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
    <div class="form-group">
      <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
      <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['name'][$v]) ? $detail['lang']['name'][$v] : '';?>">
    </div>
    <div class="form-group">
      <label for="description_<?php echo $v;?>">Description (<?php echo $v;?>)</label>
      <textarea  class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[description][<?php echo $v; ?>]" autocomplete="off"><?php echo !empty($detail['lang']['description'][$v]) ? $detail['lang']['description'][$v] : '';?></textarea>
    </div>
    <?php } ?>
    <div class="form-group">
      <label for="membership_bid" class="form-label">Bid</label>
      <input type="text" class="form-control reset_field" id="membership_bid" name="membership_bid" autocomplete="off" value="<?php echo !empty($detail['membership_bid']) ? $detail['membership_bid'] : '';?>">
    </div>
    <div class="form-group">
      <label for="membership_portfolio" class="form-label">Portfolio</label>
      <input type="text" class="form-control reset_field" id="membership_portfolio" name="membership_portfolio" autocomplete="off" value="<?php echo !empty($detail['membership_portfolio']) ? $detail['membership_portfolio'] : '';?>">
    </div>
    <div class="form-group">
      <label for="membership_skills" class="form-label">Skills</label>
      <input type="text" class="form-control reset_field" id="membership_skills" name="membership_skills" autocomplete="off" value="<?php echo !empty($detail['membership_skills']) ? $detail['membership_skills'] : '';?>">
    </div>
    <div class="form-group">
      <label for="membership_commission_percent" class="form-label">Commission %</label>
      <input type="text" class="form-control reset_field" id="membership_commission_percent" name="membership_commission_percent" autocomplete="off" value="<?php echo !empty($detail['membership_commission_percent']) ? $detail['membership_commission_percent'] : '';?>">
    </div>
    <div class="form-group">
      <label for="price_per_month" class="form-label">Price/month</label>
      <input type="text" class="form-control reset_field" id="price_per_month" name="price_per_month" autocomplete="off" value="<?php echo !empty($detail['price_per_month']) ? $detail['price_per_month'] : '';?>">
    </div>
    <div class="form-group">
      <label for="price_per_year" class="form-label">Price/year</label>
      <input type="text" class="form-control reset_field" id="price_per_year" name="price_per_year" autocomplete="off" value="<?php echo !empty($detail['price_per_year']) ? $detail['price_per_year'] : '';?>">
    </div>



    <div class="form-group">
      <label for="display_order" class="form-label">Display Order </label>
      <input type="text" class="form-control reset_field" id="display_order" name="display_order" autocomplete="off" value="<?php echo !empty($detail['display_order']) ? $detail['display_order'] : '';?>">
    </div>
    <div class="form-group">
      <label class="form-label">Status</label>
      <div class="radio-inline">
        <input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
        <label for="status_1">Active</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['membership_status'] == '0' ?  'checked' : ''; ?>>
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
