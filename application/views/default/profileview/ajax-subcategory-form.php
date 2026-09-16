<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>

<select name="sub_category" id="sub_category" class="selectpicker browser-default" title="Speciality" data-live-search="true">
<?php
if($all_category_subchild){
	foreach($all_category_subchild as $category_subchild_list){
		?>
		<option value="<?php D($category_subchild_list->category_subchild_id);?>"><?php D(ucfirst($category_subchild_list->category_subchild_name));?></option>
		<?php
	}
}
?>
</select>
			            		