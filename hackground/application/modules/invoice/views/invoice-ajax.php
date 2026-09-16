<?php defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
?>
<ul class="dashboard-box-list">
<?php if($invoiceData){
foreach($invoiceData as $l=>$invoice){
$token=md5(date('Y-m-d').'-ORGUP');
$invoice_url=SITE_URL.'/invoice/details/'.md5($invoice['invoice_id']).'?auth='.$token;
?>
<li>
	<div class="job-listing">
		<div class="job-listing-details">
			<div class="job-listing-description">
				<h4 class="job-listing-title">
					<a href="<?php echo $invoice_url;?>" target="_blank"><?php echo make_invoice_number($invoice['invoice_number']);?></a>
					<?php if($invoice['invoice_status']==1){?>
					<span class="dashboard-status-button green">Paid</span>
					<?php }elseif($invoice['invoice_status']==2){?>
					<span class="dashboard-status-button red">Rejected</span>
					<a href="<?php D(VZ);?>" data-tippy-placement="top" title="<?php echo $invoice['change_reason']; ?>"> <i class="icon-feather-info"></i> </a>
					<?php }else{?>
					<span class="dashboard-status-button yellow">Pending</span>
					<?php }?>
				</h4>

				<div class="job-listing-footer if-button">
					<ul>
						<li><b>Total:</b> <?php echo $currency.displayamount($invoice['total'],2);?></li>
						<li><b>Date:</b> <?php echo $invoice['invoice_date'];?></li>
					</ul>
				</div>
			</div>
		</div>
		</div>
        <!-- Buttons -->
		<div class="buttons-to-right single-right-button always-visible">
			<a href="<?php echo $invoice_url;?>" target="_blank" class="btn btn-sm btn-outline-site ico" data-tippy-placement="top" data-tippy="" title="View">
				<i class="icon-feather-eye"></i>
			</a>
		</div>
	
</li>
<?php		
}
}else{?>
<li class="justify-content-center text-danger">No record found</li>
<?php }?>
</ul>




