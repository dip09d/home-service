<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<ul class="boxed-list-ul">			
<?php if($memberInfo){
	foreach($memberInfo as $education){
		
	?>
<li class="education-contain">
	<div class="boxed-list-item">
		<!-- Content -->
		<div class="item-content">
<?php if($is_editable){?>
	<div class="float-end">
		<a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle" data-popup="education" data-popup-id="<?php D($education->education_id)?>" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit-2"></i></a> 
		<a href="javascript:void(0)" class="delete_account_btn btn btn-outline-danger btn-circle ms-1" data-popup="education" data-popup-id="<?php D($education->education_id)?>" data-tippy-placement="top" title="Delete"><i class="icon-feather-trash"></i></a>
	</div>
<?php }?>
			<h4><?php D($education->education_degree); if($education->education_area_of_study){D(', '.$education->education_area_of_study);} if($education->education_degree || $education->education_area_of_study){D(' | ');}?><?php D($education->education_school);?></h4>
			<div class="item-details margin-top-7">
				<div class="detail-item"><i class="icon-material-outline-date-range"></i> <?php D($education->education_from_year);?> - <?php D($education->education_end_year);?></div>
			</div>
			<div class="item-description">
				<p><?php D($education->education_description);?></p>
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