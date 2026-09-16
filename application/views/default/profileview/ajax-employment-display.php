<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<ul class="boxed-list-ul">			
<?php if($memberInfo){
	foreach($memberInfo as $employment){
		
	?>
<li class="employment-contain">
	<div class="boxed-list-item">
		<!-- Content -->
		<div class="item-content">
<?php if($is_editable){?>
	<div class="float-end">
		<a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle" data-popup="employment" data-popup-id="<?php D($employment->employment_id)?>" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit-2"></i></a> 
		<a href="javascript:void(0)" class="delete_account_btn btn btn-outline-danger btn-circle ms-1" data-popup="employment" data-popup-id="<?php D($employment->employment_id)?>" data-tippy-placement="top" title="Delete"><i class="icon-feather-trash"></i></a>
	</div>
<?php }?>
			<h4><?php D($employment->employment_title);?> | <?php D($employment->employment_company);?>
			
			
			</h4>
			<div class="item-details margin-top-7">
				<div class="detail-item"><a href="#"><i class="icon-material-outline-business"></i> <?php D($employment->employment_city);?>, <?php D($employment->country_name);?></a></div>
				<div class="detail-item"><i class="icon-material-outline-date-range"></i> <?php D(dateFormat($employment->employment_from,'M Y'));?> - <?php if($employment->employment_is_working_on==1){D('Present');}else{D(dateFormat($employment->employment_to,'M Y'));}?></div>
			</div>
			<div class="item-description">
				<p><?php D($employment->employment_description);?></p>
			</div>
		</div>
	</div>

</li>
<?php }
	}else{
		?>
		<!--<p class="text-center">No record</p>-->
		<?php
	}
?>
	
</ul>