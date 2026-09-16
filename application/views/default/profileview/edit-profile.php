<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//get_print($memberInfo,FALSE);
if(!$is_editable){
?>
<style>
.portfolio-action-btn .edit_account_btn{
	display: block !important;
}
.edit_account_btn, .delete_account_btn, .edit_logo_btn {
	display: none !important;
}
</style>
<?php }?>
<style>
.cropper-view-box, .cropper-face {
	border-radius: 50%;
}
</style>
<div id="edit-profile-page">
  <?php
$logo=getMemberLogo($member_id);
$is_fav_class="";
if($login_user_id){
	$is_fav = isFavouriteMember($login_user_id, $member_id);
	if($is_fav){
		$is_fav_class="active";
	}	
}
?>
  <!-- Titlebar -->
  <div class="single-page-header freelancer-header" data-background-image="<?php // echo IMAGE;?>">
    <div class="container">
      <div class="single-page-header-inner">
        <div class="start-side"><!--<?php // echo IMAGE;?>default-member-logo.svg-->
          <div class="header-image freelancer-avatar" id="" style="position: relative">
            <ec id="crop-avatar-dashboard" style="width: 100%">
              <input type="hidden" name="logo" id="logo" class="replceLogoVal">
              <img src="<?php D($logo);?>" alt=""><a href="javascript:void(0)" class="edit_logo_btn btn btn-light btn-circle text-site" data-popup="logo" data-tippy-placement="top" title="Change avatar"><i class="icon-feather-edit-2"></i></a></ec>
          </div>
          <div class="header-details">
            <h1>
              <?php D(ucwords($memberInfo->member_name))?> <?php if($memberInfo->country_code_short){?>
                <img class="flag" src="<?php echo IMAGE;?>flags/<?php D(strtolower($memberInfo->country_code_short));?>.svg" alt="<?php D(ucfirst($memberInfo->country_name))?>" title="<?php D(ucfirst($memberInfo->country_name))?>" data-tippy-placement="top" height="16">
                <?php }?>
              <span class="show_edit_btn">
              <ec id="profile-heading-data">
                <?php D(ucfirst($memberInfo->member_heading))?>
              </ec>
              <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site ms-2 btn-circle" data-popup="heading" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit-2"></i></a></span>              
              </h1>
            <ul>
              <li>
                <div class="star-rating" data-rating="<?php echo round($memberInfo->avg_rating,1);?>"></div>
              </li>
              <?php if($memberInfo->badges){
              	?>
              	<li>
              	<?php
              	foreach($memberInfo->badges as $b=>$badge){
              		$badge_icon=UPLOAD_HTTP_PATH.'badge-icons/'.$badge->icon_image;
				?>
				<img src="<?php echo $badge_icon;?>" alt="<?php echo $badge->name;?>" height="26" width="26" data-tippy-placement="top" title="<?php echo $badge->name;?>"  /> &nbsp;
				<?php	
				}
				?>
				</li>
				<?php
				}
              	?>              
            </ul>
          </div>
        </div>
        <div class="end-side">        	
          <div class="ms-auto" style="min-width: 150px;">          	
            <p class="mb-0"><?php echo __('profileview_profile_job_success','Job Success');?> <strong><?php echo $memberInfo->success_rate;?>%</strong></p>
            <div class="progress" style="max-width:200px; height:6px;margin-bottom:10px">
              <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?php echo $memberInfo->success_rate;?>%" aria-valuenow="<?php echo $memberInfo->success_rate;?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
			<?php if(!$is_editable){?>
			<a href="<?php echo VZ;?>" data-mid="<?php echo md5($member_id);?>" class="btn btn-primary hire-member btn-sm me-2"><?php echo __('profileview_profile_hire_freelancer','Hire Freelancer');?></a>
            <a href="<?php echo VZ;?>" data-mid="<?php echo md5($member_id);?>" class="btn btn-outline-site invite-member btn-sm me-2"><?php echo __('profileview_profile_invite','Invite to Job');?></a>			
			<a href="<?php echo VZ;?>" class="btn btn-circle action_favorite <?php echo $is_fav_class;?>" data-mid="<?php echo md5($member_id);?>"><i class="icon-feather-heart"></i></a>		
			<?php }?>
          </div>
        </div>
      </div>
    </div>
  </div>
  
<section class="section">
  <div class="container">
    <div class="row"> 
      <!-- Content -->
      <div class="col-lg-8"> 
        
        <!-- Page Content -->
        <div class="panel mb-4" id="job-about">
          <div class="panel-header d-flex">
            <h4 class="show_edit_btn"><?php echo __('profileview_profile_about','About');?></h4>
            <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle ico" data-popup="overview" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit-2"></i></a>
          </div>
          <div class="panel-body">
            <ec id="profile-overview-data">
              <?php D(ucfirst(nl2br($memberInfo->member_overview)))?>
            </ec>
          </div>
        </div>
        
        <!-- Boxed List -->
        <div class="boxed-list mb-4" id="job-experience">
          <div class="boxed-list-headline">
            <h4><?php echo __('profileview_profile_hist_feed','Work History and Feedback');?></h4>
          </div>
			<div id="profile-reviews-data"></div>
          

          <div class="clearfix"></div>
          <!-- Pagination / End --> 
          
        </div>
        <!-- Boxed List / End --> 
        <!-- Boxed List -->
        
        <div class="boxed-list mb-4" id="job-portfolio">
          <div class="boxed-list-headline d-flex">
            <h4><?php echo __('profileview_profile_protfolio','Portfolio');?> </h4>
            <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle ico" data-popup="portfolio" data-tippy-placement="top" title="Add">
              <icon class="icon-feather-plus"></icon>
              </a>
          </div>
          <ul class="boxed-list-ul">
            <li class="">
              <div id="profile-portfolio-data"></div>
            </li>
          </ul>
        </div>
        <!-- Boxed List / End --> 
        <!-- Boxed List -->
        <div class="boxed-list mb-4">
          <div class="boxed-list-headline d-flex">
            <h4><?php echo __('profileview_profile_em_history','Employment History');?></h4> <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle ico" data-popup="employment" data-tippy-placement="top" title="Add"><icon class="icon-feather-plus"></icon>
              </a>
          </div>
          <div id="profile-employment-data"> </div>
        </div>
        <!-- Boxed List / End --> 
        
        <div class="panel mb-4">
            <div class="panel-header d-flex">
              <h4><?php echo __('profileview_profile_skill','Skills');?></h4> <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle ico" data-popup="skill" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit-2"></i></a>
            </div>
            <div class="panel-body task-tags" id="profile-skill-data"> </div>
          </div>
          
        <!-- Boxed List -->
        <div class="boxed-list mb-4">
          <div class="boxed-list-headline d-flex">
            <h4><?php echo __('profileview_profile_education','Education');?></h4> <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle ico" data-popup="education" data-tippy-placement="top" title="Add"><icon class="icon-feather-plus"></icon>
              </a>
          </div>
          <div id="profile-education-data"> </div>
        </div>
        <!-- Boxed List / End --> 
        
      </div>
      
      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="sidebar-container">
          <div class="panel mb-4">
            <div class="panel-body"> 
              <!-- Profile Overview -->
              <ul class="list-group-0">
                <li>
                <span><?php echo __('profileview_profile_hou_rate','Hourly Rate');?> </span>
                <strong>
                  <ec id="profile-hourly-data">
                    <?php if($memberInfo->member_hourly_rate && $memberInfo->member_hourly_rate>0){D(priceSymbol().priceFormat($memberInfo->member_hourly_rate));}elseif(!$is_editable){D('Not set');}else{D('Set');}?>
                  </ec>
                  <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle float-end ms-2" data-popup="hourly" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit-2"></i></a></strong>
                </li>
                <li>
                	<span><?php echo __('profileview_profile_work_hour','Total Working Hour');?></span>
                	<strong><?php D(displayamount($memberInfo->total_working_hour,2));?></strong>                
                </li>
                <li>
                  <span><?php echo __('profileview_profile_earned','Earned');?></span>
                  <strong>
                  <?php D(priceSymbol().priceFormat($memberInfo->total_earning));?>
                  </strong>
                </li>
                <li>
                <span><?php echo __('profileview_profile_job','Jobs');?></span>
                <strong>
                  <?php D($memberInfo->total_jobs);?>
                  </strong>
                </li>
                <li> 
                <span><?php echo __('profileview_profile_availability','Availability');?></span>
                <strong>
                  <ec id="profile-availability-data">
                    <?php if($memberInfo->not_available_until){
                    		echo 'Offline till '.dateFormat($memberInfo->not_available_until);
                    	}elseif($memberInfo->available_per_week){
	                    	$duration=getAllProjectDurationTime($memberInfo->available_per_week);
	                    	D($duration['freelanceName']);
                    	}elseif(!$is_editable){
                    		D('Not set');
                    	}else{
                    		D('Set');
                    	}?>
                  </ec>
                  
                  <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle float-end ms-2" data-popup="availability" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit-2"></i></a></strong>
                </li>
              </ul>
              
              <!-- Button --> 
              <!--<a href="#small-dialog" class="btn btn-primary apply-now-button popup-with-zoom-anim">Make an Offer</a>--> 
            </div>
          </div>
          <!-- Freelancer Indicators -->
          <div class="sidebar-widget d-none">
            <div class="freelancer-indicators"> 
              
              <!-- Indicator -->
              <div class="indicator"> <strong>88%</strong>
                <div class="indicator-bar" data-indicator-percentage="88"><span></span></div>
                <span><?php echo __('profileview_profile_job_success','Job Success');?></span> </div>
              
              <!-- Indicator -->
              <div class="indicator"> <strong>100%</strong>
                <div class="indicator-bar" data-indicator-percentage="100"><span></span></div>
                <span><?php echo __('profileview_profile_recommendation','Recommendation');?></span> </div>
              
              <!-- Indicator -->
              <div class="indicator"> <strong>90%</strong>
                <div class="indicator-bar" data-indicator-percentage="90"><span></span></div>
                <span><?php echo __('profileview_profile_on_time','On Time');?></span> </div>
              
              <!-- Indicator -->
              <div class="indicator"> <strong>80%</strong>
                <div class="indicator-bar" data-indicator-percentage="80"><span></span></div>
                <span><?php echo __('profileview_profile_on_budget','On Budget');?></span> </div>
            </div>
          </div>
          <div class="panel mb-4">
            <div class="panel-header d-flex">
              <h4><?php echo __('profileview_profile_language','Language');?></h4>
              <a href="javascript:void(0)" class="edit_account_btn btn btn-outline-site btn-circle ico" data-popup="language" data-tippy-placement="top" title="Add language"><i class="icon-feather-plus"></i></a>
            </div>
            <div class="panel-body" id="profile-language-data"> </div>
          </div>          
          
          <!-- Widget -->
          <div class="sidebar-widget d-none">
            <h3><?php echo __('profileview_profile_attachment','Attachments');?></h3>
            <div class="attachments-container"> <a href="#" class="attachment-box"><span><?php echo __('profileview_profile_letter','Cover Letter');?></span><i>PDF</i></a> <a href="#" class="attachment-box"><span><?php echo __('profileview_profile_contract','Contract');?></span><i>DOCX</i></a> </div>
          </div>
          
          <!-- Sidebar Widget -->
          <div class="panel mb-4">
            <div class="panel-header">
              <h4><?php echo __('profileview_profile_share','Share');?></h4>
            </div>
            <div class="panel-body">                             
              <!-- Copy URL -->
              <div class="input-group copy-url mb-3">
                <input type="text" id="copy-url-profile" value="" class="form-control">
                <button class="btn copy-url-button" data-clipboard-target="#copy-url-profile" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
              </div>
              
              <!-- Share Buttons -->
              <div class="freelancer-socials">
                  <ul class="social-links">
                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $profile_url;?>" target="_blank" title="Share on Facebook" data-tippy-placement="top"><i class="icon-brand-facebook-f"></i></a></li>
                    <li><a href="https://twitter.com/home?status=<?php echo $profile_url;?>" target="_blank" title="Share on Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
                    <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $profile_url;?>&title=&summary=&source=" target="_blank" title="Share on LinkedIn" data-tippy-placement="top"><i class="icon-brand-linkedin-in"></i></a></li>
                   </ul>
                  <!-- Bookmark icon -->
              	  <!-- <span class="bookmark-icon"></span> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
    <div class="text-center padding-top-50 padding-bottom-50"><?php load_view('inc/spinner',array('size'=>30));?></div>
    </div>

  </div>
</div>
<script type="text/javascript">
	var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
	function load_data(type){
		$( "#profile-"+type+"-data").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();
		$.get( "<?php D(get_link('editprofileloadDataAJAXURL').'/'.md5($member_id).'/');?>",{'type':type}, function( data ) {
			setTimeout(function(){ $( "#profile-"+type+"-data").html( data );
			loadtooltip();
			},2000)
		});
	}
	function loadtooltip(){
		tippy('[data-tippy-placement]', {
			delay: 100,
			arrow: true,
			arrowType: 'sharp',
			size: 'regular',
			duration: 200,
			// 'shift-toward', 'fade', 'scale', 'perspective'
			animation: 'shift-away',
			animateFill: true,
			theme: 'dark',
			// How far the tooltip is from its reference element in pixels 
			distance: 10,
		});
	}
		
</script>
<?php if($is_editable){?>
<script type="text/javascript">
var all_skills=<?php D(json_encode($all_skills));?>;
var  main = function(){
var bh = new Bloodhound({
	local:all_skills,
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('skill_name'),
	queryTokenizer: Bloodhound.tokenizers.whitespace,
});

$('#edit-profile-page').on('click','.edit_account_btn',function(){
		$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
		$('#myModal').modal('show');
		var formtype=$(this).attr('data-popup');
		$.get( "<?php echo get_link('editprofileAJAXURL')?>",{'formtype':formtype,'Okey':$(this).attr('data-popup-id')}, function( data ) {
			setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );$('.selectpicker').selectpicker('refresh');if(formtype=='skill'){load_tag_input(bh);}},1000)
		});
	});
$('#edit-profile-page').on('click','.delete_account_btn',function(){
		var sec=$(this);
		var formtype=sec.attr('data-popup');
		if(formtype=='language'){
			var title="Remove language";
			var message="Remove the following language";
		}else if(formtype=='employment'){
			var title="Remove employment";
			var message="Remove the following employment";
		}else if(formtype=='education'){
			var title="Remove education";
			var message="Remove the following education";
		}else if(formtype=='portfolio'){
			var title="Remove portfolio";
			var message="Remove the following portfolio";
		}
		bootbox.confirm({
			title:title,
		    message:message,
		    size: 'small',
		    buttons: {
		        confirm: {
		            label: "Yes",
		            className: 'btn btn-success float-end'
		        },
		        cancel: {
		            label: "No",
		            className: 'btn btn-dark float-start'
		        }
		    },
		    callback: function (result) {
		    	if(result==true){
		      		$.post( "<?php echo get_link('deleteprofileDataAJAXURL')?>",{'formtype':formtype,'Okey':sec.attr('data-popup-id')}, function( data ) {
			sec.closest('.'+formtype+'-contain').slideUp('slow');
		});	
		       	}
		    }
		});
		
	});
load_data('language');
load_data('employment');
load_data('education');
load_data('skill');
load_data('portfolio');
load_data('reviews');
}
function SaveHeading(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="headingform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#profile-heading-data').html(msg['msg_heading']);
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function SaveOverview(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="overviewform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#profile-overview-data').html(msg['msg_overview']);
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function SaveHourly(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="hourlyform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#profile-hourly-data').html(msg['msg_hourly']);
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function SaveAvailability(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="availabilityform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#profile-availability-data').html(msg['msg_availability']);
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function SaveLanguage(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="languageform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					//$('#profile-language-data').html(msg['msg_hourly']);
					load_data('language');
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function SaveEmployment(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="employmentform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					//$('#profile-language-data').html(msg['msg_hourly']);
					load_data('employment');
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function SaveEducation(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="educationform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					//$('#profile-language-data').html(msg['msg_hourly']);
					load_data('education');
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function SaveSkill(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="skillform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					//$('#profile-language-data').html(msg['msg_hourly']);
					load_data('skill');
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function SavePortfolio(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="portfolioform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('editprofileFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					//$('#profile-language-data').html(msg['msg_hourly']);
					load_data('portfolio');
					$('#myModal').modal('hide');
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
	function updateprofilepercent(){}
</script>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="text-center padding-top-50 padding-bottom-50">
        <?php load_view('inc/spinner',array('size'=>30));?>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="avatar-modal-profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index: 10000" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content mycustom-modal">
      <form class="avatar-form" action="<?php D(get_link('editprofileFormCheckAJAXURL'))?>" enctype="multipart/form-data" method="post">
        <input  type="hidden" value="logo" id="formtype" name="formtype"/>
        <div class="modal-header"> 
          <!-- <button type="submit" class="btn btn-success float-end avatar-save">Done</button>-->
          <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
          <h4 class="modal-title"><?php echo __('profileview_profile_avatar','Change Avatar');?></h4>
          <button  class="btn btn-success float-end avatar-save" type="submit"><?php echo __('profileview_save','Save');?></button>
        </div>
        <div class="modal-body">
          <div class="avatar-body"> 
            <!-- Upload image and data -->
            <div class="avatar-upload">
              <input type="hidden" class="avatar-src" name="avatar_src">
              <input type="hidden" class="avatar-data" name="avatar_data">
              <label for="avatarInput"><?php echo __('profileview_logo_profile_pic','Profile Picture');?> </label>
              <div class="uploadButton margin-top-0">
                <input class="uploadButton-input avatar-input" type="file" id="avatarInput" name="avatar_file">
                <label class="uploadButton-button" for="avatarInput"><?php echo __('profileview_logo_upload_files','Upload Files');?></label>
                <span class="uploadButton-file-name"><?php echo __('profileview_logo_max_size','Maximum file size: 2 MB');?></span> </div>
            </div>
            <p class="green-text"><?php echo __('profileview_logo_file_format','File must be gif, jpg, png, jpeg.');?></p>
          </div>
          
          <!-- Crop and preview -->
          <div class="row">
            <div class="col-md-9">
              <div class="avatar-crop-wrapper"></div>
            </div>
            <div class="col-md-3">
              <div class="avatar-preview preview-lg"></div>
              <div class="avatar-preview preview-md d-none"></div>
              <div class="avatar-preview preview-sm d-none"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php }else{?>
<script type="text/javascript">
function check_login(succ, fail){
	$.get('<?php echo get_link('IsLoginURL'); ?>', function(res){
		if(res == 1){
			if(typeof succ == 'function'){
				succ();
			}
		}else{
			if(typeof fail == 'function'){
				fail();
			}
		}
	});
}
var  main = function(){
	$('#edit-profile-page').on('click','.edit_account_btn',function(){
		$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
		$('#myModal').modal('show');
		var formtype=$(this).attr('data-popup');
		$.get( "<?php echo get_link('editprofileAJAXURL')?>",{'formtype':formtype,'Okey':$(this).attr('data-popup-id')}, function( data ) {
			setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );$('.selectpicker').selectpicker('refresh');if(formtype=='skill'){load_tag_input(bh);}},1000)
		});
	});
	load_data('language');
	load_data('employment');
	load_data('education');
	load_data('skill');
	load_data('portfolio');
	load_data('reviews');

	$('.action_favorite').on('click',function(e){
		e.preventDefault();
		var _self=$(this);
		var data = {
			mid: _self.data('mid'),
		};
		$.post('<?php echo get_link('actionfavorite_freelancer'); ?>', data, function(res){
			if(res['status'] == 'OK'){
				if(res['cmd']== 'add'){
					_self.addClass('active');
					bootbox.alert({
						title:'<?php echo __('profileview_make_favt','Make Favorite');?>',
						message: '<?php echo __('profileview_successfuly_save','Successfully Saved');?>',
						buttons: {
						'ok': {
							label: '<?php echo __('profileview_ok','Ok');?>',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							
					    }
					});
				}else{
					_self.removeClass('active');
					bootbox.alert({
						title:'<?php echo __('profileview_remove_favorite','Remove Favorite');?>',
						message: '<?php echo __('profileview_remove_msg','Successfully Removed');?>',
						buttons: {
						'ok': {
							label: '<?php echo __('profileview_ok','Ok');?>',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							
					    }
					});
					
				}
			}else if(res['popup'] == 'login'){
				bootbox.confirm({
					title:'Login Error!',
					message: 'You are not Logged In. Please login first.',
					buttons: {
					'confirm': {
						label: 'Login',
						className: 'btn-primary float-end'
						},
					'cancel': {
						label: 'Cancel',
						className: 'btn-dark float-start'
						}
					},
					callback: function (result) {
						if(result){
							var base_url = '<?php echo base_url();?>';
							var refer = window.location.href.replace(base_url, '');
							location.href = '<?php echo base_url('login?refer='); ?>'+refer;
						}
					}
				});
			}
		},'JSON');
		
	})
	$('.hire-member').on('click',function(e){
		e.preventDefault();
		var _self=$(this);
		var data = {
			mid: _self.data('mid'),
			formtype:'hire'
		};
		var hiremember = function(){
				$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
				$('#myModal').modal('show');
				
				$.get("<?php echo get_link('HireInviteFreelanceFormURL'); ?>",data, function( data ) {
					setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );$('.selectpicker').selectpicker('refresh');},1000)
				});
		};
		var login_error = function(){
			bootbox.confirm({
				title:'<?php D(__('project_view_Save_Search_login_error','Login Error!'));?>',
				message: '<?php D(__('project_view_Save_Search_you_are_not_logged_in','You are not Logged In. Please login first.'));?>',
				buttons: {
				'confirm': {
					label: '<?php D(__('project_view_Save_Search_you_are_not_logged_in','You are not Logged In. Please login first.'));?>',
					className: 'btn-primary float-end'
					},
				'cancel': {
					label: '<?php D(__('project_view_save_search_button_cancel','Cancel'));?>',
					className: 'btn-dark float-start'
					}
				},
				callback: function (result) {
					if(result){
						var base_url = '<?php echo base_url();?>';
						var refer = window.location.href.replace(base_url, '');
						location.href = '<?php echo base_url('login?refer='); ?>'+refer;
					}
				}
			});
		};

		check_login(hiremember, login_error);
		
	})
	$('.invite-member').on('click',function(e){
		e.preventDefault();
		var _self=$(this);
		var data = {
			mid: _self.data('mid'),
			formtype:'invite'
		};
		var invitemember = function(){
				$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
				$('#myModal').modal('show');
				
				$.get("<?php echo get_link('HireInviteFreelanceFormURL'); ?>",data, function( data ) {
					setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );$('.selectpicker').selectpicker('refresh');},1000)
				});
		};
		var login_error = function(){
			bootbox.confirm({
				title:'<?php D(__('project_view_Save_Search_login_error','Login Error!'));?>',
				message: '<?php D(__('project_view_Save_Search_you_are_not_logged_in','You are not Logged In. Please login first.'));?>',
				buttons: {
				'confirm': {
					label: '<?php D(__('project_view_Save_Search_you_are_not_logged_in','You are not Logged In. Please login first.'));?>',
					className: 'btn-primary float-end'
					},
				'cancel': {
					label: '<?php D(__('project_view_save_search_button_cancel','Cancel'));?>',
					className: 'btn-dark float-start'
					}
				},
				callback: function (result) {
					if(result){
						var base_url = '<?php echo base_url();?>';
						var refer = window.location.href.replace(base_url, '');
						location.href = '<?php echo base_url('login?refer='); ?>'+refer;
					}
				}
			});
		};

		check_login(invitemember, login_error);
		
	})
}
</script>
<?php }?>
