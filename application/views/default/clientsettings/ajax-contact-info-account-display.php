<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($organizationInfo,TRUE);
?>

<div class="row">
    <div class="col-md-6">
        <div class="submit-field">
            <h5><?php ucwords(D($organizationInfo->member_name));?></h5>
            <p><?php ucwords(D($organizationInfo->organization_name));?><?php echo __('contact_company_account_client','- Client');?> </p>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Account Type -->
        <div class="submit-field">
            <h5><?php echo __('contact_company_account_email','Email');?></h5>
            <p><?php D($organizationInfo->member_email)?></p>
        </div>
    </div>
    
</div>