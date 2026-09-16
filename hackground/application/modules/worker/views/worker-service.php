<link href="<?php echo CSS;?>bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS;?>bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="<?php echo JS;?>typeahead.bundle.min.js"></script>

<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $worker_id;?>"/>
    <input type="hidden" name="page" value="<?php echo $page;?>"/>
            
    <div class="form-group">
        <label for="experience_level" class="form-label">Select Service</label>
        <div data-error-wrapper="skills">
        <input  class="form-control tagsinput_skill" name="skills" id="skills" value="">
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
	var all_service = <?php echo count($all_service) > 0 ? json_encode($all_service) : '[]';?>;
	var bhtn = new Bloodhound({
		local:all_service,
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('category_subchild_name'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
		});
var elts = $('.tagsinput_skill');
elts.tagsinput({
  itemValue: 'category_subchild_id',
  itemText: 'category_subchild_name',
  typeaheadjs: {
	limit: 25,
	displayKey: 'category_subchild_name',
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
	  suggestion: function(e) {  var test_regexp = new RegExp('('+e._query+')' , "gi"); return ('<div>'+ e.category_subchild_name.replace(test_regexp,'<b>$1</b>')  + '</div>'); }
	}
  }
});

<?php if(!empty($detail['worker_service']['all_service'])){ foreach($detail['worker_service']['all_service'] as $skill){ ?>
elts.tagsinput('add', {category_subchild_id:'<?php echo $skill['category_subchild_id']; ?>', category_subchild_name:'<?php echo $skill['category_subchild_name']; ?>'});
<?php } } ?>

})();

</script>