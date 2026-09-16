<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID;?>"/>
    <input type="hidden" name="member_language_id" value="<?php echo $member_language_id;?>"/>
    <input type="hidden" name="page" value="<?php echo $page;?>"/>
    <?php //get_print($detail, false); ?>
    <div class="form-group">
      <label for="name" class="form-label">Language </label>
      <select name="language" class="form-control">
        <?php print_select_option($language, 'language_id', 'language_name', (!empty($detail['language_id']) ? $detail['language_id'] : '')); ?>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Proficiency</label>
      <?php foreach($language_preference as $k => $v){ ?>
      <div>
        <input type="radio" name="language_preference" value="<?php echo $v['language_preference_id'];?>" class="magic-checkbox" id="lang_pref_<?php echo $v['language_preference_id'];?>" <?php echo (!empty($detail) && $v['language_preference_id'] == $detail['language_preference_id']) ? 'checked' : ($k == 0 ? 'checked' : '');?> />
        <label for="lang_pref_<?php echo $v['language_preference_id'];?>"><?php echo $v['language_preference_name'];?>: <?php echo $v['language_preference_info'];?></label>
      </div>
      <?php } ?>
    </div>
    <button type="submit" class="btn btn-site"><?php echo !empty($detail) ? 'Save' : 'Add'; ?></button>
  </form>
</div>
