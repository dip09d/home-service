<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if($current_balance >= $sub_total){ ?>
<form action="" method="post" id="shopping-balance-form" class="checkoutForm" onsubmit="return processCheckout(this);return false;">
<input type="hidden" name="method" value="wallet">
<input type="hidden" name="payfor" value="<?php D($payfor)?>">
<button class="btn btn-lg btn-success btn-block saveBTN" type="submit" name="checkout_submit_order" onclick="return confirm('Are you sure you want to pay for this with your shopping balance?')">
	<?php D(__('paymentmethod_page_Pay_With_Shopping_Balance',"Pay With Shopping Balance"));?>
</button>
</form>
<?php } ?>
      
<?php if($enable_paypal ==1){ ?>
<form action="" class="checkoutForm" method="post" id="paypal-form" onsubmit="return processCheckout(this);return false;">
	<input type="hidden" name="method" value="paypal">
	<input type="hidden" name="payfor" value="<?php D($payfor)?>">
	<button type="submit" name="paypal" class="btn btn-lg btn-success btn-block saveBTN"><?php D(__('paymentmethod_page_Pay_With_Paypal',"Pay With Paypal"));?> </button>
</form>
<?php } ?>
<?php if($enable_telr ==1){ ?>
<form action="" class="checkoutForm" method="post" id="telr-form" onsubmit="return processCheckout(this);return false;">
	<input type="hidden" name="method" value="telr">
	<input type="hidden" name="payfor" value="<?php D($payfor)?>">
	<button type="submit" name="telr" class="btn btn-lg btn-success btn-block saveBTN"><?php D(__('paymentmethod_page_Pay_With_Telr',"Pay With Telr"));?></button>
</form>
<?php } ?>
<?php if($enable_ngenius ==1){ ?>
<form action="" class="checkoutForm" method="post" id="ngenius-form" onsubmit="return processCheckout(this);return false;">
	<input type="hidden" name="method" value="ngenius">
	<input type="hidden" name="payfor" value="<?php D($payfor)?>">
	<button type="submit" name="ngenius" class="btn btn-lg btn-success btn-block saveBTN"><?php D(__('paymentmethod_page_Pay_With_Ngenius',"Pay With Ngenius"));?></button>
</form>
<?php } ?>
<?php if($enable_bank ==1){ ?>
<form action="" class="checkoutForm" method="post" id="bank-form" onsubmit="return processCheckout(this);return false;">
	<input type="hidden" name="method" value="bank">
	<input type="hidden" name="payfor" value="<?php D($payfor)?>">
	<button type="submit" name="bank" class="btn btn-lg btn-success btn-block saveBTN"><?php D(__('paymentmethod_page_Pay_With_Bank',"Pay With Bank Transfer"));?></button>
</form>
<?php } ?>
<?php if($enable_stripe == 1){ ?>
<form action="" class="checkoutForm" method="post" id="credit-card-form" onsubmit="return processCheckout(this);return false;">
	<input type="hidden" name="method" value="stripe">
	<input type="hidden" name="payfor" value="<?php D($payfor)?>">
	<button type="submit" name="stripe" class="btn btn-lg btn-success btn-block saveBTN"><?php D(__('paymentmethod_page_Pay_With_Credit_Card',"Pay With Credit Card"));?> </button>
</form>
<?php } ?>

<?php if($enable_payza == 1){ ?>
<form action="" class="checkoutForm" method="post" id="payza-form" onsubmit="return processCheckout(this);return false;">
	<input type="hidden" name="method" value="payza">
	<input type="hidden" name="payfor" value="<?php D($payfor)?>">
	<button type="submit" name="payza" class="btn btn-lg btn-success btn-block saveBTN"><?php D(__('paymentmethod_page_Pay_With_Payza',"Pay With Payza"));?></button>
</form>
<?php } ?>


<?php if($enable_coinpayments == 1){ ?>
<form action="" class="checkoutForm" method="post" id="coinpayments-form" onsubmit="return processCheckout(this);return false;">
	<input type="hidden" name="method" value="coinpayments">
	<input type="hidden" name="payfor" value="<?php D($payfor)?>">
	<button type="submit" name="coinpayments" class="btn btn-lg btn-success btn-block saveBTN"><?php D(__('paymentmethod_page_Pay_With_Coinpayments',"Pay With Coinpayments"));?></button>
</form>
<?php } ?>

<?php if($enable_dusupay == 1){ ?>
<form action="" class="checkoutForm" method="post" id="mobile-money-form" onsubmit="return processCheckout(this);return false;">
	<input type="hidden" name="method" value="mobile_money">
	<input type="hidden" name="payfor" value="<?php D($payfor)?>">
	<button type="submit" name="coinpayments" class="btn btn-lg btn-success btn-block saveBTN"><?php D(__('paymentmethod_page_Pay_With_Mobile_Money',"Pay With Mobile Money"));?></button>
</form>
<?php } ?>

<script type="text/javascript">
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

$(document).ready(function(){
$('input[name="method"]').click(function(){
	var id=$(this).attr('id');
	var amount=$(this).data('total');
	var fee=$(this).data('processing-fee');
	$('.checkoutForm').hide();
	if(id=='shopping-balance'){
		$('.processing-fee').hide();
		//$('.total-price').html('<?php echo $s_currency; ?><?php echo $sub_total; ?>');
	}else{
		$('.processing-fee').show();
		//$('.total-price').html('<?php echo $s_currency; ?><?php echo $total; ?>');
	}
	$('.processing-fee ec').html(fee);
	$('.total-price').html('<?php echo $s_currency; ?>'+amount);
	$('#'+id+'-form').show();
})

$('input[name="method"]:first').click();	
});
function processCheckout(ev){
	var formID= $(ev).attr('id');
	var buttonsection=$('#'+formID).find('.saveBTN');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.ajax({
			method: "POST",
			dataType: 'json',
			url: "<?php if($payfor=='checkout'){D(get_link('processCheckoutFormCheckAJAXURL'));}elseif($payfor=='cart'){D(get_link('processCartFormCheckAJAXURL'));}elseif($payfor=='featured'){D(get_link('processFeaturedFormCheckAJAXURL').$ids);}elseif($payfor=='offer'){D(get_link('processOfferFormCheckAJAXURL').$ids);}elseif($payfor=='offerrequest'){D(get_link('processOfferFormCheckRequestAJAXURL').$ids);}?>",
			data: $('#'+formID).serialize(),
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					if(msg['method']=='wallet' || msg['method']=='bank'){
						swal({
		                  type: 'success',
		                  text: 'Processing...... ',
		                  timer: 5000,
		              	  onOpen: function(){
						 	 swal.showLoading()
						  }
		                  }).then(function(){
		                  window.location.href=msg['redirect'];
		                })
					}else{
						 window.location.href=msg['redirect'];
					}
					
				} else if (msg['status'] == 'FAIL') {
					
				}
			}
		})
	
	return false;
}
</script>