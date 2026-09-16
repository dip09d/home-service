<style>
.sidebar-widget {
	margin-bottom: 20px;
	display: block;
}
.job-overview {
	border-radius: 4px;
	background-color: #f9f9f9;
}
.job-overview .job-overview-headline {
	color: #333;
	font-size: 2rem;
	padding: 12px 20px;
	background-color: #f0f0f0;
	color: #333;
	position: relative;
	border-radius: 4px 4px 0 0;
}
.job-overview .job-overview-inner {
	padding: 20px;
}
.job-overview .job-overview-inner ul {
	padding: 0;
	margin: 0;
	list-style: none;
}
.job-overview .job-overview-inner ul li {
	position: relative;
	display: block;
	padding-left: 30px;
	margin-bottom: 1rem;
}
.job-overview .job-overview-inner ul li span {
	font-weight: 600;
	color: #333;
	margin: 0;
	padding: 0;
	display: block;
}
.job-overview .job-overview-inner ul li i {
	position: absolute;
	left: 0;
	top: 0;
	font-size: 24px;
	color: #66676b;
}
</style>
<?php
$child_level = 1;
$last_category = 0;

if(!empty($category)){
	$child_level = 1;
	$last_category = $detail['category']['category']['id'];
}

if(!empty($sub_category)){
	$child_level = 2;
	$last_category = $detail['category']['sub_category']['id'];
}


?>

<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
  <input type="hidden" name="ID" value="<?php echo $project_id;?>"/>
  <input type="hidden" name="page" value="<?php echo $page;?>"/>
  <div class="row">
    <div class="col-sm-6">
      <div class="form-group">
        <label for="project_title" class="form-label">Project Title </label>
        <input type="text" class="form-control reset_field" id="project_title" name="project[project_title]" autocomplete="off" value="<?php echo $detail['project_title']; ?>">
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="category_id" class="form-label">Category </label>
        <select name="category_id" class="form-control fetch-category" data-level="1" data-target="#category_subchild_id" id="category_id">
          <option value="">-Select-</option>
          <?php print_select_option($category, 'category_id', 'category_name', (!empty($detail['category']['category']) ? $detail['category']['category']['id'] : '')); ?>
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="form-group">
        <label for="category_subchild_id" class="form-label">Speciality </label>
        <select name="category_subchild_id" class="form-control" data-level="2" data-target="#category_subchild_level_3_id" id="category_subchild_id">
          <option value="">-Select-</option>
          <?php print_select_option($sub_category, 'category_subchild_id', 'category_subchild_name', (!empty($detail['category']['sub_category']) ? $detail['category']['sub_category']['id'] : '')); ?>
        </select>
      </div>
    </div>
  </div>
  <button type="submit" class="btn btn-site">Save</button>
</form>
<script>

function get_category(id, target, level){
	var type = '';
	switch(target){
		case '#category_subchild_id':
		type = 'category_subchild';
		break;
		
		case '#category_subchild_level_3_id':
		type = 'category_subchild_level_3';
		break;
		
		case '#category_subchild_level_4_id':
		type = 'category_subchild_level_4';
		break;
		
	}
	
	hide_and_reset_all_child(level);
	
	$.get('<?php echo base_url('proposal/get_category');?>?type='+type+'&id='+id, function(res){
		if(res == 0){
			/* get_category_component(id, level); */
			$(target).html('<option value=""> - Select -</option>');
			show_direct_child(level);
		}else{
			$(target).html(res);
			/* if(type == 'category_subchild'){
				reset_select('#category_subchild_level_3_id');
				reset_select('#category_subchild_level_4_id');
			}else if(type == 'category_subchild_level_3'){
				reset_select('#category_subchild_level_4_id');
				
			} */
			show_direct_child(level);
			
		}
		
		
		
	});
}

function hide_and_reset_all_child(level){
	$("[data-level]").filter(function() {
		return $(this).data('level') > level;
	}).parent().hide();
	
	$("[data-level]").filter(function() {
		return $(this).data('level') > level;
	}).html('<option value="">-Select-</option>');
}

function hide_all_child(level){
	$("[data-level]").filter(function() {
		return $(this).data('level') > level;
	}).parent().hide();
}

function show_direct_child(level){
	var direct_child = parseInt(level)+1;
	$("[data-level]").filter(function() {
		return $(this).data('level') == direct_child;
	}).parent().show();
}


function reset_select(ele){
	$(ele).html('<option value="">-Select-</option>');
}


function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

$('.fetch-category').change(function(){
	var val = $(this).val();
	var target = $(this).data('target');
	var level = $(this).data('level');
	if(!val){
		reset_select(target);
		return false;
	}
	
	get_category(val, target, level);
	
});


$(document).ready(function(){
	hide_and_reset_all_child('<?php echo $child_level; ?>');
});
</script>