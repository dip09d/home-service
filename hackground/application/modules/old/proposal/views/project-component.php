<?php
if($additional_fields){foreach($additional_fields as $k => $v){ 
$values = array();
foreach($v->component_values as $key => $val){
	$values[$key] = (array) $val;
}
?>

<?php if($v->component_type == 'SELECT'){ ?>
<div class="form-group">
	<label for="component_<?php echo $v->component_id;?>"><?php echo $v->component_name; ?></label>
	<select name="component[<?php echo $v->component_key; ?>]" class="form-control" id="component_<?php echo $v->component_id;?>" <?php if($v->is_required == '1'){ echo 'required'; }?>>
		<option value="">-Select-</option>
		<?php print_select_option($values, 'component_value_id', 'component_value_name', (!empty($detail['component'][$v->component_key]) ? $detail['component'][$v->component_key] : '')); ?>
	</select>
</div>
<?php } ?>

<?php if($v->component_type == 'TEXT'){ ?>
<div class="form-group">
	<label for="component_<?php echo $v->component_id;?>"><?php echo $v->component_name; ?></label>
	<input type="text" class="form-control" name="component[<?php echo $v->component_key; ?>]" id="component_<?php echo $v->component_id;?>" value="<?php echo !empty($detail['component'][$v->component_key]) ? $detail['component'][$v->component_key] : ''; ?>" />
</div>
<?php } ?>

<?php if($v->component_type == 'CHECKBOX'){ 
$check_values = array();
$check_values = !empty($detail['component'][$v->component_key])  ? $detail['component'][$v->component_key] : '';
if($check_values){
	$check_values = explode(',', $check_values);
}
?>
<div class="form-group">
	<p><b><?php echo $v->component_name; ?></b></p>
	
	<?php foreach($v->component_values as $key => $val){ ?>
	<div class="checkbox-inline">
		<input type="checkbox" name="component[<?php echo $v->component_key; ?>][]" value="<?php echo $val->component_value_id; ?>" class="magic-checkbox" id="component_value_<?php echo $val->component_value_id; ?>" <?php echo in_array($val->component_value_id, $check_values) ? 'checked' : ''; ?>>
		<label for="component_value_<?php echo $val->component_value_id; ?>"><?php echo $val->component_value_name; ?></label> 
	</div>
	<?php } ?>
	
</div>
<?php } ?>

<?php if($v->component_type == 'RADIO'){ ?>
<div class="form-group">
	<p><b><?php echo $v->component_name; ?></b></p>
	
	<?php foreach($v->component_values as $key => $val){ ?>
	<div class="radio-inline">
		<input type="radio" name="component[<?php echo $v->component_key; ?>]" value="<?php echo $val->component_value_id; ?>" class="magic-radio" id="component_value_<?php echo $val->component_value_id; ?>" <?php echo (!empty($detail['component'][$v->component_key]) && $detail['component'][$v->component_key] ==  $val->component_value_id) ? 'checked' : ''?>>
		<label for="component_value_<?php echo $val->component_value_id; ?>"><?php echo $val->component_value_name; ?></label> 
	</div>
	<?php } ?>
	
</div>
<?php } ?>

<?php } } ?>