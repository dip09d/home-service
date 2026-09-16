<?php defined('BASEPATH') OR exit('No direct script access allowed');
//get_print($InvoiceDetails,FALSE);
$currency=priceSymbol();
$issuer_information=unserialize($InvoiceDetails->issuer_information);
$recipient_information=unserialize($InvoiceDetails->recipient_information);
//get_print($recipient_information,FALSE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title><?php echo __('invoice_details_invoice','Invoice');?></title>
	<link rel="stylesheet" href="<?php echo CSS;?>invoice.css">
</head> 

<body>

<!-- Print Button -->
<div class="print-button-container">
	<a href="javascript:window.print()" class="print-button"><?php echo __('invoice_details_print_this','Print this invoice');?></a>
</div>

<!-- Invoice -->
<div id="invoice">

	<!-- Header -->
	<div class="row">
		<div class="col-xl-6">
			<div id="logo"><img src="<?php echo LOGO;?>" alt=""></div>
		</div>

		<div class="col-xl-6">	

			<p id="details">
				<strong><?php echo __('invoice_details_invoice_number','Invoice Number:');?></strong> #<?php echo make_invoice_number($InvoiceDetails->invoice_number);?> <br>
				<strong><?php echo __('invoice_details_issued','Issued:');?></strong> <?php echo dateFormat($InvoiceDetails->invoice_date,'M d, Y')?> <br>
				<!--Due 7 days from date of issue-->
			</p>
		</div>
	</div>


	<!-- Client & Supplier -->
	<div class="row">
		<div class="col-xl-12">
			<h2><?php echo __('invoice_details_invoice','Invoice');?></h2>
		</div>

		<div class="col-xl-6">	
			<strong class="mb-2"><?php echo __('invoice_details_issuer','Issuer');?></strong>
			<p>
				<?php echo $issuer_information['I_name'];?> <br>
				<?php if($issuer_information['I_addr']){?>
				<?php echo $issuer_information['I_addr'];?><br>
				<?php }?>
				<?php if($issuer_information['I_GST']){?>
				<?php echo 'GSTIN:'.$issuer_information['I_GST'];?><br>
				<?php }?>
				<?php if($issuer_information['I_pin']){?>
				<?php echo $issuer_information['I_pin'];?><br>
				<?php }?>
				<?php 
				$location_issuer=array($issuer_information['I_city'],$issuer_information['I_state'],$issuer_information['I_country']);
				$location_issuer=array_filter($location_issuer);
				if($location_issuer){?>
				<?php echo implode(', ',$location_issuer);?><br>
				<?php }?>
				<?php if($issuer_information['I_phone']){?>
				<?php echo $issuer_information['I_phone'];?><br>
				<?php }?>
				<?php if($issuer_information['I_email']){?>
				<?php echo $issuer_information['I_email'];?><br>
				<?php }?>
			</p>
		</div>

		<div class="col-xl-6">	
			<strong class="mb-2"><?php echo __('invoice_details_recipient','Recipient');?></strong>
			<p>
				<?php echo $recipient_information['R_name'];?> <br>
				<?php 
				$location_address=array($recipient_information['R_flat'],$recipient_information['R_street'],$recipient_information['R_addr']);
				$location_address=array_filter($location_address);
				if($location_address){?>
					<?php echo implode(', ',$location_address);?><br>
				<?php }?>
				
				
				<?php if($recipient_information['R_landmark']){?>
				<?php echo 'Landmark:'.$recipient_information['R_landmark'];?><br>
				<?php }?>
				<?php if($recipient_information['R_pin']){?>
				<?php echo $recipient_information['R_pin'];?><br>
				<?php }?>
				<?php 
				$location_recipient=array($recipient_information['R_city'],$recipient_information['R_state'],$recipient_information['R_country']);
				$location_recipient=array_filter($location_recipient);
				if($location_recipient){?>
				<?php echo implode(', ',$location_recipient);?><br>
				<?php }?>
				<?php if($recipient_information['R_phone']){?>
				<?php echo $recipient_information['R_phone'];?><br>
				<?php }?>
				<?php if($recipient_information['R_email']){?>
				<?php echo $recipient_information['R_email'];?><br>
				<?php }?>
			</p>
		</div>
	</div>


	<!-- Invoice -->
	<div class="row">
		<div class="col-xl-12">
			<table class="margin-top-20">
				<tr>
					<th><?php echo __('invoice_details_description','Description');?></th>
					<th><?php echo __('invoice_details_unit_price','Unit Price');?></th>
					<th><?php echo __('invoice_details_unit','Unit');?></th>
					<th><?php echo __('invoice_details_qty','Qty');?></th>
					<th><?php echo __('invoice_details_total','Total');?></th>
				</tr>
<?php if($InvoiceDetails->invoice_row){
	foreach($InvoiceDetails->invoice_row as $r=>$invoice_row){
	?>
				<tr>
					<td><?php echo $invoice_row->invoice_row_text;?></td> 
					<td><?php echo $currency.displayamount($invoice_row->invoice_row_unit_price,2);?></td>
					<td><?php echo $invoice_row->invoice_row_unit;?></td>
					<td><?php echo $invoice_row->invoice_row_amount;?></td>
					<td><?php echo $currency.displayamount($invoice_row->Invoice_Amount_Without_VAT,2);?></td>
				</tr>
				
<?php
	}
 }?>				
			</table>
		</div>
		
		<div class="col-xl-4 col-xl-offset-8">	
			<table id="totals">
				<tr>
					<th><?php echo __('invoice_total','Total');?></th> 
					<th><span><?php echo $currency.displayamount($InvoiceDetails->total,2);?></span></th>
				</tr>
			</table>
		</div>
	</div>


	<!-- Footer -->
	<div class="row">
		<div class="col-xl-12">
			<ul id="footer">
				<li><span><?php echo get_setting('website_name');?></span></li>
				<li><?php echo get_setting('admin_email');?></li>
			</ul>
		</div>
	</div>
		
</div>


</body>
</html>