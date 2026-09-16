<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
/*var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var main=function(){

bootbox.alert({
	title:'Add Fund By Paypal',
	message: '<div class="text-center">'+SPINNER+' Processing. Please wait...</div>',
	buttons: {
	'ok': {
		label: 'Ok',
		className: 'd-none'
		}
	},
	 "backdrop"  : "static",
});
window.setTimeout(function(){
	 $('#pay').click();
 }, 2000);
 	
}*/
</script>
<section class="pgLoad" style="background-color: #fff;">
	<?php load_view('inc/spinner',array('size'=>36));?> <br />
	<p><?php echo __('payment_paypal_form_processing','Processing your payment. Please wait...');?></p>

<form action="<?php D($formdata['url']);?>" method="post" style="display: none">
    <input name="amount" type="hidden" value="<?php D($formdata['amount_converted']);?>">
    <input name="currency_code" type="hidden" value="<?php D($formdata['currency_code']);?>">
    <input name="shipping" type="hidden" value="0.00">
    <input name="tax" type="hidden" value="0.00">
    <input name="return" type="hidden" value="<?php D($formdata['return_url']);?>">
    <input name="cancel_return" type="hidden" value="<?php D($formdata['cancel_url'])?>">
    <input name="notify_url" type="hidden" value="<?php D($formdata['notify_url']);?>">
    <input name="cmd" type="hidden" value="_xclick">
    <input name="business" type="hidden" value="<?php D(get_setting('paypal_email'))?>">
    <input name="item_name" type="hidden" value="<?php D($formdata['item_name']);?> - <?php D(get_setting('website_name'))?>">
    <input name="no_note" type="hidden" value="1">
    <input type="hidden" name="no_shipping" value="1">
    <input name="lc" type="hidden" value="EN">
    <input name="bn" type="hidden" value="PP-BuyNowBF">
    <input name="custom" type="hidden" value="<?php D($formdata['custom']);?>">
    <input type="submit" name="pay" value="Submit" id="pay">
    </form>	
</section>
<script>
var main=function(){
	$('body').addClass('loading');
	setTimeout(function(){
	$('body').removeClass('loading').addClass('loaded');	
	$('#pay').click();})	
};
</script>

<style>
/*.loading {
	background: #fff url('<?php echo IMAGE;?>loader.gif') no-repeat center center;
	background-attachment:fixed;
}*/
.pgLoad {
    /*opacity: 0;*/
	display:flex;
	align-items:center;
	justify-content:center;
	flex-direction:column;
}
.loaded .pgLoad {
	opacity: 1;
	-webkit-transition: opacity 5s ease-out;
	-moz-transition: opacity 5s ease-out;
	transition: opacity 5s ease-out;
}
@media (min-width: 768px) {
	.pgLoad{
		min-height: 450px
	}
}
@media (max-width: 767px) {
	.pgLoad{
		max-height: 250px
	}
}

</style>