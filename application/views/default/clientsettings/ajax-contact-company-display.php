<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($organizationInfo,TRUE);
?>


<div class="row">
    <div class="col-md-4">
        <div class="submit-field">
            <p><?php ucwords(D($organizationInfo->organization_name));?></p>
            <p><a href="<?php echo get_link('redirectToURL')?>?ref=<?php D($organizationInfo->organization_website)?>" target="_blank"><?php D($organizationInfo->organization_website)?></a></p>
        </div>
    </div>

    
    <div class="col-md-4">
        <!-- Account Type -->
        <div class="submit-field">
            <h5><?php echo __('contact_company_diplay_tagline','Tagline');?></h5>
            <p><?php D($organizationInfo->organization_heading)?></p>
        </div>
    </div>
    <div class="col-md-4">
        <!-- Account Type -->
        <div class="submit-field">
            <h5><?php echo __('contact_company_diplay_description','Description');?></h5>
            <p><?php D($organizationInfo->organization_info)?></p>
        </div>
    </div>
    
</div>