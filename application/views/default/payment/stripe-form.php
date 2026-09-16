<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
$currencyCode=CurrencyCode();
$enable_paypal=get_setting('enable_paypal');
$enable_stripe=get_setting('enable_stripe');
$payfor=1;
$p=0;
$sub_total=0;
?>
<section class="pgLoad" style="background-color: #fff;">
	<?php load_view('inc/spinner',array('size'=>36));?> <br />
	<p><?php echo __('payment_paypal_form_processing','Processing your payment. Please wait...');?></p>

</section>	
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
var stripe_key = "<?php echo get_setting('stripe_key')?>";
var stripe = Stripe(stripe_key);

var main=function(){
	$('body').addClass('loading');
	setTimeout(function(){
		stripe.redirectToCheckout({ sessionId: "<?php D($checkout_session->id);?>" });
	},2000)	
}
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