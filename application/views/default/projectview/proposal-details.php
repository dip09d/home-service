<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//get_print($proposaldetails,FALSE);
//get_print($projects,FALSE);
$is_hourly=$projects['project_settings']->is_hourly;
$HireApplicationProjecURL=get_link('HireProjectURL')."/".md5($proposaldetails['proposal']->project_id)."/".md5($proposaldetails['proposal']->member_id);
$CreateMessageRoomURL=get_link('CreateMessageRoomURL')."/".md5($proposaldetails['proposal']->project_id)."/".md5($proposaldetails['proposal']->member_id);
?>

<div id="edit-profile-page">
  <?php
$logo=getMemberLogo($member_id);
?>
  <!-- Titlebar
================================================== -->
<div class="single-page-header freelancer-header" data-background-image="<?php // echo IMAGE;?>">
    <div class="container">
      <div class="single-page-header-inner">
        <div class="start-side"><!--<?php echo IMAGE;?>default-member-logo.svg-->
          <div class="header-image freelancer-avatar" id="" style="position: relative">
            <ec id="crop-avatar-dashboard" style="width: 100%">
              <input type="hidden" name="logo" id="logo" class="replceLogoVal">
              <img src="<?php D($logo);?>" alt=""></ec>
          </div>
          <div class="header-details">
            <h1>
              <?php D(ucwords($memberInfo->member_name))?>
              <span class="show_edit_btn">
              <ec id="profile-heading-data">
                <?php D(ucfirst($memberInfo->member_heading))?>
              </ec>
              </span>
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
				<img src="<?php echo $badge_icon;?>" alt="<?php echo $badge->name;?>" height="24" width="24" data-tippy-placement="top" title="<?php echo $badge->name;?>"  />
				<?php	
				}
				?>
				</li>
				<?php
				}
              	?>
              <li>
                <?php if($memberInfo->country_code_short){?>
                <img class="flag" src="<?php echo IMAGE;?>flags/<?php D(strtolower($memberInfo->country_code_short));?>.svg" alt="<?php D(ucfirst($memberInfo->country_name))?>">
                <?php }?>
                <?php D(ucfirst($memberInfo->country_name))?>
              </li>
              <!--<li>
                <div class="verified-badge-with-title">Verified</div>
              </li>-->
            </ul>
          </div>
        </div>
        <div class="end-side">
          <div class="ms-auto" style="min-width: 150px;">
          	<p class="mb-0"><?php echo __('projectview_proposal_job_success','Job Success');?> <strong><?php echo $memberInfo->success_rate;?>%</strong></p>
            <div class="progress" style="max-width:200px; height:6px">
              <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?php echo $memberInfo->success_rate;?>%" aria-valuenow="<?php echo $memberInfo->success_rate;?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
</div>
  
<section class="section">
  <div class="container">
    <div class="row"> 
      <!-- Content -->
      <div class="col-xl-8 col-lg-8"> 
        
        <!-- Page Content -->
        <div class="panel mb-4">
          <div class="panel-header">
            <h4 class="panel-title"><?php echo __('projectview_proposal_about','About');?> </h4>
          </div>
          <div class="panel-body">
            <ec id="profile-overview-data">
              <?php D(ucfirst(nl2br($memberInfo->member_overview)))?>
            </ec>
          </div>
        </div>
        <div class="panel mb-4">
          <div class="panel-header">
            <h4 class="panel-title"><?php echo __('projectview_proposal_cover_letter','Cover Letter');?> </h4>
          </div>
          <div class="panel-body">
            <ec id="profile-overview-data">
              <?php D(ucfirst(nl2br($proposaldetails['proposal']->bid_details)))?>
            </ec>
          </div>
        </div>
        <?php if($is_hourly){}else{
            	if($proposaldetails['proposal']->bid_by_project!=1){
            		if($proposaldetails['proposal']->project_bid_milestones){
            		?>
        
          
        <div class="boxed-list mb-4">
            <div class="boxed-list-headline">
                <h3><?php echo __('projectview_proposal_milestone','Milestone');?></h3>
            </div>
            <ul class="boxed-list-ul">
              <?php foreach($proposaldetails['proposal']->project_bid_milestones as $k=>$val){?>
              <li class="milestone-contain">
                <div class="boxed-list-item"> 
                  <!-- Content -->
                  <div class="item-content">
                    <h4><?php echo ucfirst($val->bid_milestone_title);?></h4>
                    <div class="item-details margin-top-7">
                      <div class="detail-item"><i class="icon-material-outline-account-balance-wallet"></i> <?php echo __('projectview_proposal_amount','Amount:');?> <?php echo priceSymbol().priceFormat($val->bid_milestone_amount);?></div>
                      <div class="detail-item"><i class="icon-material-outline-date-range"></i><?php echo __('projectview_proposal_due_date','Due date:');?>  <?php echo $val->bid_milestone_due_date;?></div>
                    </div>
                  </div>
                </div>
              </li>
              <?php }?>
              <li class="milestone-contain">
              	<div class="boxed-list-item"> 
                <h4 class="mb-0"><?php echo __('projectview_proposal_total_bids','Total Bids:');?></h4>
                <h4 class="mb-0 ms-auto">
                  <ec id="profile-hourly-data">
                    <?php D(priceSymbol().priceFormat($proposaldetails['proposal']->bid_amount));?>
                  </ec>
                </h4></div>
              </li>
            </ul>
        </div>
        
        <?php }}}?>
        <?php if($proposaldetails['project_question']){?>
        <div class="panel mb-4">
          <div class="panel-header">
            <h4 class="panel-title"><?php echo __('projectview_proposal_question','Question');?> </h4>
          </div>
          <div class="panel-body">
            <?php
				foreach($proposaldetails['project_question'] as $k=>$val){
				?>
            <div class="form-group">
              <label><b><?php echo $k+1;?>. <?php echo $val->question_title;?></b></label>
              <p><?php echo $val->question_answer;?></p>
            </div>
            <?php		
					}
				?>
          </div>
        </div>
        <?php }?>
        <?php if($proposaldetails['proposal']->bid_attachment){?>
        <div class="panel mb-4">
          <div class="panel-header">
            <h4 class="panel-title"><?php echo __('projectview_proposal_attachment','Attachments');?> </h4>
          </div>
          <div class="panel-body">
            <div class="attachments-container">
              <?php
				$attachments=json_decode($proposaldetails['proposal']->bid_attachment);
				foreach($attachments as $k=>$val){
					if($val->file && file_exists(UPLOAD_PATH.'projects-files/projects-applications/'.$val->file)){
						$path_parts = pathinfo($val->name);
				?>
              <a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/projects-applications/'.$val->file;?>" target="_blank" class="attachment-box "><span><?php echo $path_parts['filename'];?></span><i><?php echo strtoupper($path_parts['extension']);?></i><i class="icon-feather-download"></i></a>
              <?php
					}	
				}
				?>
            </div>
          </div>
        </div>
        <?php }?>
        
               <!-- Boxed List -->
        <div class="boxed-list mb-4">
          <div class="boxed-list-headline">
            <h3><?php echo __('projectview_proposal_work_history','Work History and Feedback');?></h3>
          </div>
			  <div id="profile-reviews-data"></div>
          

          <div class="clearfix"></div>
          <!-- Pagination / End --> 
          
        </div>
        <!-- Boxed List / End --> 
        <!-- Boxed List -->
        
        <div class="boxed-list mb-4">
          <div class="boxed-list-headline">
            <h4><?php echo __('projectview_proposal_portfolio','Portfolio');?> </h4>
          </div>
          <ul class="boxed-list-ul">
            <li>
              <div id="profile-portfolio-data"></div>
            </li>
          </ul>
        </div>
        <!-- Boxed List / End --> 
        <!-- Boxed List -->
        <div class="boxed-list mb-4">
          <div class="boxed-list-headline">
            <h3><?php echo __('projectview_proposal_employment_history','Employment History');?> </h3>
          </div>
          <div id="profile-employment-data"> </div>
        </div>
        <!-- Boxed List / End --> 
        <!-- Boxed List -->
        <div class="boxed-list mb-4">
          <div class="boxed-list-headline">
            <h3><?php echo __('projectview_proposal_education','Education');?> </h3>
          </div>
          <div id="profile-education-data"> </div>
        </div>
        <!-- Boxed List / End --> 
        
      </div>
      
      <!-- Sidebar -->
      <div class="col-xl-4 col-lg-4">
        <div class="sidebar-container">
          <div class="panel mb-4">
            <div class="panel-body">
              <?php if($proposaldetails['proposal']->is_hired){?>
              <?php }else{?>
              <?php if($proposaldetails['proposal']->is_archive){?>
              <a href="<?php D(VZ);?>" data-btn="unarchive-btn" class="proposal-btn btn btn-secondary btn-block" title="Remove Bid" data-tippy-placement="top"><i class="icon-feather-trash"></i> <?php echo __('projectview_proposal_unarchive','Unarchive');?> </a>
              <?php }elseif($proposaldetails['proposal']->is_shortlisted){?>
              <a href="<?php D(VZ);?>" data-btn="hire-btn"  class="proposal-btn btn btn-success btn-block"><i class="icon-material-outline-check"></i> <?php echo __('projectview_hire','Hire');?> </a> <a href="<?php D(VZ);?>" data-btn="interview-btn" class="proposal-btn btn btn-info btn-block"><i class="icon-feather-phone"></i> <?php echo __('projectview_bid_interview','Interview');?></a> <a href="<?php D(VZ);?>" data-btn="archive-btn" class="proposal-btn btn btn-danger btn-block" title="Remove Bid" data-tippy-placement="top"><i class="icon-feather-trash"></i> <?php echo __('projectview_bid_archive','Archive');?> </a>
              <?php }elseif($proposaldetails['proposal']->is_interview){?>
              <a href="<?php D(VZ);?>" data-btn="hire-btn"  class="proposal-btn btn btn-success btn-block"><i class="icon-material-outline-check"></i> <?php echo __('projectview_hire','Hire');?> </a> <a href="<?php D(VZ);?>" data-btn="archive-btn" class="proposal-btn btn btn-danger btn-block" title="Remove Bid" data-tippy-placement="top"><i class="icon-feather-trash"></i> <?php echo __('projectview_bid_archive','Archive');?> </a>
              <?php }else{?>
              <a href="<?php D(VZ);?>" data-btn="hire-btn"  class="proposal-btn btn btn-success btn-block"><i class="icon-material-outline-check"></i> <?php echo __('projectview_hire','Hire');?> </a> <a href="<?php D(VZ);?>" data-btn="shortlisted-btn" class="proposal-btn btn btn-info btn-block"><i class="icon-feather-star"></i> <?php echo __('projectview_proposal_shortlist','Shortlist');?></a> <a href="<?php D(VZ);?>" data-btn="archive-btn" class="proposal-btn btn btn-danger btn-block" title="Remove Bid" data-tippy-placement="top"><i class="icon-feather-trash"></i> <?php echo __('projectview_bid_archive','Archive');?> </a>
              <?php
                }
              }?>
              <a href="<?php D(VZ);?>" data-btn="message-btn" class="proposal-btn btn btn-primary btn-block"><i class="icon-feather-mail"></i> <?php echo __('projectview_proposal_message','Message');?> </a>
              <div class="mb-3"></div>
              
              <!-- Profile Overview -->
              <ul class="list-group-0">
                <li class="overview-item">
                <span>
                  <?php if($is_hourly){?>
                    <?php echo __('projectview_proposal_hour','Hour');?> 
                  <?php }else{?>
                    <?php echo __('projectview_proposal_bids','Bids');?>
                  <?php }?>
                  </span>
                <strong>
                  <ec id="profile-hourly-data">
                    <?php D(priceSymbol().priceFormat($proposaldetails['proposal']->bid_amount));?>
                  </ec>
                </strong></li>
                <li><span><?php echo __('projectview_proposal_earned','Earned');?></span>
                <strong>
                  <?php D(priceSymbol().priceFormat($memberInfo->total_earning));?>
                  </strong></li>
                <li class="overview-item">
                <span><?php echo __('projectview_proposal_jobs','Jobs');?></span>
                <strong>
                  <?php D($memberInfo->total_jobs);?>
                  </strong>
                </li>
                <li>
                <span><?php echo __('projectview_proposal_total_hour','Total Working Hour');?></span>
                <strong><?php D(displayamount($memberInfo->total_working_hour,2));?></strong>                
                </li>
                <li> <span><?php echo __('projectview_proposal_availability','Availability');?></span> 
                <strong>
                  <ec id="profile-availability-data">
                    <?php if($memberInfo->not_available_until){
                    		echo 'Offline till '.dateFormat($memberInfo->not_available_until);
                    	}elseif($memberInfo->available_per_week){
	                    	$duration=getAllProjectDurationTime($memberInfo->available_per_week);
	                    	D($duration['freelanceName']);
                    	}else{
                    		D('Not set');
                    	}?>
                  </ec>
                  </strong></li>
              </ul>
            </div>
          </div>
          <!-- Freelancer Indicators -->
          <div class="sidebar-widget d-none">
            <div class="freelancer-indicators"> 
              
              <!-- Indicator -->
              <div class="indicator"> <strong>88%</strong>
                <div class="indicator-bar" data-indicator-percentage="88"><span></span></div>
                <span><?php echo __('projectview_proposal_job_success','Job Success');?></span> </div>
              
              <!-- Indicator -->
              <div class="indicator"> <strong>100%</strong>
                <div class="indicator-bar" data-indicator-percentage="100"><span></span></div>
                <span><?php echo __('projectview_proposal_recommendation','Recommendation');?></span> </div>
              
              <!-- Indicator -->
              <div class="indicator"> <strong>90%</strong>
                <div class="indicator-bar" data-indicator-percentage="90"><span></span></div>
                <span><?php echo __('projectview_proposal_on_time','On Time');?></span> </div>
              
              <!-- Indicator -->
              <div class="indicator"> <strong>80%</strong>
                <div class="indicator-bar" data-indicator-percentage="80"><span></span></div>
                <span><?php echo __('projectview_proposal_on_budget','On Budget');?></span> </div>
            </div>
          </div>
          <div class="panel mb-4">
            <div class="panel-header">
              <h4><?php echo __('projectview_proposal_language','Language');?> </h4>
            </div>
            <div class="panel-body" id="profile-language-data"> </div>
          </div>
          <!-- Widget -->
          <div class="panel mb-4 d-none">
            <div class="panel-header">
              <h3><?php echo __('projectview_proposal_social_profiles','Social Profiles');?></h3>
            </div>
            <div class="panel-body freelancer-socials">
              <ul>
                <li><a href="#" title="Dribbble" data-tippy-placement="top"><i class="icon-brand-dribbble"></i></a></li>
                <li><a href="#" title="Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
                <li><a href="#" title="Behance" data-tippy-placement="top"><i class="icon-brand-behance"></i></a></li>
                <li><a href="#" title="GitHub" data-tippy-placement="top"><i class="icon-brand-github"></i></a></li>
              </ul>
            </div>
          </div>
          
          <!-- Widget -->
          <div class="panel mb-4">
            <div class="panel-header">
              <h4><?php echo __('projectview_proposal_skills','Skills');?> </h4>
            </div>
            <div class="panel-body task-tags" id="profile-skill-data"> </div>
          </div>
          
          <!-- Widget -->
          <div class="sidebar-widget d-none">
            <h3><?php echo __('projectview_proposal_attachment','Attachments');?></h3>
            <div class="attachments-container"> <a href="#" class="attachment-box"><span><?php echo __('projectview_proposal_cover_letter','Cover Letter');?></span><i><?php echo __('projectview_proposal_pdf','PDF');?></i></a> <a href="#" class="attachment-box"><span><?php echo __('projectview_proposal_contract','Contract');?></span><i><?php echo __('projectview_proposal_docx','DOCX');?></i></a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function view_application(application_url){
	var redirectWindow = window.open(application_url, '_blank');
    redirectWindow.location;
	/*$('#myModal').modal('show');
	$.get( application_url,{}, function( data ) {
			setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );},1000)
	});*/
}
function make_shortlist(application_id,section){
	var formtype="shortlist";
	var buttonsection=section;
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.post( "<?php echo get_link('myProjectBidsClientStatusAjaxURL');?>",{'formtype':formtype,'project_id':"<?php D($projects['project_id']);?>",'application_id':application_id}, function( data ) {
		location.reload();
		//buttonsection.html(buttonval.replace('Shortlist','Shortlisted')).removeAttr('disabled');
		//buttonsection.removeClass('dark').addClass('green disabled');	
	});
}
function make_interview(application_id,section){
	var formtype="interview";
	var buttonsection=section;
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.post( "<?php echo get_link('myProjectBidsClientStatusAjaxURL');?>",{'formtype':formtype,'project_id':"<?php D($projects['project_id']);?>",'application_id':application_id}, function( data ) {
		location.reload();
		//buttonsection.html(buttonval.replace('Interview','Interview')).removeAttr('disabled');
		//buttonsection.removeClass('dark').addClass('green disabled');	
	});
}
function make_archive(application_id,section){
	var formtype="archive";
	var buttonsection=section;
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.post( "<?php echo get_link('myProjectBidsClientStatusAjaxURL');?>",{'formtype':formtype,'project_id':"<?php D($projects['project_id']);?>",'application_id':application_id}, function( data ) {
		location.reload();
		//buttonsection.html(buttonval.replace('Archive','Archived')).removeAttr('disabled');
		//$('#application-id-'+application_id).slideUp('slow');	
	});
}
function remove_archive(application_id,section){
	var formtype="unarchive";
	var buttonsection=section;
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	$.post( "<?php echo get_link('myProjectBidsClientStatusAjaxURL');?>",{'formtype':formtype,'project_id':"<?php D($projects['project_id']);?>",'application_id':application_id}, function( data ) {
		location.reload();
		//buttonsection.html(buttonval.replace('Unarchive','Unarchived')).removeAttr('disabled');
		//$('#application-id-'+application_id).slideUp('slow');	
	});
}
function send_message(application_id,section){
	var redirectWindow = window.open("<?php echo $CreateMessageRoomURL;?>", '_blank');
    redirectWindow.location;
}
function make_hire(application_id,section){
	var redirectWindow = window.open("<?php echo $HireApplicationProjecURL;?>", '_blank');
    redirectWindow.location;
}
</script> 
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
<script type="text/javascript">
var  main = function(){
	
	load_data('language');
	load_data('employment');
	load_data('education');
	load_data('skill');
	load_data('portfolio');
	load_data('reviews');
	
	$('.proposal-btn').click(function(){
		var section=$(this);
		var btn= section.attr('data-btn');
		var application_id='<?php echo $proposaldetails['proposal']->bid_id;?>';
		if(btn=='application-dtl'){
			var application_url=section.attr('data-href');
			view_application(application_url);
		}else if(btn=='shortlisted-btn'){
			make_shortlist(application_id,section);
		}else if(btn=='unarchive-btn'){
			remove_archive(application_id,section);
		}else if(btn=='archive-btn'){
			make_archive(application_id,section);
		}else if(btn=='message-btn'){
			send_message(application_id,section);
		}else if(btn=='interview-btn'){
			make_interview(application_id,section);
		}else if(btn=='hire-btn'){
			make_hire(application_id,section);
		}
		//alert(btn);
	})
	
}
</script>
<script src="<?php echo JS;?>rating/star-rating.js"></script>