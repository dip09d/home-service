<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* echo '<pre>';
print_r($projectData);
echo '</pre>'; */
?>

<div class="dashboard-container">

<!-- Dashboard Content
	================================================== -->
	<div class="dashboard-content-container" >
		<div class="container">
		<div class="dashboard-content-inner" >
			
			<!-- Dashboard Headline -->
			
			<div class="row">
				<div class="col-xl-3">
                    <ul class="postJobStep" id="postJobStepItem">
                        <li class="active"><a ><i class="icon-line-awesome-pencil"></i><?php echo __('postproject_title','Title');?><i class="icon-material-outline-check-circle float-end"></i></a></li>
                        <li><a><i class="icon-line-awesome-pencil-square-o"></i><?php echo __('postproject_description','Description');?>  <i class="icon-material-outline-check-circle float-end"></i></a></li>
                        <li><a><i class="icon-line-awesome-file-text"></i><?php echo __('postproject_details','Details');?>  <i class="icon-material-outline-check-circle float-end"></i></a></li>
                        <li><a><i class="icon-line-awesome-lightbulb-o"></i><?php echo __('postproject_expertise','Expertise');?>  <i class="icon-material-outline-check-circle float-end"></i></a></li>
                        <li><a><i class="icon-material-outline-visibility"></i><?php echo __('postproject_visibility','Visibility');?>  <i class="icon-material-outline-check-circle float-end"></i></a></li>
                        <li><a><i class="icon-material-outline-monetization-on"></i><?php echo __('postproject_budget','Budget');?>  <i class="icon-material-outline-check-circle float-end"></i></a></li>
                        <li><a><i class="icon-material-baseline-star-border"></i><?php echo __('postproject_review','Review');?> <i></i></a></li>
                    </ul>
				</div>
				<div class="col-xl-9">				
                <form action="" method="post" accept-charset="utf-8" id="postprojectform" class="form-horizontal" role="form" name="postprojectform" onsubmit="return false;">  
                <input type="hidden" name="dataid" value="<?php echo $itemid;?>"/>
                <?php
                $this->layout->view('post-step-1', array('step'=>1,'all_category'=>$all_category,'projectData'=>$projectData),TRUE);
                
                $this->layout->view('post-step-2', array('step'=>2,'projectData'=>$projectData),TRUE);
                $this->layout->view('post-step-3', array('step'=>3,'all_projectType'=>$all_projectType,'projectData'=>$projectData),TRUE);
                $this->layout->view('post-step-4', array('step'=>4,'projectData'=>$projectData),TRUE);
                $this->layout->view('post-step-5', array('step'=>5,'projectData'=>$projectData),TRUE);
                $this->layout->view('post-step-6', array('step'=>6,'all_projectExperienceLevel'=>$all_projectExperienceLevel,'all_projectDuration'=>$all_projectDuration,'all_projectDurationTime'=>$all_projectDurationTime,'projectData'=>$projectData),TRUE);
                $this->layout->view('post-step-7', array('step'=>7,'projectData'=>$projectData),TRUE);
                
                ?>
                </form>		
					
				</div>
			</div>
			<!-- Row -->
			
			<!-- Row / End -->

			<!-- Footer -->
			<div class="dashboard-footer-spacer"></div>
			
			<!-- Footer / End -->

		</div>
		</div>
	</div>
	<!-- Dashboard Content / End -->

</div>
<!--<style>
form>div {
	display:block !important
}
</style>-->
<?php
if($projectData && $projectData['project_skills']){
    $myskills=$projectData['project_skills'];
?>
<script>
var pre_skills=<?php D(json_encode($myskills));?>;
</script>
<?php
}
?>
<script type="text/javascript">
	var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
	var all_skills=<?php D(json_encode($all_skills));?>;

	function load_data(type){
		$( "#project-"+type+"-data").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();
		if(type=='title'){
			$('#preview_title').html($('#title').val());
			$('#preview_category').html($('#category option:selected').text()+', '+$('#sub_category option:selected').text());
			
		}else if(type=='description'){
			$('#preview_description').html($('#description').val().replace(/\r?\n/g,'<br/>'));
			$('#preview_attachment_sec').hide();
			$('#preview_attachment').empty();
			$('#uploadfile_container .thumbnail_sec').each(function(){
				$('#preview_attachment').append('<div>'+$(this).text()+'</div>');
				$('#preview_attachment_sec').show();
			})
		}else if(type=='details'){
			var preview_projectType=$('input[name="projectType"]:checked').next('label').text();
			$('#preview_projectType').html(preview_projectType);
			var is_cover_required="No";
			if($('#is_cover_required').is(':checked')){
				var is_cover_required="Yes";
			}
			$('#preview_is_cover_required').html(is_cover_required);
			$('#addQuestion_container .question_sec').each(function(){
				$('#preview_question_sec').append('<p class="mb-2">'+$(this).find('input').val()+'</p>');
			})
			$('#preview_question_sec').show();
			
			
		}else if(type=='expertise'){
			$('#preview_skills').empty();
			$('#dataStep-4 .bootstrap-tagsinput span.tag').each(function(){
				console.log($(this).text());
				$('#preview_skills').append('<span class="m-1">'+$(this).text()+'</div>');
			})
		}else if(type=='visibility'){
			var projectVisibility=$('input[name="projectVisibility"]:checked').next('label').text();
			$('#preview_projectVisibility').html(projectVisibility);
			var member_required=$('input[name="member_required"]:checked').val();
			if(member_required=='S'){
				$('#preview_no_of_freelancer').html('1');
			}else{
				$('#preview_no_of_freelancer').html($('#no_of_freelancer').val());
			}
		}else if(type=='budget'){
			var projectPaymentType=$('input[name="projectPaymentType"]:checked').val();
			if(projectPaymentType=='hourly'){
				$('#preview_projectPaymentType').html('Pay by the hour');
			}else{
				$('#preview_projectPaymentType').html('Pay by the fixed'+" <?php D(priceSymbol())?>"+$('#fixed_budget').val());
			}
			var preview_experience_level=$('input[name="experience_level"]:checked').next('label').text();
			$('#preview_experience_level').html(preview_experience_level);
			var preview_hourly_duration=$('input[name="hourly_duration"]:checked').next('label').text();
			$('#preview_hourly_duration').html(preview_hourly_duration);
			var preview_hourly_duration_time=$('input[name="hourly_duration_time"]:checked').next('label').text();
			$('#preview_hourly_duration_time').html(preview_hourly_duration_time);		
		}

	}
	function uploadData(formdata){
	var len = $("#uploadfile_container div.thumbnail_sec").length;
   	var num = Number(len);
	num = num + 1;	
	$("#uploadfile_container").append('<div id="thumbnail_'+num+'" class="thumbnail_sec">'+SPINNER+'</div>');
    $.ajax({
        url: "<?php D(get_link('uploadFileFormCheckAJAXURL'))?>",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
           if(response.status=='OK'){
    			var name = response.upload_response.original_name;
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> <a href="<?php D(get_link('downloadTempURL'))?>/'+response.upload_response.file_name+'" target="_blank">'+name+'</a><a href="<?php D(VZ);?>" class=" text-danger ripple-effect ico float-end" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
		   }else{
		   		$("#thumbnail_"+num).html('<p class="text-danger">Error in upload file</p>');
		   }
           
        },
        
    }).fail(function(){
    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
    });
	}
	
var  main = function(){
	$('.selectpicker').selectpicker('refresh');
	$('#category').on('change',function(){
	$('.sub_category_display').show();
	$( "#load_sub_category").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();
		$.get( "<?php echo get_link('editprofileAJAXURL')?>",{'formtype':'getsubcat','Okey':$(this).val()}, function( data ) {
			setTimeout(function(){ $("#load_sub_category").html(data);$('.selectpicker').selectpicker('refresh');},1000)
		});
	});
	$('#addQuestion').on('click',function(){
		$('#addQuestion_container').append('<div class="question_sec input-group mb-3"><input type="text" class="form-control input-text with-border" name="question[]"><a href="<?php D(VZ);?>" class="btn text-danger" onclick="$(this).closest(\'.question_sec\').remove()"><i class="icon-feather-x f20"></i></a></div>');
		$('#is_cover_required_display').show();
	})
	$('.edit-project').on('click',function(){
		var secPopup=parseInt($(this).attr('data-popup'));
		for(var p=secPopup+1;p<=7;p++){
			$('#postJobStepItem li:nth-child('+p+')').removeClass('active');
		}
		$('#dataStep-7').hide();
		$('#dataStep-'+secPopup).show();
	})
	
	$('.backbtnproject').on('click',function(){
		var step=$(this).attr('data-step');
		if(step==1){
			return false;
		}
		var prevStep=parseInt(step)-1;
		$('#postJobStepItem li:nth-child('+step+')').removeClass('active');
		$('#dataStep-'+step).hide();
		$('#dataStep-'+prevStep).show();
	});
	$('.nextbtnproject').on('click',function(){
		var step=$(this).attr('data-step');
		var buttonsection=$(this);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="postprojectform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('postprojectFormCheckAJAXURL'))?>/"+step,
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					load_data(msg['preview_data']);
					if(step==7){
						window.location.href="<?php D(get_link('postprojectSuccessURL'))?>/"+msg['project_id'];
					}
					var nextStep=parseInt(step)+1;
					$('#postJobStepItem li:nth-child('+nextStep+')').addClass('active');
					$('#dataStep-'+step).hide();
					$('#dataStep-'+nextStep).show();
					
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})		
	});
	
	
	$('.no_of_freelancer_radio').on('change',function(){
		var no_of_freelancer_radio=$('.no_of_freelancer_radio:checked').val();
		if(no_of_freelancer_radio=='M'){
			$('.no_of_freelancer_display').show();
		}else{
			$('.no_of_freelancer_display').hide();
		}
	});
	$('.project_payment_type').on('change',function(){
		var project_payment_type=$('.project_payment_type:checked').val();
		if(project_payment_type=='fixed'){
			$('.hourly_project_display').hide();
			$('.fixed_project_display').show();
		}else{
			$('.fixed_project_display').hide();
			$('.hourly_project_display').show();
		}
	});
}
var mainload = function(){
	setTimeout(function(){
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
	        "<?php D('No match found')?>",
	        "</div>"
	      ].join("\n"),
	      suggestion: function(e) {  var test_regexp = new RegExp('('+e._query+')' , "gi"); return ('<div>'+ e.skill_name.replace(test_regexp,'<b>$1</b>')  + '</div>'); }
	    }
	  }
	});
	/* elts.on('beforeItemAdd', function(event) {
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
	}) */
	$.each(pre_skills, function(index, value) {
		elts.tagsinput('add', value);
		console.log(value);
	});
	},2000);
}

</script>