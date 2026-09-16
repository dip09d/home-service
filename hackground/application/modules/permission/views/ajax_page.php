<?php if($page == 'add'){ ?>

<div class="modal-header hidden">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <?php if($parent){ ?>
    <div class="form-group">
      <label for="name">Parent Menu</label>
      <input class="form-control" name="name" placeholder="" type="text" value="<?php echo $parent['name']; ?>" readonly>
      <input type="hidden" name="parent_id" value="<?php echo $parent['id'];?>"/>
    </div>
    <?php } ?>
    <div class="form-group">
      <label>Menu Name</label>
      <input type="text" class="form-control reset_input" name="name" autocomplete="off">
    </div>
    <div class="form-group">
      <label>Description</label>
      <input type="text" class="form-control reset_input" name="menu_desc" autocomplete="off">
    </div>
    <div class="form-group">
      <label>URL</label>
      <input type="text" class="form-control reset_input" name="url" autocomplete="off">
    </div>
    <div class="form-group">
      <?php 
				 $actions = ['ADD', 'EDIT', 'LIST', 'DELETE'];
				?>
      <label>Action</label>
      <select name="action" class="form-control reset_input">
        <option value="">Action</option>
        <?php foreach( $actions as $v ){ ?>
        <option value="<?php echo $v; ?>"><?php echo $v; ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group">
      <label>Menu Icon Class</label>
      <div class="input-group">
        <input type="text" class="form-control reset_input" name="style_class" autocomplete="off">
        <span class="input-group-append">
        <button type="button" class="btn btn-outline-site" onclick="browse_icon()">Browse..</button>
        </span> </div>
    </div>
    <div class="form-group">
      <label>Menu Order</label>
      <input type="number" class="form-control reset_input" name="ord" autocomplete="off">
    </div>
    <div class="form-group">
      <p><b>Show Left</b></p>
      <div class="radio-inline">
        <input type="radio" name="show_left" value="Y" class="magic-radio" id="show_left_Y" checked>
        <label for="show_left_Y">Yes</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="show_left" value="N" class="magic-radio" id="show_left_N">
        <label for="show_left_N">No</label>
      </div>
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
			form.find('.reset_input').val('');
		}		
		
	}
}

function browse_icon(){
	var url = '<?php echo base_url('dashboard/icons_ajax');?>';
	Modal.openURL({
		title: 'All Icons',
		url : url
	});
}

</script>
<?php } ?>
<?php if($page == 'edit'){ ?>
<div class="modal-header hidden">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID?>"/>
    <div class="form-group">
      <label>Menu Name 2</label>
      <input type="text" class="form-control reset_input" name="name" autocomplete="off" value="<?php echo !empty($detail['name']) ? $detail['name'] : '';?>">
    </div>
    <div class="form-group">
      <label>Description</label>
      <input type="text" class="form-control reset_input" name="menu_desc" autocomplete="off" value="<?php echo !empty($detail['menu_desc']) ? $detail['menu_desc'] : '';?>">
    </div>
    <div class="form-group">
      <label>URL</label>
      <input type="text" class="form-control reset_input" name="url" autocomplete="off" value="<?php echo !empty($detail['url']) ? $detail['url'] : '';?>">
    </div>
    <div class="form-group">
      <label>Menu Icon Class</label>
      <div class="input-group">
        <input type="text" class="form-control reset_input" name="style_class" autocomplete="off" value="<?php echo !empty($detail['style_class']) ? $detail['style_class'] : '';?>">
        <span class="input-group-append">
        <button type="button" class="btn btn-outline-site" onclick="browse_icon()">Browse..</button>
        </span> </div>
    </div>
    <div class="item form-group">
      <label>Menu Code</label>
      <input class="form-control" placeholder="" type="text" value="<?php echo $detail['menu_code']; ?>" readonly>
    </div>
    <div class="form-group">
      <label>Menu Order</label>
      <input type="number" class="form-control reset_input" name="ord" autocomplete="off" value="<?php echo !empty($detail['ord']) ? $detail['ord'] : '';?>">
    </div>
    <div class="form-group">
      <p><b>Show Left</b></p>
      <div class="radio-inline">
        <input type="radio" name="show_left" value="Y" class="magic-radio" id="show_left_Y" checked>
        <label for="show_left_Y">Yes</label>
      </div>
      <div class="radio-inline">
        <input type="radio" name="show_left" value="N" class="magic-radio" id="show_left_N" <?php echo $detail['show_left'] == 'N' ?  'checked' : ''; ?>>
        <label for="show_left_N">No</label>
      </div>
    </div>
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

function browse_icon(){
	var url = '<?php echo base_url('dashboard/icons_ajax');?>';
	Modal.openURL({
		title: 'All Icons',
		url : url
	});
}

</script>
<?php } ?>
<?php if($page == 'add_role'){ ?>
<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <div class="form-group">
      <label>Name</label>
      <input type="text" class="form-control reset_input" name="name" autocomplete="off">
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
			form.find('.reset_input').val('');
		}		
		
	}
}


</script>
<?php } ?>
<?php if($page == 'edit_role'){ ?>
<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID?>"/>
    <div class="form-group">
      <label>Name</label>
      <input type="text" class="form-control reset_input" name="name" autocomplete="off" value="<?php echo !empty($detail['name']) ? $detail['name'] : '';?>">
    </div>
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
