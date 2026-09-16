<?php ob_clean();?>
<?php
//print_r($orderDetails);
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
$year=date('Y');
$joining_date=date('d-M-Y',strtotime($InvoiceDetails->date_of_joining));
$expired_date=date('d-M-Y',strtotime('+5 years',strtotime($InvoiceDetails->date_of_joining)));
$site_logo = LOGO;
$worker_logo = UPLOAD_HTTP_PATH.'worker-icard/'.$InvoiceDetails->icard_logo;
$worker_contact_icon = UPLOAD_HTTP_PATH.'icard-icon/phone.png';
$worker_email_icon = UPLOAD_HTTP_PATH.'icard-icon/email.png';
$worker_website_icon = UPLOAD_HTTP_PATH.'icard-icon/web.png';
$worker_cal_icon = UPLOAD_HTTP_PATH.'icard-icon/cal.png';
$worker_loc_icon = UPLOAD_HTTP_PATH.'icard-icon/loc.png';
$favicon = FAVICON;
$card_phone=get_setting('icard_phone');
$card_email=get_setting('icard_email');
$card_address=get_setting('icard_address');
$html = <<<EOT
<style>
    .print-button {
        line-height: 24px;
        font-size: 15px;
        font-weight: 600;
        color: #fff;
        background-color: #333;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        padding: 10px 20px;
        display: inline-block;
        text-align: center;
        margin: 40px auto 40px auto;
        transition: 0.3s;
        text-decoration: none !important;
        outline: none !important;
        width: auto;
    }
    body {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }

    @media print {
        .print-button-container, .print-button {
          display: none;
          opacity: 0;
          visibility: hidden;
          height: 0;
        }
        body {
          background: #fff;
          font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
          height: 100%;
          color: #666;
        }
        
        @page {
          size: A4; 
          margin: 0 17mm;
        }
        
        .content-block, p {
          page-break-inside: avoid;
        }
        html, body {
          width: 210mm;
          height: 297mm;
        }
        
    }

    /* Single unified ID card with all information on one view */
    .id-card {
        width: 8.5cm;
        height: 5.4cm;
        margin: 20px auto;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        display: flex;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        position: relative;
    }

    /* Left section - Photo and Basic Info */
    .card-left {
        width: 50%;
        background: white;
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border-right: 3px solid #ff8c42;
    }

    .profile-photo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 3px solid #ff8c42;
        
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .employee-name {
        font-size: 10px;
        font-weight: 700;
        color: #ff8c42;
        text-align: center;
        line-height: 1.2;
    }

    .employee-position {
        font-size: 8px;
        color: #666;
        text-align: center;
        font-weight: 500;
    }

    .employee-id {
        font-size: 7px;
        color: #333;
        font-weight: 600;
        margin-top: 4px;
        padding-top: 4px;
        border-top: 1px solid #ff8c42;
    }

    .qr-code {
        width: 90px;
        height: 45px;
        
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 6px;
        color: #999;
        margin-top: 2px;
    }

    .qr-code img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Right section - All company and contact info */
    .card-right {
        width: 50%;
        background: #1a2b3d;
        color: white;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .company-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #ff8c42;
    }

    .company-logo {
        width: 24px;
        height: 24px;
        background: #ff8c42;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 11px;
        color: white;
        flex-shrink: 0;
    }

    .company-info {
        display: flex;
        flex-direction: column;
    }

    .company-name {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .company-tagline {
        font-size: 6px;
        color: #b0b8c1;
        letter-spacing: 0.5px;
    }

    .info-section {
        margin: 4px 0;
    }

    .info-title {
        font-size: 6px;
        font-weight: 600;
        color: #ff8c42;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 2px 0;
        font-size: 6px;
        color: #ccc;
    }

    .info-icon {
        background-color: #ff8c42;
        border-radius: 50%;
        flex-shrink: 0;
        height: 14px;
        width: 14px;
        padding: 2px;
        box-sizing: border-box;
    }

    .card-footer {
        border-top: 1px solid #ff8c42;
        padding-top: 4px;
        font-size: 5px;
        color: #b0b8c1;
        text-align: center;
    }

    .title {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: 600;
    }

</style>
<div style="text-align:center;width:340px;height:340px">
<table class="table" cellpadding="6" border="0" style="border:none;">
       
        
    <tr class="id-card">
        <!-- Left Section -->
        <td class="card-left">
            <div class="profile-photo">
            <img src="{$worker_logo}" alt="" style="border:none;">
            </div>
            <div class="employee-name">{$InvoiceDetails->worker_name}</div>
            <div class="employee-position">{$InvoiceDetails->designation}</div>
            <div class="employee-id">ID: {$InvoiceDetails->employee_id}</div>
            <div class="qr-code">
                <img src="{$site_logo}" alt="" style="border:none; height:50px">
            </div>
        </td>

        <!-- Right Section -->
        <td class="card-right">
            <div>
                <div class="company-header">
                    <div class="company-logo"><img src="{$favicon}" alt="Phone"
             style="width:14px; height:14px; vertical-align:middle;"></div> 
                    <div class="company-info">
                        <div class="company-name">SnapHive</div>
                        <div class="company-tagline">Power By People</div>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-title">Contact</div>
                    <div class="info-row">
                        <span class="info-icon"><img src="{$worker_contact_icon}" alt="Phone"
             style="width:10px; height:10px; vertical-align:middle;"></span>
                        <span>{$card_phone}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-icon"><img src="{$worker_email_icon}" alt="Phone"
             style="width:10px; height:10px; vertical-align:middle;"></span>
                        <span>{$card_email}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-icon"><img src="{$worker_website_icon}" alt="Phone"
             style="width:10px; height:10px; vertical-align:middle;"></span>
                        <span>www.snaphive.com</span>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-title">Validity</div>
                    <div class="info-row">
                        <span class="info-icon"><img src="{$worker_cal_icon}" alt="Phone" style="width:10px; height:10px; vertical-align:middle;"></span>
                        <span>Valid: {$joining_date}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-icon"><img src="{$worker_cal_icon}" alt="Phone" style="width:10px; height:10px; vertical-align:middle;"></span>
                        <span>Expiry: {$expired_date}</span>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-title">Address</div>
                    <div class="info-row">
                        <span class="info-icon"><img src="{$worker_loc_icon}" alt="Phone"
             style="width:10px; height:10px; vertical-align:middle;"></span>
                        <span>{$card_address}</span>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                © {$year} SnapHive. All Rights Reserved.
            </div>
        </td>
    </tr>
</table>
</div>
EOT;


if($this->input->get('html')){
	echo $html;
    if($this->input->get('html')){?>
        <div class="print-button-container">
            <a href="javascript:window.print()" class="print-button">Print</a>
        </div>
        <?php }
	die;
}
$download='';
if($download){
	get_pdf($html, $filename, array('title' => 'INVOICE'), TRUE);
}else{
	get_pdf($html, 'icard.pdf', array('title' => 'I-card'));
}

echo $html;
?>
