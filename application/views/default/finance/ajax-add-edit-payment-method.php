<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$enable_paypal=get_setting('enable_paypal_withdraw');
$enable_stripe=get_setting('enable_stripe_withdraw');
$enable_stripe=0;
$enable_bank=get_setting('enable_bank_withdraw');
?>
<div class="modal-header">
<button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('finace_add_cancel','Cancel');?></button>
    <h4 class="modal-title"><?php echo __('finace_add_payment_method','Payment Method');?></h4> 
    <button type="button" class="btn btn-success float-end" onclick="SaveAccount(this)"><?php echo __('finace_add_save','Save');?></button>
</div>
<div class="modal-body">
<form action="" method="post" accept-charset="utf-8" id="withdrawmethodform" class="form-horizontal" role="form" name="withdrawmethodform" onsubmit="return false;">  
       
    <div class="row">
     <div class="col">
                <div class="submit-field remove_arrow_select">
                    <label class="form-label"><?php echo __('finace_add_fund_S_method','Select Method');?></label>
                    <select  data-live-search="true" name="payment_method" id="payment_method"  class="selectpicker browser-default">
                        <option value=""><?php echo __('finace_add_select','Select');?></option>	
                        <?php if($enable_paypal){?>	
						<option value="paypal"><?php echo __('finace_add_paypal','Paypal');?></option>		
                        <?php }?>
                        <?php if($enable_stripe){?>	
						<option value="stripe"><?php echo __('finace_add_strip','Stripe');?></option>	
                        <?php }?>
                        <?php if($enable_bank){?>		
						<option value="bank"><?php echo __('finace_add_bank','Bank');?></option>		
                        <?php }?>
					</select>
					<span id="payment_methodError" class="rerror"></span>
				</div>
                <div class="paypal_container default_container" style="display:none">
                    <div class="submit-field ">
                        <label class="form-label"><?php echo __('finace_add_email_id','Account Email id');?></label>
                        <input type="text"  class="form-control" name="paypal_address" id="paypal_address" value="">
                        <span id="paypal_addressError" class="rerror"></span>
                    </div>
                </div>
                <div class="stripe_container default_container" style="display:none">
                    <div class="submit-field ">
                        <label class="form-label"><?php echo __('finace_add_email_id','Account Email id');?></label>
                        <input type="text"  class="form-control" name="stripe_address" id="stripe_address" value="">
                        <span id="stripe_addressError" class="rerror"></span>
                    </div>
                </div>
                <div class="bank_container default_container" style="display:none">
                    <div class="submit-field ">
                        <label class="form-label"><?php echo __('finace_add_bank_name','Bank Name');?></label>
                        <input type="text"  class="form-control" name="bank_name" id="bank_name" value="">
                        <span id="bank_nameError" class="rerror"></span>
                    </div>
                    <div class="submit-field ">
                        <label class="form-label"><?php echo __('finace_add_acc_number','Account Number');?></label>
                        <input type="text"  class="form-control" name="bank_ac_number" id="bank_ac_number" value="">
                        <span id="bank_ac_numberError" class="rerror"></span>
                    </div>
                    <div class="submit-field ">
                        <label class="form-label"><?php echo __('finace_add_swift_code','Swift Code');?></label>
                        <input type="text"  class="form-control" name="bank_swift_code" id="bank_swift_code" value="">
                        <span id="bank_swift_codeError" class="rerror"></span>
                    </div>
                    <div class="submit-field ">
                        <label class="form-label"><?php echo __('finace_add_iban','IBAN');?></label>
                        <input type="text"  class="form-control" name="bank_iban" id="bank_iban" value="">
                        <span id="bank_ibanError" class="rerror"></span>
                    </div>
                </div>
            
        </div>
    </div>
 </form>
</div>
<script>
$('.selectpicker').selectpicker('refresh');
$('#payment_method').change(function(){
    $('.default_container').hide();
    var method=$(this).val();
    if(method=='paypal'){
        $('.'+method+'_container').show();
    }
    else if(method=='stripe'){
        $('.'+method+'_container').show();
    }
    else if(method=='bank'){
        $('.'+method+'_container').show();
    }

})

function SaveAccount(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="withdrawmethodform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('processwithdrawmethodFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
                    $('#myModal').modal('hide');
					var message='<?php D(__('popup_withdrawn_method_success_message',"Your account added successfully!"));?>';
					bootbox.alert({
						title:'Withdrawn Fund Account',
						message: message,
						buttons: {
							'ok': {
								label: 'Ok',
								className: 'btn-primary float-end'
							}
						},
						callback: function (result) {
							location.reload();
						}
					});
                    
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
}
</script>