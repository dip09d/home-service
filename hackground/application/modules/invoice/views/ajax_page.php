

<?php if($page == 'markaspaid'){ 
	$currency = get_setting('site_currency');
	?>
<div class="modal-header">
	<h5 class="modal-title"><?php echo $title;?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
			  <input type="hidden" name="ID" value="<?php echo $ID?>"/>
			  <div class="form-group">
			  <label for="amount">Amount: <b><?php echo $currency.''.round($detail['total'],2); ?></b></label>
			  </div>

			  	<!-- Payment Type -->
				<div class="form-group">
					<label class="d-block">Payment Type</label>

					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="payment_type" id="payment_cash" value="cash" checked>
						<label class="form-check-label" for="payment_cash">Cash</label>
					</div>

					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="payment_type" id="payment_online" value="online">
						<label class="form-check-label" for="payment_online">Online</label>
					</div>
				</div>
	
				<div class="form-group">
                  <label for="religion_name">Note</label>
                  <textarea class="form-control reset_field" id="note" name="note" autocomplete="off" ></textarea>
                </div>
				<?php $this->load->view('upload_file_component', array('input_name' => 'attachment', 'label' => 'Attachment',  'url' => base_url('invoice/upload_file/markpaid'))); ?>

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