<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($projectData,TRUE);
$ProjectDetailsURL=get_link('myProjectDetailsURL')."/".$projectData['project']->project_url;
$ProjectApplicationURL=get_link('myProjectDetailsBidsClientURL')."/".$projectData['project']->project_id;
$ApplyProjecURL=get_link('ApplyProjectURL')."/".$projectData['project']->project_url;
?>
<div class="single-page-header">
	<div class="container">
		<div class="single-page-header-inner">
					<div class="start-side">
						
						<div class="header-details">
							<h1><?php D(ucfirst($projectData['project']->project_title));?></h1>
							<p><?php D($projectData['project_category']->category_subchild_name);?>, <?php D($projectData['project_category']->category_name);?></p>

						</div>
					</div>
					<?php if($projectData['project_settings']->is_fixed==1){?>
					<div class="end-side">
						<div class="salary-box">
							<div class="salary-type"><?php echo __('projectview_apply_fixed_budget','Fixed Budget');?></div>
							<div class="salary-amount"><?php D(priceSymbol().priceFormat($projectData['project_settings']->budget));?></div>
						</div>
					</div>
					<?php }?>
				</div>
	</div>
</div>
<section class="section">
<div class="container">
	<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link" href="<?php echo $ProjectDetailsURL;?>"><?php echo __('projectview_bid_details','Details');?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link active" href="<?php echo $ProjectApplicationURL;?>"><?php echo __('projectview_bid_application','Applications');?></a>
	</li>
	</ul>

	<!-- Dashboard Headline -->
	<div class="dashboard-headline">
		<h3><?php echo __('projectview_bid_manage_proposal','Manage Proposals');?></h3>
	</div>
    
					
					
	<ul class="nav nav-tabs mb-3" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#proposal" role="tab"><?php echo __('projectview_bid_proposal','Proposal');?> <span id="show_count_total_proposal"></span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#archive" role="tab"><?php echo __('projectview_bid_archive','Archive');?> <span id="show_count_archive_proposal"></span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#shortlisted" role="tab"><?php echo __('projectview_bid_shortlisted','Shortlisted');?> <span id="show_count_shortlisted_proposal"></span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#interview" role="tab"><?php echo __('projectview_bid_interview','Interview');?> <span id="show_count_interview_proposal"></span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#invite" role="tab"><?php echo __('projectview_bid_invite','Invite');?> <span id="show_count_invite_proposal"></span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#hired" role="tab"><?php echo __('projectview_hire','Hire');?> <span id="show_count_hired_proposal"></span></a>
		</li>
	</ul>


	<div class="sort-by mb-3 d-none">
		<div class="sort-group">
		<div class="input-group">
			<input type="text" class="form-control" placeholder="Search for freelancers" />
			<button class="btn btn-primary"><i class="icon-feather-search"></i></button>
		</div>
		</div>
		
		<div class="sort-by">
		<span><?php echo __('projectview_bid_sort_by','Sort by:');?></span>
		<select class="selectpicker hide-tick">
			<option><?php echo __('projectview_bid_relevance','Relevance');?></option>
			<option><?php echo __('projectview_bid_newest','Newest');?></option>
			<option><?php echo __('projectview_bid_oldest','Oldest');?></option>
			<option><?php echo __('projectview_bid_random','Random');?></option>
		</select>
		</div>
		<div class="sort-group">
			<a href="javascript:void(0)" class="btn btn-outline-success"><i class="icon-feather-filter"></i><?php echo __('projectview_bid_filter','Filter');?> </a>
		</div>
		<div class="sort-group text-right">
		<span><?php echo __('projectview_bid_view_by','View by:');?></span>
		<a href="#" class="btn btn-outline-success"><i class="icon-feather-grid"></i></a> &nbsp;
		<a href="#" class="btn btn-outline-success active"><i class="icon-feather-list"></i></a>
		</div>
		
	</div>


	<div class="tab-content dashboard-box_ margin-top-0 mb-4">
		<div class="tab-pane active" id="proposal" role="tabpanel"><?php echo __('projectview_bid_proposal','proposal');?></div>
		<div class="tab-pane" id="archive" role="tabpanel"><?php echo __('projectview_bid_archive','archive');?></div>
		<div class="tab-pane" id="shortlisted" role="tabpanel"><?php echo __('projectview_bid_shortlisted','shortlist');?></div>
		<div class="tab-pane" id="interview" role="tabpanel"><?php echo __('projectview_bid_interview','interview');?></div>
		<div class="tab-pane" id="invite" role="tabpanel"></div>
		<div class="tab-pane" id="hired" role="tabpanel"><?php echo __('projectview_bid_hired','hired');?></div>
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
<script>
	var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
	var baseURL = '<?php echo get_link('myProjectDetailsBidsClientloadAjaxURL');?>/<?php echo $projectData['project']->project_id;?>';
	function load_count(project_id){
		$.ajax({
	      type: "POST",
	      dataType:'json',
	      url: '<?php echo get_link('myProjectDetailsBidsClientloadCountAjaxURL');?>/'+project_id,
	      error: function(data){},
	      success: function(data){
	        if(data['status']=='OK'){
	        	var p=data['project'];
	        	for (var key in p){
				    //console.log(key, p[key]);
				    var cnt="";
				    if(p[key]>0){
						cnt="("+p[key]+")";
					}
				    $('#show_count_'+key).html(cnt);
			    }
			}
	      }
  		})
	}
	var main=function(){
	$('#proposal').html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 70px;">'+SPINNER+'</div>');
    

	 $('#proposal').load(baseURL,{type:'proposal'}, function() {
	    $('.mytabs').tab(); //initialize tabs
	});
   
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		$('.tab-pane').empty();
	  var target = $(e.target).attr("href") // activated tab
	  var pattern=/#.+/gi //use regex to get anchor(==selector)
	  var contentID = e.target.toString().match(pattern)[0]; //get anchor 
		$('#'+contentID.replace('#','')).html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 70px;">'+SPINNER+'</div>');
	    $.ajax({
	      type: "POST",
	      data:{type:contentID.replace('#','')},
	      url: '<?php echo get_link('myProjectDetailsBidsClientloadAjaxURL');?>/<?php echo $projectData['project']->project_id;?>',
	      error: function(data){

	      },
	      success: function(data){
	        $(target).html(data);
	        
	      }
  		})
	});
	load_count(<?php D($projectData['project']->project_id);?>);
}

</script>