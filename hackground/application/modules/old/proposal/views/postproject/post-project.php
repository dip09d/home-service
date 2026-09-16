<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="<?php echo JS;?>upload-drag-file.js" type="text/javascript"></script>
<link href="<?php echo CSS;?>bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS;?>bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="<?php echo JS;?>typeahead.bundle.min.js"></script>
<style>
.dashboard-box {
    display: block;
    border-radius: 4px;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-top: 25px;
    position: relative;
}

.margin-top-0 {
    margin-top: 0px !important;
}
.dashboard-box .headline {
    display: block;
    padding: 1rem 1.333rem;
    border-bottom: 1px solid #e4e4e4;
    position: relative;
}
.dashboard-box .headline h3, .dashboard-box .headline h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0;
}
.content {
    min-height: auto;
}

.dashboard-nav ul li {
    display: block;
    border-left: 3px solid transparent;
    transition: 0.3s;
    line-height: 25px;
}

.dashboard-nav ul li a {
    color: #707070;
    display: block;
    padding: 11px 20px;
    transition: 0.3s;
    cursor: pointer;
    position: relative;
}

.dashboard-nav ul li a i {
    padding-right: 8px;
    width: 20px;
    font-size: 20px;
    color: #909090;
    transition: 0.3s;
    position: relative;
    top: 2px;
}

.dashboard-nav ul li.active-submenu a i, .dashboard-nav ul li.active a i, .dashboard-nav ul li:hover a i {
    color: #66676b;
}
a, .header-widget .log-in-button:hover i, .notification-text span.color, .header-notifications-content .notification-text span.color, ul.user-menu-small-nav li a:hover, ul.user-menu-small-nav li a:hover i, #navigation ul li:hover a:after, #navigation ul li a.current, #navigation ul li a.current:after, #navigation ul li:hover a, #breadcrumbs ul li a:hover, #breadcrumbs.white ul li a:hover, .single-page-header .header-details li a:hover, .blog-post-content h3 a:hover, #posts-nav li a:hover, .task-tags > span, .task-tags > a, .freelancer-detail-item a:hover, .list-4 li:before, .list-3 li:before, .list-2 li:before, .list-1 li:before, .share-buttons-content span strong, .keyword, .banner-headline strong.color, .category-box-icon, .recommended .pricing-plan-label, .recommended .pricing-plan-label strong, .boxed-widget.summary li.total-costs span, .testimonial-box:before, .single-counter h3, .popup-tabs-nav li.active a, .dialog-with-tabs .forgot-password:hover, .dropdown-menu > li > a:hover, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .bootstrap-select .dropdown-menu li.selected a span.check-mark:before, .bootstrap-select .dropdown-menu li.selected:hover a span.check-mark:before, .boxed-list-headline h3 i, .boxed-list-item .item-details .detail-item a:hover, .job-overview .job-overview-inner ul li i, .dashboard-nav ul:before, .dashboard-nav ul li.active-submenu a i, .dashboard-nav ul li.active a i, .dashboard-nav ul li:hover a i, .dashboard-nav ul li.active-submenu a, .dashboard-nav ul li:hover a, .dashboard-nav ul li.active a, .dashboard-nav ul li.active-submenu a:after, .dashboard-nav ul li.active a:after, .dashboard-nav ul li:hover a:after, .help-icon, .header-widget .log-in-button:hover, .header-widget .log-in-button:hover i {
    color: #40de00;
}

.dashboard-box-footer {
    background-color: #f9f9f9;
    padding: 1rem 1.25rem;
    border-top: 1px solid #e4e4e4;
}

.upload-area {
    width: 100%;
    max-height: 100px;
    border: 2px dashed #40de00;
    border-radius: 4px;
    text-align: center;
    overflow: auto;
    padding: 1rem;
    margin-bottom: 0.25rem;
	cursor: pointer;
}
.upload-area h4 {
    color: #999;
    font-weight: 500;
    font-size: 1rem;
    line-height: 20px;
    margin-bottom: 0;
}
#fileinput {
    display: none;
}
.myradio .btn-group {
    display: flex;
}
.myradio .btn-group > .btn {
    border: 1px solid #ddd;
    border-radius: 4px !important;
    flex: 1;
    padding: 2rem 1rem;
}
.myradio .btn-group > .btn i {
    font-size: 2rem;
}
.myradio .btn-group > .btn:active, .myradio .btn-group > .btn.active {
    color: #00a500;
    background-color: #ffffff;
    border-color: #00a500;
}

.myradio .btn-group .btn + .btn {
    margin-left: 1rem;
}
</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
        <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
     <?php echo $breadcrumb ? $breadcrumb : '';?>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
		<div class="box">
			<div class="box-header with-border">
			  <h3 class="box-title"><?php echo $title ? $title : '';?></h3>
			</div>
       
			<div class="box-body table-responsive">
			<div class="row">
				<div class="col-xl-3">
					<div class="dashboard-nav">
					<div class="dashboard-nav-inner">

						<ul id="postJobStepItem" class="margin-top-50">
							<li class="active"><a ><i class="icon-line-awesome-pencil"></i> Title <i class="icon-material-outline-check-circle pull-right float-right"></i></a></li>
							<li><a><i class="icon-line-awesome-pencil-square-o"></i> Description <i class="icon-material-outline-check-circle pull-right float-right"></i></a></li>
							<li><a><i class="icon-line-awesome-file-text"></i> Details <i class="icon-material-outline-check-circle pull-right float-right"></i></a></li>
							<li><a><i class="icon-line-awesome-lightbulb-o"></i> Expertise <i class="icon-material-outline-check-circle pull-right float-right"></i></a></li>
							<li><a><i class="icon-material-outline-visibility"></i> Visibility <i class="icon-material-outline-check-circle pull-right float-right"></i></a></li>
							<li><a><i class="icon-material-outline-monetization-on"></i> Budget <i class="icon-material-outline-check-circle pull-right float-right"></i></a></li>
							<li><a><i class="icon-material-outline-check"></i> Review</a></li>
						</ul>
						
					</div>
				</div>
					
					
					
				</div>
				<div class="col-xl-9">
				
			<form action="" method="post" accept-charset="utf-8" id="postprojectform" class="form-horizontal" role="form" name="postprojectform" onsubmit="return false;">  
            <input type="hidden" name="member_id" value="<?php echo $member_id;?>"/>
			<?php
			$this->layout->view('postproject/post-step-1', array('step'=>1,'all_category'=>$all_category),TRUE);
			
			$this->layout->view('postproject/post-step-2', array('step'=>2),TRUE);
			$this->layout->view('postproject/post-step-3', array('step'=>3,'all_projectType'=>$all_projectType),TRUE);
			$this->layout->view('postproject/post-step-4', array('step'=>4),TRUE);
			$this->layout->view('postproject/post-step-5', array('step'=>5),TRUE);
			$this->layout->view('postproject/post-step-6', array('step'=>6,'all_projectExperienceLevel'=>$all_projectExperienceLevel,'all_projectDuration'=>$all_projectDuration,'all_projectDurationTime'=>$all_projectDurationTime),TRUE);
			$this->layout->view('postproject/post-step-7', array('step'=>7),TRUE);
			
			?>
			</form>		
					
				</div>
			</div>
			</div>
		 <!-- /.box-body -->
		</div>
      <!-- /.box -->
	</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>


<script type="text/javascript">
	var SPINNER='<svg class="MYspinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
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
			var preview_projectType=$('input[name="projectType"]:checked').parent('label').text();
			$('#preview_projectType').html(preview_projectType);
			var is_cover_required="No";
			if($('#is_cover_required').is(':checked')){
				var is_cover_required="Yes";
			}
			$('#preview_is_cover_required').html(is_cover_required);
			$('#addQuestion_container .question_sec').each(function(){
				$('#preview_question_sec').append('<p class="margin-bottom-5">'+$(this).find('input').val()+'</p>');
			})
			$('#preview_question_sec').show();
			
			
		}else if(type=='expertise'){
			$('#preview_skills').empty();
			$('#dataStep-4 .bootstrap-tagsinput span.tag').each(function(){
				console.log($(this).text());
				$('#preview_skills').append('<span class="m-1">'+$(this).text()+'</div>');
			})
		}else if(type=='visibility'){
			var projectVisibility=$('input[name="projectVisibility"]:checked').parent('label').text();
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
			var preview_experience_level=$('input[name="experience_level"]:checked').parent('label').text();
			$('#preview_experience_level').html(preview_experience_level);
			var preview_hourly_duration=$('input[name="hourly_duration"]:checked').parent('label').text();
			$('#preview_hourly_duration').html(preview_hourly_duration);
			var preview_hourly_duration_time=$('input[name="hourly_duration_time"]:checked').parent('label').text();
			$('#preview_hourly_duration_time').html(preview_hourly_duration_time);		
		}

	}
	function uploadData(formdata){
	var len = $("#uploadfile_container div.thumbnail_sec").length;
   	var num = Number(len);
	num = num + 1;	
	$("#uploadfile_container").append('<div id="thumbnail_'+num+'" class="thumbnail_sec">'+SPINNER+'</div>');
    $.ajax({
        url: "<?php echo base_url('proposal/uploadattachment'); ?>",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
           if(response.status=='OK'){
    			var name = response.upload_response.original_name;
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> <a href="'+response.upload_response.file_url+'" target="_blank">'+name+'</a><a href="<?php D(VZ);?>" class=" text-danger ripple-effect ico float-right" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
		   }else{
		   		$("#thumbnail_"+num).html('<p class="text-danger">Error in upload file</p>');
		   }
           
        },
        
    }).fail(function(){
    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
    });
	}
	
var  main = function(){

	$('#category').on('change',function(){
	$('.sub_category_display').show();
	
		$.get( "<?php echo base_url('proposal/get_category'); ?>",{'type':'category_subchild','id':$(this).val()}, function( data ) {
			if(data != '0'){
				$("#load_sub_category").find('select').html(data);
			}else{
				$("#load_sub_category").find('select').html('<option value="">-Select-</option>');
			}
			
		});
	});
	$('#addQuestion').on('click',function(){
		$('#addQuestion_container').append('<div class="question_sec input-group margin-bottom-10"><input type="text" class="form-control input-text with-border" name="question[]"><div class="input-group-append"><a href="<?php D(VZ);?>" class="btn text-danger" onclick="$(this).closest(\'.question_sec\').remove()"><i class="icon-feather-x f20"></i></a></div></div>');
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
	        url: "<?php echo base_url('proposal/post_project_form_check'); ?>/"+step,
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					load_data(msg['preview_data']);
					if(step==7){
						window.location.href="<?php echo base_url('proposal/success');?>/"+msg['project_id'];
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
	},2000);
}

var error_icon='<span class=" icon-line-awesome-exclamation-circle" aria-hidden="true"></span>';
function registerFormPostResponse(formnameid,errors) {
    clearErrors();
    $('#'+formnameid+' input[type="text"] , #'+formnameid+' input[type="password"], #'+formnameid+' input[type="date"], #'+formnameid+' input[type="number"] , #'+formnameid+' textarea').removeClass('is-invalid').addClass('is-valid');
    if (errors.length > 0) {
        for (i = 0; i < errors.length; i++) {
            showError(formnameid,errors[i].id, errors[i].message);
        }
    }
	/*var error_ele = $('.rerror').not(':empty').offset();
	if(error_ele){
		$(window).scrollTop(error_ele.top - 150);
	}*/
}
function clearErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.rerror').hide();
    $('.invalid-feedback').removeClass('invalid-feedback');
}
function showError(formnameid,field,message) {
	$('#'+formnameid+' #'+field).addClass('is-invalid');
	$('#'+formnameid+' #'+field+'Error').addClass('invalid-feedback').html(error_icon+' '+message).show();
	if($("#"+formnameid+" input[name=recaptcha_response_field]").length){
		Recaptcha.reload();
	}
}


main();
mainload();
</script>