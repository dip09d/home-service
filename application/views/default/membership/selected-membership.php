<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
$enable_paypal=get_setting('enable_paypal');
$enable_stripe=get_setting('enable_stripe');
$enable_wallet=1;
$payfor=1;
$p=0;
$sub_total=0;
if($membership){
    $sub_total=($duration=='month' ? $membership->price_per_month:$membership->price_per_year);
}
?>
<!-- Dashboard Container -->
<div class="dashboard-container">
	<?php echo $left_panel;?>
	<!-- Dashboard Sidebar / End -->
	<!-- Dashboard Content
	================================================== -->
	<div class="dashboard-content-container">
		<div class="dashboard-content-inner" >		
			<div class="dashboard-headline">
				<h3><?php echo __('membership','Membership');?></h3>				
			</div>
            <?php //get_print($membership,false);?>
            <?php if($membership){?>
            <div class="dashboard-box mt-0 mb-4">
			<div class="headline">
			<h3><?php echo $membership->name;?></h3>
			</div>
			<div class="content with-padding">	
				<div class="row">
					<div class="col-md-4">
						<p><b><?php echo __('membership_amount','Amount');?> : </b>
                            <?php if($duration=='month'){?>
		                    <?php echo $currency;?><?php echo $membership->price_per_month;?><?php echo __('membership_month','/month');?>
                            <?php }else{?>
                                <?php echo $currency;?><?php echo $membership->price_per_year;?><?php echo __('membership_year','/year');?>
                            <?php }?>
						</p>
						<p class="processing-fee"><b><?php echo __('membership_processing_fee','Processing Fee:');?></b> <ec class="processingFeeText">0</ec></p>
					</div>
				</div>
				
            </div>
            </div>
			<div class="dashboard-box mt-0 mb-4">
			<div class="headline">
			<h3><?php D(__('cart_checkout_page_Payment_Options',"Payment Options"));?></h3>
			</div>
			<div class="content with-padding">	
             <div class="btn-group btn-group-toggle pricing-group" data-toggle="buttons"> 
			 <?php if($enable_wallet == 1 && $member_details->balance>$sub_total){
            	$feeCalculation=generateProcessingFee('wallet',$sub_total);
            $p++;
            ?>            
            <label for="wallet" class="btn btn-outline-black">
            <input type="radio" name="method" id="wallet" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
            <?php D(__('paymentmethod_page_Pay_By_Wallet',"Pay With Wallet"));?><br/>
            <img src="<?php D(IMAGE)?>wallet.png" style="max-height:64px">
            </label>
            <?php } ?>                       
            <?php if($enable_paypal == 1){
            	$feeCalculation=generateProcessingFee('paypal',$sub_total);
            $p++;
            ?>            
            <label for="paypal" class="btn btn-outline-black">
            <input type="radio" name="method" id="paypal" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
            <?php D(__('paymentmethod_page_Pay_By_Paypal',"Pay With Paypal"));?><br/>
            <img src="<?php D(IMAGE)?>paypal.png">
            </label>
            <?php } ?>
             <?php if($enable_stripe == 1){
            	$feeCalculation=generateProcessingFee('stripe',$sub_total);
            $p++;
            ?>            
            <label for="stripe" class="btn btn-outline-black">
            <input type="radio" name="method" id="stripe" data-processing-fee-text="<?php D($feeCalculation['processing_fee_text'])?>" data-processing-fee="<?php D($feeCalculation['processing_fee'])?>" data-total="<?php D($feeCalculation['total_amount']);?>">
            <?php D(__('paymentmethod_page_Pay_By_Stripe',"Pay With Stripe"));?><br/>
            <img src="<?php D(IMAGE)?>stripe.png">
            </label>
            <?php } ?>
            </div>
            
            </div>
            </div>
			<?php if($enable_wallet ==1){ ?>
			<form style="display:none" action="" class="checkoutForm" method="post" id="wallet-form" onsubmit="return processCheckout(this);return false;">
				<input type="hidden" name="method" value="wallet">
				<input type="hidden" name="payfor" value="<?php D($payfor)?>">
				<button type="submit" name="wallet" class="btn btn-primary saveBTN"><?php D(__('paymentmethod_page_Pay_With_Wallet',"Pay With Wallet"));?> </button>
			</form>
			<?php } ?>
			<?php if($enable_paypal ==1){ ?>
			<form style="display:none" action="" class="checkoutForm" method="post" id="paypal-form" onsubmit="return processCheckout(this);return false;">
				<input type="hidden" name="method" value="paypal">
				<input type="hidden" name="payfor" value="<?php D($payfor)?>">
				<button type="submit" name="paypal" class="btn btn-primary saveBTN"><?php D(__('paymentmethod_page_Pay_With_Paypal',"Pay With Paypal"));?> </button>
			</form>
			<?php } ?>
			<?php if($enable_stripe ==1){ ?>
			<form style="display:none" action="" class="checkoutForm" method="post" id="stripe-form" onsubmit="return processCheckout(this);return false;">
				<input type="hidden" name="method" value="stripe">
				<input type="hidden" name="payfor" value="<?php D($payfor)?>">
				<button type="submit" name="stripe" class="btn btn-primary saveBTN"><?php D(__('paymentmethod_page_Pay_With_Stripe',"Pay With Stripe"));?> </button>
			</form>
			<?php } ?>		
        <?php }else{?>
            <div class="alert alert-danger"><?php echo __('membership_eror_process','Error in process');?></div>
        <?php }?>
		</div>
	</div>
	<!-- Dashboard Content / End -->

</div>
<!-- Dashboard Container / End -->
<script type="text/javascript">
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

var main=function(){
$('input[name="method"]').change(function(){
	var id=$(this).attr('id');
	var amount=$(this).data('total');
	var fee=$(this).data('processing-fee');
	var feetext=$(this).data('processing-fee-text');
	$('.checkoutForm').hide();
	if(id=='wallet'){
		$('.processing-fee').hide();
	}else{
		$('.processing-fee').show();
	}
	$('.processing-fee ec').html(fee);
	$('.total-price').html('<?php echo $currency; ?>'+amount);
	$('.processingFeeText').html(feetext);
	
	$('#'+id+'-form').show();
	var payamount=<?php echo $sub_total;?>;
	$('.saveBTN').removeAttr('disabled');
	
})

$('input[name="method"]:first').click();	
}
function processCheckout(ev){
	var formID= $(ev).attr('id');
	var buttonsection=$('#'+formID).find('.saveBTN');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.ajax({
			method: "POST",
			dataType: 'json',
			url: "<?php if($payfor==1){D(get_link('processMembershipFormCheckAJAXURL'));}?>/<?php echo md5($membership->membership_id);?>/<?php echo $duration?>",
			data: $('#'+formID).serialize()+'&'+$.param({ 'okey': <?php echo $sub_total;?> }),
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					if(msg['method']=='wallet' || msg['method']=='bank'){
						
					}else{
						 window.location.href=msg['redirect'];
					}
				} else if (msg['status'] == 'FAIL') {
					bootbox.alert({
						title:'Add Fund',
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