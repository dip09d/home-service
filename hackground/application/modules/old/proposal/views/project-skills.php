<link href="<?php echo CSS;?>bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS;?>bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="<?php echo JS;?>typeahead.bundle.min.js"></script>
		
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
        <input type="hidden" name="ID" value="<?php echo $project_id;?>"/>
        <input type="hidden" name="page" value="<?php echo $page;?>"/>
        
        	<div class="form-group">
                <label for="experience_level" class="form-label">Select required skills</label>
                <div data-error-wrapper="skills">
                <input  class="form-control tagsinput_skill" name="skills" id="skills" value="<?php echo !empty($detail['skills']['skill_names']) ? implode(',', $detail['skills']['skill_names'])  : ''; ?>">
                </div>
            </div>		
            <button type="submit" class="btn btn-site">Save</button>			
</form>
	
	


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
(function(){
	var all_skills = <?php echo count($all_skills) > 0 ? json_encode($all_skills) : '[]';?>;
	var bhtn = new Bloodhound({
		local:all_skills,
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('skill_name'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
		});
var elts = $('.tagsinput_skill');
elts.tagsinput({
  itemValue: 'skill_id',
  itemText: 'skill_name',
  typeaheadjs: {
	limit: 25,
	displayKey: 'skill_name',
	hint: false,
	highlight: true,
	minLength: 1,
	source: bhtn.ttAdapter(),
	templates: {
	  notFound: [
		"<div class=empty-message>",
		"No match found",
		"</div>"
	  ].join("\n"),
	  suggestion: function(e) {  var test_regexp = new RegExp('('+e._query+')' , "gi"); return ('<div>'+ e.skill_name.replace(test_regexp,'<b>$1</b>')  + '</div>'); }
	}
  }
});

<?php if(!empty($detail['skills']['all_skills'])){ foreach($detail['skills']['all_skills'] as $skill){ ?>
elts.tagsinput('add', {skill_id:'<?php echo $skill['skill_id']; ?>', skill_name:'<?php echo $skill['skill_name']; ?>'});
<?php } } ?>

})();


</script>