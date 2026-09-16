<?php ob_clean();
//print_r($InvoiceDetails);
if (!defined('PDF_MARGIN_HEADER')) {
	define ('PDF_MARGIN_HEADER', 0);
}
if (!defined('PDF_MARGIN_LEFT')) {
	define ('PDF_MARGIN_LEFT', 5);
}
if (!defined('PDF_MARGIN_RIGHT')) {
	define ('PDF_MARGIN_RIGHT', 5);
}
if (!defined('PDF_MARGIN_TOP')) {
	define ('PDF_MARGIN_TOP', 5);
}
if (!defined('PDF_MARGIN_BOTTOM')) {
	define ('PDF_MARGIN_BOTTOM', 5);
}
$currency=priceSymbol();
$issuer_information=unserialize($InvoiceDetails->issuer_information);
$recipient_information=unserialize($InvoiceDetails->recipient_information);
$is_invoice_paid=$InvoiceDetails->invoice_status;
if($is_invoice_paid == 1){
	$inv_paid = '<td align="right" style="font-size:20px; font-weight:bold; color:green;">
                    PAID
                </td>';
}else{
	$inv_paid = '<td></td>';
}

	$receiver_information = array(
		'name'=> $recipient_information['R_name'],
	);
	$recipient_location_address=array($recipient_information['R_flat'],$recipient_information['R_addr']);
	$recipient_location_address=array_filter($recipient_location_address);

	$receiver_information_address=array();
	$receiver_information_address[]='<span>'.implode(', ',$recipient_location_address).'</span>';
	$receiver_information_address[]='<span>'.$recipient_information['R_landmark'].'</span>';
	// $receiver_information_address[]='<span>'.mb_convert_case(trim($recipient_information['R_city']), MB_CASE_TITLE, "UTF-8").' - '.$recipient_information['R_pin'].'</span>';
	$receiver_information_address[]='<span>'.$recipient_information['R_landmark'].'</span>';
	$receiver_information_address[]='<span>Phone: '.$recipient_information['R_phone'].'</span>';
	$receiver_information_address[]='<span>Type: '.$recipient_information['R_type'].'</span>';
	$receiver_information_address[]='<span>Email: '.$recipient_information['R_email'].'</span>';
	$receiver_information['address']=implode('<br>',$receiver_information_address);
	$sender_information = array(
		'name'=>$issuer_information['I_name'],
	);
	$issuer_location_address=array($issuer_information['I_addr']);
	$issuer_location_address=array_filter($issuer_location_address);

	$sender_information_address=array();
	$sender_information_address[]='<span>'.implode(', ',$issuer_location_address).'</span>';
	$sender_information_address[]='<span>'.$issuer_information['I_GST'].'</span>';
	$sender_information_address[]='<span>'.mb_convert_case(trim($issuer_information['I_city']), MB_CASE_TITLE, "UTF-8").' - '.$issuer_information['I_pin'].'</span>';
	$sender_information_address[]='<span>'.$issuer_information['I_state'].'</span>';
	$sender_information_address[]='<span>Phone: '.$issuer_information['I_phone'].'</span>';
	$sender_information_address[]='<span>Email: '.$issuer_information['I_email'].'</span>';
	$sender_information['address']=implode('<br>',$sender_information_address);
	
	if(isset($worker_id) && $worker_id > 0){
		$receiver_information['address'] = '';
	}

$inv_row = '';
$total_amount_array = array();
$tax_break_up_row = array();
$total_qty=$total_mrp=$total_saving=0;
if(count($InvoiceDetails->invoice_row) > 0){
	// echo '<pre>'; print_r($InvoiceDetails->invoice_row); die;
	foreach($InvoiceDetails->invoice_row as $k => $v){
		$unit_price = $v->invoice_row_unit_price;
		$qty = $v->invoice_row_amount;
		if($v->invoice_row_unit=='hour'){
			$hour_name =formatHours($qty);
		}else{
			$hour_name ='1pcs';
		}
		
		
		//$row_total  = $InvoiceDetails->round_up_amount; // backend calculated final amount
		$row_total =$v->invoice_row_amount*$unit_price;
		
		$net_total  = $InvoiceDetails->round_up_amount;
		$total_amount_array[] = $row_total ;
		$inv_row  .= '<tr>
			<td class="row_table_item" style="width:60%">'.$v->invoice_row_text.'</td>
			<td class="row_table_item center" style="width:15%">'.number_format($unit_price,2).'</td>
			<td class="row_table_item center" style="width:15%">'.$hour_name.'</td>
			<td class="row_table_item center" style="width:15%">'.number_format($row_total,2).'</td>
		</tr>';

	}
}
$amt = array_sum($total_amount_array);
$total_gst=$InvoiceDetails->tax_amount;
$platform_fee=$InvoiceDetails->platform_fee;
$inv_row_summery='';


$tax_bearkup_table_row='';

$extraRow= 3;

$site_logo = LOGO;
$payment_status_logo = IMG_ASSETS.($is_invoice_paid == 1 ? 'paid-stamp.png':'unpaid-stamp.png');

$c = 'RS';
$extraRow_sub=$extraRow-1;
$billing_date=date('M dS ,Y', strtotime($InvoiceDetails->invoice_date));
$billing_time=date('h:i:s A', strtotime($InvoiceDetails->invoice_date));

$invoice_disclaimer='<ul style="font-size:11px;padding-left: 0px;margin-left:0px">
<li>Your term and condition will show here</li>
</ul>';

$invoice_times='';
$invoice_number = make_invoice_number($InvoiceDetails->invoice_number);

$html = <<<EOT

<style>
table{
	width:100%;
}
.table {
	border-collapse:collapse;
	max-width:100%;
	width:100%;
	border: 1px solid #ccc;
}
.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
	/* border:none;
    border-top: 1px solid #ddd; */
}
tr {
	border: none;
}
th {
    text-align: left;
	text-transform:uppercase;
	font-weight:bold
}
.referance_css{
	color:#007fff;
	font-weight:600
}
.row_table_head{
	border: 1px solid #ccc;
	border-bottom: 2px solid #007fff;
	font-weight:600;
	font-size:10px;
}
.row_table_item{
	/* background-color:#ddd; */
	padding: 10px;
    border: 1px solid #ccc;
}
.right{
	text-align:right;
}
.center{
	text-align:center;
}
.summery-item{
	/* border-top:2px solid #fff */
}
</style>
</head>
<body>
<div class="invoice-box" >
<table class="table" cellpadding="6" border="0" style="border:none;">
<tr><td style="border:none; width:53%"><img src="{$site_logo}" alt="" style="border:none; height:50px"></td>
<td align="right" ><img src="{$payment_status_logo}" alt="" style="border:none; height:50px"></td>
</tr>
<tr><td style="border:none; width:53%">
<table>
<tr>

<td>
	<table cellpadding="2">
	<tr>
	<td class=""><p style="font-weight:700;font-size:16px">SnapHive</p></td>
	</tr>

	
	<tr>
	<td class="">GSTIN : 19PKJPS5988D1Z0</td>
	</tr>
	<tr>
	<td class="">Eraqi Street , South Bazar , Andal, West Bengal , 713321</td>
	</tr>
	<tr>
	<td class="">+91 79033 71185</td>
	</tr>
	<tr>
	<td class="">moin.knockonce@gmail.com</td>
	</tr>
	<tr>
	<td class="">www.snaphive.com</td>
	</tr>
	</table>
</td>
</tr>
</table>
</td>
<td style="border:none; width:47%">
	<table cellpadding="2">
		<tr><td colspan="2" class=""><h2 style="font-weight:700;font-size:16px">INVOICE</h2></td></tr>
		<tr><td class="referance_css">Invoice Number :</td><td class="right">{$invoice_number}</td></tr>
		<tr><td class="referance_css">Order Date :</td><td class="right">{$billing_date}</td></tr>
		<tr><td class="referance_css">Order Time :</td><td class="right">{$billing_time}</td></tr>
		{$invoice_times}
	</table>
</td>
</tr>
</table>

<table  cellpadding="6" style="border:none;">
<tr>
	<td style="width:50%">
	<table>
		<tr><td colspan="2" style="color:#007fff;font-weight:700;border-bottom: 2px solid #007fff;line-height:3">Issuer</td></tr>
		<tr><td colspan="2"  style="font-weight:600;font-size:11px; line-height:3">{$sender_information['name']}</td></tr>
		<tr><td colspan="2"  style="font-size:9px;color: #777;">{$sender_information['address']}</td></tr>
		
	</table>	
	</td>		
	<td style="width:50%">
	<table>
		<tr><td colspan="2" style="color:#007fff;font-weight:700;border-bottom: 2px solid #007fff;line-height:3">Recipient</td></tr>
		<tr><td colspan="2"  style="font-weight:600;font-size:11px; line-height:3">{$receiver_information['name']}</td></tr>
		<tr><td colspan="2"  style="font-size:9px;color: #777;">{$receiver_information['address']}</td></tr>
	</table>	
	</td>
</tr>
</table>

<div>&nbsp;</div>
<table cellpadding="6" style="border-collapse:collapse;padding-bottom-10px;" cellspacing=0>
	<thead>
<tr>
<th class="row_table_head" style="width:60%">Item</th>

<th class="row_table_head center" style="width:15%">Price ($c)/hr</th>
<th class="row_table_head center" style="width:15%">Duration/Pcs</th>
<th class="row_table_head center" style="width:15%">Total ($c) </th>
</tr>
</thead>
<tbody>
	
</table>

<table cellpadding="6" style="border-collapse:collapse;" cellspacing=0>

{$inv_row}
</tbody>
<tfoot>
{$inv_row_summery}
<tr>
<th colspan="{$extraRow_sub}"></th>
<th colspan="1" style="padding: 8px;border-top: 1px solid #ccc;" class="row_table_item">Sub Total ($c)</th>
<th style="padding: 8px;border-top: 1px solid #ccc;text-align:center;" class="row_table_item">{$amt}</th>
</tr>
<tr>
<th colspan="{$extraRow_sub}"></th>
<th colspan="1" style="padding: 8px;border-top: 1px solid #ccc;" class="row_table_item">Total GST ($c)</th>
<th style="padding: 8px;border-top: 1px solid #ccc;text-align:center;" class="row_table_item">{$total_gst}</th>
</tr>
<tr>
<th colspan="{$extraRow_sub}"></th>
<th colspan="1" style="padding: 8px;border-top: 1px solid #ccc;" class="row_table_item">Platform Fee ($c)</th>
<th style="padding: 8px;border-top: 1px solid #ccc;text-align:center;" class="row_table_item">{$platform_fee}</th>
</tr>

<tr>
<th colspan="{$extraRow_sub}"></th>
<th colspan="1" style="padding: 8px;border-left: 1px solid #ccc;border-right: 1px solid #ccc;color:#fff;border-top: 1px solid #ccc;background-color:#007fff;font-weight:bold;font-size:11px;">Net Total ($c)</th>
<th style="padding: 8px;border-top: 1px solid #ccc;text-align:center;background-color:#007fff;color:#fff;font-weight:bold;font-size:11px;">{$net_total}</th>
</tr>


</tfoot>

</table>

<div>&nbsp;</div>
<div>&nbsp;</div>
<p style="color:#007fff;font-weight:700;border-bottom: 2px solid #007fff;line-height:3px">IMPORTANT NOTICE</p>



{$invoice_disclaimer}
<p style="line-height:18px">This is a computer generated invoice</p>
<p style="line-height:5px;font-size:11px;">&#34;To exist as a nation, to prosper as a state, to live as a people, we
must have trees.&#34; &#45; Theodore Roosevelt</p>
<p style="line-height:5px;font-weight:700;font-size:12px;" class="center" >Save Paper &#45; Go Electronic!</p>
</div>

EOT;
if($this->input->get('html')){
	echo $html;
	die;
}

	get_pdf($html, 'download.pdf', array('title' => 'INVOICE'));


echo $html;
