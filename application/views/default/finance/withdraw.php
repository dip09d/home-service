<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
$enable_paypal=get_setting('enable_paypal_withdraw');
$enable_stripe=get_setting('enable_stripe_withdraw');
$enable_bank=get_setting('enable_bank_withdraw');
$payfor=2;
$p=0;
$sub_total=0;
?>
<!-- Dashboard Container -->
<div class="dashboard-container">
	<?php echo $left_panel;?>
	<!-- Dashboard Sidebar / End -->
	<!-- Dashboard Content
	================================================== -->
	<div class="dashboard-content-container">
		<div class="dashboard-content-inner" >		
			<!--<div class="dashboard-headline">
				<h3>My Favourite</h3>				
			</div>-->
            <div class="dashboard-box mt-0 mb-4">
			<div class="headline">
				<h3><i class="icon-material-outline-credit-card text-site"></i> <?php echo __('finace_add_fund_amount','Amount');?> </h3>
			</div>
			<div class="content with-padding">	
				<div class="row">
					<div class="col-md-4">
						<div class="input-with-icon-start">
		                    <i><?php echo $currency;?></i>
		                    <input type="text" name="amount" id="amount" class="form-control text-right" placeholder="0" onkeyup="updateTotal('add_fund')" onkeypress="return isNumberKey(event)">
						</div>
						<p class="processing-fee" style="display:none"><b><?php echo __('finace_add_fund_P_fee','Processing Fee:');?></b> <ec class="processingFeeText">0</ec></p>
					</div>
				</div>
				
            </div>
            </div>
			<div class="dashboard-box mt-0 mb-4">
			<div class="headline">
			<h3>
			<i class="icon-material-outline-credit-card text-site"></i> <?php D(__('cart_checkout_page_Withdraw_Options',"Withdraw Options"));?>
			<button class="btn btn-sm btn-primary float-end add_new_method"><i class="icon-feather-plus"></i><?php echo __('finace_transaction_add_new','Add new');?></button>
			</h3>
			</div>
			<div class="content with-padding">	
             <div class="btn-group btn-group-toggle pricing-group">    
			<?php if($list){
				foreach($list as $k=>$account){
				?>

			<?php if($enable_paypal == 1 && $account->payment_type=='paypal'){
            	$feeCalculation=generateProcessingFee('paypal_withdrawn',$sub_total);
           		 $p++;
            ?>                        
            <input type="radio" class="btn-check" value="<?php echo $account->account_id;?>" name="method" id="paypal" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
            <label for="paypal" class="btn btn-outline-light"><?php //D(__('paymentmethod_page_withdraw_By_Paypal',"With Paypal"));?>
            <img src="<?php D(IMAGE)?>paypal.png"><br>
			<b>ID:</b> <?php D($account->account_heading);?>
			<a href="<?php D(VZ);?>" data-id="<?php echo md5($account->account_id);?>" class="btn btn-sm btn-danger ico removeaccount" data-tippy-placement="top" title="Remove"><i class="icon-feather-trash"></i></a>
            
            </label>
            <?php }
			elseif($enable_stripe == 1 && $account->payment_type=='stripe'){
				$feeCalculation=generateProcessingFee('stripe_withdrawn',$sub_total);
				$p++;
			?>
			
            <input type="radio" class="btn-check" value="<?php echo $account->account_id;?>" name="method" id="stripe" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
            <label for="stripe" class="btn btn-outline-light"><?php //D(__('paymentmethod_page_withdraw_By_Stripe',"With Stripe"));?>
            <img src="<?php D(IMAGE)?>stripe.png">
			<br>
			<b>ID:</b> <?php D($account->account_heading);?>
			<a href="<?php D(VZ);?>" data-id="<?php echo md5($account->account_id);?>" class="btn btn-sm btn-danger ico removeaccount" data-tippy-placement="top" title="Remove"><i class="icon-feather-trash"></i></a>
            
            </label>
			<?php
			}elseif($enable_bank == 1 && $account->payment_type=='bank'){
				$feeCalculation=generateProcessingFee('bank_withdrawn',$sub_total);
				$p++;
			?>
			
            <input type="radio" class="btn-check" value="<?php echo $account->account_id;?>" name="method" id="bank" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
            <label for="bank" class="btn btn-outline-light"><?php //D(__('paymentmethod_page_withdraw_By_Bank',"With Bank"));?>
            <img src="<?php D(IMAGE)?>bank.png"><br>
			<b>A/C:</b> <?php D($account->account_heading);?>
			<a href="<?php D(VZ);?>" data-id="<?php echo md5($account->account_id);?>" class="btn btn-sm btn-danger ico removeaccount" data-tippy-placement="top" title="Remove"><i class="icon-feather-trash"></i></a>
                
            </label>
			<?php
			}
			?>

				<?php
				}
			}
			
			?>	
            </div>
            <?php if(!$list){?>
            <a href="<?php echo VZ;?>" class="card-box mx-auto add_new_method">
                <div class="card-body text-center">
                	<h1><i class="icon-line-awesome-plus-circle"></i></h1>
                    <p><?php echo __('finace_transaction_add_new_method','Add new payment method.');?></p>	
                </div>
            </a>
			<?php	
				}
			?>
            
            </div>
            </div>
			<?php if($enable_paypal ==1){ ?>
			<form style="display:none" action="" class="checkoutForm" method="post" id="paypal-form" onsubmit="return processCheckout(this);return false;">
				<input type="hidden" name="method" value="paypal">
				<input type="hidden" name="payfor" value="<?php D($payfor)?>">
				<button type="submit" name="paypal" class="btn btn-primary saveBTN"><?php D(__('paymentmethod_page_Pay_With_Withdraw',"Withdraw With Paypal"));?> </button>
			</form>
			<?php } ?>
			<?php if($enable_stripe ==1){ ?>
			<form style="display:none" action="" class="checkoutForm" method="post" id="stripe-form" onsubmit="return processCheckout(this);return false;">
				<input type="hidden" name="method" value="stripe">
				<input type="hidden" name="payfor" value="<?php D($payfor)?>">
				<button type="submit" name="stripe" class="btn btn-primary saveBTN"><?php D(__('paymentmethod_page_Pay_With_stripe',"Withdraw With Stripe"));?> </button>
			</form>
			<?php } ?>
			<?php if($enable_bank ==1){ ?>
			<form style="display:none" action="" class="checkoutForm" method="post" id="bank-form" onsubmit="return processCheckout(this);return false;">
				<input type="hidden" name="method" value="bank">
				<input type="hidden" name="payfor" value="<?php D($payfor)?>">
				<button type="submit" name="bank" class="btn btn-primary saveBTN"><?php D(__('paymentmethod_page_Pay_With_Bank',"Withdraw With Bank"));?> </button>
			</form>
			<?php } ?>		

		</div>
	</div>
	<!-- Dashboard Content / End -->

</div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="text-center padding-top-50 padding-bottom-50">
        <?php load_view('inc/spinner',array('size'=>30));?>
      </div>
    </div>
  </div>
</div>
<!-- Dashboard Container / End -->
<script type="text/javascript">
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

var main=function(){
$('input[name="method"]').click(function(){
	$('.processing-fee').hide();
	var id=$(this).attr('id');
	var amount=$(this).data('total');
	var fee=$(this).data('processing-fee');
	var feetext=$(this).data('processing-fee-text');
	$('.checkoutForm').hide();
	if(fee>0){
		$('.processing-fee').show();
	}
	$('.processing-fee ec').html(fee);
	$('.total-price').html('<?php echo $currency; ?>'+amount);
	$('.processingFeeText').html(feetext);
	$('#'+id+'-form').show();
	var payamount=$('input[name="amount"]').val();
	if(payamount>0){
		$('.saveBTN').removeAttr('disabled');
	}else{
		$('.saveBTN').attr('disabled','disabled');
	}
})

$('input[name="method"]:first').click();	
$('input[name="amount"]').on('keyup',function(){
	var payamount=$('input[name="amount"]').val();
	if(payamount>0){
		$('.saveBTN').removeAttr('disabled');
	}else{
		$('.saveBTN').attr('disabled','disabled');
	}
})
$('.add_new_method').on('click',  function(e){
	e.preventDefault();
	$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
	$('#myModal').modal('show');
	var _self = $(this);
	var data = {
		rid: _self.data('id'),
	};
	$.get( "<?php echo get_link('WithdrawformAJAXURL')?>",data, function( data ) {
		setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );$('.selectpicker').selectpicker('refresh');},1000)
	});	

});
$('.removeaccount').on('click',  function(e){
	e.preventDefault();
	var _self = $(this);
		var data = {
			aid: _self.data('id'),
		};
		
		bootbox.confirm({
			title: '<?php D(__('withdraw_page_Delete_withdraw_method','Delete withdraw method'));?>',
			message: 'Are you sure to delete this withdraw method?',
			size: 'small',
			buttons: {
				confirm: {
					label: "Yes",
					className: 'btn-primary float-end'
				},
				cancel: {
					label: "No",
					className: 'btn-dark float-start'
				}
			},
			
			callback: function(result){
				if(result==true){
					$.post('<?php echo get_link('actionwithdrawremove'); ?>', data, function(res){
						if(res.status == 'OK'){
							location.reload();
						}
					},'JSON');
				}
			}

		});

});
}
function processCheckout(ev){
	var formID= $(ev).attr('id');
	var buttonsection=$('#'+formID).find('.saveBTN');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.ajax({
			method: "POST",
			dataType: 'json',
			url: "<?php D(get_link('processAddFundFormCheckAJAXURL'));?>",
			data: $('#'+formID).serialize()+'&'+$.param({ 'okey': $('input[name="amount"]').val() ,'account_id': $('input[type="radio"][name="method"]:checked').val() }),
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					var message='<?php D(__('popup_withdrawn_request_success_message',"Your request has been submitted successfully!"));?>';
					bootbox.alert({
						title:'Withdrawn Fund',
						message: message,
						buttons: {
							'ok': {
								label: 'Ok',
								className: 'btn-primary float-end'
							}
						},
						callback: function (result) {
							window.location.href="<?php D(get_link('TransactionHistoryURL'))?>";
						}
					});
				} else if (msg['status'] == 'FAIL') {
					bootbox.alert({
						title:'Withdrawn Fund',
						message: msg['error'],
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						}
					});
				}
			}
		})
	
	return false;
}
function updateTotal(){
	var amount=$('#amount').val();
	if(isNaN(amount)){
		amount=0;
	}
	$('#amount').val(amount);
}
</script>
<?php if(($this->input->get('refer') && $this->input->get('refer')=='paymentsuccess') || ($this->input->get('ref_p') && $this->input->get('ref_p')=='paymentsuccess')){?>
<script>
var mainload=function(){
	bootbox.alert({
		title: '<?php D(__('popup_manageproposal_Payment_Success',"Payment Success"));?>',
		message: 'Payment Successfull',
		size: 'small',
		buttons: {
			ok: {
				label: "Ok",
				className: 'btn-primary float-end'
			},
		},
		callback: function(result){
			window.location.href='<?php D(get_link('AddFundURL'));?>';
		}

	});
}
</script>
<?php }elseif(($this->input->get('refer') && $this->input->get('refer')=='paymenterror') || ($this->input->get('ref_p') && $this->input->get('ref_p')=='paymenterror')){?>
<script>
var mainload=function(){
	bootbox.alert({
		title: '<?php D(__('popup_manageproposal_Payment_Error',"Payment Error"));?>',
		message: 'Payment failed',
		size: 'small',
		buttons: {
			ok: {
				label: "Ok",
				className: 'btn-primary float-end'
			},
		},
		callback: function(result){
			window.location.href='<?php D(get_link('AddFundURL'));?>';
		}

	});
}
</script>
<?php }?>