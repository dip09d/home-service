<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>

<div id="dataStep-7" style="display: none">
  <div class="dashboard-box margin-top-0"> 
    <!-- Headline -->
    <div class="headline">
      <h4><?php echo __('postproject_review_post','Review and Post');?> </h4>
    </div>
    <div class="content with-padding">
        <div class="submit-field mb-0">
          <h5><b><?php echo __('postproject_title','Title');?></b> <a href="javascript:void(0)" class="edit-project float-end btn btn-outline-secondary btn-circle" data-popup="1" data-tippy-placement="top" title="Edit title"><i class="icon-feather-edit-2"></i></a></h5>
          <label><?php echo __('postproject_project_name','Name of your project');?></label>
          <p id="preview_title"></p>
          <label><?php echo __('postproject_project_category','Project category');?></label>
          <p id="preview_category"></p>
        </div>

    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
      <h4><?php echo __('postproject_description','Description');?> <a href="javascript:void(0)" class="edit-project float-end btn btn-outline-secondary btn-circle" data-popup="2" data-tippy-placement="top" title="Edit Description"><i class="icon-feather-edit-2"></i></a></h4>
    </div>
    <div class="content with-padding">
      <div class="submit-field mb-0">        
        <label><?php echo __('postproject_description','Description');?></label>
        <p id="preview_description"><?php echo __('postproject_test_check','test check');?></p>
        <div id="preview_attachment_sec" style="display: none">
        <label><?php echo __('postproject_attachment','Attachment');?></label>
        <p id="preview_attachment"></p>
        </div>
      </div>
    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
    <h4><?php echo __('postproject_details','Details');?> <a href="javascript:void(0)" class="edit-project float-end btn btn-outline-secondary btn-circle" data-popup="3" data-tippy-placement="top" title="Edit Details"><i class="icon-feather-edit-2"></i></a></h4>
    </div>
    <div class="content with-padding">
      <div class="submit-field">        
        <label><?php echo __('postproject_project_type','Type of project');?></label>
        <p id="preview_projectType"></p>
        <div id="preview_attachment_sec" style="display: none">
        <label><?php echo __('postproject_attachment','Attachment');?></label>
        <p id="preview_attachment"></p>
        </div>
      </div>
      <div class="submit-field">
        <label><?php echo __('postproject_screen_question','Screen question (optional)');?></label>
        <div id="preview_question_sec" style="display: none"></div>
      </div>
      <div class="submit-field mb-0">  
        <label><?php echo __('postproject_cover_letter','Required cover letter');?></label>
        <p id="preview_is_cover_required"></p>
      </div>
    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
    	<h4><?php echo __('postproject_expertise','Expertise');?> <a href="javascript:void(0)" class="edit-project float-end btn btn-outline-secondary btn-circle" data-popup="4" data-tippy-placement="top" title="Edit Expertise"><i class="icon-feather-edit-2"></i></a></h4>
    </div>
    <div class="content with-padding">
		<div class="task-tags" id="preview_skills"></div>
    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
    	<h4><?php echo __('postproject_visibility','Visibility');?> <a href="javascript:void(0)" class="edit-project float-end btn btn-outline-secondary btn-circle" data-popup="5" data-tippy-placement="top" title="Edit Visibility"><i class="icon-feather-edit-2"></i></a></h4>
    </div>
    <div class="content with-padding pb-0">      
      <div class="row">
        <div class="col-xl-6">
            <div class="submit-field">        
                <label><?php echo __('postproject_project_visibility','Project visibility');?></label>
                <p id="preview_projectVisibility"></p>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="submit-field"> 
                <label><?php echo __('postproject_freelancer_need','Freelancer need');?></label>
                <p id="preview_no_of_freelancer"></p>
      		</div>
        </div>
      </div>
    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
    <h4><?php echo __('postproject_budget','Budget');?> <a href="javascript:void(0)" class="edit-project float-end btn btn-outline-secondary btn-circle" data-popup="6" data-tippy-placement="top" title="Edit Budget"><i class="icon-feather-edit-2"></i></a></h4>
    </div>
    <div class="content with-padding pb-0">      
      <div class="row">
        <div class="col-xl-6">
          <div class="submit-field">
            <label class="form-label"><?php echo __('postproject_h_fixed_price','Hourly or Fixed price');?></label>
            <p id="preview_projectPaymentType"></p>
          </div>
        </div>
        <div class="col-xl-6">
          <div class="submit-field">
            <label class="form-label"><?php echo __('postproject_experience_level','Experience level');?></label>
            <p id="preview_experience_level"></p>
          </div>
        </div>
      </div>
      <div class="row hourly_project_display" style="display: none">
        <div class="col-xl-6">
          <div class="submit-field">
            <label class="form-label"><?php echo __('postproject_project_duration','Project Duration');?></label>
            <p id="preview_hourly_duration"></p>
          </div>
        </div>
        <div class="col-xl-6">
          <div class="submit-field">
            <label class="form-label"><?php echo __('postproject_project_time','Project Time');?></label>
            <p id="preview_hourly_duration_time"></p>
          </div>
        </div>
      </div>
    </div>    
  </div>
  	<button class="btn btn-secondary backbtnproject" data-step="7"><?php echo __('postproject_back','Back');?></button>
	<button class="btn btn-primary nextbtnproject" data-step="7"><?php echo __('postproject_post','Post');?></button>
</div>
