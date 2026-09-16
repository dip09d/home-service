<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<ul class="langList <?php if($is_editable){?>with-edit<?php }?>">
<?php if($memberInfo){
	foreach($memberInfo as $language){
		
	?>
<li class="language-contain">
<b><?php D($language->language_name)?>:</b> <?php D($language->language_preference_name)?>
<?php if($is_editable){?>
	<div class="float-end">
		<a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle" data-popup="language" data-popup-id="<?php D($language->member_language_id)?>" data-tippy-placement="top" title="Edit language"><i class="icon-feather-edit-2"></i></a> 
		<a href="javascript:void(0)" class="delete_account_btn btn btn-outline-danger btn-circle" data-popup="language" data-popup-id="<?php D($language->member_language_id)?>" data-tippy-placement="top" title="Delete language"><i class="icon-feather-trash"></i></a>
	</div>
<?php }?>
</li>
<?php }
	}
?>
</ul>