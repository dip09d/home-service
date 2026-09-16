<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);

$email=$memberInfo->member_email;
$d=explode('@',$email,2);
if(count($d)==2){
$first=$d[0];
$email_l=strlen($first);
$firs_part=substr($first,0,1);
if($email_l>3){
	$star=$email_l-3;
	for($s=0;$s<$star;$s++){
		$firs_part.='*';
	}
	$firs_part.=substr($first,-2);
}else{
	$firs_part.="**";
}
$display_email=$firs_part."@".$d[1];
}else{
	$display_email=$email;
}
?>

<div class="row">
    <div class="col-sm">
        <div class="form-field">
            <label class="mb-1"><?php echo __('setting_contact_info_user_id','User ID');?></label>
            <p><?php D($memberInfo->member_username)?></p>
        </div>
        <div class="form-field">
            <label class="mb-1"><?php echo __('setting_contact_info_name','Name');?></label>
            <p><?php ucwords(D($memberInfo->member_name));?></p>
        </div>
	</div>
    <div class="col-sm">
        <!-- Account Type -->
        <div class="form-field mb-0">
            <label class="mb-1"><?php echo __('setting_contact_info_email','Email');?></label>
            <p><?php D($display_email)?></p>
        </div>
    </div>
</div>