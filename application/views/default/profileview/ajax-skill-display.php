<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>	
<?php if($memberInfo){
	foreach($memberInfo as $skills){
		
	?>
<span><?php ucfirst(D($skills->skill_name));?></span>
<?php }
	}else{
		?>
		<!--<p class="text-center">No record</p>-->
		<?php
	}
?>