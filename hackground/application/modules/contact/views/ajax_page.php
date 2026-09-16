<?php if($page == 'reply'){ ?>

<div class="modal-header">
  <h4 class="modal-title"><?php echo $title;?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $ID;?>"/>
    <div class="form-group">
      <label for="email">Reply To </label>
      <input type="email" class="form-control reset_field" id="email" name="email" value="<?php echo $detail['email'];?>" readonly />
    </div>
    <div class="form-group">
        <label for="reply_message">Reply Message</label>
        <textarea class="form-control reset_field" id="reply_message" name="reply_message" rows="5"><?= htmlspecialchars($detail['reply_message']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-site"><i class="icon-feather-corner-up-left"></i> Reply </button>
    
    <!-- /.box-body -->
    
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
