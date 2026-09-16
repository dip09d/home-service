<div class="row">
	<div class="col-sm-6">
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
			<input type="hidden" name="ID" value="<?php echo $member_id;?>"/>
			<input type="hidden" name="page" value="<?php echo $page;?>"/>
			<div class="box-body">
				<div id="all_industry">
					<?php if($detail['member_industry']){foreach($detail['member_industry'] as $k => $v){ ?>
					<div class="row" id="row_<?php echo ($k+1); ?>">
					<div class="col-md-4">
						<div class="form-group">
						<label>I've Got</label>
						<select class="form-control" name="experience[<?php echo ($k+1); ?>]">
							<?php print_select_option_assoc($experience, $v['experience']); ?>                     
						</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
						<label>Experience In</label>
						<select class="form-control" name="industry[<?php echo ($k+1); ?>]">
							<option value="">-Select-</option>                
							<?php print_select_option($options['industry'], 'category_subchild_id', 'category_subchild_name', $v['industry']); ?>
						</select>
						</div>
					</div>
					<div class="col-md-4">
						<h5>&nbsp;</h5>
						<a href="javascript:void(0)" onclick="removeIndustryRow('<?php echo ($k+1); ?>')">[Remove]</a>
					</div>
				</div>

					<?php } } ?>
				</div>
				
				<div class="form-group">
					<button type="button" class="btn btn-site" id="add_industry">Add Industry</button>
				 </div>
 
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn-block btn btn-primary">Save</button>
				</div>
	
			</div>
		</form>
</div>
</div>

<script type="text/template" id="new_row_template">
<div class="row" id="row_{ROW_ID}">
	<div class="col-md-4">
		<div class="form-group">
		<label>I've Got</label>
		<select class="form-control" name="experience[{ROW_ID}]">
			<?php print_select_option_assoc($experience); ?>                     
		</select>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
		<label>Experience In</label>
		<select class="form-control" name="industry[{ROW_ID}]">
			<option value="">-Select-</option>                
			<?php print_select_option($options['industry'], 'category_subchild_id', 'category_subchild_name'); ?>
		</select>
		</div>
	</div>
	<div class="col-md-4">
		<h5>&nbsp;</h5>
		<a href="javascript:void(0)" onclick="removeIndustryRow('{ROW_ID}')">[Remove]</a>
	</div>
</div>
</script>


<script>

(function(){
	
'use strict';

var add_industry = $('#add_industry');
var ROW_ID = <?php echo count($detail['member_industry']) > 0 ? (count($detail['member_industry'])+1) : 1 ?>;

add_industry.click(function(){
	var html = $('#new_row_template').html();
	html = html.replace(/{ROW_ID}/g, ROW_ID);
	$('#all_industry').append(html);
	ROW_ID++;
});

function removeIndustryRow(row_id){
	$('#all_industry').find('#row_'+row_id).remove();
}	

window.removeIndustryRow = removeIndustryRow;
})();
	
</script>

<script>
function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>