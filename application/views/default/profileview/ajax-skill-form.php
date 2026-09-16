<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('profileview_skill_myskill','My skills');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveSkill(this)"><?php echo __('profileview_save','Save');?></button>
      </div>
    <div class="modal-body">
	    <div class="row">
			<div class="col">
				<form action="" method="post" accept-charset="utf-8" id="skillform" class="form-horizontal" role="form" name="skillform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
					<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<p><?php echo __('profileview_skill_use_this','Use this space to show clients you have the skills and experience they are looking for.');?></p>
								<div class="keywords-list skillContaintag">
									
								</div>
								<input  class="form-control input-text with-border tagsinput_skill" name="skills" id="skills" data-role="tagsinput" value="1,2">
								<span id="skillsError" class="rerror"></span>
							</div>
						</div>
       				</div>
       			</form>
       		</div>
       	</div>
    </div>
<script type="text/javascript">
var pre_skills=<?php D(json_encode($memberInfo->skills));?>;
function load_tag_input(sourcedata){
var elt = $('input.tagsinput_skill');
elt.tagsinput({
  itemValue: 'skill_id',
  itemText: 'skill_name',
  typeaheadjs: {
  	limit: 25,
  	displayKey: 'skill_name',
    hint: false,
    highlight: true,
    minLength: 1,
    source: sourcedata.ttAdapter(),
    templates: {
      notFound: [
        "<div class=empty-message>",
        "<?php D('No match found')?>",
        "</div>"
      ].join("\n"),
      suggestion: function(e) {  var test_regexp = new RegExp('('+e._query+')' , "gi"); return ('<div>'+ e.skill_name.replace(test_regexp,'<b>$1</b>')  + '</div>'); }
    }
  }
});
/*elt.on('beforeItemAdd', function(event) {
	var itemdata=event.item;
	console.log(itemdata);
	var key=itemdata.skill_id;
	if($(".skill_set_"+key).length>0){	
	}else{
		var name=key;
		var html='<span class=" keyword skill_set_'+key+'" ><span class="keyword-remove"></span><span class="keyword-text">'+itemdata.skill_name+'</span><input type="hidden" name="byskills[]" value="'+name+'"/><input type="hidden" name="byskillsname[]" value="'+itemdata.skill_key+'"/></span>';
		$(".skillContaintag").append(html);
		

	}
	//console.log(event.item);
	event.cancel = true;
})*/
if(pre_skills){
	for(var i=0; i<pre_skills.length; i++){
		elt.tagsinput('add', { "skill_id": pre_skills[i]['skill_id'] , "skill_key": pre_skills[i]['skill_key']   , "skill_name": pre_skills[i]['skill_name']});
	}
}

}
    </script>